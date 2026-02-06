@extends('client.components.app')

@section('page_title', 'Politique des Cookies - King - Fournisseur d\'Or de Mineurs ASIC')

@section('content')
<section class="mt-8 mb-lg-14 mb-8">
    <div class="container">
        <!-- Row -->
        <div class="row">
            <div class="col-12">
                <div class="text-center mb-8">
                    <h1 class="fw-bold">Politique des Cookies</h1>
                    <p class="text-muted">Dernière mise à jour: {{ date('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="mb-6">
                    <h2 class="h4 mb-3">Qu'est-ce qu'un cookie ?</h2>
                    <p>Un cookie est un petit fichier texte stocké sur votre ordinateur...</p>
                </div>

                <div class="mb-6">
                    <h2 class="h4 mb-3">Comment utilisons-nous les cookies ?</h2>
                    <p>Nous utilisons les cookies pour améliorer votre expérience sur notre site...</p>
                </div>

                <div class="mb-6">
                    <h2 class="h4 mb-3">Types de cookies utilisés</h2>
                    <ul>
                        <li><strong>Cookies essentiels:</strong> Nécessaires au fonctionnement du site</li>
                        <li><strong>Cookies analytiques:</strong> Pour comprendre comment vous utilisez notre site</li>
                        <li><strong>Cookies marketing:</strong> Pour personnaliser la publicité</li>
                    </ul>
                </div>

                <div class="mb-6">
                    <h2 class="h4 mb-3">Gérer vos cookies</h2>
                    <p>Vous pouvez gérer vos préférences de cookies via les paramètres de votre navigateur...</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
