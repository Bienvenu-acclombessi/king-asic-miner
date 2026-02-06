<nav class="navbar-vertical-nav d-none d-xl-block ">
	<div class="navbar-vertical">
		<div class="px-4 py-5">
			<a href="{{ route('admin.dashboard') }}" class="navbar-brand">
				<img src="/assets/kingshop/assets/images/logo/freshcart-logo.svg" alt="King ASIC Miner">
			</a>
		</div>
		<div class="navbar-vertical-content flex-grow-1" data-simplebar="">
			<ul class="navbar-nav flex-column" id="sideNavbar">

				<!-- Dashboard -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-house"></i>
							</span>
							<span class="nav-link-text">Dashboard</span>
						</div>
					</a>
				</li>

				<!-- Sales Management Section -->
				<li class="nav-item mt-6 mb-3">
					<span class="nav-label">Sales Management</span>
				</li>

				<!-- Orders -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-cart"></i>
							</span>
							<span class="nav-link-text">Orders</span>
						</div>
					</a>
				</li>

				<!-- Customers -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-people"></i>
							</span>
							<span class="nav-link-text">Customers</span>
						</div>
					</a>
				</li>

				<!-- Store Management Section -->
				<li class="nav-item mt-6 mb-3">
					<span class="nav-label">Store Management</span>
				</li>

				<!-- Products -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-box"></i>
							</span>
							<span class="nav-link-text">Products</span>
						</div>
					</a>
				</li>

				<!-- Attributes -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}" href="{{ route('admin.attributes.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-list-stars"></i>
							</span>
							<span class="nav-link-text">Attributes</span>
						</div>
					</a>
				</li>

				<!-- Collection Groups -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.collection-groups.*') ? 'active' : '' }}" href="{{ route('admin.collection-groups.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-collection"></i>
							</span>
							<span class="nav-link-text">Collection Groups</span>
						</div>
					</a>
				</li>

				<!-- Categories -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-folder2-open"></i>
							</span>
							<span class="nav-link-text">Categories</span>
						</div>
					</a>
				</li>

				<!-- Tags -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-tags"></i>
							</span>
							<span class="nav-link-text">Tags</span>
						</div>
					</a>
				</li>

				<!-- Product Types -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.product-types.*') ? 'active' : '' }}" href="{{ route('admin.product-types.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-box-seam"></i>
							</span>
							<span class="nav-link-text">Product Types</span>
						</div>
					</a>
				</li>

				<!-- Product Options -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.product-options.*') ? 'active' : '' }}" href="{{ route('admin.product-options.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-sliders"></i>
							</span>
							<span class="nav-link-text">Product Options</span>
						</div>
					</a>
				</li>

				<!-- Brands -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-shop"></i>
							</span>
							<span class="nav-link-text">Brands</span>
						</div>
					</a>
				</li>

				<!-- Tax Classes -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.tax-classes.*') ? 'active' : '' }}" href="{{ route('admin.tax-classes.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-calculator"></i>
							</span>
							<span class="nav-link-text">Tax Classes</span>
						</div>
					</a>
				</li>

				<!-- Marketing Section -->
				<li class="nav-item mt-6 mb-3">
					<span class="nav-label">Marketing</span>
				</li>

				<!-- Promotions -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}" href="{{ route('admin.promotions.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-badge-percent"></i>
							</span>
							<span class="nav-link-text">Promotions</span>
						</div>
					</a>
				</li>

				<!-- Coupons -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-ticket-perforated"></i>
							</span>
							<span class="nav-link-text">Coupons</span>
						</div>
					</a>
				</li>

				<!-- Email Campaigns -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.email-campaigns.*') ? 'active' : '' }}" href="{{ route('admin.email-campaigns.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-envelope"></i>
							</span>
							<span class="nav-link-text">Email Campaigns</span>
						</div>
					</a>
				</li>

				<!-- Reviews -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-star"></i>
							</span>
							<span class="nav-link-text">Reviews</span>
						</div>
					</a>
				</li>

			</ul>
		</div>
	</div>
</nav>

<!-- Mobile Sidebar (Offcanvas) -->
<nav class="navbar-vertical-nav offcanvas offcanvas-start navbar-offcanvac" tabindex="-1" id="offcanvasExample">
	<div class="navbar-vertical">
		<div class="px-4 py-5 d-flex justify-content-between align-items-center">
			<a href="{{ route('admin.dashboard') }}" class="navbar-brand">
				<img src="/assets/kingshop/assets/images/logo/freshcart-logo.svg" alt="King ASIC Miner">
			</a>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="navbar-vertical-content flex-grow-1" data-simplebar="">
			<ul class="navbar-nav flex-column">

				<!-- Dashboard -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-house"></i>
							</span>
							<span>Dashboard</span>
						</div>
					</a>
				</li>

				<!-- Sales Management Section -->
				<li class="nav-item mt-6 mb-3">
					<span class="nav-label">Sales Management</span>
				</li>

				<!-- Orders -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-cart"></i>
							</span>
							<span class="nav-link-text">Orders</span>
						</div>
					</a>
				</li>

				<!-- Customers -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-people"></i>
							</span>
							<span class="nav-link-text">Customers</span>
						</div>
					</a>
				</li>

				<!-- Store Management Section -->
				<li class="nav-item mt-6 mb-3">
					<span class="nav-label">Store Management</span>
				</li>

				<!-- Products -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-box"></i>
							</span>
							<span class="nav-link-text">Products</span>
						</div>
					</a>
				</li>

				<!-- Attributes -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}" href="{{ route('admin.attributes.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-list-stars"></i>
							</span>
							<span class="nav-link-text">Attributes</span>
						</div>
					</a>
				</li>

				<!-- Collection Groups -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.collection-groups.*') ? 'active' : '' }}" href="{{ route('admin.collection-groups.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-collection"></i>
							</span>
							<span class="nav-link-text">Collection Groups</span>
						</div>
					</a>
				</li>

				<!-- Categories -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-folder2-open"></i>
							</span>
							<span class="nav-link-text">Categories</span>
						</div>
					</a>
				</li>

				<!-- Tags -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-tags"></i>
							</span>
							<span class="nav-link-text">Tags</span>
						</div>
					</a>
				</li>

				<!-- Product Types -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.product-types.*') ? 'active' : '' }}" href="{{ route('admin.product-types.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-box-seam"></i>
							</span>
							<span class="nav-link-text">Product Types</span>
						</div>
					</a>
				</li>

				<!-- Product Options -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.product-options.*') ? 'active' : '' }}" href="{{ route('admin.product-options.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-sliders"></i>
							</span>
							<span class="nav-link-text">Product Options</span>
						</div>
					</a>
				</li>

				<!-- Brands -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-shop"></i>
							</span>
							<span class="nav-link-text">Brands</span>
						</div>
					</a>
				</li>

				<!-- Tax Classes -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.tax-classes.*') ? 'active' : '' }}" href="{{ route('admin.tax-classes.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-calculator"></i>
							</span>
							<span class="nav-link-text">Tax Classes</span>
						</div>
					</a>
				</li>

				<!-- Marketing Section -->
				<li class="nav-item mt-6 mb-3">
					<span class="nav-label">Marketing</span>
				</li>

				<!-- Promotions -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}" href="{{ route('admin.promotions.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-badge-percent"></i>
							</span>
							<span class="nav-link-text">Promotions</span>
						</div>
					</a>
				</li>

				<!-- Coupons -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-ticket-perforated"></i>
							</span>
							<span class="nav-link-text">Coupons</span>
						</div>
					</a>
				</li>

				<!-- Email Campaigns -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.email-campaigns.*') ? 'active' : '' }}" href="{{ route('admin.email-campaigns.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-envelope"></i>
							</span>
							<span class="nav-link-text">Email Campaigns</span>
						</div>
					</a>
				</li>

				<!-- Reviews -->
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
						<div class="d-flex align-items-center">
							<span class="nav-link-icon">
								<i class="bi bi-star"></i>
							</span>
							<span class="nav-link-text">Reviews</span>
						</div>
					</a>
				</li>

			</ul>
		</div>
	</div>
</nav>
