<?php
require_once 'config.php';
require_once 'functions.php';

// Auto-detect page name from script filename
$page_name = basename($_SERVER['SCRIPT_NAME'], '.php');
if (empty($page_name)) $page_name = 'matrimonial-lawyer';

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
        <section class="hero hero-service">
            <div class="container">
                <h1><?php echo e($content['hero']['title']); ?></h1>
                <p class="hero-subtitle"><?php echo e($content['hero']['subtitle']); ?></p>
                <div class="hero-buttons">
                    <a href="/contact" class="btn btn-primary"><?php echo e($content['hero']['cta_primary']); ?></a>
                    <a href="#learn-more" class="btn btn-secondary"><?php echo e($content['hero']['cta_secondary']); ?></a>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if (isset($content['services']) && is_array($content['services'])): ?>
        <section class="service-details" id="learn-more">
            <div class="container">
                <?php foreach ($content['services'] as $service): ?>
                <div class="service-block">
                    <h2><?php echo e($service['title']); ?></h2>
                    <p><?php echo e($service['description']); ?></p>
                    <?php if (isset($service['features']) && is_array($service['features'])): ?>
                    <ul class="feature-list">
                        <?php foreach ($service['features'] as $feature): ?>
                        <li><?php echo e($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if (isset($content['process']) && isset($content['process']['steps'])): ?>
        <section class="process">
            <div class="container">
                <h2><?php echo e($content['process']['title']); ?></h2>
                <div class="process-steps">
                    <?php foreach ($content['process']['steps'] as $step): ?>
                    <div class="process-step">
                        <div class="step-number"><?php echo e($step['step']); ?></div>
                        <h3><?php echo e($step['title']); ?></h3>
                        <p><?php echo e($step['description']); ?></p>
                        <?php if (isset($step['duration'])): ?>
                        <span class="duration">Duration: <?php echo e($step['duration']); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if (isset($page_data['faq']) && is_array($page_data['faq'])): ?>
        <section class="faq">
            <div class="container">
                <h2>Frequently Asked Questions</h2>
                <div class="faq-list" itemscope itemtype="https://schema.org/FAQPage">
                    <?php foreach ($page_data['faq'] as $item): ?>
                    <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                        <h3 class="faq-question" itemprop="name"><?php echo e($item['question']); ?></h3>
                        <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                            <p itemprop="text"><?php echo e($item['answer']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <section class="cta">
            <div class="container">
                <h2>Ready to Get Started?</h2>
                <p>Contact us today to discuss your needs.</p>
                <a href="/contact" class="btn btn-primary btn-lg">Get In Touch</a>
            </div>
        </section>
    </main>

    <?php include 'components/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>
</html>