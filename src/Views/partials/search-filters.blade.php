<div class="card mb-3 bg-light">
    <div class="card-header">
        <div class="row">
            <div class="col-9 collapse-filter">
                <i class="fas fa-search"></i>
                Search Filters
            </div>
            <div id="search-filter-collapsed" class="col-3">
                <i class="fas fa-search d-none" id="collapsed-filter-hint"></i>
                <i id="search-filter-chevron" class="fas fa-chevron-left float-right pt-1" onclick="toggleCollapseSearch()"></i>
            </div>
        </div>
    </div>
    <div class="card-body collapse-filter">
        <form method="get" action="?">

            @if(isset($parent_id) && $parent_id !== null)
                <input type="hidden" name="parent_id" value="{{ $parent_id }}">
            @endif

            @foreach($index_columns as $index_column)
                @switch(DevsRyan\LaravelEasyAdmin\Services\HelperService::inputType($index_column, $model_path))
                    @case('password')
                        {{-- Do not include search for password --}}
                        @break
                    @case('boolean')
                        @include('easy-admin::partials.search-form-inputs.boolean')
                        @break
                    @case('decimal')
                    @case('integer')
                       @include('easy-admin::partials.search-form-inputs.number')
                        @break
                    @case('date')
                        @include('easy-admin::partials.search-form-inputs.date')
                        @break
                    @case('timestamp')
                         @include('easy-admin::partials.search-form-inputs.timestamp')
                        @break
                    @default
                        @include('easy-admin::partials.search-form-inputs.text')
                        @break
                @endswitch
            @endforeach

            <button class="btn btn-primary" type="submit" aria-pressed="true">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>
</div>
