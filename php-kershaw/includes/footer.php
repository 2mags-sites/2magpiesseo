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
                        <li><a href="/service-traditional-burial.php">Traditional Burial</a></li>
                        <li><a href="/service-cremation.php">Cremation Service</a></li>
                        <li><a href="/service-direct-cremation.php">Direct Cremation</a></li>
                        <li><a href="/pre-paid-plans.php">Pre-Paid Plans</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/about.php">About Us</a></li>
                        <li><a href="/funeral-costs.php">Funeral Costs</a></li>
                        <li><a href="/arranging-a-funeral.php">Arranging a Funeral</a></li>
                        <li><a href="/contact.php">Contact Us</a></li>
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
        // Mobile Menu Toggle
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const navMenu = document.querySelector('.nav-menu');
        const navItems = document.querySelectorAll('.nav-item');

        // Toggle mobile menu
        mobileMenuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });

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
    </script>

    <?php if(isset($page_scripts)) echo $page_scripts; ?>
</body>
</html>