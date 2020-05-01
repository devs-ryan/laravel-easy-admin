@if(Request::is('easy-admin/*/*/edit'))
    <input 
    @if((!in_array('update', $allowed) and Request::is('easy-admin/*/*/edit')) or (!in_array('create', $allowed) and !Request::is('easy-admin/*/*/edit')))
        disabled 
    @endif
    @if(in_array($field, $required_fields))
        required
    @endif
    class="form-control" type="password" name="{{ $field }}" value="{{ old($field) ?? '**********' }}">
    <small>Remove stars to update password otherwise it will remain 'as is'</small>
@else
    <input 
    @if((!in_array('update', $allowed) and Request::is('easy-admin/*/*/edit')) or (!in_array('create', $allowed) and !Request::is('easy-admin/*/*/edit')))
        disabled 
    @endif
    @if(in_array($field, $required_fields))
        required
    @endif
    class="form-control" type="password" name="{{ $field }}" value="{{ old($field) ?? '' }}">
@endif