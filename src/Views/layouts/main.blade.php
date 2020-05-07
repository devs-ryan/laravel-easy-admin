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
	
	<link rel="stylesheet" href="{{ asset('raysirsharp/LaravelEasyAdmin/css/laravel-easy-admin.min.css') }}">
	<link rel="icon" type="image/x-icon" href="{{ asset('raysirsharp/LaravelEasyAdmin/img/easy-admin.ico') }}"/>
	<script type="text/javascript" src="{{ asset('raysirsharp/LaravelEasyAdmin/js/laravel-easy-admin.min.js') }}"></script>
	@yield('styles')
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
	@yield('scripts')
</body>

</html>