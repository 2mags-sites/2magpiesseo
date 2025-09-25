# PHP Builder Deployment Strategy

## Key Learnings from Barringtons Deployment

### ✅ What Works: Separate Repository Per Client

**Architecture:**
- `php-builder` (PRIVATE) - Your tools, templates, processes
- `barringtons-funeral-services` (PUBLIC) - Just the client's PHP website
- `kershaw-funeral-services` (PUBLIC) - Just another client's website
- etc.

**Benefits:**
1. Each client gets their own deployment pipeline
2. Organization secrets work with public repos
3. Clean separation of concerns
4. No complex path detection needed
5. Client-specific deployment settings

### 🚫 What Doesn't Work

1. **Single repo with multiple projects** - Too complex, secrets don't work with private repos
2. **Including .env in git** - GitHub blocks pushes with secrets
3. **Excluding uploaded images** - These are content, not code

## Correct Deployment Process

### Step 1: Prepare Client Website
```bash
cd "project-CLIENT/php-website"

# Create .gitignore BEFORE git init
cat > .gitignore << 'EOF'
# Environment variables (CRITICAL - must exclude)
.env
.env.local
.env.*.local

# Backups
*.backup
*.bak

# System files
.DS_Store
Thumbs.db

# IDE files
.vscode/
.idea/

# Logs
*.log
error_log

# WordPress (if added later)
blog/
EOF
```

### Step 2: Create GitHub Repository
```bash
# Initialize git
git init

# Create PUBLIC repository in organization
gh repo create 2mags-sites/CLIENT-NAME --public --description "CLIENT business description"

# Add all files EXCEPT .env (it's gitignored)
git add -A
git commit -m "Initial commit - CLIENT NAME website"
```

### Step 3: Add Deployment Workflow
Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]
  workflow_dispatch:

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
    - name: 🚀 Checkout code
      uses: actions/checkout@v3

    - name: 📤 Deploy to server
      uses: appleboy/scp-action@v0.1.4
      with:
        host: ${{ secrets.WHM_HOST }}
        username: ${{ secrets.WHM_USERNAME }}
        key: ${{ secrets.WHM_SSH_KEY }}
        source: "*"
        target: "/home/CPANEL_USER/public_html"
        rm: false

    - name: 🔒 Set correct permissions
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: ${{ secrets.WHM_HOST }}
        username: ${{ secrets.WHM_USERNAME }}
        key: ${{ secrets.WHM_SSH_KEY }}
        script: |
          # Set ownership
          chown -R CPANEL_USER:CPANEL_USER /home/CPANEL_USER/public_html

          # Set directory permissions
          find /home/CPANEL_USER/public_html -type d -exec chmod 755 {} \;

          # Set file permissions
          find /home/CPANEL_USER/public_html -type f -exec chmod 644 {} \;

          # Make uploads writable
          [ -d "/home/CPANEL_USER/public_html/assets/images/uploads" ] && \
            chmod 777 /home/CPANEL_USER/public_html/assets/images/uploads

          # Protect .env
          [ -f "/home/CPANEL_USER/public_html/.env" ] && \
            chmod 600 /home/CPANEL_USER/public_html/.env
