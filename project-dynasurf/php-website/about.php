<?php
require_once 'includes/admin-config.php';
$content = loadContent('about');
$page_title = $content['meta']['title'];
$page_description = $content['meta']['description'];
$page_keywords = $content['meta']['keywords'];
require_once 'includes/header.php';
?>

    <!-- Service Hero Section -->
    <section class="service-hero">
        <div class="container">
            <h1><?php echo editable($content['hero']['title'] ?? 'About Dynasurf UK Ltd', 'hero.title'); ?></h1>
            <p class="lead"><?php echo editable($content['hero']['subtitle'] ?? '50+ years of precision engineering excellence serving industry across the UK', 'hero.subtitle'); ?></p>
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
            <!-- Company Overview -->
            <div class="content-with-image">
                <div class="story-box">
                    <h3>Our Company Story</h3>
                    <?php if (isset($content['company_overview']['content']) && is_array($content['company_overview']['content'])): ?>
                        <?php foreach ($content['company_overview']['content'] as $index => $paragraph): ?>
                            <p><?php echo editable($paragraph, 'company_overview.content.' . $index); ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['company_overview']['image'] ?? '', 'company_overview.image', 'Dynasurf facility in Sandbach Cheshire', 'Dynasurf Facility'); ?>
                </div>
            </div>

            <!-- Our Capabilities -->
            <div class="section">
                <div class="section-heading">
                    <h2><?php echo editable($content['capabilities']['title'] ?? 'Modern Facilities & Advanced Capabilities', 'capabilities.title'); ?></h2>
                    <p><?php echo editable($content['capabilities']['content'] ?? 'Our purpose-built facility in Sandbach houses state-of-the-art equipment and comprehensive capabilities:', 'capabilities.content'); ?></p>
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
                <div class="content-with-image" style="margin-top: 3rem;">
                    <div class="content-text">
                        <p><?php echo editable($content['capabilities']['additional_content'] ?? '', 'capabilities.additional_content'); ?></p>
                    </div>
                    <div class="content-image">
                        <?php echo editableImage($content['capabilities']['image'] ?? '', 'capabilities.image', 'Modern engineering equipment and workshop', 'Modern Engineering Facility'); ?>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Our Team -->
    <section class="section cogs-bg-left" style="margin-top: 4rem;">
        <div class="container">
            <div class="section-heading">
                <h2><?php echo editable($content['team']['title'] ?? 'Our Expert Team', 'team.title'); ?></h2>
                <p><?php echo editable($content['team']['subtitle'] ?? 'Skilled engineers and craftsmen with decades of collective experience', 'team.subtitle'); ?></p>
            </div>

            <div class="content-with-image">
                <div class="content-text">
                    <h3>Experience & Expertise</h3>
                    <p><?php echo editable($content['team']['content'] ?? '', 'team.content'); ?></p>
                    <div class="features-list" style="margin: 1.5rem 0;">
                        <?php if (isset($content['team']['expertise']) && is_array($content['team']['expertise'])): ?>
                            <?php foreach ($content['team']['expertise'] as $index => $expert): ?>
                                <div class="feature-item">
                                    <div class="feature-icon"><?php echo editable($expert['icon'], 'team.expertise.' . $index . '.icon'); ?></div>
                                    <div class="feature-text">
                                        <h4><?php echo editable($expert['title'], 'team.expertise.' . $index . '.title'); ?></h4>
                                        <p><?php echo editable($expert['description'], 'team.expertise.' . $index . '.description'); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['team']['image'] ?? '', 'team.image', 'Skilled engineering team at work', 'Engineering Team'); ?>
                </div>
            </div>
        </div>
    </section>

    <main class="section">
        <div class="container">
            <!-- Quality & Standards -->
            <div class="content-with-image reverse" style="margin-top: 4rem;">
                <div class="content-text">
                    <h3><?php echo editable($content['quality_standards']['title'] ?? 'Quality Standards & Certifications', 'quality_standards.title'); ?></h3>
                    <p><?php echo editable($content['quality_standards']['content'] ?? '', 'quality_standards.content'); ?></p>
                    <div class="features-list" style="margin: 1.5rem 0;">
                        <?php if (isset($content['quality_standards']['standards']) && is_array($content['quality_standards']['standards'])): ?>
                            <?php foreach ($content['quality_standards']['standards'] as $index => $standard): ?>
                                <div class="feature-item">
                                    <div class="feature-icon"><?php echo editable($standard['icon'], 'quality_standards.standards.' . $index . '.icon'); ?></div>
                                    <div class="feature-text">
                                        <h4><?php echo editable($standard['title'], 'quality_standards.standards.' . $index . '.title'); ?></h4>
                                        <p><?php echo editable($standard['description'], 'quality_standards.standards.' . $index . '.description'); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['quality_standards']['image'] ?? '', 'quality_standards.image', 'Quality control and measurement equipment', 'Quality Standards'); ?>
                </div>
            </div>

            <!-- Industries We Serve -->
            <div class="section-heading" style="margin-top: 4rem;">
                <h2><?php echo editable($content['industries']['title'] ?? 'Industries We Serve', 'industries.title'); ?></h2>
                <p><?php echo editable($content['industries']['subtitle'] ?? 'Supporting critical equipment across diverse sectors', 'industries.subtitle'); ?></p>
            </div>

            <div class="content-with-image">
                <div class="content-text">
                    <h3>Diverse Industry Experience</h3>
                    <p><?php echo editable($content['industries']['content'] ?? '', 'industries.content'); ?></p>
                    <div class="features-list" style="margin: 1.5rem 0;">
                        <?php if (isset($content['industries']['sectors']) && is_array($content['industries']['sectors'])): ?>
                            <?php foreach ($content['industries']['sectors'] as $index => $sector): ?>
                                <div class="feature-item">
                                    <div class="feature-icon"><?php echo editable($sector['icon'], 'industries.sectors.' . $index . '.icon'); ?></div>
                                    <div class="feature-text">
                                        <h4><?php echo editable($sector['title'], 'industries.sectors.' . $index . '.title'); ?></h4>
                                        <p><?php echo editable($sector['description'], 'industries.sectors.' . $index . '.description'); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['industries']['image'] ?? '', 'industries.image', 'Various industries served by Dynasurf', 'Industries Served'); ?>
                </div>
            </div>

            <!-- Environmental Commitment -->
            <div class="content-with-image reverse" style="margin-top: 4rem;">
                <div class="content-text">
                    <h3><?php echo editable($content['environmental']['title'] ?? 'Environmental Responsibility', 'environmental.title'); ?></h3>
                    <p><?php echo editable($content['environmental']['content'] ?? '', 'environmental.content'); ?></p>
                    <div class="features-list" style="margin: 1.5rem 0;">
                        <?php if (isset($content['environmental']['commitments']) && is_array($content['environmental']['commitments'])): ?>
                            <?php foreach ($content['environmental']['commitments'] as $index => $commitment): ?>
                                <div class="feature-item">
                                    <div class="feature-icon"><?php echo editable($commitment['icon'], 'environmental.commitments.' . $index . '.icon'); ?></div>
                                    <div class="feature-text">
                                        <h4><?php echo editable($commitment['title'], 'environmental.commitments.' . $index . '.title'); ?></h4>
                                        <p><?php echo editable($commitment['description'], 'environmental.commitments.' . $index . '.description'); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['environmental']['image'] ?? '', 'environmental.image', 'Environmental responsibility and sustainability', 'Environmental Commitment'); ?>
                </div>
            </div>

            <!-- Our Values -->
            <div class="section-heading" style="margin-top: 4rem;">
                <h2><?php echo editable($content['values']['title'] ?? 'Our Values & Commitment', 'values.title'); ?></h2>
                <p><?php echo editable($content['values']['subtitle'] ?? 'The principles that guide everything we do', 'values.subtitle'); ?></p>
            </div>

            <div class="features-list">
                <?php if (isset($content['values']['principles']) && is_array($content['values']['principles'])): ?>
                    <?php foreach ($content['values']['principles'] as $index => $principle): ?>
                        <div class="feature-item">
                            <div class="feature-icon"><?php echo editable($principle['icon'], 'values.principles.' . $index . '.icon'); ?></div>
                            <div class="feature-text">
                                <h4><?php echo editable($principle['title'], 'values.principles.' . $index . '.title'); ?></h4>
                                <p><?php echo editable($principle['description'], 'values.principles.' . $index . '.description'); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Location & Accessibility -->
            <div class="content-with-image" style="margin-top: 4rem;">
                <div class="content-text">
                    <h3><?php echo editable($content['location']['title'] ?? 'Location & Accessibility', 'location.title'); ?></h3>
                    <p><?php echo editable($content['location']['content'] ?? '', 'location.content'); ?></p>
                    <div class="features-list" style="margin: 1.5rem 0;">
                        <?php if (isset($content['location']['advantages']) && is_array($content['location']['advantages'])): ?>
                            <?php foreach ($content['location']['advantages'] as $index => $advantage): ?>
                                <div class="feature-item">
                                    <div class="feature-icon"><?php echo editable($advantage['icon'], 'location.advantages.' . $index . '.icon'); ?></div>
                                    <div class="feature-text">
                                        <h4><?php echo editable($advantage['title'], 'location.advantages.' . $index . '.title'); ?></h4>
                                        <p><?php echo editable($advantage['description'], 'location.advantages.' . $index . '.description'); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['location']['image'] ?? '', 'location.image', 'Dynasurf location in Sandbach Cheshire', 'Dynasurf Location'); ?>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="cta-section" style="margin-top: 4rem;">
                <h2><?php echo editable($content['cta']['title'] ?? 'Experience the Dynasurf Difference', 'cta.title'); ?></h2>
                <p><?php echo editable($content['cta']['subtitle'] ?? '50+ years of engineering excellence at your service', 'cta.subtitle'); ?></p>
                <div class="cta-phones">
                    <div class="phone-item">
                        📞 <?php echo editable($content['cta']['phone'] ?? '01270 763032', 'cta.phone'); ?>
                    </div>
                    <div class="phone-item">
                        ✉️ <?php echo editable($content['cta']['email'] ?? 'sales@dynasurf.co.uk', 'cta.email'); ?>
                    </div>
                </div>
                <div>
                    <a href="<?php echo htmlspecialchars($content['cta']['button_link'] ?? '/contact'); ?>" class="btn btn-secondary">
                        <?php echo editable($content['cta']['button_text'] ?? 'Contact Our Team', 'cta.button_text'); ?>
                    </a>
                </div>
            </div>
        </div>
    </main>

<?php require_once 'includes/footer.php'; ?>