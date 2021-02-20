<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/easy-admin">
        <img src="{{ asset('devsryan/LaravelEasyAdmin/img/easy-admin.png') }}" alt="Laravel Easy Admin">
        {{ env('EASY_ADMIN_APP_NAME', 'Laravel Easy Admin') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ml-auto">
            @if(count($nav_items) > 0)
                <li class="nav-item dropdown">
                    <a class="nav-link text-info dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-compass"></i> Navigation
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        @foreach($nav_items as $link => $nav_title)
                            <a class="dropdown-item" href="/easy-admin/{{ $link }}/index"><i class="fas fa-external-link-alt"></i> {{ $nav_title }}</a>
                        @endforeach
                    </div>
                </li>
            @endif
            <li class="nav-item pr-3">
                <div class="nav-link">
                    <span class="text-info"><i class="fas fa-user"></i> {{ Auth::user()->email ?? Auth::user()->name ?? '' }}</span>
                </div>
            </li>
            <li class="nav-item border-left border-right px-3">
                <div class="nav-link">
                    <a target="_blank" class="text-decoration-none text-success" href="{{ env('APP_URL') }}"><i class="fas fa-home"></i> App Home</a>
                </div>
            </li>
            <li class="nav-item pl-3">
                <form action="/easy-admin/logout" method="post">
                    @csrf
                    <button type="submit" class="btn btn-link px-0 text-decoration-none text-warning">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
<div class="w-100 text-white p-3" style="background-color: rgb(230,231,231)!important;">
    <div class="row">
        <div class="col-md-6">
            <span class="text-secondary">
                ADMIN /
                @if(Request::is('easy-admin/*/index*'))
                    <a href="/easy-admin">HOME</a>
                @elseif(Request::is('easy-admin/*/create*'))
                    <a href="/easy-admin">HOME</a> / <a href="/easy-admin/{{ $url_model }}/index">{{ strtoupper($model) }} - INDEX</a>
                @elseif(Request::is('easy-admin/*/*/edit'))
                    <a href="/easy-admin">HOME</a> / <a href="/easy-admin/{{ $url_model }}/index">{{ strtoupper($model) }} - INDEX</a>
                @endif
            </span>
            <h1 class="text-dark">
                @if(isset($model))
                    {{ $model }}
                @else
                    {{ $title }}
                @endif
            </h1>
        </div>
        @if(Request::is('easy-admin/*/index*') and in_array('create', $allowed))
            <div class="col-md-6 text-right mt-auto">
                <a href="/easy-admin/{{$url_model}}/create" class="btn btn-primary" role="button" aria-pressed="true">
                    <i class="fas fa-folder-plus"></i> Create {{ $model }}
                </a>
            </div>
        @endif
    </div>
</div>
@include('easy-admin::partials.messages')
