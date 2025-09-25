<?php
// Set content type to XML
header('Content-Type: application/xml; charset=UTF-8');

// Include configuration
require_once 'includes/config.php';
require_once 'includes/blog-config.php';

// Determine site URL
$site_url = ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false)
    ? 'http://localhost' . BASE_URL
    : 'https://www.arthurkershawfunerals.com';

// Static pages array with priorities and change frequencies
$static_pages = [
    ['loc' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
    ['loc' => '/about.php', 'priority' => '0.9', 'changefreq' => 'monthly'],
    ['loc' => '/contact.php', 'priority' => '0.9', 'changefreq' => 'monthly'],
    ['loc' => '/blog.php', 'priority' => '0.8', 'changefreq' => 'daily'],

    // Services
    ['loc' => '/service-traditional-burial.php', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => '/service-cremation.php', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => '/service-direct-cremation.php', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => '/pre-paid-plans.php', 'priority' => '0.8', 'changefreq' => 'monthly'],

    // Locations
    ['loc' => '/funeral-directors-sale.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => '/funeral-directors-stretford.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => '/funeral-directors-altrincham.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => '/funeral-directors-timperley.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => '/funeral-directors-ashton-upon-mersey.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => '/funeral-directors-hale.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => '/funeral-directors-lymm.php', 'priority' => '0.7', 'changefreq' => 'monthly'],

    // Information
    ['loc' => '/funeral-costs.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => '/arranging-a-funeral.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => '/faqs.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['loc' => '/testimonials.php', 'priority' => '0.6', 'changefreq' => 'weekly'],
    ['loc' => '/funeral-notices.php', 'priority' => '0.5', 'changefreq' => 'daily'],
    ['loc' => '/privacy-policy.php', 'priority' => '0.3', 'changefreq' => 'yearly'],
    ['loc' => '/sitemap.php', 'priority' => '0.3', 'changefreq' => 'monthly'],
];

// Function to fetch all blog posts from WordPress
function getAllBlogPosts() {
    if (!BLOG_ENABLED) {
        return [];
    }

    // Use cached version for sitemap (30-minute cache)
    require_once 'includes/cache.php';
    return getAllBlogPostsCached(1800); // 30-minute cache
}

// Start XML output
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <?php // Output static pages ?>
    <?php foreach ($static_pages as $page): ?>
    <url>
        <loc><?php echo htmlspecialchars($site_url . $page['loc']); ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq><?php echo $page['changefreq']; ?></changefreq>
        <priority><?php echo $page['priority']; ?></priority>
    </url>
    <?php endforeach; ?>

    <?php // Output blog posts ?>
    <?php
    $blog_posts = getAllBlogPosts();
    foreach ($blog_posts as $post):
        $post_url = $site_url . '/blog-post.php?id=' . $post['id'] . '&slug=' . $post['slug'];
        $lastmod = isset($post['modified']) ? date('Y-m-d', strtotime($post['modified'])) : date('Y-m-d', strtotime($post['date']));
    ?>
    <url>
        <loc><?php echo htmlspecialchars($post_url); ?></loc>
        <lastmod><?php echo $lastmod; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <?php endforeach; ?>

</urlset>