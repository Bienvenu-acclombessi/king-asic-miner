<section class="my-lg-14 my-8">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Mineurs de la semaine</h3>
            <a href="{{ route('public.shop.index') }}" class="btn btn-primary rounded">Voir toutes les ressources</a>
        </div>

        <div class="row g-4 row-cols-lg-5 row-cols-2 row-cols-md-3">
            @forelse($products ?? [] as $product)
                <div class="col">
                    @include('client.pages.accueil.partials.product_card', ['product' => $product])
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <p class="mb-0">Aucun produit disponible pour le moment.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>