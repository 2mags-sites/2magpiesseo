<?php
// Our Location Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('our-location');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Our Locations - Waterloo, Formby & Netherton | Barringtons';
$page_description = $content['meta']['description'] ?? 'Find Barringtons Funeral Services locations in Waterloo, Formby, and Netherton. Directions, parking, and opening hours for all branches.';
$page_keywords = $content['meta']['keywords'] ?? 'funeral directors Waterloo, funeral directors Formby, funeral directors Netherton, Barringtons locations';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="our-location" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'Our Locations', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="intro-block fade-in">
                <h2><?php echo editable($content['intro']['title'] ?? 'Serving Liverpool & Surrounding Areas', 'intro.title'); ?></h2>
                <p class="lead-text"><?php echo editable($content['intro']['subtitle'] ?? '', 'intro.subtitle'); ?></p>
            </div>

            <div class="branches-grid">
                <?php foreach(($content['branches'] ?? []) as $index => $branch): ?>
                <div class="branch-card fade-in">
                    <div class="branch-image">
                        <?php
                        $img = editableImage($branch['image'] ?? '', "branches.$index.image", $branch['name'] ?? 'Branch Image', $branch['name'] ?? '');
                        // Add style to the image
                        echo str_replace('<img ', '<img style="width: 100%; height: 200px; object-fit: cover;" ', $img);
                        ?>
                    </div>
                    <div class="branch-info">
                        <h3><?php echo editable($branch['name'] ?? '', "branches.$index.name"); ?></h3>
                        <p><strong>Address:</strong> <?php echo editable($branch['address'] ?? '', "branches.$index.address"); ?></p>
                        <p><strong>Phone:</strong> <?php echo editable($branch['phone'] ?? '', "branches.$index.phone"); ?></p>
                        <p><strong>Hours:</strong> <?php echo editable($branch['hours'] ?? '', "branches.$index.hours"); ?></p>
                        <p><strong>Parking:</strong> <?php echo editable($branch['parking'] ?? '', "branches.$index.parking"); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="cta-section fade-in">
                <h3><?php echo editable($content['cta']['title'] ?? 'Visit Us', 'cta.title'); ?></h3>
                <p><?php echo editable($content['cta']['subtitle'] ?? '', 'cta.subtitle'); ?></p>
                <a href="tel:01519281625" class="btn-primary">Call 0151 928 1625</a>
            </div>
        </div>
    </section>

<?php
// Include footer
require_once 'includes/footer.php';
?>