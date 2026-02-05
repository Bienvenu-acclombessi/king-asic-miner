@extends('client.components.app')

@section('page_title', 'Mot de passe modifie - King ASIC Miner')

@section('content')

<!-- section -->
<section class="my-lg-14 my-8">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row justify-content-center align-items-center">
      <div class="col-12 col-md-6 col-lg-5">
        <div class="text-center">
          <div class="mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
          </div>
          <h1 class="mb-3 h2 fw-bold">Mot de passe modifie avec succes!</h1>
          <p class="mb-4">Votre mot de passe a ete modifie! Vous pouvez desormais vous connecter avec ce nouveau mot de passe.</p>
          <a href="{{ route('auth.login.view') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Retourner se connecter
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
