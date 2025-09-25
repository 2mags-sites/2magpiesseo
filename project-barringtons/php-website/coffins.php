<?php
// Coffins Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('coffins');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Coffins & Caskets - Traditional & Eco-Friendly Options | Barringtons';
$page_description = $content['meta']['description'] ?? 'Wide range of coffins and caskets from traditional solid wood to eco-friendly options. Personalised designs available. Liverpool funeral directors.';
$page_keywords = $content['meta']['keywords'] ?? 'coffins Liverpool, caskets, eco coffins, wooden coffins, funeral coffins';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="coffins" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'Coffins & Caskets', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="intro-block fade-in">
                <h2><?php echo editable($content['intro']['title'] ?? 'Our Coffin Range', 'intro.title'); ?></h2>
                <p class="lead-text"><?php echo editable($content['intro']['subtitle'] ?? '', 'intro.subtitle'); ?></p>
            </div>

            <div class="services-list-grid">
                <?php foreach(($content['options'] ?? []) as $index => $option): ?>
                <div class="service-box fade-in">
                    <h3><?php echo editable($option['title'] ?? '', "options.$index.title"); ?></h3>
                    <p><?php echo editable($option['description'] ?? '', "options.$index.description"); ?></p>
                    <p class="price"><?php echo editable($option['price'] ?? '', "options.$index.price"); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php
// Include footer
require_once 'includes/footer.php';
?>