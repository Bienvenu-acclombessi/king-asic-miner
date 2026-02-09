@php
use App\Models\Configuration\Collection;
use App\Models\Configuration\Tag;
use Illuminate\Support\Facades\DB;

// Get brand collections (all parent collections except cooling solutions)
$brandCollections = Collection::withCount('products')
    ->whereNull('parent_id')
    ->whereNotIn('slug', ['hydro-cooling', 'immersion-cooling', 'power-generation', 'air-cooling'])
    ->orderBy('attribute_data->name')
    ->get();

// Get tags grouped by type with products count
$tagsByType = Tag::select('tags.*', DB::raw('COUNT(DISTINCT taggables.taggable_id) as products_count'))
    ->leftJoin('taggables', function($join) {
        $join->on('tags.id', '=', 'taggables.tag_id')
             ->where('taggables.taggable_type', '=', 'App\Models\Products\Product');
    })
    ->groupBy('tags.id')
    ->get()
    ->groupBy(function($tag) {
        return $tag->meta['type']['id'] ?? null;
    });

// Get cooling solution collections by slug
$coolingSolutions = Collection::whereNull('parent_id')
    ->whereIn('slug', ['hydro-cooling', 'immersion-cooling', 'power-generation', 'air-cooling'])
    ->with(['children' => function($query) {
        $query->withCount('products');
    }])
    ->withCount('products')
    ->get();
@endphp

