<?php
// Homepage
require_once 'includes/admin-config.php';

// Load content from JSON
$content = loadContent('index');

// Business info for schema markup
$business_info = [
    'name' => 'Barringtons Funeral Services',
    'phone' => '0151 928 1625',
    'email' => 'info@barringtonsfunerals.co.uk',
    'address' => '23 Crosby Rd N, Waterloo, Liverpool, L22 4QF'
];

// Set page meta
$page_title = 'Barringtons Funeral Services | Independent Family Funeral Directors Liverpool';
$page_description = 'Family funeral directors in Liverpool since 1902. Available 24/7. Compassionate, professional service with transparent pricing. Call 0151 928 1625.';
$page_keywords = 'funeral directors Liverpool, family funeral directors, Barringtons funerals, Liverpool funeral services';

// Include header
require_once 'includes/header.php';
?>

    <section class="hero">
        <div class="hero-image"></div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-card">
                <h2><?php echo editable($content['hero']['card1']['title'] ?? 'Immediate Need', 'hero.card1.title'); ?></h2>
                <p><?php echo editable($content['hero']['card1']['description'] ?? 'Our caring team is here to guide you through every step', 'hero.card1.description'); ?></p>
                <button class="hero-btn" onclick="window.location.href='immediate-need.php'">Begin Planning</button>
            </div>
            <div class="hero-card">
                <h2><?php echo editable($content['hero']['card2']['title'] ?? 'Plan For The Future', 'hero.card2.title'); ?></h2>
                <p><?php echo editable($content['hero']['card2']['description'] ?? 'Take comfort in knowing your wishes are documented', 'hero.card2.description'); ?></p>
                <button class="hero-btn" onclick="window.location.href='planning-ahead.php'">Start Your Plan</button>
            </div>
        </div>
    </section>

    <section class="reviews-section">
        <h2><?php echo editable($content['reviews']['title'] ?? 'Our Family Caring for Your Family', 'reviews.title'); ?></h2>
        <div class="reviews-carousel">
            <button class="carousel-btn carousel-btn-left" onclick="scrollReviews(-1)">‹</button>
            <div class="reviews-container">
                <div class="reviews-track">
                    <div class="review-card">
                        <div class="stars">★★★★★</div>
                        <p class="review-text"><?php echo editable($content['reviews']['review1']['text'] ?? '', 'reviews.review1.text'); ?></p>
                        <p class="review-author"><?php echo editable($content['reviews']['review1']['author'] ?? '', 'reviews.review1.author'); ?></p>
                        <p class="review-source"><?php echo $content['reviews']['review1']['source'] ?? ''; ?></p>
                    </div>
                    <div class="review-card">
                        <div class="stars">★★★★★</div>
                        <p class="review-text"><?php echo editable($content['reviews']['review2']['text'] ?? '', 'reviews.review2.text'); ?></p>
                        <p class="review-author"><?php echo editable($content['reviews']['review2']['author'] ?? '', 'reviews.review2.author'); ?></p>
                        <p class="review-source"><?php echo $content['reviews']['review2']['source'] ?? ''; ?></p>
                    </div>
                </div>
            </div>
            <button class="carousel-btn carousel-btn-right" onclick="scrollReviews(1)">›</button>
        </div>
    </section>

    <section class="family-section">
        <div class="family-content">
            <div class="family-text">
                <h2><?php echo editable($content['family']['title'] ?? "Liverpool's Independent & Family Run Funeral Service", 'family.title'); ?></h2>
                <p><?php echo editable($content['family']['description1'] ?? '', 'family.description1'); ?></p>
                <p><?php echo editable($content['family']['description2'] ?? '', 'family.description2'); ?></p>
                <div class="accreditation-logos">
                    <img src="assets/images/pers_nafd.png" alt="NAFD Member" />
                    <img src="assets/images/pers_SAIF.png" alt="SAIF Member" />
                    <img src="assets/images/pers_agfd-logo.jpg" alt="Association of Green Funeral Directors" />
                    <img src="assets/images/pers_good-funeral-guide.jpg" alt="Good Funeral Guide Recommended" />
                    <img src="assets/images/pers_green.jpg" alt="Green Funeral Provider" />
                </div>
            </div>
            <div class="family-image">
                <img src="assets/images/family.jpg" alt="Barrington Family" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
            </div>
        </div>
    </section>

    <section class="services-section" id="services">
        <div class="container">
            <h2><?php echo editable($content['services']['title'] ?? 'Services We Offer', 'services.title'); ?></h2>
            <div class="services-grid">
                <div class="service-card fade-in">
                    <div class="service-image">
                        <img src="assets/images/arrange.jpg" alt="Arrange a Funeral" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="service-content">
                        <h3><?php echo editable($content['services']['service1']['title'] ?? 'Arrange a Funeral', 'services.service1.title'); ?></h3>
                        <p><?php echo editable($content['services']['service1']['description'] ?? '', 'services.service1.description'); ?></p>
                    </div>
                </div>
                <div class="service-card fade-in">
                    <div class="service-image">
                        <img src="assets/images/estimator.jpg" alt="Funeral Estimator" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="service-content">
                        <h3><?php echo editable($content['services']['service2']['title'] ?? 'Funeral Estimator', 'services.service2.title'); ?></h3>
                        <p><?php echo editable($content['services']['service2']['description'] ?? '', 'services.service2.description'); ?></p>
                    </div>
                </div>
                <div class="service-card fade-in">
                    <div class="service-image">
                        <img src="assets/images/planning.jpg" alt="Plan Ahead" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="service-content">
                        <h3><?php echo editable($content['services']['service3']['title'] ?? 'Plan Ahead', 'services.service3.title'); ?></h3>
                        <p><?php echo editable($content['services']['service3']['description'] ?? '', 'services.service3.description'); ?></p>
                    </div>
                </div>
                <div class="service-card fade-in">
                    <div class="service-image">
                        <img src="assets/images/storefront.jpg" alt="Memorial Masonry" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="service-content">
                        <h3><?php echo editable($content['services']['service4']['title'] ?? 'Memorial Masonry', 'services.service4.title'); ?></h3>
                        <p><?php echo editable($content['services']['service4']['description'] ?? '', 'services.service4.description'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- The rest of the sections remain the same -->
    <section class="news-section">
        <div class="news-content">
            <h2>Latest News & Support</h2>
            <p>Updates and helpful resources for our community</p>
            <div class="news-card fade-in">
                <div class="news-date">March 2024</div>
                <h3>Understanding Grief: A Guide for Families</h3>
                <p>Grief is a natural response to loss, and everyone experiences it differently...</p>
                <a href="helpful-links.php" class="read-more">Read More →</a>
            </div>
            <div class="news-card fade-in">
                <div class="news-date">February 2024</div>
                <h3>New Eco-Friendly Options Available</h3>
                <p>We're pleased to announce expanded green funeral options...</p>
                <a href="services-overview.php" class="read-more">Read More →</a>
            </div>
            <div class="news-cta">
                <p>Need immediate assistance?</p>
                <h3>Call us 24/7 on 0151 928 1625</h3>
            </div>
        </div>
    </section>

    <section class="branches-section">
        <div class="container">
            <h2>Our Branches</h2>
            <p>Three locations across Liverpool to serve you</p>
            <div class="branches-grid">
                <div class="branch-card fade-in">
                    <div class="branch-image">
                        <img src="assets/images/waterloo.jpg" alt="Waterloo Branch" style="width: 100%; height: 200px; object-fit: cover;">
                    </div>
                    <div class="branch-info">
                        <h3>Waterloo</h3>
                        <p><strong>Address:</strong> 23 Crosby Rd N, L22 4QF</p>
                        <p><strong>Phone:</strong> 0151 928 1625</p>
                        <p><strong>Hours:</strong> 24 Hours</p>
                    </div>
                </div>
                <div class="branch-card fade-in">
                    <div class="branch-image">
                        <img src="assets/images/formby.png" alt="Formby Branch" style="width: 100%; height: 200px; object-fit: cover;">
                    </div>
                    <div class="branch-info">
                        <h3>Formby</h3>
                        <p><strong>Address:</strong> 64 Elbow Lane, L37 4AB</p>
                        <p><strong>Phone:</strong> 0151 928 1625</p>
                        <p><strong>Hours:</strong> By Appointment</p>
                    </div>
                </div>
                <div class="branch-card fade-in">
                    <div class="branch-image">
                        <img src="assets/images/netherton.jpg" alt="Netherton Branch" style="width: 100%; height: 200px; object-fit: cover;">
                    </div>
                    <div class="branch-info">
                        <h3>Netherton</h3>
                        <p><strong>Address:</strong> 47 Liverpool Road, L30 3QA</p>
                        <p><strong>Phone:</strong> 0151 928 1625</p>
                        <p><strong>Hours:</strong> Mon-Fri 9am-5pm</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
// Include footer (which now includes the contact form)
require_once 'includes/footer.php';
?>