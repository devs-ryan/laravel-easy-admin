@extends('easy-admin::layouts.main')

@section('title', env('EASY_ADMIN_APP_NAME', 'Laravel Easy Admin'))

@section('content')
    <div class="p-md-5">
        <div class="jumbotron col-lg-10 offset-lg-1 col-xl-8 offset-xl-2">
            <h3>App Index</h3>
            <p class="lead">Select an asset from the list below:</p>
            <hr class="my-4">

            @php
                $count = 0; // use this counter to determine if there is generalized models
            @endphp

            {{-- Page Models  --}}
            @if(count($pages) > 0 )
                <div id="accordion">
                    <button
                        class="btn btn-primary btn-block collapsed text-left rounded-0 border border-secondary"
                        data-toggle="collapse"
                        data-target="#collapsePage"
                        aria-expanded="false"
                        aria-controls="collapsePage"
                    >
                        Pages
                    </button>
                    <ul
                        class="list-group collapse pl-4"
                        id="collapsePage"
                        data-parent="#accordion"
                    >
                        @foreach($nav_items as $link => $nav_title)
                            @if (in_array($nav_title, $pages))

                                @php $count++; @endphp
                                <a href="/easy-admin/{{ $link }}/index">
                                    <li class="list-group-item rounded-0">
                                        <i class="fas fa-angle-right"></i>
                                        {{ $nav_title }}
                                    </li>
                                </a>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Post Type Models  --}}
            @if(count($posts) > 0 )
                <div id="accordion">
                    <button
                        class="btn btn-primary btn-block collapsed text-left rounded-0 border border-secondary"
                        data-toggle="collapse"
                        data-target="#collapsePost"
                        aria-expanded="false"
                        aria-controls="collapsePost"
                    >
                        Posts
                    </button>
                    <ul
                        class="list-group collapse pl-4"
                        id="collapsePost"
                        data-parent="#accordion"
                    >
                        @foreach($nav_items as $link => $nav_title)
                            @if (in_array($nav_title, $posts))

                                @php $count++; @endphp
                                <a href="/easy-admin/{{ $link }}/index">
                                    <li class="list-group-item rounded-0">
                                        <i class="fas fa-angle-right"></i>
                                        {{ $nav_title }}
                                    </li>
                                </a>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Section Models  --}}
            @if(count($partials) > 0 )
                <div id="accordion">
                    <button
                        class="btn btn-primary btn-block collapsed text-left rounded-0 border border-secondary"
                        data-toggle="collapse"
                        data-target="#collapsePartial"
                        aria-expanded="false"
                        aria-controls="collapsePartial"
                    >
                        Partials
                    </button>
                    <ul
                        class="list-group collapse pl-4"
                        id="collapsePartial"
                        data-parent="#accordion"
                    >
                        @foreach($nav_items as $link => $nav_title)
                            @if (in_array("Global.$nav_title", $partials))

                                @php $count++; @endphp
                                <a href="/easy-admin/{{ $link }}/index">
                                    <li class="list-group-item rounded-0">
                                        <i class="fas fa-angle-right"></i>
                                        {{ $nav_title }}
                                    </li>
                                </a>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- General Models  --}}
            @if(count($nav_items) - $count > 0 )
                <div id="accordion">
                    <button
                        class="btn btn-primary btn-block collapsed text-left rounded-0 border border-secondary"
                        data-toggle="collapse"
                        data-target="#collapseGlobal"
                        aria-expanded="false"
                        aria-controls="collapseGlobal"
                    >
                        {{ $count > 0 ? 'Global' : 'Models' }}
                    </button>
                    <ul
                        class="list-group collapse pl-4"
                        id="collapseGlobal"
                        data-parent="#accordion"
                    >
                        @foreach($nav_items as $link => $nav_title)
                            @if (!in_array($nav_title, $pages) && !in_array($nav_title, $posts) && !in_array($nav_title, $partial_models))
                                <a href="/easy-admin/{{ $link }}/index">
                                    <li class="list-group-item rounded-0">
                                        <i class="fas fa-angle-right"></i>
                                        {{ $nav_title }}
                                    </li>
                                </a>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Custom Links --}}
            @if (count($custom_links) > 0)
                <div id="accordion">
                    <button
                        class="btn btn-primary btn-block collapsed text-left rounded-0 border border-secondary"
                        data-toggle="collapse"
                        data-target="#collapseLinks"
                        aria-expanded="false"
                        aria-controls="collapseLinks"
                    >
                        Links
                    </button>
                    <ul
                        class="list-group collapse pl-4"
                        id="collapseLinks"
                        data-parent="#accordion"
                    >
                        @foreach($custom_links as $nav_title => $link)
                            <a href="{{ $link }}">
                                <li class="list-group-item rounded-0">
                                    <i class="fas fa-angle-right"></i>
                                    {{ $nav_title }}
                                </li>
                            </a>
                        @endforeach
                    </ul>
                </div>
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

@push('styles')
    <style>
        .btn {
            position: relative;
        }
        .btn:focus, .btn:active:focus, .btn.active:focus {
            outline:none;
            box-shadow:none;
        }
        .btn:focus, .btn:active:focus, .btn.active:focus{
            background-color: #007bff;
        }

        .btn[aria-expanded="true"] {
            background-color: #28a745;
        }
        .btn[aria-expanded="true"]::after {
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            content: "\f106";
            position: absolute;
            right: 1rem;
            top: 0.3rem;
        }
        .btn[aria-expanded="false"]::after {
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            content: "\f107";
            position: absolute;
            right: 1rem;
            top: 0.3rem;
        }
    </style>
@endpush
