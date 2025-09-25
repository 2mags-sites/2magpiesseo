<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include configuration
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/admin-config.php';

// Default meta values if not set
$page_title = $page_title ?? 'Precision Engineering Services Sandbach | Dynasurf UK Ltd';
$page_description = $page_description ?? 'Leading precision engineering company in Sandbach offering hydraulic ram repair, cylinder honing up to 300mm, and hydraulic hose manufacturing. Express service available. Call 01270 763032.';
$page_keywords = $page_keywords ?? 'precision engineering services Sandbach, hydraulic hose manufacturing Cheshire, cylinder honing services UK, hydraulic ram repair';

// Current page for navigation highlighting
$current_page = basename($_SERVER['PHP_SELF'], '.php');
if ($current_page == 'index') $current_page = 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">

    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/dyna.css">
    <?php if (basename($_SERVER['PHP_SELF']) !== 'index.php'): ?>
    <link rel="stylesheet" href="assets/css/service-pages.css">
    <?php endif; ?>

    <!-- CSRF Token for Forms -->
    <meta name="csrf-token" content="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

    <!-- Schema.org LocalBusiness -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "<?php echo $business_info['name']; ?>",
        "description": "Precision engineering specialists in hydraulic repairs, cylinder honing, and hydraulic hose manufacturing since 1971",
        "url": "<?php echo SITE_URL; ?>",
        "telephone": "<?php echo $business_info['phone']; ?>",
        "email": "<?php echo $business_info['email']; ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Millbuck Way, Springvale Industrial Estate",
            "addressLocality": "Sandbach",
            "addressRegion": "Cheshire",
            "postalCode": "CW11 3GQ",
            "addressCountry": "GB"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": 53.1447,
            "longitude": -2.3626
        },
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday"],
                "opens": "07:15",
                "closes": "16:30"
            },
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": "Friday",
                "opens": "07:15",
                "closes": "12:00"
            }
        ],
        "foundingDate": "1971",
        "priceRange": "££",
        "areaServed": {
            "@type": "GeoCircle",
            "geoMidpoint": {
                "@type": "GeoCoordinates",
                "latitude": 53.1447,
                "longitude": -2.3626
            },
            "geoRadius": "50000"
        }
    }
    </script>
</head>
<body>
    <?php if (IS_ADMIN) { echo renderAdminBar(); } ?>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="nav-container">
                <a href="/" class="logo">
                    <img src="/assets/images/dynasurf_logo.png" alt="Dynasurf UK Ltd - Precision Engineering" />
                </a>
                <nav class="main-nav">
                    <ul>
                        <li><a href="/" <?php echo $current_page == 'home' ? 'class="active"' : ''; ?>>Home</a></li>
                        <li><a href="/about.php" <?php echo $current_page == 'about' ? 'class="active"' : ''; ?>>About</a></li>
                        <li class="dropdown">
                            <a href="#" <?php echo in_array($current_page, ['hydraulic-ram-repair', 'cylinder-tube-honing', 'hydraulic-hose-manufacturing']) ? 'class="active"' : ''; ?>>Services <span class="dropdown-arrow">▼</span></a>
                            <div class="dropdown-menu">
                                <a href="/hydraulic-ram-repair.php">Hydraulic Ram Repair</a>
                                <a href="/cylinder-tube-honing.php">Cylinder Tube Honing</a>
                                <a href="/hydraulic-hose-manufacturing.php">Hydraulic Hose Manufacturing</a>
                                <div class="dropdown-divider"></div>
                                <a href="/#services">All Services</a>
                            </div>
                        </li>
                        <li><a href="/news/" <?php echo $current_page == 'news' ? 'class="active"' : ''; ?>>News</a></li>
                        <li><a href="/contact.php" <?php echo $current_page == 'contact' ? 'class="active"' : ''; ?>>Contact</a></li>
                    </ul>
                </nav>
                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>
            </div>
        </div>
    </header>

    <div class="mobile-overlay" onclick="closeMobileMenu()"></div>