<aside class="col-lg-3 col-md-4 mb-6 mb-md-0">
	<div class="offcanvas offcanvas-start offcanvas-collapse w-md-50" tabindex="-1" id="offcanvasCategory" aria-labelledby="offcanvasCategoryLabel">

		<div class="offcanvas-header d-lg-none">
			<h5 class="offcanvas-title" id="offcanvasCategoryLabel">Filter</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body ps-lg-2 pt-lg-0">

			{{-- Card 1: Filter By Asic Miner --}}
			<div class="mb-8 card">
				<div class="card-header">
					<h5 class="mb-0">Filter By Asic Miner</h5>
				</div>
				<div class="card-body">
					<ul class="nav nav-category" id="asicMinerCollapseMenu">

						{{-- Shop By Brand --}}
						<li class="nav-item border-bottom w-100">
							<a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#brandCollapse" aria-expanded="true" aria-controls="brandCollapse">
								Shop By Brand
								<i class="feather-icon icon-chevron-right"></i>
							</a>
							<div id="brandCollapse" class="accordion-collapse collapse show" data-bs-parent="#asicMinerCollapseMenu">
								<div>
									<ul class="nav flex-column ms-3">
										@foreach($brandCollections as $collection)
											<li class="nav-item">
												<a href="{{ route('public.shop.collection', $collection->slug) }}" class="nav-link d-flex justify-content-between align-items-center">
													<span>{{ $collection->attribute_data['name'] ?? 'Collection' }}</span>
													<span class="text-muted">{{ $collection->products_count }}</span>
												</a>
											</li>
										@endforeach
									</ul>
								</div>
							</div>
						</li>

						{{-- Shop By Coins (Type 1) --}}
						@if(isset($tagsByType[1]))
							<li class="nav-item border-bottom w-100">
								<a href="#" class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#coinsCollapse" aria-expanded="false" aria-controls="coinsCollapse">
									{{ $tagsByType[1]->first()->meta['type']['name'] ?? 'Shop By Coins' }}
									<i class="feather-icon icon-chevron-right"></i>
								</a>
								<div id="coinsCollapse" class="accordion-collapse collapse" data-bs-parent="#asicMinerCollapseMenu">
									<div>
										<ul class="nav flex-column ms-3">
											@foreach($tagsByType[1] as $tag)
												<li class="nav-item">
													<a href="{{ route('public.shop.tag', $tag->slug) }}" class="nav-link d-flex justify-content-between align-items-center">
														<span>{{ $tag->value }}</span>
														<span class="text-muted">{{ $tag->products_count }}</span>
													</a>
												</li>
											@endforeach
										</ul>
									</div>
								</div>
							</li>
						@endif

						{{-- Shop By Algorithms (Type 2) --}}
						@if(isset($tagsByType[2]))
							<li class="nav-item border-bottom w-100">
								<a href="#" class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#algorithmsCollapse" aria-expanded="false" aria-controls="algorithmsCollapse">
									{{ $tagsByType[2]->first()->meta['type']['name'] ?? 'Shop By Algorithms' }}
									<i class="feather-icon icon-chevron-right"></i>
								</a>
								<div id="algorithmsCollapse" class="accordion-collapse collapse" data-bs-parent="#asicMinerCollapseMenu">
									<div>
										<ul class="nav flex-column ms-3">
											@foreach($tagsByType[2] as $tag)
												<li class="nav-item">
													<a href="{{ route('public.shop.tag', $tag->slug) }}" class="nav-link d-flex justify-content-between align-items-center">
														<span>{{ $tag->value }}</span>
														<span class="text-muted">{{ $tag->products_count }}</span>
													</a>
												</li>
											@endforeach
										</ul>
									</div>
								</div>
							</li>
						@endif

						{{-- Parts/Accessories (Type 3) --}}
						@if(isset($tagsByType[3]))
							<li class="nav-item border-bottom w-100">
								<a href="#" class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#partsCollapse" aria-expanded="false" aria-controls="partsCollapse">
									{{ $tagsByType[3]->first()->meta['type']['name'] ?? 'Parts/Accessories' }}
									<i class="feather-icon icon-chevron-right"></i>
								</a>
								<div id="partsCollapse" class="accordion-collapse collapse" data-bs-parent="#asicMinerCollapseMenu">
									<div>
										<ul class="nav flex-column ms-3">
											@foreach($tagsByType[3] as $tag)
												<li class="nav-item">
													<a href="{{ route('public.shop.tag', $tag->slug) }}" class="nav-link d-flex justify-content-between align-items-center">
														<span>{{ $tag->value }}</span>
														<span class="text-muted">{{ $tag->products_count }}</span>
													</a>
												</li>
											@endforeach
										</ul>
									</div>
								</div>
							</li>
						@endif

					</ul>
				</div>
			</div>

			{{-- Card 2: Filter by Crypto Mining Solution --}}
			<div class="mb-8 card">
				<div class="card-header">
					<h5 class="mb-0">Filter By Crypto Mining Solution</h5>
				</div>
				<div class="card-body">
					<ul class="nav nav-category" id="coolingSolutionCollapseMenu">

						@foreach($coolingSolutions as $index => $collection)
							<li class="nav-item border-bottom w-100">
								@if($collection->children->isNotEmpty())
									{{-- Has children: show collapse --}}
									<a href="#" class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#cooling{{ $index }}" aria-expanded="false" aria-controls="cooling{{ $index }}">
										{{ $collection->attribute_data['name'] ?? 'Collection' }}
										<i class="feather-icon icon-chevron-right"></i>
									</a>
									<div id="cooling{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#coolingSolutionCollapseMenu">
										<div>
											<ul class="nav flex-column ms-3">
												@foreach($collection->children as $child)
													<li class="nav-item">
														<a href="{{ route('public.shop.collection', $child->slug) }}" class="nav-link d-flex justify-content-between align-items-center">
															<span>{{ $child->attribute_data['name'] ?? 'Subcollection' }}</span>
															<span class="text-muted">{{ $child->products_count }}</span>
														</a>
													</li>
												@endforeach
											</ul>
										</div>
									</div>
								@else
									{{-- No children: direct link --}}
									<a href="{{ route('public.shop.collection', $collection->slug) }}" class="nav-link d-flex justify-content-between align-items-center">
										<span>{{ $collection->attribute_data['name'] ?? 'Collection' }}</span>
										<span class="text-muted">{{ $collection->products_count }}</span>
									</a>
								@endif
							</li>
						@endforeach

					</ul>
				</div>
			</div>

		</div>
	</div>
</aside>

