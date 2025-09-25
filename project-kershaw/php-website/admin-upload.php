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

// Check if file was uploaded
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit;
}

// Validate file type
$allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
$file_type = $_FILES['image']['type'];

if (!in_array($file_type, $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WebP allowed.']);
    exit;
}

// Validate file size (max 5MB)
$max_size = 5 * 1024 * 1024; // 5MB
if ($_FILES['image']['size'] > $max_size) {
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is 5MB.']);
    exit;
}

// Create upload directory if it doesn't exist
$upload_dir = __DIR__ . '/assets/images/uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Generate unique filename
$file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
$new_filename = uniqid('img_') . '_' . time() . '.' . $file_extension;
$upload_path = $upload_dir . $new_filename;

// Move uploaded file
if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
    // Get image dimensions
    list($width, $height) = getimagesize($upload_path);

    // Return success with file info
    echo json_encode([
        'success' => true,
        'message' => 'Image uploaded successfully',
        'file' => [
            'url' => '/assets/images/uploads/' . $new_filename,
            'name' => $new_filename,
            'size' => $_FILES['image']['size'],
            'width' => $width,
            'height' => $height
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file']);
}
?>