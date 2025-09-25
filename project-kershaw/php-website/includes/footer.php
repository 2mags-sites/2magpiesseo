    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>About Us</h4>
                    <p>Family-owned funeral directors serving Sale and Greater Manchester since 1892. Available 24/7 for compassionate, professional funeral services.</p>
                </div>
                <div class="footer-section">
                    <h4>Our Services</h4>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>/service-traditional-burial.php">Traditional Burial</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/service-cremation.php">Cremation Service</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/service-direct-cremation.php">Direct Cremation</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/pre-paid-plans.php">Pre-Paid Plans</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>/about.php">About Us</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/funeral-costs.php">Funeral Costs</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/arranging-a-funeral.php">Arranging a Funeral</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/contact.php">Contact Us</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/sitemap.php">Sitemap</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact Us</h4>
                    <p><strong>24/7 Phone:</strong> 0161 969 2288</p>
                    <p><strong>Email:</strong> info@arthurkershawfunerals.com</p>
                    <p><strong>Address:</strong><br>
                    168-170 Washway Road<br>
                    Sale, Cheshire<br>
                    M33 6RH</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Arthur Kershaw Funeral Services Ltd. All rights reserved. | <a href="/privacy-policy.php" style="color: rgba(255,255,255,0.7);">Privacy Policy</a></p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu JavaScript -->
    <script>
        // Mobile Menu Toggle - ensure DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
            const navMenu = document.querySelector('.nav-menu');
            const navItems = document.querySelectorAll('.nav-item');

            if (mobileMenuToggle) {
                // Toggle mobile menu
                mobileMenuToggle.addEventListener('click', () => {
                    navMenu.classList.toggle('active');
                });
            }

            // Handle dropdown menus on mobile
            navItems.forEach(item => {
                const link = item.querySelector('.nav-link');
                const dropdown = item.querySelector('.dropdown');

                if (dropdown) {
                    link.addEventListener('click', (e) => {
                        // On mobile, prevent default and toggle dropdown
                        if (window.innerWidth <= 768) {
                            e.preventDefault();
                            item.classList.toggle('active');
                        }
                    });
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.navbar')) {
                    navMenu.classList.remove('active');
                    navItems.forEach(item => item.classList.remove('active'));
                }
            });
        });
    </script>

    <!-- FAQ Toggle JavaScript -->
    <script>
        // FAQ Toggle functionality - ensure DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const faqQuestions = document.querySelectorAll('.faq-question');

            console.log('FAQ script loaded. Found ' + faqQuestions.length + ' FAQ questions');

            faqQuestions.forEach(question => {
                question.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const faqItem = this.parentElement;
                    console.log('FAQ clicked', faqItem);

                    // Close other FAQ items if you want accordion behavior
                    // Comment out these lines if you want multiple FAQs open at once
                    const allItems = document.querySelectorAll('.faq-item');
                    allItems.forEach(item => {
                        if (item !== faqItem) {
                            item.classList.remove('active');
                        }
                    });

                    // Toggle the clicked item
                    faqItem.classList.toggle('active');
                });
            });
        });
    </script>

    <!-- Contact Form AJAX Submission -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contactForm = document.getElementById('contact-form');

            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;
                    const messageDiv = document.getElementById('form-message');

                    // Disable button and show loading
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

                    // Send AJAX request
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        // Check if response contains success or error
                        if (data.includes('success=1')) {
                            // Success
                            messageDiv.style.display = 'block';
                            messageDiv.className = 'alert alert-success';
                            messageDiv.style.cssText = 'background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;';
                            messageDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + (<?php echo json_encode(EnvLoader::get('FORM_SUCCESS_MESSAGE', 'Thank you! Your message has been sent successfully.')); ?>);

                            // Clear form
                            contactForm.reset();

                            // Scroll to message
                            messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        } else if (data.includes('error=1')) {
                            // Error
                            messageDiv.style.display = 'block';
                            messageDiv.className = 'alert alert-danger';
                            messageDiv.style.cssText = 'background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;';
                            messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + (<?php echo json_encode(EnvLoader::get('FORM_ERROR_MESSAGE', 'Sorry, there was an error. Please try again.')); ?>);
                        } else {
                            // Parse specific error from response
                            let errorMsg = 'An error occurred. Please try again.';
                            if (data.includes('Too many submissions')) {
                                errorMsg = 'Too many submissions. Please try again later.';
                            } else if (data.includes('Security validation failed')) {
                                errorMsg = 'Security validation failed. Please refresh the page and try again.';
                            } else if (data.includes('Spam detected')) {
                                errorMsg = 'Your submission was flagged as spam. Please contact us directly.';
                            }

                            messageDiv.style.display = 'block';
                            messageDiv.className = 'alert alert-danger';
                            messageDiv.style.cssText = 'background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;';
                            messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + errorMsg;
                        }
                    })
                    .catch(error => {
                        messageDiv.style.display = 'block';
                        messageDiv.className = 'alert alert-danger';
                        messageDiv.style.cssText = 'background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;';
                        messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Connection error. Please check your internet and try again.';
                    })
                    .finally(() => {
                        // Re-enable button
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;
                    });
                });
            }
        });
    </script>

    <?php if(isset($page_scripts)) echo $page_scripts; ?>
</body>
</html>