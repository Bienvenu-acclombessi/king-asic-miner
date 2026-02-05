
<!-- Hero Section -->
<section class="hero-section position-relative" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1639762681485-074b7f938ba0?w=1920') center/cover; min-height: 500px;">
    <div class="container h-100">
        <div class="row h-100 align-items-center" style="min-height: 500px;">
            <div class="col-lg-7">
                <div class="hero-content text-white py-5">
                    <h1 class="display-4 fw-bold mb-4" style="font-size: 3.5rem;">FOURNISSEUR D'OR APEXTO</h1>
                    <p class="lead mb-4" style="font-size: 1.5rem;">POUR TOUS VOS BESOINS MINIERS</p>
                    <p class="mb-4">Apexto est une société de crypto-monnaie offshore spécialisée dans l'entrepreneuriat et la blockchain. Préparez-vous à en bénéficier !</p>
                    <a href="#" class="btn btn-success btn-lg px-5 py-3 fw-semibold">Commencer l'achat</a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <div class="hero-product-image">
                    <i class="bi bi-hdd-rack-fill text-white" style="font-size: 12rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search/Filter Bar -->
<section class="search-filter-bar py-4 bg-light border-bottom">
    <div class="container">
        <div class="row g-3 align-items-center">
            <div class="col-lg-3 col-md-6">
                <select class="form-select">
                    <option>Toutes les catégories</option>
                    <option>Bitcoin Miners</option>
                    <option>Litecoin Miners</option>
                    <option>Ethereum Miners</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <select class="form-select">
                    <option>Toutes les marques</option>
                    <option>Bitmain</option>
                    <option>Whatsminer</option>
                    <option>IceRiver</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <select class="form-select">
                    <option>Algorithme</option>
                    <option>SHA-256</option>
                    <option>Scrypt</option>
                    <option>Ethash</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <button class="btn btn-success w-100 fw-semibold">Rechercher</button>
            </div>
        </div>
    </div>
</section>

<!-- Mineurs de la semaine -->
<section class="miners-week py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Mineurs de la semaine</h2>
            <a href="#" class="btn btn-success">Voir tous les mineurs ASIC</a>
        </div>

        <div class="row g-4">
            @for ($i = 0; $i < 5; $i++)
            <div class="col-lg-20p col-md-4 col-sm-6">
                <div class="product-card card h-100 border shadow-sm">
                    <div class="product-image bg-white text-center p-3" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-cpu-fill text-secondary" style="font-size: 4rem;"></i>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-semibold mb-2">Bitmain Antminer S21+ 234 TH/s</h6>
                        <p class="text-muted small mb-1">Mineur Bitcoin SHA-256</p>
                        <p class="text-success small fw-semibold mb-2">En stock</p>
                        <div class="mb-3">
                            <span class="text-dark fw-bold" style="font-size: 1.1rem;">$6,999</span>
                        </div>
                        <button class="btn btn-success w-100 btn-sm">Acheter maintenant</button>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

<!-- Banner Bitmain Antminer L9 -->
<section class="product-banner py-5" style="background: linear-gradient(90deg, #1a1a2e 0%, #16213e 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white">
                <h2 class="fw-bold mb-3">Bitmain Antminer L9</h2>
                <p class="lead mb-4">Mineur Litecoin haute performance - 16 GH/s</p>
                <p class="mb-4">Le mineur Litecoin le plus puissant du marché. Profitabilité maximale avec une efficacité énergétique optimale.</p>
                <a href="#" class="btn btn-success btn-lg">Découvrir maintenant</a>
            </div>
            <div class="col-lg-6 text-center">
                <i class="bi bi-hdd-rack-fill text-white" style="font-size: 10rem; opacity: 0.4;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Excellente offre - Pré-Manjique -->
<section class="excellent-offer py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Excellente offre</h2>
                <p class="text-muted mb-0">Pré-Manjique</p>
            </div>
            <a href="#" class="btn btn-success">Voir tous les produits</a>
        </div>

        <div class="row g-4">
            @for ($i = 0; $i < 4; $i++)
            <div class="col-lg-3 col-md-6">
                <div class="product-card card h-100 border shadow-sm">
                    <div class="product-image bg-white text-center p-3" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-gpu-card text-secondary" style="font-size: 4rem;"></i>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-semibold mb-2">IceRiver KS3M 6 TH/s</h6>
                        <p class="text-muted small mb-1">Mineur Kaspa kHeavyHash</p>
                        <p class="text-success small fw-semibold mb-2">En stock</p>
                        <div class="mb-3">
                            <span class="text-dark fw-bold" style="font-size: 1.1rem;">$8,999</span>
                        </div>
                        <button class="btn btn-success w-100 btn-sm">Acheter maintenant</button>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

