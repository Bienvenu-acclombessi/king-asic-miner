@extends('client.components.app')

@section('page_title', 'Authentification du Personnel - King - Fournisseur d\'Or de Mineurs ASIC')

@section('content')
<section class="mt-8 mb-lg-14 mb-8">
    <div class="container">
        <!-- Row -->
        <div class="row">
            <div class="col-12">
                <div class="text-center mb-8">
                    <h1 class="fw-bold">Authentification du Personnel</h1>
                    <p class="lead">Vérifiez l'identité de notre personnel</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="card">
                    <div class="card-body p-6">
                        <h2 class="h4 mb-4">Vérifier un membre du personnel</h2>
                        <form>
                            <div class="mb-3">
                                <label for="staffCode" class="form-label">Code d'authentification</label>
                                <input type="text" class="form-control" id="staffCode" placeholder="Entrez le code">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Vérifier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
