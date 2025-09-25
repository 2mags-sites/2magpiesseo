<?php
// Our Facilities Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('our-facilities');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Our Facilities - Chapel of Rest & Arrangement Rooms | Barringtons';
$page_description = $content['meta']['description'] ?? 'Private chapels of rest, comfortable arrangement rooms, and peaceful surroundings at our Liverpool funeral homes. 24 hour access available.';
$page_keywords = $content['meta']['keywords'] ?? 'funeral home facilities, chapel of rest Liverpool, funeral parlour, viewing rooms';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="our-facilities" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'Our Facilities', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="intro-block fade-in">
                <h2><?php echo editable($content['intro']['title'] ?? 'Peaceful & Dignified Surroundings', 'intro.title'); ?></h2>
                <p class="lead-text"><?php echo editable($content['intro']['subtitle'] ?? '', 'intro.subtitle'); ?></p>
            </div>

            <div class="services-list-grid">
                <?php foreach(($content['facilities'] ?? []) as $index => $facility): ?>
                <div class="service-box fade-in">
                    <h3><?php echo editable($facility['title'] ?? '', "facilities.$index.title"); ?></h3>
                    <p><?php echo editable($facility['description'] ?? '', "facilities.$index.description"); ?></p>
                    <?php if(!empty($facility['features'])): ?>
                    <ul>
                        <?php foreach($facility['features'] as $feature): ?>
                        <li><?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="info-card fade-in">
                <h3><?php echo editable($content['access']['title'] ?? '24 Hour Access', 'access.title'); ?></h3>
                <p><?php echo editable($content['access']['description'] ?? '', 'access.description'); ?></p>
                <p><strong><?php echo editable($content['access']['cta'] ?? '', 'access.cta'); ?></strong></p>
            </div>
        </div>
    </section>

<?php
// Include footer
require_once 'includes/footer.php';
?>