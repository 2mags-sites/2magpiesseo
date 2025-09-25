<?php
require_once 'config.php';
require_once 'functions.php';

// Auto-detect page name from script filename
$page_name = basename($_SERVER['SCRIPT_NAME'], '.php');
if (empty($page_name)) $page_name = 'contact';

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
        <section class="hero hero-contact">
            <div class="container">
                <h1><?php echo e($content['hero']['title']); ?></h1>
                <p class="hero-subtitle"><?php echo e($content['hero']['subtitle']); ?></p>
            </div>
        </section>
        <?php endif; ?>

        <section class="contact-section">
            <div class="container">
                <div class="contact-grid">
                    <div class="contact-info">
                        <h2>Get In Touch</h2>
                        <?php if (isset($content['contact_info'])): ?>
                        <div class="info-item">
                            <strong>Phone:</strong>
                            <a href="tel:<?php echo format_phone($content['contact_info']['phone']); ?>">
                                <?php echo e($content['contact_info']['phone']); ?>
                            </a>
                        </div>
                        <div class="info-item">
                            <strong>Email:</strong>
                            <a href="mailto:<?php echo e($content['contact_info']['email']); ?>">
                                <?php echo e($content['contact_info']['email']); ?>
                            </a>
                        </div>
                        <div class="info-item">
                            <strong>Address:</strong>
                            <address><?php echo e($content['contact_info']['address']); ?></address>
                        </div>
                        <div class="info-item">
                            <strong>Hours:</strong>
                            <p><?php echo e($content['contact_info']['hours']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="contact-form">
                        <h2>Send Us a Message</h2>
                        <form action="handlers/contact.php" method="POST" id="contact-form">
                            <div class="form-group">
                                <label for="name">Your Name *</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Your Email *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Your Phone</label>
                                <input type="tel" id="phone" name="phone">
                            </div>
                            <div class="form-group">
                                <label for="message">Your Message *</label>
                                <textarea id="message" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'components/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>
</html>