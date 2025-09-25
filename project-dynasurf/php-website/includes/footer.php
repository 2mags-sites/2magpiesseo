        <footer>
            <div class="container">
                <div class="footer-content">
                    <div class="footer-column">
                        <h4>Core Services</h4>
                        <ul>
                            <li><a href="/hydraulic-ram-repair.php">Hydraulic Ram Repair</a></li>
                            <li><a href="/cylinder-tube-honing.php">Cylinder Tube Honing</a></li>
                            <li><a href="/hydraulic-hose-manufacturing.php">Hydraulic Hose Manufacturing</a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>Legal</h4>
                        <ul>
                            <li><a href="/terms-and-conditions.php">Terms & Conditions</a></li>
                            <li><a href="/privacy-policy.php">Privacy Policy</a></li>
                            <li><a href="/cookie-policy.php">Cookie Policy</a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>Company</h4>
                        <ul>
                            <li><a href="/about.php">About Us</a></li>
                            <li><a href="/news.php">News</a></li>
                            <li><a href="/contact.php">Contact</a></li>
                        </ul>
                    </div>

                    <div class="footer-column footer-contact">
                        <h4>Contact Info</h4>
                        <p>📞 01270 763032</p>
                        <p>✉️ sales@dynasurf.co.uk</p>
                        <p>📍 Millbuck Way, Sandbach</p>
                        <p>⏰ Mon-Thu: 7:15am-4:30pm</p>
                        <p>⏰ Fri: 7:15am-12noon</p>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>&copy; <?php echo date('Y'); ?> Dynasurf (UK) Ltd. All rights reserved. | Precision Engineering Since 1971</p>
                </div>
            </div>
        </footer>
    <!-- Contact Form Modal (for AJAX submissions) -->
    <div id="contact-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Quick Contact</h2>
            <form id="quick-contact-form">
                <div class="form-group">
                    <label for="contact-name">Name *</label>
                    <input type="text" id="contact-name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="contact-email">Email *</label>
                    <input type="email" id="contact-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="contact-phone">Phone</label>
                    <input type="tel" id="contact-phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="contact-message">Message *</label>
                    <textarea id="contact-message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
            <div id="contact-response" style="display: none;"></div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/dyna.js"></script>
    <script>

        // Handle dropdown toggle on desktop
        document.querySelectorAll('.dropdown > a[href="#"]').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault(); // Always prevent default for dropdown toggles
            });
        });

        // Smooth Scrolling for Anchor Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');

                // Skip if it's just "#" or empty, or if it's a dropdown toggle
                if (href === '#' || href === '' || href.length <= 1 ||
                    this.closest('.dropdown') && this.nextElementSibling?.classList.contains('dropdown-menu')) {
                    return;
                }

                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // FAQ Accordion Toggle
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', function() {
                const faqItem = this.closest('.faq-item');
                const answer = faqItem.querySelector('.faq-answer');
                const isActive = faqItem.classList.contains('active');

                // Close all other FAQ items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                    item.querySelector('.faq-answer').style.maxHeight = null;
                });

                // Toggle current item
                if (!isActive) {
                    faqItem.classList.add('active');
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                }
            });
        });

        // Contact Form AJAX Submission
        const contactForm = document.getElementById('quick-contact-form');
        const contactModal = document.getElementById('contact-modal');
        const contactResponse = document.getElementById('contact-response');

        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;

                // Show loading state
                submitButton.textContent = 'Sending...';
                submitButton.disabled = true;
                contactResponse.style.display = 'none';

                fetch('/includes/contact-handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    contactResponse.style.display = 'block';
                    if (data.success) {
                        contactResponse.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                        contactForm.reset();
                        setTimeout(() => {
                            if (contactModal) {
                                contactModal.style.display = 'none';
                            }
                        }, 2000);
                    } else {
                        contactResponse.innerHTML = '<div class="alert alert-error">' + data.message + '</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    contactResponse.style.display = 'block';
                    contactResponse.innerHTML = '<div class="alert alert-error">An error occurred. Please try again.</div>';
                })
                .finally(() => {
                    submitButton.textContent = originalText;
                    submitButton.disabled = false;
                });
            });
        }

        // Contact Modal Controls
        const closeModal = document.querySelector('.modal .close');
        if (closeModal) {
            closeModal.addEventListener('click', function() {
                contactModal.style.display = 'none';
            });
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === contactModal) {
                contactModal.style.display = 'none';
            }
        });

        // Quick contact trigger (can be used by other elements)
        function openQuickContact() {
            if (contactModal) {
                contactModal.style.display = 'block';
            }
        }

        // Add click handlers for quick contact buttons
        document.querySelectorAll('[data-action="quick-contact"]').forEach(button => {
            button.addEventListener('click', openQuickContact);
        });

        // Form Validation Enhancement
        function validateForm(form) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('error');
                    isValid = false;
                } else {
                    field.classList.remove('error');
                }
            });

            // Email validation
            const emailFields = form.querySelectorAll('input[type="email"]');
            emailFields.forEach(field => {
                if (field.value && !isValidEmail(field.value)) {
                    field.classList.add('error');
                    isValid = false;
                }
            });

            return isValid;
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Add real-time validation to form fields
        document.querySelectorAll('input, textarea').forEach(field => {
            field.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }

                if (this.type === 'email' && this.value && !isValidEmail(this.value)) {
                    this.classList.add('error');
                } else if (this.type === 'email') {
                    this.classList.remove('error');
                }
            });

            field.addEventListener('input', function() {
                if (this.classList.contains('error') && this.value.trim()) {
                    this.classList.remove('error');
                }
            });
        });

        // Parallax Effect for Hero and Service Hero Gears Background
        const heroSections = document.querySelectorAll('.hero, .service-hero');
        if (heroSections.length > 0) {
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const rate = scrolled * 0.5; // Parallax speed

                // Apply transform to hero sections only
                heroSections.forEach(section => {
                    section.style.setProperty('--parallax-offset', rate + 'px');
                });
            });
        }

        // Back to Top Button (if exists)
        const backToTopButton = document.querySelector('.back-to-top');
        if (backToTopButton) {
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('visible');
                } else {
                    backToTopButton.classList.remove('visible');
                }
            });

            backToTopButton.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Print functionality
        function printPage() {
            window.print();
        }

        // Page load analytics (if needed)
        document.addEventListener('DOMContentLoaded', function() {
            // Track page load time
            const loadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart;
            console.log('Page load time:', loadTime + 'ms');

            // Initialize tooltips if they exist
            const tooltips = document.querySelectorAll('[data-tooltip]');
            tooltips.forEach(tooltip => {
                tooltip.addEventListener('mouseenter', function() {
                    const tooltipText = this.getAttribute('data-tooltip');
                    const tooltipElement = document.createElement('div');
                    tooltipElement.className = 'tooltip';
                    tooltipElement.textContent = tooltipText;
                    document.body.appendChild(tooltipElement);

                    const rect = this.getBoundingClientRect();
                    tooltipElement.style.left = rect.left + (rect.width / 2) - (tooltipElement.offsetWidth / 2) + 'px';
                    tooltipElement.style.top = rect.top - tooltipElement.offsetHeight - 10 + 'px';
                });

                tooltip.addEventListener('mouseleave', function() {
                    const tooltipElement = document.querySelector('.tooltip');
                    if (tooltipElement) {
                        tooltipElement.remove();
                    }
                });
            });
        });
    </script>

    <?php if (defined('IS_ADMIN') && IS_ADMIN): ?>
    <!-- Admin JavaScript -->
    <script src="/includes/admin-functions.js"></script>
    <script>
        // Additional admin-specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Admin mode active');

            // Add admin indicators
            const adminIndicator = document.createElement('div');
            adminIndicator.innerHTML = '🔧 Admin Mode';
            adminIndicator.style.cssText = `
                position: fixed;
                top: 10px;
                right: 10px;
                background: #ff6b6b;
                color: white;
                padding: 5px 10px;
                border-radius: 3px;
                font-size: 12px;
                z-index: 10000;
                font-family: Arial, sans-serif;
            `;
            document.body.appendChild(adminIndicator);

            // Add admin quick actions
            const adminPanel = document.createElement('div');
            adminPanel.innerHTML = `
                <div style="position: fixed; bottom: 20px; right: 20px; background: #333; color: white; padding: 15px; border-radius: 5px; z-index: 9999; font-family: Arial, sans-serif; font-size: 12px;">
                    <div style="margin-bottom: 10px; font-weight: bold;">Admin Quick Actions</div>
                    <button onclick="window.location.href='/view-logs.php'" style="display: block; width: 100%; margin-bottom: 5px; padding: 5px; background: #007cba; color: white; border: none; border-radius: 3px; cursor: pointer;">View Logs</button>
                    <button onclick="clearCache()" style="display: block; width: 100%; margin-bottom: 5px; padding: 5px; background: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer;">Clear Cache</button>
                    <button onclick="toggleEditMode()" style="display: block; width: 100%; padding: 5px; background: #ffc107; color: black; border: none; border-radius: 3px; cursor: pointer;">Toggle Edit</button>
                </div>
            `;
            document.body.appendChild(adminPanel);
        });

        function clearCache() {
            if (confirm('Clear application cache?')) {
                fetch('/includes/admin-save.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=clear_cache'
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || 'Cache cleared');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error clearing cache');
                });
            }
        }

        function toggleEditMode() {
            document.body.classList.toggle('admin-edit-mode');
            const isEditMode = document.body.classList.contains('admin-edit-mode');

            if (isEditMode) {
                // Add edit indicators to editable elements
                document.querySelectorAll('[data-editable]').forEach(element => {
                    element.style.outline = '2px dashed #007cba';
                    element.style.cursor = 'pointer';
                    element.title = 'Click to edit';
                });
            } else {
                // Remove edit indicators
                document.querySelectorAll('[data-editable]').forEach(element => {
                    element.style.outline = '';
                    element.style.cursor = '';
                    element.title = '';
                });
            }
        }
    </script>
    <?php endif; ?>

</body>
</html>