/**
 * Script pour gérer les filtres de la boutique
 * Améliore l'expérience utilisateur avec des transitions fluides
 */

document.addEventListener('DOMContentLoaded', function() {
    // Gérer les changements de select avec loading state
    const selectElements = document.querySelectorAll('.form-select');

    selectElements.forEach(select => {
        select.addEventListener('change', function() {
            // Ajouter un indicateur de chargement
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'loading-overlay';
            loadingOverlay.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `;
            document.body.appendChild(loadingOverlay);

            // Redirection (le onchange du select gère déjà la redirection)
        });
    });

    // Ajouter des classes CSS pour les transitions
    const style = document.createElement('style');
    style.textContent = `
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .product-grid-transition {
            transition: all 0.3s ease-in-out;
        }
    `;
    document.head.appendChild(style);

    // Smooth scroll pour la pagination
    const paginationLinks = document.querySelectorAll('.pagination .page-link');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Scroll vers le haut de la liste de produits
            const productList = document.querySelector('.row.g-4');
            if (productList) {
                window.scrollTo({
                    top: productList.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
});
