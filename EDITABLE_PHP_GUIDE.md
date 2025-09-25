# Editable PHP Website Guide

## Overview
This guide ensures all PHP websites are built with inline editing capabilities from the start, allowing clients to edit content without accessing code.

## Core Principle
**SEPARATION OF CONTENT AND STRUCTURE**
- Content lives in JSON files
- PHP files handle structure and presentation
- Admin mode enables inline editing
- All changes save back to JSON

## Directory Structure

```
php-website/
├── content/                 # Protected JSON content files
│   ├── index.json          # Homepage content
│   ├── about.json          # About page content
│   └── [page-name].json    # One JSON per page
├── includes/
│   ├── header.php          # Site header, navigation, meta tags
│   ├── footer.php          # Site footer, ALL JavaScript
│   ├── admin-config.php    # Admin mode configuration
│   └── config.php          # Site configuration
├── assets/
│   ├── css/
│   │   └── styles.css      # Single stylesheet
│   └── images/
│       └── uploads/        # User uploaded images
├── admin-save.php          # Handles content saves
├── admin-upload.php        # Handles image uploads
├── .htaccess              # Security configuration
└── [page-name].php         # Individual pages

```

## Step-by-Step Implementation

### 1. Create admin-config.php
```php
<?php
session_start();

// CHANGE THIS SECRET KEY FOR EACH PROJECT!
define('ADMIN_SECRET_KEY', 'project-specific-key-2024');

// Check admin activation
if (isset($_GET['admin']) && $_GET['admin'] === ADMIN_SECRET_KEY) {
    $_SESSION['admin_mode'] = true;
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Check logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    unset($_SESSION['admin_mode']);
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Define admin mode
define('ADMIN_MODE', isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === true);

// Load content from JSON
function loadContent($page) {
    $file = __DIR__ . '/../content/' . $page . '.json';
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    }
    return false;
}

// Save content to JSON
function saveContent($page, $content) {
    if (!ADMIN_MODE) return false;
    $file = __DIR__ . '/../content/' . $page . '.json';
    $json = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    return file_put_contents($file, $json);
}

// Make field editable
function editable($value, $field_path = '') {
    if (ADMIN_MODE && !empty($field_path)) {
        return '<span class="editable" data-field="' . htmlspecialchars($field_path) . '">' . $value . '</span>';
    }
    return $value;
}
?>
```

### 2. JSON Content Structure

```json
{
  "meta": {
    "title": "Page Title | Business Name",
    "description": "Meta description for SEO",
    "keywords": "keyword1, keyword2, keyword3"
  },
  "hero": {
    "title": "Main Heading",
    "subtitle": "Subheading text",
    "image": "assets/images/hero-background.jpg",
    "image_url": null,
    "cta_primary": {
      "text": "Contact Us",
      "link": "/contact.php"
    }
  },
  "content": {
    "section1": {
      "title": "Section Title",
      "text": "Section content...",
      "image_url": null
    }
  },
  "faqs": {
    "title": "Frequently Asked Questions",
    "items": [
      {
        "question": "Question here?",
        "answer": "Answer here."
      }
    ]
  }
}
```

### 3. PHP Page Template

```php
<?php
// Include admin configuration
require_once 'includes/admin-config.php';

// Load content from JSON
$content = loadContent('page-name');

// Set page meta from JSON
$page_title = $content['meta']['title'];
$page_description = $content['meta']['description'];
$page_keywords = $content['meta']['keywords'];

// Include header
require_once 'includes/header.php';
?>

<?php if (ADMIN_MODE): ?>
<!-- Admin Bar -->
<div style="background: #333; color: white; padding: 10px; text-align: center; position: fixed; top: 0; width: 100%; z-index: 9999;">
    <strong>🔧 ADMIN MODE</strong> - Click text to edit |
    <button onclick="saveAllChanges()">Save Changes</button>
    <a href="?logout=true" style="color: #dc3545;">Exit Admin</a>
</div>
<div style="height: 40px;"></div>
<?php endif; ?>

<!-- Page Content -->
<!-- Hero Section with Editable Background Image -->
<section class="page-hero">
    <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/default-hero.jpg'; ?>');">
        <?php if (ADMIN_MODE): ?>
            <div class="hero-edit-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content-single">
        <h1><?php echo editable($content['hero']['title'], 'hero.title'); ?></h1>
        <p><?php echo editable($content['hero']['subtitle'], 'hero.subtitle'); ?></p>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
```

### 4. Admin JavaScript (in footer.php)

