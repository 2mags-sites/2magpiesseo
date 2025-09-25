# SEO Editing Setup Guide

## Overview
This guide explains how to implement editable SEO meta tags (title, description, keywords) in the admin interface, allowing clients to optimize their pages for search engines without touching code.

## Features
- **Edit SEO Button** in admin bar for quick access
- **Modal Interface** for editing meta tags
- **Live Preview** - changes appear immediately in browser
- **Character Recommendations** for optimal SEO
- **JSON Storage** - SEO data stored with other content

## Implementation Requirements

### 1. Admin Bar Update (header.php)
Add the Edit SEO button to the admin bar:

```php
<?php if (IS_ADMIN): ?>
<div id="admin-bar" style="...">
    <div style="display: flex; gap: 10px;">
        <button onclick="editPageSEO()" style="background: #10b981; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: 500;">🔍 Edit SEO</button>
        <button onclick="saveAllChanges()" style="...">💾 Save Changes</button>
        <a href="?logout=true" style="...">Logout</a>
    </div>
</div>
<?php endif; ?>
```

### 2. JSON Structure
Every content JSON file must include a `meta` section:

```json
{
    "meta": {
        "title": "Page Title | Your Business Name",
        "description": "A compelling meta description for search engines (150-160 characters)",
        "keywords": "relevant, keywords, for, this, page"
    },
    "hero": {
        // ... other page content
    }
}
```

### 3. PHP Page Pattern
Update all PHP pages to load SEO meta from JSON:

```php
<?php
// Include admin configuration
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('page-name');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Default Page Title | Business Name';
$page_description = $content['meta']['description'] ?? 'Default meta description for this page.';
$page_keywords = $content['meta']['keywords'] ?? 'default, keywords, here';

// Include header (which uses these variables)
require_once 'includes/header.php';
?>
```

### 4. JavaScript Functions
The `admin-functions.js` file now includes these SEO functions:

- `editPageSEO()` - Opens the SEO editing modal
- `closeSEOModal()` - Closes the modal
- `saveSEOChanges()` - Saves changes to editedFields and updates live preview

## Modal Features

### Field Validations
- **Title**: Recommends 50-60 characters
- **Description**: Recommends 150-160 characters
- **Keywords**: Comma-separated list

### Live Preview
When saving SEO changes:
1. Browser title updates immediately
2. Meta tags update in DOM
3. Open Graph tags update for social sharing
4. Changes persist when "Save Changes" is clicked

## User Workflow

1. **Activate Admin Mode**: `?admin=your-secret-key`
2. **Click "Edit SEO"**: Opens modal with current values
3. **Edit Fields**: Make changes to title, description, keywords
4. **Save SEO**: Applies changes locally (preview)
5. **Save Changes**: Persists all changes to JSON files

## SEO Best Practices

### Title Tags
- Include primary keyword near beginning
- Add brand name with separator (|, -, •)
- Keep under 60 characters
- Make each page title unique

### Meta Descriptions
- Include target keywords naturally
- Write compelling copy that encourages clicks
- Keep between 150-160 characters
- Include a call-to-action when relevant
- Make each description unique

### Keywords
- Focus on 3-5 primary keywords per page
- Include variations and related terms
- Separate with commas
- Match content on the page

## Conversion Checklist

When converting a site to use editable SEO:

- [ ] Add `meta` section to all JSON files
- [ ] Update all PHP pages to use `$content['meta']` pattern
- [ ] Add Edit SEO button to admin bar
- [ ] Include SEO functions in admin-functions.js
- [ ] Test editing on each page type
- [ ] Verify changes save correctly
- [ ] Check meta tags update in browser

## Default Values

Always provide sensible defaults in PHP:

```php
$page_title = $content['meta']['title'] ??
    'About Us | ' . $business_info['name'];

$page_description = $content['meta']['description'] ??
    'Learn about our services and commitment to quality...';

$page_keywords = $content['meta']['keywords'] ??
    $business_info['name'] . ', services, quality, professional';
```

## Troubleshooting

### SEO changes not saving?
- Check `admin-save.php` handles nested `meta` fields
- Verify JSON file has write permissions
- Check browser console for JavaScript errors

### Modal not opening?
- Ensure `editPageSEO()` function is loaded
- Check for JavaScript syntax errors
- Verify admin mode is active

### Meta tags not updating?
- Check PHP page uses `$content['meta']` pattern
- Verify JSON structure is correct
- Clear browser cache

## Security Considerations

- SEO fields are sanitized with `htmlspecialchars()`
- Admin mode required to access editing
- Changes require authenticated save action
- No direct file access to JSON files

---

**Remember**: Good SEO starts with editable meta tags. This system ensures every page can be optimized without touching code.