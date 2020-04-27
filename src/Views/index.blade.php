@extends('easy-admin::partials.layout')


@section('title', $model)

@section('content')
    <div class="container m-0 p-3" style="max-width: 100% !important;">
        <div class="row">
            <div class="col-md-3">
                @include('easy-admin::partials.search-filters')
                @include('easy-admin::partials.need-help-card')
            </div>
            <div class="col-md-9">
                @include('easy-admin::partials.index-content')
            </div>
        </div>
    </div>
@endsection

