<?php
// Include configuration
require_once 'includes/config.php';
require_once 'includes/blog-config.php';
require_once 'includes/blog-functions.php';

// Pagination settings
$posts_per_page = 9; // Divisible by 3 for grid layout
$page_num = 1;
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page_num = max(1, intval($_GET['page']));
}

// Calculate offset for API
$offset = ($page_num - 1) * $posts_per_page;

// Fetch posts from WordPress REST API with pagination
$api_url = SITE_URL . '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts';
$api_url .= '?per_page=' . $posts_per_page;
$api_url .= '&offset=' . $offset;
$api_url .= '&orderby=date&order=desc';
$api_url .= '&_embed';

// Also get total posts count for pagination
$count_url = SITE_URL . '/' . BLOG_FOLDER . '/wp-json/wp/v2/posts';
$count_url .= '?per_page=1';

// Create context with timeout
$context = stream_context_create([
    'http' => [
        'timeout' => 5,
        'ignore_errors' => true
    ]
]);

// Fetch posts
$response = @file_get_contents($api_url, false, $context);
$posts = $response ? json_decode($response, true) : [];

// Get total posts from headers
$total_posts = 0;
$headers_found = false;
if (isset($http_response_header)) {
    foreach ($http_response_header as $header) {
        if (stripos($header, 'X-WP-Total:') === 0) {
            $total_posts = intval(trim(substr($header, 11)));
            $headers_found = true;
            break;
        }
    }
}

// If headers didn't work, try a separate HEAD request
if (!$headers_found || $total_posts == 0) {
    $context_head = stream_context_create([
        'http' => [
            'method' => 'HEAD',
            'timeout' => 3
        ]
    ]);

    // Get total count
    @file_get_contents($api_url, false, $context_head);
    if (isset($http_response_header)) {
        foreach ($http_response_header as $header) {
            if (stripos($header, 'X-WP-Total:') === 0) {
                $total_posts = intval(trim(substr($header, 11)));
                break;
            }
        }
    }
}

// If still no total, make a dedicated API call for total pages
if ($total_posts == 0) {
    // Use the X-WP-TotalPages header approach
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    $headers = curl_exec($ch);
    curl_close($ch);

    if (preg_match('/X-WP-Total:\s*(\d+)/i', $headers, $matches)) {
        $total_posts = intval($matches[1]);
    }
}

// As a fallback, if we still don't have total posts and we have a full page of posts,
// assume there might be more pages
if ($total_posts == 0 && count($posts) == $posts_per_page) {
    // Set a high number to show pagination
    $total_posts = 100; // This will show pagination controls
}

// Calculate total pages
$total_pages = $total_posts > 0 ? ceil($total_posts / $posts_per_page) : 1;

// Debug output (remove in production)
$debug_pagination = false; // Set to true to see debug info

