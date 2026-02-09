@if($relatedProducts && $relatedProducts->count() > 0)
<section class="my-lg-14 my-14">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h3>Related Items</h3>
        </div>
      </div>
      <div class="row g-4 row-cols-lg-5 row-cols-2 row-cols-md-2 mt-2">
        @foreach($relatedProducts as $relatedProduct)
          @php
            // Get price
            $relatedVariant = $relatedProduct->variants->first();
            $relatedPrice = $relatedVariant?->prices->first()?->price ?? 0;
            $relatedComparePrice = $relatedVariant?->prices->first()?->compare_price ?? null;
          @endphp

          <div class="col">
            <div class="card card-product">
              <div class="card-body">
                <div class="text-center position-relative">
                  <a href="{{ route('public.product.show', $relatedProduct->slug) }}">
                    @if($relatedProduct->thumbnail_url)
                      <img src="{{ $relatedProduct->thumbnail_url }}" alt="{{ $relatedProduct->name }}" class="mb-3 img-fluid" style="max-height: 200px; object-fit: contain;" onerror="this.src='/assets/kingshop/assets/images/products/product-img-1.jpg'">
                    @else
                      <img src="/assets/kingshop/assets/images/products/product-img-1.jpg" alt="{{ $relatedProduct->name }}" class="mb-3 img-fluid" style="max-height: 200px; object-fit: contain;">
                    @endif
                  </a>
                </div>

                @if($relatedProduct->brand_name)
                  <div class="text-small mb-1">
                    <a href="{{ route('public.shop.brand', $relatedProduct->brand_id) }}" class="text-decoration-none text-muted">
                      <small>{{ $relatedProduct->brand_name }}</small>
                    </a>
                  </div>
                @endif

                <h2 class="fs-6">
                  <a href="{{ route('public.product.show', $relatedProduct->slug) }}" class="text-inherit text-decoration-none">
                    {{ Str::limit($relatedProduct->name, 50) }}
                  </a>
                </h2>

                @if($relatedProduct->getCustomAttribute('hashrate'))
                  <div class="text-muted small mb-2">
                    <i class="bi bi-cpu"></i> {{ $relatedProduct->getCustomAttribute('hashrate') }}
                  </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mt-3">
                  <div>
                    @if($relatedPrice > 0)
                      <span class="text-dark fw-bold">${{ number_format($relatedPrice / 100, 0) }}</span>
                      @if($relatedComparePrice && $relatedComparePrice > $relatedPrice)
                        <span class="text-decoration-line-through text-muted small">${{ number_format($relatedComparePrice / 100, 0) }}</span>
                      @endif
                    @else
                      <span class="text-dark small">Price on request</span>
                    @endif
                  </div>
                  <div>
                    <a href="{{ route('public.product.show', $relatedProduct->slug) }}" class="btn btn-primary btn-sm">
                      <i class="bi bi-eye"></i> View
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
</section>
@endif
