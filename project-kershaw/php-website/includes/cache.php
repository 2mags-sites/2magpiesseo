<?php
/**
 * Simple file-based caching system for blog API calls
 */
class BlogCache {
    private $cache_dir;
    private $cache_enabled;

    public function __construct() {
        $this->cache_dir = dirname(__DIR__) . '/cache/';
        $this->cache_enabled = true; // Can be disabled for development

        // Create cache directory if it doesn't exist
        if ($this->cache_enabled && !is_dir($this->cache_dir)) {
            mkdir($this->cache_dir, 0755, true);
            // Add .htaccess to protect cache files
            file_put_contents($this->cache_dir . '.htaccess', 'Deny from all');
        }
    }

    /**
     * Get cached data if available and not expired
     * @param string $key Cache key
     * @param int $ttl Time to live in seconds (default 5 minutes)
     * @return mixed Cached data or false if not available
     */
    public function get($key, $ttl = 300) {
        if (!$this->cache_enabled) {
            return false;
        }

        $cache_file = $this->cache_dir . md5($key) . '.cache';

        if (!file_exists($cache_file)) {
            return false;
        }

        // Check if cache is expired
        if (time() - filemtime($cache_file) > $ttl) {
            @unlink($cache_file);
            return false;
        }

        $data = @file_get_contents($cache_file);
        if ($data === false) {
            return false;
        }

        return unserialize($data);
    }

    /**
     * Store data in cache
     * @param string $key Cache key
     * @param mixed $data Data to cache
     * @return bool Success
     */
    public function set($key, $data) {
        if (!$this->cache_enabled) {
            return false;
        }

        $cache_file = $this->cache_dir . md5($key) . '.cache';
        return @file_put_contents($cache_file, serialize($data)) !== false;
    }

    /**
     * Clear specific cache or all cache
     * @param string $key Optional specific cache key to clear
     */
    public function clear($key = null) {
        if (!$this->cache_enabled) {
            return;
        }

        if ($key) {
            $cache_file = $this->cache_dir . md5($key) . '.cache';
            @unlink($cache_file);
        } else {
            // Clear all cache files
            $files = glob($this->cache_dir . '*.cache');
            foreach ($files as $file) {
                @unlink($file);
            }
        }
    }

    /**
     * Clear expired cache files
     */
    public function clearExpired() {
        if (!$this->cache_enabled) {
            return;
        }

        $files = glob($this->cache_dir . '*.cache');
        $now = time();

        foreach ($files as $file) {
            // Remove files older than 1 hour
            if ($now - filemtime($file) > 3600) {
                @unlink($file);
            }
        }
    }
}

/**
 * Fetch blog posts with caching (for homepage only)
 * @param int $limit Number of posts to fetch
 * @param int $offset Offset for pagination
 * @param int $cache_ttl Cache time in seconds (default 5 minutes)
 * @return array Blog posts
 */
function fetchBlogPostsCached($limit = 4, $offset = 0, $cache_ttl = 300) {
    if (!BLOG_ENABLED) {
        return [];
    }

    $cache = new BlogCache();
    $cache_key = 'blog_posts_' . $limit . '_' . $offset;

    // Try to get from cache
    $posts = $cache->get($cache_key, $cache_ttl);
    if ($posts !== false) {
        return $posts;
    }

    // Fetch fresh data
    $api_url = SITE_URL . '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts';
    $api_url .= '?per_page=' . $limit;
    if ($offset > 0) {
        $api_url .= '&offset=' . $offset;
    }
    $api_url .= '&orderby=date&order=desc';
    $api_url .= '&_fields=id,title,excerpt,date,slug,link,featured_image_url,_links,_embedded';
    $api_url .= '&_embed'; // Get featured media

    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'ignore_errors' => true
        ]
    ]);

    $response = @file_get_contents($api_url, false, $context);

    if ($response === false) {
        return [];
    }

    $posts = json_decode($response, true);

    if (!is_array($posts)) {
        return [];
    }

    // Format posts for easier use (matching the structure from blog-functions.php)
    $formatted_posts = [];
    foreach ($posts as $post) {
        // Decode HTML entities for proper display
        $title = html_entity_decode($post['title']['rendered'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $excerpt_raw = isset($post['excerpt']['rendered']) ? strip_tags($post['excerpt']['rendered']) : '';
        $excerpt = html_entity_decode($excerpt_raw, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Get featured image
        $featured_image = null;
        if (isset($post['featured_image_url'])) {
            $featured_image = $post['featured_image_url'];
        } elseif (isset($post['_embedded']['wp:featuredmedia'][0]['source_url'])) {
            $featured_image = $post['_embedded']['wp:featuredmedia'][0]['source_url'];
        }

        $formatted_posts[] = [
            'id' => $post['id'],
            'title' => $title,
            'slug' => $post['slug'],
            'date' => $post['date'],
            'excerpt' => $excerpt,
            'link' => isset($post['link']) ? str_replace(SITE_URL, '', $post['link']) : '',
            'featured_image' => $featured_image
        ];
    }

    $posts = $formatted_posts;

    // Store in cache
    $cache->set($cache_key, $posts);

    return $posts;
}

/**
 * Get total post count with caching
 * @param int $cache_ttl Cache time in seconds (default 5 minutes)
 * @return int Total post count
 */
function getTotalPostCountCached($cache_ttl = 300) {
    if (!BLOG_ENABLED) {
        return 0;
    }

    $cache = new BlogCache();
    $cache_key = 'blog_total_count';

    // Try to get from cache
    $count = $cache->get($cache_key, $cache_ttl);
    if ($count !== false) {
        return $count;
    }

    // Fetch fresh count
    $api_url = SITE_URL . '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts';
    $api_url .= '?per_page=1&_fields=id';

    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'ignore_errors' => true
        ]
    ]);

    $response = @file_get_contents($api_url, false, $context);

    if ($response === false) {
        return 0;
    }

    // Parse the X-WP-Total header
    $headers = $http_response_header;
    foreach ($headers as $header) {
        if (stripos($header, 'X-WP-Total:') !== false) {
            $count = intval(trim(str_replace('X-WP-Total:', '', $header)));
            // Store in cache
            $cache->set($cache_key, $count);
            return $count;
        }
    }

    return 0;
}

