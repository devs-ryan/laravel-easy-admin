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
	    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/easy-admin">
                <img src="{{ asset('raysirsharp/LaravelEasyAdmin/img/easy-admin.ico') }}" alt="Laravel Easy Admin">
                {{ env('EASY_ADMIN_APP_NAME', 'Laravel Easy Admin') }}
            </a>
            @if(env('APP_URL'))
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <div class="nav-link">
                                <a class="text-decoration-none text-warning" href="{{ env('APP_URL') }}"><i class="fas fa-home"></i> App Home</a>
                            </div>
                        </li>
                    </ul>
                </div>
            @endif
        </nav>
	</header>
	@include('easy-admin::partials.messages')
	<main>
	    @yield('content')
	</main>
	<footer>
	    @include('easy-admin::partials.footer')
	</footer>
	@yield('scripts')
</body>

</html>