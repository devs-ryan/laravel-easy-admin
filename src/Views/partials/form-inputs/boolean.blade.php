<div class="form-check form-check-inline">
    <input 
    @if(isset($data) && $data->$field == true)
        checked
    @endif
    @if(in_array($field, $required_fields))
        required
    @endif
    class="form-check-input" type="radio" name="{{ $field }}" value="1" checked>
    <label class="form-check-label">
        True
    </label>
</div>
<div class="form-check form-check-inline">
    <input 
    @if(isset($data) && $data->$field == false)
        checked
    @endif
    @if(in_array($field, $required_fields))
        required
    @endif
    class="form-check-input" type="radio" name="{{ $field }}" value="0">
    <label class="form-check-label">
        False
    </label>
</div>
@if(!in_array($field, $required_fields))
    <div class="form-check form-check-inline">
        <input 
        @if(isset($data) && $data->$field === NULL)
            checked
        @endif
        class="form-check-input" type="radio" name="{{ $field }}" value="">
        <label class="form-check-label">
            NULL
        </label>
    </div>
@endif