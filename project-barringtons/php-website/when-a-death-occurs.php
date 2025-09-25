<?php
// When a Death Occurs Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('when-a-death-occurs');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'When a Death Occurs - Complete Guide | Barringtons Funeral Services';
$page_description = $content['meta']['description'] ?? 'Comprehensive guide on what to do when someone dies. Medical certification, registration, funeral planning, and grief support. Liverpool funeral directors here to help.';
$page_keywords = $content['meta']['keywords'] ?? 'what to do when someone dies, death registration Liverpool, Medical Examiner process, Coroner involvement, funeral planning guide';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="when-a-death-occurs" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'When a Death Occurs', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="intro-block fade-in">
                <h2><?php echo editable($content['intro']['title'] ?? 'When Someone Dies: Your First Steps', 'intro.title'); ?></h2>
                <p class="lead-text"><?php echo editable($content['intro']['subtitle'] ?? 'Losing someone you love brings profound changes to your life. While the immediate shock may have passed, there are still many practical matters to address. This guide provides detailed information to help you navigate the process with clarity and confidence.', 'intro.subtitle'); ?></p>
            </div>

            <div class="guide-sections">
                <div class="info-card guide-section fade-in">
                    <h2><?php echo editable($content['sections']['medical_certification']['title'] ?? 'Medical Certification and the Medical Examiner', 'sections.medical_certification.title'); ?></h2>
                    <div class="info-box">
                        <p><?php echo editable($content['sections']['medical_certification']['description'] ?? 'Since September 2024, the death certification process follows these steps:', 'sections.medical_certification.description'); ?></p>
                        <ol>
                            <?php if (isset($content['sections']['medical_certification']['steps']) && is_array($content['sections']['medical_certification']['steps'])): ?>
                                <?php foreach ($content['sections']['medical_certification']['steps'] as $index => $step): ?>
                            <li><?php echo editable($step ?? '', "sections.medical_certification.steps.{$index}"); ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ol>
                        <p class="note"><?php echo editable($content['sections']['medical_certification']['note'] ?? 'This process ensures thoroughness while respecting your need to proceed with funeral arrangements.', 'sections.medical_certification.note'); ?></p>
                    </div>
                </div>

                <div class="info-card guide-section fade-in">
                    <h2><?php echo editable($content['sections']['registration']['title'] ?? 'Registering the Death', 'sections.registration.title'); ?></h2>
                    <p><?php echo editable($content['sections']['registration']['description'] ?? 'Registration must occur within five days unless the Coroner is involved. This important legal step allows you to:', 'sections.registration.description'); ?></p>
                    <ul class="check-list">
                        <?php if (isset($content['sections']['registration']['benefits']) && is_array($content['sections']['registration']['benefits'])): ?>
                            <?php foreach ($content['sections']['registration']['benefits'] as $index => $benefit): ?>
                        <li><?php echo editable($benefit ?? '', "sections.registration.benefits.{$index}"); ?></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>

                    <div class="sub-section">
                        <h3><?php echo editable($content['sections']['registration']['who_can_register']['title'] ?? 'Who can register a death:', 'sections.registration.who_can_register.title'); ?></h3>
                        <ul>
                            <?php if (isset($content['sections']['registration']['who_can_register']['list']) && is_array($content['sections']['registration']['who_can_register']['list'])): ?>
                                <?php foreach ($content['sections']['registration']['who_can_register']['list'] as $index => $person): ?>
                            <li><?php echo editable($person ?? '', "sections.registration.who_can_register.list.{$index}"); ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="sub-section">
                        <h3><?php echo editable($content['sections']['registration']['documents']['title'] ?? 'Documents you\'ll receive:', 'sections.registration.documents.title'); ?></h3>
                        <ul>
                            <?php if (isset($content['sections']['registration']['documents']['list']) && is_array($content['sections']['registration']['documents']['list'])): ?>
                                <?php foreach ($content['sections']['registration']['documents']['list'] as $index => $document): ?>
                            <li><?php echo editable($document ?? '', "sections.registration.documents.list.{$index}"); ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="info-card guide-section fade-in">
                    <h2><?php echo editable($content['sections']['tell_us_once']['title'] ?? 'The Tell Us Once Service', 'sections.tell_us_once.title'); ?></h2>
                    <p><?php echo editable($content['sections']['tell_us_once']['description'] ?? 'This free government service simplifies notifying multiple departments about the death. One notification covers:', 'sections.tell_us_once.description'); ?></p>
                    <ul>
                        <?php if (isset($content['sections']['tell_us_once']['services']) && is_array($content['sections']['tell_us_once']['services'])): ?>
                            <?php foreach ($content['sections']['tell_us_once']['services'] as $index => $service): ?>
                        <li><?php echo editable($service ?? '', "sections.tell_us_once.services.{$index}"); ?></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                    <p class="note"><?php echo editable($content['sections']['tell_us_once']['note'] ?? 'The Registrar will provide you with a unique reference number to access this service online or by phone.', 'sections.tell_us_once.note'); ?></p>
                </div>

                <div class="info-card guide-section fade-in">
                    <h2><?php echo editable($content['sections']['coroner']['title'] ?? 'When the Coroner is Involved', 'sections.coroner.title'); ?></h2>
                    <p><?php echo editable($content['sections']['coroner']['description'] ?? '', 'sections.coroner.description'); ?></p>
                    <ul class="check-list">
                        <?php foreach(($content['sections']['coroner']['circumstances'] ?? []) as $index => $circumstance): ?>
                        <li><?php echo editable($circumstance, "sections.coroner.circumstances.$index"); ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="info-box">
                        <h3><?php echo editable($content['sections']['coroner']['what_to_expect']['title'] ?? 'What to expect:', 'sections.coroner.what_to_expect.title'); ?></h3>
                        <ul>
                            <?php foreach(($content['sections']['coroner']['what_to_expect']['list'] ?? []) as $index => $item): ?>
                            <li><?php echo editable($item, "sections.coroner.what_to_expect.list.$index"); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="info-card guide-section fade-in">
                    <h2><?php echo editable($content['sections']['planning']['title'] ?? 'Planning the Funeral', 'sections.planning.title'); ?></h2>
                    <div class="planning-steps">
                        <div class="planning-step">
                            <h3><?php echo editable($content['sections']['planning']['burial_vs_cremation']['title'] ?? '', 'sections.planning.burial_vs_cremation.title'); ?></h3>
                            <p><?php echo editable($content['sections']['planning']['burial_vs_cremation']['description'] ?? '', 'sections.planning.burial_vs_cremation.description'); ?></p>
                            <p><strong><?php echo editable($content['sections']['planning']['burial_vs_cremation']['cremation']['title'] ?? 'Cremation offers:', 'sections.planning.burial_vs_cremation.cremation.title'); ?></strong></p>
                            <ul>
                                <?php foreach(($content['sections']['planning']['burial_vs_cremation']['cremation']['benefits'] ?? []) as $index => $benefit): ?>
                                <li><?php echo editable($benefit, "sections.planning.burial_vs_cremation.cremation.benefits.$index"); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <p><strong><?php echo editable($content['sections']['planning']['burial_vs_cremation']['burial']['title'] ?? 'Burial provides:', 'sections.planning.burial_vs_cremation.burial.title'); ?></strong></p>
                            <ul>
                                <?php foreach(($content['sections']['planning']['burial_vs_cremation']['burial']['benefits'] ?? []) as $index => $benefit): ?>
                                <li><?php echo editable($benefit, "sections.planning.burial_vs_cremation.burial.benefits.$index"); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <p><?php echo editable($content['sections']['planning']['burial_vs_cremation']['note'] ?? '', 'sections.planning.burial_vs_cremation.note'); ?></p>
                        </div>

                        <div class="planning-step">
                            <h3><?php echo editable($content['sections']['planning']['meaningful_service']['title'] ?? 'Creating a Meaningful Service', 'sections.planning.meaningful_service.title'); ?></h3>
                            <p><?php echo editable($content['sections']['planning']['meaningful_service']['description'] ?? '', 'sections.planning.meaningful_service.description'); ?></p>
                            <ul>
                                <?php foreach(($content['sections']['planning']['meaningful_service']['elements'] ?? []) as $index => $element): ?>
                                <li><?php echo editable($element, "sections.planning.meaningful_service.elements.$index"); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <p><?php echo editable($content['sections']['planning']['meaningful_service']['note'] ?? '', 'sections.planning.meaningful_service.note'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="info-card guide-section fade-in">
                    <h2><?php echo editable($content['sections']['practical']['title'] ?? 'Practical Considerations', 'sections.practical.title'); ?></h2>
                    <div class="practical-grid">
                        <div class="practical-card">
                            <h3><?php echo editable($content['sections']['practical']['documents']['title'] ?? '', 'sections.practical.documents.title'); ?></h3>
                            <p><?php echo editable($content['sections']['practical']['documents']['description'] ?? '', 'sections.practical.documents.description'); ?></p>
                            <ul>
                                <?php foreach(($content['sections']['practical']['documents']['list'] ?? []) as $index => $doc): ?>
                                <li><?php echo editable($doc, "sections.practical.documents.list.$index"); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="practical-card">
                            <h3><?php echo editable($content['sections']['practical']['notifications']['title'] ?? '', 'sections.practical.notifications.title'); ?></h3>
                            <p><?php echo editable($content['sections']['practical']['notifications']['description'] ?? '', 'sections.practical.notifications.description'); ?></p>
                            <ul>
                                <?php foreach(($content['sections']['practical']['notifications']['list'] ?? []) as $index => $notify): ?>
                                <li><?php echo editable($notify, "sections.practical.notifications.list.$index"); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="info-card guide-section fade-in">
                    <h2><?php echo editable($content['sections']['support']['title'] ?? 'Support Through Grief', 'sections.support.title'); ?></h2>
                    <p><?php echo editable($content['sections']['support']['description'] ?? '', 'sections.support.description'); ?></p>
                    <ul class="support-list">
                        <?php foreach(($content['sections']['support']['considerations'] ?? []) as $index => $consideration): ?>
                        <li><?php echo editable($consideration, "sections.support.considerations.$index"); ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="support-box">
                        <h3><?php echo editable($content['sections']['support']['commitment']['title'] ?? 'Our Commitment to You', 'sections.support.commitment.title'); ?></h3>
                        <p><?php echo editable($content['sections']['support']['commitment']['description'] ?? '', 'sections.support.commitment.description'); ?></p>
                        <p><strong><?php echo editable($content['sections']['support']['commitment']['promise_title'] ?? 'Our promise:', 'sections.support.commitment.promise_title'); ?></strong></p>
                        <ul>
                            <?php foreach(($content['sections']['support']['commitment']['promises'] ?? []) as $index => $promise): ?>
                            <li><?php echo editable($promise, "sections.support.commitment.promises.$index"); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <p><strong>We're here whenever you need us. Call 0151 928 1625 at any time to speak with one of our funeral directors.</strong></p>
                    </div>
                </div>

                <div class="info-card image-section fade-in">
                    <h2><?php echo editable($content['contact_section']['title'] ?? 'We\'re here whenever you need us', 'contact_section.title'); ?></h2>
                    <p><?php echo editable($content['contact_section']['description'] ?? 'Call 0151 928 1625 at any time to speak with one of our funeral directors.', 'contact_section.description'); ?></p>
                </div>
            </div>
        </div>
    </section>

<?php
// Include footer (which now includes the contact form)
require_once 'includes/footer.php';
?>