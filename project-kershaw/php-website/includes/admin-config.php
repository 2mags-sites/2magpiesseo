<?php
// Admin Configuration

// Load environment variables first (before any output)
require_once __DIR__ . '/env-loader.php';

// Start session after environment is loaded
if (session_status() === PHP_SESSION_NONE) {
    // Set session cookie parameters for local development
    $path = '/php-builder/website-rebuilder/project-kershaw/php-website/';
    session_set_cookie_params(0, $path);
    session_start();
}

// Get secret keys from environment or use defaults (for backward compatibility)
define('ADMIN_SECRET_KEY', EnvLoader::get('ADMIN_SECRET_KEY', 'kershaw2024admin'));
define('CACHE_CLEAR_KEY', EnvLoader::get('CACHE_CLEAR_KEY', ADMIN_SECRET_KEY));

// Check if admin mode is being activated
if (isset($_GET['admin']) && $_GET['admin'] === ADMIN_SECRET_KEY) {
    $_SESSION['admin_mode'] = true;
    // Redirect to remove the secret key from URL
    $redirect_url = strtok($_SERVER["REQUEST_URI"], '?');
    header('Location: ' . $redirect_url);
    exit();
}

// Check if cache clear is requested (requires secret key)
if (isset($_GET['clearcache']) && $_GET['clearcache'] === CACHE_CLEAR_KEY) {
    // Include cache functions
    require_once __DIR__ . '/cache.php';

    // Clear all cache
    $cache = new BlogCache();
    $cache->clear();

    // Also clear old-style cache if it exists
    $old_cache_dir = dirname(__DIR__) . '/content/cache';
    if (is_dir($old_cache_dir)) {
        $files = glob($old_cache_dir . '/*.json');
        foreach ($files as $file) {
            @unlink($file);
        }
    }

    // Set a success message in session
    $_SESSION['cache_cleared'] = true;

    // Redirect to remove the secret key from URL
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Check if logout is requested
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    unset($_SESSION['admin_mode']);
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Define admin mode constant
define('ADMIN_MODE', isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === true);

// Function to load content from JSON
function loadContent($page) {
    $file = __DIR__ . '/../content/' . $page . '.json';
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    }
    return false;
}

// Function to save content to JSON (only works in admin mode)
function saveContent($page, $content) {
    if (!ADMIN_MODE) {
        return false;
    }

    $file = __DIR__ . '/../content/' . $page . '.json';
    $json = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    return file_put_contents($file, $json);
}

// Function to get editable field
function editable($value, $field_path = '') {
    if (ADMIN_MODE && !empty($field_path)) {
        return '<span class="editable" data-field="' . htmlspecialchars($field_path) . '">' . $value . '</span>';
    }
    return $value;
}
?>