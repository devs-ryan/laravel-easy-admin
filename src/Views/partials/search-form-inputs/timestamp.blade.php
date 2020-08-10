<div class="form-group">
    <label>{{ ucfirst($index_column) }} From:</label>
    <div class="input-group input-group-sm">
        <input class="form-control" type="datetime-local" id="{{ $index_column }}__from" name="{{ $index_column }}__from" value="{{ Request::get($index_column . '__from') ?? '' }}">
        <div class="input-group-append clear-button" onclick="{{ $index_column }}FromClearInput()">
            <span class="input-group-text">
                <i class="fas fa-eraser"></i>
            </span>
        </div>
    </div>
</div>
<div class="form-group">
    <label>{{ ucfirst($index_column) }} To:</label>
    <div class="input-group input-group-sm">
        <input class="form-control form-control-sm" type="datetime-local" id="{{ $index_column }}__to" name="{{ $index_column }}__to" value="{{ Request::get($index_column . '__to') ?? '' }}">
        <div class="input-group-append clear-button" onclick="{{ $index_column }}ToClearInput()">
            <span class="input-group-text">
                <i class="fas fa-eraser"></i>
            </span>
        </div>
    </div>
</div>


@push('scripts')
<script>
    function {{ $index_column }}FromClearInput() {
        $('#{{ $index_column }}__from').val("");
    }
    function {{ $index_column }}ToClearInput() {
        $('#{{ $index_column }}__to').val("");
    }
</script>
@endpush