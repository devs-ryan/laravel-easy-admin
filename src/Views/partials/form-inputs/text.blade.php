<textarea
@if (in_array($field, $wysiwyg_fields))
    id="summernote-{{$field}}"
@endif
@if((!in_array('update', $allowed) and Request::is('easy-admin/*/*/edit')) or (!in_array('create', $allowed) and !Request::is('easy-admin/*/*/edit')))
    disabled
@endif
@if(in_array($field, $required_fields))
    required
@endif
@if (!$textarea)
    style="resize: none;"
@endif
name="{{ $field }}" 
rows="{{ $textarea ? '6' : '1' }}" 
class="form-control">{{ old($field) ?? $data->$field ?? DevsRyan\LaravelEasyAdmin\Services\HelperService::getDefaultValue($field, $model_path) }}</textarea>

@if (in_array($field, $wysiwyg_fields))
    @push('scripts')
        <script>
            $('#summernote-{{$field}}').summernote({
                height: 180
            });
        </script>
    @endpush
@endif

