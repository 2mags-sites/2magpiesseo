<?php
// Include admin config
require_once 'includes/admin-config.php';

// Set JSON response header
header('Content-Type: application/json');

// Check if admin mode is active
if (!ADMIN_MODE) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['page']) || !isset($input['changes'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$page = $input['page'];
$changes = $input['changes'];

// Load current content
$content = loadContent($page);

if (!$content) {
    echo json_encode(['success' => false, 'message' => 'Page not found']);
    exit;
}

// Handle FAQ deletions first
if (isset($changes['faqs.items.DELETE'])) {
    $toDelete = $changes['faqs.items.DELETE'];
    // Sort in reverse order to delete from end first
    rsort($toDelete);
    foreach ($toDelete as $index) {
        array_splice($content['faqs']['items'], $index, 1);
    }
    unset($changes['faqs.items.DELETE']);
}

// Handle new FAQ additions
$newFAQs = [];
foreach ($changes as $path => $value) {
    if (strpos($path, 'faqs.items.') === 0 && strpos($path, '.question') !== false) {
        // Extract index
        preg_match('/faqs\.items\.(\d+)\.question/', $path, $matches);
        if (isset($matches[1])) {
            $index = $matches[1];
            $answerPath = "faqs.items.$index.answer";
            if (isset($changes[$answerPath])) {
                // Check if this is a new FAQ (index doesn't exist yet)
                if (!isset($content['faqs']['items'][$index])) {
                    $newFAQs[] = [
                        'question' => strip_tags($value, '<br>'),
                        'answer' => strip_tags($changes[$answerPath], '<br>')
                    ];
                    unset($changes[$answerPath]);
                }
            }
        }
    }
}

// Add new FAQs to content
foreach ($newFAQs as $newFAQ) {
    $content['faqs']['items'][] = $newFAQ;
}

// Remove the NEW marker if present
unset($changes['faqs.items.NEW']);

// Apply remaining changes to content array
foreach ($changes as $path => $value) {
    // Skip if already handled
    if (strpos($path, 'faqs.items.') === 0 && !isset($content['faqs']['items'][explode('.', $path)[2]])) {
        continue;
    }

    // Parse the path (e.g., "hero.title" or "services.items.0.title")
    $keys = explode('.', $path);
    $current = &$content;

    foreach ($keys as $i => $key) {
        if ($i === count($keys) - 1) {
            // Last key - set the value
            $current[$key] = strip_tags($value, '<br>');
        } else {
            // Navigate deeper into the array
            if (!isset($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }
    }
}

// Save updated content
if (saveContent($page, $content)) {
    echo json_encode(['success' => true, 'message' => 'Changes saved successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save changes']);
}
?>