# Production Deployment Checklist for PHP Websites

## Critical Path and URL Management

### 1. BASE_URL Configuration
**CRITICAL**: All PHP sites must handle different URL paths between development and production.

```php
// In includes/config.php - MUST BE INCLUDED IN EVERY PAGE
if ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
    define('BASE_URL', '/subfolder/path'); // Local development path
} else {
    define('BASE_URL', ''); // Production root path
}
```

### 2. Asset and Link References
**NEVER use hardcoded paths**. Always use BASE_URL:
```php
// ❌ WRONG
<link rel="stylesheet" href="/assets/css/styles.css">
<a href="/about.php">About</a>

// ✅ CORRECT
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/styles.css">
<a href="<?php echo BASE_URL; ?>/about.php">About</a>
```

### 3. Required Include Order
```php
<?php
// MUST be first line after opening PHP tag
require_once 'includes/config.php';  // Sets BASE_URL and other constants

// Then page-specific variables
$page_title = "...";
$page_description = "...";

// Then header include
require_once 'includes/header.php';
?>
```

## WordPress/Blog Integration Specifics

### 1. HTML Entity Decoding
**CRITICAL**: WordPress REST API returns HTML entities that must be decoded:

```php
// When processing WordPress API responses:
$title = html_entity_decode($post['title']['rendered'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
$excerpt = html_entity_decode(strip_tags($post['excerpt']['rendered']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
```

Common entities that need decoding:
- `&#8217;` → apostrophe (')
- `&#8220;` and `&#8221;` → smart quotes (" ")
- `&amp;` → ampersand (&)
- `&nbsp;` → non-breaking space

### 2. Blog Configuration
```php
// Blog path must be configurable
define('BLOG_FOLDER', 'news'); // or 'blog' or whatever

// API URLs must handle both local and production
if (IS_LOCAL) {
    define('BLOG_API_URL', 'http://localhost' . BASE_URL . '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts');
} else {
    define('BLOG_API_URL', '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts');
}
```

### 3. WordPress .htaccess
When WordPress is in a subfolder, its .htaccess RewriteBase must match:
```apache
RewriteBase /subfolder/news/  # Must match actual path
```

## Pre-Deployment Checklist

### Essential Files to Check:
- [ ] `includes/config.php` exists and has BASE_URL logic
- [ ] All PHP pages include config.php as first include
- [ ] All asset references use `<?php echo BASE_URL; ?>`
- [ ] All navigation links use `<?php echo BASE_URL; ?>`
- [ ] Blog integration uses proper HTML entity decoding
- [ ] WordPress .htaccess has correct RewriteBase

### Common Issues to Prevent:
1. **404 errors on all pages**: Missing BASE_URL configuration
2. **CSS/JS not loading**: Hardcoded asset paths
3. **Navigation broken**: Hardcoded link paths
4. **Special characters showing as codes**: Missing html_entity_decode()
5. **Blog posts not showing**: Wrong API URL or WordPress path

### Testing Commands:
```bash
# Test that all PHP files include config.php
grep -L "require_once 'includes/config.php'" *.php

# Check for hardcoded paths (should return nothing)
grep -r 'href="/' *.php | grep -v BASE_URL
grep -r 'src="/' *.php | grep -v BASE_URL

# Verify BASE_URL is used in header.php
grep BASE_URL includes/header.php
```

## Quick Fix Script

Create `fix-paths.php` for emergency deployment fixes:
```php
<?php
// Emergency path fixer for production deployment
$files = glob('*.php');
foreach ($files as $file) {
    $content = file_get_contents($file);

    // Add config include if missing
    if (strpos($content, "require_once 'includes/config.php'") === false) {
        $content = str_replace('<?php' . PHP_EOL,
            '<?php' . PHP_EOL . "require_once 'includes/config.php';" . PHP_EOL,
            $content);
    }

    // Replace hardcoded paths
    $content = str_replace('href="/', 'href="<?php echo BASE_URL; ?>/', $content);
    $content = str_replace('src="/assets', 'src="<?php echo BASE_URL; ?>/assets', $content);

    file_put_contents($file, $content);
    echo "Fixed: $file\n";
}
?>
```

## Final Production Steps

1. **Update config.php** with production domain
2. **Clear any cache** folders
3. **Update WordPress wp-config.php** with production database
4. **Test all navigation** links work
5. **Verify blog posts** display with correct formatting
6. **Check special characters** display correctly (not as HTML entities)
7. **Test on mobile** to ensure responsive design works
8. **Check browser console** for any 404 errors on assets

## Key Learnings Summary

The three critical issues that will affect EVERY production deployment:

1. **BASE_URL Configuration** - Essential for any site that might not run at domain root
2. **HTML Entity Decoding** - Required when pulling content from WordPress or any CMS API
3. **Config Include Order** - config.php must be included before any other includes

## Remember:
- **NEVER** commit database passwords to git
- **ALWAYS** test path changes locally first
- **DOCUMENT** any custom URL structures for future maintenance