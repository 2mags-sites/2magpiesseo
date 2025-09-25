<?php
// Helpful Links Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('helpful-links');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Grief Support & Helpful Links | Barringtons Funeral Services';
$page_description = $content['meta']['description'] ?? 'Grief support resources and helpful links for bereavement. Find local support groups, counselling services, and practical guidance in Liverpool.';
$page_keywords = $content['meta']['keywords'] ?? 'grief support Liverpool, bereavement help, funeral resources, grief counselling, support groups';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="helpful-links" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'Helpful Links', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="intro-block fade-in">
                <h2><?php echo editable($content['intro']['title'] ?? 'Support & Resources', 'intro.title'); ?></h2>
                <p class="lead-text"><?php echo editable($content['intro']['subtitle'] ?? '', 'intro.subtitle'); ?></p>
            </div>

            <div class="resources-grid">
                <?php foreach(($content['resources'] ?? []) as $index => $resource): ?>
                <div class="resource-card fade-in">
                    <h3><?php echo editable($resource['title'] ?? '', "resources.$index.title"); ?></h3>
                    <ul>
                        <?php if(!empty($resource['links'])): ?>
                            <?php foreach($resource['links'] as $link): ?>
                            <li><a href="<?php echo $link['url'] ?? '#'; ?>" target="_blank"><?php echo $link['name'] ?? ''; ?></a> - <?php echo $link['description'] ?? ''; ?></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if(!empty($resource['contacts'])): ?>
                            <?php foreach($resource['contacts'] as $contact): ?>
                            <li><strong><?php echo $contact['name'] ?? ''; ?></strong><?php echo !empty($contact['phone']) ? ' - ' . $contact['phone'] : ''; ?><?php echo !empty($contact['description']) ? ' - ' . $contact['description'] : ''; ?></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php
// Include footer (which now includes the contact form)
require_once 'includes/footer.php';
?>