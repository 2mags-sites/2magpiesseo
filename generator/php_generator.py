"""
PHP Generator - Creates complete PHP website with HTML5 Boilerplate templates
"""

import os
import json
import logging
from pathlib import Path
from typing import Dict, List, Optional
import shutil

logger = logging.getLogger(__name__)


class PHPGenerator:
    """Generates complete PHP website from JSON data"""

    def __init__(self):
        self.template_dir = Path(__file__).parent.parent / 'templates'
        self.generated_files = []

    def generate(self, data: Dict, output_dir: Path, business_type: str = 'general_service') -> Path:
        """
        Generate complete PHP website

        Args:
            data: JSON data (universal and pages)
            output_dir: Output directory
            business_type: Type of business

        Returns:
            Path to generated website
        """
        logger.info(f"Generating PHP website in: {output_dir}")

        # Create directory structure
        self._create_directory_structure(output_dir)

        # Copy static assets
        self._copy_static_assets(output_dir)

        # Generate PHP core files
        self._generate_core_files(output_dir)

        # Generate PHP page files
        self._generate_page_files(output_dir, data)

        # Generate component files
        self._generate_component_files(output_dir, data)

        # Generate helper files
        self._generate_helper_files(output_dir)

        # Copy JSON data files
        self._copy_data_files(output_dir, data)

        logger.info(f"Generated {len(self.generated_files)} PHP files")
        return output_dir

    def _create_directory_structure(self, output_dir: Path):
        """Create required directory structure"""
        directories = [
            'includes',
            'components',
            'handlers',
            'assets/css',
            'assets/js',
            'assets/images',
            'data/universal',
            'data/pages'
        ]

        for directory in directories:
            (output_dir / directory).mkdir(parents=True, exist_ok=True)

    def _copy_static_assets(self, output_dir: Path):
        """Copy or generate static CSS and JS files"""

        # Generate CSS based on HTML5 Boilerplate
        css_content = self._generate_css()
        css_file = output_dir / 'assets' / 'css' / 'main.css'
        css_file.write_text(css_content, encoding='utf-8')
        self.generated_files.append(css_file)

        # Generate normalize.css
        normalize_css = self._generate_normalize_css()
        normalize_file = output_dir / 'assets' / 'css' / 'normalize.css'
        normalize_file.write_text(normalize_css, encoding='utf-8')
        self.generated_files.append(normalize_file)

        # Generate main JavaScript
        js_content = self._generate_javascript()
        js_file = output_dir / 'assets' / 'js' / 'main.js'
        js_file.write_text(js_content, encoding='utf-8')
        self.generated_files.append(js_file)

    def _generate_core_files(self, output_dir: Path):
        """Generate core PHP files"""

        # Generate config.php
        config_content = '''<?php
/**
 * Configuration File
 * PHP Website Builder - Configuration
 */

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Site settings
define('SITE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]");
define('SITE_ROOT', dirname(__FILE__));
define('DATA_DIR', SITE_ROOT . '/data');

// Email settings for contact form
define('CONTACT_EMAIL', 'info@example.com'); // Change this to your email
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 25);

// Performance settings
define('ENABLE_CACHE', true);
define('CACHE_TIME', 3600); // 1 hour

// SEO settings
define('ENABLE_SITEMAP', true);
define('ENABLE_ROBOTS_TXT', true);

// Security settings
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
?>'''
        (output_dir / 'config.php').write_text(config_content, encoding='utf-8')
        self.generated_files.append(output_dir / 'config.php')

        # Generate functions.php
        functions_content = r'''<?php
/**
 * Helper Functions
 * PHP Website Builder - Helper Functions
 */

/**
 * Load JSON data file
 */
function load_json_data($filename) {
    $filepath = DATA_DIR . '/' . $filename;
    if (file_exists($filepath)) {
        $json = file_get_contents($filepath);
        return json_decode($json, true);
    }
    return [];
}

/**
 * Get universal data
 */
function get_universal_data($section = null) {
    static $universal_data = null;

    if ($universal_data === null) {
        $universal_data = [
            'business' => load_json_data('universal/business.json'),
            'navigation' => load_json_data('universal/navigation.json'),
            'contact' => load_json_data('universal/contact.json'),
            'team' => load_json_data('universal/team.json'),
            'social' => load_json_data('universal/social.json')
        ];
    }

    if ($section) {
        return isset($universal_data[$section]) ? $universal_data[$section] : [];
    }

    return $universal_data;
}

/**
 * Get page data
 */
function get_page_data($page_name) {
    return load_json_data('pages/' . $page_name . '.json');
}

/**
 * Escape HTML output
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Get proper URL for links (adds .php for local development)
 */
function get_url($path) {
    // Check if we're running on PHP's built-in server
    $is_builtin_server = php_sapi_name() === 'cli-server';

    // If it's the built-in server and path doesn't have an extension, add .php
    if ($is_builtin_server && !empty($path) && $path !== '/') {
        // Remove leading slash if present
        $path = ltrim($path, '/');

        // Check if it already has an extension
        if (!preg_match('/\.\w+$/', $path)) {
            // Special handling for index/home
            if ($path === 'index' || $path === '') {
                return '/';
            }
            return '/' . $path . '.php';
        }
    }

    return $path;
}

/**
 * Format phone number
 */
function format_phone($phone) {
    // Remove non-numeric characters
    $phone = preg_replace('/[^0-9+]/', '', $phone);
    return $phone;
}

/**
 * Generate meta tags
 */
function generate_meta_tags($meta_data) {
    $tags = [];

    if (isset($meta_data['title'])) {
        $tags[] = '<title>' . e($meta_data['title']) . '</title>';
    }

    if (isset($meta_data['description'])) {
        $tags[] = '<meta name="description" content="' . e($meta_data['description']) . '">';
    }

    if (isset($meta_data['keywords']) && is_array($meta_data['keywords'])) {
        $keywords = implode(', ', $meta_data['keywords']);
        $tags[] = '<meta name="keywords" content="' . e($keywords) . '">';
    }

    if (isset($meta_data['canonical_url'])) {
        $tags[] = '<link rel="canonical" href="' . SITE_URL . e($meta_data['canonical_url']) . '">';
    }

    // Open Graph tags
    if (isset($meta_data['title'])) {
        $tags[] = '<meta property="og:title" content="' . e($meta_data['title']) . '">';
    }

    if (isset($meta_data['description'])) {
        $tags[] = '<meta property="og:description" content="' . e($meta_data['description']) . '">';
    }

    $tags[] = '<meta property="og:type" content="website">';
    $tags[] = '<meta property="og:url" content="' . SITE_URL . '">';

    return implode("\n    ", $tags);
}

/**
 * Generate schema markup
 */
function generate_schema($business_data) {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "LocalBusiness",
        "name" => $business_data['name'] ?? '',
        "description" => $business_data['tagline'] ?? '',
        "telephone" => $business_data['contact']['phone'] ?? '',
        "email" => $business_data['contact']['email'] ?? '',
        "address" => [
            "@type" => "PostalAddress",
            "streetAddress" => $business_data['contact']['address']['street'] ?? '',
            "addressLocality" => $business_data['contact']['address']['city'] ?? '',
            "addressRegion" => $business_data['contact']['address']['region'] ?? '',
            "postalCode" => $business_data['contact']['address']['postal_code'] ?? ''
        ]
    ];

    return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

/**
 * Check if current page is active
 */
function is_active_page($page_name) {
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
    return $current_page === $page_name;
}
?>'''
        (output_dir / 'functions.php').write_text(functions_content, encoding='utf-8')
        self.generated_files.append(output_dir / 'functions.php')

    def _generate_page_files(self, output_dir: Path, data: Dict):
        """Generate individual PHP page files"""

        # Template for all pages
        page_template = '''<?php
require_once 'config.php';
require_once 'functions.php';

// Auto-detect page name from script filename
$page_name = basename($_SERVER['SCRIPT_NAME'], '.php');
if (empty($page_name)) $page_name = '{page_name}';

// Load page data
$page_data = get_page_data($page_name);
$business_data = get_universal_data('business');
$navigation_data = get_universal_data('navigation');
$contact_data = get_universal_data('contact');

// Get meta data
$meta = isset($page_data['meta']) ? $page_data['meta'] : [];
$content = isset($page_data['content']) ? $page_data['content'] : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo generate_meta_tags($meta); ?>

    <!-- HTML5 Boilerplate CSS -->
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <!-- Schema.org markup -->
    <?php echo generate_schema($business_data); ?>

    <!-- Additional meta tags -->
    <meta name="theme-color" content="#fafafa">
    <meta name="robots" content="index, follow">
    <meta name="author" content="<?php echo e($business_data['name']); ?>">
</head>
<body>
    <?php include 'components/header.php'; ?>

    <main role="main">
        {page_content}
    </main>

    <?php include 'components/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>
</html>'''

        # Generate index.php (homepage)
        homepage_content = '''
        <?php if (isset($content['hero'])): ?>
        <section class="hero">
            <div class="container">
                <h1><?php echo e($content['hero']['title']); ?></h1>
                <p class="hero-subtitle"><?php echo e($content['hero']['subtitle']); ?></p>
                <div class="hero-buttons">
                    <a href="/contact" class="btn btn-primary"><?php echo e($content['hero']['cta_primary']); ?></a>
                    <a href="/about" class="btn btn-secondary"><?php echo e($content['hero']['cta_secondary']); ?></a>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if (isset($content['features']) && is_array($content['features'])): ?>
        <section class="features">
            <div class="container">
                <h2>Why Choose Us</h2>
                <div class="features-grid">
                    <?php foreach ($content['features'] as $feature): ?>
                    <div class="feature-item">
                        <h3><?php echo e($feature['title']); ?></h3>
                        <p><?php echo e($feature['description']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if (isset($content['services_overview']) && is_array($content['services_overview'])): ?>
        <section class="services-overview">
            <div class="container">
                <h2>Our Services</h2>
                <div class="services-grid">
                    <?php foreach ($content['services_overview'] as $service): ?>
                    <div class="service-card">
                        <h3><?php echo e($service['title']); ?></h3>
                        <p><?php echo e($service['description']); ?></p>
                        <?php if (isset($service['link'])): ?>
                        <a href="<?php echo e($service['link']); ?>" class="btn-link">Learn More →</a>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>'''

        index_php = page_template.replace('{page_name}', 'index').replace('{page_content}', homepage_content)
        (output_dir / 'index.php').write_text(index_php, encoding='utf-8')
        self.generated_files.append(output_dir / 'index.php')

        # Generate service pages
        service_page_content = '''
        <?php if (isset($content['hero'])): ?>
        <section class="hero hero-service">
            <div class="container">
                <h1><?php echo e($content['hero']['title']); ?></h1>
                <p class="hero-subtitle"><?php echo e($content['hero']['subtitle']); ?></p>
                <div class="hero-buttons">
                    <a href="/contact" class="btn btn-primary"><?php echo e($content['hero']['cta_primary']); ?></a>
                    <a href="#learn-more" class="btn btn-secondary"><?php echo e($content['hero']['cta_secondary']); ?></a>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if (isset($content['services']) && is_array($content['services'])): ?>
        <section class="service-details" id="learn-more">
            <div class="container">
                <?php foreach ($content['services'] as $service): ?>
                <div class="service-block">
                    <h2><?php echo e($service['title']); ?></h2>
                    <p><?php echo e($service['description']); ?></p>
                    <?php if (isset($service['features']) && is_array($service['features'])): ?>
                    <ul class="feature-list">
                        <?php foreach ($service['features'] as $feature): ?>
                        <li><?php echo e($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if (isset($content['process']) && isset($content['process']['steps'])): ?>
        <section class="process">
            <div class="container">
                <h2><?php echo e($content['process']['title']); ?></h2>
                <div class="process-steps">
                    <?php foreach ($content['process']['steps'] as $step): ?>
                    <div class="process-step">
                        <div class="step-number"><?php echo e($step['step']); ?></div>
                        <h3><?php echo e($step['title']); ?></h3>
                        <p><?php echo e($step['description']); ?></p>
                        <?php if (isset($step['duration'])): ?>
                        <span class="duration">Duration: <?php echo e($step['duration']); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if (isset($page_data['faq']) && is_array($page_data['faq'])): ?>
        <section class="faq">
            <div class="container">
                <h2>Frequently Asked Questions</h2>
                <div class="faq-list" itemscope itemtype="https://schema.org/FAQPage">
                    <?php foreach ($page_data['faq'] as $item): ?>
                    <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                        <h3 class="faq-question" itemprop="name"><?php echo e($item['question']); ?></h3>
                        <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                            <p itemprop="text"><?php echo e($item['answer']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <section class="cta">
            <div class="container">
                <h2>Ready to Get Started?</h2>
                <p>Contact us today to discuss your needs.</p>
                <a href="/contact" class="btn btn-primary btn-lg">Get In Touch</a>
            </div>
        </section>'''

        # Generate service pages from pages data
        if 'pages' in data:
            for page_name, page_data in data['pages'].items():
                if page_name not in ['index', 'about', 'contact']:  # Skip already generated pages
                    service_php = page_template.replace('{page_name}', page_name).replace('{page_content}', service_page_content)
                    (output_dir / f'{page_name}.php').write_text(service_php, encoding='utf-8')
                    self.generated_files.append(output_dir / f'{page_name}.php')

        # Generate about.php - updated to match AI-generated content structure
        about_content = '''
        <?php if (isset($content['hero'])): ?>
        <section class="hero hero-about">
            <div class="container">
                <h1><?php echo e($content['hero']['title']); ?></h1>
                <p class="hero-subtitle"><?php echo e($content['hero']['subtitle']); ?></p>
                <?php if (isset($content['hero']['description'])): ?>
                <p class="hero-description"><?php echo e($content['hero']['description']); ?></p>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if (isset($content['our_story']) || isset($content['story'])): ?>
        <?php $story = isset($content['our_story']) ? $content['our_story'] : $content['story']; ?>
        <section class="about-content">
            <div class="container">
                <div class="content-block">
                    <h2><?php echo e($story['title']); ?></h2>
                    <p><?php echo e($story['content']); ?></p>

                    <?php if (isset($story['milestones']) && is_array($story['milestones'])): ?>
                    <div class="milestones">
                        <h3>Our Journey</h3>
                        <div class="timeline">
                            <?php foreach ($story['milestones'] as $milestone): ?>
                            <div class="milestone-item">
                                <span class="year"><?php echo e($milestone['year']); ?></span>
                                <span class="event"><?php echo e($milestone['event']); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if (isset($content['mission'])): ?>
                <div class="content-block">
                    <h2><?php echo e($content['mission']['title']); ?></h2>
                    <p><?php echo e(isset($content['mission']['statement']) ? $content['mission']['statement'] : $content['mission']['content']); ?></p>

                    <?php if (isset($content['mission']['core_values']) && is_array($content['mission']['core_values'])): ?>
                    <h3>Our Core Values</h3>
                    <div class="values-grid">
                        <?php foreach ($content['mission']['core_values'] as $value): ?>
                        <div class="value-item">
                            <h3><?php echo e($value['title']); ?></h3>
                            <p><?php echo e($value['description']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if (isset($content['what_sets_us_apart'])): ?>
                <div class="content-block">
                    <h2><?php echo e($content['what_sets_us_apart']['title']); ?></h2>
                    <p><?php echo e($content['what_sets_us_apart']['introduction']); ?></p>

                    <?php if (isset($content['what_sets_us_apart']['differentiators']) && is_array($content['what_sets_us_apart']['differentiators'])): ?>
                    <div class="differentiators-grid">
                        <?php foreach ($content['what_sets_us_apart']['differentiators'] as $diff): ?>
                        <div class="differentiator-item">
                            <h3><?php echo e($diff['title']); ?></h3>
                            <p><?php echo e($diff['description']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if (isset($content['values']) && is_array($content['values'])): ?>
                <div class="content-block">
                    <h2>Our Values</h2>
                    <div class="values-grid">
                        <?php foreach ($content['values'] as $value): ?>
                        <div class="value-item">
                            <h3><?php echo e($value['title']); ?></h3>
                            <p><?php echo e($value['description']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>'''

        about_php = page_template.replace('{page_name}', 'about').replace('{page_content}', about_content)
        (output_dir / 'about.php').write_text(about_php, encoding='utf-8')
        self.generated_files.append(output_dir / 'about.php')

        # Generate contact.php
        contact_content = '''
        <?php if (isset($content['hero'])): ?>
        <section class="hero hero-contact">
            <div class="container">
                <h1><?php echo e($content['hero']['title']); ?></h1>
                <p class="hero-subtitle"><?php echo e($content['hero']['subtitle']); ?></p>
            </div>
        </section>
        <?php endif; ?>

        <section class="contact-section">
            <div class="container">
                <div class="contact-grid">
                    <div class="contact-info">
                        <h2>Get In Touch</h2>
                        <?php if (isset($content['contact_info'])): ?>
                        <div class="info-item">
                            <strong>Phone:</strong>
                            <a href="tel:<?php echo format_phone($content['contact_info']['phone']); ?>">
                                <?php echo e($content['contact_info']['phone']); ?>
                            </a>
                        </div>
                        <div class="info-item">
                            <strong>Email:</strong>
                            <a href="mailto:<?php echo e($content['contact_info']['email']); ?>">
                                <?php echo e($content['contact_info']['email']); ?>
                            </a>
                        </div>
                        <div class="info-item">
                            <strong>Address:</strong>
                            <address><?php echo e($content['contact_info']['address']); ?></address>
                        </div>
                        <div class="info-item">
                            <strong>Hours:</strong>
                            <p><?php echo e($content['contact_info']['hours']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="contact-form">
                        <h2>Send Us a Message</h2>
                        <form action="handlers/contact.php" method="POST" id="contact-form">
                            <div class="form-group">
                                <label for="name">Your Name *</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Your Email *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Your Phone</label>
                                <input type="tel" id="phone" name="phone">
                            </div>
                            <div class="form-group">
                                <label for="message">Your Message *</label>
                                <textarea id="message" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>'''

        contact_php = page_template.replace('{page_name}', 'contact').replace('{page_content}', contact_content)
        (output_dir / 'contact.php').write_text(contact_php, encoding='utf-8')
        self.generated_files.append(output_dir / 'contact.php')

    def _generate_component_files(self, output_dir: Path, data: Dict):
        """Generate PHP component files"""

        # Generate header.php
        header_content = '''<?php
// Header Component
$business_data = get_universal_data('business');
$navigation_data = get_universal_data('navigation');
?>
<header class="site-header" role="banner">
    <div class="container">
        <div class="header-wrapper">
            <div class="site-branding">
                <a href="/" class="site-logo">
                    <?php echo e($business_data['name']); ?>
                </a>
            </div>

            <nav class="main-navigation" role="navigation">
                <button class="menu-toggle" aria-label="Toggle navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <ul class="nav-menu">
                    <?php foreach ($navigation_data['primary_nav'] as $item): ?>
                    <li class="nav-item <?php echo is_active_page(basename($item['url'], '.php')) ? 'active' : ''; ?>">
                        <a href="<?php echo get_url($item['url']); ?>"><?php echo e($item['label']); ?></a>

                        <?php if (isset($item['dropdown']) && is_array($item['dropdown'])): ?>
                        <ul class="dropdown-menu">
                            <?php foreach ($item['dropdown'] as $subitem): ?>
                            <li><a href="<?php echo get_url($subitem['url']); ?>"><?php echo e($subitem['label']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <?php if (isset($navigation_data['cta_button'])): ?>
                <a href="<?php echo get_url($navigation_data['cta_button']['url']); ?>"
                   class="nav-cta <?php echo e($navigation_data['cta_button']['class']); ?>">
                    <?php echo e($navigation_data['cta_button']['label']); ?>
                </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>'''
        (output_dir / 'components' / 'header.php').write_text(header_content, encoding='utf-8')
        self.generated_files.append(output_dir / 'components' / 'header.php')

        # Generate footer.php
        footer_content = '''<?php
// Footer Component
$business_data = get_universal_data('business');
$contact_data = get_universal_data('contact');
$social_data = get_universal_data('social');
?>
<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3><?php echo e($business_data['name']); ?></h3>
                <p><?php echo e($business_data['tagline']); ?></p>
            </div>

            <div class="footer-section">
                <h4>Contact Info</h4>
                <?php if (!empty($contact_data['phone'])): ?>
                <p>Phone: <a href="tel:<?php echo format_phone($contact_data['phone']); ?>"><?php echo e($contact_data['phone']); ?></a></p>
                <?php endif; ?>
                <?php if (!empty($contact_data['email'])): ?>
                <p>Email: <a href="mailto:<?php echo e($contact_data['email']); ?>"><?php echo e($contact_data['email']); ?></a></p>
                <?php endif; ?>
            </div>

            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="/">Home</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/contact">Contact</a></li>
                    <li><a href="/sitemap.xml">Sitemap</a></li>
                </ul>
            </div>

            <?php if (!empty(array_filter($social_data))): ?>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <?php foreach ($social_data as $platform => $url): ?>
                        <?php if (!empty($url)): ?>
                        <a href="<?php echo e($url); ?>" target="_blank" rel="noopener" aria-label="<?php echo ucfirst($platform); ?>">
                            <?php echo ucfirst($platform); ?>
                        </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo e($business_data['name']); ?>. All rights reserved.</p>
        </div>
    </div>
</footer>'''
        (output_dir / 'components' / 'footer.php').write_text(footer_content, encoding='utf-8')
        self.generated_files.append(output_dir / 'components' / 'footer.php')

    def _generate_helper_files(self, output_dir: Path):
        """Generate helper files like contact handler"""

        # Generate contact form handler
        contact_handler = r'''<?php
require_once '../config.php';
require_once '../functions.php';

// Process contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        header('Location: /contact?error=required');
        exit;
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: /contact?error=email');
        exit;
    }

    // Prepare email
    $to = CONTACT_EMAIL;
    $subject = 'New Contact Form Submission';
    $email_message = "Name: $name\n";
    $email_message .= "Email: $email\n";
    $email_message .= "Phone: $phone\n\n";
    $email_message .= "Message:\n$message";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Send email
    if (mail($to, $subject, $email_message, $headers)) {
        header('Location: /contact?success=1');
    } else {
        header('Location: /contact?error=send');
    }
} else {
    header('Location: /contact');
}
?>'''
        (output_dir / 'handlers' / 'contact.php').write_text(contact_handler, encoding='utf-8')
        self.generated_files.append(output_dir / 'handlers' / 'contact.php')

        # Generate 404 page
        error_404 = '''<?php
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
</html>'''
        (output_dir / '404.php').write_text(error_404, encoding='utf-8')
        self.generated_files.append(output_dir / '404.php')

    def _copy_data_files(self, output_dir: Path, data: Dict):
        """Copy JSON data files to output directory"""
        # Data files are already created by JSON generator
        # This method ensures they're in the right place
        pass

    def _generate_css(self) -> str:
        """Generate main CSS file based on HTML5 Boilerplate"""
        return '''/*! HTML5 Boilerplate v8.0.0 | MIT License | https://html5boilerplate.com/ */

/* Main CSS for PHP Website Builder */

:root {
    --primary-color: #2c3e50;
    --secondary-color: #3498db;
    --accent-color: #e74c3c;
    --text-color: #333;
    --light-gray: #f8f9fa;
    --border-color: #dee2e6;
    --max-width: 1200px;
}

* {
    box-sizing: border-box;
}

html {
    font-size: 16px;
    line-height: 1.6;
    -webkit-text-size-adjust: 100%;
}

body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    color: var(--text-color);
    background-color: #fff;
}

/* Container */
.container {
    max-width: var(--max-width);
    margin: 0 auto;
    padding: 0 20px;
}

/* Typography */
h1, h2, h3, h4, h5, h6 {
    margin-top: 0;
    margin-bottom: 1rem;
    font-weight: 600;
    line-height: 1.2;
    color: var(--primary-color);
}

h1 { font-size: 2.5rem; }
h2 { font-size: 2rem; }
h3 { font-size: 1.75rem; }
h4 { font-size: 1.5rem; }
h5 { font-size: 1.25rem; }
h6 { font-size: 1rem; }

p {
    margin-top: 0;
    margin-bottom: 1rem;
}

a {
    color: var(--secondary-color);
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Header */
.site-header {
    background-color: #fff;
    border-bottom: 1px solid var(--border-color);
    padding: 1rem 0;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.site-logo {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary-color);
    text-decoration: none;
}

/* Navigation */
.main-navigation {
    display: flex;
    align-items: center;
}

.menu-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
}

.menu-toggle span {
    display: block;
    width: 25px;
    height: 3px;
    background-color: var(--primary-color);
    margin: 5px 0;
    transition: 0.3s;
}

.nav-menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-item {
    position: relative;
    margin: 0 1rem;
}

.nav-item a {
    color: var(--text-color);
    padding: 0.5rem 0;
    display: block;
}

.nav-item:hover a,
.nav-item.active a {
    color: var(--secondary-color);
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    list-style: none;
    padding: 0.5rem 0;
    min-width: 200px;
    z-index: 1000;
}

.nav-item:hover .dropdown-menu {
    display: block;
}

.dropdown-menu li {
    margin: 0;
}

.dropdown-menu a {
    padding: 0.5rem 1rem;
    display: block;
}

.dropdown-menu a:hover {
    background-color: var(--light-gray);
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    font-weight: 500;
    text-align: center;
    text-decoration: none;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background-color: var(--secondary-color);
    color: #fff;
}

.btn-primary:hover {
    background-color: #2980b9;
    text-decoration: none;
}

.btn-secondary {
    background-color: transparent;
    color: var(--secondary-color);
    border: 2px solid var(--secondary-color);
}

.btn-secondary:hover {
    background-color: var(--secondary-color);
    color: #fff;
    text-decoration: none;
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1.125rem;
}

/* Hero Section */
.hero {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: #fff;
    padding: 5rem 0;
    text-align: center;
}

.hero h1 {
    color: #fff;
    margin-bottom: 1rem;
    font-size: 3rem;
}

.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Sections */
section {
    padding: 4rem 0;
}

section:nth-child(even) {
    background-color: var(--light-gray);
}

/* Features Grid */
.features-grid,
.services-grid,
.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.feature-item,
.service-card,
.value-item {
    text-align: center;
    padding: 2rem;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: transform 0.3s;
}

.feature-item:hover,
.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

/* Process Steps */
.process-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.process-step {
    text-align: center;
    position: relative;
}

.step-number {
    width: 50px;
    height: 50px;
    background-color: var(--secondary-color);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    margin: 0 auto 1rem;
}

/* FAQ Section */
.faq-list {
    margin-top: 2rem;
}

.faq-item {
    background-color: #fff;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 1rem;
    padding: 1.5rem;
}

.faq-question {
    margin-bottom: 0.5rem;
    color: var(--primary-color);
}

.faq-answer {
    color: #666;
}

/* Contact Section */
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-top: 2rem;
}

.info-item {
    margin-bottom: 1rem;
}

.info-item strong {
    color: var(--primary-color);
}

/* Forms */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--primary-color);
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--secondary-color);
}

/* Footer */
.site-footer {
    background-color: var(--primary-color);
    color: #fff;
    padding: 3rem 0 1rem;
    margin-top: 4rem;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.footer-section h3,
.footer-section h4 {
    color: #fff;
    margin-bottom: 1rem;
}

.footer-section p,
.footer-section a {
    color: rgba(255, 255, 255, 0.8);
}

.footer-section a:hover {
    color: #fff;
}

.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 0.5rem;
}

.social-links {
    display: flex;
    gap: 1rem;
}

.footer-bottom {
    text-align: center;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.6);
}

/* CTA Section */
.cta {
    background-color: var(--secondary-color);
    color: #fff;
    text-align: center;
}

.cta h2 {
    color: #fff;
}

/* Responsive Design */
@media (max-width: 768px) {
    .menu-toggle {
        display: block;
    }

    .nav-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background-color: #fff;
        flex-direction: column;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .nav-menu.active {
        display: flex;
    }

    .nav-item {
        margin: 0;
        border-bottom: 1px solid var(--border-color);
    }

    .nav-item a {
        padding: 1rem;
    }

    .dropdown-menu {
        position: static;
        display: block;
        box-shadow: none;
        background-color: var(--light-gray);
    }

    .nav-cta {
        display: none;
    }

    .hero h1 {
        font-size: 2rem;
    }

    .contact-grid {
        grid-template-columns: 1fr;
    }

    .footer-content {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .hero {
        padding: 3rem 0;
    }

    section {
        padding: 2rem 0;
    }

    .features-grid,
    .services-grid,
    .process-steps {
        grid-template-columns: 1fr;
    }
}

/* Print Styles */
@media print {
    .site-header,
    .site-footer,
    .hero-buttons,
    .cta,
    .menu-toggle {
        display: none;
    }

    body {
        font-size: 12pt;
    }

    a {
        color: #000;
        text-decoration: none;
    }

    a[href]:after {
        content: " (" attr(href) ")";
    }
}'''

    def _generate_normalize_css(self) -> str:
        """Generate normalize.css"""
        return '''/*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */

html {
  line-height: 1.15;
  -webkit-text-size-adjust: 100%;
}

body {
  margin: 0;
}

main {
  display: block;
}

h1 {
  font-size: 2em;
  margin: 0.67em 0;
}

hr {
  box-sizing: content-box;
  height: 0;
  overflow: visible;
}

pre {
  font-family: monospace, monospace;
  font-size: 1em;
}

a {
  background-color: transparent;
}

abbr[title] {
  border-bottom: none;
  text-decoration: underline;
  text-decoration: underline dotted;
}

b,
strong {
  font-weight: bolder;
}

code,
kbd,
samp {
  font-family: monospace, monospace;
  font-size: 1em;
}

small {
  font-size: 80%;
}

sub,
sup {
  font-size: 75%;
  line-height: 0;
  position: relative;
  vertical-align: baseline;
}

sub {
  bottom: -0.25em;
}

sup {
  top: -0.5em;
}

img {
  border-style: none;
}

button,
input,
optgroup,
select,
textarea {
  font-family: inherit;
  font-size: 100%;
  line-height: 1.15;
  margin: 0;
}

button,
input {
  overflow: visible;
}

button,
select {
  text-transform: none;
}

button,
[type="button"],
[type="reset"],
[type="submit"] {
  -webkit-appearance: button;
}

button::-moz-focus-inner,
[type="button"]::-moz-focus-inner,
[type="reset"]::-moz-focus-inner,
[type="submit"]::-moz-focus-inner {
  border-style: none;
  padding: 0;
}

button:-moz-focusring,
[type="button"]:-moz-focusring,
[type="reset"]:-moz-focusring,
[type="submit"]:-moz-focusring {
  outline: 1px dotted ButtonText;
}

fieldset {
  padding: 0.35em 0.75em 0.625em;
}

legend {
  box-sizing: border-box;
  color: inherit;
  display: table;
  max-width: 100%;
  padding: 0;
  white-space: normal;
}

progress {
  vertical-align: baseline;
}

textarea {
  overflow: auto;
}

[type="checkbox"],
[type="radio"] {
  box-sizing: border-box;
  padding: 0;
}

[type="number"]::-webkit-inner-spin-button,
[type="number"]::-webkit-outer-spin-button {
  height: auto;
}

[type="search"] {
  -webkit-appearance: textfield;
  outline-offset: -2px;
}

[type="search"]::-webkit-search-decoration {
  -webkit-appearance: none;
}

::-webkit-file-upload-button {
  -webkit-appearance: button;
  font: inherit;
}

details {
  display: block;
}

summary {
  display: list-item;
}

template {
  display: none;
}

[hidden] {
  display: none;
}'''

    def _generate_javascript(self) -> str:
        """Generate main JavaScript file"""
        return r'''/**
 * Main JavaScript File
 * PHP Website Builder
 */

(function() {
    'use strict';

    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.querySelector('.menu-toggle');
        const navMenu = document.querySelector('.nav-menu');

        if (menuToggle && navMenu) {
            menuToggle.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                menuToggle.classList.toggle('active');
            });
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!menuToggle.contains(event.target) && !navMenu.contains(event.target)) {
                navMenu.classList.remove('active');
                menuToggle.classList.remove('active');
            }
        });

        // Smooth scrolling for anchor links
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        anchorLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').slice(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Form validation
        const contactForm = document.getElementById('contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                const name = document.getElementById('name');
                const email = document.getElementById('email');
                const message = document.getElementById('message');

                let valid = true;

                // Validate name
                if (name && name.value.trim() === '') {
                    valid = false;
                    showError(name, 'Please enter your name');
                } else {
                    clearError(name);
                }

                // Validate email
                if (email && !isValidEmail(email.value)) {
                    valid = false;
                    showError(email, 'Please enter a valid email');
                } else {
                    clearError(email);
                }

                // Validate message
                if (message && message.value.trim() === '') {
                    valid = false;
                    showError(message, 'Please enter a message');
                } else {
                    clearError(message);
                }

                if (!valid) {
                    e.preventDefault();
                }
            });
        }

        // FAQ accordion
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(function(item) {
            const question = item.querySelector('.faq-question');
            const answer = item.querySelector('.faq-answer');

            if (question && answer) {
                // Initially hide answer
                answer.style.display = 'none';

                question.style.cursor = 'pointer';
                question.addEventListener('click', function() {
                    // Toggle answer visibility
                    if (answer.style.display === 'none') {
                        answer.style.display = 'block';
                        item.classList.add('active');
                    } else {
                        answer.style.display = 'none';
                        item.classList.remove('active');
                    }
                });
            }
        });

        // Back to top button
        const backToTop = document.createElement('button');
        backToTop.innerHTML = '&uarr;';
        backToTop.className = 'back-to-top';
        backToTop.style.cssText = 'position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; background-color: #3498db; color: white; border: none; border-radius: 50%; font-size: 20px; cursor: pointer; display: none; z-index: 999;';
        document.body.appendChild(backToTop);

        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        });

        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });

    // Helper functions
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        let errorElement = formGroup.querySelector('.error-message');

        if (!errorElement) {
            errorElement = document.createElement('span');
            errorElement.className = 'error-message';
            errorElement.style.cssText = 'color: red; font-size: 0.875rem; margin-top: 0.25rem; display: block;';
            formGroup.appendChild(errorElement);
        }

        errorElement.textContent = message;
        input.style.borderColor = 'red';
    }

    function clearError(input) {
        const formGroup = input.closest('.form-group');
        const errorElement = formGroup.querySelector('.error-message');

        if (errorElement) {
            errorElement.remove();
        }

        input.style.borderColor = '';
    }

    // Performance optimization: Lazy load images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });

        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(function(img) {
            imageObserver.observe(img);
        });
    }

})();'''