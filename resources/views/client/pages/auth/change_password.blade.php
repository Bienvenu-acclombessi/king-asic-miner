@extends('client.components.app')

@section('page_title', 'Nouveau mot de passe - King ASIC Miner')

@section('content')

<!-- section -->
<section class="my-lg-14 my-8">
  <!-- container -->
  <div class="container">
    <!-- row -->
    <div class="row justify-content-center align-items-center">
      <div class="col-12 col-md-6 col-lg-4 order-lg-1 order-2">
        <!-- img -->
        <img src="/assets/kingshop/assets/images/svg-graphics/fp-g.svg" alt="" class="img-fluid">
      </div>
      <div class="col-12 col-md-6 offset-lg-1 col-lg-4 order-lg-2 order-1 d-flex align-items-center">
        <div class="w-100">
          <div class="mb-lg-9 mb-5">
            <!-- heading -->
            <h1 class="mb-2 h2 fw-bold">Nouveau mot de passe</h1>
            <p>Entrez votre nouveau mot de passe ci-dessous.</p>
          </div>

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <!-- form -->
          <form action="{{ route('auth.password_change.post') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <!-- row -->
            <div class="row g-3">
              <!-- col -->
              <div class="col-12">
                <div class="password-field position-relative">
                  <input type="password" name="password" id="newPassword" placeholder="Nouveau mot de passe" class="form-control" required>
                  <span><i id="passwordToggler" class="bi bi-eye-slash"></i></span>
                </div>
              </div>
              <div class="col-12">
                <div class="password-field position-relative">
                  <input type="password" name="password2" id="confirmPassword" placeholder="Confirmer le mot de passe" class="form-control" required>
                  <span><i id="passwordToggler2" class="bi bi-eye-slash"></i></span>
                </div>
              </div>

              <!-- btn -->
              <div class="col-12 d-grid gap-2">
                <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                <a href="{{ route('auth.login.view') }}" class="btn btn-light">Annuler</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle pour le nouveau mot de passe
    const togglePassword = document.getElementById('passwordToggler');
    const newPassword = document.getElementById('newPassword');

    if (togglePassword && newPassword) {
        togglePassword.addEventListener('click', function() {
            const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            newPassword.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    }

    // Toggle pour la confirmation du mot de passe
    const togglePassword2 = document.getElementById('passwordToggler2');
    const confirmPassword = document.getElementById('confirmPassword');

    if (togglePassword2 && confirmPassword) {
        togglePassword2.addEventListener('click', function() {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    }
});
</script>
@endsection
