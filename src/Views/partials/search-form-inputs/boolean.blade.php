<div class="form-group">
    <label>{{ ucfirst($index_column) }}</label> <br>
    <div class="form-check form-check-inline">
        <input
        class="form-check-input" type="checkbox" name="{{ $index_column }}" value="1">
        <label class="form-check-label">
            True
        </label>
    </div>
    <div class="form-check form-check-inline">
        <input
        class="form-check-input" type="checkbox" name="{{ $index_column }}" value="0">
        <label class="form-check-label">
            False
        </label>
    </div>
    <div class="form-check form-check-inline">
        <input
        class="form-check-input" type="checkbox" name="{{ $index_column }}" value="NULL">
        <label class="form-check-label">
            NULL
        </label>
    </div>
</div>