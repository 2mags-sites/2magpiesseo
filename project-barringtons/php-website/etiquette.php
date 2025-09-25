<?php
// Etiquette Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('etiquette');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Funeral Etiquette Guide | What to Wear & How to Act | Barringtons';
$page_description = $content['meta']['description'] ?? 'Complete guide to funeral etiquette. What to wear, what to say, how to behave at a funeral. Helpful advice for attending or planning a funeral service.';
$page_keywords = $content['meta']['keywords'] ?? 'funeral etiquette, what to wear to funeral, funeral behavior, funeral customs, funeral traditions';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="etiquette" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'Funeral Etiquette', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="intro-block fade-in">
                <h2><?php echo editable($content['intro']['title'] ?? 'A Guide to Funeral Etiquette', 'intro.title'); ?></h2>
                <p class="lead-text"><?php echo editable($content['intro']['subtitle'] ?? '', 'intro.subtitle'); ?></p>
            </div>

            <div class="guide-sections">
                <?php foreach(($content['sections'] ?? []) as $index => $section): ?>
                <div class="info-card guide-section fade-in">
                    <h2><?php echo editable($section['title'] ?? '', "sections.$index.title"); ?></h2>
                    <?php if(!empty($section['description'])): ?>
                    <p><?php echo editable($section['description'] ?? '', "sections.$index.description"); ?></p>
                    <?php endif; ?>
                    <?php if(!empty($section['items'])): ?>
                    <ul>
                        <?php foreach($section['items'] as $item): ?>
                        <li><?php echo $item; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <?php if(!empty($section['avoid'])): ?>
                    <p><strong><?php echo editable($section['avoid'] ?? '', "sections.$index.avoid"); ?></strong></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php
// Include footer (which now includes the contact form)
require_once 'includes/footer.php';
?>