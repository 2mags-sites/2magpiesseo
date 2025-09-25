<?php
// Planning Ahead Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('planning-ahead');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Plan Ahead - Funeral Pre-Planning | Barringtons Liverpool';
$page_description = $content['meta']['description'] ?? 'Pre-plan your funeral with Barringtons. Lock in today\'s prices, document your wishes, and give your family peace of mind. Free consultation. Call 0151 928 1625.';
$page_keywords = $content['meta']['keywords'] ?? 'funeral pre-planning Liverpool, prepaid funeral plans, funeral wishes, advance funeral planning, funeral payment plans';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="planning-ahead" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'Planning Ahead', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="intro-block fade-in">
                <h2><?php echo editable($content['intro']['title'] ?? 'Plan Your Farewell, Your Way', 'intro.title'); ?></h2>
                <p class="lead-text"><?php echo editable($content['intro']['subtitle'] ?? '', 'intro.subtitle'); ?></p>
            </div>

            <div class="benefits-grid">
                <div class="benefit-card fade-in">
                    <h3><?php echo editable($content['benefits']['family']['title'] ?? 'A Gift to Your Family', 'benefits.family.title'); ?></h3>
                    <p><?php echo editable($content['benefits']['family']['description'] ?? '', 'benefits.family.description'); ?></p>
                </div>

                <div class="benefit-card fade-in">
                    <h3><?php echo editable($content['benefits']['wishes']['title'] ?? 'Your Wishes, Honored', 'benefits.wishes.title'); ?></h3>
                    <p><?php echo editable($content['benefits']['wishes']['description'] ?? '', 'benefits.wishes.description'); ?></p>
                </div>

                <div class="benefit-card fade-in">
                    <h3><?php echo editable($content['benefits']['financial']['title'] ?? 'Financial Peace of Mind', 'benefits.financial.title'); ?></h3>
                    <p><?php echo editable($content['benefits']['financial']['description'] ?? '', 'benefits.financial.description'); ?></p>
                </div>
            </div>

            <div class="info-card process-section fade-in">
                <h2><?php echo editable($content['process']['title'] ?? 'How It Works', 'process.title'); ?></h2>
                <div class="process-steps">
                    <?php foreach(($content['process']['steps'] ?? []) as $index => $step): ?>
                    <div class="process-step">
                        <div class="step-number"><?php echo $index + 1; ?></div>
                        <div class="step-content">
                            <h3><?php echo editable($step['title'] ?? '', "process.steps.$index.title"); ?></h3>
                            <p><?php echo editable($step['description'] ?? '', "process.steps.$index.description"); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="plan-types fade-in">
                <h2><?php echo editable($content['plans']['title'] ?? 'Planning Options', 'plans.title'); ?></h2>
                <div class="plan-cards">
                    <div class="plan-card">
                        <h3><?php echo editable($content['plans']['wishes']['title'] ?? 'Wishes Only', 'plans.wishes.title'); ?></h3>
                        <p class="plan-price"><?php echo editable($content['plans']['wishes']['price'] ?? 'Free', 'plans.wishes.price'); ?></p>
                        <p><?php echo editable($content['plans']['wishes']['description'] ?? '', 'plans.wishes.description'); ?></p>
                        <ul>
                            <?php foreach(($content['plans']['wishes']['features'] ?? []) as $feature): ?>
                            <li><?php echo $feature; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="#contact" class="btn-outline">Learn More</a>
                    </div>

                    <div class="plan-card featured">
                        <?php if (!empty($content['plans']['prepaid']['badge'])): ?>
                        <div class="featured-badge"><?php echo editable($content['plans']['prepaid']['badge'], 'plans.prepaid.badge'); ?></div>
                        <?php endif; ?>
                        <h3><?php echo editable($content['plans']['prepaid']['title'] ?? 'Prepaid Plan', 'plans.prepaid.title'); ?></h3>
                        <p class="plan-price"><?php echo editable($content['plans']['prepaid']['price'] ?? 'Fixed Price', 'plans.prepaid.price'); ?></p>
                        <p><?php echo editable($content['plans']['prepaid']['description'] ?? '', 'plans.prepaid.description'); ?></p>
                        <ul>
                            <?php foreach(($content['plans']['prepaid']['features'] ?? []) as $feature): ?>
                            <li><?php echo $feature; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="#contact" class="btn-primary">Get Started</a>
                    </div>

                    <div class="plan-card">
                        <h3><?php echo editable($content['plans']['installment']['title'] ?? 'Installment Plan', 'plans.installment.title'); ?></h3>
                        <p class="plan-price"><?php echo editable($content['plans']['installment']['price'] ?? 'Monthly Payments', 'plans.installment.price'); ?></p>
                        <p><?php echo editable($content['plans']['installment']['description'] ?? '', 'plans.installment.description'); ?></p>
                        <ul>
                            <?php foreach(($content['plans']['installment']['features'] ?? []) as $feature): ?>
                            <li><?php echo $feature; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="#contact" class="btn-outline">Learn More</a>
                    </div>
                </div>
            </div>

            <div class="promise-section faq-section fade-in">
                <h2><?php echo editable($content['faq']['title'] ?? 'Common Questions', 'faq.title'); ?></h2>
                <div class="faq-grid">
                    <?php foreach(($content['faq']['items'] ?? []) as $index => $faq): ?>
                    <div class="faq-item">
                        <h4><?php echo editable($faq['question'] ?? '', "faq.items.$index.question"); ?></h4>
                        <p><?php echo editable($faq['answer'] ?? '', "faq.items.$index.answer"); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="cta-section fade-in">
                <h2><?php echo editable($content['cta']['title'] ?? 'Start Your Plan Today', 'cta.title'); ?></h2>
                <p><?php echo editable($content['cta']['subtitle'] ?? '', 'cta.subtitle'); ?></p>
                <div class="cta-buttons">
                    <a href="tel:01519281625" class="btn-primary">Call 0151 928 1625</a>
                    <a href="#contact" class="btn-outline">Request Information Pack</a>
                </div>
            </div>
        </div>
    </section>

<?php
// Include footer (which now includes the contact form)
require_once 'includes/footer.php';
?>