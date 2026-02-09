@php
    // Calculer les prix min et max des variants
    $prices = $product->variants->flatMap(function($variant) {
        return $variant->prices->pluck('price');
    })->filter();

    $minPrice = $prices->min();
    $maxPrice = $prices->max();

    // Vérifier le stock
    $totalStock = $product->variants->sum('stock');
    $inStock = $totalStock > 0;

    // Récupérer la première collection
    $collection = $product->collections->first();

    // Helper function pour extraire le nom (gère string ou array)
    $getName = function($data) {
        if (is_string($data)) {
            return $data;
        }
        if (is_array($data)) {
            return $data['en'] ?? $data['fr'] ?? (is_string(reset($data)) ? reset($data) : '');
        }
        return '';
    };

    // Récupérer le nom de la collection (Category = Collection)
    $categoryName = 'Non catégorisé';
    if ($collection && isset($collection->attribute_data['name'])) {
        $categoryName = $getName($collection->attribute_data['name']);
    }

    // Récupérer l'image thumbnail (colonne directe)
    $thumbnailUrl = $product->thumbnail_url ?? '/assets/kingshop/assets/images/products/product-img-1.jpg';

    // Nom du produit (colonne directe)
    $productName = $product->name ?? 'Produit sans nom';

    // Description du produit (colonne directe)
    $productDescription = $product->description ?? '';

    // Récupérer la marque (brand)
    $brandName = '';
    if ($product->brand) {
        $brandName = $product->brand->name ?? '';
    }

    // Générer un slug à partir du nom
    $productSlugRaw = $product->attribute_data['slug'] ?? null;
    $productSlug = $productSlugRaw ? $getName($productSlugRaw) : \Illuminate\Support\Str::slug($productName);
@endphp