```

### Step 4: Push and Deploy
```bash
git branch -M main
git remote add origin https://github.com/2mags-sites/CLIENT-NAME.git
git push -u origin main
```

## Critical Requirements

### Organization Secrets (One-time setup)
In GitHub Organization Settings → Secrets:
- `WHM_HOST` - Your server IP/hostname
- `WHM_USERNAME` - root
- `WHM_SSH_KEY` - Private SSH key

**IMPORTANT:** Set to "Public repositories" so they work with client repos

### Repository Settings
- **Visibility:** PUBLIC (required for org secrets to work)
- **Actions:** Enabled by default for org repos
- **.github folder:** Must be at repository ROOT, not in subdirectory

### Files to Track vs Ignore

**ALWAYS Track:**
- ✅ All PHP files
- ✅ All JSON content files
- ✅ Uploaded images (`assets/images/uploads/`)
- ✅ CSS/JS files
- ✅ .htaccess files

**NEVER Track:**
- ❌ .env file (contains secrets)
- ❌ WordPress directory (if present)
- ❌ Error logs
- ❌ Backup files

## Manual Deployment After First Push

### Upload .env File Manually
Since .env is not in git, upload it once via FTP/cPanel:
```bash
# Or use scp
scp .env user@server:/home/user/public_html/
```

### Set Permissions
```bash
chmod 600 /home/user/public_html/.env
```

## Workflow for Updates

### When Developer Makes Changes:
1. Make changes locally
2. Test thoroughly
3. Commit and push
4. GitHub Actions deploys automatically

### When Client Makes Changes (via Admin):
1. Client edits content via admin mode
2. Changes saved to JSON files on server
3. Before next developer update:
   ```bash
   # Fetch latest JSON from production
   scp -r user@server:/home/user/public_html/content/*.json content/

   # Commit the updated content
   git add content/
   git commit -m "Sync content from production"
   git push
   ```

## Common Issues and Solutions

### Admin mode not working after deployment
**Cause:** admin-config.php has hardcoded ADMIN_SECRET_KEY instead of reading from .env
**Solution:** Ensure admin-config.php uses EnvLoader:
```php
// Wrong - hardcoded
define('ADMIN_SECRET_KEY', 'some_hardcoded_key');

// Correct - reads from .env
require_once __DIR__ . '/env-loader.php';
define('ADMIN_SECRET_KEY', EnvLoader::get('ADMIN_SECRET_KEY', 'default_key'));
```

### "Repository rule violations - Push contains secrets"
**Cause:** .env or other secrets in commit history
**Solution:** Start fresh - delete repo, create new one, ensure .gitignore is set BEFORE first commit

### "Error: can't connect without a private SSH key"
**Cause:** Secrets not accessible
**Solution:**
1. Ensure repository is PUBLIC
2. Check organization secrets are set to "Public repositories"
3. Verify secret names match exactly

### Workflow not appearing in Actions tab
**Cause:** .github folder not at repository root
**Solution:** Must be `/.github/workflows/`, not `/subfolder/.github/workflows/`

## Template for New Client Sites

### Quick Setup Script
Save as `setup-client-deployment.sh`:

```bash
#!/bin/bash
CLIENT_NAME=$1
CPANEL_USER=$2
DOMAIN=$3

# Create .gitignore
cat > .gitignore << 'EOF'
.env
.env.local
*.backup
*.log
.DS_Store
blog/
EOF

# Initialize git
git init

# Create workflow
mkdir -p .github/workflows
cat > .github/workflows/deploy.yml << EOF
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v3

    - name: Deploy to $DOMAIN
      uses: appleboy/scp-action@v0.1.4
      with:
        host: \${{ secrets.WHM_HOST }}
        username: \${{ secrets.WHM_USERNAME }}
        key: \${{ secrets.WHM_SSH_KEY }}
        source: "*"
        target: "/home/$CPANEL_USER/public_html"
        rm: false

    - name: Set permissions
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: \${{ secrets.WHM_HOST }}
        username: \${{ secrets.WHM_USERNAME }}
        key: \${{ secrets.WHM_SSH_KEY }}
        script: |
          chown -R $CPANEL_USER:$CPANEL_USER /home/$CPANEL_USER/public_html
          find /home/$CPANEL_USER/public_html -type d -exec chmod 755 {} \;
          find /home/$CPANEL_USER/public_html -type f -exec chmod 644 {} \;
EOF

# Create GitHub repo
gh repo create 2mags-sites/$CLIENT_NAME --public --description "$CLIENT_NAME website"

# Initial commit and push
git add -A
git commit -m "Initial commit - $CLIENT_NAME website"
git branch -M main
git remote add origin https://github.com/2mags-sites/$CLIENT_NAME.git
git push -u origin main

echo "✅ Deployment setup complete for $CLIENT_NAME"
echo "📦 Repository: https://github.com/2mags-sites/$CLIENT_NAME"
echo "🚀 Actions: https://github.com/2mags-sites/$CLIENT_NAME/actions"
echo "🔐 Remember to upload .env file manually!"
```

### Usage:
```bash
cd project-clientname/php-website
bash setup-client-deployment.sh "client-name" "cpanelusername" "client-domain.com"
```

## Summary of Key Learnings

1. **Use separate PUBLIC repos per client** - Don't try to manage all clients in one repo
2. **Organization secrets need PUBLIC repos** - Private repos can't access org secrets
3. **Exclude .env from git** - Upload manually once
4. **Include uploaded images** - They're content, not code
5. **.github must be at repo root** - Not in subdirectories
6. **Simple is better** - One repo = one site = one deployment pipeline

This approach gives you:
- ✅ Automatic deployment on push
- ✅ Clean separation per client
- ✅ No complex workflow logic
- ✅ Easy to maintain
- ✅ Secure (no secrets in git)