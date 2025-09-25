<?php
// Test what the commercial-litigation.php file sees

// First set the variables as router would
$_SERVER['SCRIPT_NAME'] = '/commercial-litigation.php';
$_SERVER['PHP_SELF'] = '/commercial-litigation.php';

echo "<h1>Page Detection Test</h1>";
echo "<h2>Server Variables Set By Router:</h2>";
echo "<pre>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "</pre>";

echo "<h2>What commercial-litigation.php Would Detect:</h2>";
echo "<pre>";
$page_name = basename($_SERVER['SCRIPT_NAME'], '.php');
echo "Detected page name: $page_name\n";

if (empty($page_name)) {
    $page_name = 'commercial-litigation';
    echo "Empty, using fallback: $page_name\n";
}
echo "</pre>";

echo "<h2>Loading the JSON data:</h2>";
require_once 'functions.php';
$page_data = get_page_data($page_name);
echo "<pre>";
echo "Page title: " . ($page_data['meta']['title'] ?? 'No title') . "\n";
echo "Hero title: " . ($page_data['content']['hero']['title'] ?? 'No hero title') . "\n";
echo "</pre>";
?>