<div class="card card-product-horizontal h-100">
    <a href="{{ route('public.product.show', $productSlug) }}" class="text-decoration-none">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-row align-items-start gap-3">
                {{-- Image à gauche --}}
                <div class="flex-shrink-0 product-image-container" style="width: 180px; min-width: 120px;">
                    <div class="position-relative">
                        @if($product->is_featured)
                            <div class="position-absolute top-0 start-0" style="z-index: 10;">
                                <span class="badge bg-danger" style="font-size: 0.7rem;">Featured</span>
                            </div>
                        @endif

                        <img src="{{ $thumbnailUrl }}"
                             alt="{{ $productName }}"
                             class="img-fluid rounded"
                             style="width: 100%; height: auto; max-height: 180px; object-fit: contain;">
                    </div>
                </div>

                {{-- Informations à droite --}}
                <div class="flex-grow-1 d-flex flex-column justify-content-between">
                    {{-- Titre et détails --}}
                    <div class="mb-3">
                        {{-- Nom du produit --}}
                        <h3 class="mb-2" style="font-size: clamp(0.9rem, 2.5vw, 1.125rem); line-height: 1.4;">
                            <span class="text-dark text-decoration-none fw-semibold">{{ $productName }}</span>
                        </h3>

                        {{-- Marque --}}
                        @if($brandName)
                        <div class="mb-2">
                            <span class="text-muted" style="font-size: 0.875rem;">
                                {{ $brandName }}
                            </span>
                        </div>
                        @endif

                        {{-- Stock Status --}}
                        <div class="mb-3">
                            @if($inStock)
                                <span class="badge bg-light text-success border border-success" style="font-size: 0.75rem; font-weight: 500;">In stock</span>
                            @else
                                <span class="badge bg-light text-danger border border-danger" style="font-size: 0.75rem; font-weight: 500;">Out of stock</span>
                            @endif
                        </div>

                        {{-- Prix --}}
                        <div class="mb-3">
                            @if($minPrice && $maxPrice)
                                @if($minPrice == $maxPrice)
                                    <span class="text-dark fw-bold" style="font-size: clamp(1.125rem, 3vw, 1.5rem);">
                                        ${{ number_format($minPrice / 100, 2) }}
                                    </span>
                                @else
                                    <span class="text-dark fw-bold" style="font-size: clamp(1.125rem, 3vw, 1.5rem);">
                                        ${{ number_format($minPrice / 100, 2) }} – ${{ number_format($maxPrice / 100, 2) }}
                                    </span>
                                @endif
                            @else
                                <span class="text-muted" style="font-size: 1rem;">Prix non disponible</span>
                            @endif
                        </div>

                        {{-- Description --}}
                        <div class="d-none d-md-block mb-3">
                            @if($productDescription)
                                <p class="text-muted mb-0" style="font-size: 0.875rem; line-height: 1.6;">
                                    {{ Str::limit($productDescription, 180) }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        {{-- Add to Cart Button --}}
                        <button class="btn btn-primary"
                                style="font-size: 0.875rem; padding: 0.5rem 1.5rem;"
                                onclick="event.stopPropagation(); event.preventDefault();">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-shopping-cart me-1">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Add to cart
                        </button>

                        {{-- Actions secondaires --}}
                        <div class="d-flex gap-2">
                            <a href="#!" class="btn btn-outline-secondary"
                               data-bs-toggle="modal"
                               data-bs-target="#quickViewModal"
                               onclick="event.stopPropagation();"
                               title="Quick View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#!" class="btn btn-outline-secondary"
                               data-bs-toggle="tooltip"
                               title="Wishlist"
                               onclick="event.stopPropagation();">
                                <i class="bi bi-heart"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>

<style>
    /* Styles spécifiques pour la carte horizontale */
    .card-product-horizontal {
        transition: all 0.3s ease;
    }

    .card-product-horizontal:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    /* Responsive adjustments pour tablette */
    @media (max-width: 991.98px) {
        .card-product-horizontal .product-image-container {
            width: 140px !important;
            min-width: 100px !important;
        }

        .card-product-horizontal img {
            max-height: 140px !important;
        }
    }

    /* Responsive adjustments pour mobile */
    @media (max-width: 767.98px) {
        .card-product-horizontal .card-body {
            padding: 0.75rem !important;
        }

        .card-product-horizontal .product-image-container {
            width: 90px !important;
            min-width: 90px !important;
        }

        .card-product-horizontal img {
            max-height: 90px !important;
        }

        .card-product-horizontal h3 {
            font-size: 0.875rem !important;
            margin-bottom: 0.5rem !important;
            line-height: 1.3 !important;
        }

        .card-product-horizontal .badge {
            font-size: 0.65rem !important;
            padding: 0.25rem 0.4rem !important;
        }

        .card-product-horizontal .d-flex.gap-3 {
            gap: 0.75rem !important;
        }

        .card-product-horizontal .d-flex.gap-2 {
            gap: 0.5rem !important;
        }

        .card-product-horizontal .btn {
            font-size: 0.75rem !important;
            padding: 0.375rem 0.75rem !important;
        }

        .card-product-horizontal .btn svg {
            width: 14px !important;
            height: 14px !important;
        }
    }

    /* Ajustements pour très petits écrans */
    @media (max-width: 575.98px) {
        .card-product-horizontal .card-body {
            padding: 0.5rem !important;
        }

        .card-product-horizontal .product-image-container {
            width: 75px !important;
            min-width: 75px !important;
        }

        .card-product-horizontal img {
            max-height: 75px !important;
        }

        .card-product-horizontal h3 {
            font-size: 0.8rem !important;
        }

        .card-product-horizontal .text-muted {
            font-size: 0.7rem !important;
        }

        .card-product-horizontal .btn {
            font-size: 0.7rem !important;
            padding: 0.3rem 0.6rem !important;
        }

        .card-product-horizontal .btn-outline-secondary {
            padding: 0.3rem 0.5rem !important;
        }
    }
</style>
