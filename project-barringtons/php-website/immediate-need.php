<?php
// Immediate Need Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('immediate-need');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Immediate Need - 24 Hour Support | Barringtons Funeral Services';
$page_description = $content['meta']['description'] ?? 'We\'re here for you 24/7 when you need immediate funeral support. Compassionate guidance through every step. Call 0151 928 1625 anytime.';
$page_keywords = $content['meta']['keywords'] ?? 'immediate funeral need Liverpool, 24 hour funeral support, emergency funeral services, death registration Liverpool, what to do when someone dies';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="immediate-need" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'Immediate Need', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="intro-block fade-in">
                <h2><?php echo editable($content['intro']['title'] ?? 'When Someone Dies: Your First Steps', 'intro.title'); ?></h2>
                <p class="lead-text"><?php echo editable($content['intro']['subtitle'] ?? '', 'intro.subtitle'); ?></p>
            </div>

            <div class="steps-container">
                <?php foreach(($content['steps'] ?? []) as $index => $step): ?>
                <div class="info-card fade-in">
                    <div class="step-number"><?php echo isset($step['number']) ? str_replace(['Step ', 'step '], '', $step['number']) : ($index + 1); ?></div>
                    <h3><?php echo editable($step['title'] ?? '', "steps.$index.title"); ?></h3>
                    <p><?php echo editable($step['description'] ?? '', "steps.$index.description"); ?></p>
                    <?php if(!empty($step['list'])): ?>
                    <ul>
                        <?php foreach($step['list'] as $item): ?>
                        <li><?php echo $item; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>

            </div>

            <div class="promise-section fade-in">
                <h3><?php echo editable($content['remember']['title'] ?? 'Remember:', 'remember.title'); ?></h3>
                <ul class="remember-list">
                    <?php foreach(($content['remember']['points'] ?? []) as $point): ?>
                    <li><?php echo $point; ?></li>
                    <?php endforeach; ?>
                </ul>

                <p class="highlight-text"><?php echo editable($content['remember']['highlight'] ?? '', 'remember.highlight'); ?></p>
            </div>

            <div class="cta-section fade-in">
                <h2><?php echo editable($content['cta']['title'] ?? "We're Here to Help", 'cta.title'); ?></h2>
                <p><?php echo editable($content['cta']['subtitle'] ?? '', 'cta.subtitle'); ?></p>

                <div class="contact-cards">
                    <div class="contact-card">
                        <h3>24 Hour Support Line</h3>
                        <p class="phone-number">0151 928 1625</p>
                        <p>Available any time, day or night</p>
                    </div>

                    <div class="contact-card">
                        <h3>Main Office</h3>
                        <p>23 Crosby Rd N, Waterloo L22 4QF</p>
                        <p>Open 24 hours</p>
                    </div>

                    <div class="contact-card">
                        <h3>Email Support</h3>
                        <p>care@barringtonsfunerals.co.uk</p>
                        <p>We'll respond as quickly as possible</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Enhanced Step Styling */
        .steps-container .info-card {
            position: relative;
            padding-left: 5rem;
            padding-top: 1.5rem;
            padding-right: 2rem;
            padding-bottom: 2rem;
            background: white;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            border-left: 3px solid var(--pink, #de3280);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .steps-container .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .steps-container .step-number {
            position: absolute;
            left: -1.5rem;
            top: 2rem;
            width: 3rem;
            height: 3rem;
            background: var(--pink, #de3280);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 3px 10px rgba(222, 50, 128, 0.3);
            border: 3px solid white;
            line-height: 1;
            text-align: center;
            padding: 0;
        }

        .steps-container .info-card h3 {
            color: var(--dark-blue, #062159);
            font-size: 1.4rem;
            margin-bottom: 1rem;
            margin-top: 0;
        }

        .steps-container .info-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .steps-container .info-card ul {
            margin: 1rem 0;
            padding-left: 1.5rem;
        }

        .steps-container .info-card ul li {
            color: #666;
            line-height: 1.8;
            margin-bottom: 0.5rem;
        }

        /* Add connecting line between steps */
        .steps-container {
            position: relative;
        }

        .steps-container::before {
            content: '';
            position: absolute;
            left: 1.5rem;
            top: 4rem;
            bottom: 3rem;
            width: 2px;
            background: linear-gradient(to bottom, var(--pink, #de3280), rgba(222, 50, 128, 0.2));
            z-index: -1;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .steps-container .info-card {
                padding-left: 3.5rem;
                margin-left: 1rem;
            }

            .steps-container .step-number {
                left: -0.75rem;
                width: 2.5rem;
                height: 2.5rem;
                font-size: 0.85rem;
            }

            .steps-container::before {
                left: 1.5rem;
            }

            .steps-container .info-card h3 {
                font-size: 1.2rem;
            }
        }
    </style>

<?php
// Include footer (which now includes the contact form)
require_once 'includes/footer.php';
?>