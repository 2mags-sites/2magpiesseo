# GitHub Actions Deployment Setup for PHP Builder

## Overview
This guide shows how to set up automated deployment from GitHub to cPanel for PHP websites, adapted from the existing site-builder workflow.

## What We're Bringing Over

### From Site-Builder (Working System):
1. **GitHub Actions workflow** using `appleboy/scp-action`
2. **WHM root SSH deployment** (one key for all sites)
3. **Project detection** based on changed files
4. **Sparse checkout** for efficiency
5. **Environment secrets** in GitHub

## Setup Requirements

### 1. GitHub Secrets (Already Configured)
Your GitHub account already has these secrets from site-builder:
- `WHM_HOST` - Your server hostname/IP
- `WHM_USERNAME` - root
- `WHM_SSH_KEY` - Private SSH key for WHM

### 2. Project Structure for PHP Builder
```
php-builder/
├── .github/
│   └── workflows/
│       └── deploy-php-sites.yml
├── projects/
│   ├── project-barringtons/
│   │   └── php-website/        # Deploy this folder
│   ├── project-kershaw/
│   │   └── php-website/        # Deploy this folder
│   └── project-[name]/
│       └── php-website/        # Deploy this folder
```

## The Deployment Workflow

### Basic Version (No JSON Syncing)
```yaml
name: Deploy PHP Websites

on:
  push:
    branches: [ main ]
    paths:
      - 'projects/**/php-website/**'
  workflow_dispatch:
    inputs:
      project:
        description: 'Specific project to deploy'
        required: false
        type: choice
        options:
          - ''
          - 'project-barringtons'
          - 'project-kershaw'

jobs:
  detect-changes:
    runs-on: ubuntu-latest
    outputs:
      projects: ${{ steps.detect.outputs.projects }}
    steps:
    - uses: actions/checkout@v3
      with:
        fetch-depth: 2

    - name: Detect changed projects
      id: detect
      run: |
        if [ "${{ github.event.inputs.project }}" != "" ]; then
          echo "projects=[\"${{ github.event.inputs.project }}\"]" >> $GITHUB_OUTPUT
        else
          CHANGED=$(git diff --name-only HEAD^ HEAD 2>/dev/null | grep '^projects/.*php-website/' | cut -d'/' -f2 | sort -u | jq -R -s -c 'split("\n")[:-1]')
          if [ "$CHANGED" == "[]" ] || [ -z "$CHANGED" ]; then
            echo "projects=[]" >> $GITHUB_OUTPUT
          else
            echo "projects=$CHANGED" >> $GITHUB_OUTPUT
          fi
        fi

  deploy:
    needs: detect-changes
    if: needs.detect-changes.outputs.projects != '[]'
    runs-on: ubuntu-latest
    environment: cpanel-deploy
    strategy:
      matrix:
        project: ${{ fromJson(needs.detect-changes.outputs.projects) }}

    steps:
    - name: 🚀 Checkout code
      uses: actions/checkout@v3
      with:
        sparse-checkout: |
          projects/${{ matrix.project }}/php-website
        sparse-checkout-cone-mode: false

    - name: Set deployment path
      id: set-path
      run: |
        case "${{ matrix.project }}" in
          "project-barringtons")
            echo "target=/home/barringtons/public_html" >> $GITHUB_OUTPUT
            echo "domain=barringtonsfunerals.co.uk" >> $GITHUB_OUTPUT
            ;;
          "project-kershaw")
            echo "target=/home/kershaw/public_html" >> $GITHUB_OUTPUT
            echo "domain=kershawfunerals.co.uk" >> $GITHUB_OUTPUT
            ;;
          *)
            echo "Unknown project: ${{ matrix.project }}"
            echo "target=unknown" >> $GITHUB_OUTPUT
            ;;
        esac

    - name: 📤 Deploy to cPanel
      if: steps.set-path.outputs.target != 'unknown'
      uses: appleboy/scp-action@v0.1.4
      with:
        host: ${{ secrets.WHM_HOST }}
        username: ${{ secrets.WHM_USERNAME }}
        key: ${{ secrets.WHM_SSH_KEY }}
        source: "projects/${{ matrix.project }}/php-website/*"
        target: "${{ steps.set-path.outputs.target }}"
        rm: false
        strip_components: 3  # Removes projects/PROJECT/php-website
```

