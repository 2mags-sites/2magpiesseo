<?php
/**
 * Router for PHP Built-in Server
 * This handles URL rewriting since .htaccess doesn't work with php -S
 */

// Get the requested URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove leading slash
$uri = ltrim($uri, '/');

// If no URI or just slash, serve index.php
if (empty($uri)) {
    require 'index.php';
    return;
}

// Check if the file exists as-is (for assets, etc.)
if (file_exists($uri) && !is_dir($uri)) {
    // Let PHP's built-in server handle static files
    return false;
}

// Check if it's a PHP file request without extension
if (!preg_match('/\.(php|html|css|js|jpg|jpeg|png|gif|ico|xml|txt)$/i', $uri)) {
    // Try to find the PHP file
    if (file_exists($uri . '.php')) {
        // Set the SCRIPT_NAME correctly so the PHP file knows its name
        $_SERVER['SCRIPT_NAME'] = '/' . $uri . '.php';
        $_SERVER['PHP_SELF'] = '/' . $uri . '.php';
        require $uri . '.php';
        return;
    }
}

// If nothing matched, try to serve it directly
if (file_exists($uri)) {
    return false;
}

// Default to 404
require '404.php';
?>