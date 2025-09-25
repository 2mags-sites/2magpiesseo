<?php
require_once 'includes/admin-config.php';
$content = loadContent('automotive-component-reconditioning');
$page_title = $content['meta']['title'];
$page_description = $content['meta']['description'];
$page_keywords = $content['meta']['keywords'];
require_once 'includes/header.php';
?>

    <!-- Service Hero Section -->
    <section class="service-hero">
        <div class="container">
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
            <h1><?php echo editable($content['hero']['title'] ?? 'Automotive Component Reconditioning', 'hero.title'); ?></h1>
            <p class="lead"><?php echo editable($content['hero']['subtitle'] ?? 'Expert restoration of gearbox, engine and steering components', 'hero.subtitle'); ?></p>
        </div>
    </section>

    <main class="section">
        <div class="container">
            <!-- Service Overview -->
            <div class="section-heading">
                <h2><?php echo editable($content['overview']['title'] ?? 'Professional Automotive Reconditioning Services', 'overview.title'); ?></h2>
                <p><?php echo editable($content['overview']['subtitle'] ?? 'Restore gearbox parts, engine components and steering systems to original specifications', 'overview.subtitle'); ?></p>
            </div>

            <div class="content-with-image">
                <div class="content-text">
                    <h3>What is Automotive Component Reconditioning?</h3>
                    <?php if (isset($content['overview']['content']) && is_array($content['overview']['content'])): ?>
                        <?php foreach ($content['overview']['content'] as $index => $paragraph): ?>
                            <p><?php echo editable($paragraph, 'overview.content.' . $index); ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['overview']['image'] ?? '', 'overview.image', 'Automotive gearbox components being reconditioned', 'Automotive Reconditioning Process'); ?>
                </div>
            </div>

            <!-- Reconditioning Capabilities -->
            <div class="content-with-image reverse">
                <div class="content-text">
                    <h3><?php echo editable($content['capabilities']['title'] ?? 'Reconditioning Capabilities', 'capabilities.title'); ?></h3>
                    <p><?php echo editable($content['capabilities']['content'] ?? 'Our automotive reconditioning facility offers comprehensive restoration services:', 'capabilities.content'); ?></p>
                    <ul style="list-style: none; padding: 0; margin: 1.5rem 0;">
                        <?php if (isset($content['capabilities']['specs']) && is_array($content['capabilities']['specs'])): ?>
                            <?php foreach ($content['capabilities']['specs'] as $index => $spec): ?>
                                <li style="margin-bottom: 0.75rem;">
                                    <strong>✓ <?php echo editable($spec['label'], 'capabilities.specs.' . $index . '.label'); ?>:</strong>
                                    <?php echo editable($spec['description'], 'capabilities.specs.' . $index . '.description'); ?>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                    <p><?php echo editable($content['capabilities']['additional_content'] ?? '', 'capabilities.additional_content'); ?></p>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['capabilities']['image'] ?? '', 'capabilities.image', 'Precision grinding automotive shaft component', 'Precision Grinding'); ?>
                </div>
            </div>

            <!-- Our Process -->
            <div class="section-heading" style="margin-top: 4rem;">
                <h2><?php echo editable($content['process']['title'] ?? 'Reconditioning Process', 'process.title'); ?></h2>
                <p><?php echo editable($content['process']['subtitle'] ?? 'Systematic restoration for optimal results', 'process.subtitle'); ?></p>
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

            <!-- Components We Recondition -->
            <div class="content-with-image" style="margin-top: 4rem;">
                <div class="content-text">
                    <h3><?php echo editable($content['components']['title'] ?? 'Components We Recondition', 'components.title'); ?></h3>
                    <p><?php echo editable($content['components']['content'] ?? 'Our automotive reconditioning services cover all major drivetrain and steering components:', 'components.content'); ?></p>
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
                    <?php echo editableImage($content['components']['image'] ?? '', 'components.image', 'Various reconditioned automotive components', 'Reconditioned Components'); ?>
                </div>
            </div>

            <!-- Applications -->
            <div class="content-with-image reverse" style="margin-top: 4rem;">
                <div class="content-text">
                    <h3><?php echo editable($content['applications']['title'] ?? 'Automotive Applications', 'applications.title'); ?></h3>
                    <p><?php echo editable($content['applications']['content'] ?? 'Our reconditioning services support diverse automotive sectors and applications:', 'applications.content'); ?></p>
                    <div class="features-list" style="margin: 1.5rem 0;">
                        <?php if (isset($content['applications']['sectors']) && is_array($content['applications']['sectors'])): ?>
                            <?php foreach ($content['applications']['sectors'] as $index => $sector): ?>
                                <div class="feature-item">
                                    <div class="feature-icon"><?php echo editable($sector['icon'], 'applications.sectors.' . $index . '.icon'); ?></div>
                                    <div class="feature-text">
                                        <h4><?php echo editable($sector['title'], 'applications.sectors.' . $index . '.title'); ?></h4>
                                        <p><?php echo editable($sector['description'], 'applications.sectors.' . $index . '.description'); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <p><?php echo editable($content['applications']['additional_content'] ?? '', 'applications.additional_content'); ?></p>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['applications']['image'] ?? '', 'applications.image', 'Automotive applications showing various vehicle types', 'Automotive Applications'); ?>
                </div>
            </div>

            <!-- Benefits -->
            <div class="content-with-image" style="margin-top: 4rem;">
                <div class="content-text">
                    <h3><?php echo editable($content['benefits']['title'] ?? 'Benefits of Automotive Reconditioning', 'benefits.title'); ?></h3>
                    <p><?php echo editable($content['benefits']['content'] ?? 'Professional reconditioning offers significant advantages for automotive applications:', 'benefits.content'); ?></p>
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
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['benefits']['image'] ?? '', 'benefits.image', 'Before and after automotive component reconditioning', 'Reconditioning Benefits'); ?>
                </div>
            </div>

            <!-- Why Choose Dynasurf -->
            <div class="section-heading" style="margin-top: 4rem;">
                <h2><?php echo editable($content['why_choose']['title'] ?? 'Why Choose Dynasurf for Automotive Reconditioning?', 'why_choose.title'); ?></h2>
                <p><?php echo editable($content['why_choose']['subtitle'] ?? 'Expertise and precision for automotive excellence', 'why_choose.subtitle'); ?></p>
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
                <h2>Frequently Asked Questions</h2>
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
                <h2><?php echo editable($content['cta']['title'] ?? 'Need Automotive Component Reconditioning?', 'cta.title'); ?></h2>
                <p><?php echo editable($content['cta']['subtitle'] ?? 'Restore your automotive components to better-than-new condition', 'cta.subtitle'); ?></p>
                <div class="cta-phones">
                    <div class="phone-item">
                        📞 <?php echo editable($content['cta']['phone'] ?? '01270 763032', 'cta.phone'); ?>
                    </div>
                    <div class="phone-item">
                        ✉️ <?php echo editable($content['cta']['email'] ?? 'sales@dynasurf.co.uk', 'cta.email'); ?>
                    </div>
                </div>
                <div>
                    <a href="<?php echo htmlspecialchars($content['cta']['button_link'] ?? '/contact.php'); ?>" class="btn btn-secondary">
                        <?php echo editable($content['cta']['button_text'] ?? 'Get Reconditioning Quote', 'cta.button_text'); ?>
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