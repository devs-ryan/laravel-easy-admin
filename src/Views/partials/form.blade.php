@foreach($fields as $field)
    <div class="form-group row">
        <label for="inputPassword" class="col-sm-12 col-form-label"><i class="fas fa-pencil-alt"></i> {{ ucwords(str_replace('_', ' ', $field)) }}:</label>
        <div class="col-sm-12">
            @if($field != 'password')
                <textarea name="{{ $field }}" rows="1" class="form-control">{{ old($field) ?? $data->$field ?? '' }}</textarea>
            @else
                <textarea name="{{ $field }}" rows="1" class="form-control">{{ old($field) ?? '**********' }}</textarea>
                <small>Remove stars to update password otherwise it will remain 'as is'</small>
            @endif
        </div>
    </div>
@endforeach
<div class="text-right">
    <button class="btn btn-primary">
        <i class="far fa-check-circle"></i> Submit
    </button>
</div>