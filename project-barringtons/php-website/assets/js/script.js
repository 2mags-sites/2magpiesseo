// Note: Mobile menu and dropdown functionality is handled in footer.php
// This file handles the reviews carousel and other non-menu features

// Reviews carousel functionality
let currentReviewIndex = 0;
const reviewsTrack = document.querySelector('.reviews-track');
const reviewCards = document.querySelectorAll('.review-card');
const totalReviews = reviewCards.length;

function getVisibleCards() {
    if (window.innerWidth <= 768) return 1;
    if (window.innerWidth <= 1024) return 2;
    return 3;
}

function updateCarousel() {
    const visibleCards = getVisibleCards();
    const cardWidth = 100 / visibleCards;
    const gap = 2; // rem
    const gapInPercent = (gap * 16) / (reviewsTrack.offsetWidth / visibleCards) * 100;
    const offset = currentReviewIndex * (cardWidth + gapInPercent);
    reviewsTrack.style.transform = `translateX(-${offset}%)`;
}

function scrollReviews(direction) {
    const visibleCards = getVisibleCards();
    const maxIndex = Math.max(0, totalReviews - visibleCards);

    currentReviewIndex += direction;

    if (currentReviewIndex < 0) {
        currentReviewIndex = maxIndex;
    } else if (currentReviewIndex > maxIndex) {
        currentReviewIndex = 0;
    }

    updateCarousel();
}

// Auto-scroll reviews every 5 seconds
setInterval(() => {
    scrollReviews(1);
}, 5000);

// Update carousel on window resize
window.addEventListener('resize', updateCarousel);

// Intersection Observer for fade-in animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animationDelay = '0.1s';
            entry.target.classList.add('fade-in');
        }
    });
}, observerOptions);

document.querySelectorAll('.fade-in').forEach(el => {
    observer.observe(el);
});

// Smooth scroll for navigation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});