// Format posts for display
$formatted_posts = [];
if (is_array($posts)) {
    foreach ($posts as $post) {
        // Decode HTML entities
        $title = html_entity_decode($post['title']['rendered'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $excerpt_raw = isset($post['excerpt']['rendered']) ? strip_tags($post['excerpt']['rendered']) : '';
        $excerpt = html_entity_decode($excerpt_raw, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Get featured image
        $featured_image = null;
        if (isset($post['featured_image_url'])) {
            $featured_image = $post['featured_image_url'];
        } elseif (isset($post['_embedded']['wp:featuredmedia'][0]['source_url'])) {
            $featured_image = $post['_embedded']['wp:featuredmedia'][0]['source_url'];
        }

        $formatted_posts[] = [
            'id' => $post['id'],
            'title' => $title,
            'slug' => $post['slug'],
            'date' => $post['date'],
            'excerpt' => $excerpt,
            'featured_image' => $featured_image
        ];
    }
}

// Page metadata
$page_title = "News & Updates | Arthur Kershaw Funeral Services";
$page_description = "Latest news and updates from Arthur Kershaw Funeral Services. Stay informed about our services and community involvement.";
$page_keywords = "funeral news Sale, Arthur Kershaw updates, funeral services news Manchester";

// Include header
require_once 'includes/header.php';
?>

<style>
.blog-hero {
    background: var(--primary-color);
    color: white;
    padding: 60px 0;
    margin-bottom: 60px;
}

.blog-hero h1 {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.blog-hero p {
    font-size: 1.1rem;
    opacity: 0.95;
}

.blog-posts-container {
    min-height: 500px;
    margin-bottom: 60px;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin: 60px 0;
}

.pagination-link,
.pagination-current {
    padding: 10px 15px;
    border: 1px solid #ddd;
    text-decoration: none;
    color: var(--text-dark);
    border-radius: 4px;
    transition: all 0.3s ease;
}

.pagination-link:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.pagination-current {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.pagination-disabled {
    padding: 10px 15px;
    border: 1px solid #eee;
    color: #ccc;
    border-radius: 4px;
    cursor: not-allowed;
}

.pagination-prev,
.pagination-next {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.no-posts {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-muted);
}

.no-posts i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}
</style>

<!-- Blog Hero Section -->
<section class="blog-hero">
    <div class="container">
        <h1>News & Updates</h1>
        <p>Stay informed with the latest news from Arthur Kershaw Funeral Services</p>
    </div>
</section>

<!-- Blog Posts -->
<div class="container blog-posts-container">
    <?php if ($debug_pagination): ?>
    <div style="background: #f0f0f0; padding: 20px; margin-bottom: 20px; border-radius: 5px;">
        <h4>Debug Info:</h4>
        <p>Total Posts: <?php echo $total_posts; ?></p>
        <p>Posts Per Page: <?php echo $posts_per_page; ?></p>
        <p>Total Pages: <?php echo $total_pages; ?></p>
        <p>Current Page: <?php echo $page_num; ?></p>
        <p>Posts on this page: <?php echo count($formatted_posts); ?></p>
    </div>
    <?php endif; ?>
    <?php if (!empty($formatted_posts)): ?>
        <div class="blog-posts-grid">
            <?php foreach ($formatted_posts as $post): ?>
            <article class="blog-card">
                <?php if ($post['featured_image']): ?>
                <div class="blog-card-image">
                    <img src="<?php echo htmlspecialchars($post['featured_image']); ?>"
                         alt="<?php echo htmlspecialchars($post['title']); ?>">
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
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav class="pagination" aria-label="Blog pagination">
            <!-- Previous button -->
            <?php if ($page_num > 1): ?>
                <a href="<?php echo BASE_URL; ?>/blog.php?page=<?php echo $page_num - 1; ?>"
                   class="pagination-link pagination-prev">
                    <i class="fas fa-chevron-left"></i> Previous
                </a>
            <?php else: ?>
                <span class="pagination-disabled pagination-prev">
                    <i class="fas fa-chevron-left"></i> Previous
                </span>
            <?php endif; ?>

            <!-- Page numbers -->
            <?php
            // Show page numbers with ellipsis for many pages
            $range = 2; // Pages to show on each side of current
            $start = max(1, $page_num - $range);
            $end = min($total_pages, $page_num + $range);

            if ($start > 1): ?>
                <a href="<?php echo BASE_URL; ?>/blog.php?page=1" class="pagination-link">1</a>
                <?php if ($start > 2): ?>
                    <span class="pagination-disabled">...</span>
                <?php endif; ?>
            <?php endif; ?>

            <?php for ($i = $start; $i <= $end; $i++): ?>
                <?php if ($i == $page_num): ?>
                    <span class="pagination-current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/blog.php?page=<?php echo $i; ?>"
                       class="pagination-link"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($end < $total_pages): ?>
                <?php if ($end < $total_pages - 1): ?>
                    <span class="pagination-disabled">...</span>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>/blog.php?page=<?php echo $total_pages; ?>"
                   class="pagination-link"><?php echo $total_pages; ?></a>
            <?php endif; ?>

            <!-- Next button -->
            <?php if ($page_num < $total_pages): ?>
                <a href="<?php echo BASE_URL; ?>/blog.php?page=<?php echo $page_num + 1; ?>"
                   class="pagination-link pagination-next">
                    Next <i class="fas fa-chevron-right"></i>
                </a>
            <?php else: ?>
                <span class="pagination-disabled pagination-next">
                    Next <i class="fas fa-chevron-right"></i>
                </span>
            <?php endif; ?>
        </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="no-posts">
            <i class="fas fa-newspaper"></i>
            <h2>No posts found</h2>
            <p>Check back soon for updates.</p>
        </div>
    <?php endif; ?>
</div>

<?php
// Include footer
require_once 'includes/footer.php';
?>