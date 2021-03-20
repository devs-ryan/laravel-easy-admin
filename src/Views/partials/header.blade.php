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
                            @if(!in_array($nav_title, $partial_models) || in_array("Global.$nav_title", $partials))
                                <a class="dropdown-item" href="/easy-admin/{{ $link }}/index">
                                    <i class="fas fa-external-link-alt"></i> {{ $nav_title }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </li>
            @endif
            <li class="nav-item pr-3">
                <div class="nav-link">
                    <span class="text-info"><i class="fas fa-user"></i> {{ Auth::user()->email ?? Auth::user()->name ?? '' }}</span>
                </div>
            </li>
            <li class="nav-item border-nav px-3">
                <div class="nav-link">
                    <a target="_blank" class="text-decoration-none text-success" href="{{ env('APP_URL', '/') }}"><i class="fas fa-home"></i> App Home</a>
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
<div class="w-100 p-3" style="background-color: rgb(230,231,231)!important;">
    <div class="row">
        <div class="col-md-6">
            <div class="text-secondary">
                ADMIN /
                @if(Request::is('easy-admin/*/index*'))
                    @if(isset($parent_id) && $parent_id !== null)
                        {!! \DevsRyan\LaravelEasyAdmin\Services\HelperService::makePartialBreadcrums($parent_id, $model, $nav_items) !!}
                    @else
                        <a href="/easy-admin">HOME</a>
                    @endif
                @elseif(Request::is('easy-admin/*/create*') || Request::is('easy-admin/*/*/edit'))
                    @if(isset($parent_id) && $parent_id !== null)
                        {!! \DevsRyan\LaravelEasyAdmin\Services\HelperService::makePartialBreadcrums($parent_id, $model, $nav_items) !!} /
                        <a href="/easy-admin/{{ $url_model }}/index?parent_id={{ $parent_id }}">{{ strtoupper($model) }} - INDEX</a>
                    @else
                        <a href="/easy-admin">HOME</a> / <a href="/easy-admin/{{ $url_model }}/index">{{ strtoupper($model) }} - INDEX</a>
                    @endif
                @endif
            </div>
            <h1 class="text-dark">
                @if(isset($model))
                    {{ $model }}
                @else
                    {{ $title }}
                @endif
            </h1>
            <h6 class="mb-0">
                @if(Request::is('easy-admin/*/index*'))
                    <strong>Results:</strong> {{ $model_count }} -
                    <strong>Showing:</strong> {{ $data->count() }} <br>
                    @if(isset($model) && ($limits['min'] || $limits['max']))
                        <strong>Min:</strong> {{ $limits['min'] ? $limits['min'] : 'None' }}
                        |
                        <strong>Max:</strong> {{ $limits['max'] ? $limits['max'] : 'None' }}
                    @endif
                @endif
            </h6>
        </div>

        <div class="col-md-6 text-right mt-auto">
            @if(Request::is('easy-admin/*/index*') && in_array('create', $allowed) && (!$limits['max'] || $model_count < $limits['max']))
                @php
                    $parent_id_for_create = isset($parent_id) && $parent_id !== null ? "?parent_id=$parent_id" : '';
                @endphp
                <a href="/easy-admin/{{$url_model}}/create{{$parent_id_for_create}}" class="btn btn-primary" role="button" aria-pressed="true">
                    <i class="fas fa-folder-plus"></i> Create {{ $model }}
                </a>
            @endif
            @if(Request::is('easy-admin/*/index*'))
                @if($limits['max'] && $model_count >= $limits['max'])
                    <div class="text-info">* <i>The max # of records has been reached.</i></div>
                @endif
                @if($limits['min'] && $model_count <= $limits['min'])
                    <div class="text-info">* <i>The min # of records has been reached.</i></div>
                @endif
            @endif
        </div>
    </div>
</div>
@include('easy-admin::partials.messages')
