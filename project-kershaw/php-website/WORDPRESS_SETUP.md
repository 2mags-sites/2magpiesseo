# WordPress Setup Instructions for Local Testing

## Prerequisites
- XAMPP running with Apache and MySQL
- Access to phpMyAdmin (http://localhost/phpmyadmin)

## Step 1: Download WordPress
1. Download WordPress from https://wordpress.org/download/
2. Extract the ZIP file
3. Copy all WordPress files into: `C:\Users\dave\Projects\php builder\website-rebuilder\project-kershaw\php-website\news\`

## Step 2: Create Database
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click "New" to create a database
3. Database name: `kershaw_news`
4. Collation: `utf8mb4_general_ci`
5. Click "Create"

## Step 3: Configure WordPress
1. Navigate to: http://localhost/php-builder/website-rebuilder/project-kershaw/php-website/news/
2. Follow WordPress installation wizard:
   - Database Name: `kershaw_news`
   - Username: `root`
   - Password: (leave blank for XAMPP default)
   - Database Host: `localhost`
   - Table Prefix: `wp_` (default is fine)

3. Site Information:
   - Site Title: "Arthur Kershaw News"
   - Username: (your admin username)
   - Password: (choose a strong password)
   - Email: (your email)

## Step 4: Configure Permalinks
1. Log into WordPress admin: /news/wp-admin/
2. Go to Settings → Permalinks
3. Select "Post name" option
4. Save Changes

## Step 5: Enable REST API (Should be enabled by default)
Test by visiting: http://localhost/php-builder/website-rebuilder/project-kershaw/php-website/news/wp-json/wp/v2/posts

## Step 6: Create Test Posts
1. Go to Posts → Add New
2. Create 4-5 test posts with:
   - Title
   - Content
   - Featured Image (optional)
   - Excerpt
3. Publish the posts

## Step 7: Test Integration
1. Visit the main site: http://localhost/php-builder/website-rebuilder/project-kershaw/php-website/
2. Scroll down to see "Latest News" section near the bottom
3. Should display 4 blog post cards

## Troubleshooting

### Blog posts not showing?
1. Check if WordPress is installed correctly
2. Verify REST API is accessible
3. Check browser console for errors
4. Ensure posts are published (not draft)

### Sample posts showing instead of real posts?
This means WordPress is not detected or API is not accessible. Check:
- WordPress is installed in `/news/` folder
- REST API endpoint is working
- No .htaccess blocking API access

### Images not showing?
- Make sure to set Featured Images in WordPress posts
- Check image URLs are correct

## Next Steps: Adding Yoast SEO

1. Install Yoast SEO plugin in WordPress
2. We'll add code to expose Yoast fields in REST API
3. This will allow SEO data to be used in the main site

## File Locations
- Blog config: `includes/blog-config.php`
- Blog functions: `includes/blog-functions.php`
- Blog styles: `assets/css/blog-styles.css`
- Homepage with blog: `index.php` (search for "Latest News Section")

## Current Configuration
- Blog folder: `/news/`
- Display name: "Latest News"
- Number of posts: 4
- Cache: Disabled for local development