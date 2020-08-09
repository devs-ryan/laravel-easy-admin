<div class="form-group">
    <label>{{ ucfirst($index_column) }}</label> <br>
    <div class="form-check form-check-inline">
        <input
        class="form-check-input" type="radio" name="{{ $index_column }}" value="1"
        @if(Request::get($index_column) !== null && Request::get($index_column) == true)
            checked
        @endif
        >
        <label class="form-check-label">
            True
        </label>
    </div>
    <div class="form-check form-check-inline">
        <input
        class="form-check-input" type="radio" name="{{ $index_column }}" value="0"
        @if(Request::get($index_column) !== null && Request::get($index_column) == false)
            checked
        @endif
        >
        <label class="form-check-label">
            False
        </label>
    </div>
    <div class="form-check form-check-inline">
        <input
        class="form-check-input" type="radio" name="{{ $index_column }}" value=""
        @if(Request::get($index_column) === null)
            checked
        @endif
        >
        <label class="form-check-label">
            Any
        </label>
    </div>
</div>