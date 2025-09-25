<?php
/**
 * Configuration File
 * PHP Website Builder - Configuration
 */

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Site settings
define('SITE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]");
define('SITE_ROOT', dirname(__FILE__));
define('DATA_DIR', SITE_ROOT . '/data');

// Email settings for contact form
define('CONTACT_EMAIL', 'info@example.com'); // Change this to your email
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 25);

// Performance settings
define('ENABLE_CACHE', true);
define('CACHE_TIME', 3600); // 1 hour

// SEO settings
define('ENABLE_SITEMAP', true);
define('ENABLE_ROBOTS_TXT', true);

// Security settings
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
?>