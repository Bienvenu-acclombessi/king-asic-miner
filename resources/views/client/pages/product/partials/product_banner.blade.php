@php
  // Get primary variant and price
  $primaryVariant = $product->variants->first();
  $price = $primaryVariant?->prices->first()?->price ?? 0;
  $comparePrice = $primaryVariant?->prices->first()?->compare_price ?? null;
  $sku = $primaryVariant?->sku ?? '';

  // Prepare variants data for JavaScript
  $variantsData = $product->variants->map(function($variant) {
    $price = $variant->prices->first();
    return [
      'id' => $variant->id,
      'sku' => $variant->sku,
      'stock' => $variant->stock,
      'purchasable' => $variant->purchasable === 'always',
      'price' => $price?->price ?? 0,
      'compare_price' => $price?->compare_price ?? null,
      'option_values' => $variant->values->pluck('id')->toArray(),
    ];
  });
@endphp

<script>
  window.productData = {
    id: {{ $product->id }},
    name: @json($product->name),
    variants: @json($variantsData)
  };
</script>

<section class="mt-8">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          {{-- Product Gallery --}}
          <div class="slider slider-for">
            @if($product->images->isNotEmpty())
              @foreach($product->images as $image)
                <div>
                  <div class="zoom" onmousemove="zoom(event)" style="background-image: url({{ $image->url }})">
                    <img src="{{ $image->url }}" alt="{{ $product->name }}">
                  </div>
                </div>
              @endforeach
            @elseif($product->thumbnail_url)
              <div>
                <div class="zoom" onmousemove="zoom(event)" style="background-image: url({{ $product->thumbnail_url }})">
                  <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}">
                </div>
              </div>
            @else
              <div>
                <div class="zoom" onmousemove="zoom(event)" style="background-image: url(/assets/kingshop/assets/images/products/product-single-img-1.jpg)">
                  <img src="/assets/kingshop/assets/images/products/product-single-img-1.jpg" alt="{{ $product->name }}">
                </div>
              </div>
            @endif
          </div>

          {{-- Thumbnail Navigation --}}
          <div class="slider slider-nav mt-4">
            @if($product->images->isNotEmpty())
              @foreach($product->images as $image)
                <div>
                  <img src="{{ $image->url }}" alt="{{ $product->name }}" class="w-100 rounded">
                </div>
              @endforeach
            @elseif($product->thumbnail_url)
              <div>
                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="w-100 rounded">
              </div>
            @else
              <div>
                <img src="/assets/kingshop/assets/images/products/product-single-img-1.jpg" alt="{{ $product->name }}" class="w-100 rounded">
              </div>
            @endif
          </div>
        </div>

        <div class="col-md-6">
          <div class="ps-lg-10 mt-6 mt-md-0" id="product-detail" data-product-id="{{ $product->id }}">
            {{-- Product Title --}}
            <h1 class="mb-2">{{ $product->name }}</h1>

            {{-- Product Meta --}}
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
              @if($sku)
                <span class="text-muted">SKU: <strong data-sku>{{ $sku }}</strong></span>
              @endif

              @if($product->brand_name)
                <span class="d-flex align-items-center gap-1">
                  <i class="bi bi-award text-success"></i> {{ $product->brand_name }}
                </span>
              @endif

              <span class="d-flex align-items-center gap-1">
                <i class="bi bi-shield-check text-success"></i> Manufacturer Warranty
              </span>

              @if($primaryVariant && $primaryVariant->stock > 0)
                <span class="badge bg-success" data-stock>In Stock ({{ $primaryVariant->stock }} available)</span>
              @else
                <span class="badge bg-danger" data-stock>Out of Stock</span>
              @endif
            </div>

            {{-- Short Description --}}
            @if($product->short_description)
              <div class="mb-3">
                <p>{!! nl2br(e($product->short_description)) !!}</p>
              </div>
            @endif

            {{-- Price Warning --}}
            <div class="alert alert-warning py-2 mb-3">
              <small>With rapid market changes, miner prices can vary frequently, even within a short time. Kindly verify the latest price with our sales team before making your payment.</small>
            </div>

            {{-- Price --}}
            <div class="fs-3 mb-3">
              @if($price > 0)
                <span class="fw-bold text-dark" data-price>${{ number_format($price / 100, 2) }}</span>
                @if($comparePrice && $comparePrice > $price)
                  <span class="text-decoration-line-through text-muted ms-2" data-compare-price>${{ number_format($comparePrice / 100, 2) }}</span>
                @else
                  <span class="text-decoration-line-through text-muted ms-2" data-compare-price style="display: none;"></span>
                @endif
              @else
                <span class="fw-bold text-dark" data-price>Price on request</span>
              @endif
            </div>

            <hr class="my-4">

            {{-- Product Options (Dynamic) --}}
            @if($product->productOptions->isNotEmpty())
              @foreach($product->productOptions as $option)
                @php
                  $optionName = is_array($option->name) ? ($option->name['en'] ?? $option->name['fr'] ?? 'Option') : ($option->name ?? 'Option');
                  $displayType = $option->pivot->display_type ?? 'button';
                  $isRequired = $option->pivot->required ?? false;
                @endphp

                @if($option->values->isNotEmpty())
                  <div class="mb-4" data-option-id="{{ $option->id }}" data-option-required="{{ $isRequired ? 'true' : 'false' }}">
                    <label class="form-label fw-semibold">
                      {{ $optionName }}
                      @if($isRequired)
                        <span class="text-danger">*</span>
                      @endif
                    </label>

                    {{-- Button Display Type --}}
                    @if($displayType === 'button')
                      <div class="d-flex gap-2 flex-wrap">
                        @foreach($option->values as $index => $value)
                          @php
                            $valueName = is_array($value->name) ? ($value->name['en'] ?? $value->name['fr'] ?? '') : ($value->name ?? '');
                          @endphp
                          <button
                            type="button"
                            class="btn btn-outline-primary option-button product-option-input {{ $index === 0 ? 'active' : '' }}"
                            data-option-id="{{ $option->id }}"
                            data-value-id="{{ $value->id }}"
                            data-value-name="{{ $valueName }}">
                            {{ $valueName }}
                          </button>
                        @endforeach
                      </div>

                    {{-- Dropdown Display Type --}}
                    @elseif($displayType === 'dropdown' || $displayType === 'select')
                      <select class="form-select option-select product-option-input" data-option-id="{{ $option->id }}" {{ $isRequired ? 'required' : '' }}>
                        <option value="">Select {{ $optionName }}</option>
                        @foreach($option->values as $value)
                          @php
                            $valueName = is_array($value->name) ? ($value->name['en'] ?? $value->name['fr'] ?? '') : ($value->name ?? '');
                          @endphp
                          <option value="{{ $value->id }}" data-value-name="{{ $valueName }}">
                            {{ $valueName }}
                          </option>
                        @endforeach
                      </select>

                    {{-- Radio Display Type --}}
                    @elseif($displayType === 'radio')
                      <div class="d-flex flex-column gap-2">
                        @foreach($option->values as $index => $value)
                          @php
                            $valueName = is_array($value->name) ? ($value->name['en'] ?? $value->name['fr'] ?? '') : ($value->name ?? '');
                          @endphp
                          <div class="form-check">
                            <input
                              class="form-check-input option-radio product-option-input"
                              type="radio"
                              name="option_{{ $option->id }}"
                              id="option_{{ $option->id }}_{{ $value->id }}"
                              value="{{ $value->id }}"
                              data-option-id="{{ $option->id }}"
                              data-value-name="{{ $valueName }}"
                              {{ $index === 0 ? 'checked' : '' }}
                              {{ $isRequired ? 'required' : '' }}>
                            <label class="form-check-label" for="option_{{ $option->id }}_{{ $value->id }}">
                              {{ $valueName }}
                            </label>
                          </div>
                        @endforeach
                      </div>

                    {{-- Color Swatch Display Type --}}
                    @elseif($displayType === 'color')
                      <div class="d-flex gap-2 flex-wrap">
                        @foreach($option->values as $index => $value)
                          @php
                            $valueName = is_array($value->name) ? ($value->name['en'] ?? $value->name['fr'] ?? '') : ($value->name ?? '');
                            $colorCode = $value->meta['color'] ?? '#cccccc';
                          @endphp
                          <button
                            type="button"
                            class="btn btn-outline-secondary option-color product-option-input {{ $index === 0 ? 'active' : '' }}"
                            data-option-id="{{ $option->id }}"
                            data-value-id="{{ $value->id }}"
                            data-value-name="{{ $valueName }}"
                            style="width: 50px; height: 50px; position: relative;"
                            title="{{ $valueName }}">
                            <span class="d-block w-100 h-100 rounded" style="background-color: {{ $colorCode }};"></span>
                          </button>
                        @endforeach
                      </div>
                    @endif

                    @if(!$isRequired)
                      <a href="#" class="small text-muted option-clear" data-option-id="{{ $option->id }}">Clear</a>
                    @endif
                  </div>
                @endif
              @endforeach
            @endif

            {{-- Quantity & Add to Cart --}}
            <div class="mb-3">
              <div class="input-group input-spinner" style="width: 130px;">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="decrease-quantity">-</button>
                <input type="number" step="1" min="1" max="100" value="1" id="product-quantity" class="quantity-field form-control form-control-sm text-center">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="increase-quantity">+</button>
              </div>
            </div>

            <div class="row g-2 mb-4">
              <div class="col-6">
                <button type="button" class="btn btn-primary w-100" id="add-to-cart-detail">
                  <i class="bi bi-cart-plus me-2"></i>Add to cart
                </button>
              </div>
              <div class="col-6">
                <button type="button" class="btn btn-dark w-100" id="buy-now-detail">Buy now</button>
              </div>
            </div>

            {{-- VIP Price Section --}}
            <div class="bg-light rounded p-3 mb-4">
              <p class="mb-2 small">Apexto is now offering U.S.-based inventory and DDP (Delivered Duty Paid) shipping for customers in the U.S. For more details, pls. contact our sales team.</p>
              <div class="d-flex flex-wrap gap-2 align-items-center">
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#vipPriceModal">
                  <i class="bi bi-star-fill me-1"></i> Get VIP Price
                </button>
                <small class="text-muted">Order 2 or more machines to save on shipping costs! Contact our sales team now to secure the best rates.</small>
              </div>
            </div>

            <hr class="my-4">

            {{-- Trusted By --}}
            <div class="mb-4">
              <h6 class="fw-semibold mb-3">Trusted by</h6>
              <div class="row g-2">
                <div class="col-6 col-lg-3">
                  <div class="bg-light rounded p-2 text-center">
                    <img src="https://apextomining.com/apexto/uploads/2022/12/Apexto-Mining-Reviews.png" alt="Trustpilot" class="img-fluid" style="max-height: 30px;">
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="bg-light rounded p-2 text-center">
                    <img src="https://apextomining.com/apexto/uploads/2022/12/Asicminervalue-1.png" alt="Asicminervalue" class="img-fluid" style="max-height: 30px;">
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="bg-light rounded p-2 text-center">
                    <img src="https://apextomining.com/apexto/uploads/2022/12/kryptex-1.png" alt="Kryptex" class="img-fluid" style="max-height: 30px;">
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="bg-light rounded p-2 text-center">
                    <img src="https://apextomining.com/apexto/uploads/2022/12/nicehash-2.png" alt="NiceHash" class="img-fluid" style="max-height: 30px;">
                  </div>
                </div>
              </div>
            </div>

            {{-- Payment Methods --}}
            <div class="mb-4">
              <h6 class="fw-semibold mb-3">Payment Method</h6>
              <div class="d-flex flex-wrap gap-2">
                <span class="badge bg-secondary">USD</span>
                <span class="badge bg-secondary">USDT (TRC 20)</span>
                <span class="badge bg-secondary">KAS</span>
                <span class="badge bg-secondary">BTC</span>
                <span class="badge bg-secondary">ETH</span>
                <span class="badge bg-secondary">LTC</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
