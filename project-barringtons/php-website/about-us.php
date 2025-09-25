<?php
// About Us Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('about-us');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'About Barringtons - Family Funeral Directors Since 1902 | Liverpool';
$page_description = $content['meta']['description'] ?? 'Four generations of caring funeral service in Liverpool. Led by David Barrington (NAFD President 2019-2021). Over 3000 memorial trees planted. Trusted, independent & compassionate.';
$page_keywords = $content['meta']['keywords'] ?? 'family funeral directors Liverpool, independent funeral directors Merseyside, NAFD funeral directors, David Barrington, established funeral services';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="about-us" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'About Us', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="content-full">
                <div class="content-main">
                    <div class="intro-block fade-in">
                        <div class="intro-content">
                            <div class="intro-text">
                                <h2><?php echo editable($content['about']['intro']['title'] ?? 'About Barrington', 'about.intro.title'); ?></h2>
                                <p class="lead-text"><?php echo editable($content['about']['intro']['subtitle'] ?? 'A family caring for families', 'about.intro.subtitle'); ?></p>
                                <p><?php echo editable($content['about']['intro']['description1'] ?? '', 'about.intro.description1'); ?></p>
                                <p><?php echo editable($content['about']['intro']['description2'] ?? '', 'about.intro.description2'); ?></p>
                                <p><strong>Company Number:</strong> <span><?php echo editable($content['about']['company']['number'] ?? '07587745', 'about.company.number'); ?></span></p>
                            </div>
                            <div class="intro-image">
                                <?php echo editableImage($content['about']['intro']['image'] ?? '', 'about.intro.image', 'Team Photo Barrington Family', 'Barrington Family Team'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="info-card fade-in">
                        <div class="card-content">
                            <div class="card-image">
                                <?php echo editableImage($content['about']['director']['image'] ?? '', 'about.director.image', 'David Barrington Director', 'David Barrington - Director'); ?>
                            </div>
                            <div class="card-text">
                                <h3><?php echo editable($content['about']['director']['name'] ?? 'David Barrington - Director', 'about.director.name'); ?></h3>
                                <p><strong><?php echo editable($content['about']['director']['title'] ?? 'NAFD President 2019-2021 | NAFD Approved Tutor', 'about.director.title'); ?></strong></p>
                                <p><?php echo editable($content['about']['director']['description'] ?? '', 'about.director.description'); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="promise-section fade-in">
                        <h2><?php echo editable($content['about']['promise']['title'] ?? 'Our Promise to You', 'about.promise.title'); ?></h2>
                        <p><?php echo editable($content['about']['promise']['description'] ?? '', 'about.promise.description'); ?></p>

                        <h3><?php echo editable($content['about']['promise']['trusted_title'] ?? 'Trusted Care', 'about.promise.trusted_title'); ?></h3>
                        <p><?php echo editable($content['about']['promise']['trusted_description'] ?? '', 'about.promise.trusted_description'); ?></p>

                        <h3><?php echo editable($content['about']['promise']['growing_title'] ?? 'Growing Hope', 'about.promise.growing_title'); ?></h3>
                        <div class="feature-with-image">
                            <div class="feature-text">
                                <p><?php echo editable($content['about']['promise']['growing_description'] ?? '', 'about.promise.growing_description'); ?></p>
                            </div>
                            <div class="feature-image">
                                <?php echo editableImage($content['about']['promise']['growing_image'] ?? '', 'about.promise.growing_image', 'Memorial Trees Over 3000 Planted', 'Memorial Trees'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="locations-section fade-in">
                        <h2><?php echo editable($content['about']['locations']['title'] ?? 'Our Three Offices - Always Close By', 'about.locations.title'); ?></h2>
                        <p class="lead-text"><?php echo editable($content['about']['locations']['subtitle'] ?? '', 'about.locations.subtitle'); ?></p>

                        <div class="locations-grid">
                            <div class="location-card">
                                <h3><?php echo editable($content['about']['locations']['waterloo']['name'] ?? 'Waterloo Office', 'about.locations.waterloo.name'); ?></h3>
                                <p class="location-tag"><?php echo editable($content['about']['locations']['waterloo']['tag'] ?? '', 'about.locations.waterloo.tag'); ?></p>
                                <p><strong>Address:</strong><br><?php echo editable($content['about']['locations']['waterloo']['address'] ?? '', 'about.locations.waterloo.address'); ?></p>
                                <p><strong>Opening Hours:</strong><br><?php echo editable($content['about']['locations']['waterloo']['hours'] ?? '', 'about.locations.waterloo.hours'); ?></p>
                                <p class="location-note"><?php echo editable($content['about']['locations']['waterloo']['note'] ?? '', 'about.locations.waterloo.note'); ?></p>
                            </div>

                            <div class="location-card">
                                <h3><?php echo editable($content['about']['locations']['formby']['name'] ?? 'Formby Office', 'about.locations.formby.name'); ?></h3>
                                <p class="location-tag"><?php echo editable($content['about']['locations']['formby']['tag'] ?? '', 'about.locations.formby.tag'); ?></p>
                                <p><strong>Address:</strong><br><?php echo editable($content['about']['locations']['formby']['address'] ?? '', 'about.locations.formby.address'); ?></p>
                                <p><strong>Opening Hours:</strong><br><?php echo editable($content['about']['locations']['formby']['hours'] ?? '', 'about.locations.formby.hours'); ?></p>
                                <p class="location-note"><?php echo editable($content['about']['locations']['formby']['note'] ?? '', 'about.locations.formby.note'); ?></p>
                            </div>

                            <div class="location-card">
                                <h3><?php echo editable($content['about']['locations']['netherton']['name'] ?? 'Netherton Office', 'about.locations.netherton.name'); ?></h3>
                                <p class="location-tag"><?php echo editable($content['about']['locations']['netherton']['tag'] ?? '', 'about.locations.netherton.tag'); ?></p>
                                <p><strong>Address:</strong><br><?php echo editable($content['about']['locations']['netherton']['address'] ?? '', 'about.locations.netherton.address'); ?></p>
                                <p><strong>Opening Hours:</strong><br><?php echo editable($content['about']['locations']['netherton']['hours'] ?? '', 'about.locations.netherton.hours'); ?></p>
                                <p class="location-note"><?php echo editable($content['about']['locations']['netherton']['note'] ?? '', 'about.locations.netherton.note'); ?></p>
                            </div>
                        </div>

                        <div class="contact-banner">
                            <p><?php echo editable($content['about']['locations']['contact_banner']['text'] ?? '', 'about.locations.contact_banner.text'); ?></p>
                            <h3><?php echo editable($content['about']['locations']['contact_banner']['phone'] ?? '', 'about.locations.contact_banner.phone'); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
// Include footer (which now includes the contact form)
require_once 'includes/footer.php';
?>