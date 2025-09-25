# PHP Website Conversion Checklist - EDITABLE VERSION

## Pre-Conversion Requirements
- [ ] All HTML preview pages completed and tested
- [ ] Single styles.css file created and linked
- [ ] Business documentation (BUSINESS_COMPLETE.md) available
- [ ] Keyword mapping (keyword-mapping.json) completed
- [ ] Business info JSON file created

## PHP Structure Setup (EDITABLE ARCHITECTURE)
- [ ] Create php-website/ directory
- [ ] Create content/ subdirectory for JSON files
- [ ] Create includes/ subdirectory
- [ ] Create assets/css/ subdirectory
- [ ] Create assets/images/uploads/ subdirectory
- [ ] Copy styles.css to assets/css/
- [ ] Set write permissions on content/ and uploads/

## Component Creation

### 1. Create header.php
- [ ] DOCTYPE and html opening tag
- [ ] Dynamic meta tags using PHP variables
- [ ] Link to /assets/css/styles.css
- [ ] Font Awesome CDN link
- [ ] Schema.org LocalBusiness JSON-LD
- [ ] Complete navigation HTML
- [ ] Mobile menu toggle button
- [ ] Body opening tag

### 2. Create footer.php
- [ ] Footer HTML structure
- [ ] Copyright with dynamic year
- [ ] ALL JavaScript in single location:
  - [ ] Mobile menu toggle script
  - [ ] FAQ accordion script
  - [ ] Any other interactive features
- [ ] All scripts wrapped in DOMContentLoaded
- [ ] Closing body and html tags

### 3. Create admin-config.php (REQUIRED)
- [ ] Session management
- [ ] Admin secret key (UNIQUE per project)
- [ ] loadContent() function
- [ ] saveContent() function
- [ ] editable() helper function
- [ ] Admin mode detection

### 4. Create admin-save.php
- [ ] Check admin mode authorization
- [ ] Handle JSON updates
- [ ] Process FAQ additions/deletions
- [ ] Return JSON response

### 5. Create admin-upload.php
- [ ] Check admin mode authorization
- [ ] Validate file types (images only)
- [ ] Validate file size (5MB max)
- [ ] Save to uploads directory
- [ ] Return file URL in JSON

## Page Conversion Process

### For EACH PHP page:

#### 1. Create JSON content file (content/page-name.json)
- [ ] Extract all text content from HTML
- [ ] Structure with meta, sections, faqs
- [ ] Include image_url fields where needed
- [ ] Save to content/ directory

#### 2. PHP Page Structure
- [ ] Include admin-config.php at top
- [ ] Load content with loadContent('page-name')
- [ ] Set meta variables from JSON
- [ ] Use editable() helper for all content
- [ ] Add admin bar if ADMIN_MODE
- [ ] NO hardcoded content in PHP

#### Example:
```php
<?php
require_once 'includes/admin-config.php';
$content = loadContent('page-name');
$page_title = $content['meta']['title'];
$page_description = $content['meta']['description'];
$page_keywords = $content['meta']['keywords'];
require_once 'includes/header.php';
?>
<!-- Content with editable() wrapper -->
<?php require_once 'includes/footer.php'; ?>
```

## Common Issues to Check

### JavaScript Issues
- [ ] Remove ALL inline <script> tags from individual pages
- [ ] Ensure NO duplicate FAQ toggle scripts
- [ ] Check console for JavaScript errors
- [ ] Verify FAQ dropdowns work on all pages
- [ ] Test mobile menu on all screen sizes

### SEO/Meta Issues
- [ ] Correct variable names ($page_* not $meta_*)
- [ ] All pages have unique titles
- [ ] All pages have unique descriptions
- [ ] Keywords relevant to page content

### Testing
- [ ] Test all FAQ dropdowns
- [ ] Test mobile navigation
- [ ] Validate Schema.org at schema.org/validator
- [ ] Check all internal links work
- [ ] Test on mobile devices
- [ ] Check page load speed

## Final Files to Create
- [ ] sitemap.xml (all page URLs with priorities)
- [ ] robots.txt (crawl directives)
- [ ] .htaccess WITH SECURITY RULES:

### CRITICAL .htaccess Security Configuration
```apache
# Protect content directory
<Directory ~ "/content">
    Order Allow,Deny
    Deny from all
</Directory>

# Protect admin files
<FilesMatch "^admin-.*\.php$">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# Block JSON files
<Files "*.json">
    Order Allow,Deny
    Deny from all
</Files>

# Secure uploads
<Directory ~ "/assets/images/uploads">
    php_flag engine off
    <FilesMatch "\.(?i:gif|jpe?g|png|webp)$">
        Order Deny,Allow
        Allow from all
    </FilesMatch>
</Directory>

Options -Indexes
```

## Deployment Readiness
- [ ] CHANGE ADMIN_SECRET_KEY to unique value
- [ ] All 404 errors resolved
- [ ] All console errors fixed
- [ ] Forms tested (if applicable)
- [ ] Contact information verified
- [ ] Schema.org validation passed
- [ ] Mobile responsiveness confirmed
- [ ] Admin mode tested and working
- [ ] Content saves correctly to JSON
- [ ] JSON files NOT accessible via URL
- [ ] Upload directory protected
- [ ] .htaccess security rules in place

## DO NOT
- ❌ Create .htaccess during development
- ❌ Put JavaScript in individual PHP files
- ❌ Use different variable naming conventions
- ❌ Duplicate Schema.org LocalBusiness data
- ❌ Create multiple CSS files
- ❌ Forget to test FAQ functionality
- ❌ Leave console.log statements in production

## Quick Debug Guide

### FAQ Not Working?
1. Check for duplicate scripts
2. Verify DOMContentLoaded wrapper
3. Check class names match (.faq-item, .faq-question, .faq-answer)
4. Look for JavaScript errors in console

### Mobile Menu Not Working?
1. Verify toggle button exists in header.php
2. Check footer.php has mobile menu script
3. Verify CSS has mobile menu styles
4. Test at actual mobile breakpoint (768px)

### Meta Tags Not Showing?
1. Check variable names ($page_* not $meta_*)
2. Verify header.php uses isset() checks
3. Ensure variables defined before require_once

---
Last Updated: January 2024
Use this checklist for every PHP website conversion to ensure consistency and avoid common issues.