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
          <div>
            <div class="mb-lg-9 mb-5">
              <!-- heading -->
              <h1 class="mb-2 h2 fw-bold">Mot de passe oublié?</h1>
              <p>Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
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
            <form action="{{ route('auth.forget_password.post') }}" method="POST">
              @csrf
              <!-- row -->
              <div class="row g-3">
               <!-- col -->
                <div class="col-12">
                  <!-- input -->
                  <input type="email" class="form-control" name="email" id="inputEmail4" placeholder="Email" value="{{ old('email') }}" required>
                </div>

                <!-- btn -->
                <div class="col-12 d-grid gap-2">
                  <button type="submit" class="btn btn-primary">Envoyer le lien</button>
                  <a href="{{ route('auth.login.view') }}" class="btn btn-light">Retour</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>