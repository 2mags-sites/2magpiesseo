<?php
// Admin Configuration for Editable Content
session_start();

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Admin Secret Key - In production, this should be in .env file
define('ADMIN_SECRET_KEY', 'barringtons_admin_2024_secure');
define('CACHE_CLEAR_KEY', 'clear_cache_2024');

// Check if admin mode should be activated
if (isset($_GET['admin']) && $_GET['admin'] === ADMIN_SECRET_KEY) {
    $_SESSION['admin_mode'] = true;
    $_SESSION['admin_login_time'] = time();
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Check if logout requested
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    unset($_SESSION['admin_mode']);
    unset($_SESSION['admin_login_time']);
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Check if cache clear requested
if (isset($_GET['clearcache']) && $_GET['clearcache'] === CACHE_CLEAR_KEY) {
    // Clear any cached content
    $cache_cleared = true;
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?cache_cleared=1');
    exit;
}

// Check admin session timeout (2 hours)
if (isset($_SESSION['admin_mode']) && isset($_SESSION['admin_login_time'])) {
    if (time() - $_SESSION['admin_login_time'] > 7200) {
        unset($_SESSION['admin_mode']);
        unset($_SESSION['admin_login_time']);
    } else {
        // Refresh login time on activity
        $_SESSION['admin_login_time'] = time();
    }
}

// Define admin mode constant
define('IS_ADMIN', isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === true);

/**
 * Check if admin mode is active
 */
function isAdminMode() {
    return IS_ADMIN;
}

/**
 * Validate CSRF token
 */
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get CSRF token
 */
function getCSRFToken() {
    return $_SESSION['csrf_token'] ?? '';
}

/**
 * Load content from JSON file
 */
function loadContent($page_name) {
    $content_file = __DIR__ . '/../content/' . $page_name . '.json';

    if (file_exists($content_file)) {
        $content = json_decode(file_get_contents($content_file), true);
        if ($content === null) {
            // Return default structure if JSON is invalid
            return getDefaultContent($page_name);
        }
        return $content;
    }

    // Create default content if file doesn't exist
    $default_content = getDefaultContent($page_name);
    saveContent($page_name, $default_content);
    return $default_content;
}

/**
 * Save content to JSON file
 */
function saveContent($page_name, $content) {
    $content_file = __DIR__ . '/../content/' . $page_name . '.json';
    $content_dir = dirname($content_file);

    if (!is_dir($content_dir)) {
        mkdir($content_dir, 0755, true);
    }

    return file_put_contents($content_file, json_encode($content, JSON_PRETTY_PRINT));
}

/**
 * Get default content structure for a page
 */
function getDefaultContent($page_name) {
    return [
        'meta' => [
            'title' => 'Page Title',
            'description' => 'Page description',
            'keywords' => 'page, keywords'
        ],
        'content' => [
            'hero' => [
                'title' => 'Welcome',
                'subtitle' => 'Subtitle text'
            ],
            'main' => [
                'text' => 'Main content text'
            ]
        ]
    ];
}

/**
 * Make text editable in admin mode
 */
function editable($value, $field_path, $type = 'text') {
    if (!IS_ADMIN) {
        return $value;
    }

    $data_field = htmlspecialchars($field_path);
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
    if ($current_page === '') $current_page = 'index';

    return '<span class="editable-content" data-field="' . $data_field . '" data-page="' . $current_page . '">' . $value . '</span>';
}

/**
 * Make image editable in admin mode
 */
function editableImage($src, $field_path, $placeholder_text = 'Click to upload image', $alt = '') {
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
    if ($current_page === '') $current_page = 'index';

    if (!IS_ADMIN) {
        if (empty($src)) {
            // Return placeholder image
            return '<img src="https://placehold.co/600x400/e5e7eb/6b7280?text=' . urlencode($placeholder_text) . '" alt="' . htmlspecialchars($alt) . '">';
        }
        return '<img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($alt) . '">';
    }

    $data_field = htmlspecialchars($field_path);
    $current_src = empty($src) ? 'https://placehold.co/600x400/e5e7eb/6b7280?text=' . urlencode($placeholder_text) : $src;

    return '<img class="editable-image" data-field="' . $data_field . '" data-page="' . $current_page . '" data-placeholder="' . htmlspecialchars($placeholder_text) . '" src="' . htmlspecialchars($current_src) . '" alt="' . htmlspecialchars($alt) . '" />';
}

// Admin bar will be added via JavaScript in admin.js
?>