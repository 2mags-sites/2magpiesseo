<?php
/**
 * Router for PHP Built-in Server
 * Handles clean URLs without .php extension
 * Usage: php -S localhost:8000 router.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = ltrim($uri, '/');

// If no URI, serve index
if (empty($uri)) {
    $uri = 'index';
}

// Check for static files
if (file_exists($uri) && !is_dir($uri) && preg_match('/\.(css|js|jpg|jpeg|png|gif|ico|xml|txt|woff|woff2|ttf|svg)$/i', $uri)) {
    return false; // Let PHP handle static files
}

// For PHP pages without extension
if (!preg_match('/\.(php|html|css|js|jpg|jpeg|png|gif|ico|xml|txt)$/i', $uri)) {
    if (file_exists($uri . '.php')) {
        // Set the correct script name for page detection
        $_SERVER['SCRIPT_NAME'] = '/' . $uri . '.php';
        $_SERVER['PHP_SELF'] = '/' . $uri . '.php';
        $_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/' . $uri . '.php';
        chdir(dirname($_SERVER['SCRIPT_FILENAME']));
        require $uri . '.php';
        return;
    }
}

// Default handling
if (file_exists($uri)) {
    return false;
}

// 404 page
if (file_exists('404.php')) {
    require '404.php';
} else {
    http_response_code(404);
    echo '404 Not Found';
}
?>