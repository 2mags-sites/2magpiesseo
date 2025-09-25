<?php
// Blog Integration Functions
require_once 'blog-config.php';

function getLatestPosts($count = 4) {
    // Check if WordPress is installed
    if (!wordPressInstalled()) {
        return getSamplePosts($count);
    }

    // Use the new caching system from cache.php
    require_once dirname(__FILE__) . '/cache.php';

    // For homepage, use 5-minute cache
    $cache_ttl = IS_LOCAL ? 60 : 300; // 1 minute in dev, 5 minutes in production
    return fetchBlogPostsCached($count, 0, $cache_ttl);
}

function getFeaturedImageFromPost($post) {
    // Check if featured media is embedded
    if (isset($post['_embedded']['wp:featuredmedia'][0]['source_url'])) {
        return $post['_embedded']['wp:featuredmedia'][0]['source_url'];
    }

    return null;
}

function wordPressInstalled() {
    // Check if WordPress is installed by looking for wp-config.php
    $wp_config = dirname(__DIR__) . '/' . BLOG_FOLDER . '/wp-config.php';
    return file_exists($wp_config);
}

function getSamplePosts($count = 4) {
    $sample = [
        [
            'id' => 1,
            'title' => 'Welcome to Our New Website',
            'slug' => 'welcome-new-website',
            'date' => date('Y-m-d', strtotime('-1 week')),
            'excerpt' => 'We are excited to launch our newly designed website with improved features and better user experience.',
            'link' => '/' . BLOG_FOLDER . '/welcome-new-website/',
            'featured_image' => 'https://via.placeholder.com/400x300/8B7355/ffffff?text=News+1'
        ],
        [
            'id' => 2,
            'title' => 'Important Service Updates',
            'slug' => 'service-updates',
            'date' => date('Y-m-d', strtotime('-2 weeks')),
            'excerpt' => 'We have made several improvements to our services to better serve our community.',
            'link' => '/' . BLOG_FOLDER . '/service-updates/',
            'featured_image' => 'https://via.placeholder.com/400x300/8B7355/ffffff?text=News+2'
        ],
        [
            'id' => 3,
            'title' => 'Community Support Initiative',
            'slug' => 'community-support',
            'date' => date('Y-m-d', strtotime('-3 weeks')),
            'excerpt' => 'Learn about our new initiative to provide additional support to local families.',
            'link' => '/' . BLOG_FOLDER . '/community-support/',
            'featured_image' => 'https://via.placeholder.com/400x300/8B7355/ffffff?text=News+3'
        ],
        [
            'id' => 4,
            'title' => 'Seasonal Remembrance Services',
            'slug' => 'remembrance-services',
            'date' => date('Y-m-d', strtotime('-4 weeks')),
            'excerpt' => 'Join us for our special remembrance services this season as we honor those we have lost.',
            'link' => '/' . BLOG_FOLDER . '/remembrance-services/',
            'featured_image' => 'https://via.placeholder.com/400x300/8B7355/ffffff?text=News+4'
        ]
    ];

    return array_slice($sample, 0, $count);
}

function getCachedPosts() {
    $cache_file = dirname(__DIR__) . '/content/cache/blog-posts.json';

    if (!file_exists($cache_file)) {
        return false;
    }

    $file_age = time() - filemtime($cache_file);
    if ($file_age > CACHE_DURATION) {
        return false;
    }

    $cached = file_get_contents($cache_file);
    return json_decode($cached, true);
}

function saveCachedPosts($posts) {
    $cache_dir = dirname(__DIR__) . '/content/cache';
    $cache_file = $cache_dir . '/blog-posts.json';

    if (!file_exists($cache_dir)) {
        mkdir($cache_dir, 0755, true);
    }

    file_put_contents($cache_file, json_encode($posts));
}

function formatPostDate($date_string) {
    $date = new DateTime($date_string);
    return $date->format('F j, Y');
}
?>