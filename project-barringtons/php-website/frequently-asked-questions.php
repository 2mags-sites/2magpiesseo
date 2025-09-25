<?php
// Frequently Asked Questions Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('frequently-asked-questions');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Frequently Asked Questions | Barringtons Funeral Services';
$page_description = $content['meta']['description'] ?? 'Common questions about funeral arrangements, costs, and services. Find answers to help you make informed decisions during a difficult time.';
$page_keywords = $content['meta']['keywords'] ?? 'funeral FAQ, funeral questions, funeral costs, funeral arrangements';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="frequently-asked-questions" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'Frequently Asked Questions', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="intro-block fade-in">
                <h2><?php echo editable($content['intro']['title'] ?? 'Common Questions', 'intro.title'); ?></h2>
                <p class="lead-text"><?php echo editable($content['intro']['subtitle'] ?? '', 'intro.subtitle'); ?></p>
            </div>

            <div class="faq-section">
                <div class="faq-grid">
                    <?php foreach(($content['questions'] ?? []) as $index => $faq): ?>
                    <div class="faq-item">
                        <h4><?php echo editable($faq['question'] ?? '', "questions.$index.question"); ?></h4>
                        <p><?php echo editable($faq['answer'] ?? '', "questions.$index.answer"); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

<?php
// Include footer
require_once 'includes/footer.php';
?>