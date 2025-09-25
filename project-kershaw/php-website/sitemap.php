<?php
// Include configuration
require_once 'includes/config.php';
require_once 'includes/blog-config.php';

// Page metadata
$page_title = "Sitemap | Arthur Kershaw Funeral Services";
$page_description = "Complete sitemap of Arthur Kershaw Funeral Services website including all pages and latest news articles.";
$page_keywords = "sitemap, site map, Arthur Kershaw funeral services";

// Function to fetch recent blog posts
function getRecentBlogPosts($limit = 20) {
    if (!BLOG_ENABLED) {
        return [];
    }

    $api_url = SITE_URL . '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts';
    $api_url .= '?per_page=' . $limit;
    $api_url .= '&orderby=date&order=desc';
    $api_url .= '&_fields=id,title,slug,date';

    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'ignore_errors' => true
        ]
    ]);

    $response = @file_get_contents($api_url, false, $context);

    if ($response === false) {
        return [];
    }

    $posts = json_decode($response, true);

    if (!is_array($posts)) {
        return [];
    }

    return $posts;
}

// Include header
require_once 'includes/header.php';
?>

<style>
.sitemap-section {
    padding: 80px 0;
    background: white;
}

.sitemap-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.sitemap-header {
    text-align: center;
    margin-bottom: 60px;
}

.sitemap-header h1 {
    font-size: 2.5rem;
    color: var(--text-dark);
    margin-bottom: 15px;
}

.sitemap-header p {
    color: var(--text-muted);
    font-size: 1.1rem;
}

.sitemap-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 60px;
}

.sitemap-category {
    background: var(--bg-light);
    padding: 30px;
    border-radius: 8px;
}

.sitemap-category h2 {
    font-size: 1.3rem;
    color: var(--primary-color);
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--primary-color);
}

.sitemap-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sitemap-links li {
    margin-bottom: 12px;
}

.sitemap-links a {
    color: var(--text-dark);
    text-decoration: none;
    transition: color 0.3s ease;
    display: flex;
    align-items: center;
}

.sitemap-links a:before {
    content: "›";
    font-size: 1.2rem;
    margin-right: 8px;
    color: var(--primary-color);
}

.sitemap-links a:hover {
    color: var(--primary-color);
}

.blog-posts-section {
    background: white;
    padding: 40px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.blog-posts-section h2 {
    font-size: 1.5rem;
    color: var(--text-dark);
    margin-bottom: 30px;
    text-align: center;
}

.blog-posts-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.blog-post-item {
    padding: 15px;
    background: var(--bg-light);
    border-radius: 4px;
    transition: background 0.3s ease;
}

.blog-post-item:hover {
    background: #e8e8e8;
}

.blog-post-item a {
    color: var(--text-dark);
    text-decoration: none;
    display: block;
}

.blog-post-date {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-top: 5px;
}

.xml-sitemap-link {
    text-align: center;
    margin-top: 40px;
    padding-top: 40px;
    border-top: 1px solid #e0e0e0;
}

.xml-sitemap-link a {
    color: var(--primary-color);
    text-decoration: none;
}

.xml-sitemap-link a:hover {
    text-decoration: underline;
}
</style>

<section class="sitemap-section">
    <div class="sitemap-container">
        <div class="sitemap-header">
            <h1>Sitemap</h1>
            <p>Navigate through all pages of our website</p>
        </div>

        <div class="sitemap-grid">
            <!-- Main Pages -->
            <div class="sitemap-category">
                <h2>Main Pages</h2>
                <ul class="sitemap-links">
                    <li><a href="<?php echo BASE_URL; ?>/">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/about.php">About Us</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/contact.php">Contact</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/blog.php">News & Updates</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="sitemap-category">
                <h2>Our Services</h2>
                <ul class="sitemap-links">
                    <li><a href="<?php echo BASE_URL; ?>/service-traditional-burial.php">Traditional Burial</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/service-cremation.php">Cremation Service</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/service-direct-cremation.php">Direct Cremation</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/pre-paid-plans.php">Pre-Paid Funeral Plans</a></li>
                </ul>
            </div>

            <!-- Locations -->
            <div class="sitemap-category">
                <h2>Locations We Serve</h2>
                <ul class="sitemap-links">
                    <li><a href="<?php echo BASE_URL; ?>/funeral-directors-sale.php">Sale</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/funeral-directors-stretford.php">Stretford</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/funeral-directors-altrincham.php">Altrincham</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/funeral-directors-timperley.php">Timperley</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/funeral-directors-ashton-upon-mersey.php">Ashton-Upon-Mersey</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/funeral-directors-hale.php">Hale</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/funeral-directors-lymm.php">Lymm</a></li>
                </ul>
            </div>

            <!-- Information -->
            <div class="sitemap-category">
                <h2>Information</h2>
                <ul class="sitemap-links">
                    <li><a href="<?php echo BASE_URL; ?>/funeral-costs.php">Funeral Costs</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/arranging-a-funeral.php">Arranging a Funeral</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/faqs.php">FAQs</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/testimonials.php">Testimonials</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/funeral-notices.php">Funeral Notices</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div class="sitemap-category">
                <h2>Legal</h2>
                <ul class="sitemap-links">
                    <li><a href="<?php echo BASE_URL; ?>/privacy-policy.php">Privacy Policy</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/sitemap.php">Sitemap</a></li>
                </ul>
            </div>
        </div>

        <!-- Recent Blog Posts -->
        <?php
        $recent_posts = getRecentBlogPosts(20);
        if (!empty($recent_posts)):
        ?>
        <div class="blog-posts-section">
            <h2>Recent News & Updates</h2>
            <div class="blog-posts-list">
                <?php foreach ($recent_posts as $post):
                    $title = html_entity_decode($post['title']['rendered'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $date = date('F j, Y', strtotime($post['date']));
                ?>
                <div class="blog-post-item">
                    <a href="<?php echo BASE_URL; ?>/blog-post.php?id=<?php echo $post['id']; ?>&slug=<?php echo $post['slug']; ?>">
                        <?php echo htmlspecialchars($title); ?>
                        <div class="blog-post-date"><?php echo $date; ?></div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="<?php echo BASE_URL; ?>/blog.php" class="btn btn-outline">View All News Articles</a>
            </div>
        </div>
        <?php endif; ?>

        <!-- XML Sitemap Link -->
        <div class="xml-sitemap-link">
            <p><a href="<?php echo BASE_URL; ?>/sitemap.xml.php" target="_blank">
                <i class="fas fa-code"></i> XML Sitemap for Search Engines
            </a></p>
        </div>
    </div>
</section>

<?php
// Include footer
require_once 'includes/footer.php';
?>