/**
 * Fetch single blog post with caching
 * @param int $post_id Post ID
 * @param string $post_slug Post slug (optional)
 * @param int $cache_ttl Cache time in seconds (default 15 minutes)
 * @return array|null Post data or null if not found
 */
function fetchSinglePostCached($post_id, $post_slug = null, $cache_ttl = 900) {
    if (!BLOG_ENABLED) {
        return null;
    }

    $cache = new BlogCache();
    $cache_key = 'blog_post_' . $post_id . '_' . $post_slug;

    // Try to get from cache
    $post = $cache->get($cache_key, $cache_ttl);
    if ($post !== false) {
        return $post;
    }

    // Fetch fresh data
    $api_url = SITE_URL . '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts/';

    if ($post_id) {
        $api_url .= $post_id;
    } else {
        $api_url .= '?slug=' . $post_slug . '&per_page=1';
    }

    $api_url .= (strpos($api_url, '?') !== false ? '&' : '?') . '_embed&_fields=id,title,content,excerpt,date,slug,link,yoast_meta,featured_image_url,_embedded';

    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'ignore_errors' => true
        ]
    ]);

    $response = @file_get_contents($api_url, false, $context);

    if ($response === false) {
        return null;
    }

    $post_data = json_decode($response, true);

    // Handle array response (when searching by slug)
    if (is_array($post_data) && isset($post_data[0])) {
        $post = $post_data[0];
    } else {
        $post = $post_data;
    }

    if (!$post || isset($post['code'])) {
        return null;
    }

    // Store in cache
    $cache->set($cache_key, $post);

    return $post;
}

/**
 * Get all blog posts for sitemap with caching
 * @param int $cache_ttl Cache time in seconds (default 30 minutes)
 * @return array All blog posts
 */
function getAllBlogPostsCached($cache_ttl = 1800) {
    if (!BLOG_ENABLED) {
        return [];
    }

    $cache = new BlogCache();
    $cache_key = 'blog_all_posts_sitemap';

    // Try to get from cache
    $posts = $cache->get($cache_key, $cache_ttl);
    if ($posts !== false) {
        return $posts;
    }

    // Fetch all posts with pagination
    $all_posts = [];
    $page = 1;
    $per_page = 100;

    do {
        $api_url = SITE_URL . '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts';
        $api_url .= '?per_page=' . $per_page;
        $api_url .= '&page=' . $page;
        $api_url .= '&_fields=id,slug,date,modified';

        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'ignore_errors' => true
            ]
        ]);

        $response = @file_get_contents($api_url, false, $context);

        if ($response === false) {
            break;
        }

        $posts = json_decode($response, true);

        if (!is_array($posts) || empty($posts)) {
            break;
        }

        $all_posts = array_merge($all_posts, $posts);
        $page++;

        if (count($posts) < $per_page) {
            break;
        }

        if ($page > 100) { // Safety limit
            break;
        }

    } while (true);

    // Store in cache
    $cache->set($cache_key, $all_posts);

    return $all_posts;
}
?>