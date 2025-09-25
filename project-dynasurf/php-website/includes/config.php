<?php
/**
 * Site Configuration Template
 * Core configuration file for PHP websites
 */

// Load environment variables
require_once __DIR__ . '/env-loader.php';

// Site Configuration from Environment
define('SITE_NAME', 'Dynasurf');
define('SITE_TITLE', 'Dynasurf (UK) Ltd - Precision Engineering Since 1971');
define('SITE_URL', EnvLoader::get('APP_URL', 'https://dynasurf.co.uk'));
define('SITE_EMAIL', 'sales@dynasurf.co.uk');
define('SITE_PHONE', '01270 763032');

// Business Information
$business_info = [
    'name' => 'Dynasurf (UK) Ltd',
    'phone' => '01270 763032',
    'email' => 'sales@dynasurf.co.uk',
    'address' => 'Millbuck Way, Springvale Industrial Estate, Sandbach, Cheshire, CW11 3GQ',
    'hours' => 'Mon-Thu: 7:15am-4:30pm, Fri: 7:15am-12noon',
    'company_number' => '',
    'established' => '1971'
];

// Contact Form Settings
define('CONTACT_EMAIL_TO', EnvLoader::get('CONTACT_TO_EMAIL', 'info@example.com'));
define('CONTACT_EMAIL_FROM', EnvLoader::get('CONTACT_FROM_EMAIL', 'noreply@example.com'));
define('CONTACT_EMAIL_FROM_NAME', EnvLoader::get('CONTACT_FROM_NAME', 'Website Contact'));

// Admin Settings
if (!defined('ADMIN_MODE')) {
    define('ADMIN_MODE', isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === true);
}
if (!defined('IS_ADMIN')) {
    define('IS_ADMIN', ADMIN_MODE); // Alias for compatibility
}

// Development/Production Mode
define('DEV_MODE', EnvLoader::get('APP_ENV', 'production') === 'development');
define('SHOW_ERRORS', EnvLoader::get('APP_DEBUG', 'false') === 'true');

if (SHOW_ERRORS) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}

// Start session for admin mode and CSRF tokens
// IMPORTANT: Use proper session_status() check to avoid conflicts
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Helper function to get CSRF token
function getCSRFToken() {
    return $_SESSION['csrf_token'] ?? '';
}

// Helper function to verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Helper function for safe output
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Helper function to check if running on localhost
function isLocalhost() {
    $whitelist = ['127.0.0.1', '::1', 'localhost'];
    return in_array($_SERVER['SERVER_NAME'] ?? '', $whitelist) ||
           in_array($_SERVER['REMOTE_ADDR'] ?? '', $whitelist);
}

// Set timezone (can be overridden in .env)
date_default_timezone_set(EnvLoader::get('TIMEZONE', 'Europe/London'));
?>