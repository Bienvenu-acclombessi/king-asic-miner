<style>
    @media (max-width: 767px) {
        .banner-title { font-size: 1.5rem !important; }
        .banner-subtitle { font-size: 1.2rem !important; }
        .banner-text { font-size: 0.9rem !important; }
        .banner-btn { font-size: 0.65rem !important; padding: 0.6rem 0.8rem !important; }
        .banner-btn i { font-size: 1rem !important; }
        .banner-text-col { padding-top: 2rem !important; padding-bottom: 1rem !important; }
        .banner-image-col { padding-top: 0.5rem !important; padding-bottom: 2rem !important; padding-left: 1rem !important; padding-right: 1rem !important; }
        .banner-image { max-width: 80% !important; }
    }
    @media (min-width: 768px) {
        .banner-title { font-size: 3rem !important; }
        .banner-subtitle { font-size: 2rem !important; }
        .banner-text { font-size: 1.25rem !important; }
        .banner-btn { font-size: 0.85rem !important; gap: 0.5rem; padding: 0.8rem 1rem !important; max-width: 250px !important; }
        .banner-btn i { font-size: 1.2rem !important; }
    }
</style>
<section class="mt-1" style="background-image: url('/assets/kingshop/assets/images/custom/banner_bg.webp'); background-size: cover; background-position: center; min-height: 100vh; position: relative; margin-bottom: 150px;">
    <div class="container">
        <div class="row">
            <div class="col-xxl-5 col-md-7 py-md-14 text-center text-md-start d-flex flex-column banner-text-col">
                <h2 class="text-white fw-bold mt-4 banner-title">FOURNISSEUR D'OR APEXTO</h2>
                <h3 class="text-white mt-1 banner-subtitle" style="font-weight: 400;">POUR TOUS VOS BESOINS MINIERS</h3>
                <p class="text-white banner-text">Apexto vend des mineurs à des prix avantageux et peut vous aider à raccourcir le ROl.</p>
                <a href="#!" class="btn btn-primary mt-3 rounded-pill d-flex flex-row align-items-center mx-auto mx-md-0 banner-btn"><i class="feather-icon icon-gift mb-2"></i><span>OBTENEZ UN COUPON DE 20%</span></a>
            </div>
            <div class="col-xxl-7 col-md-5 py-md-14 px-md-8 d-flex justify-content-center align-items-center banner-image-col">
                <img src="/assets/kingshop/assets/images/custom/banner_product.webp" height="373" width="533" alt="banner image" class="img-fluid banner-image">
            </div>
                

        </div>
    </div>

    <!-- Stats Box - 50% on banner, 50% outside -->
    <div class="container" style="position: absolute; bottom: 0; left: 50%; transform: translate(-50%, 50%); width: 90%; max-width: 1200px;">
        <div class="row d-none d-md-flex bg-white shadow-lg" style="border-radius: 60px; padding: 0rem 2rem; height: 150px;">
            <div class="col-md-3 d-flex flex-column align-items-start justify-content-center position-relative">
                <div class="counter-value h1 fw-bold text-dark mb-2" data-target="14">0</div>
                <div class="counter-label mb-3">Des années d'expériences</div>
                <div class="bg-primary" style="width: 50px; height: 5px; margin-left: 15%;"></div>
            </div>
            <div class="col-md-3 d-flex flex-column align-items-start justify-content-center position-relative text-center">
                <div class="counter-value h1 fw-bold text-dark mb-2" data-target="258000">0</div>
                <div class="counter-label mb-3">Matériel minier vendu</div>
                <div class="bg-primary" style="width: 50px; height: 5px;margin-left: 15%;"></div>
            </div>
            <div class="col-md-3 d-flex flex-column align-items-start justify-content-center position-relative text-center">
                <div class="counter-value h1 fw-bold text-dark mb-2" data-target="20000">0</div>
                <div class="counter-label mb-3">Clients du monde entier</div>
                <div class="bg-primary" style="width: 50px; height: 5px;margin-left: 15%;"></div>
            </div>
            <div class="col-md-3 d-flex flex-column align-items-start justify-content-center text-center">
                <div class="counter-value h1 fw-bold text-dark mb-2" data-target="80">0</div>
                <div class="counter-label mb-3">Pays livrés à</div>
                <div class="bg-primary" style="width: 50px; height: 5px;margin-left: 15%;"></div>
            </div>
        </div>

        <!-- Mobile Stats - 3 boxes only -->
        <div class="row d-flex d-md-none gap-2 justify-content-center">
            <div class="col bg-white shadow-sm d-flex flex-column align-items-center justify-content-center" style="border-radius: 5px; padding: 1rem; min-height: 100px;">
                <div class="counter-value h4 fw-bold text-dark mb-1" data-target="14">0</div>
                <div class="counter-label small text-center">Années d'expériences</div>
            </div>
            <div class="col bg-white shadow-sm d-flex flex-column align-items-center justify-content-center" style="border-radius: 5px; padding: 1rem; min-height: 100px;">
                <div class="counter-value h4 fw-bold text-dark mb-1" data-target="258000">0</div>
                <div class="counter-label small text-center">Matériel minier vendu</div>
            </div>
            <div class="col bg-white shadow-sm d-flex flex-column align-items-center justify-content-center" style="border-radius: 5px; padding: 1rem; min-height: 100px;">
                <div class="counter-value h4 fw-bold text-dark mb-1" data-target="80">0</div>
                <div class="counter-label small text-center">Pays de livraison</div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter-value[data-target]');
    const duration = 2000; // Durée de l'animation en ms

    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-target'));
        const start = 0;
        const startTime = performance.now();

        const updateCounter = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Easing function pour un effet plus fluide
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const current = Math.floor(start + (target - start) * easeOutQuart);

            counter.textContent = current.toLocaleString('fr-FR');

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString('fr-FR');
            }
        };

        requestAnimationFrame(updateCounter);
    };

    // Observer pour déclencher l'animation quand les compteurs sont visibles
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
});
</script>