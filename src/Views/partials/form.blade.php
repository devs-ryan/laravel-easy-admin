@foreach($fields as $field)
    <div class="form-group row">
        <label for="inputPassword" class="col-sm-12 col-form-label">
            @if(in_array($field, $required_fields))
                *
            @endif
            {{ ucwords(str_replace('_', ' ', $field)) }}:
        </label>
        <div class="col-sm-12">
            @if($field != 'password')
                <textarea
                @if((!in_array('update', $allowed) and Request::is('easy-admin/*/*/edit')) or (!in_array('create', $allowed) and !Request::is('easy-admin/*/*/edit')))
                    disabled 
                @endif
                name="{{ $field }}" rows="1" class="form-control">{{ old($field) ?? $data->$field ?? '' }}</textarea>
            @else
                @if(Request::is('easy-admin/*/*/edit'))
                    <input 
                    @if((!in_array('update', $allowed) and Request::is('easy-admin/*/*/edit')) or (!in_array('create', $allowed) and !Request::is('easy-admin/*/*/edit')))
                        disabled 
                    @endif
                    class="form-control" type="password" name="{{ $field }}" value="{{ old($field) ?? '**********' }}">
                    <small>Remove stars to update password otherwise it will remain 'as is'</small>
                @else
                    <input 
                    @if((!in_array('update', $allowed) and Request::is('easy-admin/*/*/edit')) or (!in_array('create', $allowed) and !Request::is('easy-admin/*/*/edit')))
                        disabled 
                    @endif
                    class="form-control" type="password" name="{{ $field }}" value="{{ old($field) ?? '' }}">
                @endif
            @endif
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