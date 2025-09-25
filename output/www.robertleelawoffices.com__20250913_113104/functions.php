<?php
/**
 * Helper Functions
 * PHP Website Builder - Helper Functions
 */

/**
 * Load JSON data file
 */
function load_json_data($filename) {
    $filepath = DATA_DIR . '/' . $filename;
    if (file_exists($filepath)) {
        $json = file_get_contents($filepath);
        return json_decode($json, true);
    }
    return [];
}

/**
 * Get universal data
 */
function get_universal_data($section = null) {
    static $universal_data = null;

    if ($universal_data === null) {
        $universal_data = [
            'business' => load_json_data('universal/business.json'),
            'navigation' => load_json_data('universal/navigation.json'),
            'contact' => load_json_data('universal/contact.json'),
            'team' => load_json_data('universal/team.json'),
            'social' => load_json_data('universal/social.json')
        ];
    }

    if ($section) {
        return isset($universal_data[$section]) ? $universal_data[$section] : [];
    }

    return $universal_data;
}

/**
 * Get page data
 */
function get_page_data($page_name) {
    return load_json_data('pages/' . $page_name . '.json');
}

/**
 * Escape HTML output
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Get proper URL for links (adds .php for local development)
 */
function get_url($path) {
    // Check if we're running on PHP's built-in server
    $is_builtin_server = php_sapi_name() === 'cli-server';

    // If it's the built-in server and path doesn't have an extension, add .php
    if ($is_builtin_server && !empty($path) && $path !== '/') {
        // Remove leading slash if present
        $path = ltrim($path, '/');

        // Check if it already has an extension
        if (!preg_match('/\.\w+$/', $path)) {
            // Special handling for index/home
            if ($path === 'index' || $path === '') {
                return '/';
            }
            return '/' . $path . '.php';
        }
    }

    return $path;
}

/**
 * Format phone number
 */
function format_phone($phone) {
    // Remove non-numeric characters
    $phone = preg_replace('/[^0-9+]/', '', $phone);
    return $phone;
}

/**
 * Generate meta tags
 */
function generate_meta_tags($meta_data) {
    $tags = [];

    if (isset($meta_data['title'])) {
        $tags[] = '<title>' . e($meta_data['title']) . '</title>';
    }

    if (isset($meta_data['description'])) {
        $tags[] = '<meta name="description" content="' . e($meta_data['description']) . '">';
    }

    if (isset($meta_data['keywords']) && is_array($meta_data['keywords'])) {
        $keywords = implode(', ', $meta_data['keywords']);
        $tags[] = '<meta name="keywords" content="' . e($keywords) . '">';
    }

    if (isset($meta_data['canonical_url'])) {
        $tags[] = '<link rel="canonical" href="' . SITE_URL . e($meta_data['canonical_url']) . '">';
    }

    // Open Graph tags
    if (isset($meta_data['title'])) {
        $tags[] = '<meta property="og:title" content="' . e($meta_data['title']) . '">';
    }

    if (isset($meta_data['description'])) {
        $tags[] = '<meta property="og:description" content="' . e($meta_data['description']) . '">';
    }

    $tags[] = '<meta property="og:type" content="website">';
    $tags[] = '<meta property="og:url" content="' . SITE_URL . '">';

    return implode("\n    ", $tags);
}

/**
 * Generate schema markup
 */
function generate_schema($business_data) {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "LocalBusiness",
        "name" => $business_data['name'] ?? '',
        "description" => $business_data['tagline'] ?? '',
        "telephone" => $business_data['contact']['phone'] ?? '',
        "email" => $business_data['contact']['email'] ?? '',
        "address" => [
            "@type" => "PostalAddress",
            "streetAddress" => $business_data['contact']['address']['street'] ?? '',
            "addressLocality" => $business_data['contact']['address']['city'] ?? '',
            "addressRegion" => $business_data['contact']['address']['region'] ?? '',
            "postalCode" => $business_data['contact']['address']['postal_code'] ?? ''
        ]
    ];

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Check if current page is active
 */
function is_active_page($page_name) {
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
    return $current_page === $page_name;
}
?>