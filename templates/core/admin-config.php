<?php
/**
 * STANDARDIZED ADMIN CONFIGURATION
 * Drop-in component for all PHP websites
 * Handles admin mode activation and content editing
 *
 * CRITICAL: This file MUST handle sessions independently
 * DO NOT rely on config.php for session management
 */

// CRITICAL: Start session HERE, not in config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Load environment variables DIRECTLY - DO NOT include config.php
// CRITICAL: Including config.php causes duplicate constant definitions
require_once __DIR__ . '/env-loader.php';

// Admin secret keys from environment (NO defaults for security)
// CRITICAL: Never use hardcoded defaults - they will be exposed in git
$adminKey = EnvLoader::get('ADMIN_SECRET_KEY');
$cacheKey = EnvLoader::get('CACHE_CLEAR_KEY');

if (empty($adminKey) || empty($cacheKey)) {
    error_log('SECURITY WARNING: Admin keys not set in .env file');
    // Use secure random keys that change every request if .env is missing
    // This means admin mode won't work without .env, which is intentional
    $adminKey = $adminKey ?: bin2hex(random_bytes(16));
    $cacheKey = $cacheKey ?: bin2hex(random_bytes(16));
}

define('ADMIN_SECRET_KEY', $adminKey);
define('CACHE_CLEAR_KEY', $cacheKey);

// Check for admin mode activation
if (isset($_GET['admin']) && $_GET['admin'] === ADMIN_SECRET_KEY) {
    $_SESSION['admin_mode'] = true;
    $_SESSION['admin_login_time'] = time();
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Check for admin logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    unset($_SESSION['admin_mode']);
    unset($_SESSION['admin_login_time']);
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Check for cache clear
if (isset($_GET['clearcache']) && $_GET['clearcache'] === CACHE_CLEAR_KEY) {
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Check admin session timeout (2 hours)
if (isset($_SESSION['admin_mode']) && isset($_SESSION['admin_login_time'])) {
    if (time() - $_SESSION['admin_login_time'] > 7200) {
        unset($_SESSION['admin_mode']);
        unset($_SESSION['admin_login_time']);
    } else {
        $_SESSION['admin_login_time'] = time();
    }
}

// Define admin mode constant
define('IS_ADMIN', isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === true);

/**
 * Check if admin mode is active (function for compatibility)
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
 * Verify CSRF token (alias for compatibility)
 */
function verifyCSRFToken($token) {
    return validateCSRFToken($token);
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
            error_log("Failed to decode JSON for page: $page_name");
            return [];
        }
        return $content;
    }

    error_log("Content file not found: $content_file");
    return [];
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

    $json_string = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    if ($json_string === false) {
        error_log("Failed to encode JSON for page: $page_name");
        return false;
    }

    $result = file_put_contents($content_file, $json_string);
    if ($result === false) {
        error_log("Failed to write content file: $content_file");
        return false;
    }

    return true;
}

/**
 * Make text content editable in admin mode
 * CRITICAL: Must use class="editable-content" for JavaScript to work
 * DO NOT use "editable-field" or any other class name
 */
function editable($value, $field_path, $type = 'text') {
    if (!IS_ADMIN) {
        return htmlspecialchars($value);
    }

    $data_field = htmlspecialchars($field_path);
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
    if ($current_page === '') $current_page = 'index';

    // CRITICAL: Must use "editable-content" class
    return '<span class="editable-content" data-field="' . $data_field . '" data-page="' . $current_page . '">' . htmlspecialchars($value) . '</span>';
}

/**
 * Make image editable in admin mode
 */
function editableImage($src, $field_path, $placeholder_text = '', $alt = '', $class = '') {
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
    if ($current_page === '') $current_page = 'index';

    if (IS_ADMIN) {
        $upload_overlay = '<div class="image-edit-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: 500; display: none;">📷 Click to Upload</div>';

        return '<div class="editable-image-container" data-field="' . $field_path . '" data-page="' . $current_page . '" style="position: relative; cursor: pointer;" data-placeholder="' . htmlspecialchars($placeholder_text) . '">
                    <img src="' . ($src ?: 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300"><rect width="400" height="300" fill="#e5e7eb"/><text x="200" y="150" text-anchor="middle" font-family="Arial" font-size="14" fill="#6b7280">' . ($placeholder_text ?: 'Click to upload image') . '</text></svg>')) . '" alt="' . htmlspecialchars($alt) . '" class="' . $class . '" style="width: 100%; height: 100%; object-fit: cover;">
                    ' . $upload_overlay . '
                </div>';
    }

    return '<img src="' . ($src ?: 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300"><rect width="400" height="300" fill="#e5e7eb"/><text x="200" y="150" text-anchor="middle" font-family="Arial" font-size="14" fill="#6b7280">' . ($placeholder_text ?: 'Image placeholder') . '</text></svg>')) . '" alt="' . htmlspecialchars($alt) . '" class="' . $class . '">';
}

/**
 * Generate admin bar HTML if in admin mode
 */
function renderAdminBar() {
    if (!IS_ADMIN) {
        return '';
    }

    ob_start();
    ?>
    <div id="admin-bar" style="position: fixed; top: 0; left: 0; right: 0; background: #1f2937; color: white; padding: 10px 20px; z-index: 9999; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 15px;">
            <strong>🔧 Admin Mode</strong>
            <span style="font-size: 0.9em; opacity: 0.8;">Click on text to edit</span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <button id="save-changes" style="background: #10b981; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: 500;">Save Changes</button>
            <button id="add-faq" style="background: #3b82f6; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: 500;">Add FAQ</button>
            <a href="?logout=true" style="background: #ef4444; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-weight: 500;">Exit Admin</a>
        </div>
    </div>
    <div style="height: 60px;"></div>
    <?php
    return ob_get_clean();
}
?>