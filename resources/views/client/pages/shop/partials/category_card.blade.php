{{-- Category Card Component --}}
<a href="{{ $link ?? '#' }}" class="category-card" aria-label="{{ $name }}">
    <div class="category-card-image">
        <img src="{{ $image }}" alt="{{ $name }}" width="70" height="70">
    </div>
    <span class="category-card-name">{{ $name }}</span>
</a>
