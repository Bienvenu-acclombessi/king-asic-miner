  <section class="my-lg-14 my-8">
    <!-- container -->
    <div class="container">
      <!-- row -->
      <div class="row justify-content-center align-items-center">
        <div class="col-12 col-md-6 col-lg-4 order-lg-1 order-2">
          <!-- img -->
          <img src="/assets/kingshop/assets/images/svg-graphics/signup-g.svg" alt="" class="img-fluid">
        </div>
        <!-- col -->
        <div class="col-12 col-md-6 offset-lg-1 col-lg-4 order-lg-2 order-1">
          <div class="mb-lg-9 mb-5">
            <h1 class="mb-1 h2 fw-bold">Créer un compte</h1>
            <p>Bienvenue ! Créez votre compte pour commencer vos achats.</p>
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
          <form action="{{ route('auth.register.post') }}" method="POST">
            @csrf
            <div class="row g-3">
              <!-- col -->
              <div class="col">
                <!-- input --><input type="text" class="form-control" name="first_name" placeholder="Nom" aria-label="Nom" value="{{ old('first_name') }}" required>
              </div>
              <div class="col">
                <!-- input --><input type="text" class="form-control" name="last_name" placeholder="Prénom" aria-label="Prénom" value="{{ old('last_name') }}" required>
              </div>
              <div class="col-12">
                <!-- input --><input type="email" class="form-control" name="email" id="inputEmail4" placeholder="Email" value="{{ old('email') }}" required>
              </div>
              <div class="col-12">
                <!-- input --><input type="tel" class="form-control" name="telephone" id="inputTelephone" placeholder="Numéro de téléphone (optionnel)" value="{{ old('telephone') }}">
              </div>
              <div class="col-12">
                <div class="password-field position-relative">
                  <input type="password" name="password" id="fakePassword" placeholder="Mot de passe" class="form-control" required>
                  <span><i id="passwordToggler" class="bi bi-eye-slash"></i></span>
                </div>
              </div>
              <div class="col-12">
                <div class="password-field position-relative">
                  <input type="password" name="password2" id="confirmPassword" placeholder="Confirmer le mot de passe" class="form-control" required>
                  <span><i id="passwordToggler2" class="bi bi-eye-slash"></i></span>
                </div>
              </div>
              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="termsCheckbox" required>
                  <label class="form-check-label" for="termsCheckbox">
                    J'accepte les <a href="#!">conditions d'utilisation</a> et les <a href="#!">politiques de confidentialité</a>
                  </label>
                </div>
              </div>
              <!-- btn -->
              <div class="col-12 d-grid"> <button type="submit" class="btn btn-primary">S'inscrire</button>
              </div>

              <!-- text -->
              <div>Vous avez déjà un compte? <a href="{{ route('auth.login.view') }}">Se connecter</a></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>