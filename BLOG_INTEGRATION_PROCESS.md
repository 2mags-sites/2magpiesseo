# Blog Integration Process for PHP Websites

## Process Overview

1. **Scraping** - Extract existing content
2. **Analysis** - Understand current structure
3. **SEO Planning** - Keywords and architecture
4. **Content Generation** - Create PHP pages with semantic HTML
5. **→ DESIGN DECISION POINT** - See [DESIGN_INTEGRATION_PROCESS.md](./DESIGN_INTEGRATION_PROCESS.md)
   - Option A: Work with designer on custom templates
   - Option B: Apply pre-made Figma design
6. **Blog Integration** - Add WordPress for dynamic content (this document)
7. **Testing & Deployment**

## Prerequisites Setup

### 1. Configuration Files Structure
Create these files FIRST before any other development:

```
/includes/
  ├── config.php         # BASE_URL configuration
  └── blog-config.php    # Blog/WordPress settings
```

### 2. Base Configuration (config.php)
```php
<?php
// Detect environment and set BASE_URL
if ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
    define('BASE_URL', '/your-local-path');
    define('SITE_URL', 'http://localhost');
} else {
    define('BASE_URL', '');  // Root for production
    define('SITE_URL', 'https://www.yourdomain.com');
}
```

### 3. Blog Configuration (blog-config.php)
```php
<?php
define('BLOG_ENABLED', true);
define('BLOG_FOLDER', 'news');  // WordPress installation folder
define('BLOG_API_TIMEOUT', 5);   // API timeout in seconds
```

## WordPress Setup

### 1. Install WordPress in Subfolder
- Install in `/news/` or `/blog/` subfolder
- Configure wp-config.php with correct WP_HOME and WP_SITEURL

### 2. Expose Yoast SEO Fields in REST API
Add to WordPress theme's functions.php:
```php
// Expose Yoast fields in REST API
add_action('rest_api_init', function() {
    register_rest_field(['post', 'page'], 'yoast_meta', [
        'get_callback' => function($post) {
            return [
                'title' => get_post_meta($post['id'], '_yoast_wpseo_title', true),
                'description' => get_post_meta($post['id'], '_yoast_wpseo_metadesc', true),
                'keywords' => get_post_meta($post['id'], '_yoast_wpseo_focuskw', true),
            ];
        },
        'schema' => null,
    ]);
});

// Add featured image URL
add_action('rest_api_init', function() {
    register_rest_field('post', 'featured_image_url', [
        'get_callback' => function($post) {
            $image_id = get_post_thumbnail_id($post['id']);
            return $image_id ? wp_get_attachment_image_url($image_id, 'full') : null;
        },
        'schema' => null,
    ]);
});
```

## Standard Blog Components

### 1. Blog Functions Library
Create `/includes/blog-functions.php`:
```php
<?php
function fetchBlogPosts($limit = 4, $offset = 0) {
    if (!BLOG_ENABLED) return [];

    $api_url = SITE_URL . '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts';
    $api_url .= '?per_page=' . $limit;
    $api_url .= '&offset=' . $offset;
    $api_url .= '&orderby=date&order=desc';
    $api_url .= '&_fields=id,title,excerpt,date,slug,featured_image_url';

    $context = stream_context_create([
        'http' => ['timeout' => BLOG_API_TIMEOUT, 'ignore_errors' => true]
    ]);

    $response = @file_get_contents($api_url, false, $context);
    if ($response === false) return [];

    $posts = json_decode($response, true);
    if (!is_array($posts)) return [];

    // CRITICAL: Decode HTML entities
    foreach ($posts as &$post) {
        $post['title']['rendered'] = html_entity_decode(
            $post['title']['rendered'],
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );
        $post['excerpt']['rendered'] = html_entity_decode(
            strip_tags($post['excerpt']['rendered']),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );
    }

    return $posts;
}

function getTotalPostCount() {
    if (!BLOG_ENABLED) return 0;

    $api_url = SITE_URL . '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts';
    $api_url .= '?per_page=1&_fields=id';

    $context = stream_context_create([
        'http' => ['timeout' => BLOG_API_TIMEOUT, 'ignore_errors' => true]
    ]);

    $response = @file_get_contents($api_url, false, $context);
    if ($response === false) return 0;

    $headers = $http_response_header;
    foreach ($headers as $header) {
        if (stripos($header, 'X-WP-Total:') !== false) {
            return intval(trim(str_replace('X-WP-Total:', '', $header)));
        }
    }

    return 0;
}
```

