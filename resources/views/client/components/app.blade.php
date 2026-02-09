<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="KING-ASIC-MINER" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title') - KING-ASIC-MINER </title>

    <link href="/assets/kingshop/assets/libs/slick-carousel/slick/slick.css" rel="stylesheet" />
    <link href="/assets/kingshop/assets/libs/slick-carousel/slick/slick-theme.css" rel="stylesheet" />
    <link href="/assets/kingshop/assets/libs/tiny-slider/dist/tiny-slider.css" rel="stylesheet">

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/x-icon" href="/assets/kingshop/assets/images/favicon/favicon.ico">


    <!-- Libs CSS -->
    <link href="/assets/kingshop/assets/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/kingshop/assets/libs/feather-webfont/dist/feather-icons.css" rel="stylesheet">
    <link href="/assets/kingshop/assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- Theme CSS -->
    <link rel="stylesheet" href="/assets/kingshop/assets/css/theme.min.css">
    <link rel="stylesheet" href="/assets/kingshop/assets/css/custom.css">

    @yield('styles')
    <!-- Google tag (gtag.js) -->

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-M8S4MT3EYG');
    </script>

</head>

<body>

    <!-- header -->
    @include('client.components.layouts.header')

    <!-- Modal -->
    @include('client.components.commons.modals.login')

    <!-- Shop Cart -->

    @include('client.components.commons.modals.cart')

    <!-- Modal -->
    @include('client.components.commons.modals.location')

    <main>
        @yield('content')
    </main>


    <!-- Modal -->
   @include('client.components.commons.modals.product_quickview')


    <!-- footer -->
    @include('client.components.layouts.footer')

    <!-- Javascript-->
    @yield('scripts')


    <!-- Libs JS -->
    <script src="/assets/kingshop/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="/assets/kingshop/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/kingshop/assets/libs/simplebar/dist/simplebar.min.js"></script>

    <!-- Theme JS -->
    <script src="/assets/kingshop/assets/js/theme.min.js"></script>
    <script src="/assets/kingshop/assets/libs/jquery-countdown/dist/jquery.countdown.min.js"></script>
    <script src="/assets/kingshop/assets/js/vendors/countdown.js"></script>
    <script src="/assets/kingshop/assets/libs/slick-carousel/slick/slick.min.js"></script>
    <script src="/assets/kingshop/assets/js/vendors/slick-slider.js"></script>
    <script src="/assets/kingshop/assets/libs/tiny-slider/dist/min/tiny-slider.js"></script>
    <script src="/assets/kingshop/assets/js/vendors/tns-slider.js"></script>
    <script src="/assets/kingshop/assets/js/vendors/zoom.js"></script>
    <script src="/assets/kingshop/assets/js/vendors/increment-value.js"></script>
    <script src="//code.tidio.co/vy7fga8fhtel5qwbcvixk7ccxabojogz.js" async></script>

    <!-- Cart Management -->
    <script src="/client/js/cart.js"></script>

    <!-- Toast Container for Notifications -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

</body>

</html>