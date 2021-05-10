<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
	    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/easy-admin">
                <img src="{{ asset('devsryan/LaravelEasyAdmin/img/easy-admin.png') }}" alt="Laravel Easy Admin">
                {{ env('EASY_ADMIN_APP_NAME', 'Laravel Easy Admin') }}
            </a>
            @if(env('APP_URL'))
                <button
                    class="navbar-toggler"
                    type="button" data-toggle="collapse"
                    data-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <div class="nav-link">
                                <a class="text-decoration-none text-warning" href="{{ env('APP_URL', '/') }}">
                                    <i class="fas fa-home"></i> App Home
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            @endif
        </nav>
        @include('easy-admin::partials.messages')
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
