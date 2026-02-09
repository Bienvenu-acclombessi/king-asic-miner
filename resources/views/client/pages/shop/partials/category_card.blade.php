{{-- Category Card Component --}}
@php
    $collectionName = $collection->attribute_data['name'] ?? 'Collection';
    $collectionLink = route('public.shop.collection', $collection->slug);
    $collectionImage = $collection->attribute_data['image'] ?? null;
@endphp
<a href="{{ $collectionLink }}" class="category-card" aria-label="{{ $collectionName }}">
    @if($collectionImage)
        <div class="category-card-image">
            <img src="{{ $collectionImage }}" alt="{{ $collectionName }}" width="70" height="70">
        </div>
    @endif
    <span class="category-card-name">{{ $collectionName }}</span>
</a>
