@extends('client.components.app')

@section('page_title', 'Article d\'Aide - King - Fournisseur d\'Or de Mineurs ASIC')

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
                        <li class="breadcrumb-item active" aria-current="page">Article</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Articles similaires</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none">Article similaire 1</a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none">Article similaire 2</a>
                            </li>
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none">Article similaire 3</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Article Content -->
            <div class="col-lg-9">
                <div class="mb-6">
                    <h1 class="fw-bold mb-4">Titre de l'article</h1>
                    <div class="mb-4">
                        <span class="text-muted">Dernière mise à jour: {{ date('d/m/Y') }}</span>
                    </div>

                    <div class="article-content">
                        <p>Contenu de l'article d'aide...</p>

                        <h2 class="h4 mt-5 mb-3">Section 1</h2>
                        <p>Contenu de la section...</p>

                        <h2 class="h4 mt-5 mb-3">Section 2</h2>
                        <p>Contenu de la section...</p>
                    </div>

                    <!-- Was this helpful? -->
                    <div class="card mt-6 bg-light">
                        <div class="card-body text-center p-5">
                            <h4 class="mb-3">Cet article vous a-t-il été utile ?</h4>
                            <div class="d-flex gap-3 justify-content-center">
                                <button class="btn btn-outline-success">
                                    <i class="bi bi-hand-thumbs-up"></i> Oui
                                </button>
                                <button class="btn btn-outline-danger">
                                    <i class="bi bi-hand-thumbs-down"></i> Non
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Support -->
                    <div class="mt-5">
                        <p class="text-muted">Besoin d'aide supplémentaire ? <a href="{{ route('public.company.contact') }}">Contactez notre support</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
