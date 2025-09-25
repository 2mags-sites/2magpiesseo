# SEO Meta Update Pattern

## What We've Added

### 1. Edit SEO Button
Added a green "Edit SEO" button in the admin bar that opens a modal for editing:
- Page Title
- Meta Description
- Keywords

### 2. JSON Structure
All content JSON files now need a `meta` section:
```json
{
    "meta": {
        "title": "Page Title | Site Name",
        "description": "Meta description for SEO",
        "keywords": "keyword1, keyword2, keyword3"
    },
    // ... rest of content
}
```

### 3. PHP Pattern
All PHP pages should load meta from JSON:
```php
// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Default Page Title';
$page_description = $content['meta']['description'] ?? 'Default description';
$page_keywords = $content['meta']['keywords'] ?? 'default, keywords';
```

## Implementation Status

✅ **Completed:**
- Updated admin bar with Edit SEO button (header.php)
- Added SEO editing modal functionality (admin-functions.js)
- Updated about-us.php to use JSON meta pattern
- Added meta section to about-us.json

## To Update All Other Pages:

Each PHP page needs updating from hardcoded meta to JSON-based meta. The pattern is:
1. Change hardcoded meta variables to pull from `$content['meta']`
2. Add meta section to corresponding JSON file

This allows SEO meta to be edited through the admin interface just like other content.