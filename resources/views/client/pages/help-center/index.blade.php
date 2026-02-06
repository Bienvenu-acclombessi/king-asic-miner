@extends('client.components.app')

@section('page_title', 'Centre d\'Aide - King - Fournisseur d\'Or de Mineurs ASIC')

@section('content')
<section class="mt-8 mb-lg-14 mb-8">
    <div class="container">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="text-center mb-8">
                    <h1 class="fw-bold mb-3">Centre d'Aide</h1>
                    <p class="lead mb-5">Comment pouvons-nous vous aider aujourd'hui ?</p>

                    <!-- Search Bar -->
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <form action="{{ route('public.help-center.search') }}" method="GET">
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control" name="q" placeholder="Rechercher dans l'aide..." required>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-search"></i> Rechercher
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="row g-4">
            <!-- Getting Started -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 card-lift">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="bi bi-rocket-takeoff text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h3 class="h4 mb-3">Démarrage</h3>
                        <p class="text-muted mb-4">Tout ce que vous devez savoir pour commencer</p>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'comment-commander') }}" class="text-decoration-none">
                                    Comment passer une commande
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'creer-compte') }}" class="text-decoration-none">
                                    Créer un compte
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'moyens-paiement') }}" class="text-decoration-none">
                                    Moyens de paiement
                                </a>
                            </li>
                        </ul>
                        <a href="{{ route('public.help-center.category', 'demarrage') }}" class="btn btn-outline-primary btn-sm">
                            Voir tout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Orders & Shipping -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 card-lift">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="bi bi-box-seam text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h3 class="h4 mb-3">Commandes & Livraison</h3>
                        <p class="text-muted mb-4">Suivez vos commandes et livraisons</p>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'suivre-commande') }}" class="text-decoration-none">
                                    Suivre ma commande
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'delais-livraison') }}" class="text-decoration-none">
                                    Délais de livraison
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'frais-livraison') }}" class="text-decoration-none">
                                    Frais de livraison
                                </a>
                            </li>
                        </ul>
                        <a href="{{ route('public.help-center.category', 'commandes-livraison') }}" class="btn btn-outline-primary btn-sm">
                            Voir tout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Returns & Warranty -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 card-lift">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="bi bi-shield-check text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h3 class="h4 mb-3">Retours & Garantie</h3>
                        <p class="text-muted mb-4">Politique de retour et garantie produits</p>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'politique-retour') }}" class="text-decoration-none">
                                    Politique de retour
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'garantie-produits') }}" class="text-decoration-none">
                                    Garantie des produits
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'retourner-produit') }}" class="text-decoration-none">
                                    Comment retourner un produit
                                </a>
                            </li>
                        </ul>
                        <a href="{{ route('public.help-center.category', 'retours-garantie') }}" class="btn btn-outline-primary btn-sm">
                            Voir tout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Products & Technical -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 card-lift">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="bi bi-cpu text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h3 class="h4 mb-3">Produits & Technique</h3>
                        <p class="text-muted mb-4">Informations techniques sur nos produits</p>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'choisir-mineur') }}" class="text-decoration-none">
                                    Choisir le bon mineur
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'installation-mineur') }}" class="text-decoration-none">
                                    Installation d'un mineur
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'specifications-techniques') }}" class="text-decoration-none">
                                    Spécifications techniques
                                </a>
                            </li>
                        </ul>
                        <a href="{{ route('public.help-center.category', 'produits-technique') }}" class="btn btn-outline-primary btn-sm">
                            Voir tout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account & Security -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 card-lift">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="bi bi-person-lock text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h3 class="h4 mb-3">Compte & Sécurité</h3>
                        <p class="text-muted mb-4">Gérez votre compte en toute sécurité</p>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'modifier-mot-de-passe') }}" class="text-decoration-none">
                                    Modifier mon mot de passe
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'securiser-compte') }}" class="text-decoration-none">
                                    Sécuriser mon compte
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'gerer-adresses') }}" class="text-decoration-none">
                                    Gérer mes adresses
                                </a>
                            </li>
                        </ul>
                        <a href="{{ route('public.help-center.category', 'compte-securite') }}" class="btn btn-outline-primary btn-sm">
                            Voir tout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Payment & Billing -->
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 card-lift">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="bi bi-credit-card text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h3 class="h4 mb-3">Paiement & Facturation</h3>
                        <p class="text-muted mb-4">Questions sur les paiements</p>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'moyens-paiement-acceptes') }}" class="text-decoration-none">
                                    Moyens de paiement acceptés
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'securite-paiement') }}" class="text-decoration-none">
                                    Sécurité des paiements
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'obtenir-facture') }}" class="text-decoration-none">
                                    Obtenir une facture
                                </a>
                            </li>
                        </ul>
                        <a href="{{ route('public.help-center.category', 'paiement-facturation') }}" class="btn btn-outline-primary btn-sm">
                            Voir tout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact CTA -->
        <div class="row mt-8">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body p-6 text-center">
                        <h3 class="h4 mb-3">Vous n'avez pas trouvé ce que vous cherchez ?</h3>
                        <p class="text-muted mb-4">Notre équipe de support est là pour vous aider</p>
                        <a href="{{ route('public.company.contact') }}" class="btn btn-primary">
                            <i class="bi bi-envelope"></i> Nous contacter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
