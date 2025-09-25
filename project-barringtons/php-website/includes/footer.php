    <!-- Contact Section - Appears on every page -->
    <section class="contact-section" id="contact">
        <div class="container">
            <h2>Contact Us</h2>
            <div class="contact-grid">
                <div class="contact-form fade-in">
                    <form id="contactForm" method="POST" action="/contact-handler.php">
                        <!-- Honeypot field for spam protection -->
                        <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">

                        <div class="form-group">
                            <label for="name">Your Name *</label>
                            <input type="text" id="name" name="name" placeholder="Enter your name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="Your contact number">
                        </div>
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" rows="4" placeholder="How can we help you?" required></textarea>
                        </div>
                        <div class="form-group" style="display: flex; align-items: flex-start;">
                            <input type="checkbox" id="privacy_consent" name="privacy_consent" required style="margin: 4px 8px 0 0;">
                            <label for="privacy_consent" style="margin: 0; flex: 1;">I agree to the <a href="privacy-policy.php" target="_blank" style="color: var(--pink);">Privacy Policy</a> *</label>
                        </div>
                        <button type="submit" class="submit-btn">Send Message</button>

                        <div id="formMessage" class="form-message" style="display:none;"></div>
                    </form>
                </div>
                <div class="contact-info fade-in">
                    <h3>Our Locations</h3>
                    <div class="info-item">
                        <strong>Waterloo Office (Main)</strong><br>
                        23 Crosby Rd N, Waterloo, L22 4QF<br>
                        <span class="office-hours">Open 24 hours</span>
                    </div>
                    <div class="info-item">
                        <strong>Formby Office</strong><br>
                        64 Elbow Lane, Formby, L37 4AB<br>
                        <span class="office-hours">By appointment</span>
                    </div>
                    <div class="info-item">
                        <strong>Netherton Office</strong><br>
                        47 Liverpool Road, Netherton, L30 3QA<br>
                        <span class="office-hours">Mon-Fri 9am-5pm</span>
                    </div>
                    <div class="info-item">
                        <strong>24 Hour Support Line</strong><br>
                        <span class="contact-phone"><?php echo $business_info['phone']; ?></span><br>
                        <strong>Email:</strong> <?php echo $business_info['email']; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>Barringtons</h4>
                <p>Family funeral directors serving Liverpool with compassion and dignity for over 100 years.</p>
                <div class="footer-logos">
                    <img src="/assets/images/pers_nafd.png" alt="NAFD Member" />
                    <img src="/assets/images/pers_SAIF.png" alt="SAIF Member" />
                    <img src="/assets/images/pers_agfd-logo.jpg" alt="Association of Green Funeral Directors" />
                    <img src="/assets/images/pers_good-funeral-guide.jpg" alt="Good Funeral Guide" />
                    <img src="/assets/images/pers_green.jpg" alt="Green Funeral Provider" />
                </div>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <a href="/immediate-need">Immediate Need</a><br>
                <a href="/planning-ahead">Plan Ahead</a><br>
                <a href="/services-overview">Our Services</a><br>
                <a href="/helpful-links">Grief Support</a><br>
                <a href="/blog-news">News & Articles</a>
            </div>
            <div class="footer-section">
                <h4>Resources</h4>
                <a href="/etiquette">Funeral Etiquette</a><br>
                <a href="/when-a-death-occurs">What to Do When Someone Dies</a><br>
                <a href="/helpful-links">Grief Support Guide</a><br>
                <a href="/personalisation">Memorial Ideas</a>
            </div>
            <div class="footer-section">
                <h4>Professional Support</h4>
                <p>Member of NAFD<br>
                Member of SAIF<br>
                GriefSteps Partnership</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Barringtons Funerals. All Rights Reserved | <a href="/privacy-policy">Privacy Policy</a> | <a href="/terms-of-service">Terms of Service</a></p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
    // Mobile menu toggle
    function toggleMobileMenu() {
        const nav = document.querySelector('nav');
        const toggle = document.querySelector('.mobile-menu-toggle');
        nav.classList.toggle('active');
        toggle.classList.toggle('active');
    }

    // Dropdown menu functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Handle mobile dropdown toggles
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const dropdown = this.parentElement;
                const isActive = dropdown.classList.contains('active');

                // On mobile, handle dropdown toggling
                if (window.innerWidth <= 768) {
                    // Close all other dropdowns
                    document.querySelectorAll('.nav-dropdown.active').forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            otherDropdown.classList.remove('active');
                        }
                    });

                    // Toggle this dropdown
                    if (isActive) {
                        dropdown.classList.remove('active');
                    } else {
                        dropdown.classList.add('active');
                    }
                } else {
                    // Desktop behavior - hover handles it
                    dropdown.classList.toggle('active');
                }
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.nav-dropdown')) {
                document.querySelectorAll('.nav-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            }
        });

        // Contact form AJAX submission
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = this.querySelector('.submit-btn');
                const formMessage = document.getElementById('formMessage');

                submitBtn.disabled = true;
                submitBtn.textContent = 'Sending...';

                fetch('/contact-handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    formMessage.style.display = 'block';
                    formMessage.className = 'form-message ' + (data.success ? 'success' : 'error');
                    formMessage.textContent = data.message;

                    if (data.success) {
                        contactForm.reset();
                    }

                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send Message';

                    // Hide message after 5 seconds
                    setTimeout(() => {
                        formMessage.style.display = 'none';
                    }, 5000);
                })
                .catch(error => {
                    formMessage.style.display = 'block';
                    formMessage.className = 'form-message error';
                    formMessage.textContent = 'An error occurred. Please try again later.';

                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send Message';
                });
            });
        }

        // Smooth scroll to contact form when clicking Contact Us links
        document.querySelectorAll('a[href="#contact"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector('#contact').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    });
    </script>

    <!-- Main Script File -->
    <script src="assets/js/script.js" defer></script>

    <?php if (IS_ADMIN): ?>
    <!-- Admin Mode Scripts -->
    <meta name="csrf-token" content="<?php echo getCSRFToken(); ?>">
    <script src="/assets/js/admin-functions.js" defer></script>
    <?php endif; ?>

</body>
</html>