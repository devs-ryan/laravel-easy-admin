<input 
@if((!in_array('update', $allowed) and Request::is('easy-admin/*/*/edit')) or (!in_array('create', $allowed) and !Request::is('easy-admin/*/*/edit')))
    disabled 
@endif
@if(in_array($field, $required_fields))
    required
@endif
@if(Request::is('easy-admin/*/*/edit'))
    class="form-control" type="datetime-local" name="{{ $field }}" value="{{ old($field) ?? Carbon\Carbon::parse($data->$field)->format('Y-m-d\TH:i') ?? '' }}">
@else
    class="form-control" type="datetime-local" name="{{ $field }}" value="{{ old($field) ?? '' }}">
@endif