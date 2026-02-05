<aside class="col-lg-3 col-md-4 mb-6 mb-md-0">
	<div class="offcanvas offcanvas-start offcanvas-collapse w-md-50 " tabindex="-1" id="offcanvasCategory" aria-labelledby="offcanvasCategoryLabel">

		<div class="offcanvas-header d-lg-none">
			<h5 class="offcanvas-title" id="offcanvasCategoryLabel">Filter</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body ps-lg-2 pt-lg-0">
			@php
                $category_title = "Filter by Crypto Mining Solution"
            @endphp
			@include('client.pages.shop.partials.category_filter_card')
            @php
            $category_title = "Filter by Asic Miner"
            @endphp
            @include('client.pages.shop.partials.category_filter_card')
		</div>
	</div>
</aside>

