<!DOCTYPE html>
<html data-bs-theme="light" lang="en-US" dir="ltr">


<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================--><!--    Document Title--><!-- ===============================================-->
    <title>Se connecter</title>

    <!-- ===============================================--><!--    Favicons--><!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="/admin/assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/admin/assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/admin/assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="/admin/assets/img/favicons/favicon.ico">
    <link rel="manifest" href="/admin/assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="/admin/assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <script src="/admin/assets/js/config.js"></script>
    <script src="/admin/assets/vendors/simplebar/simplebar.min.js"></script>

    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="/admin/assets/vendors/simplebar/simplebar.min.css" rel="stylesheet">
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
                        <p class="opacity-75 text-white">Bienvenue chez Vibe Sneakers ! Connectez-vous pour suivre vos commandes, gérer vos préférences et accélérer le processus de paiement. </p>
                      </div>
                    </div>
                    <div class="mt-3 mb-4 mt-md-4 mb-md-5" data-bs-theme="light">
                      <p class="text-white">Vous n'avez pas de compte ?<br><a class="text-decoration-underline link-light" href="{{route('auth.register.view')}}">S'inscrire</a></p>
                      <p class="mb-0 mt-4 mt-md-5 fs-10 fw-semi-bold text-white opacity-75">Lire nos <a class="text-decoration-underline text-white" href="{{ route('terms_view') }}">Conditions d'utilisation</a> et <a class="text-decoration-underline text-white" href="{{ route('privacy_policy_view') }}">Politiques de confidentialité </a></p>
                    </div>
                  </div>
                  <div class="col-md-7 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                      <div class="row flex-between-center">
                        <div class="col-auto">
                          <h3>Se connecter</h3>
                        </div>
                      </div>
                      <form action="{{route('auth.login.post')}}" method="post">
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
                        <div class="mb-3"><label class="form-label" for="card-email">Adresse email</label><input class="form-control" name="email" id="card-email" type="email" /></div>
                        <div class="mb-3">
                          <div class="d-flex justify-content-between"><label class="form-label" for="card-password">Mot de Passe</label></div><input name="password" class="form-control" id="card-password" type="password" />
                        </div>
                        <div class="row flex-between-center">
                          <div class="col-auto">
                            <div class="form-check mb-0"><input class="form-check-input" type="checkbox" id="card-checkbox" checked="checked" /><label class="form-check-label mb-0" for="card-checkbox">Se rappeler de moi</label></div>
                          </div>
                          <div class="col-auto"><a class="fs-10" href="{{route('auth.forget_password.post')}}">Mot de passe oublié?</a></div>
                        </div>
                        <div class="mb-3"><button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">Se connecter</button></div>
                      </form>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>


    <!-- ===============================================--><!--    JavaScripts--><!-- ===============================================-->
    <script src="/admin/assets/vendors/popper/popper.min.js"></script>
    <script src="/admin/assets/vendors/bootstrap/bootstrap.min.js"></script>
    <script src="/admin/assets/vendors/anchorjs/anchor.min.js"></script>
    <script src="/admin/assets/vendors/is/is.min.js"></script>
    <script src="/admin/assets/vendors/fontawesome/all.min.js"></script>
    <script src="/admin/assets/vendors/lodash/lodash.min.js"></script>
    <script src="//polyfill.io/v3/polyfill.min58be.js?features=window.scroll"></script>
    <script src="/admin/assets/vendors/list.js/list.min.js"></script>
    <script src="/assets/assets/js/theme.js"></script>
  </body>


</html>
