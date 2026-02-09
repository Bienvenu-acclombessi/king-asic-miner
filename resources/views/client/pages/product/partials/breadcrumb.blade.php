<div class="mt-4">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Home</a></li>

              @if($product->collections->isNotEmpty())
                @php
                  $primaryCollection = $product->collections->first();
                  $collectionData = $primaryCollection->attribute_data;
                  $collectionName = is_array($collectionData) && isset($collectionData['name'])
                    ? (is_array($collectionData['name']) ? ($collectionData['name']['en'] ?? $collectionData['name'][0] ?? 'Collection') : $collectionData['name'])
                    : 'Collection';
                @endphp
                <li class="breadcrumb-item">
                  <a href="{{ route('public.shop.collection', $primaryCollection->slug ?? '#') }}">{{ $collectionName }}</a>
                </li>
              @else
                <li class="breadcrumb-item"><a href="{{ route('public.shop.index') }}">Shop</a></li>
              @endif

              <li class="breadcrumb-item active" aria-current="page">
                {{ $product->name }}
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
</div>
