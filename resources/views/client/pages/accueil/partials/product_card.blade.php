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

    // Slug du produit (colonne directe)
    $productSlug = $product->slug ?? \Illuminate\Support\Str::slug($productName);
@endphp

<div class="card card-product h-100">
    <a href="{{ route('public.product.show', $productSlug) }}" class="text-decoration-none">
        <div class="card-body">
            {{-- 1. Image --}}
            <div class="text-center position-relative mb-3">
                @if($product->is_featured)
                    <div class="position-absolute top-0 start-0">
                        <span class="badge bg-danger">Featured</span>
                    </div>
                @endif

                <img src="{{ $thumbnailUrl }}" alt="{{ $productName }}" class="img-fluid" style="max-height: 200px; object-fit: contain;">

                <div class="card-product-action">
                    <a href="#!" class="btn-action" data-bs-toggle="modal" data-bs-target="#quickViewModal" onclick="event.stopPropagation();">
                        <i class="bi bi-eye" data-bs-toggle="tooltip" data-bs-html="true" title="Quick View"></i>
                    </a>
                    <a href="#!" class="btn-action" data-bs-toggle="tooltip" data-bs-html="true" title="Wishlist" onclick="event.stopPropagation();">
                        <i class="bi bi-heart"></i>
                    </a>
                    <a href="#!" class="btn-action" data-bs-toggle="tooltip" data-bs-html="true" title="Compare" onclick="event.stopPropagation();">
                        <i class="bi bi-arrow-left-right"></i>
                    </a>
                </div>
            </div>

            {{-- 2. Product Name --}}
            <div class="mb-2">
                <h2 class="fs-6 mb-0">
                    <span class="text-inherit text-decoration-none">{{ $productName }}</span>
                </h2>
            </div>

            {{-- 3. Category (Collection) --}}
            <div class="mb-2">
                <span class="text-muted">
                    <small>{{ $categoryName }}</small>
                </span>
            </div>

            {{-- 4. Stock Status --}}
            <div class="mb-2">
                @if($inStock)
                    <span class="badge bg-success">In Stock</span>
                @else
                    <span class="badge bg-danger">Out of Stock</span>
                @endif
            </div>

            {{-- 5. Price --}}
            <div class="mb-2">
                @if($minPrice && $maxPrice)
                    @if($minPrice == $maxPrice)
                        <span class="text-dark fw-bold">${{ number_format($minPrice / 100, 2) }}</span>
                    @else
                        <span class="text-dark fw-bold">${{ number_format($minPrice / 100, 2) }} - ${{ number_format($maxPrice / 100, 2) }}</span>
                    @endif
                @else
                    <span class="text-muted">Prix non disponible</span>
                @endif
            </div>

            {{-- 6. Add to Cart Button --}}
            <div>
                @if($inStock)
                    <button type="button"
                            class="btn btn-primary btn-sm w-100 add-to-cart-btn"
                            data-product-id="{{ $product->id }}"
                            data-quantity="1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-plus">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg> Add to cart
                    </button>
                @else
                    <button type="button" class="btn btn-secondary btn-sm w-100" disabled>
                        Out of Stock
                    </button>
                @endif
            </div>
        </div>
    </a>
</div>