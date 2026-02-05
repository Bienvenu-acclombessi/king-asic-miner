  <!-- section -->
  <section class="my-lg-14 my-8">
    <div class="container">
      <!-- row -->
      <div class="row justify-content-center align-items-center">
        <div class="col-12 col-md-6 col-lg-4 order-lg-1 order-2">
          <!-- img -->
          <img src="/assets/kingshop/assets/images/svg-graphics/signin-g.svg" alt="" class="img-fluid">
        </div>
        <!-- col -->
        <div class="col-12 col-md-6 offset-lg-1 col-lg-4 order-lg-2 order-1">
          <div class="mb-lg-9 mb-5">
            <h1 class="mb-1 h2 fw-bold">Se connecter</h1>
            <p>Bienvenue ! Entrez votre email pour commencer.</p>
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

          @if (session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif

          <form action="{{ route('auth.login.post') }}" method="POST">
            @csrf
            <div class="row g-3">
              <!-- row -->

              <div class="col-12">
                <!-- input -->
                <input type="email" class="form-control" name="email" id="inputEmail4" placeholder="Email" value="{{ old('email') }}" required>
              </div>
              <div class="col-12">
                <!-- input -->
                <div class="password-field position-relative">
                  <input type="password" name="password" id="fakePassword" placeholder="Mot de passe" class="form-control" required>
                  <span><i id="passwordToggler" class="bi bi-eye-slash"></i></span>
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <!-- form check -->
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" value="1" id="flexCheckDefault">
                  <!-- label --> <label class="form-check-label" for="flexCheckDefault">
                    Se rappeler de moi
                  </label>
                </div>
                <div> Mot de passe oublié? <a href="{{ route('auth.forget_password.view') }}">Réinitialiser</a></div>
              </div>
              <!-- btn -->
              <div class="col-12 d-grid"> <button type="submit" class="btn btn-primary">Se connecter</button>
              </div>
              <!-- link -->
              <div>Vous n'avez pas de compte? <a href="{{ route('auth.register.view') }}"> S'inscrire</a></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>