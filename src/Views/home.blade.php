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

            {{-- Section Models  --}}
            @if(count($sections) > 0 )
                @foreach ($sections as $section)
                    @if(strpos($section, "Global.") === 0)
                        <h5 class="pt-4">Sections:</h5>
                        @break
                    @endif
                @endforeach

                <ul class="list-group">
                    @foreach($nav_items as $link => $nav_title)
                        @if (in_array("Global.$nav_title", $sections))

                            @php $count++; @endphp
                            <a href="/easy-admin/{{ $link }}/index">
                                <li class="list-group-item">{{ $nav_title }}</li>
                            </a>
                        @endif
                    @endforeach
                </ul>
            @endif

            {{-- General Models  --}}
            @if(count($nav_items) - $count > 1 )
                <h5 class="pt-4">
                    {{ count($sections) + count($pages) > 1 ? 'Post Types / Global Settings:' : 'Models:' }}
                </h5>
                <ul class="list-group">
                    @foreach($nav_items as $link => $nav_title)
                        @if (!in_array($nav_title, $pages) && !in_array("Global.$nav_title", $sections))
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
