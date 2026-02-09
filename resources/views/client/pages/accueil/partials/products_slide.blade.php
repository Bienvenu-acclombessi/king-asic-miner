<section class="mt-8">
  <div class="container">
    @if($productSlides && $productSlides->count() > 0)
      <div class="hero-slider">
        @foreach($productSlides as $slide)
          @php
            $product = $slide->product;
            if (!$product) continue;

            // Colonnes directes
            $productName = $product->name ?? 'N/A';
            $productSlug = $product->slug ?? '';
            $shortDesc = $product->short_description ?? '';

            $backgroundImage = $slide->background_image
              ? asset('storage/' . $slide->background_image)
              : 'https://via.placeholder.com/1920x400';
          @endphp

          <div class="product-slide" style="background-image: url('{{ $backgroundImage }}'); background-size: cover; background-position: center center; background-color: rgb(0,5,11);">
            <div class="row align-items-center g-0">
              <div class="col-md-6 col-lg-5 p-5">
                <h2 class="text-white fw-bold mb-3">{{ $productName }}</h2>
                <p class="text-white mb-4">{{ $shortDesc ?: 'Discover this amazing product.' }}</p>
                <a href="{{ route('public.product.show', ['slug' => $productSlug]) }}" class="btn btn-light">
                  Acheter maintenant <i class="feather-icon icon-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="alert alert-info text-center">
        <i class="bi bi-info-circle me-2"></i>
        Aucun slide produit disponible pour le moment.
      </div>
    @endif
  </div>
</section>

<style>
  .product-slide {
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  }

  .product-slide .row {
    min-height: 250px;
  }

  @media (min-width: 768px) {
    .product-slide .row {
      min-height: 250px;
      max-height: 300px;
    }
  }

  @media (max-width: 767px) {
    .product-slide .col-md-6.p-5 {
      padding: 2rem !important;
    }

    .product-slide h2 {
      font-size: 1.5rem;
    }
  }

  /* Indicateurs du slider en blanc */
  .hero-slider .slick-dots li button:before {
    color: #fff !important;
    opacity: 0.5;
  }

  .hero-slider .slick-dots li.slick-active button:before {
    color: #fff !important;
    opacity: 1;
  }

  /* Flèches du slider en blanc */
  .hero-slider .slick-prev:before,
  .hero-slider .slick-next:before {
    color: #fff !important;
  }

  .hero-slider .slick-prev,
  .hero-slider .slick-next {
    z-index: 10;
  }
</style>
