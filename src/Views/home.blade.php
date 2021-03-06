@extends('easy-admin::layouts.main')

@section('title', env('EASY_ADMIN_APP_NAME', 'Laravel Easy Admin'))

@section('content')
    <div class="p-md-5">
        <div class="jumbotron">
            <h3>App Index</h3>
            <p class="lead">Select an asset from the list below:</p>
            <hr class="my-4">

            @php
                $count = 0; // use this counter to determine if there is generalized models
            @endphp

            {{-- Page Models  --}}
            @if(count($pages) > 0 )
                <h5 class="pt-4">Pages:</h5>
                <ul class="list-group">
                    @foreach($nav_items as $link => $nav_title)
                        @if (in_array($nav_title, $pages))

                            @php $count++; @endphp
                            <a href="/easy-admin/{{ $link }}/index">
                                <li class="list-group-item">{{ $nav_title }}</li>
                            </a>
                        @endif
                    @endforeach
                </ul>
            @endif

            {{-- Post Type Models  --}}
            @if(count($posts) > 0 )
                <h5 class="pt-4">Posts:</h5>
                <ul class="list-group">
                    @foreach($nav_items as $link => $nav_title)
                        @if (in_array($nav_title, $posts))

                            @php $count++; @endphp
                            <a href="/easy-admin/{{ $link }}/index">
                                <li class="list-group-item">{{ $nav_title }}</li>
                            </a>
                        @endif
                    @endforeach
                </ul>
            @endif

            {{-- Section Models  --}}
            @if(count($partials) > 0 )
                @foreach($partials as $partial)
                    @if(strpos($partial, "Global.") === 0)
                        <h5 class="pt-4">Partials:</h5>
                        @break
                    @endif
                @endforeach

                <ul class="list-group">
                    @foreach($nav_items as $link => $nav_title)
                        @if (in_array("Global.$nav_title", $partials))
                            <a href="/easy-admin/{{ $link }}/index">
                                <li class="list-group-item">{{ $nav_title }}</li>
                            </a>
                        @endif
                    @endforeach
                </ul>

                @php $count += count($partials); @endphp
            @endif

            {{-- General Models  --}}
            @if(count($nav_items) - $count > 0 )
                <h5 class="pt-4">
                    {{ $count > 0 ? 'Global:' : 'Models:' }}
                </h5>
                <ul class="list-group">
                    @foreach($nav_items as $link => $nav_title)
                        @if (!in_array($nav_title, $pages) && !in_array($nav_title, $posts) && !in_array($nav_title, $partial_models))
                            <a href="/easy-admin/{{ $link }}/index">
                                <li class="list-group-item">{{ $nav_title }}</li>
                            </a>
                        @endif
                    @endforeach
                </ul>
            @endif

            {{-- No Results --}}
            @if(count($nav_items) == 0)
                <ul class="list-group">
                    <i>Your admin panel does not currently have any assets added to it.</i> <br>
                    <p>
                        Contact you system admin
                        @if(env('EASY_ADMIN_SUPPORT_EMAIL') != NULL)
                            <a target="_blank" href="mailto:{{ env('EASY_ADMIN_SUPPORT_EMAIL') }}?subject=Easy Admin Help Request">{{ env('EASY_ADMIN_SUPPORT_EMAIL') }}</a>
                        @endif
                        to add new assets or lean how by visiting: <br>
                            <a href="https://github.com/devsryan/laravel-easy-admin" target="_blank">https://github.com/devsryan/laravel-easy-admin</a>
                    </p>
                </ul>
            @endif

        </div>
    </div>
@endsection
