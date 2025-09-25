# Hero Background Image Editing Setup

This guide explains how to make hero section background images editable in admin mode.

## Requirements

1. The standardized admin system must be implemented (admin-config.php, admin-functions.js, etc.)
2. Hero sections must follow the structure outlined below

## HTML Structure

Your hero section should follow this pattern:

```php
<section class="page-hero">
    <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/default-hero.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content-single">
        <h1><?php echo editable($content['hero']['title'] ?? 'Page Title', 'hero.title'); ?></h1>
    </div>
</section>
```

## Key Components

### 1. Hero Image Div
- Must have class: `editable-hero-bg`
- Must have `data-field` attribute (typically "hero.image")
- Must have `data-page` attribute with the page name
- Background image should pull from `$content['hero']['image']`

### 2. Edit Overlay
- Only shown when `IS_ADMIN` is true
- Contains the edit button/text
- Initially hidden with `display: none`
- Will be repositioned by JavaScript to top-right corner

### 3. JSON Structure
Your page's JSON file should include:

```json
{
  "hero": {
    "title": "Page Title",
    "image": "assets/images/hero-background.jpg"
  },
  // ... other content
}
```

## CSS Requirements

The hero section should have proper positioning:

```css
.page-hero {
    position: relative;
    height: 400px; /* or your preferred height */
    overflow: hidden;
}

.hero-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
}
```

## How It Works

1. When in admin mode, hovering over the hero section shows a "📷 Click to Change Hero Image" button in the top-right corner
2. Clicking the button opens a file picker
3. Selected image is uploaded via `admin-upload.php`
4. Background image updates immediately
5. Changes are saved to JSON when "Save Changes" is clicked

## Features

- **Non-intrusive**: Edit button appears in corner, doesn't interfere with H1 title editing
- **Visual feedback**: Loading state during upload
- **Immediate preview**: See changes before saving
- **Fallback support**: Default image if none specified

## Troubleshooting

### Button not appearing
- Check that `IS_ADMIN` is true
- Verify the hero-edit-overlay div is present
- Check browser console for JavaScript errors

### Button not clickable
- Ensure z-index layering is correct
- Check that no other elements are blocking the button
- Verify admin-functions.js is loaded

### Image not updating
- Check admin-upload.php is working
- Verify file permissions on upload directory
- Check browser console for upload errors

## Notes

- The edit button is positioned to avoid interfering with editable text content
- Hero images should be optimized for web (recommended: 1920x600px or similar)
- Upload size limits are controlled by admin-upload.php and PHP settings