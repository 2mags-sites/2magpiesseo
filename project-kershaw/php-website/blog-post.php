<?php
// Include configuration
require_once 'includes/config.php';
require_once 'includes/blog-config.php';

// Get the post ID or slug from URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$post_slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['slug'])) : null;

if (!$post_id && !$post_slug) {
    header('Location: ' . BASE_URL . '/');
    exit;
}

// Fetch the single post from WordPress REST API
$api_url = SITE_URL . '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts/';

if ($post_id) {
    $api_url .= $post_id;
} else {
    $api_url .= '?slug=' . $post_slug . '&per_page=1';
}

// Add fields to get all data including Yoast
$api_url .= (strpos($api_url, '?') !== false ? '&' : '?') . '_embed&_fields=id,title,content,excerpt,date,slug,link,yoast_meta,featured_image_url,_embedded';

// Fetch the post
$context = stream_context_create([
    'http' => [
        'timeout' => 5,
        'ignore_errors' => true
    ]
]);

$response = @file_get_contents($api_url, false, $context);

if ($response === false) {
    header('Location: ' . BASE_URL . '/');
    exit;
}

$post_data = json_decode($response, true);

// Handle array response (when searching by slug)
if (is_array($post_data) && isset($post_data[0])) {
    $post = $post_data[0];
} else {
    $post = $post_data;
}

if (!$post || isset($post['code'])) {
    header('Location: ' . BASE_URL . '/');
    exit;
}

// Decode HTML entities
$post_title = html_entity_decode($post['title']['rendered'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
$post_content = $post['content']['rendered']; // Keep HTML for proper rendering
$post_excerpt = html_entity_decode(strip_tags($post['excerpt']['rendered']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
$post_date = date('F j, Y', strtotime($post['date']));

// Get featured image
$featured_image = null;
if (isset($post['featured_image_url'])) {
    $featured_image = $post['featured_image_url'];
} elseif (isset($post['_embedded']['wp:featuredmedia'][0]['source_url'])) {
    $featured_image = $post['_embedded']['wp:featuredmedia'][0]['source_url'];
}

// Get Yoast SEO data if available
$yoast_meta = isset($post['yoast_meta']) ? $post['yoast_meta'] : [];

// Page metadata (use Yoast data if available)
$page_title = !empty($yoast_meta['title']) ?
    html_entity_decode($yoast_meta['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8') :
    $post_title . ' | Arthur Kershaw Funeral Services';

$page_description = !empty($yoast_meta['description']) ?
    html_entity_decode($yoast_meta['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8') :
    $post_excerpt;

$page_keywords = !empty($yoast_meta['keywords']) ?
    html_entity_decode($yoast_meta['keywords'], ENT_QUOTES | ENT_HTML5, 'UTF-8') :
    'funeral news, Arthur Kershaw updates';

// Include header
require_once 'includes/header.php';
?>

<style>
.blog-post {
    max-width: 800px;
    margin: 0 auto;
    padding: 100px 20px 60px 20px; /* Increased top padding for fixed header */
}

.blog-post-header {
    margin-bottom: 40px;
    text-align: center;
}

.blog-post-title {
    font-size: 2.5rem;
    margin-bottom: 15px;
    color: var(--text-dark);
}

.blog-post-meta {
    color: var(--text-muted);
    font-size: 1rem;
}

.blog-post-image {
    width: 100%;
    max-height: 500px;
    object-fit: cover;
    margin-bottom: 40px;
    border-radius: 8px;
}

.blog-post-content {
    line-height: 1.8;
    font-size: 1.1rem;
}

.blog-post-content h2 {
    margin-top: 30px;
    margin-bottom: 15px;
}

.blog-post-content p {
    margin-bottom: 20px;
}

.blog-post-content img {
    max-width: 100%;
    height: auto;
    margin: 20px 0;
}

.back-to-blog {
    display: inline-flex;
    align-items: center;
    color: var(--primary-color);
    text-decoration: none;
    margin-bottom: 30px;
}

.back-to-blog:hover {
    text-decoration: underline;
}

.back-to-blog i {
    margin-right: 8px;
}

.yoast-debug {
    margin-top: 60px;
    padding: 20px;
    background: #f5f5f5;
    border-radius: 8px;
}

.yoast-debug h3 {
    margin-bottom: 15px;
}

.yoast-debug pre {
    background: white;
    padding: 15px;
    border-radius: 4px;
    overflow-x: auto;
}
</style>

<div class="container">
    <div class="blog-post">
        <a href="<?php echo BASE_URL; ?>/" class="back-to-blog">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>

        <article>
            <header class="blog-post-header">
                <h1 class="blog-post-title"><?php echo $post_title; ?></h1>
                <div class="blog-post-meta">
                    <time datetime="<?php echo $post['date']; ?>"><?php echo $post_date; ?></time>
                </div>
            </header>

            <?php if ($featured_image): ?>
                <img src="<?php echo $featured_image; ?>" alt="<?php echo htmlspecialchars($post_title); ?>" class="blog-post-image">
            <?php endif; ?>

            <div class="blog-post-content">
                <?php echo $post_content; ?>
            </div>
        </article>

        <?php /* Debug section - uncomment if needed
        <?php if (!empty($yoast_meta)): ?>
        <div class="yoast-debug">
            <h3>Yoast SEO Data (Debug)</h3>
            <pre><?php echo json_encode($yoast_meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></pre>
        </div>
        <?php endif; ?>
        */ ?>
    </div>
</div>

<?php
// Include footer
require_once 'includes/footer.php';
?>