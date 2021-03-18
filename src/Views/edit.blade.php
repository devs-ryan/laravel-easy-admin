@extends('easy-admin::layouts.main')


@section('title', $model . '- Update')

@section('content')
    <div class="p-md-5">
        <div class="jumbotron">
            <div class="row">
                <div class="col-md-8">
                    <h3><i class="fas fa-folder-plus"></i> Update {{ ucwords(str_replace('-', ' ', $url_model)) }}</h3>
                    <p class="lead mb-0">Edit the form below to update this {{ ucwords(str_replace('-', ' ', $url_model)) }}:</p>
                    <small>
                        * = Required Field
                    </small>
                </div>
                {{-- delete button --}}
                @if(in_array('delete', $allowed) && (!$limits['min'] || $model_count > $limits['min']))
                    <div class="col-md-4 text-right mt-auto">
                        <form class="float-right" action="/easy-admin/{{$url_model}}/{{ $id }}" method="post">
                            @csrf
                            @method('DELETE')
                            @if (isset($parent_id) && $parent_id !== null)
                                <input type="hidden" name="easy_admin_delete_with_parent_id" value="{{ $parent_id }}">
                            @endif
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>
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
