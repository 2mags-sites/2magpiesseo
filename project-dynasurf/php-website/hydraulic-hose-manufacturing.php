<?php
require_once 'includes/admin-config.php';
$content = loadContent('hydraulic-hose-manufacturing');
$page_title = $content['meta']['title'];
$page_description = $content['meta']['description'];
$page_keywords = $content['meta']['keywords'];
require_once 'includes/header.php';
?>

    <!-- Service Hero Section -->
    <section class="service-hero">
        <div class="container">
            <h1><?php echo editable($content['hero']['title'], 'hero.title'); ?></h1>
            <p class="lead"><?php echo editable($content['hero']['subtitle'], 'hero.subtitle'); ?></p>
            <nav class="breadcrumb">
                <ol>
                    <?php foreach ($content['hero']['breadcrumbs'] as $crumb): ?>
                        <li>
                            <?php if (!empty($crumb['url'])): ?>
                                <a href="<?php echo htmlspecialchars($crumb['url']); ?>"><?php echo editable($crumb['name'], 'hero.breadcrumbs.' . array_search($crumb, $content['hero']['breadcrumbs']) . '.name'); ?></a>
                            <?php else: ?>
                                <?php echo editable($crumb['name'], 'hero.breadcrumbs.' . array_search($crumb, $content['hero']['breadcrumbs']) . '.name'); ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
        </div>
    </section>

    <main class="section" style="padding-top: 3rem;">
        <div class="container">
            <!-- Service Overview -->
            <div class="content-with-image">
                <div class="story-box">
                    <h3><?php echo editable($content['overview']['title'], 'overview.title'); ?></h3>
                    <?php if (isset($content['overview']['content']) && is_array($content['overview']['content'])): ?>
                        <?php foreach ($content['overview']['content'] as $index => $paragraph): ?>
                            <p><?php echo editable($paragraph, 'overview.content.' . $index); ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['overview']['image'], 'overview.image', 'Hydraulic hose manufacturing equipment and assemblies', 'Hydraulic Hose Manufacturing Process'); ?>
                </div>
            </div>

            <!-- Precision Grinding -->
            <div class="section" style="margin-top: 4rem;">
                <div class="section-heading">
                    <h2><?php echo editable($content['capabilities']['title'], 'capabilities.title'); ?></h2>
                    <p><?php echo editable($content['capabilities']['content'], 'capabilities.content'); ?></p>
                </div>
                <div class="features-list">
                    <?php if (isset($content['capabilities']['specs']) && is_array($content['capabilities']['specs'])): ?>
                        <?php foreach ($content['capabilities']['specs'] as $index => $spec): ?>
                            <div class="feature-item">
                                <div class="feature-icon">✓</div>
                                <div class="feature-text">
                                    <h4><?php echo editable($spec['label'], 'capabilities.specs.' . $index . '.label'); ?></h4>
                                    <p><?php echo editable($spec['description'], 'capabilities.specs.' . $index . '.description'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="content-with-image" style="margin-top: 2rem;">
                    <div class="content-text">
                        <p><?php echo editable($content['capabilities']['additional_content'], 'capabilities.additional_content'); ?></p>
                    </div>
                    <div class="content-image">
                        <?php echo editableImage($content['capabilities']['image'], 'capabilities.image', 'Hydraulic hose crimping and testing equipment', 'Manufacturing Equipment'); ?>
                    </div>
                </div>
            </div>

            <!-- Our Process -->
            <div class="section-heading" style="margin-top: 4rem;">
                <h2><?php echo editable($content['process']['title'], 'process.title'); ?></h2>
                <p><?php echo editable($content['process']['subtitle'], 'process.subtitle'); ?></p>
            </div>

            <div class="process-steps">
                <?php if (isset($content['process']['steps']) && is_array($content['process']['steps'])): ?>
                    <?php foreach ($content['process']['steps'] as $index => $step): ?>
                        <div class="process-step">
                            <div class="step-number"><?php echo editable($step['number'], 'process.steps.' . $index . '.number'); ?></div>
                            <h4><?php echo editable($step['title'], 'process.steps.' . $index . '.title'); ?></h4>
                            <p><?php echo editable($step['description'], 'process.steps.' . $index . '.description'); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <!-- Component Types -->
    <section class="section cogs-bg-left" style="margin-top: 4rem;">
        <div class="container">
            <div class="content-with-image">
                <div class="story-box">
                    <h3><?php echo editable($content['components']['title'], 'components.title'); ?></h3>
                    <p><?php echo editable($content['components']['content'], 'components.content'); ?></p>
                    <div class="features-list" style="margin: 1.5rem 0;">
                        <?php if (isset($content['components']['types']) && is_array($content['components']['types'])): ?>
                            <?php foreach ($content['components']['types'] as $index => $type): ?>
                                <div class="feature-item">
                                    <div class="feature-icon"><?php echo editable($type['icon'], 'components.types.' . $index . '.icon'); ?></div>
                                    <div class="feature-text">
                                        <h4><?php echo editable($type['title'], 'components.types.' . $index . '.title'); ?></h4>
                                        <p><?php echo editable($type['description'], 'components.types.' . $index . '.description'); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['components']['image'], 'components.image', 'Various hydraulic hose assemblies and fittings', 'Hydraulic Hose Assemblies'); ?>
                </div>
            </div>

        </div>
    </section>

    <!-- Benefits -->
    <section class="section cogs-bg-right" style="margin-top: 4rem;">
        <div class="container">
            <div class="content-with-image reverse">
                    <div class="story-box">
                        <h3><?php echo editable($content['benefits']['title'], 'benefits.title'); ?></h3>
                        <p><?php echo editable($content['benefits']['content'], 'benefits.content'); ?></p>
                        <div class="features-list" style="margin: 1.5rem 0;">
                            <?php if (isset($content['benefits']['advantages']) && is_array($content['benefits']['advantages'])): ?>
                                <?php foreach ($content['benefits']['advantages'] as $index => $advantage): ?>
                                    <div class="feature-item">
                                        <div class="feature-icon"><?php echo editable($advantage['icon'], 'benefits.advantages.' . $index . '.icon'); ?></div>
                                        <div class="feature-text">
                                            <h4><?php echo editable($advantage['title'], 'benefits.advantages.' . $index . '.title'); ?></h4>
                                            <p><?php echo editable($advantage['description'], 'benefits.advantages.' . $index . '.description'); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <p><?php echo editable($content['benefits']['additional_content'], 'benefits.additional_content'); ?></p>
                    </div>
                    <div class="content-image">
                        <?php echo editableImage($content['benefits']['image'], 'benefits.image', 'Quality tested hydraulic hose assemblies', 'Quality Assurance'); ?>
                    </div>
                </div>
        </div>
    </section>

    <main class="section">
        <div class="container">
            <!-- Why Choose Dynasurf -->
            <div class="section-heading" style="margin-top: 4rem;">
                <h2><?php echo editable($content['why_choose']['title'], 'why_choose.title'); ?></h2>
                <p><?php echo editable($content['why_choose']['subtitle'], 'why_choose.subtitle'); ?></p>
            </div>

            <div class="features-list">
                <?php if (isset($content['why_choose']['features']) && is_array($content['why_choose']['features'])): ?>
                    <?php foreach ($content['why_choose']['features'] as $index => $feature): ?>
                        <div class="feature-item">
                            <div class="feature-icon"><?php echo editable($feature['icon'], 'why_choose.features.' . $index . '.icon'); ?></div>
                            <div class="feature-text">
                                <h4><?php echo editable($feature['title'], 'why_choose.features.' . $index . '.title'); ?></h4>
                                <p><?php echo editable($feature['description'], 'why_choose.features.' . $index . '.description'); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- FAQ Section -->
            <div class="section-heading" style="margin-top: 4rem;">
                <h2><?php echo editable($content['faq']['title'], 'faq.title'); ?></h2>
            </div>

            <div class="faq-container">
                <?php if (isset($content['faqs']) && is_array($content['faqs'])): ?>
                    <?php foreach ($content['faqs'] as $index => $faq): ?>
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <?php echo editable($faq['question'], 'faqs.' . $index . '.question'); ?>
                                <span class="faq-icon">▼</span>
                            </div>
                            <div class="faq-answer">
                                <p><?php echo editable($faq['answer'], 'faqs.' . $index . '.answer'); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- CTA Section -->
            <div class="cta-section" style="margin-top: 4rem;">
                <h2><?php echo editable($content['cta']['title'], 'cta.title'); ?></h2>
                <p><?php echo editable($content['cta']['subtitle'], 'cta.subtitle'); ?></p>
                <div class="cta-phones">
                    <div class="phone-item">
                        📞 <?php echo editable($content['cta']['phone'], 'cta.phone'); ?>
                    </div>
                    <div class="phone-item">
                        ✉️ <?php echo editable($content['cta']['email'], 'cta.email'); ?>
                    </div>
                </div>
                <div>
                    <a href="<?php echo htmlspecialchars($content['cta']['button_link']); ?>" class="btn btn-secondary">
                        <?php echo editable($content['cta']['button_text'], 'cta.button_text'); ?>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        function toggleFAQ(element) {
            const answer = element.nextElementSibling;
            const isActive = element.classList.contains('active');

            // Close all FAQs
            document.querySelectorAll('.faq-question').forEach(q => {
                q.classList.remove('active');
                q.nextElementSibling.classList.remove('active');
            });

            // Open clicked FAQ if it wasn't active
            if (!isActive) {
                element.classList.add('active');
                answer.classList.add('active');
            }
        }
    </script>

<?php require_once 'includes/footer.php'; ?>