/**
 * Finance Customers Page Advanced Animations and Interactions
 * Modern CSS and JavaScript-based animations for enhanced UX
 */

class CustomersPageAnimations {
    constructor() {
        this.init();
    }

    init() {
        this.setupScrollReveal();
        this.setupIntersectionObserver();
        this.setupParallaxEffects();
        this.setupCounterAnimations();
        this.setupTableAnimations();
        this.setupFormInteractions();
        this.setupLoadingStates();
        this.setupTooltipAnimations();
    }

    // Scroll-based reveal animations
    setupScrollReveal() {
        const scrollElements = document.querySelectorAll('.scroll-reveal');
        
        const elementInView = (el, dividend = 1) => {
            const elementTop = el.getBoundingClientRect().top;
            return (
                elementTop <= (window.innerHeight || document.documentElement.clientHeight) / dividend
            );
        };

        const displayScrollElement = (element) => {
            element.classList.add('revealed');
        };

        const hideScrollElement = (element) => {
            element.classList.remove('revealed');
        };

        const handleScrollAnimation = () => {
            scrollElements.forEach((el) => {
                if (elementInView(el, 1.25)) {
                    displayScrollElement(el);
                } else {
                    hideScrollElement(el);
                }
            });
        };

        window.addEventListener('scroll', () => {
            requestAnimationFrame(handleScrollAnimation);
        });

        // Initial check
        handleScrollAnimation();
    }

    // Intersection Observer for performance optimization
    setupIntersectionObserver() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    
                    // Stagger animation for metric cards
                    if (entry.target.classList.contains('metric-card')) {
                        const delay = Array.from(entry.target.parentNode.children).indexOf(entry.target) * 100;
                        entry.target.style.animationDelay = `${delay}ms`;
                    }
                }
            });
        }, observerOptions);

        // Observe elements
        document.querySelectorAll('.metric-card, .customer-card, .chart-container').forEach(el => {
            observer.observe(el);
        });
    }

    // Subtle parallax effects
    setupParallaxEffects() {
        let ticking = false;

        const updateParallax = () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.parallax-element');
            
            parallaxElements.forEach(element => {
                const speed = element.dataset.speed || 0.5;
                const yPos = -(scrolled * speed);
                element.style.transform = `translateY(${yPos}px)`;
            });
            
            ticking = false;
        };

        const requestTick = () => {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        };

        window.addEventListener('scroll', requestTick);
    }

    // Counter animations for metric values
    setupCounterAnimations() {
        const animateCounter = (element, start, end, duration = 2000) => {
            const range = end - start;
            const minTimer = 50;
            const stepTime = Math.abs(Math.floor(duration / range));
            const startTime = new Date().getTime();
            const endTime = startTime + duration;
            
            const run = () => {
                const now = new Date().getTime();
                const remaining = Math.max((endTime - now) / duration, 0);
                const value = Math.round(end - (remaining * range));
                
                element.textContent = this.formatNumber(value);
                
                if (value !== end) {
                    requestAnimationFrame(run);
                }
            };
            
            run();
        };

        const metricValues = document.querySelectorAll('.metric-value');
        metricValues.forEach(element => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const text = element.textContent.replace(/[^\d]/g, '');
                        const endValue = parseInt(text) || 0;
                        animateCounter(element, 0, endValue);
                        observer.unobserve(element);
                    }
                });
            });
            observer.observe(element);
        });
    }

    // Enhanced table row animations
    setupTableAnimations() {
        const tableRows = document.querySelectorAll('.table-modern tbody tr');
        
        tableRows.forEach((row, index) => {
            // Add stagger delay
            row.style.setProperty('--stagger-delay', `${index * 0.05}s`);
            
            // Enhanced hover effects
            row.addEventListener('mouseenter', (e) => {
                const rect = e.target.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                // Create ripple effect
                const ripple = document.createElement('div');
                ripple.className = 'table-ripple';
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                
                e.target.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });

            // Add click animation
            row.addEventListener('click', function(e) {
                this.classList.add('row-clicked');
                setTimeout(() => {
                    this.classList.remove('row-clicked');
                }, 300);
            });
        });
    }

    // Form interaction enhancements
    setupFormInteractions() {
        const formControls = document.querySelectorAll('.form-control, .form-select');
        
        formControls.forEach(control => {
            // Focus animation
            control.addEventListener('focus', function() {
                this.closest('.form-floating')?.classList.add('form-focused');
                this.classList.add('input-glow');
            });
            
            control.addEventListener('blur', function() {
                this.closest('.form-floating')?.classList.remove('form-focused');
                this.classList.remove('input-glow');
            });

            // Typing animation
            control.addEventListener('input', function() {
                this.classList.add('input-typing');
                clearTimeout(this.typingTimer);
                this.typingTimer = setTimeout(() => {
                    this.classList.remove('input-typing');
                }, 500);
            });
        });

        // Search button enhancement
        const searchBtn = document.querySelector('.btn-search');
        if (searchBtn) {
            searchBtn.addEventListener('click', function(e) {
                this.classList.add('btn-loading');
                
                // Simulate search loading
                setTimeout(() => {
                    this.classList.remove('btn-loading');
                }, 1000);
            });
        }
    }

    // Loading state animations
    setupLoadingStates() {
        const createSkeletonLoader = (container) => {
            const skeleton = document.createElement('div');
            skeleton.className = 'skeleton-loader';
            skeleton.innerHTML = `
                <div class="skeleton skeleton-text skeleton-title"></div>
                <div class="skeleton skeleton-text"></div>
                <div class="skeleton skeleton-text" style="width: 60%"></div>
            `;
            container.appendChild(skeleton);
            return skeleton;
        };

        // Simulate loading for new content
        window.showLoadingState = (selector) => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(el => {
                el.classList.add('loading-state');
                createSkeletonLoader(el);
            });
        };

        window.hideLoadingState = (selector) => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(el => {
                el.classList.remove('loading-state');
                const skeleton = el.querySelector('.skeleton-loader');
                if (skeleton) skeleton.remove();
            });
        };
    }

    // Enhanced tooltip animations
    setupTooltipAnimations() {
        // Create custom tooltips with animations
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        
        tooltipElements.forEach(element => {
            let tooltip;
            
            element.addEventListener('mouseenter', function(e) {
                tooltip = document.createElement('div');
                tooltip.className = 'custom-tooltip';
                tooltip.textContent = this.dataset.tooltip;
                document.body.appendChild(tooltip);
                
                const rect = this.getBoundingClientRect();
                tooltip.style.left = `${rect.left + rect.width / 2}px`;
                tooltip.style.top = `${rect.top - tooltip.offsetHeight - 8}px`;
                
                // Animate in
                requestAnimationFrame(() => {
                    tooltip.classList.add('tooltip-visible');
                });
            });
            
            element.addEventListener('mouseleave', function() {
                if (tooltip) {
                    tooltip.classList.remove('tooltip-visible');
                    setTimeout(() => {
                        tooltip.remove();
                    }, 200);
                }
            });
        });
    }

    // Utility functions
    formatNumber(num) {
        return new Intl.NumberFormat('tr-TR').format(num);
    }

    // Public methods for external use
    static getInstance() {
        if (!this.instance) {
            this.instance = new CustomersPageAnimations();
        }
        return this.instance;
    }

    // Method to trigger specific animations
    triggerAnimation(selector, animationType) {
        const elements = document.querySelectorAll(selector);
        elements.forEach((el, index) => {
            setTimeout(() => {
                el.classList.add(animationType);
            }, index * 100);
        });
    }

    // Smooth scroll to element
    scrollToElement(selector, offset = 0) {
        const element = document.querySelector(selector);
        if (element) {
            const elementTop = element.getBoundingClientRect().top + window.pageYOffset;
            window.scrollTo({
                top: elementTop - offset,
                behavior: 'smooth'
            });
        }
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    CustomersPageAnimations.getInstance();
});

