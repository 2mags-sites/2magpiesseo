<?php
/**
 * Simple Configuration File
 * Just the essentials that appear in multiple places
 * All content is already generated in the HTML/PHP pages
 */

// Determine base URL based on environment
if ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
    // Local development
    define('BASE_URL', '/php-builder/website-rebuilder/project-kershaw/php-website');
} else {
    // Production
    define('BASE_URL', '');
}

// Basic contact information for header/footer
$SITE_CONFIG = [
    'business_name' => 'Arthur Kershaw Funeral Services',
    'phone' => '0161 969 2288',
    'email' => 'info@arthurkershawfunerals.com',
    'address' => '168-170 Washway Road',
    'city' => 'Sale',
    'postcode' => 'M33 6RH',
    'available' => '24/7'
];

// For Schema.org generation, read the existing JSON
// (Same files AI used to generate content)
function getBusinessData() {
    $file = __DIR__ . '/../../01-scraping/business-info.json';
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    }
    return null;
}

function getSEOData($page) {
    $file = __DIR__ . '/../../03-seo/keyword-mapping.json';
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        return isset($data['pages'][$page]) ? $data['pages'][$page] : null;
    }
    return null;
}

// Include admin configuration (for admin mode and cache clearing)
require_once __DIR__ . '/admin-config.php';
?>