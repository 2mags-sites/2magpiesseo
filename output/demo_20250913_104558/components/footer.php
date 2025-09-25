<?php
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
</footer>