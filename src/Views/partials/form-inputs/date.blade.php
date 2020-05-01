<input 
@if((!in_array('update', $allowed) and Request::is('easy-admin/*/*/edit')) or (!in_array('create', $allowed) and !Request::is('easy-admin/*/*/edit')))
    disabled 
@endif
@if(in_array($field, $required_fields))
    required
@endif
class="form-control" type="date" name="{{ $field }}" value="{{ old($field) ?? $data->$field ?? '' }}">