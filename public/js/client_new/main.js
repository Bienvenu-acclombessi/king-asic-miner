/**
 * Main JavaScript for Apexto Mining Clone
 * Handles interactions, animations, and dynamic behaviors
 */

document.addEventListener('DOMContentLoaded', function() {

    // ===================================
    // Sticky Header on Scroll
    // ===================================
    const header = document.querySelector('.main-header');
    let lastScroll = 0;

    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;

        if (currentScroll > 100) {
            header.classList.add('shadow');
        } else {
            header.classList.remove('shadow');
        }

        lastScroll = currentScroll;
    });

    // ===================================
    // Search Form Enhancement
    // ===================================
    const searchInputs = document.querySelectorAll('.search-form input');

    searchInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('active');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('active');
        });
    });

    // ===================================
    // Product Card Hover Effects
    // ===================================
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s ease';
        });
    });

    // ===================================
    // Smooth Scroll for Anchor Links
    // ===================================
    const anchorLinks = document.querySelectorAll('a[href^="#"]');

    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');

            if (href !== '#' && href !== '#!' && document.querySelector(href)) {
                e.preventDefault();
                const target = document.querySelector(href);

                window.scrollTo({
                    top: target.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ===================================
    // Add to Cart Button Animation
    // ===================================
    const addToCartButtons = document.querySelectorAll('.product-card .btn-primary');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Add animation class
            this.classList.add('added');
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="bi bi-check2"></i> Added!';

            // Reset after 2 seconds
            setTimeout(() => {
                this.classList.remove('added');
                this.innerHTML = originalText;
            }, 2000);
        });
    });

    // ===================================
    // Newsletter Form Validation
    // ===================================
    const newsletterForms = document.querySelectorAll('.newsletter-form');

    newsletterForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value.trim();

            if (validateEmail(email)) {
                // Success message
                showNotification('Success! You\'ve been subscribed to our newsletter.', 'success');
                emailInput.value = '';
            } else {
                // Error message
                showNotification('Please enter a valid email address.', 'error');
            }
        });
    });

    // ===================================
    // Email Validation Helper
    // ===================================
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // ===================================
    // Notification System
    // ===================================
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 start-50 translate-middle-x mt-3`;
        notification.style.zIndex = '9999';
        notification.innerHTML = message;

        document.body.appendChild(notification);

        // Fade in
        setTimeout(() => {
            notification.style.opacity = '1';
        }, 10);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // ===================================
    // Mega Menu Enhancement
    // ===================================
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');

        if (toggle && menu) {
            toggle.addEventListener('mouseenter', function() {
                if (window.innerWidth > 768) {
                    menu.classList.add('show');
                }
            });

            dropdown.addEventListener('mouseleave', function() {
                if (window.innerWidth > 768) {
                    menu.classList.remove('show');
                }
            });
        }
    });

    // ===================================
    // Lazy Loading for Images (if needed)
    // ===================================
    const lazyImages = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));

    // ===================================
    // Cart Update Animation
    // ===================================
    function updateCartCount(count) {
        const cartBadge = document.querySelector('.bi-cart3 + .badge');
        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.style.animation = 'none';
            setTimeout(() => {
                cartBadge.style.animation = 'pulse 0.5s ease';
            }, 10);
        }
    }

    // ===================================
    // Mobile Menu Close on Link Click
    // ===================================
    const offcanvasLinks = document.querySelectorAll('#mobileMenu a');
    const offcanvasElement = document.getElementById('mobileMenu');

    if (offcanvasElement) {
        const bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);

        offcanvasLinks.forEach(link => {
            link.addEventListener('click', () => {
                bsOffcanvas.hide();
            });
        });
    }

    // ===================================
    // Scroll to Top Button (optional)
    // ===================================
    const scrollToTopBtn = document.createElement('button');
    scrollToTopBtn.innerHTML = '<i class="bi bi-arrow-up"></i>';
    scrollToTopBtn.className = 'btn btn-primary position-fixed bottom-0 end-0 m-4 rounded-circle';
    scrollToTopBtn.style.width = '50px';
    scrollToTopBtn.style.height = '50px';
    scrollToTopBtn.style.display = 'none';
    scrollToTopBtn.style.zIndex = '1000';

    document.body.appendChild(scrollToTopBtn);

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.style.display = 'block';
        } else {
            scrollToTopBtn.style.display = 'none';
        }
    });

    scrollToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // ===================================
    // Initialize Tooltips (if using)
    // ===================================
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // ===================================
    // Console Welcome Message
    // ===================================
    console.log('%c🚀 Apexto Mining Clone', 'color: #25c385; font-size: 20px; font-weight: bold;');
    console.log('%cBuilt with Bootstrap 5 & Vanilla JavaScript', 'color: #767676; font-size: 12px;');

});

// ===================================
// Additional CSS for animations
// ===================================
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.2);
        }
    }

    .btn.added {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
    }

    .search-form.active input {
        border-color: #25c385;
    }
`;
document.head.appendChild(style);

