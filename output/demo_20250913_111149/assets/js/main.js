/**
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

})();