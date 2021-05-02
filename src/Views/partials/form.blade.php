
{{-- Add and lock in relationship field for partials --}}
@if($relationship_column_name !== null)
    <input type="hidden" name="easy_admin_submit_with_parent_id" value="{{ $data->$relationship_column_name ?? Request('parent_id') }}">
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">
            *
            {{ ucwords(str_replace('_', ' ', $relationship_column_name)) }}:
        </label>
        <div class="col-sm-12">
            <input
                type="text" readonly required
                name="{{ $relationship_column_name }}"
                class="form-control"
                value="{{ $data->$relationship_column_name ?? Request('parent_id') }}"
            >
            <small>Note: This is the database ID of the parent that this partial belongs to.</small>
        </div>
    </div>
@endif

@foreach($fields as $field)

    {{-- Skip relationship field that is loaded above --}}
    @php if ($relationship_column_name === $field) continue; @endphp

    <div class="form-group row">
        <label class="col-sm-12 col-form-label">
            @if(in_array($field, $required_fields))
                *
            @endif
            {{ ucwords(str_replace('_', ' ', $field)) }}:
        </label>
        <div class="col-sm-12">
            @switch(DevsRyan\LaravelEasyAdmin\Services\HelperService::inputType($field, $model_path, $select_fields, $image_fields, $file_fields))
                @case('password')
                    @include('easy-admin::partials.form-inputs.password')
                    @break
                @case('boolean')
                    @include('easy-admin::partials.form-inputs.boolean')
                    @break
                @case('decimal')
                @case('integer')
                    @include('easy-admin::partials.form-inputs.number')
                    @break
                @case('date')
                    @include('easy-admin::partials.form-inputs.date')
                    @break
                @case('timestamp')
                    @include('easy-admin::partials.form-inputs.timestamp')
                    @break
                @case('image')
                    @include('easy-admin::partials.form-inputs.image')
                    @break
                @case('file')
                    @include('easy-admin::partials.form-inputs.file')
                    @break
                @case('select')
                    @include('easy-admin::partials.form-inputs.select')
                    @break
                @default
                    @include('easy-admin::partials.form-inputs.text')
                    @break
            @endswitch
        </div>
    </div>
@endforeach

{{-- Submit button --}}
@if((in_array('update', $allowed) and Request::is('easy-admin/*/*/edit')) or (in_array('create', $allowed) and !Request::is('easy-admin/*/*/edit')))
    <div class="text-right">
        <button id="submit-button" type="submit" class="btn btn-primary">
            <i class="far fa-check-circle"></i> Submit
        </button>
        @if(count($model_partials) > 0 && Request::is('easy-admin/*/create'))
            <button type="button" class="btn btn-success" onclick="addPartialBoolToForm()">
                <i class="far fa-arrow-alt-circle-right"></i> Submit + Add Partials
            </button>
        @endif
    </div>
@endif

{{-- Add partial redirect flag to form to check for min/max --}}
@if(count($model_partials) > 0 && Request::is('easy-admin/*/create'))
    @push('scripts')
        <script>
            function addPartialBoolToForm() {
                if ($('#partial_redirect_easy_admin').length < 1) {
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'partial_redirect_easy_admin',
                        name: 'partial_redirect_easy_admin',
                        value: 'true'
                    }).appendTo('form');
                }

                $('#submit-button').click();
            }
        </script>
    @endpush
@endif

@if (count($image_fields) > 0 || count($wysiwyg_fields) > 0)
    <script>var ID = 0;</script>
@endif

@if (count($image_fields) > 0)
    @push('modals')
        @include('easy-admin::partials.upload-handler-modal')
    @endpush

    @push('scripts')
        <script>
            function openFieldUploadModal(sender) {
                const input = $(sender).prev();
                input.attr('id', 'input-text-' + ID);
                $('#uploadHandlerModal').attr('data-target', ID);
                $('#uploadHandlerModal').attr('data-target-input', 'true');
                $('#uploadHandlerModal').attr('data-target-input-name', input.attr('name'));
                ID += 1;

                // trigger get images (defined inside modal)
                getImages();
            }
        </script>
    @endpush
@endif

@if (count($wysiwyg_fields) > 0)

    @push('modals')
        @include('easy-admin::partials.upload-handler-modal')
    @endpush

    @push('scripts')
        <script>

            const appendHtml = `
                <hr>
                <div class="d-flex flex-row-reverse">
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-toggle="modal"
                        data-target="#uploadHandlerModal"
                        onclick="openUploadModal(this);"
                    >
                        Select/Upload Image
                    </button>
                </div>
            `;

            $(document).ready(function() {
                const uploadTarget = $('.form-group.note-form-group.note-group-select-from-files');
                uploadTarget.find("input").remove();
                uploadTarget.append(appendHtml);
            });

            function openUploadModal(sender) {
                const input = $(sender).closest('.note-editor');
                const editorArea = input.find('.note-editable');
                editorArea.attr('id', 'wysiwyg-content-' + ID);
                $('#uploadHandlerModal').attr('data-target', ID);
                $('#uploadHandlerModal').attr('data-target-input', 'false');
                $('.note-modal .modal-header button').click();
                ID += 1;

                // trigger get images (defined inside modal)
                getImages();
            }
        </script>
    @endpush
@endif
