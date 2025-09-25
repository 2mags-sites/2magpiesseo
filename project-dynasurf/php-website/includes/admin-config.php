<?php
/**
 * Admin Configuration and Helper Functions
 */

// Load environment variables
require_once __DIR__ . '/env-loader.php';

// Define admin mode based on session (only if not already defined)
if (!defined('IS_ADMIN')) {
    define('IS_ADMIN', isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === true);
}

// Admin authentication keys from environment
define('ADMIN_SECRET_KEY', EnvLoader::get('ADMIN_SECRET_KEY', 'dynasurf_admin_2024_xK9mP3nQ'));
define('CACHE_CLEAR_KEY', EnvLoader::get('CACHE_CLEAR_KEY', 'clear_dynasurf_cache_7R2sL5vW'));

// Check for admin mode activation/deactivation
if (isset($_GET['admin']) && $_GET['admin'] === ADMIN_SECRET_KEY) {
    $_SESSION['admin_mode'] = true;
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    unset($_SESSION['admin_mode']);
    session_destroy();
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Check for cache clear
if (isset($_GET['clearcache']) && $_GET['clearcache'] === CACHE_CLEAR_KEY) {
    $cacheDir = __DIR__ . '/../cache/';
    if (is_dir($cacheDir)) {
        $files = glob($cacheDir . '*');
        foreach($files as $file) {
            if(is_file($file)) {
                unlink($file);
            }
        }
    }
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

/**
 * Load content from JSON file
 */
function loadContent($page) {
    $jsonFile = __DIR__ . '/../content/' . $page . '.json';

    if (file_exists($jsonFile)) {
        $content = json_decode(file_get_contents($jsonFile), true);
        return $content ?: [];
    }

    // Return default structure if file doesn't exist
    return [
        'meta' => [
            'title' => '',
            'description' => '',
            'keywords' => ''
        ],
        'hero' => [
            'title' => '',
            'subtitle' => '',
            'image' => ''
        ],
        'sections' => []
    ];
}

/**
 * Save content to JSON file
 */
function saveContent($page, $content) {
    $jsonFile = __DIR__ . '/../content/' . $page . '.json';

    // Ensure content directory exists
    $contentDir = dirname($jsonFile);
    if (!is_dir($contentDir)) {
        mkdir($contentDir, 0755, true);
    }

    // Save content
    return file_put_contents($jsonFile, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

/**
 * Make text content editable in admin mode
 */
function editable($value, $fieldPath, $type = 'text') {
    if (!IS_ADMIN) {
        return $value;
    }

    $classes = 'editable-content editable-' . $type;
    $html = '<span class="' . $classes . '" data-field="' . htmlspecialchars($fieldPath) . '">';
    $html .= $value;
    $html .= '</span>';

    return $html;
}

/**
 * Make image editable in admin mode
 */
function editableImage($src, $fieldPath, $placeholder = 'Click to upload image', $alt = '') {
    if (!IS_ADMIN) {
        if (empty($src)) {
            $src = placeholderImage($placeholder);
        }
        return '<img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($alt) . '" />';
    }

    if (empty($src)) {
        $src = placeholderImage($placeholder);
    }

    $html = '<div class="editable-image" data-field="' . htmlspecialchars($fieldPath) . '">';
    $html .= '<img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($alt) . '" />';
    $html .= '<div class="image-edit-overlay">📷 Click to Change Image</div>';
    $html .= '</div>';

    return $html;
}

/**
 * Generate a placeholder image
 */
function placeholderImage($text, $width = 600, $height = 400) {
    // Generate a simple data URI placeholder
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '">';
    $svg .= '<rect width="100%" height="100%" fill="#e5e7eb"/>';
    $svg .= '<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="Arial" font-size="16" fill="#6b7280">';
    $svg .= htmlspecialchars($text);
    $svg .= '</text></svg>';

    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

/**
 * Update nested value in array using dot notation
 */
function updateNestedValue(&$array, $path, $value) {
    $keys = explode('.', $path);
    $current = &$array;

    foreach ($keys as $i => $key) {
        // Check if this is an array index
        if (preg_match('/(.+)\[(\d+)\]/', $key, $matches)) {
            $arrayKey = $matches[1];
            $index = $matches[2];

            if (!isset($current[$arrayKey])) {
                $current[$arrayKey] = [];
            }

            if ($i === count($keys) - 1) {
                $current[$arrayKey][$index] = $value;
            } else {
                if (!isset($current[$arrayKey][$index])) {
                    $current[$arrayKey][$index] = [];
                }
                $current = &$current[$arrayKey][$index];
            }
        } else {
            if ($i === count($keys) - 1) {
                $current[$key] = $value;
            } else {
                if (!isset($current[$key])) {
                    $current[$key] = [];
                }
                $current = &$current[$key];
            }
        }
    }
}

// Admin Bar HTML (included when IS_ADMIN is true)
function renderAdminBar() {
    if (!IS_ADMIN) {
        return '';
    }

    $currentPage = basename($_SERVER['PHP_SELF'], '.php');

    return '
    <div id="admin-bar" style="position: fixed; top: 0; left: 0; right: 0; background: #2c3e50; color: white; padding: 10px 20px; z-index: 10000; display: flex; justify-content: space-between; align-items: center; font-family: -apple-system, sans-serif;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <span style="font-weight: bold;">🔧 Admin Mode</span>
            <span style="opacity: 0.8;">Editing: ' . $currentPage . '</span>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="editSEO()" style="background: #3498db; color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer;">Edit SEO</button>
            <button onclick="addFAQ()" style="background: #27ae60; color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer;">Add FAQ</button>
            <button onclick="saveChanges()" style="background: #e74c3c; color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer; font-weight: bold;">Save Changes</button>
            <a href="?logout=true" style="background: #95a5a6; color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">Logout</a>
        </div>
    </div>
    <div style="height: 50px;"></div>';
}
?>