### 2. Homepage Blog Panel
```php
<!-- Blog Section -->
<section class="blog-section">
    <div class="container">
        <h2>Latest News & Updates</h2>
        <div class="blog-posts-grid">
            <?php
            $posts = fetchBlogPosts(4);
            foreach ($posts as $post):
                $title = $post['title']['rendered'];
                $excerpt = $post['excerpt']['rendered'];
                $date = date('F j, Y', strtotime($post['date']));
                $url = BASE_URL . '/blog-post.php?id=' . $post['id'] . '&slug=' . $post['slug'];
            ?>
            <article class="blog-post-card">
                <?php if ($post['featured_image_url']): ?>
                <img src="<?php echo $post['featured_image_url']; ?>" alt="<?php echo htmlspecialchars($title); ?>">
                <?php endif; ?>
                <h3><a href="<?php echo $url; ?>"><?php echo htmlspecialchars($title); ?></a></h3>
                <time><?php echo $date; ?></time>
                <p><?php echo htmlspecialchars($excerpt); ?></p>
                <a href="<?php echo $url; ?>" class="read-more">Read More →</a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
```

### 3. Blog Index Page with Pagination
Key considerations:
- Use 9 posts per page for 3-column grid (divisible by 3)
- Avoid variable name conflicts (don't use $current_page if header uses it)
- Calculate offset: `($page_num - 1) * $posts_per_page`

### 4. Single Blog Post Page
Critical elements:
- Handle both ID and slug parameters
- Decode HTML entities for title and excerpt
- Keep HTML for content rendering
- Pull Yoast SEO data for meta tags
- Add proper padding for fixed headers (100px top padding)

### 5. CSS Grid Considerations
```css
.blog-posts-grid {
    display: grid;
    /* Use auto-fill NOT auto-fit to maintain columns with single item */
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
}
```

## Sitemap Integration

### 1. XML Sitemap Generator
- Fetch ALL posts using pagination (100 at a time)
- Include both static pages and dynamic blog posts
- Set appropriate priorities and change frequencies

### 2. HTML Sitemap
- Show recent posts (limit 20)
- Include all static pages organized by category
- Link to XML sitemap for search engines

## Common Pitfalls to Avoid

1. **HTML Entities**: Always decode HTML entities from WordPress API
2. **Variable Conflicts**: Check header.php for variable names before using in pages
3. **Path Issues**: ALWAYS use BASE_URL for links and assets
4. **Grid Layout**: Use `auto-fill` not `auto-fit` for consistent columns
5. **API Timeouts**: Set reasonable timeouts (5 seconds) and handle failures gracefully
6. **Pagination Offset**: Calculate correctly: `($page - 1) * $per_page`
7. **Fixed Headers**: Add sufficient top padding (100px) to prevent content hiding

## Testing Checklist

- [ ] Blog posts display on homepage
- [ ] Special characters (apostrophes, quotes) display correctly
- [ ] Blog index pagination works
- [ ] Single post page loads with full content
- [ ] SEO metadata populates correctly
- [ ] Images load from WordPress
- [ ] Sitemap includes all posts
- [ ] All links work in both dev and production
- [ ] Grid maintains structure with 1, 2, or 3 posts
- [ ] Back navigation not hidden under header

## Caching System

### How Caching Works
The system uses file-based caching ONLY for WordPress API calls to improve performance:
- **Homepage blog panel**: 5-minute cache (1 minute in development)
- **Blog index page**: NO CACHE - shows new posts immediately
- **XML Sitemap**: 30-minute cache for search engine crawlers
- **Individual blog posts**: 15-minute cache

### Important: What's NOT Cached
- HTML pages themselves
- Admin/edit mode interface
- User sessions
- Page content edits
- Any non-WordPress content

### Cache Management URLs

Using the secret key `kershaw2024admin` (CHANGE THIS IN PRODUCTION!):

1. **Enter Admin/Edit Mode**:
   ```
   https://yoursite.com/?admin=kershaw2024admin
   ```
   - Enables inline editing of page content
   - Session-based (only for your browser)
   - Other users will NOT see edit interface

2. **Clear All Cache**:
   ```
   https://yoursite.com/?clearcache=kershaw2024admin
   ```
   - Clears entire website cache
   - Can be run from any page
   - Shows green success notification
   - Useful after adding new WordPress posts

3. **Exit Admin Mode**:
   ```
   https://yoursite.com/?logout=true
   ```

### Cache File Locations
- Primary cache: `/cache/*.cache` (protected by .htaccess)
- Legacy cache: `/content/cache/*.json` (also cleared)

### When to Clear Cache
- After adding/editing WordPress posts that need immediate visibility
- After changing featured images
- If blog posts appear outdated on homepage
- After WordPress plugin updates that affect REST API output

## Production Deployment

1. **CRITICAL: Change the secret key** in `/includes/admin-config.php`:
   ```php
   define('ADMIN_SECRET_KEY', 'your-unique-secret-key-here');
   ```

2. Update config.php BASE_URL for production
3. Update blog-config.php SITE_URL
4. Ensure WordPress REST API is accessible
5. Test all blog functionality
6. Test cache clearing with your new secret key
7. Submit sitemap.xml to search engines

## Security Notes
- Never share your admin secret key
- The cache directory is protected from direct access
- Admin mode is session-based and user-specific
- Cache files contain only public blog data, no sensitive information