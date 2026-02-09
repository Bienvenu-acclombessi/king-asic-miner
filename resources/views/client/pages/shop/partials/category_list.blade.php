@php
use App\Models\Configuration\Collection;

// Get the 12 top collections ordered by products count
$collections = Collection::withCount('products')
    ->orderBy('products_count', 'desc')
    ->limit(12)
    ->get();
@endphp

{{-- Collections List Section --}}
<section class="categories-section">
    <div class="categories-grid">
        @foreach($collections as $collection)
            @php
                $collectionName = $collection->attribute_data['name'] ?? 'Unknown';
                $collectionSlug = Str::slug($collectionName);
                $collectionImage = $collection->meta['image'] ?? null;
            @endphp

            @include('client.pages.shop.partials.category_card', [
                'name' => $collectionName,
                'image' => $collectionImage,
                'link' => route('public.shop.category', ['slug' => $collectionSlug])
            ])
        @endforeach
    </div>
</section>

<style>
.categories-section {
    padding: 20px 0;
}

.categories-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: flex-start;
}

.category-card {
    display: inline-flex;
    align-items: center;
    border: 1px solid #d1d1d1;
    background: #fff;
    padding: 0 20px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
    min-height: 70px;
    min-width: 150px;
}

.category-card:hover {
    border-color: #f7941d;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.category-card-image {
    width: 70px;
    height: 70px;
    margin: 0 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-card-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.category-card-name {
    color: #000;
    margin: 0 10px;
    font-weight: 500;
    white-space: nowrap;
}

@media (max-width: 768px) {
    .categories-grid {
        display: none;
    }
}
</style>
