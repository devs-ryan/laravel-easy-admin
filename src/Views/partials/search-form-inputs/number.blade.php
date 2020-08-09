<div class="form-group">
    <label>{{ ucfirst($index_column) }}</label>
    <div class="input-group input-group-sm">
        <input class="form-control" value="{{ Request::get($index_column) ?? '' }}" type="number" id="{{ $index_column }}" name="{{ $index_column }}">
        <div class="input-group-append clear-button" onclick="{{ $index_column }}ClearInput()">
            <span class="input-group-text">
                <i class="fas fa-eraser"></i>
            </span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function {{ $index_column }}ClearInput() {
        $('#{{ $index_column }}').val("");
    }
</script>
@endpush
