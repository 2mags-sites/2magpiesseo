<?php
require_once 'config.php';
require_once 'functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Content Test - Verify Unique Page Content</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .page-test { border: 2px solid #333; margin: 20px 0; padding: 15px; background: #f5f5f5; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        h2 { color: #2c3e50; }
        .content-preview { background: white; padding: 10px; margin: 10px 0; border-left: 4px solid #3498db; }
    </style>
</head>
<body>
    <h1>🔍 Content Verification Test</h1>
    <p>This page verifies that each PHP page has unique content.</p>

    <?php
    $test_pages = [
        'index' => 'Homepage',
        'family-law' => 'Family Law Service',
        'divorce-attorney' => 'Divorce Attorney Service',
        'commercial-litigation' => 'Commercial Litigation Service',
        'about' => 'About Page'
    ];

    $all_titles = [];
    $all_content = [];

    foreach ($test_pages as $page => $label) {
        echo "<div class='page-test'>";
        echo "<h2>📄 $label ($page.php)</h2>";

        $page_data = get_page_data($page);

        if (empty($page_data)) {
            echo "<p class='error'>❌ ERROR: Could not load data for $page</p>";
        } else {
            // Check meta title
            $title = $page_data['meta']['title'] ?? 'No title';
            echo "<p><strong>Meta Title:</strong> $title</p>";

            // Check for duplicate titles
            if (in_array($title, $all_titles)) {
                echo "<p class='error'>⚠️ WARNING: Duplicate title detected!</p>";
            } else {
                echo "<p class='success'>✅ Unique title</p>";
            }
            $all_titles[] = $title;

            // Check hero content
            if (isset($page_data['content']['hero'])) {
                $hero_title = $page_data['content']['hero']['title'];
                $hero_subtitle = $page_data['content']['hero']['subtitle'];
                echo "<div class='content-preview'>";
                echo "<strong>Hero Title:</strong> $hero_title<br>";
                echo "<strong>Hero Subtitle:</strong> $hero_subtitle";
                echo "</div>";

                // Check for duplicate hero titles
                if (in_array($hero_title, $all_content)) {
                    echo "<p class='error'>⚠️ WARNING: Duplicate hero title!</p>";
                } else {
                    echo "<p class='success'>✅ Unique hero content</p>";
                }
                $all_content[] = $hero_title;
            }

            // Count unique content sections
            $sections = isset($page_data['content']) ? count($page_data['content']) : 0;
            $faqs = isset($page_data['faq']) ? count($page_data['faq']) : 0;

            echo "<p><strong>Content Sections:</strong> $sections</p>";
            echo "<p><strong>FAQ Items:</strong> $faqs</p>";

            // Show first service if available
            if (isset($page_data['content']['services'][0])) {
                $service = $page_data['content']['services'][0];
                echo "<div class='content-preview'>";
                echo "<strong>First Service:</strong> " . $service['title'] . "<br>";
                echo "<em>" . substr($service['description'], 0, 100) . "...</em>";
                echo "</div>";
            }
        }

        echo "</div>";
    }
    ?>

    <div style="background: #e8f4f8; padding: 20px; margin: 20px 0; border-radius: 5px;">
        <h2>📊 Summary</h2>
        <p><strong>Total Pages Tested:</strong> <?php echo count($test_pages); ?></p>
        <p><strong>Unique Titles Found:</strong> <?php echo count(array_unique($all_titles)); ?></p>
        <p><strong>Unique Hero Sections:</strong> <?php echo count(array_unique($all_content)); ?></p>

        <?php if (count(array_unique($all_titles)) == count($test_pages)): ?>
            <p class="success">✅ All pages have unique content!</p>
        <?php else: ?>
            <p class="error">❌ Some pages have duplicate content!</p>
        <?php endif; ?>
    </div>

    <div style="background: #fff3cd; padding: 15px; margin: 20px 0; border: 1px solid #ffc107;">
        <h3>⚠️ Important: How to Access the Website</h3>
        <p>Make sure you're accessing the pages through the PHP server:</p>
        <ol>
            <li>Start the PHP server: <code>php -S localhost:8000</code></li>
            <li>Access pages via: <code>http://localhost:8000/family-law.php</code></li>
            <li>Do NOT open files directly in browser (file:// protocol won't work)</li>
        </ol>
        <p><strong>Current URL:</strong> <code><?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?></code></p>
    </div>
</body>
</html>