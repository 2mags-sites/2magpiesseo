<?php
require_once 'config.php';
require_once 'functions.php';

// Auto-detect page name from script filename
$page_name = basename($_SERVER['SCRIPT_NAME'], '.php');
if (empty($page_name)) $page_name = 'index';

// Load page data
$page_data = get_page_data($page_name);
$business_data = get_universal_data('business');
$navigation_data = get_universal_data('navigation');
$contact_data = get_universal_data('contact');

// Get meta data
$meta = isset($page_data['meta']) ? $page_data['meta'] : [];
$content = isset($page_data['content']) ? $page_data['content'] : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo generate_meta_tags($meta); ?>

    <!-- HTML5 Boilerplate CSS -->
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <!-- Schema.org markup -->
    <?php echo generate_schema($business_data); ?>

    <!-- Additional meta tags -->
    <meta name="theme-color" content="#fafafa">
    <meta name="robots" content="index, follow">
    <meta name="author" content="<?php echo e($business_data['name']); ?>">
</head>
<body>
    <?php include 'components/header.php'; ?>

    <main role="main">
        
        <?php if (isset($content['hero'])): ?>
        <section class="hero">
            <div class="container">
                <h1><?php echo e($content['hero']['title']); ?></h1>
                <p class="hero-subtitle"><?php echo e($content['hero']['subtitle']); ?></p>
                <div class="hero-buttons">
                    <a href="/contact" class="btn btn-primary"><?php echo e($content['hero']['cta_primary']); ?></a>
                    <a href="/about" class="btn btn-secondary"><?php echo e($content['hero']['cta_secondary']); ?></a>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if (isset($content['features']) && is_array($content['features'])): ?>
        <section class="features">
            <div class="container">
                <h2>Why Choose Us</h2>
                <div class="features-grid">
                    <?php foreach ($content['features'] as $feature): ?>
                    <div class="feature-item">
                        <h3><?php echo e($feature['title']); ?></h3>
                        <p><?php echo e($feature['description']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if (isset($content['services_overview']) && is_array($content['services_overview'])): ?>
        <section class="services-overview">
            <div class="container">
                <h2>Our Services</h2>
                <div class="services-grid">
                    <?php foreach ($content['services_overview'] as $service): ?>
                    <div class="service-card">
                        <h3><?php echo e($service['title']); ?></h3>
                        <p><?php echo e($service['description']); ?></p>
                        <?php if (isset($service['link'])): ?>
                        <a href="<?php echo e($service['link']); ?>" class="btn-link">Learn More →</a>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <?php include 'components/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>
</html>