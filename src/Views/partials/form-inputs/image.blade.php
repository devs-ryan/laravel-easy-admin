<div class="pb-2">
    @if (isset($data->$field))
        <img
            class="img-preview"
            width="80" height="80"
            src="{{ asset(DevsRyan\LaravelEasyAdmin\Services\FileService::getFileLink($model, $field, $data->$field, true)) }}"
            alt="thumbnail"
        />
        <br>
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
    readonly
@if(in_array($field, $required_fields))
    required
@endif
name="{{ $field }}" value="{{ old($field) ?? $data->$field ?? '' }}" class="form-control">
@if(!(!in_array('update', $allowed) and Request::is('easy-admin/*/*/edit')) or !(!in_array('create', $allowed) and !Request::is('easy-admin/*/*/edit')))
    <button
        type="button"
        class="btn btn-success btn-sm mt-2"
        data-toggle="modal"
        data-target="#uploadHandlerModal"
        onclick="openFieldUploadModal(this);"
    >
        <i class="fas fa-file-upload"></i>
        Select / Upload Image
    </button>
@endif
