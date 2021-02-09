<input
@if((!in_array('update', $allowed) and Request::is('easy-admin/*/*/edit')) or (!in_array('create', $allowed) and !Request::is('easy-admin/*/*/edit')))
    disabled
@endif
@if(in_array($field, $required_fields) && !isset($data->$field))
    required
@endif
class="form-control-file bg-white border rounded" type="file" name="{{ $field }}" value="{{ old($field) ?? $data->$field ?? '' }}">
@if (isset($data->$field))
    <a target="_blank" href="{{ Raysirsharp\LaravelEasyAdmin\Services\FileService::getFileLink($model, $field, $data->$field) }}">
        <i class="fas fa-eye"></i>
        Show Existing File
    </a>
@endif
