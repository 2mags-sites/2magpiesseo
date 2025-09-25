<?php
/**
 * Contact Form Handler
 * Processes form submissions and sends via SendGrid
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/env-loader.php';
require_once 'includes/sendgrid-mailer.php';

// Check if this is an AJAX request
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Security: Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($is_ajax) {
        http_response_code(400);
        die('Invalid request');
    }
    header('Location: /contact');
    exit;
}

// CSRF Token Validation
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    if ($is_ajax) {
        http_response_code(403);
        die('Security validation failed');
    }
    $_SESSION['form_error'] = 'Security validation failed. Please try again.';
    header('Location: /contact');
    exit;
}

// Honeypot Check (spam prevention)
if (!empty($_POST['website'])) { // Hidden field that bots fill
    // Silently reject spam but appear successful
    if ($is_ajax) {
        die('success=1'); // Fake success for spammers
    }
    $_SESSION['form_success'] = true;
    header('Location: /contact?success=1');
    exit;
}

// Rate Limiting
$ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
$cache_dir = __DIR__ . '/cache';
if (!is_dir($cache_dir)) {
    mkdir($cache_dir, 0755, true);
}

$rate_limit_file = $cache_dir . '/rate_' . md5($ip) . '.json';
$max_submissions = (int)EnvLoader::get('MAX_SUBMISSIONS_PER_HOUR', 5);

if (file_exists($rate_limit_file)) {
    $submissions = json_decode(file_get_contents($rate_limit_file), true);
    $recent = array_filter($submissions, function($time) {
        return $time > (time() - 3600); // Last hour
    });

    if (count($recent) >= $max_submissions) {
        if ($is_ajax) {
            http_response_code(429);
            die('Too many submissions');
        }
        $_SESSION['form_error'] = 'Too many submissions. Please try again later.';
        header('Location: /contact');
        exit;
    }
    $submissions = $recent;
} else {
    $submissions = [];
}

// reCAPTCHA Validation (if enabled)
$recaptcha_secret = EnvLoader::get('RECAPTCHA_SECRET_KEY');
if ($recaptcha_secret) {
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    if (empty($recaptcha_response)) {
        $_SESSION['form_error'] = 'Please complete the reCAPTCHA verification.';
        header('Location: /contact');
        exit;
    }

    $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    $verify_data = http_build_query([
        'secret' => $recaptcha_secret,
        'response' => $recaptcha_response,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ]);

    $verify_options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $verify_data
        ]
    ];

    $verify_context = stream_context_create($verify_options);
    $verify_response = file_get_contents($verify_url, false, $verify_context);
    $captcha_result = json_decode($verify_response);

    if (!$captcha_result->success || ($captcha_result->score ?? 1) < 0.5) {
        $_SESSION['form_error'] = 'reCAPTCHA verification failed. Please try again.';
        header('Location: /contact');
        exit;
    }
}

// Sanitize and Validate Input
$name = trim(htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'));
$email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
$phone = trim(htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8'));
$message = trim(htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8'));

// Service field (if present)
$service = '';
if (isset($_POST['service'])) {
    $service = trim(htmlspecialchars($_POST['service'], ENT_QUOTES, 'UTF-8'));
}

// Validate Required Fields
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

// Privacy Policy Agreement
if (!isset($_POST['privacy']) || $_POST['privacy'] !== 'on') {
    $errors[] = 'You must agree to the Privacy Policy';
}

// If errors, redirect back
if (!empty($errors)) {
    if ($is_ajax) {
        http_response_code(400);
        die(implode('. ', $errors));
    }
    $_SESSION['form_error'] = implode('. ', $errors);
    $_SESSION['form_data'] = $_POST; // Preserve form data
    header('Location: /contact');
    exit;
}

// Prepare email data
$email_data = [
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'message' => $message,
    'service' => $service,
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
    'timestamp' => date('Y-m-d H:i:s')
];

// Send Email via SendGrid
try {
    $mailer = new SendGridMailer();
    $result = $mailer->sendContactForm($email_data);

    if ($result) {
        // Update rate limit
        $submissions[] = time();
        file_put_contents($rate_limit_file, json_encode($submissions));

        // Set success message
        $_SESSION['form_success'] = EnvLoader::get('FORM_SUCCESS_MESSAGE',
            'Thank you for your message. We\'ll be in touch soon.');

        // Clear form data
        unset($_SESSION['form_data']);

        // Redirect with success
        if ($is_ajax) {
            die('success=1');
        }
        header('Location: /contact?success=1');
        exit;
    } else {
        throw new Exception('Email send failed');
    }
} catch (Exception $e) {
    // Log error (optional)
    error_log('Contact form error: ' . $e->getMessage());

    // Set error message
    $_SESSION['form_error'] = EnvLoader::get('FORM_ERROR_MESSAGE',
        'Sorry, there was an error sending your message. Please try again or call us directly.');

    // Preserve form data
    $_SESSION['form_data'] = $_POST;

    // Redirect with error
    if ($is_ajax) {
        http_response_code(500);
        die('error=1');
    }
    header('Location: /contact?error=1');
    exit;
}
?>