<!-- Pourquoi choisir Apexto ? -->
<section class="why-choose py-5">
    <div class="container">
        <h2 class="fw-bold text-center mb-5">Pourquoi choisir Apexto ?</h2>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="benefit-card text-center p-4">
                    <div class="benefit-icon mb-3">
                        <div class="icon-circle d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-star-fill text-white" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-semibold mb-3">FOURNISSEUR PRÉFÉRÉ</h5>
                    <p class="text-muted small">Partenariats directs avec les principaux fabricants garantissant des produits authentiques et des prix compétitifs.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="benefit-card text-center p-4">
                    <div class="benefit-icon mb-3">
                        <div class="icon-circle d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-truck text-white" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-semibold mb-3">LIVRAISON À TEMPS</h5>
                    <p class="text-muted small">Expédition rapide et fiable dans le monde entier. La plupart des commandes sont expédiées sous 24 à 48 heures.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="benefit-card text-center p-4">
                    <div class="benefit-icon mb-3">
                        <div class="icon-circle d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-shield-check text-white" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-semibold mb-3">GARANTIE GARANTIE</h5>
                    <p class="text-muted small">Couverture de garantie complète et remplacement sans tracas pour les unités défectueuses.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="benefit-card text-center p-4">
                    <div class="benefit-icon mb-3">
                        <div class="icon-circle d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-headset text-white" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-semibold mb-3">SUPPORT TECHNIQUE</h5>
                    <p class="text-muted small">Équipe de support experte 24/7 prête à vous aider avec l'installation, le dépannage et l'optimisation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ressources minières Apexto -->
<section class="mining-resources py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold text-center mb-5">Ressources minières Apexto</h2>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="resource-card card border-0 shadow-sm overflow-hidden h-100">
                    <div class="resource-image position-relative" style="height: 250px; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1639762681485-074b7f938ba0?w=800') center/cover;">
                        <div class="position-absolute bottom-0 start-0 p-4 text-white">
                            <h4 class="fw-bold">Mineurs Asic</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="resource-card card border-0 shadow-sm overflow-hidden h-100">
                    <div class="resource-image position-relative" style="height: 250px; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1581092162384-8987c1d64926?w=800') center/cover;">
                        <div class="position-absolute bottom-0 start-0 p-4 text-white">
                            <h4 class="fw-bold">Salle de bain pour refroidissement immersif</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="resource-card card border-0 shadow-sm overflow-hidden h-100">
                    <div class="resource-image position-relative" style="height: 250px; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1558346490-a72e53ae2d4f?w=800') center/cover;">
                        <div class="position-absolute bottom-0 start-0 p-4 text-white">
                            <h4 class="fw-bold">Installation de processus miniers</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="resource-card card border-0 shadow-sm overflow-hidden h-100">
                    <div class="resource-image position-relative" style="height: 250px; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=800') center/cover;">
                        <div class="position-absolute bottom-0 start-0 p-4 text-white">
                            <h4 class="fw-bold">Méthodes pour l'hydroélectricité</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rencontrez notre équipe -->
<section class="team py-5">
    <div class="container">
        <h2 class="fw-bold text-center mb-2">Rencontrez notre équipe</h2>
        <p class="text-center text-muted mb-5">Des professionnels dévoués à votre service d'exploitation minière</p>

        <div class="row g-4">
            @php
                $teamMembers = [
                    ['name' => 'Faye Phong', 'role' => 'Directrice des ventes'],
                    ['name' => 'Selina Xu', 'role' => 'Responsable marketing'],
                    ['name' => 'David Chen', 'role' => 'Support technique'],
                    ['name' => 'Lisa Wang', 'role' => 'Service client'],
                    ['name' => 'Mike Zhang', 'role' => 'Expert blockchain'],
                    ['name' => 'Anna Lee', 'role' => 'Gestionnaire de comptes']
                ];
            @endphp

            @foreach($teamMembers as $member)
            <div class="col-lg-2 col-md-4 col-6">
                <div class="team-card card border-0 shadow-sm text-center">
                    <div class="team-image bg-light" style="height: 180px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-person-circle text-secondary" style="font-size: 5rem;"></i>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="fw-semibold mb-1">{{ $member['name'] }}</h6>
                        <p class="text-muted small mb-2">{{ $member['role'] }}</p>
                        <div class="social-links d-flex justify-content-center gap-2">
                            <a href="#" class="text-primary"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="text-info"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="text-success"><i class="bi bi-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Partenaires YouTube -->
