@php
  // Algorithm - try to get from product type or tags
  $algorithm = 'SHA-256'; // Default
  if ($product->tags->isNotEmpty()) {
    foreach ($product->tags as $tag) {
      $tagName = is_array($tag->name) ? ($tag->name['en'] ?? '') : ($tag->name ?? '');
      if (in_array(strtoupper($tagName), ['SHA-256', 'SCRYPT', 'ETHASH', 'KHEAVYHASH', 'X11', 'BLAKE2B'])) {
        $algorithm = strtoupper($tagName);
        break;
      }
    }
  }
@endphp

<section class="mt-lg-14 mt-8">
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-12">
				<!-- Product Description -->
				<div class="mb-5">
					<h2 class="mb-4">{{ $product->name }} Description</h2>
					<div class="my-4">
						@if($product->description)
							{!! nl2br(e($product->description)) !!}
						@else
							<p class="mb-3">{{ $product->name }} is a professional ASIC mining machine.</p>
						@endif
					</div>
				</div>

				<!-- Specifications -->
				<div class="mb-5">
					<h3 class="mb-4">Specifications</h3>
					<div class="my-4">
						<div class="table-responsive">
							<table class="table table-striped">
								<tbody>
									{{-- Dynamic Attributes from Database --}}
									@if($product->attributes && $product->attributes->count() > 0)
										@foreach($product->attributes as $attribute)
											@php
												// Get attribute value from pivot table
												$attrValue = $attribute->pivot->value ?? null;

												// Skip if no value
												if ($attrValue === null || $attrValue === '') {
													continue;
												}

												// Get attribute name (multilingual support)
												$attrName = is_array($attribute->name)
													? ($attribute->name['en'] ?? $attribute->name['fr'] ?? $attribute->name[0] ?? $attribute->handle)
													: ($attribute->name ?? $attribute->handle);

												// Special styling for certain attributes
												$isHighlighted = in_array($attribute->handle, ['hashrate', 'power', 'efficiency']);
												$highlightClass = '';
												if ($attribute->handle === 'hashrate') {
													$highlightClass = 'text-primary fw-bold';
												} elseif ($attribute->handle === 'power') {
													$highlightClass = 'text-success fw-bold';
												} elseif ($attribute->handle === 'efficiency') {
													$highlightClass = 'text-info fw-bold';
												}
											@endphp

											<tr>
												<th style="width: 40%;">{{ ucfirst($attrName) }}</th>
												<td>
													@if($attribute->type === 'boolean' || $attribute->type === 'checkbox')
														@if($attrValue == '1' || $attrValue === true || $attrValue === 'true')
															<span class="badge bg-success">Yes</span>
														@else
															<span class="badge bg-secondary">No</span>
														@endif
													@elseif($attribute->type === 'select' || $attribute->type === 'dropdown')
														<strong>{{ $attrValue }}</strong>
													@elseif($isHighlighted)
														<strong class="{{ $highlightClass }}">{{ $attrValue }}</strong>
													@else
														{{ $attrValue }}
													@endif
												</td>
											</tr>
										@endforeach
									@else
										{{-- Fallback if no attributes --}}
										<tr>
											<th>Model</th>
											<td>{{ $product->name }}</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>

			</div>

			<!-- Sidebar -->
			<div class="col-md-3 col-12">
				<!-- Product Quick Info -->
				<div class="card mb-4">
					<div class="card-body">
						<h5 class="card-title fw-bold mb-4">{{ $product->name }}</h5>
						<div class="row text-center mb-4">
							<div class="col-4">
								<div class="mb-2">
									<img src="/assets/img/product/algorithms.webp" alt="Algorithm" style="width: 40px; height: 40px;">
								</div>
								<p class="small text-muted mb-1">Algorithm</p>
								<p class="fw-bold mb-0">{{ $algorithm }}</p>
							</div>
							<div class="col-4">
								<div class="mb-2">
									<img src="/assets/img/product/hashrate.webp" alt="Hashrate" style="width: 40px; height: 40px;">
								</div>
								<p class="small text-muted mb-1">Hashrate</p>
								<p class="fw-bold mb-0">{{ $product->getCustomAttribute('hashrate', 'N/A') }}</p>
							</div>
							<div class="col-4">
								<div class="mb-2">
									<img src="/assets/img/product/consumption.webp" alt="Consumption" style="width: 40px; height: 40px;">
								</div>
								<p class="small text-muted mb-1">Consumption</p>
								<p class="fw-bold text-success mb-0">{{ $product->getCustomAttribute('power', 'N/A') }}</p>
							</div>
						</div>

						<h6 class="fw-bold mb-3">Minable coins</h6>
						<div class="d-flex flex-wrap gap-3">
							@if($product->minableCoins && $product->minableCoins->count() > 0)
								@foreach($product->minableCoins->take(4) as $coin)
									<div class="text-center">
										@if($coin->logo_url)
											<img src="{{ $coin->logo_url }}"
												 alt="{{ $coin->name }}"
												 style="width: 40px; height: 40px; object-fit: contain;"
												 onerror="this.style.display='none'">
										@else
											<div style="width: 40px; height: 40px; background-color: {{ $coin->color ?? '#ccc' }}; border-radius: 50%; display: inline-block;"></div>
										@endif
										<p class="small mt-1 mb-0 fw-bold">{{ $coin->symbol }}</p>
									</div>
								@endforeach
								@if($product->minableCoins->count() > 4)
									<div class="text-center align-self-center">
										<p class="small text-muted mb-0">+{{ $product->minableCoins->count() - 4 }} more</p>
									</div>
								@endif
							@else
								<p class="small text-muted">No minable coins specified</p>
							@endif
						</div>
					</div>
				</div>

				<!-- B2B Bulk Orders Form -->
				<div class="card">
					<div class="card-body">
						<h5 class="fw-bold mb-1">B2B</h5>
						<h5 class="fw-bold mb-2">Bulk Orders & Wholesale</h5>
						<p class="small text-muted mb-4">Enjoy discounted pricing on large orders of crypto mining products.</p>

						<form action="#" method="POST">
							@csrf
							<div class="mb-3">
								<input type="email" class="form-control" placeholder="Your email *" required>
							</div>
							<div class="mb-3">
								<input type="tel" class="form-control" placeholder="Phone *" required>
							</div>
							<div class="mb-3">
								<input type="text" class="form-control" placeholder="Target Price *" required>
							</div>
							<div class="mb-3">
								<textarea class="form-control" rows="4" placeholder="Your message"></textarea>
							</div>
							<button type="submit" class="btn btn-success w-100">Request A Custom Quote!</button>
							<p class="small text-center text-muted mt-3 mb-0">By continuing, you agree to <a href="/privacy-policy">privacy policy</a></p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
