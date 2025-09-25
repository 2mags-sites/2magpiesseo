<?php
// Include configuration
require_once 'includes/config.php';

// Include blog functions (only needed on homepage)
require_once 'includes/blog-config.php';
require_once 'includes/blog-functions.php';

// Page-specific variables
$page_title = "Arthur Kershaw Funeral Services | Funeral Directors Sale, Manchester";
$page_description = "Family-owned funeral directors in Sale, Manchester. Established 1892. Compassionate funeral services including burial, cremation and pre-paid plans. Available 24/7.";
$page_keywords = "funeral directors Sale, Arthur Kershaw funerals, funeral services Cheshire, funeral directors Manchester";

// Include header
require_once 'includes/header.php';
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Compassionate Funeral Services in Sale</h1>
            <p class="lead">Family-owned funeral directors serving Greater Manchester since 1892</p>
            <div style="margin-top: 30px;">
                <a href="<?php echo BASE_URL; ?>/contact.php" class="btn btn-secondary" style="margin-right: 15px;">Contact Us</a>
                <a href="tel:01619692288" class="btn btn-outline">Call 24/7: 0161 969 2288</a>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main>
        <!-- Welcome Section -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title">Welcome to Arthur Kershaw Funeral Services</h2>
                <div class="content-with-image">
                    <div class="content-text">
                        <p class="lead">For over 130 years, Arthur Kershaw Funeral Services has been a cornerstone of the Sale community, providing dignified and compassionate funeral services to families in their time of need.</p>
                        <p>As an independent, family-owned funeral directors, we understand that every life is unique and every farewell should be personal. We're here to guide you through this difficult time with care, respect, and professionalism.</p>
                        <p>Available 24 hours a day, 7 days a week, our experienced team is always ready to help, whether you need immediate assistance or wish to plan ahead.</p>
                    </div>
                    <div class="content-image">
                        <i class="fas fa-heart fa-3x"></i>
                        <p>130+ Years of<br>Caring Service</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title">Our Funeral Services</h2>
                <div class="service-grid">
                    <div class="service-card">
                        <i class="fas fa-cross fa-2x"></i>
                        <h3>Traditional Burial</h3>
                        <p>Dignified burial services with full ceremony, procession, and personalized touches to honor your loved one.</p>
                        <a href="/service-traditional-burial.php" class="btn-link">Learn More →</a>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-fire fa-2x"></i>
                        <h3>Cremation Service</h3>
                        <p>Respectful cremation with memorial service options, from simple ceremonies to elaborate celebrations.</p>
                        <a href="/service-cremation.php" class="btn-link">Learn More →</a>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-dove fa-2x"></i>
                        <h3>Direct Cremation</h3>
                        <p>Simple, affordable cremation without a service. A practical choice starting from £995.</p>
                        <a href="/service-direct-cremation.php" class="btn-link">Learn More →</a>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-shield-alt fa-2x"></i>
                        <h3>Pre-Paid Plans</h3>
                        <p>Plan ahead and fix costs at today's prices, giving your family peace of mind and financial protection.</p>
                        <a href="/pre-paid-plans.php" class="btn-link">Learn More →</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title">Why Choose Arthur Kershaw?</h2>
                <div class="content-with-image reverse">
                    <div class="content-text">
                        <div class="service-box">
                            <h4><i class="fas fa-users"></i> Family-Owned & Independent</h4>
                            <p>Not part of a corporate chain, we provide personal service with the flexibility to meet your specific needs.</p>
                        </div>
                        <div class="service-box">
                            <h4><i class="fas fa-history"></i> Established 1892</h4>
                            <p>Five generations of experience serving Sale and Greater Manchester families with dignity and respect.</p>
                        </div>
                        <div class="service-box">
                            <h4><i class="fas fa-pound-sign"></i> Transparent Pricing</h4>
                            <p>Clear, itemized quotes with no hidden charges. Options to suit all budgets from £995.</p>
                        </div>
                        <div class="service-box">
                            <h4><i class="fas fa-phone-alt"></i> Available 24/7</h4>
                            <p>Always here when you need us, day or night, with no additional charges for out-of-hours service.</p>
                        </div>
                    </div>
                    <div class="content-image">
                        <i class="fas fa-award fa-3x"></i>
                        <p>Trusted by<br>Local Families</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Service Areas -->
        <section class="content-section">
            <div class="container">
                <h2 class="section-title">Areas We Serve</h2>
                <p class="lead text-center mb-40">From our Sale funeral home, we provide services throughout Greater Manchester</p>
                <div class="locations-grid">
                    <a href="/funeral-directors-sale.php" class="location-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Sale</h4>
                        <p>M33</p>
                    </a>
                    <a href="/funeral-directors-stretford.php" class="location-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Stretford</h4>
                        <p>M32</p>
                    </a>
                    <a href="/funeral-directors-altrincham.php" class="location-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Altrincham</h4>
                        <p>WA14</p>
                    </a>
                    <a href="/funeral-directors-timperley.php" class="location-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Timperley</h4>
                        <p>WA15</p>
                    </a>
                    <a href="/funeral-directors-ashton-upon-mersey.php" class="location-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Ashton</h4>
                        <p>M33</p>
                    </a>
                    <a href="/funeral-directors-hale.php" class="location-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Hale</h4>
                        <p>WA15</p>
                    </a>
                    <a href="/funeral-directors-lymm.php" class="location-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Lymm</h4>
                        <p>WA13</p>
                    </a>
                </div>
            </div>
        </section>

        <!-- Latest News Section -->
        <?php
        if (BLOG_ENABLED):
            $posts = getLatestPosts(BLOG_POST_COUNT);
        ?>
        <section class="latest-posts-section">
            <div class="container">
                <h2 class="section-title"><?php echo BLOG_DISPLAY_NAME; ?></h2>
                <p class="lead text-center mb-40">Stay updated with our latest news and information</p>

                <div class="blog-posts-grid">
                    <?php if ($posts && !empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                        <article class="blog-card">
                            <?php if (!empty($post['featured_image'])): ?>
                            <div class="blog-card-image">
                                <img src="<?php echo $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                            </div>
                            <?php else: ?>
                            <div class="blog-card-image blog-card-placeholder">
                                <i class="fas fa-newspaper fa-3x"></i>
                            </div>
                            <?php endif; ?>

                            <div class="blog-card-content">
                                <h3 class="blog-card-title">
                                    <a href="<?php echo BASE_URL; ?>/blog-post.php?id=<?php echo $post['id']; ?>&slug=<?php echo $post['slug']; ?>">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h3>
                                <p class="blog-card-date"><?php echo formatPostDate($post['date']); ?></p>
                                <?php if (!empty($post['excerpt'])): ?>
                                <p class="blog-card-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                                <?php endif; ?>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="blog-placeholder" style="grid-column: 1/-1; text-align: center; padding: 40px;">
                            <i class="fas fa-newspaper fa-4x" style="color: #ddd; margin-bottom: 20px;"></i>
                            <p>WordPress is not yet installed. Blog posts will appear here once WordPress is set up in the /<?php echo BLOG_FOLDER; ?>/ folder.</p>
                            <p style="color: #666; font-size: 0.9em;">Currently showing sample posts for layout purposes.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($posts && !empty($posts)): ?>
                <div class="blog-view-all" style="text-align: center; margin-top: 40px;">
                    <a href="<?php echo BASE_URL; ?>/blog.php" class="btn btn-outline">View All <?php echo BLOG_NAV_TEXT; ?> <i class="fas fa-arrow-right"></i></a>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- CTA Section -->
        <section class="content-section">
            <div class="container">
                <div class="cta-box">
                    <h2>Here When You Need Us</h2>
                    <p class="lead">24-hour support from caring, experienced professionals</p>
                    <p><strong>Call us anytime on 0161 969 2288</strong></p>
                    <p>Or visit us at 168-170 Washway Road, Sale, M33 6RH</p>
                    <a href="/contact.php" class="btn btn-white">Get In Touch</a>
                </div>
            </div>
        </section>
    </main>

<?php
// Include footer
require_once 'includes/footer.php';
?>