<section class="youtube-partners py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold text-center mb-5">Partenaires YouTube</h2>

        <div class="row g-4">
            @for ($i = 0; $i < 5; $i++)
            <div class="col-lg-20p col-md-4 col-sm-6">
                <div class="video-card card border-0 shadow-sm overflow-hidden">
                    <div class="video-thumbnail position-relative bg-dark" style="height: 180px;">
                        <i class="bi bi-play-circle-fill text-white position-absolute top-50 start-50 translate-middle" style="font-size: 3rem; opacity: 0.8;"></i>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="fw-semibold mb-1 small">Tutoriel Mining Bitcoin 2024</h6>
                        <p class="text-muted small mb-0">50K vues</p>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

<!-- Mines à jour quotidiennes d'Apexto -->
<section class="daily-updates py-5">
    <div class="container">
        <h2 class="fw-bold text-center mb-5">Mines à jour quotidiennes d'Apexto</h2>

        <div class="row g-4">
            @for ($i = 0; $i < 5; $i++)
            <div class="col-lg-20p col-md-4 col-sm-6">
                <div class="video-card card border-0 shadow-sm overflow-hidden">
                    <div class="video-thumbnail position-relative bg-secondary" style="height: 180px;">
                        <i class="bi bi-play-circle-fill text-white position-absolute top-50 start-50 translate-middle" style="font-size: 3rem; opacity: 0.8;"></i>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="fw-semibold mb-1 small">Nouveautés Mining {{ date('Y') }}</h6>
                        <p class="text-muted small mb-0">35K vues</p>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

<!-- Partenaires Mondiaux -->
<section class="global-partners py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold text-center mb-5">Partenaires Mondiaux</h2>

        <div class="row g-4 justify-content-center align-items-center">
            @php
                $brands = ['Antminer', 'IceRiver', 'Whatsminer', 'Goldshell', 'Canaan'];
                $icons = ['bi-building', 'bi-snow', 'bi-water', 'bi-gem', 'bi-tsunami'];
            @endphp

            @foreach($brands as $index => $brand)
            <div class="col-lg-2 col-md-3 col-4">
                <div class="brand-logo-card card border shadow-sm text-center p-4 h-100">
                    <i class="bi {{ $icons[$index] }} text-primary mb-2" style="font-size: 3rem;"></i>
                    <h6 class="fw-semibold mb-0">{{ $brand }}</h6>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Blogs Apexto -->
<section class="blogs py-5">
    <div class="container">
        <h2 class="fw-bold text-center mb-5">Blogs Apexto</h2>

        <div class="row g-4">
            @for ($i = 0; $i < 4; $i++)
            <div class="col-lg-3 col-md-6">
                <div class="blog-card card border-0 shadow-sm h-100">
                    <div class="blog-image bg-light" style="height: 200px;"></div>
                    <div class="card-body">
                        <span class="badge bg-success mb-2">Mining Tips</span>
                        <h6 class="fw-semibold mb-2">Guide complet du mining Bitcoin en {{ date('Y') }}</h6>
                        <p class="text-muted small mb-3">Découvrez les meilleures pratiques pour maximiser vos profits de mining...</p>
                        <a href="#" class="text-success fw-semibold small">Lire plus <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

<!-- Newsletter Section - Abonnez-vous à la chaîne TG -->
<section class="newsletter-section py-5" style="background: linear-gradient(135deg, #25c385 0%, #1a9768 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0 text-white">
                <h3 class="fw-bold mb-3">Abonnez-vous à la chaîne TG</h3>
                <p class="mb-0">Restez informé des dernières offres, des annonces de nouveaux produits et des conseils de mining.</p>
            </div>
            <div class="col-lg-6">
                <form class="newsletter-form">
                    <div class="input-group input-group-lg">
                        <input type="email" class="form-control" placeholder="Entrez votre adresse e-mail">
                        <button class="btn btn-dark fw-semibold px-4" type="submit">S'abonner</button>
                    </div>
                    <small class="d-block mt-2 text-white opacity-75">Nous respectons votre vie privée. Désabonnez-vous à tout moment.</small>
                </form>
            </div>
        </div>
    </div>
</section>