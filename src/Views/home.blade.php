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
                    <i>Your admin panel does not currently have any assets added to it.</i> <br>
                    <p> 
                        Contact you system admin
                        @if(env('EASY_ADMIN_SUPPORT_EMAIL') != NULL)
                            <a target="_blank" href="mailto:{{ env('EASY_ADMIN_SUPPORT_EMAIL') }}?subject=Easy Admin Help Request">{{ env('EASY_ADMIN_SUPPORT_EMAIL') }}</a>
                        @endif 
                        to add new assets or lean how by visiting: <br>
                         <a href="https://github.com/devsryan/laravel-easy-admin" target="_blank">https://github.com/devsryan/laravel-easy-admin</a>
                    </p>
                   
                @endif
            </ul>
        </div>
    </div>
@endsection