// CSS for JavaScript-created elements
const additionalStyles = `
    <style>
        .table-ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.2);
            pointer-events: none;
            width: 20px;
            height: 20px;
            animation: table-ripple-animation 0.6s ease-out;
            transform: translate(-50%, -50%);
        }
        
        @keyframes table-ripple-animation {
            0% {
                width: 0;
                height: 0;
                opacity: 1;
            }
            100% {
                width: 200px;
                height: 200px;
                opacity: 0;
            }
        }
        
        .row-clicked {
            animation: row-click-flash 0.3s ease-out;
        }
        
        @keyframes row-click-flash {
            0% { background: rgba(102, 126, 234, 0.1); }
            100% { background: transparent; }
        }
        
        .input-glow {
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.3) !important;
        }
        
        .input-typing {
            border-color: #667eea !important;
        }
        
        .btn-loading {
            position: relative;
            pointer-events: none;
        }
        
        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .custom-tooltip {
            position: absolute;
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.875rem;
            z-index: 1000;
            opacity: 0;
            transform: translateX(-50%) translateY(5px);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }
        
        .custom-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border: 5px solid transparent;
            border-top-color: rgba(0, 0, 0, 0.9);
        }
        
        .tooltip-visible {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        
        .animate-in {
            animation: fadeInScale 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
`;

// Inject additional styles
document.head.insertAdjacentHTML('beforeend', additionalStyles);

// Export for external use
window.CustomersPageAnimations = CustomersPageAnimations;