<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Include configuration
require_once __DIR__ . '/config.php';

// Set default meta tags if not set by page
if (!isset($page_title)) $page_title = SITE_TITLE;
if (!isset($page_description)) $page_description = 'Family-run funeral directors serving Liverpool for over 100 years. Compassionate, personal service available 24/7. Waterloo, Formby & Netherton locations.';
if (!isset($page_keywords)) $page_keywords = 'funeral directors Liverpool, independent funeral services, family funeral directors Merseyside';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">

    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/assets/images/logo.png">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FuneralHome",
        "name": "<?php echo $business_info['name']; ?>",
        "telephone": "<?php echo $business_info['phone']; ?>",
        "email": "<?php echo $business_info['email']; ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "23 Crosby Rd S",
            "addressLocality": "Waterloo, Liverpool",
            "postalCode": "L22 1RG",
            "addressCountry": "UK"
        },
        "openingHours": "Mo-Su 00:00-24:00",
        "priceRange": "££",
        "image": "<?php echo SITE_URL; ?>/assets/images/storefront.jpg",
        "url": "<?php echo SITE_URL; ?>"
    }
    </script>
</head>
<body>
    <header>
        <div class="header-content">
            <a href="index.php" class="logo">
                <img src="assets/images/logo.png" alt="Barringtons Logo" class="logo-image">
            </a>
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <nav>
                <a href="about-us.php">About Us</a>
                <div class="nav-dropdown">
                    <a href="#" class="dropdown-toggle">Obituaries <span class="arrow">▼</span></a>
                    <div class="dropdown-menu">
                        <a href="muchloved-tributes.php">Muchloved Tributes</a>
                        <a href="https://funeral-notices.co.uk/national/funeral-directors/Barrington+Funeral+Services/MzM0Mg==" target="_blank">Funeral Notices</a>
                    </div>
                </div>
                <div class="nav-dropdown">
                    <a href="#" class="dropdown-toggle">Services <span class="arrow">▼</span></a>
                    <div class="dropdown-menu">
                        <a href="immediate-need.php">Immediate Need</a>
                        <a href="when-a-death-occurs.php">When a Death Occurs</a>
                        <a href="standardised-price-list.php">Standardised Price List</a>
                        <a href="services-overview.php">Services Overview</a>
                        <a href="coffins.php">Coffins & Caskets</a>
                        <a href="personalisation.php">Personalisation</a>
                        <a href="transport.php">Transport</a>
                        <a href="pet-products.php">Pet Products</a>
                    </div>
                </div>
                <a href="planning-ahead.php">Plan Ahead</a>
                <div class="nav-dropdown">
                    <a href="#" class="dropdown-toggle">Resources <span class="arrow">▼</span></a>
                    <div class="dropdown-menu">
                        <a href="frequently-asked-questions.php">Frequently Asked Questions</a>
                        <a href="etiquette.php">Etiquette</a>
                        <a href="social-security-benefits.php">Social Security Benefits</a>
                        <a href="helpful-links.php">Helpful Links</a>
                    </div>
                </div>
                <a href="our-location.php">Locations</a>
                <a href="#contact">Contact Us</a>
                <a href="#estimator" class="contact-btn mobile-estimator">Funeral Estimator</a>
            </nav>
            <a href="#estimator" class="contact-btn desktop-estimator">Funeral Estimator</a>
        </div>
    </header>

    <?php if (IS_ADMIN): ?>
    <!-- Admin Mode Bar -->
    <div id="admin-bar" style="position: fixed; top: 0; left: 0; right: 0; background: #2563eb; color: white; padding: 10px 20px; z-index: 10000; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 20px;">
            <span style="font-weight: bold;">🔧 Admin Mode Active</span>
            <span style="font-size: 14px; opacity: 0.9;">Click any text or image to edit</span>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="editPageSEO()" style="background: #10b981; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: 500;">🔍 Edit SEO</button>
            <button onclick="saveAllChanges()" style="background: white; color: #2563eb; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: 500;">💾 Save Changes</button>
            <a href="?logout=true" style="background: #dc2626; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-weight: 500;">Logout</a>
        </div>
    </div>
    <style>
        body { padding-top: 50px !important; }
        .editable-content {
            outline: 1px dashed #2563eb;
            outline-offset: 2px;
            min-height: 20px;
            display: inline-block;
            min-width: 50px;
        }
        .editable-content:hover {
            outline: 2px solid #2563eb;
            background: rgba(37, 99, 235, 0.05);
        }
        .editable-content:focus {
            outline: 2px solid #2563eb;
            background: rgba(37, 99, 235, 0.1);
        }
        .editable-hero-bg {
            position: relative !important;
            cursor: pointer;
        }
        .hero-edit-overlay {
            z-index: 10;
            transition: all 0.3s ease;
        }
        .hero-edit-overlay:hover {
            transform: translate(-50%, -50%) scale(1.05) !important;
            background: rgba(37, 99, 235, 1) !important;
        }
    </style>
    <?php endif; ?>