<?php
require_once 'config.php';
require_once 'functions.php';
$business_data = get_universal_data('business');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | <?php echo e($business_data['name']); ?></title>
    <link rel="stylesheet" href="/assets/css/normalize.css">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <?php include 'components/header.php'; ?>

    <main role="main">
        <section class="error-page">
            <div class="container">
                <h1>404 - Page Not Found</h1>
                <p>Sorry, the page you're looking for doesn't exist.</p>
                <a href="/" class="btn btn-primary">Go to Homepage</a>
            </div>
        </section>
    </main>

    <?php include 'components/footer.php'; ?>
</body>
</html>