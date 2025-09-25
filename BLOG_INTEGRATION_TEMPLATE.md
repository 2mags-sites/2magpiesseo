# Blog Integration Template

## CRITICAL: WordPress Setup Instructions

After installing WordPress in the `/news/` (or `/blog/`) folder, you MUST:

### 1. Add REST API Exposure Code to WordPress

**Add this code to the active theme's `functions.php` file:**
Location: `/news/wp-content/themes/[active-theme]/functions.php`

```php
// Expose Yoast SEO fields in REST API
add_action('rest_api_init', function() {
    register_rest_field('post', 'yoast_meta', array(
        'get_callback' => function($post) {
            $meta = array(
                'title' => get_post_meta($post['id'], '_yoast_wpseo_title', true),
                'description' => get_post_meta($post['id'], '_yoast_wpseo_metadesc', true),
                'keywords' => get_post_meta($post['id'], '_yoast_wpseo_focuskw', true),
                'canonical' => get_post_meta($post['id'], '_yoast_wpseo_canonical', true),
                'og_title' => get_post_meta($post['id'], '_yoast_wpseo_opengraph-title', true),
                'og_description' => get_post_meta($post['id'], '_yoast_wpseo_opengraph-description', true),
                'og_image' => get_post_meta($post['id'], '_yoast_wpseo_opengraph-image', true),
            );
            return array_filter($meta);
        },
        'schema' => array('type' => 'object')
    ));
});

// Expose featured image URL in REST API
add_action('rest_api_init', function() {
    register_rest_field('post', 'featured_image_url', array(
        'get_callback' => function($post) {
            $image_id = get_post_thumbnail_id($post['id']);
            if ($image_id) {
                $image = wp_get_attachment_image_src($image_id, 'large');
                return $image ? $image[0] : null;
            }
            return null;
        },
        'schema' => array('type' => 'string')
    ));
});

// Enable CORS for local development
add_action('rest_api_init', function() {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function($value) {
        header('Access-Control-Allow-Origin: *');
        return $value;
    });
}, 15);
```

### 2. Test the API Endpoint

After adding the code, test that it works:
```bash
curl http://localhost/[site]/news/wp-json/wp/v2/posts?_fields=id,title,yoast_meta,featured_image_url
```

## Standard Blog Configuration

When a user requests a blog, this template ensures consistent implementation across all projects.

## Configuration Variables

```php
// config.php - Blog settings
define('BLOG_ENABLED', true);
define('BLOG_FOLDER', 'blog');  // Could be: blog, news, updates, articles, etc.
define('BLOG_DISPLAY_NAME', 'Latest News');  // Heading on homepage
define('BLOG_NAV_TEXT', 'Blog');  // Navigation menu text
define('BLOG_POST_COUNT', 4);  // Number of posts on homepage
```

## Homepage Blog Section (Standard Layout)

**Location**: Always near bottom of homepage, above footer, after all service/info sections

### HTML Structure:
```php
<!-- Latest Blog Posts Section -->
<?php if (BLOG_ENABLED): ?>
<section class="latest-posts-section">
    <div class="container">
        <h2 class="section-title"><?php echo BLOG_DISPLAY_NAME; ?></h2>
        <p class="section-subtitle">Stay updated with our latest news and insights</p>

        <div class="blog-posts-grid">
            <?php
            $posts = getLatestPosts(BLOG_POST_COUNT);
            if ($posts && !empty($posts)):
                foreach ($posts as $post):
            ?>
            <article class="blog-card">
                <?php if (!empty($post['featured_image'])): ?>
                <div class="blog-card-image">
                    <img src="<?php echo $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                </div>
                <?php else: ?>
                <div class="blog-card-image blog-card-placeholder">
                    <i class="fas fa-newspaper fa-3x"></i>
                </div>
                <?php endif; ?>

                <div class="blog-card-content">
                    <h3 class="blog-card-title">
                        <a href="/<?php echo BLOG_FOLDER; ?>/<?php echo $post['slug']; ?>">
                            <?php echo $post['title']; ?>
                        </a>
                    </h3>
                    <p class="blog-card-date"><?php echo date('F j, Y', strtotime($post['date'])); ?></p>
                </div>
            </article>
            <?php
                endforeach;
            else:
            ?>
            <!-- Development/Fallback Content -->
            <div class="blog-placeholder">
                <p>Blog posts will appear here once WordPress is installed.</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="blog-view-all">
            <a href="/<?php echo BLOG_FOLDER; ?>/" class="btn btn-outline">View All Posts</a>
        </div>
    </div>
</section>
<?php endif; ?>
```

## CSS Styles (Add to styles.css)

```css
/* Blog Posts Grid - Homepage */
.latest-posts-section {
    padding: 80px 0;
    background: var(--bg-light);
}

.blog-posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin: 40px 0;
}

.blog-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.blog-card-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: var(--bg-light);
}

.blog-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.blog-card-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
}

.blog-card-content {
    padding: 20px;
}

.blog-card-title {
    font-size: 1.1rem;
    margin-bottom: 10px;
    line-height: 1.4;
}

.blog-card-title a {
    color: var(--text-dark);
    text-decoration: none;
    transition: color 0.3s ease;
}

.blog-card-title a:hover {
    color: var(--primary-color);
}

.blog-card-date {
    font-size: 0.9rem;
    color: var(--text-muted);
}

.blog-view-all {
    text-align: center;
    margin-top: 40px;
}

/* Responsive */
@media (max-width: 768px) {
    .blog-posts-grid {
        grid-template-columns: 1fr;
    }
}
```

