@extends('easy-admin::partials.layout')


@section('title', $model . '- Update')

@section('content')
    <div class="p-md-5">
        <div class="jumbotron">
            <h3><i class="fas fa-folder-plus"></i> Update {{ ucwords(str_replace('-', ' ', $url_model)) }}</h3>
            <p class="lead">Edit the form below to create a new {{ ucwords(str_replace('-', ' ', $url_model)) }}:</p>
            <hr class="my-4">
            <form action="/easy-admin/{{ $url_model }}/{{ $id }}" method="post">
                @csrf
                @method('PUT')
                @include('easy-admin::partials.form')
            </form>
        </div>
    </div>
@endsection