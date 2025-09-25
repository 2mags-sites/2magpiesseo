<?php
/**
 * DynaSurf UK Ltd - Homepage
 * Precision Engineering Services Since 1971
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include admin configuration
require_once __DIR__ . '/includes/admin-config.php';

// Load page content
$content = loadContent('index');

// Set page meta variables
$page_title = $content['meta']['title'] ?? 'Precision Engineering Services Sandbach | Dynasurf UK Ltd';
$page_description = $content['meta']['description'] ?? 'Leading precision engineering company in Sandbach offering hydraulic ram repair, cylinder honing up to 300mm, and hard chrome plating. Express service available. Call 01270 763032.';
$page_keywords = $content['meta']['keywords'] ?? 'precision engineering services Sandbach, hard chrome plating Cheshire, cylinder honing services UK, hydraulic ram repair';

// Include header
include __DIR__ . '/includes/header.php';
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1><?php echo editable($content['hero']['title'] ?? 'Precision Engineering Excellence Since 1971', 'hero.title'); ?></h1>
            <p class="lead"><?php echo editable($content['hero']['subtitle'] ?? 'Hydraulic Repairs • Cylinder Honing • Hose Manufacturing', 'hero.subtitle'); ?></p>
            <div class="hero-buttons">
                <a href="#services" class="btn btn-secondary">Our Services</a>
                <a href="contact.php" class="btn btn-primary">Get Quote</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Overview -->
    <section id="services" class="section" style="padding-top: 3rem;">
        <div class="container">
            <div class="section-heading">
                <h2><?php echo editable($content['services']['heading'] ?? 'Our Core Services', 'services.heading'); ?></h2>
                <p><?php echo editable($content['services']['subheading'] ?? 'Over 50 years of expertise in precision surface engineering', 'services.subheading'); ?></p>
            </div>
            <div class="services-grid">
                <?php
                $services = $content['services']['items'] ?? [];
                for ($i = 0; $i < 3; $i++):
                    $service = $services[$i] ?? [];
                ?>
                <div class="service-card">
                    <div class="service-icon">
                        <?php if (isset($service['icon']) && strpos($service['icon'], 'assets/') === 0): ?>
                            <img src="<?php echo htmlspecialchars($service['icon']); ?>" alt="<?php echo htmlspecialchars($service['title'] ?? ''); ?>" />
                        <?php else: ?>
                            <?php echo editable($service['icon'] ?? '⚙️', "services.items[$i].icon"); ?>
                        <?php endif; ?>
                    </div>
                    <h3><?php echo editable($service['title'] ?? 'Service Title', "services.items[$i].title"); ?></h3>
                    <p><?php echo editable($service['description'] ?? 'Service description', "services.items[$i].description"); ?></p>
                    <a href="<?php echo $service['link'] ?? '#'; ?>" class="btn-link">Learn More →</a>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section section-alt cogs-bg-left">
        <div class="container">
            <div class="content-with-image">
                <div class="content-text">
                    <h2><?php echo editable($content['about']['title'] ?? '50+ Years of Engineering Excellence', 'about.title'); ?></h2>
                    <?php
                    $aboutContent = $content['about']['content'] ?? [];
                    foreach ($aboutContent as $index => $paragraph):
                    ?>
                    <p><?php echo editable($paragraph, "about.content[$index]"); ?></p>
                    <?php endforeach; ?>
                    <a href="about.php" class="btn btn-primary">
                        <?php echo editable($content['about']['button_text'] ?? 'Learn About Us', 'about.button_text'); ?>
                    </a>
                </div>
                <div class="content-image">
                    <img src="assets/images/man-machine.jpg" alt="Dynasurf Engineering Facility">
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Section -->
    <section class="section">
        <div class="container">
            <div class="section-heading">
                <h2><?php echo editable($content['why_choose']['title'] ?? 'Why Choose Dynasurf?', 'why_choose.title'); ?></h2>
                <p><?php echo editable($content['why_choose']['subtitle'] ?? 'Industry-leading capabilities with a customer-first approach', 'why_choose.subtitle'); ?></p>
            </div>
            <div class="features-list">
                <?php
                $features = $content['why_choose']['features'] ?? [];
                foreach ($features as $index => $feature):
                ?>
                <div class="feature-item">
                    <div class="feature-icon"><?php echo editable($feature['icon'] ?? '✓', "why_choose.features[$index].icon"); ?></div>
                    <div class="feature-text">
                        <h4><?php echo editable($feature['title'] ?? 'Feature Title', "why_choose.features[$index].title"); ?></h4>
                        <p><?php echo editable($feature['description'] ?? 'Feature description', "why_choose.features[$index].description"); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-heading">
                <h2><?php echo editable($content['process']['title'] ?? 'Our Process', 'process.title'); ?></h2>
                <p><?php echo editable($content['process']['subtitle'] ?? 'Precision engineering delivered with speed and accuracy', 'process.subtitle'); ?></p>
            </div>

            <div class="process-steps">
                <?php
                $steps = $content['process']['steps'] ?? [];
                foreach ($steps as $index => $step):
                ?>
                <div class="process-step">
                    <div class="step-number"><?php echo editable($step['number'] ?? ($index + 1), "process.steps[$index].number"); ?></div>
                    <h4><?php echo editable($step['title'] ?? 'Step Title', "process.steps[$index].title"); ?></h4>
                    <p><?php echo editable($step['description'] ?? 'Step description', "process.steps[$index].description"); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Industries Section -->
    <section class="section">
        <div class="container">
            <div class="section-heading">
                <h2><?php echo editable($content['industries']['title'] ?? 'Industries We Serve', 'industries.title'); ?></h2>
                <p><?php echo editable($content['industries']['subtitle'] ?? 'Trusted by leading companies across multiple sectors', 'industries.subtitle'); ?></p>
            </div>

            <div class="industries-grid">
                <?php
                $industries = $content['industries']['items'] ?? [];
                foreach ($industries as $index => $industry):
                ?>
                <div class="industry-item">
                    <div class="industry-icon"><?php echo editable($industry['icon'] ?? '🏗️', "industries.items[$index].icon"); ?></div>
                    <h4><?php echo editable($industry['name'] ?? 'Industry Name', "industries.items[$index].name"); ?></h4>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Emergency Services Section -->
    <section class="section section-alt cogs-bg-right">
        <div class="container">
            <div class="content-with-image reverse">
                <div class="content-text">
                    <h2><?php echo editable($content['emergency']['title'] ?? 'Emergency Express Services', 'emergency.title'); ?></h2>
                    <?php
                    $emergencyContent = $content['emergency']['content'] ?? [];
                    foreach ($emergencyContent as $index => $paragraph):
                    ?>
                    <p><?php echo editable($paragraph, "emergency.content[$index]"); ?></p>
                    <?php endforeach; ?>

                    <ul style="list-style: none; padding: 0;">
                        <?php
                        $emergencyFeatures = $content['emergency']['features'] ?? [
                            '✓ Same-day service for urgent repairs',
                            '✓ Materials kept in stock for immediate use',
                            '✓ Dedicated express service team',
                            '✓ Available for all core services'
                        ];
                        foreach ($emergencyFeatures as $index => $feature):
                        ?>
                        <li style="margin-bottom: 0.5rem;"><?php echo editable($feature, "emergency.features[$index]"); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo $content['emergency']['button_link'] ?? '/contact.php'; ?>" class="btn btn-primary" style="margin-top: 1.5rem;">
                        <?php echo editable($content['emergency']['button_text'] ?? 'Contact Us', 'emergency.button_text'); ?>
                    </a>
                </div>
                <div class="content-image">
                    <img src="https://placehold.co/600x400/e5e7eb/6b7280?text=Emergency+repair+service+team+at+work" alt="Emergency Express Service">
                </div>
            </div>
        </div>
    </section>

    <!-- News Section (Placeholder for WordPress Integration) -->
    <section class="section">
        <div class="container">
            <div class="section-heading">
                <h2>Latest News</h2>
                <p>Updates from Dynasurf</p>
            </div>

            <div class="services-grid">
                <div class="service-card">
                    <h3>New Equipment Investment</h3>
                    <p>We've recently invested in state-of-the-art honing equipment to expand our capacity...</p>
                    <a href="/news" class="btn-link">Read More →</a>
                </div>
                <div class="service-card">
                    <h3>Extended Operating Hours</h3>
                    <p>To better serve our customers, we're pleased to announce extended hours for emergency services...</p>
                    <a href="/news" class="btn-link">Read More →</a>
                </div>
                <div class="service-card">
                    <h3>ISO Certification Renewed</h3>
                    <p>Dynasurf has successfully renewed its ISO certifications, demonstrating our commitment to quality...</p>
                    <a href="/news" class="btn-link">Read More →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section">
        <div class="container">
            <div class="cta-section">
                <h2><?php echo editable($content['cta']['title'] ?? 'Need Precision Engineering Services?', 'cta.title'); ?></h2>
                <p><?php echo editable($content['cta']['subtitle'] ?? 'Get a quote today for hydraulic repairs, cylinder honing, or hydraulic hose manufacturing', 'cta.subtitle'); ?></p>

                <div class="cta-phones">
                    <div class="phone-item">
                        📞 <?php echo editable($content['cta']['phone'] ?? '01270 763032', 'cta.phone'); ?>
                    </div>
                    <div class="phone-item">
                        ✉️ <?php echo editable($content['cta']['email'] ?? 'sales@dynasurf.co.uk', 'cta.email'); ?>
                    </div>
                </div>
                <div>
                    <a href="contact.php" class="btn btn-secondary">
                        <?php echo editable($content['cta']['button_text'] ?? 'Get Quote Now', 'cta.button_text'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>