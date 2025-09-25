<?php
// Blog Configuration
define('BLOG_ENABLED', true);
define('BLOG_FOLDER', 'news');  // WordPress will be installed here
define('BLOG_DISPLAY_NAME', 'Latest News');
define('BLOG_NAV_TEXT', 'News');
define('BLOG_POST_COUNT', 4);

// Development vs Production
if ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
    // Local development
    define('IS_LOCAL', true);
    define('SITE_URL', 'http://localhost/php-builder/website-rebuilder/project-kershaw/php-website');
    define('BLOG_API_URL', SITE_URL . '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts');
} else {
    // Production
    define('IS_LOCAL', false);
    define('SITE_URL', 'https://www.arthurkershawfunerals.com');
    define('BLOG_API_URL', '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts');
}

// Cache settings
define('CACHE_ENABLED', true); // Enable cache for both dev and production
define('CACHE_DURATION', IS_LOCAL ? 300 : 1800); // 5 minutes in dev, 30 minutes in production
?>