## Blog Integration Functions

```php
// includes/blog-functions.php

function getLatestPosts($count = 4) {
    // Check if in development mode
    if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
        return getSamplePosts($count);
    }

    // Check cache first
    $cache_file = __DIR__ . '/../content/cache/blog-posts.json';
    $cache_time = 1800; // 30 minutes

    if (file_exists($cache_file) && time() - filemtime($cache_file) < $cache_time) {
        $cached = json_decode(file_get_contents($cache_file), true);
        return array_slice($cached, 0, $count);
    }

    // Fetch from WordPress REST API
    $api_url = '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts?per_page=' . $count . '&_fields=id,title,slug,date,excerpt,featured_media,link';

    // Use absolute URL for API call
    $full_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $api_url;

    $context = stream_context_create([
        'http' => [
            'timeout' => 3,  // 3 second timeout
        ]
    ]);

    $response = @file_get_contents($full_url, false, $context);

    if ($response === false) {
        // API failed, use cache even if expired
        if (file_exists($cache_file)) {
            return json_decode(file_get_contents($cache_file), true);
        }
        return false;
    }

    $posts = json_decode($response, true);

    // Format posts for easier use
    $formatted_posts = [];
    foreach ($posts as $post) {
        $formatted_posts[] = [
            'id' => $post['id'],
            'title' => $post['title']['rendered'],
            'slug' => $post['slug'],
            'date' => $post['date'],
            'excerpt' => isset($post['excerpt']['rendered']) ? $post['excerpt']['rendered'] : '',
            'link' => $post['link'],
            'featured_image' => getFeaturedImage($post['featured_media'])
        ];
    }

    // Save to cache
    if (!file_exists(dirname($cache_file))) {
        mkdir(dirname($cache_file), 0755, true);
    }
    file_put_contents($cache_file, json_encode($formatted_posts));

    return $formatted_posts;
}

function getFeaturedImage($media_id) {
    if (!$media_id) return null;

    // Simplified - in production, cache this or fetch from media endpoint
    return '/' . BLOG_FOLDER . '/wp-json/wp/v2/media/' . $media_id;
}

function getSamplePosts($count = 4) {
    // Sample posts for development
    $sample = [
        [
            'id' => 1,
            'title' => 'Welcome to Our New Website',
            'slug' => 'welcome-new-website',
            'date' => date('Y-m-d', strtotime('-1 week')),
            'excerpt' => 'We are excited to launch our newly designed website...',
            'link' => '#',
            'featured_image' => 'https://via.placeholder.com/400x300'
        ],
        [
            'id' => 2,
            'title' => 'Important Announcement for Our Clients',
            'slug' => 'important-announcement',
            'date' => date('Y-m-d', strtotime('-2 weeks')),
            'excerpt' => 'We have some important updates to share with you...',
            'link' => '#',
            'featured_image' => 'https://via.placeholder.com/400x300'
        ],
        [
            'id' => 3,
            'title' => 'Seasonal Tips and Advice',
            'slug' => 'seasonal-tips',
            'date' => date('Y-m-d', strtotime('-3 weeks')),
            'excerpt' => 'As the season changes, here are some helpful tips...',
            'link' => '#',
            'featured_image' => 'https://via.placeholder.com/400x300'
        ],
        [
            'id' => 4,
            'title' => 'Community Event Highlights',
            'slug' => 'community-events',
            'date' => date('Y-m-d', strtotime('-4 weeks')),
            'excerpt' => 'Thank you to everyone who joined us at our recent event...',
            'link' => '#',
            'featured_image' => 'https://via.placeholder.com/400x300'
        ]
    ];

    return array_slice($sample, 0, $count);
}
```

## Navigation Update

Add to header.php navigation:
```php
<li class="nav-item">
    <a href="/<?php echo BLOG_FOLDER; ?>/" class="nav-link"><?php echo BLOG_NAV_TEXT; ?></a>
</li>
```

## Project State Documentation

Add to PROJECT_STATE.json when blog is enabled:
```json
{
  "blog_configuration": {
    "enabled": true,
    "folder_name": "news",
    "display_name": "Latest News",
    "nav_text": "News",
    "post_count": 4,
    "api_endpoint": "/news/wp-json/wp/v2/posts",
    "cache_duration": 1800
  }
}
```

## Deployment Instructions

When blog is enabled, include in deployment guide:

```markdown
## WordPress Blog Setup

1. Install WordPress in `/[folder-name]/` directory
2. Configure permalinks to "Post name"
3. Install and activate the custom theme from `/wordpress-theme/`
4. Enable REST API (should be enabled by default)
5. Create sample posts for testing
6. Update config.php with correct BLOG_FOLDER name
7. Test homepage integration at production URL
8. Clear cache directory if posts don't appear
```

## Testing Checklist

- [ ] Homepage shows 4 blog cards in grid layout
- [ ] Cards display featured image (or placeholder)
- [ ] Cards show post title and date
- [ ] Cards link to WordPress blog posts
- [ ] "View All Posts" button links to blog homepage
- [ ] Navigation includes blog link
- [ ] Cache refreshes every 30 minutes
- [ ] Graceful fallback if WordPress offline
- [ ] Sample posts show in development