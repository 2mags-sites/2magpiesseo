<?php
require_once 'config.php';
require_once 'functions.php';

// Auto-detect page name from script filename
$page_name = basename($_SERVER['SCRIPT_NAME'], '.php');
if (empty($page_name)) $page_name = 'about';

// Load page data
$page_data = get_page_data($page_name);
$business_data = get_universal_data('business');
$navigation_data = get_universal_data('navigation');
$contact_data = get_universal_data('contact');

// Get meta data
$meta = isset($page_data['meta']) ? $page_data['meta'] : [];
$content = isset($page_data['content']) ? $page_data['content'] : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo generate_meta_tags($meta); ?>

    <!-- HTML5 Boilerplate CSS -->
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <!-- Schema.org markup -->
    <?php echo generate_schema($business_data); ?>

    <!-- Additional meta tags -->
    <meta name="theme-color" content="#fafafa">
    <meta name="robots" content="index, follow">
    <meta name="author" content="<?php echo e($business_data['name']); ?>">
</head>
<body>
    <?php include 'components/header.php'; ?>

    <main role="main">
        
        <?php if (isset($content['hero'])): ?>
        <section class="hero hero-about">
            <div class="container">
                <h1><?php echo e($content['hero']['title']); ?></h1>
                <p class="hero-subtitle"><?php echo e($content['hero']['subtitle']); ?></p>
            </div>
        </section>
        <?php endif; ?>

        <?php if (isset($content['our_story'])): ?>
        <section class="about-content">
            <div class="container">
                <div class="content-block">
                    <h2><?php echo e($content['our_story']['title']); ?></h2>
                    <p><?php echo e($content['our_story']['content']); ?></p>

                    <?php if (isset($content['our_story']['milestones']) && is_array($content['our_story']['milestones'])): ?>
                    <div class="milestones">
                        <h3>Our Journey</h3>
                        <div class="timeline">
                            <?php foreach ($content['our_story']['milestones'] as $milestone): ?>
                            <div class="milestone-item">
                                <span class="year"><?php echo e($milestone['year']); ?></span>
                                <span class="event"><?php echo e($milestone['event']); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if (isset($content['mission'])): ?>
                <div class="content-block">
                    <h2><?php echo e($content['mission']['title']); ?></h2>
                    <p><?php echo e($content['mission']['statement']); ?></p>

                    <?php if (isset($content['mission']['core_values']) && is_array($content['mission']['core_values'])): ?>
                    <div class="values-grid">
                        <?php foreach ($content['mission']['core_values'] as $value): ?>
                        <div class="value-item">
                            <h3><?php echo e($value['title']); ?></h3>
                            <p><?php echo e($value['description']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if (isset($content['what_sets_us_apart'])): ?>
                <div class="content-block">
                    <h2><?php echo e($content['what_sets_us_apart']['title']); ?></h2>
                    <p><?php echo e($content['what_sets_us_apart']['introduction']); ?></p>

                    <?php if (isset($content['what_sets_us_apart']['differentiators']) && is_array($content['what_sets_us_apart']['differentiators'])): ?>
                    <div class="differentiators-grid">
                        <?php foreach ($content['what_sets_us_apart']['differentiators'] as $diff): ?>
                        <div class="differentiator-item">
                            <h3><?php echo e($diff['title']); ?></h3>
                            <p><?php echo e($diff['description']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <?php include 'components/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>
</html>