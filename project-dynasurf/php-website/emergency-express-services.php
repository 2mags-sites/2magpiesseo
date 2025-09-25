<?php
require_once 'includes/admin-config.php';
$content = loadContent('emergency-express-services');
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
            <h1><?php echo editable($content['hero']['title'] ?? 'Emergency Express Services', 'hero.title'); ?></h1>
            <p class="lead"><?php echo editable($content['hero']['subtitle'] ?? 'Same-day hydraulic repairs and express surface engineering when you need it most', 'hero.subtitle'); ?></p>
        </div>
    </section>

    <main class="section">
        <div class="container">
            <!-- Service Overview -->
            <div class="section-heading">
                <h2><?php echo editable($content['overview']['title'] ?? '24-Hour Emergency Response Service', 'overview.title'); ?></h2>
                <p><?php echo editable($content['overview']['subtitle'] ?? 'Minimising your downtime with rapid response hydraulic repairs', 'overview.subtitle'); ?></p>
            </div>

            <div class="content-with-image">
                <div class="content-text">
                    <h3>Emergency Express Services</h3>
                    <?php if (isset($content['overview']['content']) && is_array($content['overview']['content'])): ?>
                        <?php foreach ($content['overview']['content'] as $index => $paragraph): ?>
                            <p><?php echo editable($paragraph, 'overview.content.' . $index); ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['overview']['image'] ?? '', 'overview.image', 'Emergency repair service working around the clock', 'Emergency Service'); ?>
                </div>
            </div>

            <!-- Express Service Capabilities -->
            <div class="content-with-image reverse">
                <div class="content-text">
                    <h3><?php echo editable($content['capabilities']['title'] ?? 'Express Service Capabilities', 'capabilities.title'); ?></h3>
                    <p><?php echo editable($content['capabilities']['content'] ?? 'Our emergency express services provide rapid turnaround across all our core capabilities:', 'capabilities.content'); ?></p>
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
                    <?php echo editableImage($content['capabilities']['image'] ?? '', 'capabilities.image', 'Emergency express workshop with priority equipment', 'Express Service Equipment'); ?>
                </div>
            </div>

            <!-- Emergency Response Process -->
            <div class="section-heading" style="margin-top: 4rem;">
                <h2><?php echo editable($content['process']['title'] ?? 'Emergency Response Process', 'process.title'); ?></h2>
                <p><?php echo editable($content['process']['subtitle'] ?? 'Rapid assessment and immediate action to minimise downtime', 'process.subtitle'); ?></p>
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

            <!-- Emergency Services Available -->
            <div class="content-with-image" style="margin-top: 4rem;">
                <div class="content-text">
                    <h3><?php echo editable($content['services_covered']['title'] ?? 'Emergency Services Available', 'services_covered.title'); ?></h3>
                    <p><?php echo editable($content['services_covered']['content'] ?? 'Our emergency express service covers all critical hydraulic and surface engineering needs:', 'services_covered.content'); ?></p>
                    <div class="features-list" style="margin: 1.5rem 0;">
                        <?php if (isset($content['services_covered']['types']) && is_array($content['services_covered']['types'])): ?>
                            <?php foreach ($content['services_covered']['types'] as $index => $type): ?>
                                <div class="feature-item">
                                    <div class="feature-icon"><?php echo editable($type['icon'], 'services_covered.types.' . $index . '.icon'); ?></div>
                                    <div class="feature-text">
                                        <h4><?php echo editable($type['title'], 'services_covered.types.' . $index . '.title'); ?></h4>
                                        <p><?php echo editable($type['description'], 'services_covered.types.' . $index . '.description'); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['services_covered']['image'] ?? '', 'services_covered.image', 'Various emergency hydraulic repair scenarios', 'Emergency Services'); ?>
                </div>
            </div>

            <!-- Response Times -->
            <div class="content-with-image reverse" style="margin-top: 4rem;">
                <div class="content-text">
                    <h3><?php echo editable($content['response_times']['title'] ?? 'Express Service Response Times', 'response_times.title'); ?></h3>
                    <p><?php echo editable($content['response_times']['content'] ?? 'Our emergency service guarantees rapid response times to minimise your equipment downtime:', 'response_times.content'); ?></p>
                    <div class="features-list" style="margin: 1.5rem 0;">
                        <?php if (isset($content['response_times']['timeframes']) && is_array($content['response_times']['timeframes'])): ?>
                            <?php foreach ($content['response_times']['timeframes'] as $index => $timeframe): ?>
                                <div class="feature-item">
                                    <div class="feature-icon"><?php echo editable($timeframe['icon'], 'response_times.timeframes.' . $index . '.icon'); ?></div>
                                    <div class="feature-text">
                                        <h4><?php echo editable($timeframe['title'], 'response_times.timeframes.' . $index . '.title'); ?></h4>
                                        <p><?php echo editable($timeframe['description'], 'response_times.timeframes.' . $index . '.description'); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <p><?php echo editable($content['response_times']['additional_content'] ?? '', 'response_times.additional_content'); ?></p>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['response_times']['image'] ?? '', 'response_times.image', 'Express service timeline showing rapid response', 'Response Times'); ?>
                </div>
            </div>

            <!-- Industries Served -->
            <div class="content-with-image" style="margin-top: 4rem;">
                <div class="content-text">
                    <h3><?php echo editable($content['industries_served']['title'] ?? 'Emergency Service Industries', 'industries_served.title'); ?></h3>
                    <p><?php echo editable($content['industries_served']['content'] ?? 'Our emergency express services support critical operations across multiple sectors:', 'industries_served.content'); ?></p>
                    <div class="features-list" style="margin: 1.5rem 0;">
                        <?php if (isset($content['industries_served']['sectors']) && is_array($content['industries_served']['sectors'])): ?>
                            <?php foreach ($content['industries_served']['sectors'] as $index => $sector): ?>
                                <div class="feature-item">
                                    <div class="feature-icon"><?php echo editable($sector['icon'], 'industries_served.sectors.' . $index . '.icon'); ?></div>
                                    <div class="feature-text">
                                        <h4><?php echo editable($sector['title'], 'industries_served.sectors.' . $index . '.title'); ?></h4>
                                        <p><?php echo editable($sector['description'], 'industries_served.sectors.' . $index . '.description'); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['industries_served']['image'] ?? '', 'industries_served.image', 'Various industries requiring emergency hydraulic services', 'Emergency Industries'); ?>
                </div>
            </div>

            <!-- Why Choose Dynasurf -->
            <div class="section-heading" style="margin-top: 4rem;">
                <h2><?php echo editable($content['why_choose']['title'] ?? 'Why Choose Dynasurf for Emergency Services?', 'why_choose.title'); ?></h2>
                <p><?php echo editable($content['why_choose']['subtitle'] ?? 'Rapid response with uncompromising quality', 'why_choose.subtitle'); ?></p>
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
                <h2><?php echo editable($content['cta']['title'] ?? 'Need Emergency Hydraulic Service?', 'cta.title'); ?></h2>
                <p><?php echo editable($content['cta']['subtitle'] ?? '24/7 rapid response to minimise your downtime - Every minute counts', 'cta.subtitle'); ?></p>
                <?php if (isset($content['cta']['emergency_note'])): ?>
                    <p class="emergency-note" style="color: #e74c3c; font-weight: bold; margin: 1rem 0;">
                        <?php echo editable($content['cta']['emergency_note'], 'cta.emergency_note'); ?>
                    </p>
                <?php endif; ?>
                <div class="cta-phones">
                    <div class="phone-item">
                        📞 <?php echo editable($content['cta']['phone'] ?? '01270 763032', 'cta.phone'); ?>
                    </div>
                    <div class="phone-item">
                        ✉️ <?php echo editable($content['cta']['email'] ?? 'emergency@dynasurf.co.uk', 'cta.email'); ?>
                    </div>
                </div>
                <div>
                    <a href="<?php echo htmlspecialchars($content['cta']['button_link'] ?? '/contact.php'); ?>" class="btn btn-secondary" style="background: #e74c3c; border-color: #e74c3c;">
                        <?php echo editable($content['cta']['button_text'] ?? 'Call Emergency Service', 'cta.button_text'); ?>
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