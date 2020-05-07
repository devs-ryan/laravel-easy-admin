@extends('easy-admin::layouts.main')


@section('title', env('EASY_ADMIN_APP_NAME', 'Laravel Easy Admin'))

@section('content')
    <div class="p-md-5">
        <div class="jumbotron">
            <h3>App Index</h3>
            <p class="lead">Select an asset from the list below:</p>
            <hr class="my-4">
            <ul class="list-group">
                @foreach($nav_items as $link => $nav_title)
                    <a href="/easy-admin/{{ $link }}/index">
                        <li class="list-group-item">{{ $nav_title }}</li>
                    </a>
                @endforeach
                @if(count($nav_items) == 0)
                    Your admin panel does not currently have any assets added to it. Contact your system adming to add new assets or lean how by visiting: <br>
                    <a href="https://github.com/raysirsharp/laravel-easy-admin" target="_blank">https://github.com/raysirsharp/laravel-easy-admin</a>
                @endif
            </ul>
        </div>
    </div>
@endsection