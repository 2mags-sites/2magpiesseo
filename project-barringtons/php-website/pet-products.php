<?php
// Pet Products Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('pet-products');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Pet Cremation & Memorial Products | Barringtons Liverpool';
$page_description = $content['meta']['description'] ?? 'Compassionate pet cremation services and memorial products. Individual cremation, ashes caskets, and memorial jewelry for beloved pets.';
$page_keywords = $content['meta']['keywords'] ?? 'pet cremation Liverpool, pet memorial, pet ashes, pet funeral';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="pet-products" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'Pet Services', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="intro-block fade-in">
                <h2><?php echo editable($content['intro']['title'] ?? 'Saying Goodbye to a Beloved Pet', 'intro.title'); ?></h2>
                <p class="lead-text"><?php echo editable($content['intro']['lead_text'] ?? 'Pets are family members too. We offer dignified cremation services and beautiful memorial products to honor their memory.', 'intro.lead_text'); ?></p>
            </div>

            <div class="services-list-grid">
                <?php if (isset($content['services']) && is_array($content['services'])): ?>
                    <?php foreach ($content['services'] as $index => $service): ?>
                <div class="service-box fade-in">
                    <h3><?php echo editable($service['title'] ?? '', "services.{$index}.title"); ?></h3>
                    <p><?php echo editable($service['description'] ?? '', "services.{$index}.description"); ?></p>
                    <p class="price"><?php echo editable($service['price'] ?? '', "services.{$index}.price"); ?></p>
                </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="info-card fade-in">
                <p><?php echo editable($content['info_card']['text'] ?? 'We understand the deep bond between pets and their families. Our caring team will treat your pet with the same dignity and respect we show to all those in our care.', 'info_card.text'); ?></p>
                <p><strong><?php echo editable($content['info_card']['contact_text'] ?? 'Call 0151 928 1625', 'info_card.contact_text'); ?></strong> <?php echo editable($content['info_card']['contact_action'] ?? 'to discuss arrangements.', 'info_card.contact_action'); ?></p>
            </div>
        </div>
    </section>

<?php
// Include footer
require_once 'includes/footer.php';
?>