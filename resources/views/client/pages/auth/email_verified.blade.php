@extends('client.components.app')

@section('page_title', 'Vérification email - King ASIC Miner')

@section('content')

<!-- section -->
<section class="my-lg-14 my-8">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row justify-content-center align-items-center">
      <div class="col-12 col-md-6 col-lg-5">
        <div class="text-center">
          <img src="/assets/kingshop/assets/images/svg-graphics/signin-g.svg" alt="" class="img-fluid mb-4" style="max-width: 200px;">
          <h1 class="mb-3 h2 fw-bold">Veuillez vérifier votre e-mail !</h1>
          <p class="mb-4">Un e-mail a été envoyé à votre adresse e-mail. Veuillez cliquer sur le lien inclus pour vérifier votre adresse email.</p>
          <a href="{{ route('auth.login.view') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Retourner à la page de connexion
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
