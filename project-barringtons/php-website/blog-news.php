<?php
// Blog/News listing page
require_once 'includes/admin-config.php';

// Load content from JSON
$content = loadContent('blog-news');

// Set page meta
$page_title = $content['meta']['title'] ?? 'News & Updates | Barringtons Funeral Services';
$page_description = $content['meta']['description'] ?? 'Latest news, updates and helpful articles from Barringtons Funeral Services. Stay informed about our services and community involvement.';
$page_keywords = $content['meta']['keywords'] ?? 'funeral news Liverpool, bereavement support, funeral advice, Barringtons news';

// Include header
require_once 'includes/header.php';

// Include blog fetcher
require_once 'includes/blog-fetcher.php';

// Fetch blog posts
$blog_posts = fetchLatestBlogPosts(12); // Get more posts for the main blog page
?>

    <!-- Page Hero -->
    <section class="page-hero">
        <div class="hero-image" style="background-image: url('<?php echo $content['hero']['image'] ?? 'assets/images/hero-blog.jpg'; ?>');">
            <?php if (IS_ADMIN): ?>
                <div class="hero-edit-overlay" onclick="editHeroImage('blog-news', 'hero.image')" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 15px 30px; border-radius: 8px; cursor: pointer; font-weight: 500; display: none;">
                    📷 Click to Change Hero Image
                </div>
            <?php endif; ?>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content-single">
            <h1><?php echo editable($content['hero']['title'] ?? 'News & Updates', 'hero.title'); ?></h1>
            <p><?php echo editable($content['hero']['subtitle'] ?? 'Stay informed with the latest from Barringtons', 'hero.subtitle'); ?></p>
        </div>
    </section>

    <!-- Blog Posts Section -->
    <section class="blog-section section-padding">
        <div class="container">
            <?php if ($blog_posts && count($blog_posts) > 0): ?>
                <div class="blog-grid">
                    <?php foreach ($blog_posts as $post): ?>
                        <div class="blog-card">
                            <?php if ($post['featured_image']): ?>
                                <div class="blog-image">
                                    <img src="<?php echo $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                </div>
                            <?php else: ?>
                                <div class="blog-image">
                                    <img src="assets/images/blog-placeholder.jpg" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="blog-content">
                                <div class="blog-date"><?php echo $post['date']; ?></div>
                                <h3><?php echo $post['title']; ?></h3>
                                <p><?php echo $post['excerpt']; ?></p>
                                <a href="<?php echo $post['link']; ?>" class="read-more">Read More →</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-posts">
                    <h2>Coming Soon</h2>
                    <p>We're currently preparing our blog content. Please check back soon for helpful articles and updates from Barringtons Funeral Services.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <style>
        .blog-section {
            padding: 60px 0;
        }

        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .blog-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .blog-image {
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .blog-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .blog-content {
            padding: 25px;
        }

        .blog-date {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .blog-content h3 {
            color: #333;
            font-size: 20px;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .blog-content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .read-more {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .read-more:hover {
            color: var(--primary-hover);
        }

        .no-posts {
            text-align: center;
            padding: 60px 20px;
            background: #f9f9f9;
            border-radius: 10px;
        }

        .no-posts h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .no-posts p {
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .blog-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>

<?php
// Include footer
require_once 'includes/footer.php';
?>