```javascript
<?php if (ADMIN_MODE): ?>
<style>
.editable {
    background-color: rgba(255, 255, 0, 0.1);
    outline: 1px dashed #ccc;
    cursor: text;
    min-height: 20px;
    display: inline-block;
}
.editable:hover {
    background-color: rgba(255, 255, 0, 0.2);
    outline: 2px dashed #999;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const changes = {};

    // Make elements editable
    document.querySelectorAll('.editable').forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            this.contentEditable = true;
            this.focus();
        });

        element.addEventListener('blur', function() {
            this.contentEditable = false;
            changes[this.dataset.field] = this.innerHTML;
        });
    });

    // Save changes
    window.saveAllChanges = function() {
        if (Object.keys(changes).length === 0) {
            alert('No changes to save');
            return;
        }

        fetch('admin-save.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                page: '<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>',
                changes: changes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Changes saved!');
                location.reload();
            }
        });
    };
});
</script>
<?php endif; ?>
```

## Security Implementation

### Critical .htaccess File (ROOT DIRECTORY)

```apache
# SECURITY: Protect sensitive directories and files

# 1. Protect content directory (JSON files)
<Directory ~ "/content">
    Order Allow,Deny
    Deny from all
</Directory>

# 2. Protect admin PHP files
<FilesMatch "^admin-.*\.php$">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# 3. Block direct access to JSON files
<FilesMatch "\.json$">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# 4. Protect includes directory
<Directory ~ "/includes">
    <FilesMatch "\.(php)$">
        Order Allow,Deny
        Deny from all
    </FilesMatch>
</Directory>

# 5. Secure uploads directory
<Directory ~ "/assets/images/uploads">
    # Disable PHP execution
    php_flag engine off

    # Only allow image files
    <FilesMatch "\.(?i:gif|jpe?g|png|webp)$">
        Order Deny,Allow
        Allow from all
    </FilesMatch>
</Directory>

# 6. Prevent directory listing
Options -Indexes

# 7. Prevent access to .htaccess itself
<Files .htaccess>
    Order Allow,Deny
    Deny from all
</Files>
```

## Features Checklist

### Essential Features (MUST HAVE)
- [x] Content stored in JSON files
- [x] PHP pages read from JSON
- [x] Admin mode via URL parameter
- [x] Inline text editing
- [x] Save changes to JSON
- [x] Security via .htaccess
- [x] Session-based authentication

### Advanced Features (RECOMMENDED)
- [x] FAQ management (add/edit/delete)
- [x] Image upload capability
- [x] Hero background image editing
- [x] Visual editing indicators
- [x] Bulk save functionality
- [ ] Revision history
- [ ] Backup before save
- [ ] Multi-user support

## Admin Mode Usage

### Activation
```
https://yoursite.com?admin=your-secret-key-here
```

### Features Available
1. **Text Editing**: Click any highlighted text to edit
2. **Image Upload**: Upload/change/remove images
3. **Hero Background Images**: Click overlay button to change hero section backgrounds
4. **SEO Editing**: Click "Edit SEO" button to modify page title, description, and keywords
5. **FAQ Management**: Add, edit, delete FAQs
6. **Save All**: Save all changes at once

### Deactivation
```
Click "Exit Admin Mode" or visit: https://yoursite.com?logout=true
```

## Security Best Practices

1. **Change Secret Key**: Always use unique key per project
2. **Use HTTPS**: Protect admin key transmission
3. **Backup JSON**: Before major edits
4. **Test .htaccess**: Verify files are protected
5. **Limit Upload Size**: Max 5MB for images
6. **Validate File Types**: Only allow safe image formats
7. **Sanitize Input**: Strip dangerous HTML tags

## Deployment Checklist

- [ ] Change ADMIN_SECRET_KEY to unique value
- [ ] Create .htaccess with all security rules
- [ ] Create /content/ directory with JSON files
- [ ] Create /assets/images/uploads/ directory
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Test admin mode activation/deactivation
- [ ] Verify JSON files are not directly accessible
- [ ] Test content editing and saving
- [ ] Test image uploads (if implemented)
- [ ] Remove any debug console.log statements
- [ ] Enable HTTPS for production

## Troubleshooting

### Admin mode not activating?
- Check secret key matches
- Verify session_start() is called
- Check PHP sessions are enabled

### Changes not saving?
- Verify write permissions on /content/ directory
- Check admin-save.php is accessible
- Look for JavaScript errors in console

### Images not uploading?
- Check /assets/images/uploads/ exists
- Verify directory has write permissions
- Check file size and type restrictions

### JSON files accessible via URL?
- Verify .htaccess is in place
- Check Apache mod_rewrite is enabled
- Test direct access to /content/page.json

---

## Quick Start Commands

```bash
# Create directory structure
mkdir -p php-website/content
mkdir -p php-website/includes
mkdir -p php-website/assets/css
mkdir -p php-website/assets/images/uploads

# Set permissions
chmod 755 php-website/content
chmod 755 php-website/assets/images/uploads

# Create .htaccess (copy security rules above)
nano php-website/.htaccess
```

---

**Remember**: Every PHP website should be editable by default. This guide ensures clients can maintain their own content while developers maintain the code structure.