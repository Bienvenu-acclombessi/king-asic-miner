<section class="mt-8">
    <div class="container">
        <div class="table-responsive-xl pb-6">
            <div class="row">
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class=" pt-8 px-6 px-xl-8 rounded"
                        style="background:url(/assets/kingshop/assets/images/banner/banner-deal.jpg)no-repeat; background-size: cover; height: 100%;">
                        <div>
                            <h3 class="fw-bold text-white">100% Organic
                                Coffee Beans.
                            </h3>
                            <p class="text-white">Get the best deal before close.</p>
                            <a href="{{ route('public.shop.index') }}" class="btn btn-primary">Shop Now <i class="feather-icon icon-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">

                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                        <h3 class="mb-0">Par marque</h3>
                        <div>
                            <a href="{{ route('public.shop.index') }}" id="viewAllBrandsBtn" class="btn btn-primary">Voir Tout <i class="feather-icon icon-arrow-right ms-1"></i></a>
                        </div>
                    </div>

                    {{-- Liste de marques - chargée dynamiquement --}}
                    <div class="mb-4">
                        <div class="d-flex flex-wrap gap-3" id="brandsList">
                            <div class="text-center w-100">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Conteneur de produits - chargé dynamiquement via AJAX --}}
                    <div class="row row-cols-lg-4 row-cols-1 row-cols-md-2 g-4 flex-wrap" id="productsContainer">
                        <div class="col-12 text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.brand-link {
    font-size: 0.875rem;
    color: #6c757d;
    transition: color 0.2s ease;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
}

.brand-link:hover {
    color: #495057;
    background-color: #f8f9fa;
}

.brand-link.active {
    color: #000;
    font-weight: 500;
    background-color: #e9ecef;
}
</style>

<script>
let currentBrandId = null;
let brandsData = [];

// Charger les marques au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    loadBrands();
});

// Fonction pour charger les marques
function loadBrands() {
    fetch('{{ route('public.api.brands') }}')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.brands.length > 0) {
                brandsData = data.brands;
                displayBrands(data.brands);

                // Charger les produits de la première marque par défaut
                currentBrandId = data.brands[0].id;
                loadProductsByBrand(data.brands[0].id);

                // Mettre à jour le lien du bouton "Voir Tout"
                updateViewAllButton(data.brands[0].id);
            } else {
                document.getElementById('brandsList').innerHTML = '<p class="text-muted">Aucune marque disponible.</p>';
                document.getElementById('productsContainer').innerHTML = '';
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des marques:', error);
            document.getElementById('brandsList').innerHTML = '<p class="text-danger">Erreur lors du chargement des marques.</p>';
            document.getElementById('productsContainer').innerHTML = '';
        });
}

// Fonction pour afficher les marques
function displayBrands(brands) {
    const container = document.getElementById('brandsList');
    container.innerHTML = brands.map((brand, index) =>
        `<span class="brand-link ${index === 0 ? 'active' : ''}"
               data-brand-id="${brand.id}"
               style="cursor: pointer;">${brand.name}</span>`
    ).join('');

    // Ajouter les événements de clic
    document.querySelectorAll('.brand-link').forEach(link => {
        link.addEventListener('click', function() {
            // Retirer la classe active de tous les liens
            document.querySelectorAll('.brand-link').forEach(lnk => lnk.classList.remove('active'));

            // Ajouter la classe active au lien cliqué
            this.classList.add('active');

            // Charger les produits de la marque
            const brandId = this.getAttribute('data-brand-id');
            currentBrandId = brandId;
            loadProductsByBrand(brandId);

            // Mettre à jour le lien du bouton "Voir Tout"
            updateViewAllButton(brandId);
        });
    });
}

// Fonction pour mettre à jour le lien du bouton "Voir Tout"
function updateViewAllButton(brandId) {
    const viewAllBtn = document.getElementById('viewAllBrandsBtn');
    if (viewAllBtn) {
        viewAllBtn.href = `{{ url('/shop/brand') }}/${brandId}`;
    }
}

// Fonction pour charger les produits d'une marque
function loadProductsByBrand(brandId) {
    const container = document.getElementById('productsContainer');

    // Afficher un loader avec animation
    container.style.opacity = '0.5';
    container.style.transition = 'opacity 0.3s ease';
    container.innerHTML = `
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2 text-muted">Chargement des produits...</p>
        </div>
    `;

    fetch(`{{ url('api/products-by-brand') }}/${brandId}`)
        .then(response => response.json())
        .then(data => {
            setTimeout(() => {
                if (data.success) {
                    if (data.count > 0) {
                        container.innerHTML = data.html;
                    } else {
                        container.innerHTML = `
                            <div class="col-12">
                                <div class="alert alert-info text-center" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Aucun produit disponible pour cette marque.
                                </div>
                            </div>`;
                    }
                } else {
                    container.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-danger text-center" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Erreur lors du chargement des produits.
                            </div>
                        </div>`;
                }
                container.style.opacity = '1';

                // Réinitialiser les tooltips Bootstrap si disponible
                if (typeof bootstrap !== 'undefined') {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                }
            }, 300);
        })
        .catch(error => {
            console.error('Erreur lors du chargement des produits:', error);
            container.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger text-center" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Erreur lors du chargement des produits.
                    </div>
                </div>`;
            container.style.opacity = '1';
        });
}
</script>
