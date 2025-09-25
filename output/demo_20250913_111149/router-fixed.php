<?php
/**
 * Fixed Router for PHP Built-in Server
 * Handles clean URLs without .php extension
 * Usage: php -S localhost:8000 router-fixed.php
 */

// Get the requested URI
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$path = ltrim($path, '/');

// Debug output (comment out in production)
error_log("Router: Requested URI: $uri, Path: $path");

// If empty path, serve index
if (empty($path)) {
    require 'index.php';
    return;
}

// Check if it's a static file that exists
if (file_exists($path)) {
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    // If it's a PHP file, execute it
    if ($ext === 'php') {
        require $path;
        return;
    }
    // For other static files, let PHP's server handle them
    if (in_array($ext, ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'ico', 'xml', 'txt', 'woff', 'woff2', 'ttf', 'svg'])) {
        return false;
    }
}

// Check if adding .php would make it a valid file
$php_file = $path . '.php';
if (file_exists($php_file)) {
    // Update server variables so the PHP file knows its identity
    $_SERVER['SCRIPT_NAME'] = '/' . $php_file;
    $_SERVER['PHP_SELF'] = '/' . $php_file;
    $_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/' . $php_file;

    error_log("Router: Loading $php_file");
    require $php_file;
    return;
}

// If nothing matched, show 404
http_response_code(404);
if (file_exists('404.php')) {
    require '404.php';
} else {
    echo '<h1>404 Not Found</h1>';
    echo '<p>The requested page was not found.</p>';
}
?>