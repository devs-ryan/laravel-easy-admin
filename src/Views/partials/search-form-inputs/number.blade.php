@php
    $lock_field = (isset($relationship_column_name) && $relationship_column_name !== null && $index_column === $relationship_column_name)
        ? true : false;
@endphp

<div class="form-group">
    <label>{{ ucfirst($index_column) }}</label>
    <div class="input-group input-group-sm">
        @if ($lock_field)
            <input disabled class="form-control" value="{{ $parent_id }}" type="number" id="{{ $index_column }}" name="{{ $index_column }}">
        @else
            <input class="form-control" value="{{ Request::get($index_column) ?? '' }}" type="number" id="{{ $index_column }}" name="{{ $index_column }}">
        @endif
        @if (!$lock_field)
            <div class="input-group-append clear-button" onclick="{{ $index_column }}ClearInput()">
                <span class="input-group-text">
                    <i class="fas fa-eraser"></i>
                </span>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function {{ $index_column }}ClearInput() {
        $('#{{ $index_column }}').val("");
    }
</script>
@endpush
