<div class="pb-2">
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
</div>
<textarea
    style="resize: none;"
    readonly
@if(in_array($field, $required_fields))
    required
@endif
name="{{ $field }}" rows="1" class="form-control">{{ old($field) ?? $data->$field ?? '' }}</textarea>
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
