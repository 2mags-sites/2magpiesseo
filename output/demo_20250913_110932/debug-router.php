<?php
echo "<h1>Router Debug for: commercial-litigation</h1>";
echo "<pre>";
echo "Before router sets SCRIPT_NAME:\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";

// Simulate what router should do
$_SERVER['SCRIPT_NAME'] = '/commercial-litigation.php';
$_SERVER['PHP_SELF'] = '/commercial-litigation.php';

echo "\nAfter router sets SCRIPT_NAME:\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";

$page_name = basename($_SERVER['SCRIPT_NAME'], '.php');
echo "\nDetected page name: " . $page_name . "\n";

echo "\nNow including the actual PHP file:\n";
echo "</pre>";

require 'commercial-litigation.php';
?>