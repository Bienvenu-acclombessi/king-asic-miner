@extends('client.components.app')

@section('page_title', 'Catégorie d\'Aide - King - Fournisseur d\'Or de Mineurs ASIC')

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
                        <li class="breadcrumb-item active" aria-current="page">Catégorie</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="mb-6">
                    <h1 class="fw-bold mb-3">Nom de la Catégorie</h1>
                    <p class="lead text-muted">Description de la catégorie</p>
                </div>
            </div>
        </div>

        <!-- Articles List -->
        <div class="row">
            <div class="col-lg-8">
                <div class="list-group">
                    <a href="{{ route('public.help-center.article', 'article-1') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Titre de l'article 1</h5>
                        </div>
                        <p class="mb-1 text-muted">Description courte de l'article...</p>
                    </a>

                    <a href="{{ route('public.help-center.article', 'article-2') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Titre de l'article 2</h5>
                        </div>
                        <p class="mb-1 text-muted">Description courte de l'article...</p>
                    </a>

                    <a href="{{ route('public.help-center.article', 'article-3') }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Titre de l'article 3</h5>
                        </div>
                        <p class="mb-1 text-muted">Description courte de l'article...</p>
                    </a>
                </div>

                <!-- Back to Help Center -->
                <div class="mt-5">
                    <a href="{{ route('public.help-center.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Retour au centre d'aide
                    </a>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-4">Autres catégories</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.category', 'autre-categorie-1') }}" class="text-decoration-none">
                                    Autre catégorie 1
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('public.help-center.category', 'autre-categorie-2') }}" class="text-decoration-none">
                                    Autre catégorie 2
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
