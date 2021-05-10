<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/easy-admin">
        <img src="{{ asset('devsryan/LaravelEasyAdmin/img/easy-admin.png') }}" alt="Laravel Easy Admin">
        {{ env('EASY_ADMIN_APP_NAME', 'Laravel Easy Admin') }}
    </a>
    @if(env('APP_URL'))
        <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
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
                <li class="nav-item">
                    <div class="nav-link">
                        <a class="text-decoration-none text-info" href="{{ env('EASY_ADMIN_BASE_URL', '/easy-admin') }}">
                            <i class="fas fa-tasks"></i> Admin CMS
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    @endif
</nav>
@include('easy-admin::partials.messages')
