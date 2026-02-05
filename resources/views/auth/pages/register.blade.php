<!DOCTYPE html>
<html data-bs-theme="light" lang="en-US" dir="ltr">

  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>S'inscrire</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/admin/assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/admin/assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/admin/assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="/admin/assets/img/favicons/favicon.ico">
    <link rel="manifest" href="/admin/assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="/admin/assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <script src="/admin/assets/js/config.js"></script>
    <script src="/admin/vendors/simplebar/simplebar.min.js"></script>

    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="/admin/vendors/simplebar/simplebar.min.css" rel="stylesheet">
    <link href="/admin/assets/css/theme-rtl.min.css" rel="stylesheet" id="style-rtl">
    <link href="/admin/assets/css/theme.min.css" rel="stylesheet" id="style-default">
    <link href="/admin/assets/css/user-rtl.min.css" rel="stylesheet" id="user-style-rtl">
    <link href="/admin/assets/css/user.min.css" rel="stylesheet" id="user-style-default">
    <script>
      var isRTL = JSON.parse(localStorage.getItem('isRTL'));
      if (isRTL) {
        var linkDefault = document.getElementById('style-default');
        var userLinkDefault = document.getElementById('user-style-default');
        linkDefault.setAttribute('disabled', true);
        userLinkDefault.setAttribute('disabled', true);
        document.querySelector('html').setAttribute('dir', 'rtl');
      } else {
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
        linkRTL.setAttribute('disabled', true);
        userLinkRTL.setAttribute('disabled', true);
      }
    </script>
  </head>

  <body>
    <main class="main" id="top">
      <div class="container-fluid">
        <div class="row min-vh-100 flex-center g-0">
          <div class="col-lg-8 col-xxl-5 py-3 position-relative"><img class="bg-auth-circle-shape" src="/admin/assets/img/icons/spot-illustrations/bg-shape.png" alt="" width="250"><img class="bg-auth-circle-shape-2" src="/admin/assets/img/icons/spot-illustrations/shape-1.png" alt="" width="150">
            <div class="card overflow-hidden z-1">
              <div class="card-body p-0">
                <div class="row g-0 h-100">
                  <div class="col-md-5 text-center bg-card-gradient">
                    <div class="position-relative p-4 pt-md-5 pb-md-7" data-bs-theme="light">
                      <div class="bg-holder bg-auth-card-shape" style="background-image:url(/admin/assets/img/icons/spot-illustrations/half-circle.png);"></div><!--/.bg-holder-->
                      <div class="z-1 position-relative"><a class="link-light mb-4 font-sans-serif fs-5 d-inline-block fw-bolder" href="{{ route('accueil_view') }}">Vibe-Sneakers</a>
                        <p class="opacity-75 text-white">Chez Vibe-Sneakers, nous célébrons votre style unique et votre passion pour la mode. Connectez-vous pour découvrir nos nouveautés, gérer votre compte et suivre vos commandes avec facilité. Nous sommes là pour vous aider à trouver la paire parfaite, à chaque pas de votre parcours.</p>
                      </div>
                    </div>
                    <div class="mt-3 mb-4 mt-md-4 mb-md-5" data-bs-theme="light">
                      <p class="pt-3 text-white">Avez-vous déjà un compte ?<br><a class="btn btn-outline-light mt-2 px-4" href="{{route('auth.login.view')}}">Connectez-vous</a></p>
                    </div>
                  </div>
                  <div class="col-md-7 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                      <h3>S'inscrire</h3>
                      <form action="{{route('auth.register.post')}}" method="post">
                        @if ($errors->any())

                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        @csrf

                        <div class="mb-3"><label class="form-label" for="card-name">Nom</label><input class="form-control" name="first_name" type="text" autocomplete="on" id="card-name" /></div>
                        <div class="mb-3"><label class="form-label" for="card-name">Prenom</label><input class="form-control" type="text" name="last_name" autocomplete="on" id="card-name" /></div>
                        <div class="mb-3"><label class="form-label" for="card-email">Adresse email</label><input class="form-control" name="email" type="email" autocomplete="on" id="card-email" /></div>
                        <div class="mb-3"><label class="form-label" for="card-telphone">Numero de téléphone</label><input class="form-control" name="telephone" type="tel" autocomplete="on" id="card-telphone" placeholder="+0000000000"/></div>
                        <div class="row gx-2">
                          <div class="mb-3 col-sm-6"><label class="form-label" for="card-password">Mot de passe</label><input class="form-control" name="password" type="password" autocomplete="on" id="card-password" /></div>
                          <div class="mb-3 col-sm-6"><label class="form-label" for="card-confirm-password">Confirmer mot de passe</label><input name="password2" class="form-control" type="password" autocomplete="on" id="card-confirm-password" /></div>
                        </div>
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="card-register-checkbox" required/><label class="form-label" for="card-register-checkbox">J'accepte les <a href="{{ route('terms_view') }}">conditions d'utilisation </a>et les <a class="white-space-nowrap" href="{{ route('privacy_policy_view') }}">politiques de confidentialité</a></label></div>
                        <div class="mb-3"><button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">Créer compte</button></div>
                      </form>
                      <div class="position-relative mt-4">
                        <hr />
                        {{-- <div class="divider-content-center">or register with</div> --}}
                      </div>
                      {{-- <div class="row g-2 mt-2">
                        <div class="col-sm-6"><a class="btn btn-outline-google-plus btn-sm d-block w-100" href="#"><span class="fab fa-google-plus-g me-2" data-fa-transform="grow-8"></span> google</a></div>
                        <div class="col-sm-6"><a class="btn btn-outline-facebook btn-sm d-block w-100" href="#"><span class="fab fa-facebook-square me-2" data-fa-transform="grow-8"></span> facebook</a></div>
                      </div> --}}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main><!-- ===============================================--><!--    End of Main Content--><!-- ===============================================-->


    <script src="/admin/vendors/popper/popper.min.js"></script>
    <script src="/admin/vendors/bootstrap/bootstrap.min.js"></script>
    <script src="/admin/vendors/anchorjs/anchor.min.js"></script>
    <script src="/admin/vendors/is/is.min.js"></script>
    <script src="/admin/vendors/fontawesome/all.min.js"></script>
    <script src="/admin/vendors/lodash/lodash.min.js"></script>
    <script src="/polyfill.io/v3/polyfill.min58be.js?features=window.scroll"></script>
    <script src="/admin/vendors/list.js/list.min.js"></script>
    <script src="/admin/assets/js/theme.js"></script>
  </body>
</html>
