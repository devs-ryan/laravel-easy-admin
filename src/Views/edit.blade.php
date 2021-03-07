@extends('easy-admin::layouts.main')


@section('title', $model . '- Update')

@section('content')
    <div class="p-md-5">
        <div class="jumbotron">
            <h3><i class="fas fa-folder-plus"></i> Update {{ ucwords(str_replace('-', ' ', $url_model)) }}</h3>
            <p class="lead mb-0">Edit the form below to create a new {{ ucwords(str_replace('-', ' ', $url_model)) }}:</p>
            <small>
                * = Required Field
            </small>
            <hr class="my-4">
            <form action="/easy-admin/{{ $url_model }}/{{ $id }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('easy-admin::partials.form')
                @if(count($model_partials) > 0)
                    @include('easy-admin::partials.partial-index')
                @endif
            </form>
        </div>
    </div>
@endsection
