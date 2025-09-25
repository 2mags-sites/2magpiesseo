function toggleMobileMenu() {
    const nav = document.querySelector('.main-nav');
    const overlay = document.querySelector('.mobile-overlay');
    const body = document.body;

    // Only apply mobile styles on mobile devices
    const isMobile = window.innerWidth <= 768;

    if (nav) {
        nav.classList.toggle('active');

        // Apply inline styles for mobile menu only on mobile
        if (nav.classList.contains('active') && isMobile) {
            nav.style.position = 'fixed';
            nav.style.top = '0';
            nav.style.left = '0';
            nav.style.width = '80%';
            nav.style.maxWidth = '300px';
            nav.style.height = '100vh';
            nav.style.background = '#1A1D21';
            nav.style.zIndex = '999';
            nav.style.padding = '4rem 2rem';
            nav.style.display = 'flex';
            nav.style.flexDirection = 'column';
            nav.style.transition = 'all 0.3s ease';
            nav.style.boxShadow = '2px 0 10px rgba(0, 0, 0, 0.3)';

            // Force override any conflicting styles
            nav.style.cssText += '; left: 0px !important; transform: translateX(0px) !important;';

            // Style the navigation list and links for mobile
            const navList = nav.querySelector('ul');
            if (navList) {
                navList.style.flexDirection = 'column';
                navList.style.alignItems = 'flex-start';
                navList.style.gap = '0';
                navList.style.listStyle = 'none';
                navList.style.padding = '0';
                navList.style.margin = '0';
                navList.style.width = '100%';

                // Style all navigation items
                const navItems = navList.querySelectorAll('li');
                navItems.forEach(item => {
                    item.style.width = '100%';

                    // Handle dropdown items
                    if (item.classList.contains('dropdown')) {
                        const dropdownMenu = item.querySelector('.dropdown-menu');
                        if (dropdownMenu) {
                            // Hide dropdown initially
                            dropdownMenu.style.display = 'none';
                            dropdownMenu.style.marginLeft = '0';
                            dropdownMenu.style.marginTop = '0';
                            dropdownMenu.style.position = 'static';
                            dropdownMenu.style.opacity = '1';
                            dropdownMenu.style.visibility = 'visible';
                            dropdownMenu.style.transform = 'none';
                            dropdownMenu.style.background = 'none';
                            dropdownMenu.style.boxShadow = 'none';
                            dropdownMenu.style.border = 'none';
                            dropdownMenu.style.borderRadius = '0';

                            // Style dropdown links
                            const dropdownLinks = dropdownMenu.querySelectorAll('a');
                            dropdownLinks.forEach(link => {
                                link.style.color = '#B8C0C8';
                                link.style.padding = '0.75rem 0 0.75rem 2rem';
                                link.style.fontSize = '1rem';
                                link.style.borderBottom = '1px solid rgba(184, 192, 200, 0.1)';
                                link.style.display = 'block';
                                link.style.transition = 'color 0.2s ease';

                                // Add hover effects for dropdown links
                                link.addEventListener('mouseenter', () => {
                                    link.style.color = '#A9E702';
                                });
                                link.addEventListener('mouseleave', () => {
                                    link.style.color = '#B8C0C8';
                                });
                            });

                            // Add click handler to main dropdown link
                            const mainLink = item.querySelector('a');
                            if (mainLink) {
                                mainLink.addEventListener('click', (e) => {
                                    e.preventDefault();
                                    e.stopPropagation(); // Prevent event bubbling
                                    const isVisible = dropdownMenu.style.display === 'block';
                                    dropdownMenu.style.display = isVisible ? 'none' : 'block';
                                });

                                // Mark this as a dropdown toggle to exclude from menu close logic
                                mainLink.classList.add('dropdown-toggle');
                            }
                        }
                    }
                });

                // Style main navigation links (not dropdown sub-items)
                const mainLinks = navList.querySelectorAll('li > a');
                mainLinks.forEach(link => {
                    // Only style direct child links of li elements, not dropdown sub-items
                    if (!link.closest('.dropdown-menu')) {
                        link.style.color = '#B8C0C8';
                        link.style.textDecoration = 'none';
                        link.style.padding = '1rem 0';
                        link.style.borderBottom = '1px solid rgba(184, 192, 200, 0.2)';
                        link.style.display = 'block';
                        link.style.width = '100%';
                        link.style.fontSize = '1.1rem';
                        link.style.transition = 'color 0.2s ease';

                        // Add hover effects
                        link.addEventListener('mouseenter', () => {
                            link.style.color = '#A9E702';
                        });
                        link.addEventListener('mouseleave', () => {
                            link.style.color = '#B8C0C8';
                        });
                    }
                });
            }
        } else {
            // Reset styles when closing
            if (isMobile) {
                nav.style.left = '-100%';
                nav.style.transform = 'translateX(-100%)';
            } else {
                // Clear all mobile styles on desktop
                nav.style.cssText = '';
            }
        }
    }

    if (overlay) {
        overlay.classList.toggle('active');
    }

    // Prevent body scroll when menu is open
    if (nav && nav.classList.contains('active')) {
        body.style.overflow = 'hidden';
    } else {
        body.style.overflow = '';
    }
}

function closeMobileMenu() {
    const nav = document.querySelector('.main-nav');
    const overlay = document.querySelector('.mobile-overlay');
    const isMobile = window.innerWidth <= 768;

    if (nav) {
        nav.classList.remove('active');

        if (isMobile) {
            nav.style.left = '-100%';
            nav.style.transform = 'translateX(-100%)';
        } else {
            // Clear all mobile styles on desktop
            nav.style.cssText = '';
        }
    }

    if (overlay) {
        overlay.classList.remove('active');
    }

    document.body.style.overflow = '';
}

// Close mobile menu when clicking a link
document.addEventListener('DOMContentLoaded', function() {
    // Close menu when clicking links (except dropdown toggles)
    document.querySelectorAll('.main-nav a').forEach(link => {
        link.addEventListener('click', (e) => {
            // Only trigger mobile menu close on mobile devices and not for dropdown toggles
            const isMobile = window.innerWidth <= 768;
            if (isMobile && !link.classList.contains('dropdown-toggle')) {
                closeMobileMenu();
            }
        });
    });

    // Handle dropdown toggle on desktop
    const dropdownToggles = document.querySelectorAll('.dropdown > a[href="#"]');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault(); // Always prevent default for dropdown toggles
        });
    });

    // Smooth scroll for anchor links
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
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});