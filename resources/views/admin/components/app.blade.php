<!DOCTYPE html>
<html lang="en">

	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta content="Codescandy" name="author">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@yield('title') - KING-ASIC-MINER
		</title>
		<!-- Favicon icon-->
		<link
		rel="shortcut icon" type="image/x-icon" href="/assets/kingshop/assets/images/favicon/favicon.ico">


		<!-- Libs CSS -->
		<link href="/assets/kingshop/assets/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
		<link href="/assets/kingshop/assets/libs/feather-webfont/dist/feather-icons.css" rel="stylesheet">
		<link
		href="/assets/kingshop/assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet">


		<!-- Theme CSS -->
		<link rel="stylesheet" href="/assets/kingshop/assets/css/theme.min.css">

		@yield('css')
		@stack('styles')


	</head>

	<body>
		<!-- main -->
		<div>
			@include('admin.components.layouts.header')

			<div
				class="main-wrapper">
				<!-- navbar vertical -->

				@include('admin.components.layouts.sidebar')


				<!-- main wrapper -->
				<main class="main-content-wrapper">
					@yield('content')
				</main>
			</div>
		</div>

		<!-- Libs JS -->
		<script src="/assets/kingshop/assets/libs/jquery/dist/jquery.min.js"></script>
		<script src="/assets/kingshop/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
		<script src="/assets/kingshop/assets/libs/simplebar/dist/simplebar.min.js"></script>

		<!-- Theme JS -->
		<script src="/assets/kingshop/assets/js/theme.min.js"></script>
		<script src="/assets/kingshop/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
		<script src="/assets/kingshop/assets/js/vendors/chart.js"></script>

		@yield('js')
		@stack('scripts')

	</body>


</html>
