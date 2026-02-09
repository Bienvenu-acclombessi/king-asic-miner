@extends('client.components.app')

@section('page_title', 'Hébergement de Mineurs - King - Fournisseur d\'Or de Mineurs ASIC')

@section('content')
<section class="mt-8 mb-lg-14 mb-8">
    <div class="container">
        <!-- Row -->
        <div class="row">
            <div class="col-12">
                <div class="text-center mb-8">
                    <h1 class="fw-bold">Hébergement de Mineurs ASIC</h1>
                    <p class="lead">Solutions d'hébergement professionnelles pour vos équipements de minage</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card mb-6">
                    <div class="card-body p-6">
                        <h2 class="h4 mb-4">Pourquoi choisir notre hébergement ?</h2>
                        <ul class="mb-4">
                            <li>Infrastructure optimisée pour le minage</li>
                            <li>Électricité à tarif préférentiel</li>
                            <li>Refroidissement performant</li>
                            <li>Surveillance 24/7</li>
                            <li>Connectivité haute performance</li>
                            <li>Maintenance et support technique</li>
                        </ul>
                    </div>
                </div>

                <div class="card mb-6">
                    <div class="card-body p-6">
                        <h2 class="h4 mb-4">Nos offres d'hébergement</h2>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="border rounded p-4 h-100">
                                    <h5 class="mb-3">Forfait Standard</h5>
                                    <ul class="list-unstyled mb-3">
                                        <li class="mb-2">✓ Jusqu'à 10 mineurs</li>
                                        <li class="mb-2">✓ Électricité incluse</li>
                                        <li class="mb-2">✓ Support par email</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-4 h-100">
                                    <h5 class="mb-3">Forfait Premium</h5>
                                    <ul class="list-unstyled mb-3">
                                        <li class="mb-2">✓ Mineurs illimités</li>
                                        <li class="mb-2">✓ Électricité incluse</li>
                                        <li class="mb-2">✓ Support prioritaire 24/7</li>
                                        <li class="mb-2">✓ Maintenance incluse</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-6">
                        <h2 class="h4 mb-4">Demande d'information</h2>
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="minerCount" class="form-label">Nombre de mineurs</label>
                                <input type="number" class="form-control" id="minerCount" min="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="minerType" class="form-label">Type de mineurs</label>
                                <input type="text" class="form-control" id="minerType" placeholder="Ex: Antminer S19 Pro">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="4"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Demander des informations</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
