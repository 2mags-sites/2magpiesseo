<?php
require_once 'config.php';
require_once 'functions.php';

// Test loading different page data
$pages = ['index', 'family-law', 'divorce-attorney', 'about'];

echo "<h1>Debug: Page Data Loading Test</h1>";

foreach ($pages as $page) {
    echo "<h2>Page: $page</h2>";

    $page_data = get_page_data($page);

    if (empty($page_data)) {
        echo "<p style='color:red'>ERROR: No data loaded for $page</p>";
        echo "<p>Looking for file: " . DATA_DIR . "/pages/$page.json</p>";
        if (file_exists(DATA_DIR . "/pages/$page.json")) {
            echo "<p>File exists but cannot be loaded!</p>";
            $content = file_get_contents(DATA_DIR . "/pages/$page.json");
            echo "<p>File size: " . strlen($content) . " bytes</p>";
            $decoded = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "<p>JSON Error: " . json_last_error_msg() . "</p>";
            }
        } else {
            echo "<p>File does not exist!</p>";
        }
    } else {
        echo "<p style='color:green'>SUCCESS: Data loaded</p>";
        echo "<p>Title: " . ($page_data['meta']['title'] ?? 'No title') . "</p>";
        echo "<p>Content sections: " . (isset($page_data['content']) ? count($page_data['content']) : 0) . "</p>";
        if (isset($page_data['content']['hero']['title'])) {
            echo "<p>Hero Title: " . $page_data['content']['hero']['title'] . "</p>";
        }
    }
    echo "<hr>";
}

// Test universal data
echo "<h2>Universal Data Test</h2>";
$business_data = get_universal_data('business');
if (empty($business_data)) {
    echo "<p style='color:red'>ERROR: No business data loaded</p>";
} else {
    echo "<p style='color:green'>SUCCESS: Business data loaded</p>";
    echo "<p>Business Name: " . ($business_data['name'] ?? 'No name') . "</p>";
}
?>