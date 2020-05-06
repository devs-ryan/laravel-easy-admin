@extends('easy-admin::partials.layout')


@section('title', $model)

@section('content')
    <div class="container-fluid m-0 p-3" style="width: 100% !important;">
        <div class="row">
            <div class="col-md-3" id="search-filter-sidebar">
                @include('easy-admin::partials.search-filters')
                @include('easy-admin::partials.need-help-card')
            </div>
            <div class="col-md-9" id="index-content">
                @include('easy-admin::partials.index-content')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function toggleCollapseSearch() {
        if ($("#search-filter-sidebar").hasClass("col-md-3")) {
            
            //collapse sidebar
            $("#search-filter-sidebar").removeClass("col-md-3");
            $("#search-filter-sidebar").addClass("col-md-1");
            
            //expand main content
            $("#index-content").removeClass("col-md-9");
            $("#index-content").addClass("col-md-11");
            
            //change chevron
            $("#search-filter-chevron").removeClass("fa-chevron-left");
            $("#search-filter-chevron").addClass("fa-chevron-right");
            $("#search-filter-collapsed").removeClass("col-3");
            $("#search-filter-collapsed").addClass("col-12");
            
            //hide filter content
            $(".collapse-filter").addClass("d-none");
        }
        else if ($("#search-filter-sidebar").hasClass("col-md-1")) {
            
            //expand sidebar
            $("#search-filter-sidebar").removeClass("col-md-1");
            $("#search-filter-sidebar").addClass("col-md-3");
            
            //collapse main content
            $("#index-content").removeClass("col-md-11");
            $("#index-content").addClass("col-md-9");
            
            //change chevron
            $("#search-filter-chevron").removeClass("fa-chevron-right");
            $("#search-filter-chevron").addClass("fa-chevron-left");
            $("#search-filter-collapsed").removeClass("col-12");
            $("#search-filter-collapsed").addClass("col-3");
            
            //show filter content
            $(".collapse-filter").removeClass("d-none");
        }
    }
</script>
@endsection
