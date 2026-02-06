@extends('client.components.app')

@section('page_title', 'Commande en Gros - King - Fournisseur d\'Or de Mineurs ASIC')

@section('content')
<section class="mt-8 mb-lg-14 mb-8">
    <div class="container">
        <!-- Row -->
        <div class="row">
            <div class="col-12">
                <div class="text-center mb-8">
                    <h1 class="fw-bold">Commande en Gros</h1>
                    <p class="lead">Bénéficiez de tarifs préférentiels pour vos commandes en volume</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card mb-6">
                    <div class="card-body p-6">
                        <h2 class="h4 mb-4">Avantages des commandes en gros</h2>
                        <ul class="mb-4">
                            <li>Tarifs dégressifs selon le volume</li>
                            <li>Support dédié</li>
                            <li>Délais de livraison garantis</li>
                            <li>Options de paiement flexibles</li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-6">
                        <h2 class="h4 mb-4">Demande de devis</h2>
                        <form>
                            <div class="mb-3">
                                <label for="companyName" class="form-label">Nom de l'entreprise</label>
                                <input type="text" class="form-control" id="companyName" required>
                            </div>
                            <div class="mb-3">
                                <label for="contactName" class="form-label">Nom du contact</label>
                                <input type="text" class="form-control" id="contactName" required>
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
                                <label for="product" class="form-label">Produit souhaité</label>
                                <select class="form-control" id="product" required>
                                    <option value="">Sélectionnez un produit</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantité</label>
                                <input type="number" class="form-control" id="quantity" min="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="4"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Demander un devis</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
