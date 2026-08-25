// Zuvio Global School - Main JS Controllers

document.addEventListener('DOMContentLoaded', () => {
    // 1. Hero Banner Slides Rotation
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.hero-indicator-dot');
    let currentSlide = 0;
    let slideInterval = null;

    if (slides.length > 1) {
        function showSlide(index) {
            slides.forEach(s => s.classList.remove('active'));
            indicators.forEach(i => i.classList.remove('active'));

            slides[index].classList.add('active');
            if (indicators[index]) indicators[index].classList.add('active');
            currentSlide = index;
        }

        function nextSlide() {
            let next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }

        function startAutoplay() {
            slideInterval = setInterval(nextSlide, 6000);
        }

        function stopAutoplay() {
            if (slideInterval) clearInterval(slideInterval);
        }

        // Initialize slideshow
        showSlide(0);
        startAutoplay();

        // Mouse listeners to pause on focus
        const container = document.querySelector('.hero-container');
        if (container) {
            container.addEventListener('mouseenter', stopAutoplay);
            container.addEventListener('mouseleave', startAutoplay);
        }

        // Indicators click events
        indicators.forEach((ind, idx) => {
            ind.addEventListener('click', () => {
                showSlide(idx);
            });
        });

        // Navigation Arrows
        const prevBtn = document.getElementById('heroPrevBtn');
        const nextBtn = document.getElementById('heroNextBtn');
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                let prev = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(prev);
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                nextSlide();
            });
        }
    }

    // 2. Statistics Counter Animation
    const counterElements = document.querySelectorAll('[data-target]');
    
    if (counterElements.length > 0) {
        const animateCount = (el) => {
            const target = parseInt(el.getAttribute('data-target'));
            const suffix = el.getAttribute('data-suffix') || '';
            const id = el.id;
            
            let start = 0;
            if (id === 'stat_students') {
                start = 20;
            } else if (id === 'stat_educators') {
                start = 5;
            }
            
            const duration = 2000; // 2 seconds
            const startTime = performance.now();
            
            const updateCount = (currentTime) => {
                const elapsedTime = currentTime - startTime;
                const progress = Math.min(elapsedTime / duration, 1);
                
                // Ease out quad animation
                const easeProgress = progress * (2 - progress);
                const currentVal = Math.floor(start + (target - start) * easeProgress);
                
                el.innerText = currentVal + suffix;
                
                if (progress < 1) {
                    requestAnimationFrame(updateCount);
                } else {
                    el.innerText = target + suffix;
                }
            };
            
            requestAnimationFrame(updateCount);
        };

        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCount(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        counterElements.forEach(el => observer.observe(el));
    }
});
