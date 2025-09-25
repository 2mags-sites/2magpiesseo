# WordPress Blog Integration Setup Guide

## Overview
This website is configured to display WordPress blog posts on the homepage and has a dedicated blog listing page. The integration uses WordPress REST API to fetch posts dynamically.

## Setup Instructions

### 1. Install WordPress in the /blog directory

1. Download WordPress from https://wordpress.org/download/
2. Extract WordPress files to the `/blog/` folder in your website root
3. Access `yoursite.com/blog/` and follow WordPress installation wizard
4. During setup, use these database details:
   - Database Name: [your_database_name]_blog
   - Username: [your_database_user]
   - Password: [your_database_password]
   - Database Host: localhost
   - Table Prefix: wp_blog_ (or keep wp_)

### 2. Configure WordPress Settings

After installation:

1. **Enable REST API** (enabled by default in WordPress 5.0+)
   - No action needed unless disabled by a plugin

2. **Set Permalink Structure**
   - Go to Settings → Permalinks
   - Choose "Post name" structure
   - Save changes

3. **Configure Site Address**
   - Go to Settings → General
   - WordPress Address (URL): `https://yoursite.com/blog`
   - Site Address (URL): `https://yoursite.com/blog`

### 3. Test the REST API

Visit: `https://yoursite.com/blog/wp-json/wp/v2/posts`

You should see JSON data for your posts. If you get a 404 error:
- Check permalink settings
- Ensure .htaccess is writable
- Verify mod_rewrite is enabled

### 4. Features Integrated

The following features are already integrated into your PHP website:

1. **Homepage Blog Section** (`index.php`)
   - Displays latest 4 blog posts
   - Shows featured images, titles, excerpts, and dates
   - "View All Articles" button links to blog listing page

2. **Blog Listing Page** (`blog-news.php`)
   - Displays up to 12 latest posts in a grid layout
   - Responsive design for mobile devices
   - Fallback message if no posts available

3. **Footer Navigation**
   - "News & Articles" link added to Quick Links section

### 5. Creating Blog Content

1. Log into WordPress admin: `yoursite.com/blog/wp-admin`
2. Go to Posts → Add New
3. Create your post with:
   - Title
   - Content
   - Featured Image (recommended 600x400px minimum)
   - Categories and Tags as needed
4. Publish the post

Posts will automatically appear on your main website within a few minutes.

### 6. Customization Options

#### Change Number of Posts Displayed

Edit the following files:

**Homepage** (`index.php` line 206):
```php
$latest_posts = fetchLatestBlogPosts(4); // Change 4 to desired number
```

**Blog Page** (`blog-news.php` line 19):
```php
$blog_posts = fetchLatestBlogPosts(12); // Change 12 to desired number
```

#### Modify Blog Section Titles

The blog section titles are editable in admin mode:
- Homepage: "Latest News & Articles"
- Blog Page: "News & Updates"

### 7. Troubleshooting

**Posts not showing:**
- Check if WordPress REST API is accessible
- Verify posts are published (not draft)
- Clear any caching plugins
- Check PHP error logs

**Images not displaying:**
- Ensure featured images are set in WordPress
- Check image file permissions
- Verify WordPress media library is working

**Slow loading:**
- Consider implementing caching for API calls
- Optimize images in WordPress media library
- Use a CDN for media files

### 8. Security Recommendations

1. **Secure WordPress Installation**
   - Use strong passwords
   - Install security plugin (Wordfence, Sucuri)
   - Keep WordPress, themes, and plugins updated
   - Limit login attempts

2. **API Security**
   - Consider rate limiting API requests
   - Implement caching to reduce API calls
   - Monitor for unusual activity

3. **File Permissions**
   - Set correct permissions on WordPress files
   - Protect wp-config.php
   - Disable file editing in WordPress admin

### 9. Optional Enhancements

- **Categories Filter**: Add category filtering to blog listing page
- **Search**: Implement blog post search functionality
- **Related Posts**: Show related posts on individual post pages
- **Comments**: Enable/disable WordPress comments as needed
- **RSS Feed**: WordPress RSS feed available at `/blog/feed/`

### 10. Maintenance

Regular maintenance tasks:
- Update WordPress core, themes, and plugins monthly
- Backup WordPress database weekly
- Monitor disk space usage
- Review and moderate comments (if enabled)
- Check for broken links periodically

## Support

For WordPress-specific issues, refer to:
- WordPress Documentation: https://wordpress.org/support/
- REST API Handbook: https://developer.wordpress.org/rest-api/

For integration issues with the main website, check the following files:
- `/includes/blog-fetcher.php` - API integration logic
- `/blog-news.php` - Blog listing page
- `/index.php` - Homepage blog section (lines 195-357)