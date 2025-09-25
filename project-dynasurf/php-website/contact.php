<?php
require_once 'includes/admin-config.php';
$content = loadContent('contact');
$page_title = $content['meta']['title'];
$page_description = $content['meta']['description'];
$page_keywords = $content['meta']['keywords'];

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get honeypot field name from environment
require_once 'includes/env-loader.php';
$honeypot_field = EnvLoader::get('HONEYPOT_FIELD', 'website');

require_once 'includes/header.php';
?>

    <!-- Service Hero Section -->
    <section class="service-hero">
        <div class="container">
            <h1><?php echo editable($content['hero']['title'] ?? 'Contact Dynasurf', 'hero.title'); ?></h1>
            <p class="lead"><?php echo editable($content['hero']['subtitle'] ?? 'Get in touch for expert engineering solutions and rapid quotes', 'hero.subtitle'); ?></p>
            <nav class="breadcrumb">
                <ol>
                    <?php foreach ($content['hero']['breadcrumbs'] as $crumb): ?>
                        <li>
                            <?php if (!empty($crumb['url'])): ?>
                                <a href="<?php echo htmlspecialchars($crumb['url']); ?>"><?php echo editable($crumb['name'], 'hero.breadcrumbs.' . array_search($crumb, $content['hero']['breadcrumbs']) . '.name'); ?></a>
                            <?php else: ?>
                                <?php echo editable($crumb['name'], 'hero.breadcrumbs.' . array_search($crumb, $content['hero']['breadcrumbs']) . '.name'); ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
        </div>
    </section>

    <main class="section" style="padding-top: 3rem;">
        <div class="container">
            <!-- Contact Form with Contact Methods -->
            <div class="content-with-image">
                <div class="story-box">
                    <h3><?php echo editable($content['form']['title'] ?? 'Request a Quote', 'form.title'); ?></h3>
                    <p><?php echo editable($content['form']['description'] ?? 'Complete the form below with details of your requirements. Our technical team will respond with a comprehensive quote and advice on the best approach for your project.', 'form.description'); ?></p>

                    <!-- Success/Error Message Display -->
                    <div id="form-message" style="display: none; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;"></div>

                    <form id="contact-form" class="contact-form" action="/contact-handler.php" method="POST" style="background: rgba(26, 29, 33, 0.4); padding: 2rem; border-radius: 8px; margin-top: 1.5rem; border: 1px solid rgba(201, 209, 217, 0.1);">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                        <!-- Honeypot field for spam protection -->
                        <input type="text" name="<?php echo htmlspecialchars($honeypot_field); ?>" style="display: none;" tabindex="-1" autocomplete="off">

                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: var(--aluminum);">Name *</label>
                            <input type="text" id="name" name="name" required style="width: 100%; padding: 0.75rem; background: rgba(26, 29, 33, 0.6); border: 1px solid rgba(201, 209, 217, 0.2); border-radius: 4px; font-size: 1rem; color: var(--aluminum);">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                            <div class="form-group">
                                <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: var(--aluminum);">Email *</label>
                                <input type="email" id="email" name="email" required style="width: 100%; padding: 0.75rem; background: rgba(26, 29, 33, 0.6); border: 1px solid rgba(201, 209, 217, 0.2); border-radius: 4px; font-size: 1rem; color: var(--aluminum);">
                            </div>
                            <div class="form-group">
                                <label for="phone" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: var(--aluminum);">Phone</label>
                                <input type="tel" id="phone" name="phone" style="width: 100%; padding: 0.75rem; background: rgba(26, 29, 33, 0.6); border: 1px solid rgba(201, 209, 217, 0.2); border-radius: 4px; font-size: 1rem; color: var(--aluminum);">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label for="message" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: var(--aluminum);">Message *</label>
                            <textarea id="message" name="message" rows="6" required placeholder="Please provide details about your requirements including component types, dimensions, quantities, timescales, and any specific challenges..." style="width: 100%; padding: 0.75rem; background: rgba(26, 29, 33, 0.6); border: 1px solid rgba(201, 209, 217, 0.2); border-radius: 4px; font-size: 1rem; resize: vertical; color: var(--aluminum);"></textarea>
                        </div>

                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: flex-start; cursor: pointer;">
                                <input type="checkbox" name="privacy_consent" required style="margin-right: 0.5rem; margin-top: 0.25rem;">
                                <span>I agree to the privacy policy and consent to my data being used to respond to my enquiry *</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-secondary">
                            Send Enquiry
                        </button>

                        <p style="margin-top: 1rem; font-size: 0.9rem; color: var(--text-light);">
                            * Required fields. We typically respond to all enquiries within 2 hours during business hours.
                        </p>
                    </form>
                </div>

                <!-- Contact Methods -->
                <div class="features-list" style="padding-left: 2rem;">
                    <?php if (isset($content['contact_methods']) && is_array($content['contact_methods'])): ?>
                        <?php foreach ($content['contact_methods'] as $index => $method): ?>
                            <div class="feature-item">
                                <div class="feature-icon"><?php echo editable($method['icon'], 'contact_methods.' . $index . '.icon'); ?></div>
                                <div class="feature-text">
                                    <h4><?php echo editable($method['title'], 'contact_methods.' . $index . '.title'); ?></h4>
                                    <p style="font-size: 1.25rem; color: var(--laser-green); margin: 0.5rem 0;"><?php echo editable($method['primary'], 'contact_methods.' . $index . '.primary'); ?></p>
                                    <p><?php echo editable($method['description'], 'contact_methods.' . $index . '.description'); ?></p>
                                    <p><strong><?php echo editable($method['additional'], 'contact_methods.' . $index . '.additional'); ?></strong></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Business Hours & Location -->
            <div class="content-with-image reverse" style="margin-top: 4rem;">
                <div class="story-box">
                    <h3><?php echo editable($content['business_info']['title'] ?? 'Business Hours & Location', 'business_info.title'); ?></h3>
                    <div class="business-info" style="margin-top: 1.5rem;">
                        <div class="hours-section" style="margin-bottom: 2rem;">
                            <h4><?php echo editable($content['business_info']['hours']['title'] ?? 'Workshop Hours', 'business_info.hours.title'); ?></h4>
                            <div style="display: grid; grid-template-columns: auto 1fr; gap: 1rem; margin: 1rem 0;">
                                <?php if (isset($content['business_info']['hours']['schedule']) && is_array($content['business_info']['hours']['schedule'])): ?>
                                    <?php foreach ($content['business_info']['hours']['schedule'] as $index => $schedule): ?>
                                        <strong><?php echo editable($schedule['days'], 'business_info.hours.schedule.' . $index . '.days'); ?>:</strong>
                                        <span><?php echo editable($schedule['hours'], 'business_info.hours.schedule.' . $index . '.hours'); ?></span>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="address-section" style="margin-bottom: 2rem;">
                            <h4><?php echo editable($content['business_info']['address']['title'] ?? 'Address', 'business_info.address.title'); ?></h4>
                            <div style="margin: 1rem 0;">
                                <strong><?php echo editable($content['business_info']['address']['company'] ?? 'Dynasurf UK Ltd', 'business_info.address.company'); ?></strong><br>
                                <?php echo editable($content['business_info']['address']['street'] ?? 'Industrial Estate', 'business_info.address.street'); ?><br>
                                <?php echo editable($content['business_info']['address']['city'] ?? 'Sandbach', 'business_info.address.city'); ?><br>
                                <?php echo editable($content['business_info']['address']['county'] ?? 'Cheshire', 'business_info.address.county'); ?> <?php echo editable($content['business_info']['address']['postcode'] ?? 'CW11 3XX', 'business_info.address.postcode'); ?><br>
                                <?php echo editable($content['business_info']['address']['country'] ?? 'United Kingdom', 'business_info.address.country'); ?>
                            </div>
                        </div>

                        <div class="directions-section">
                            <h4><?php echo editable($content['business_info']['directions']['title'] ?? 'Directions', 'business_info.directions.title'); ?></h4>
                            <ul style="margin: 1rem 0; padding-left: 1.5rem;">
                                <?php if (isset($content['business_info']['directions']['points']) && is_array($content['business_info']['directions']['points'])): ?>
                                    <?php foreach ($content['business_info']['directions']['points'] as $index => $point): ?>
                                        <li><?php echo editable($point, 'business_info.directions.points.' . $index); ?></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="content-image">
                    <?php echo editableImage($content['business_info']['map_image'] ?? '', 'business_info.map_image', 'Interactive Map Placeholder', 'Location Map'); ?>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="cta-section" style="margin-top: 4rem;">
                <h2><?php echo editable($content['emergency_cta']['title'] ?? 'Emergency? Call Now!', 'emergency_cta.title'); ?></h2>
                <p><?php echo editable($content['emergency_cta']['subtitle'] ?? '24/7 emergency response line for urgent hydraulic failures', 'emergency_cta.subtitle'); ?></p>
                <div class="cta-phones">
                    <div class="phone-item">
                        📞 <?php echo editable($content['emergency_cta']['phone'] ?? '01270 763032', 'emergency_cta.phone'); ?>
                    </div>
                </div>
                <p>
                    <?php echo editable($content['emergency_cta']['message'] ?? 'Don\'t let equipment failure cost you thousands in downtime', 'emergency_cta.message'); ?>
                </p>
            </div>
        </div>
    </main>

    <script>
        function toggleFAQ(element) {
            const answer = element.nextElementSibling;
            const isActive = element.classList.contains('active');

            // Close all FAQs
            document.querySelectorAll('.faq-question').forEach(q => {
                q.classList.remove('active');
                q.nextElementSibling.classList.remove('active');
            });

            // Open clicked FAQ if it wasn't active
            if (!isActive) {
                element.classList.add('active');
                answer.classList.add('active');
            }
        }

        // Form submission handler
        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const messageDiv = document.getElementById('form-message');
            const submitBtn = this.querySelector('button[type="submit"]');

            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';

            fetch('/contact-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.style.display = 'block';

                if (data.success) {
                    messageDiv.style.backgroundColor = '#d4edda';
                    messageDiv.style.color = '#155724';
                    messageDiv.style.border = '1px solid #c3e6cb';
                    messageDiv.textContent = data.message;

                    // Reset form
                    this.reset();
                } else {
                    messageDiv.style.backgroundColor = '#f8d7da';
                    messageDiv.style.color = '#721c24';
                    messageDiv.style.border = '1px solid #f5c6cb';
                    messageDiv.textContent = data.message;
                }

                // Scroll to message
                messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            })
            .catch(error => {
                messageDiv.style.display = 'block';
                messageDiv.style.backgroundColor = '#f8d7da';
                messageDiv.style.color = '#721c24';
                messageDiv.style.border = '1px solid #f5c6cb';
                messageDiv.textContent = 'An error occurred. Please try again or call us directly.';
                console.error('Error:', error);
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Send Enquiry';
            });
        });
    </script>

<?php require_once 'includes/footer.php'; ?>