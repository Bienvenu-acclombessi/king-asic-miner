@extends('client.components.app')

@section('page_title', 'Lien envoye - King ASIC Miner')

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
            <i class="bi bi-envelope-check-fill text-primary" style="font-size: 5rem;"></i>
          </div>
          <h1 class="mb-3 h2 fw-bold">Verifiez votre e-mail!</h1>
          <p class="mb-4">Un e-mail contenant un lien de reinitialisation a ete envoye a votre adresse e-mail. Veuillez cliquer sur le lien pour reinitialiser votre mot de passe.</p>
          <a href="{{ route('auth.login.view') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Retourner a la page de connexion
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