### Advanced Version (With JSON Sync)
```yaml
name: Deploy PHP Websites with Content Sync

on:
  push:
    branches: [ main ]
    paths:
      - 'projects/**/php-website/**'

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v3

    - name: Detect changed project
      id: detect
      run: |
        PROJECT=$(git diff --name-only HEAD^ HEAD | grep '^projects/' | head -1 | cut -d'/' -f2)
        echo "project=$PROJECT" >> $GITHUB_OUTPUT

    - name: Set deployment config
      id: config
      run: |
        # Set paths based on project
        # ... (same as above)

    - name: 📥 Fetch production JSON files
      run: |
        mkdir -p temp-content
        scp -r -i <(echo "${{ secrets.WHM_SSH_KEY }}") \
          -o StrictHostKeyChecking=no \
          ${{ secrets.WHM_USERNAME }}@${{ secrets.WHM_HOST }}:${{ steps.config.outputs.target }}/content/*.json \
          temp-content/ || echo "No existing content"

    - name: 🔀 Merge JSON files
      run: |
        # For each JSON in temp-content/
        for prod_json in temp-content/*.json; do
          if [ -f "$prod_json" ]; then
            filename=$(basename "$prod_json")
            local_json="projects/${{ steps.detect.outputs.project }}/php-website/content/$filename"

            if [ -f "$local_json" ]; then
              # Merge production (priority) with local (new keys only)
              node -e "
                const fs = require('fs');
                const prod = JSON.parse(fs.readFileSync('$prod_json'));
                const local = JSON.parse(fs.readFileSync('$local_json'));

                function deepMerge(target, source) {
                  for (const key in source) {
                    if (!(key in target)) {
                      target[key] = source[key];
                    } else if (typeof source[key] === 'object' && !Array.isArray(source[key])) {
                      deepMerge(target[key], source[key]);
                    }
                  }
                  return target;
                }

                const merged = deepMerge(prod, local);
                fs.writeFileSync('$local_json', JSON.stringify(merged, null, 2));
              "
            fi
          fi
        done

    - name: 📤 Deploy merged site
      uses: appleboy/scp-action@v0.1.4
      with:
        host: ${{ secrets.WHM_HOST }}
        username: ${{ secrets.WHM_USERNAME }}
        key: ${{ secrets.WHM_SSH_KEY }}
        source: "projects/${{ steps.detect.outputs.project }}/php-website/*"
        target: "${{ steps.config.outputs.target }}"
        rm: false
        strip_components: 3

    - name: 💾 Commit merged JSON back
      run: |
        git config --local user.email "action@github.com"
        git config --local user.name "GitHub Action"
        git add projects/${{ steps.detect.outputs.project }}/php-website/content/*.json
        git diff --quiet && git diff --staged --quiet || git commit -m "Sync: Merged production content for ${{ steps.detect.outputs.project }}"
        git push
```

## Migration Steps

### Phase 1: Basic Deployment (Recommended First)
1. Create `.github/workflows/deploy-php-sites.yml` in php-builder repo
2. Copy the basic workflow above
3. Update project names and paths
4. Test with a single project

### Phase 2: Add JSON Syncing (Optional)
1. Add the fetch/merge steps
2. Create merge script
3. Test extensively in staging

## Key Differences from Site-Builder

| Aspect | Site-Builder | PHP-Builder |
|--------|--------------|-------------|
| **Source** | `projects/*/output/` | `projects/*/php-website/` |
| **Content** | Static HTML | PHP + JSON |
| **Updates** | Overwrite all | Need to preserve JSON |
| **Strip** | 3 levels | 3 levels |

## Testing Deployment

### 1. Test Locally First
```bash
# Dry run to see what would deploy
git diff --name-only HEAD^ HEAD | grep '^projects/.*php-website/'
```

### 2. Manual Trigger
Use workflow_dispatch to test specific projects without pushing changes.

### 3. Verify on Server
```bash
ssh root@yourserver "ls -la /home/barringtons/public_html/"
```

## Adding New Projects

When adding a new PHP project:

1. Add to workflow choice options:
```yaml
options:
  - 'project-newclient'
```

2. Add to deployment paths:
```yaml
"project-newclient")
  echo "target=/home/newclient/public_html" >> $GITHUB_OUTPUT
  echo "domain=newclient.com" >> $GITHUB_OUTPUT
  ;;
```

## Security Notes

- GitHub Secrets are already configured from site-builder
- WHM SSH key provides root access - handle with care
- Consider separate deploy key with limited permissions
- Always test in staging first

## Troubleshooting

**"Permission denied"**
- SSH key is already working for site-builder, should work here too

**"File not found"**
- Check strip_components matches your structure
- Verify source path includes /*

**"JSON merge fails"**
- Ensure Node.js is available in GitHub Actions
- Add error handling to merge script

## Next Steps

1. **Start with basic deployment** (no JSON sync)
2. **Test with project-barringtons** first
3. **Add JSON sync after basic deployment works**
4. **Document client-specific paths** in separate config

## Quick Start Checklist

- [ ] Copy workflow file to `.github/workflows/`
- [ ] Update project names in workflow
- [ ] Update cPanel account paths
- [ ] Test with manual trigger
- [ ] Verify files deployed correctly
- [ ] Add JSON sync (if needed)
- [ ] Document for team