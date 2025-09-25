<?php
// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Arthur Kershaw Funeral Services | Funeral Directors Sale, Manchester'; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Family-owned funeral directors in Sale, Manchester. Established 1892. Compassionate funeral services including burial, cremation and pre-paid plans. Available 24/7.'; ?>">
    <meta name="keywords" content="<?php echo isset($page_keywords) ? $page_keywords : 'funeral directors Sale, Arthur Kershaw funerals, funeral services Manchester'; ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://www.arthurkershawfunerals.com<?php echo $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="<?php echo isset($page_title) ? $page_title : 'Arthur Kershaw Funeral Services'; ?>">
    <meta property="og:description" content="<?php echo isset($page_description) ? $page_description : 'Family-owned funeral directors in Sale, Manchester since 1892'; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.arthurkershawfunerals.com<?php echo $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/blog-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FuneralHome",
        "name": "Arthur Kershaw Funeral Services",
        "image": "https://www.arthurkershawfunerals.com/assets/images/logo.png",
        "@id": "https://www.arthurkershawfunerals.com",
        "url": "https://www.arthurkershawfunerals.com",
        "telephone": "0161 969 2288",
        "priceRange": "£995 - £5000",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "168-170 Washway Road",
            "addressLocality": "Sale",
            "addressRegion": "Greater Manchester",
            "postalCode": "M33 6RH",
            "addressCountry": "GB"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": 53.4241,
            "longitude": -2.3214
        },
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
                "opens": "09:00",
                "closes": "17:00"
            },
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": "Saturday",
                "opens": "09:00",
                "closes": "12:00"
            }
        ],
        "sameAs": [
            "https://www.facebook.com/arthurkershawfunerals",
            "https://www.google.com/maps/place/Arthur+Kershaw+Funeral+Services"
        ],
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Funeral Services",
            "itemListElement": [
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Direct Cremation",
                        "description": "Simple, affordable cremation without a service"
                    },
                    "price": "995",
                    "priceCurrency": "GBP"
                },
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Traditional Funeral",
                        "description": "Full funeral service with procession"
                    },
                    "price": "3495",
                    "priceCurrency": "GBP"
                }
            ]
        }
    }
    </script>
</head>
<body>
    <?php
    // Display cache cleared notification
    if (isset($_SESSION['cache_cleared']) && $_SESSION['cache_cleared']) {
        unset($_SESSION['cache_cleared']);
        echo '<div style="background: #4CAF50; color: white; padding: 10px; text-align: center; position: fixed; top: 0; left: 0; right: 0; z-index: 10000;">Cache successfully cleared!</div>';
        echo '<script>setTimeout(function(){ document.querySelector("div[style*=\'4CAF50\']").remove(); }, 3000);</script>';
    }
    ?>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-container">
                <a href="<?php echo BASE_URL; ?>/" class="logo">Arthur Kershaw Funeral Services</a>
                <button class="mobile-menu-toggle" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/" class="nav-link <?php echo $current_page == 'index' ? 'active' : ''; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/about.php" class="nav-link <?php echo $current_page == 'about' ? 'active' : ''; ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Services <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown">
                            <a href="<?php echo BASE_URL; ?>/service-traditional-burial.php">Traditional Burial</a>
                            <a href="<?php echo BASE_URL; ?>/service-cremation.php">Cremation Service</a>
                            <a href="<?php echo BASE_URL; ?>/service-direct-cremation.php">Direct Cremation</a>
                            <a href="<?php echo BASE_URL; ?>/pre-paid-plans.php">Pre-Paid Funeral Plans</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Locations <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown">
                            <a href="<?php echo BASE_URL; ?>/funeral-directors-sale.php">Sale</a>
                            <a href="<?php echo BASE_URL; ?>/funeral-directors-stretford.php">Stretford</a>
                            <a href="<?php echo BASE_URL; ?>/funeral-directors-altrincham.php">Altrincham</a>
                            <a href="<?php echo BASE_URL; ?>/funeral-directors-timperley.php">Timperley</a>
                            <a href="<?php echo BASE_URL; ?>/funeral-directors-ashton-upon-mersey.php">Ashton-Upon-Mersey</a>
                            <a href="<?php echo BASE_URL; ?>/funeral-directors-hale.php">Hale</a>
                            <a href="<?php echo BASE_URL; ?>/funeral-directors-lymm.php">Lymm</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Information <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown">
                            <a href="<?php echo BASE_URL; ?>/funeral-costs.php">Funeral Costs</a>
                            <a href="<?php echo BASE_URL; ?>/arranging-a-funeral.php">Arranging a Funeral</a>
                            <a href="<?php echo BASE_URL; ?>/faqs.php">FAQs</a>
                            <a href="<?php echo BASE_URL; ?>/testimonials.php">Testimonials</a>
                            <a href="<?php echo BASE_URL; ?>/funeral-notices.php">Funeral Notices</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/blog.php" class="nav-link <?php echo $current_page == 'blog' ? 'active' : ''; ?>">News</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/contact.php" class="nav-link <?php echo $current_page == 'contact' ? 'active' : ''; ?>">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>