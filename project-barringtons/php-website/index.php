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

// Set page meta from JSON or use defaults
$page_title = $content['meta']['title'] ?? 'Barringtons Funeral Services | Independent Family Funeral Directors Liverpool';
$page_description = $content['meta']['description'] ?? 'Family funeral directors in Liverpool since 1902. Available 24/7. Compassionate, professional service with transparent pricing. Call 0151 928 1625.';
$page_keywords = $content['meta']['keywords'] ?? 'funeral directors Liverpool, family funeral directors, Barringtons funerals, Liverpool funeral services';

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
                <div id="youtube-placeholder" style="width: 100%; height: 100%; border-radius: 15px; background: #000; position: relative; cursor: pointer; overflow: hidden;">
                    <!-- YouTube thumbnail -->
                    <img src="https://img.youtube.com/vi/wvL8x-ny-1g/maxresdefault.jpg"
                         alt="Barrington Family Video Thumbnail"
                         style="width: 100%; height: 100%; object-fit: cover;">
                    <!-- Play button overlay -->
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80px; height: 80px; background: rgba(0,0,0,0.8); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 40px; height: 40px; fill: white; margin-left: 5px;" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                    <!-- Click to play text -->
                    <div style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); color: white; background: rgba(0,0,0,0.7); padding: 8px 16px; border-radius: 20px; font-size: 14px;">
                        Click to play video
                    </div>
                </div>
                <!-- Hidden iframe to be loaded on click -->
                <iframe
                    id="youtube-iframe"
                    data-src="https://www.youtube.com/embed/wvL8x-ny-1g?autoplay=1"
                    style="width: 100%; height: 100%; border-radius: 15px; border: none; display: none;"
                    title="Barrington Family Video"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </section>

    <section class="services-section" id="services">
        <div class="container">
            <h2><?php echo editable($content['services']['title'] ?? 'Services We Offer', 'services.title'); ?></h2>
            <div class="services-grid">
                <div class="service-card fade-in">
                    <div class="service-image">
                        <?php
                        $img = editableImage($content['services']['service1']['image'] ?? 'assets/images/arrange.jpg', 'services.service1.image', 'Funeral arrangement consultation', 'Arrange a Funeral');
                        echo str_replace('<img ', '<img style="width: 100%; height: 100%; object-fit: cover;" ', $img);
                        ?>
                    </div>
                    <div class="service-content">
                        <h3><?php echo editable($content['services']['service1']['title'] ?? 'Arrange a Funeral', 'services.service1.title'); ?></h3>
                        <p><?php echo editable($content['services']['service1']['description'] ?? '', 'services.service1.description'); ?></p>
                    </div>
                </div>
                <div class="service-card fade-in">
                    <div class="service-image">
                        <?php
                        $img = editableImage($content['services']['service2']['image'] ?? 'assets/images/estimator.jpg', 'services.service2.image', 'Funeral cost estimation', 'Funeral Estimator');
                        echo str_replace('<img ', '<img style="width: 100%; height: 100%; object-fit: cover;" ', $img);
                        ?>
                    </div>
                    <div class="service-content">
                        <h3><?php echo editable($content['services']['service2']['title'] ?? 'Funeral Estimator', 'services.service2.title'); ?></h3>
                        <p><?php echo editable($content['services']['service2']['description'] ?? '', 'services.service2.description'); ?></p>
                    </div>
                </div>
                <div class="service-card fade-in">
                    <div class="service-image">
                        <?php
                        $img = editableImage($content['services']['service3']['image'] ?? 'assets/images/planning.jpg', 'services.service3.image', 'Funeral pre-planning consultation', 'Plan Ahead');
                        echo str_replace('<img ', '<img style="width: 100%; height: 100%; object-fit: cover;" ', $img);
                        ?>
                    </div>
                    <div class="service-content">
                        <h3><?php echo editable($content['services']['service3']['title'] ?? 'Plan Ahead', 'services.service3.title'); ?></h3>
                        <p><?php echo editable($content['services']['service3']['description'] ?? '', 'services.service3.description'); ?></p>
                    </div>
                </div>
                <div class="service-card fade-in">
                    <div class="service-image">
                        <?php
                        $img = editableImage($content['services']['service4']['image'] ?? 'assets/images/storefront.jpg', 'services.service4.image', 'Memorial masonry services', 'Memorial Masonry');
                        echo str_replace('<img ', '<img style="width: 100%; height: 100%; object-fit: cover;" ', $img);
                        ?>
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
            <h2><?php echo editable($content['news']['title'] ?? 'Latest News & Support', 'news.title'); ?></h2>
            <p><?php echo editable($content['news']['subtitle'] ?? '', 'news.subtitle'); ?></p>
            <?php foreach(($content['news']['articles'] ?? []) as $index => $article): ?>
            <div class="news-card fade-in">
                <div class="news-date"><?php echo editable($article['date'] ?? '', "news.articles.$index.date"); ?></div>
                <h3><?php echo editable($article['title'] ?? '', "news.articles.$index.title"); ?></h3>
                <p><?php echo editable($article['excerpt'] ?? '', "news.articles.$index.excerpt"); ?></p>
                <a href="<?php echo $article['link'] ?? '#'; ?>" class="read-more">Read More →</a>
            </div>
            <?php endforeach; ?>
            <div class="news-cta">
                <p><?php echo editable($content['news']['cta']['text'] ?? '', 'news.cta.text'); ?></p>
                <h3><?php echo editable($content['news']['cta']['phone'] ?? '', 'news.cta.phone'); ?></h3>
            </div>
        </div>
    </section>

    <section class="branches-section">
        <div class="container">
            <h2><?php echo editable($content['branches']['title'] ?? 'Our Branches', 'branches.title'); ?></h2>
            <p><?php echo editable($content['branches']['subtitle'] ?? '', 'branches.subtitle'); ?></p>
            <div class="branches-grid">
                <?php foreach(($content['branches']['locations'] ?? []) as $index => $branch): ?>
                <div class="branch-card fade-in">
                    <div class="branch-image">
                        <?php
                        $img = editableImage($branch['image'] ?? '', "branches.locations.$index.image", $branch['name'] . ' branch exterior', $branch['name'] . ' Branch');
                        // Add style attribute to the image
                        echo str_replace('<img ', '<img style="width: 100%; height: 200px; object-fit: cover;" ', $img);
                        ?>
                    </div>
                    <div class="branch-info">
                        <h3><?php echo editable($branch['name'] ?? '', "branches.locations.$index.name"); ?></h3>
                        <p><strong>Address:</strong> <?php echo editable($branch['address'] ?? '', "branches.locations.$index.address"); ?></p>
                        <p><strong>Phone:</strong> <?php echo editable($branch['phone'] ?? '', "branches.locations.$index.phone"); ?></p>
                        <p><strong>Hours:</strong> <?php echo editable($branch['hours'] ?? '', "branches.locations.$index.hours"); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="blog-preview-section">
        <div class="container">
            <h2><?php echo editable($content['blog']['title'] ?? 'Latest News & Articles', 'blog.title'); ?></h2>
            <p class="blog-subtitle"><?php echo editable($content['blog']['subtitle'] ?? 'Stay informed with our latest updates and helpful resources', 'blog.subtitle'); ?></p>

            <?php
            // Include blog fetcher
            // require_once 'includes/blog-fetcher.php';

            // Temporarily disabled until WordPress is installed
            // $latest_posts = fetchLatestBlogPosts(4);
            $latest_posts = false; // Temporarily set to false

            if ($latest_posts && count($latest_posts) > 0): ?>
                <div class="blog-preview-grid">
                    <?php foreach ($latest_posts as $post): ?>
                        <div class="blog-preview-card fade-in">
                            <?php if ($post['featured_image']): ?>
                                <div class="blog-preview-image">
                                    <img src="<?php echo $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="blog-preview-content">
                                <div class="blog-preview-date"><?php echo $post['date']; ?></div>
                                <h3><?php echo $post['title']; ?></h3>
                                <p><?php echo $post['excerpt']; ?></p>
                                <a href="<?php echo $post['link']; ?>" class="read-more">Read More →</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="blog-view-all">
                    <a href="blog-news.php" class="btn-primary">View All Articles</a>
                </div>
            <?php else: ?>
                <div class="blog-coming-soon">
                    <p>Blog articles coming soon. Check back for helpful resources and updates.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <style>
        .blog-preview-section {
            background: #f8f9fa;
            padding: 60px 0;
            margin-top: 40px;
        }

        .blog-preview-section h2 {
            text-align: center;
            color: #333;
            font-size: 36px;
            margin-bottom: 10px;
        }

        .blog-subtitle {
            text-align: center;
            color: #666;
            font-size: 18px;
            margin-bottom: 40px;
        }

        .blog-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .blog-preview-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .blog-preview-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .blog-preview-image {
            width: 100%;
            height: 180px;
            overflow: hidden;
        }

        .blog-preview-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .blog-preview-content {
            padding: 20px;
        }

        .blog-preview-date {
            color: #999;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .blog-preview-content h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .blog-preview-content p {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .read-more {
            color: var(--primary-color, #2c5530);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }

        .read-more:hover {
            color: var(--primary-hover, #1e3a21);
        }

        .blog-view-all {
            text-align: center;
        }

        .btn-primary {
            display: inline-block;
            background: var(--primary-color, #2c5530);
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--primary-hover, #1e3a21);
        }

        .blog-coming-soon {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            color: #666;
        }

        @media (max-width: 768px) {
            .blog-preview-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
    // Lazy load YouTube video
    document.addEventListener('DOMContentLoaded', function() {
        const placeholder = document.getElementById('youtube-placeholder');
        const iframe = document.getElementById('youtube-iframe');

        if (placeholder && iframe) {
            placeholder.addEventListener('click', function() {
                // Set the iframe src from data-src
                iframe.src = iframe.dataset.src;
                // Hide placeholder and show iframe
                placeholder.style.display = 'none';
                iframe.style.display = 'block';
            });
        }
    });
    </script>

<?php
// Include footer (which now includes the contact form)
require_once 'includes/footer.php';
?>