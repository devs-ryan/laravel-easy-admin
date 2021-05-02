<div class="pb-2">
    @if (isset($data->$field) && DevsRyan\LaravelEasyAdmin\Services\FileService::getFileLink($model, $field, $data->$field))
        @if(DevsRyan\LaravelEasyAdmin\Services\FileService::checkIsImage($model, $field, $data->$field))
            <img
                class="img-preview"
                width="80" height="80"
                src="{{ asset(DevsRyan\LaravelEasyAdmin\Services\FileService::getFileLink($model, $field, $data->$field, true)) }}"
                alt="thumbnail"
            />
            <br>
        @endif
        <a target="_blank" href="{{ DevsRyan\LaravelEasyAdmin\Services\FileService::getFileLink($model, $field, $data->$field) }}">
            <small>
                <i class="fas fa-eye"></i>
                VIEW FILE
            </small>
        </a>
    @else
        <span>
            <i class="fas fa-eye-slash"></i>
            FILE NOT FOUND
        </span>
    @endif
</div>
<input
    @if((!in_array('update', $allowed) and Request::is('easy-admin/*/*/edit'))
        || (!in_array('create', $allowed) and !Request::is('easy-admin/*/*/edit')))
        disabled
    @endif
    @if(in_array($field, $required_fields) && !isset($data->$field))
        required
    @endif
    class="form-control-file bg-white border rounded"
    type="file"
    name="{{ $field }}"
    value="{{ old($field) ?? $data->$field ?? '' }}"
/>

