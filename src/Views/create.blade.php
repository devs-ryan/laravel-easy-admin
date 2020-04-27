@extends('easy-admin::partials.layout')


@section('title', $model . '- Create')

@section('content')
    <div class="p-md-5">
        <div class="jumbotron">
            <h3><i class="fas fa-folder-plus"></i> Create {{ ucwords(str_replace('-', ' ', $url_model)) }}</h3>
            <p class="lead">Fill out the form below to create a new {{ ucwords(str_replace('-', ' ', $url_model)) }}:</p>
            <hr class="my-4">
            <form action="/easy-admin/{{ $url_model }}" method="post">
                @csrf
                @include('easy-admin::partials.form')
            </form>
        </div>
    </div>
@endsection
