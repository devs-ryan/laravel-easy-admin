<!DOCTYPE html>
<html lang="">

<head>
	<meta charset="utf-8">
	<title>
	    @yield('title')
	</title>
	<meta name="author" content="Ryan Claxton">
	<meta name="description" content="Laravel Easy Admin">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('devsryan/LaravelEasyAdmin/img/easy-admin.ico') }}"/>

	<link rel="stylesheet" href="{{ asset('devsryan/LaravelEasyAdmin/css/laravel-easy-admin.min.css') }}">
    <script src="{{ asset('devsryan/LaravelEasyAdmin/js/laravel-easy-admin.min.js') }}"></script>
	@stack('styles')
</head>

<body>
	<header>
	    @include('easy-admin::partials.header')
	</header>
	<main>
	    @yield('content')
	</main>
	<footer>
	    @include('easy-admin::partials.footer')
	</footer>
	@stack('scripts')
</body>

</html>
