<?php
/**
 * Blog Post Fetcher
 * Fetches latest posts from WordPress REST API
 */

function fetchLatestBlogPosts($limit = 4) {
    // WordPress REST API endpoint - adjust this to your WordPress installation
    $api_url = '/blog/wp-json/wp/v2/posts?per_page=' . $limit . '&_embed';

    // For local development, use full URL
    $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    $full_url = $base_url . $api_url;

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $full_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2); // Reduced from 10 to 2 seconds
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local development

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Check if request was successful
    if ($http_code !== 200 || !$response) {
        return false;
    }

    $posts = json_decode($response, true);

    // Process posts to extract needed data
    $processed_posts = [];
    foreach ($posts as $post) {
        $processed_post = [
            'title' => $post['title']['rendered'],
            'excerpt' => wp_trim_words(strip_tags($post['excerpt']['rendered']), 20),
            'link' => str_replace($base_url . '/blog', '/blog', $post['link']),
            'date' => date('F j, Y', strtotime($post['date'])),
            'featured_image' => null
        ];

        // Get featured image if available
        if (isset($post['_embedded']['wp:featuredmedia'][0])) {
            $media = $post['_embedded']['wp:featuredmedia'][0];
            if (isset($media['media_details']['sizes']['medium'])) {
                $processed_post['featured_image'] = $media['media_details']['sizes']['medium']['source_url'];
            } elseif (isset($media['source_url'])) {
                $processed_post['featured_image'] = $media['source_url'];
            }
        }

        $processed_posts[] = $processed_post;
    }

    return $processed_posts;
}

// Helper function to trim words (in case WordPress function not available)
if (!function_exists('wp_trim_words')) {
    function wp_trim_words($text, $num_words = 55) {
        $text = strip_tags($text);
        $words_array = explode(' ', $text);
        if (count($words_array) > $num_words) {
            $words_array = array_slice($words_array, 0, $num_words);
            $text = implode(' ', $words_array) . '...';
        } else {
            $text = implode(' ', $words_array);
        }
        return $text;
    }
}
?>