@php
    // Récupérer les paramètres actuels de l'URL
    $currentLayout = request()->get('layout', 'grid');
    $currentPerPage = request()->get('per_page', 50);
    $currentSort = request()->get('sort', 'featured');

    // Fonction pour générer l'URL avec les paramètres
    $buildUrl = function($params) {
        return request()->fullUrlWithQuery($params);
    };
@endphp

<!-- card -->

          <!-- list icon -->
          <div class="d-lg-flex justify-content-between align-items-center">
            <div class="mb-3 mb-lg-0">
              <h1 class="mb-0 h4 text-dark">
                @if(isset($filterType) && isset($filterValue))
                  {{ $filterValue }}
                @else
                  All Products
                @endif
              </h1>
            </div>

            <!-- icon -->
            <div class="d-md-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center justify-content-between">
              <div>
              {{-- Liens de disposition dynamiques --}}
              <a href="{{ $buildUrl(['layout' => 'list']) }}"
                 class="{{ $currentLayout === 'list' ? 'active' : 'text-muted' }} me-3">
                <i class="bi bi-list-ul"></i>
              </a>
              <a href="{{ $buildUrl(['layout' => 'grid']) }}"
                 class="{{ $currentLayout === 'grid' ? 'active' : 'text-muted' }} me-3">
                <i class="bi bi-grid"></i>
              </a>
              <a href="{{ $buildUrl(['layout' => 'grid-3']) }}"
                 class="{{ $currentLayout === 'grid-3' ? 'active' : 'text-muted' }} me-3">
                <i class="bi bi-grid-3x3-gap"></i>
              </a>
              </div>
              <div class="ms-2 d-lg-none">
                <a class="btn btn-outline-gray-400 text-muted" data-bs-toggle="offcanvas" href="#offcanvasCategory" role="button" aria-controls="offcanvasCategory"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="feather feather-filter me-2">
                  <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg> Filters</a>
              </div>
            </div>

              <div class="d-flex mt-2 mt-lg-0">
                <div class="me-2 flex-grow-1">
                  <!-- select option pour le nombre par page -->
                  <select class="form-select" onchange="window.location.href=this.value">
                    <option value="{{ $buildUrl(['per_page' => 10]) }}" {{ $currentPerPage == 10 ? 'selected' : '' }}>Show: 10</option>
                    <option value="{{ $buildUrl(['per_page' => 20]) }}" {{ $currentPerPage == 20 ? 'selected' : '' }}>Show: 20</option>
                    <option value="{{ $buildUrl(['per_page' => 30]) }}" {{ $currentPerPage == 30 ? 'selected' : '' }}>Show: 30</option>
                    <option value="{{ $buildUrl(['per_page' => 50]) }}" {{ $currentPerPage == 50 ? 'selected' : '' }}>Show: 50</option>
                  </select>
                </div>
                <div>
                  <!-- select option pour le tri -->
                  <select class="form-select" onchange="window.location.href=this.value">
                    <option value="{{ $buildUrl(['sort' => 'featured']) }}" {{ $currentSort === 'featured' ? 'selected' : '' }}>Sort by: Featured</option>
                    <option value="{{ $buildUrl(['sort' => 'price_asc']) }}" {{ $currentSort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="{{ $buildUrl(['sort' => 'price_desc']) }}" {{ $currentSort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="{{ $buildUrl(['sort' => 'date']) }}" {{ $currentSort === 'date' ? 'selected' : '' }}>Release Date</option>
                    <option value="{{ $buildUrl(['sort' => 'rating']) }}" {{ $currentSort === 'rating' ? 'selected' : '' }}>Avg. Rating</option>
                  </select>
                </div>

              </div>

            </div>
          </div>

          @if(isset($filterType))
          <div class="card my-4 bg-light border-0 shadow-1">
            <!-- card body -->
            <div class="card-body p-4">
              <p class="mb-0">
                Showing results for <strong>{{ $filterValue }}</strong>
                <span class="text-muted">({{ isset($products) ? $products->total() : 0 }} products)</span>
              </p>
            </div>
          </div>
          @endif

          @php
            // Déterminer les classes CSS selon la disposition
            $rowClasses = match($currentLayout) {
                'list' => 'row g-4 row-cols-1 mt-2',
                'grid-3' => 'row g-4 row-cols-xl-3 row-cols-lg-3 row-cols-2 row-cols-md-2 mt-2',
                default => 'row g-4 row-cols-xl-4 row-cols-lg-3 row-cols-2 row-cols-md-2 mt-2', // grid
            };
          @endphp

          <!-- row -->
          <div class="{{ $rowClasses }}">

          @if(isset($products) && $products->count() > 0)
            @foreach($products as $product)
            <!-- col -->
            <div class="col">
              @if($currentLayout === 'list')
                @include('client.pages.accueil.partials.product_card_horizontal', ['product' => $product])
              @else
                @include('client.pages.accueil.partials.product_card', ['product' => $product])
              @endif
            </div>
            @endforeach
          @else
            <div class="col-12">
              <div class="card my-4">
                <div class="card-body text-center py-5">
                  <p class="mb-0 text-muted">No products found.</p>
                </div>
              </div>
            </div>
          @endif

          </div>

          {{-- Pagination Laravel --}}
          @if(isset($products) && $products->hasPages())
          <div class="row mt-8">
            <div class="col">
              <!-- nav -->
              <nav>
                <ul class="pagination">
                  {{-- Bouton Précédent --}}
                  @if ($products->onFirstPage())
                    <li class="page-item disabled">
                      <span class="page-link mx-1" aria-label="Previous">
                        <i class="feather-icon icon-chevron-left"></i>
                      </span>
                    </li>
                  @else
                    <li class="page-item">
                      <a class="page-link mx-1" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                        <i class="feather-icon icon-chevron-left"></i>
                      </a>
                    </li>
                  @endif

                  {{-- Numéros de page --}}
                  @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if ($page == $products->currentPage())
                      <li class="page-item active">
                        <span class="page-link mx-1">{{ $page }}</span>
                      </li>
                    @else
                      <li class="page-item">
                        <a class="page-link mx-1 text-body" href="{{ $url }}">{{ $page }}</a>
                      </li>
                    @endif
                  @endforeach

                  {{-- Bouton Suivant --}}
                  @if ($products->hasMorePages())
                    <li class="page-item">
                      <a class="page-link mx-1 text-body" href="{{ $products->nextPageUrl() }}" aria-label="Next">
                        <i class="feather-icon icon-chevron-right"></i>
                      </a>
                    </li>
                  @else
                    <li class="page-item disabled">
                      <span class="page-link mx-1" aria-label="Next">
                        <i class="feather-icon icon-chevron-right"></i>
                      </span>
                    </li>
                  @endif
                </ul>
              </nav>
            </div>
          </div>
          @endif