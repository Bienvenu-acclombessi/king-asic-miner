@extends('client.components.app')

@section('page_title', 'Recherche - Centre d\'Aide - King - Fournisseur d\'Or de Mineurs ASIC')

@section('content')
<section class="mt-8 mb-lg-14 mb-8">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('public.help-center.index') }}">Centre d'Aide</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Recherche</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Search Header -->
        <div class="row">
            <div class="col-12">
                <div class="mb-6">
                    <h1 class="fw-bold mb-4">Résultats de recherche</h1>
                    @if($query)
                        <p class="lead">Résultats pour : <strong>{{ $query }}</strong></p>
                    @endif

                    <!-- Search Bar -->
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <form action="{{ route('public.help-center.search') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="q" value="{{ $query }}" placeholder="Rechercher dans l'aide..." required>
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

        <!-- Search Results -->
        <div class="row">
            <div class="col-lg-8">
                @if($query)
                    <div class="mb-4">
                        <p class="text-muted">Environ X résultats trouvés</p>
                    </div>

                    <!-- Result Item -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-2">
                                <a href="{{ route('public.help-center.article', 'exemple') }}" class="text-decoration-none">
                                    Titre de l'article correspondant
                                </a>
                            </h5>
                            <p class="text-muted mb-2">
                                <small>Catégorie: Démarrage</small>
                            </p>
                            <p class="mb-0">
                                Extrait de l'article contenant les termes recherchés...
                            </p>
                        </div>
                    </div>

                    <!-- Another Result -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-2">
                                <a href="{{ route('public.help-center.article', 'exemple-2') }}" class="text-decoration-none">
                                    Autre article correspondant
                                </a>
                            </h5>
                            <p class="text-muted mb-2">
                                <small>Catégorie: Commandes & Livraison</small>
                            </p>
                            <p class="mb-0">
                                Extrait de l'article contenant les termes recherchés...
                            </p>
                        </div>
                    </div>

                    <!-- No Results Message (if needed) -->
                    {{--
                    <div class="card bg-light">
                        <div class="card-body text-center p-5">
                            <i class="bi bi-search" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 mb-3">Aucun résultat trouvé</h4>
                            <p class="text-muted mb-4">Essayez avec des termes différents ou parcourez nos catégories</p>
                            <a href="{{ route('public.help-center.index') }}" class="btn btn-primary">
                                Retour au centre d'aide
                            </a>
                        </div>
                    </div>
                    --}}
                @else
                    <div class="card bg-light">
                        <div class="card-body text-center p-5">
                            <h4 class="mb-3">Veuillez entrer un terme de recherche</h4>
                            <a href="{{ route('public.help-center.index') }}" class="btn btn-primary">
                                Retour au centre d'aide
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-4">Articles populaires</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'populaire-1') }}" class="text-decoration-none">
                                    Article populaire 1
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'populaire-2') }}" class="text-decoration-none">
                                    Article populaire 2
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.article', 'populaire-3') }}" class="text-decoration-none">
                                    Article populaire 3
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card mt-4 bg-light">
                    <div class="card-body">
                        <h5 class="mb-3">Besoin d'aide ?</h5>
                        <p class="text-muted mb-3">Notre équipe est là pour vous aider</p>
                        <a href="{{ route('public.company.contact') }}" class="btn btn-primary w-100">
                            Nous contacter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
