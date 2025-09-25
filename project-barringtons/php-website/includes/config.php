<?php
// Site Configuration
define('SITE_NAME', 'Barringtons');
define('SITE_TITLE', 'Barringtons - Independent Funeral Services');
define('SITE_URL', 'https://barringtonsfunerals.co.uk');
define('SITE_EMAIL', 'care@barringtonsfunerals.co.uk');
define('SITE_PHONE', '0151 928 1625');

// Business Information
$business_info = [
    'name' => 'Barringtons Independent Funeral Services',
    'phone' => '0151 928 1625',
    'email' => 'care@barringtonsfunerals.co.uk',
    'address' => '23 Crosby Rd S, Waterloo, Liverpool L22 1RG',
    'hours' => '24 hours a day, 7 days a week',
    'company_number' => '07587745'
];

// Locations
$locations = [
    'waterloo' => [
        'name' => 'Waterloo',
        'address' => '23 Crosby Rd S, Waterloo, L22 1RG',
        'hours' => 'Open 24 hours'
    ],
    'netherton' => [
        'name' => 'Netherton',
        'address' => '47 Liverpool Road, Netherton, L30 3QA',
        'hours' => 'Monday-Friday 9am-5pm'
    ],
    'formby' => [
        'name' => 'Formby',
        'address' => '64 Elbow Lane, Formby, L37 4AB',
        'hours' => 'By appointment'
    ]
];

// Contact Form Settings
define('CONTACT_EMAIL_TO', 'care@barringtonsfunerals.co.uk');
define('CONTACT_EMAIL_FROM', 'noreply@barringtonsfunerals.co.uk');
define('CONTACT_EMAIL_FROM_NAME', 'Barringtons Website');

// Admin Settings (will be moved to .env file in production)
define('ADMIN_MODE', isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === true);

// Development/Production Mode
define('DEV_MODE', false);
define('SHOW_ERRORS', false);

if (SHOW_ERRORS) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Start session for admin mode
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>