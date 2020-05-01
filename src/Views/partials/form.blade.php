@foreach($fields as $field)
    <div class="form-group row">
        <label for="inputPassword" class="col-sm-12 col-form-label">
            @if(in_array($field, $required_fields))
                *
            @endif
            {{ ucwords(str_replace('_', ' ', $field)) }}:
        </label>
        <div class="col-sm-12">
            @switch(Raysirsharp\LaravelEasyAdmin\Services\HelperService::inputType($field, $model_path))
                @case('password')
                    @include('easy-admin::partials.form-inputs.password')
                    @break
                @case('boolean')
                    @include('easy-admin::partials.form-inputs.boolean')
                    @break
                @case('decimal')
                @case('integer')
                    @include('easy-admin::partials.form-inputs.number')
                    @break
                @case('date')
                    @include('easy-admin::partials.form-inputs.date')
                    @break
                @case('timestamp')
                    @include('easy-admin::partials.form-inputs.timestamp')
                    @break
                @default
                    @include('easy-admin::partials.form-inputs.text')
                    @break
            @endswitch
        </div>
    </div>
@endforeach
@if((in_array('update', $allowed) and Request::is('easy-admin/*/*/edit')) or (in_array('create', $allowed) and !Request::is('easy-admin/*/*/edit')))
    <div class="text-right">
        <button class="btn btn-primary">
            <i class="far fa-check-circle"></i> Submit
        </button>
    </div>
@endif