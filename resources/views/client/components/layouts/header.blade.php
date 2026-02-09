<div class="border-bottom">
    <!-- Promo & Language Bar - Keep intact -->
    @include('client.components.layouts.promo_message')
    <!-- Main Header - Logo | Nav | Search | Account | Cart -->
    <nav class="navbar navbar-expand-lg navbar-light py-3 py-lg-4">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between w-100">
                <!-- Mobile Layout -->
                <div class="d-flex d-lg-none align-items-center w-100 justify-content-between position-relative">
                    <!-- Drawer Icon - Left (no padding) -->
                    <button class="navbar-toggler border-0 p-0 ms-n2" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbar-default" aria-controls="navbar-default">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                    </button>

                    <!-- Logo - Center -->
                    <a class="navbar-brand position-absolute start-50 translate-middle-x mb-0" href="/">
                        <img src="/assets/kingshop/assets/images/logo/logo-1-2048x523.webp" alt="King ASIC Miner" style="max-height: 40px;">
                    </a>

                    <!-- Right Icons - Cart puis Search -->
                    <div class="d-flex align-items-center">
                        <!-- Cart Icon -->
                        <a class="text-muted position-relative me-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                            href="#!" aria-controls="offcanvasRight">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success cart-count">0</span>
                        </a>

                        <!-- Search Icon -->
                        <button class="btn btn-link text-muted p-0 me-n2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSearchOffcanvas" aria-controls="mobileSearchOffcanvas">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Desktop Logo -->
                <a class="navbar-brand me-lg-4 d-none d-lg-block" href="/">
                    <img src="/assets/kingshop/assets/images/logo/logo-1-2048x523.webp" alt="King ASIC Miner" style="max-height: 45px;">
                </a>

                <!-- Desktop Navigation & Tools -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="d-flex flex-wrap align-items-center w-100">
                        <!-- Navigation -->
                        <ul class="navbar-nav flex-wrap align-items-lg-center" style="flex: 1 1 55%; min-width: 400px;">
                            <li class="nav-item">
                                <a class="nav-link px-2 px-xl-3" href="{{ route('public.home') }}">Accueil</a>
                            </li>
                            <li class="nav-item dropdown dropdown-fullwidth">
                                <a class="nav-link dropdown-toggle px-2 px-xl-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Magasin de mineur
                                </a>
                                <div class="dropdown-menu pb-0">
                                    <div class="row p-2 p-lg-4">
                                        <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                            <h6 class="text-primary ps-3">Dairy, Bread & Eggs</h6>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Butter</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Milk Drinks</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Curd & Yogurt</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Eggs</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Buns & Bakery</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Cheese</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Condensed Milk</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Dairy Products</a>
                                        </div>
                                        <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                            <h6 class="text-primary ps-3">Breakfast & Instant Food</h6>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Breakfast Cereal</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Noodles, Pasta & Soup</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Frozen Veg Snacks</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Frozen Non-Veg Snacks</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Vermicelli</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Instant Mixes</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Batter</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Fruit and Juices</a>
                                        </div>
                                        <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                            <h6 class="text-primary ps-3">Cold Drinks & Juices</h6>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Soft Drinks</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Fruit Juices</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Coldpress</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Water & Ice Cubes</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Soda & Mixers</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Health Drinks</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Herbal Drinks</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Milk Drinks</a>
                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <div class="card border-0">
                                                <img src="/assets/kingshop/assets/images/banner/menu-banner.jpg" alt="Special Offer" class="img-fluid">
                                                <div class="position-absolute ps-6 mt-8">
                                                    <h5 class="mb-0">Dont miss this <br>offer today.</h5>
                                                    <a href="#" class="btn btn-primary btn-sm mt-3">Shop Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown dropdown-fullwidth">
                                <a class="nav-link dropdown-toggle px-2 px-xl-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Solutions minières
                                </a>
                                <div class="dropdown-menu pb-0">
                                    <div class="row p-2 p-lg-4">
                                        <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                            <h6 class="text-primary ps-3">Dairy, Bread & Eggs</h6>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Butter</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Milk Drinks</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Curd & Yogurt</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Eggs</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Buns & Bakery</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Cheese</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Condensed Milk</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Dairy Products</a>
                                        </div>
                                        <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                            <h6 class="text-primary ps-3">Breakfast & Instant Food</h6>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Breakfast Cereal</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Noodles, Pasta & Soup</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Frozen Veg Snacks</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Frozen Non-Veg Snacks</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Vermicelli</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Instant Mixes</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Batter</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Fruit and Juices</a>
                                        </div>
                                        <div class="col-lg-3 col-12 mb-4 mb-lg-0">
                                            <h6 class="text-primary ps-3">Cold Drinks & Juices</h6>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Soft Drinks</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Fruit Juices</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Coldpress</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Water & Ice Cubes</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Soda & Mixers</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Health Drinks</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Herbal Drinks</a>
                                            <a class="dropdown-item" href="pages/shop-grid.html">Milk Drinks</a>
                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <div class="card border-0">
                                                <img src="/assets/kingshop/assets/images/banner/menu-banner.jpg" alt="Special Offer" class="img-fluid">
                                                <div class="position-absolute ps-6 mt-8">
                                                    <h5 class="mb-0">Dont miss this <br>offer today.</h5>
                                                    <a href="#" class="btn btn-primary btn-sm mt-3">Shop Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 px-xl-3" href="{{ route('public.hosting.index') }}">Hebergement</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle px-2 px-xl-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Société
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('public.company.about') }}">À propos de nous</a></li>
                                    <li><a class="dropdown-item" href="{{ route('public.company.staff-authentification') }}">Authentification du personnel</a></li>
                                    <li><a class="dropdown-item" href="{{ route('public.company.fraud-prevention') }}">Prévention de la fraude</a></li>
                                    <li><a class="dropdown-item" href="{{ route('public.company.faq') }}">Questions fréquentes</a></li>
                                    <li><a class="dropdown-item" href="{{ route('public.company.blog') }}">Actualités et Événements</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 px-xl-3" href="{{ route('public.bulk-order.index') }}">Commande en vrac</a>
                            </li>
                        </ul>

                        <!-- Search Bar -->
                        <form action="#" class="d-none d-lg-block me-2" style="flex: 0 1 20%; min-width: 180px; max-width: 280px;">
                            <div class="input-group input-group-sm">
                                <input class="form-control border-end-0" type="search" placeholder="Rechercher">
                                <button class="btn btn-outline-secondary border-start-0 bg-white px-2" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </button>
                            </div>
                        </form>

                        <!-- Account & Cart Icons -->
                        <div class="d-none d-lg-flex align-items-center" style="flex: 0 0 auto; min-width: 120px;">
                            <a href="#!" class="text-muted me-3" data-bs-toggle="offcanvas" data-bs-target="#accountOffcanvas" aria-controls="accountOffcanvas">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </a>
                            <a class="d-flex align-items-center text-muted text-decoration-none"
                               data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" href="#!" aria-controls="offcanvasRight" style="white-space: nowrap;">
                                <div class="position-relative me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success cart-count" style="font-size: 0.65rem;">0</span>
                                </div>
                                <span class="fw-semibold small cart-total">$0.00</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="navbar-default" aria-labelledby="navbar-defaultLabel">
        <div class="offcanvas-header">
            <a href="/">
                <img src="/assets/kingshop/assets/images/logo/logo-1-2048x523.webp" alt="King ASIC Miner" style="max-height: 40px;">
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Mobile Search -->
            <div class="mb-4">
                <form action="#">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Rechercher des produits">
                        <button class="btn btn-outline-secondary" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            <!-- Mobile Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('public.home') }}">Accueil</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Magasin de mineur
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="pages/shop-grid.html">Butter</a></li>
                        <li><a class="dropdown-item" href="pages/shop-grid.html">Milk Drinks</a></li>
                        <li><a class="dropdown-item" href="pages/shop-grid.html">Curd & Yogurt</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Solutions minières
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="pages/shop-grid.html">Item 1</a></li>
                        <li><a class="dropdown-item" href="pages/shop-grid.html">Item 2</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('public.hosting.index') }}">Hebergement</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Société
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('public.company.about') }}">À propos de nous</a></li>
                        <li><a class="dropdown-item" href="{{ route('public.company.staff-authentification') }}">Authentification du personnel</a></li>
                        <li><a class="dropdown-item" href="{{ route('public.company.fraud-prevention') }}">Prévention de la fraude</a></li>
                        <li><a class="dropdown-item" href="{{ route('public.company.faq') }}">Questions fréquentes</a></li>
                        <li><a class="dropdown-item" href="{{ route('public.company.blog') }}">Actualités et Événements</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('public.bulk-order.index') }}">Commande en vrac</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Account Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="accountOffcanvas" aria-labelledby="accountOffcanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="accountOffcanvasLabel">Mon Compte</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            @guest
                <!-- Non connecté -->
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <h5 class="mb-1">Bienvenue!</h5>
                    <p class="text-muted small">Connectez-vous pour accéder à votre compte</p>
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('auth.login.view') }}" class="btn btn-primary">Se connecter</a>
                    <a href="{{ route('auth.register.view') }}" class="btn btn-outline-primary">Créer un compte</a>
                </div>
                <hr class="my-4">
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('public.company.faq') }}" class="text-decoration-none text-dark d-flex align-items-center py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 16v-4"></path>
                            <path d="M12 8h.01"></path>
                        </svg>
                        Aide & Support
                    </a></li>
                </ul>
            @else
                <!-- Connecté -->
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <h5 class="mb-1">Bonjour, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</h5>
                    <p class="text-muted small">{{ Auth::user()->email }}</p>
                </div>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-dark d-flex align-items-center py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        Tableau de bord
                    </a></li>
                    <li class="mb-2"><a href="{{ route('customer.orders.index') }}" class="text-decoration-none text-dark d-flex align-items-center py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        Mes Commandes
                    </a></li>
                    <li class="mb-2"><a href="{{ route('customer.address.index') }}" class="text-decoration-none text-dark d-flex align-items-center py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        Mes Adresses
                    </a></li>
                    <li class="mb-2"><a href="{{ route('customer.profil.index') }}" class="text-decoration-none text-dark d-flex align-items-center py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Mon Profil
                    </a></li>
                    <li class="mb-2"><a href="{{ route('customer.password.edit') }}" class="text-decoration-none text-dark d-flex align-items-center py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M12 1v6m0 6v6m6.36-15.36l-4.24 4.24m-4.24 4.24l-4.24 4.24m15.84-4.24l-4.24-4.24m-4.24-4.24l-4.24-4.24"></path>
                        </svg>
                        Paramètres
                    </a></li>
                    <li class="mb-2"><a href="{{ route('public.company.faq') }}" class="text-decoration-none text-dark d-flex align-items-center py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 16v-4"></path>
                            <path d="M12 8h.01"></path>
                        </svg>
                        Aide & Support
                    </a></li>
                </ul>
                <hr class="my-4">
                <div class="d-grid">
                    <a href="{{ route('auth.logout.view') }}" class="btn btn-outline-danger w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Se déconnecter
                    </a>
                </div>
            @endguest
        </div>
    </div>

    <!-- Mobile Search Offcanvas -->
    <div class="offcanvas offcanvas-top" tabindex="-1" id="mobileSearchOffcanvas" aria-labelledby="mobileSearchOffcanvasLabel" style="height: auto;">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="mobileSearchOffcanvasLabel">Rechercher</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="#" class="mb-3">
                <div class="input-group input-group-lg">
                    <input class="form-control" type="search" placeholder="Rechercher des produits" autofocus>
                    <button class="btn btn-primary" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>
            </form>
            <div class="text-muted small">
                <p class="mb-1">Recherches populaires:</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#" class="badge bg-light text-dark text-decoration-none">Bitcoin Miner</a>
                    <a href="#" class="badge bg-light text-dark text-decoration-none">Antminer S19</a>
                    <a href="#" class="badge bg-light text-dark text-decoration-none">Dogecoin</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Navigation Bar -->
    <div class="fixed-bottom bg-white border-top d-lg-none" style="z-index: 1030;">
        <div class="container-fluid">
            <div class="row g-0">
                <!-- Home -->
                <div class="col text-center py-2">
                    <a href="/" class="d-flex flex-column align-items-center text-decoration-none text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22" fill="white"></polyline>
                        </svg>
                        <span class="small mt-1" style="font-size: 0.7rem;">Home</span>
                    </a>
                </div>

                <!-- Boutique -->
                <div class="col text-center py-2">
                    <a href="pages/shop-grid.html" class="d-flex flex-column align-items-center text-decoration-none text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 7H4L2 3h20l-2 4z"></path>
                            <path d="M20 7v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7"></path>
                            <rect x="9" y="11" width="6" height="6" fill="white"></rect>
                        </svg>
                        <span class="small mt-1" style="font-size: 0.7rem;">Boutique</span>
                    </a>
                </div>

                <!-- Bénéfice -->
                <div class="col text-center py-2">
                    <a href="#" class="d-flex flex-column align-items-center text-decoration-none text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"></path>
                            <path d="M9 12l2 2 4-4" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span class="small mt-1" style="font-size: 0.7rem;">Bénéfice</span>
                    </a>
                </div>

                <!-- Compte -->
                <div class="col text-center py-2">
                    <a href="#" data-bs-toggle="offcanvas" data-bs-target="#accountOffcanvas" class="d-flex flex-column align-items-center text-decoration-none text-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="8" r="5"></circle>
                            <path d="M3 21v-2a7 7 0 0 1 7-7h4a7 7 0 0 1 7 7v2z"></path>
                        </svg>
                        <span class="small mt-1" style="font-size: 0.7rem;">Compte</span>
                    </a>
                </div>

                <!-- WhatsApp / Contact -->
                <div class="col text-center py-2">
                    <a href="https://wa.me/1234567890" target="_blank" class="d-flex flex-column align-items-center text-decoration-none text-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9 12h6M12 9l3 3-3 3" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span class="small mt-1" style="font-size: 0.7rem;">Contact</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>