// ===================================
// Header Specific Functionality
// ===================================
document.addEventListener('DOMContentLoaded', function() {

    // ===================================
    // Cart Management
    // ===================================
    let cartData = {
        items: [],
        count: 0,
        total: 0
    };

    // Update cart display
    function updateCartDisplay() {
        // Update badge count
        const cartBadges = document.querySelectorAll('.cart-widget .badge');
        cartBadges.forEach(badge => {
            badge.textContent = cartData.count;
        });

        // Update total amount
        const cartTotals = document.querySelectorAll('.cart-total');
        cartTotals.forEach(total => {
            total.textContent = `$${cartData.total.toFixed(2)}`;
        });

        // Update offcanvas footer total
        const offcanvasTotal = document.querySelector('#cartOffcanvas .offcanvas-footer .fw-bold:last-child');
        if (offcanvasTotal) {
            offcanvasTotal.textContent = `$${cartData.total.toFixed(2)}`;
        }

        // Animate badge
        cartBadges.forEach(badge => {
            badge.style.animation = 'none';
            setTimeout(() => {
                badge.style.animation = 'pulse 0.5s ease';
            }, 10);
        });
    }

    // Add item to cart (example function)
    window.addToCart = function(productId, productName, price) {
        cartData.items.push({
            id: productId,
            name: productName,
            price: price
        });
        cartData.count++;
        cartData.total += price;
        updateCartDisplay();

        // Show notification
        showNotification(`${productName} ajouté au panier!`, 'success');
    };

    // ===================================
    // Search Functionality
    // ===================================
    const searchOffcanvas = document.getElementById('searchOffcanvas');
    if (searchOffcanvas) {
        searchOffcanvas.addEventListener('shown.bs.offcanvas', function () {
            const searchInput = this.querySelector('input[type="text"]');
            if (searchInput) {
                searchInput.focus();
            }
        });
    }

    // Live search (optional - can be implemented with AJAX)
    const searchInput = document.querySelector('#searchOffcanvas input[name="q"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length >= 3) {
                searchTimeout = setTimeout(() => {
                    // Implement AJAX search here
                    console.log('Searching for:', query);
                    // Example: fetch(`/api/search?q=${query}`)
                }, 300);
            }
        });
    }

    // ===================================
    // Account Offcanvas Enhancement
    // ===================================
    const accountOffcanvas = document.getElementById('accountOffcanvas');
    if (accountOffcanvas) {
        accountOffcanvas.addEventListener('shown.bs.offcanvas', function () {
            // Add animation to menu items
            const menuItems = this.querySelectorAll('.account-menu li');
            menuItems.forEach((item, index) => {
                setTimeout(() => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(20px)';
                    item.style.transition = 'all 0.3s ease';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, 10);
                }, index * 50);
            });
        });
    }

    // ===================================
    // Mega Menu Hover Enhancement
    // ===================================
    const megaMenuItems = document.querySelectorAll('.mega-menu');
    megaMenuItems.forEach(menu => {
        const dropdownMenu = menu.querySelector('.dropdown-menu');

        if (dropdownMenu && window.innerWidth >= 992) {
            menu.addEventListener('mouseenter', function() {
                dropdownMenu.classList.add('show');
            });

            menu.addEventListener('mouseleave', function() {
                dropdownMenu.classList.remove('show');
            });
        }
    });

    // ===================================
    // Sticky Header Enhancement
    // ===================================
    const mainHeader = document.querySelector('.main-header');
    const promoBanner = document.querySelector('.promo-banner');
    let headerOffset = 0;

    if (promoBanner) {
        headerOffset = promoBanner.offsetHeight;
    }

    window.addEventListener('scroll', function() {
        if (window.scrollY > headerOffset) {
            mainHeader.classList.add('scrolled');
        } else {
            mainHeader.classList.remove('scrolled');
        }
    });

    // ===================================
    // Language Selector Enhancement
    // ===================================
    const languageDropdown = document.getElementById('languageDropdown');
    if (languageDropdown) {
        const dropdown = new bootstrap.Dropdown(languageDropdown);

        // Add search functionality in language dropdown (optional)
        const languageItems = document.querySelectorAll('.language-selector .dropdown-item');
        languageItems.forEach(item => {
            item.addEventListener('click', function(e) {
                const selectedLang = this.querySelector('span').textContent;
                const selectedFlag = this.querySelector('img').src;

                // Update button
                const buttonImg = languageDropdown.querySelector('img');
                const buttonSpan = languageDropdown.querySelector('span');

                if (buttonImg && buttonSpan) {
                    buttonImg.src = selectedFlag;
                    buttonSpan.textContent = selectedLang;
                }
            });
        });
    }

    // ===================================
    // Mobile Menu Enhancement
    // ===================================
    const mobileMenuOffcanvas = document.getElementById('mobileMenu');
    if (mobileMenuOffcanvas) {
        mobileMenuOffcanvas.addEventListener('shown.bs.offcanvas', function () {
            // Add slide-in animation for menu items
            const menuItems = this.querySelectorAll('ul > li');
            menuItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                item.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, index * 50);
            });
        });
    }

    // ===================================
    // Promotional Banner Text Animation
    // ===================================
    const promoSlider = document.querySelector('.promo-text-slider');
    if (promoSlider) {
        const slides = promoSlider.querySelectorAll('.promo-slide');

        if (slides.length > 1) {
            let currentSlide = 0;

            setInterval(() => {
                slides[currentSlide].style.opacity = '0';
                slides[currentSlide].style.transform = 'translateY(-20px)';

                currentSlide = (currentSlide + 1) % slides.length;

                slides[currentSlide].style.opacity = '1';
                slides[currentSlide].style.transform = 'translateY(0)';
            }, 5000); // Change every 5 seconds
        }
    }

    // ===================================
    // Initialize on Load
    // ===================================
    updateCartDisplay();

});
