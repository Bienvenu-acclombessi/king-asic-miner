@extends('client.components.app')

@section('page_title', 'Conditions Générales - King - Fournisseur d\'Or de Mineurs ASIC')

@section('content')
<section class="mt-8 mb-lg-14 mb-8">
    <div class="container">
        <!-- Row -->
        <div class="row">
            <div class="col-12">
                <div class="text-center mb-8">
                    <h1 class="fw-bold">Conditions Générales d'Utilisation</h1>
                    <p class="text-muted">Dernière mise à jour: {{ date('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="mb-6">
                    <h2 class="h4 mb-3">1. Acceptation des conditions</h2>
                    <p>En utilisant notre site, vous acceptez les présentes conditions générales...</p>
                </div>

                <div class="mb-6">
                    <h2 class="h4 mb-3">2. Compte utilisateur</h2>
                    <p>Pour passer commande, vous devez créer un compte...</p>
                </div>

                <div class="mb-6">
                    <h2 class="h4 mb-3">3. Commandes et paiements</h2>
                    <p>Toutes les commandes sont soumises à disponibilité...</p>
                </div>

                <div class="mb-6">
                    <h2 class="h4 mb-3">4. Livraison</h2>
                    <p>Les délais de livraison sont indicatifs...</p>
                </div>

                <div class="mb-6">
                    <h2 class="h4 mb-3">5. Garantie et retours</h2>
                    <p>Nos produits sont couverts par une garantie...</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
