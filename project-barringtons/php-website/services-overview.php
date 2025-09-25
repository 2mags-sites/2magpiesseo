<?php
// Services Overview Page
require_once 'includes/admin-config.php';

// Load page content
$content = loadContent('services-overview');

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Funeral Services Overview - Traditional & Contemporary | Barringtons Liverpool';
$page_description = $content['meta']['description'] ?? 'Complete range of funeral services in Liverpool. Traditional burials, cremations, green funerals, and celebrations of life. Transparent pricing from £1,795.';
$page_keywords = $content['meta']['keywords'] ?? 'funeral services Liverpool, traditional funeral, cremation services, green funerals, celebration of life, funeral prices';

// Include header
require_once 'includes/header.php';
?>

    <section class="page-hero">
        <div class="hero-image editable-hero-bg" data-field="hero.image" data-page="services-overview" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-background.jpg'; ?>');">
        <?php if (IS_ADMIN): ?>
            <div class="hero-edit-overlay" onclick="editHeroImage(this)" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                📷 Click to Change Hero Image
            </div>
        <?php endif; ?>
    </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'Services Overview', 'hero.title'); ?></h1>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="intro-block fade-in">
                <h2><?php echo editable($content['intro']['title'] ?? 'Comprehensive Funeral Services', 'intro.title'); ?></h2>
                <p class="lead-text"><?php echo editable($content['intro']['subtitle'] ?? '', 'intro.subtitle'); ?></p>
                <div class="credentials-section">
                    <p><?php echo editable($content['intro']['credentials'] ?? '', 'intro.credentials'); ?></p>
                </div>
            </div>

            <div class="services-list-grid">
                <div class="service-box fade-in">
                    <div class="service-icon">
                        <?php echo editableImage($content['services']['traditional']['image'] ?? '', 'services.traditional.image', 'Traditional chapel service with formal seating', 'Traditional Funeral Service'); ?>
                    </div>
                    <h3><?php echo editable($content['services']['traditional']['title'] ?? 'Traditional Funerals', 'services.traditional.title'); ?></h3>
                    <p><?php echo editable($content['services']['traditional']['description'] ?? '', 'services.traditional.description'); ?></p>
                    <ul>
                        <?php foreach(($content['services']['traditional']['features'] ?? []) as $feature): ?>
                        <li><?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="service-box fade-in">
                    <div class="service-icon">
                        <?php echo editableImage($content['services']['contemporary']['image'] ?? '', 'services.contemporary.image', 'Modern celebration of life gathering', 'Contemporary Celebration Service'); ?>
                    </div>
                    <h3><?php echo editable($content['services']['contemporary']['title'] ?? 'Contemporary Celebrations', 'services.contemporary.title'); ?></h3>
                    <p><?php echo editable($content['services']['contemporary']['description'] ?? '', 'services.contemporary.description'); ?></p>
                    <ul>
                        <?php foreach(($content['services']['contemporary']['features'] ?? []) as $feature): ?>
                        <li><?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="service-box fade-in">
                    <div class="service-icon">
                        <?php echo editableImage($content['services']['direct']['image'] ?? '', 'services.direct.image', 'Simple and dignified cremation service', 'Direct Cremation Service'); ?>
                    </div>
                    <h3><?php echo editable($content['services']['direct']['title'] ?? 'Direct Cremation', 'services.direct.title'); ?></h3>
                    <p><?php echo editable($content['services']['direct']['description'] ?? '', 'services.direct.description'); ?></p>
                    <ul>
                        <?php foreach(($content['services']['direct']['features'] ?? []) as $feature): ?>
                        <li><?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="service-box fade-in">
                    <div class="service-icon">
                        <?php echo editableImage($content['services']['green']['image'] ?? '', 'services.green.image', 'Natural woodland burial ground', 'Green Funeral Service'); ?>
                    </div>
                    <h3><?php echo editable($content['services']['green']['title'] ?? 'Green Funerals', 'services.green.title'); ?></h3>
                    <p><?php echo editable($content['services']['green']['description'] ?? '', 'services.green.description'); ?></p>
                    <ul>
                        <?php foreach(($content['services']['green']['features'] ?? []) as $feature): ?>
                        <li><?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="service-box fade-in">
                    <div class="service-icon">
                        <?php echo editableImage($content['services']['religious']['image'] ?? '', 'services.religious.image', 'Multi-faith chapel for religious services', 'Religious Funeral Service'); ?>
                    </div>
                    <h3><?php echo editable($content['services']['religious']['title'] ?? 'Religious Services', 'services.religious.title'); ?></h3>
                    <p><?php echo editable($content['services']['religious']['description'] ?? '', 'services.religious.description'); ?></p>
                    <ul>
                        <?php foreach(($content['services']['religious']['features'] ?? []) as $feature): ?>
                        <li><?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="service-box fade-in">
                    <div class="service-icon">
                        <?php echo editableImage($content['services']['home']['image'] ?? '', 'services.home.image', 'Family home with peaceful setting', 'Home Funeral Service'); ?>
                    </div>
                    <h3><?php echo editable($content['services']['home']['title'] ?? 'Home Funerals', 'services.home.title'); ?></h3>
                    <p><?php echo editable($content['services']['home']['description'] ?? '', 'services.home.description'); ?></p>
                    <ul>
                        <?php foreach(($content['services']['home']['features'] ?? []) as $feature): ?>
                        <li><?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="additional-services fade-in">
                <h2><?php echo editable($content['additional']['title'] ?? 'Additional Services', 'additional.title'); ?></h2>
                <div class="additional-grid">
                    <div class="additional-item">
                        <h4><?php echo editable($content['additional']['items']['stationery']['title'] ?? 'Memorial Stationery', 'additional.items.stationery.title'); ?></h4>
                        <p><?php echo editable($content['additional']['items']['stationery']['description'] ?? '', 'additional.items.stationery.description'); ?></p>
                    </div>
                    <div class="additional-item">
                        <h4><?php echo editable($content['additional']['items']['floral']['title'] ?? 'Floral Tributes', 'additional.items.floral.title'); ?></h4>
                        <p><?php echo editable($content['additional']['items']['floral']['description'] ?? '', 'additional.items.floral.description'); ?></p>
                    </div>
                    <div class="additional-item">
                        <h4><?php echo editable($content['additional']['items']['catering']['title'] ?? 'Catering Services', 'additional.items.catering.title'); ?></h4>
                        <p><?php echo editable($content['additional']['items']['catering']['description'] ?? '', 'additional.items.catering.description'); ?></p>
                    </div>
                    <div class="additional-item">
                        <h4><?php echo editable($content['additional']['items']['jewelry']['title'] ?? 'Memorial Jewelry', 'additional.items.jewelry.title'); ?></h4>
                        <p><?php echo editable($content['additional']['items']['jewelry']['description'] ?? '', 'additional.items.jewelry.description'); ?></p>
                    </div>
                    <div class="additional-item">
                        <h4><?php echo editable($content['additional']['items']['streaming']['title'] ?? 'Live Streaming', 'additional.items.streaming.title'); ?></h4>
                        <p><?php echo editable($content['additional']['items']['streaming']['description'] ?? '', 'additional.items.streaming.description'); ?></p>
                    </div>
                    <div class="additional-item">
                        <h4><?php echo editable($content['additional']['items']['repatriation']['title'] ?? 'Repatriation', 'additional.items.repatriation.title'); ?></h4>
                        <p><?php echo editable($content['additional']['items']['repatriation']['description'] ?? '', 'additional.items.repatriation.description'); ?></p>
                    </div>
                </div>
            </div>

            <div class="pricing-section fade-in">
                <h2><?php echo editable($content['pricing']['title'] ?? 'Transparent Pricing', 'pricing.title'); ?></h2>
                <p><?php echo editable($content['pricing']['subtitle'] ?? '', 'pricing.subtitle'); ?></p>
                <div class="pricing-cards">
                    <div class="pricing-card">
                        <h3><?php echo editable($content['pricing']['packages']['essential']['title'] ?? 'Essential Service', 'pricing.packages.essential.title'); ?></h3>
                        <p class="price"><?php echo editable($content['pricing']['packages']['essential']['price'] ?? 'From £1,795', 'pricing.packages.essential.price'); ?></p>
                        <p><?php echo editable($content['pricing']['packages']['essential']['description'] ?? '', 'pricing.packages.essential.description'); ?></p>
                        <a href="standardised-price-list.php" class="btn-outline">View Details</a>
                    </div>
                    <div class="pricing-card featured">
                        <h3><?php echo editable($content['pricing']['packages']['standard']['title'] ?? 'Standard Service', 'pricing.packages.standard.title'); ?></h3>
                        <p class="price"><?php echo editable($content['pricing']['packages']['standard']['price'] ?? 'From £2,995', 'pricing.packages.standard.price'); ?></p>
                        <p><?php echo editable($content['pricing']['packages']['standard']['description'] ?? '', 'pricing.packages.standard.description'); ?></p>
                        <a href="standardised-price-list.php" class="btn-primary">View Details</a>
                    </div>
                    <div class="pricing-card">
                        <h3><?php echo editable($content['pricing']['packages']['premium']['title'] ?? 'Premium Service', 'pricing.packages.premium.title'); ?></h3>
                        <p class="price"><?php echo editable($content['pricing']['packages']['premium']['price'] ?? 'From £4,495', 'pricing.packages.premium.price'); ?></p>
                        <p><?php echo editable($content['pricing']['packages']['premium']['description'] ?? '', 'pricing.packages.premium.description'); ?></p>
                        <a href="standardised-price-list.php" class="btn-outline">View Details</a>
                    </div>
                </div>
                <p class="pricing-note"><?php echo editable($content['pricing']['note'] ?? '', 'pricing.note'); ?></p>
            </div>
        </div>
    </section>

<?php
// Include footer (which now includes the contact form)
require_once 'includes/footer.php';
?>