@extends('client.pages.account.layouts.base')

@section('page_title') Tableau de bord @endsection

@section('content')
<div class="py-6 p-md-6 p-lg-10">
    <!-- heading -->
    <h2 class="mb-6">Tableau de bord</h2>

    <!-- Row -->
    <div class="row g-4">
        <!-- Col -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h4 class="mb-0">Commandes</h4>
                        </div>
                        <div>
                            <i class="bi bi-box-seam text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-3">12</h2>
                    <div>
                        <a href="{{ route('customer.orders.index') }}" class="text-decoration-none">
                            Voir toutes les commandes <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Col -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h4 class="mb-0">Adresses</h4>
                        </div>
                        <div>
                            <i class="bi bi-geo-alt text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-3">2</h2>
                    <div>
                        <a href="{{ route('customer.address.index') }}" class="text-decoration-none">
                            Gérer les adresses <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Col -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h4 class="mb-0">Points de fidélité</h4>
                        </div>
                        <div>
                            <i class="bi bi-star text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-3">450</h2>
                    <div>
                        <span class="text-muted">Points disponibles</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="mt-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Commandes récentes</h3>
            <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-outline-primary">
                Voir tout
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>Commande</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#14899</td>
                        <td>March 5, 2023</td>
                        <td><span class="badge bg-warning">En traitement</span></td>
                        <td>$150.00</td>
                        <td>
                            <a href="{{ route('customer.orders.show', 14899) }}" class="btn btn-sm btn-outline-primary">
                                Voir
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>#14658</td>
                        <td>July 9, 2023</td>
                        <td><span class="badge bg-success">Livrée</span></td>
                        <td>$450.00</td>
                        <td>
                            <a href="{{ route('customer.orders.show', 14658) }}" class="btn btn-sm btn-outline-primary">
                                Voir
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h3 class="mb-4">Actions rapides</h3>
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('customer.profil.index') }}" class="card text-center border-0 shadow-sm text-decoration-none">
                    <div class="card-body p-4">
                        <i class="bi bi-person text-primary" style="font-size: 2rem;"></i>
                        <h5 class="mt-3 mb-0">Mon profil</h5>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('customer.address.index') }}" class="card text-center border-0 shadow-sm text-decoration-none">
                    <div class="card-body p-4">
                        <i class="bi bi-geo-alt text-success" style="font-size: 2rem;"></i>
                        <h5 class="mt-3 mb-0">Mes adresses</h5>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('customer.password.edit') }}" class="card text-center border-0 shadow-sm text-decoration-none">
                    <div class="card-body p-4">
                        <i class="bi bi-lock text-warning" style="font-size: 2rem;"></i>
                        <h5 class="mt-3 mb-0">Mot de passe</h5>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('public.help-center.index') }}" class="card text-center border-0 shadow-sm text-decoration-none">
                    <div class="card-body p-4">
                        <i class="bi bi-question-circle text-info" style="font-size: 2rem;"></i>
                        <h5 class="mt-3 mb-0">Aide</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
