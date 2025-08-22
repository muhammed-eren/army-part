const hotspots = document.querySelectorAll('.hotspot');
        
hotspots.forEach(hotspot => {
    const tooltipId = hotspot.getAttribute('data-tooltip');
    const tooltip = document.getElementById(`tooltip-${tooltipId}`);
    
    let hoverTimeout;
    let isTooltipVisible = false;
    
    hotspot.addEventListener('mouseenter', (e) => {
        clearTimeout(hoverTimeout);
        
        document.querySelectorAll('.tooltip').forEach(t => {
            if (t !== tooltip) {
                t.classList.remove('active');
            }
        });
        
        hoverTimeout = setTimeout(() => {
            tooltip.classList.add('active');
            isTooltipVisible = true;
        }, 150);
    });
    
    hotspot.addEventListener('mouseleave', (e) => {
        clearTimeout(hoverTimeout);
        
        setTimeout(() => {
            if (!tooltip.matches(':hover') && !hotspot.matches(':hover')) {
                tooltip.classList.remove('active');
                isTooltipVisible = false;
            }
        }, 300);
    });
    
    tooltip.addEventListener('mouseenter', () => {
        clearTimeout(hoverTimeout);
    });
    
    tooltip.addEventListener('mouseleave', () => {
        tooltip.classList.remove('active');
        isTooltipVisible = false;
    });
});

document.addEventListener('click', (e) => {
    if (!e.target.closest('.hotspot') && !e.target.closest('.tooltip')) {
        document.querySelectorAll('.tooltip').forEach(tooltip => {
            tooltip.classList.remove('active');
        });
    }
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.tooltip').forEach(tooltip => {
            tooltip.classList.remove('active');
        });
    }
});


$(document).ready(function() {
    $('#blur-overlay').animate({ opacity: 0 }, 400);
}); 
$('.magic-btn').on('mousemove', function(e) {
    const $btn = $(this);
    const offset = $btn.offset();
    const x = e.pageX - offset.left;
    const y = e.pageY - offset.top;
    $btn.css('--x', `${x}px`);
    $btn.css('--y', `${y}px`);
});


document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const hamburger = document.querySelector('.hamburger');
    const mobileNav = document.querySelector('.mobile-nav');
    
    hamburger.addEventListener('click', function() {
        this.classList.toggle('active');
        mobileNav.classList.toggle('nav-open');
    });

    // Close mobile menu when clicking a link
    const mobileLinks = document.querySelectorAll('.mobile-nav a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            mobileNav.classList.remove('nav-open');
        });
    });

    // Add hover effects to navigation links
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Add particle effect on mouse move
    document.addEventListener('mousemove', function(e) {
        if (Math.random() > 0.98) {
            createParticle(e.clientX, e.clientY);
        }
    });

    function createParticle(x, y) {
        const particle = document.createElement('div');
        const icons = ['fas fa-cog', 'fas fa-wrench', 'fas fa-tools'];
        const randomIcon = icons[Math.floor(Math.random() * icons.length)];
        
        particle.innerHTML = `<i class="${randomIcon}"></i>`;
        particle.className = 'fixed text-red-400 text-xs pointer-events-none';
        particle.style.left = x + 'px';
        particle.style.top = y + 'px';
        particle.style.animation = 'fadeOut 2s ease-out forwards';
        document.body.appendChild(particle);

        setTimeout(() => {
            particle.remove();
        }, 2000);
    }

    // Add fadeOut animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeOut {
            0% { opacity: 1; transform: scale(1) rotate(0deg); }
            100% { opacity: 0; transform: scale(0) translateY(-30px) rotate(180deg); }
        }
    `;
    document.head.appendChild(style);

    // Counter animation for stats
    const animateCounters = () => {
        const counters = document.querySelectorAll('.count');
        counters.forEach(counter => {
            const target = parseInt(counter.textContent.replace(/[^0-9]/g, ''));
            const increment = target / 100;
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                const suffix = counter.textContent.includes('K') ? 'K+' : 
                                counter.textContent.includes('h') ? 'h' : '+';
                counter.textContent = Math.floor(current) + suffix;
            }, 50);
        });
    };

    setTimeout(animateCounters, 2000);

    // Parallax Effect
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.parallax-element');
        
        parallaxElements.forEach(element => {
            const speed = element.dataset.speed || 0.5;
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });

        // Update background position for parallax background
        const parallaxBg = document.querySelector('.parallax-bg');
        if (parallaxBg) {
            parallaxBg.style.backgroundPositionY = `${scrolled * 0.5}px`;
        }
    });

    // Scroll Reveal Animation
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.scroll-reveal').forEach(el => {
        observer.observe(el);
    });

    // Counter Animation
    function animateStatCounters() {
        const counters = document.querySelectorAll('.stat-counter');
        counters.forEach(counter => {
            const target = parseInt(counter.dataset.target);
            const increment = target / 100;
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                const displayValue = Math.floor(current);
                if (target >= 1000) {
                    counter.textContent = displayValue.toLocaleString('tr-TR') + '+';
                } else if (target === 99) {
                    counter.textContent = displayValue + '%';
                } else {
                    counter.textContent = displayValue + '+';
                }
            }, 30);
        });
    }

    // Trigger counter animation when stats section is visible
    const statsSection = document.querySelector('.military-gradient');
    const statsObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStatCounters();
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    if (statsSection) {
        statsObserver.observe(statsSection);
    }

    // Smooth scrolling for anchor links
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

    // Add loading animation
    setTimeout(() => {
        document.body.classList.add('loaded');
    }, 100);
});

// Performance optimization for parallax
let ticking = false;

function updateParallax() {
    const scrolled = window.pageYOffset;
    const parallaxElements = document.querySelectorAll('.parallax-element');
    
    parallaxElements.forEach(element => {
        const speed = element.dataset.speed || 0.5;
        const yPos = -(scrolled * speed);
        element.style.transform = `translateY(${yPos}px)`;
    });
    
    ticking = false;
}

window.addEventListener('scroll', function() {
    if (!ticking) {
        requestAnimationFrame(updateParallax);
        ticking = true;
    }
});