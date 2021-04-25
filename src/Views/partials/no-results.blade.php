<tr><td colspan="{{ count($index_columns) + 1 }}">
    <div class="jumbotron jumbotron-fluid mb-0">
        <div class="container text-center">
            <h2 class="display-4"><i class="far fa-question-circle"></i> No Results Found</h2>
            <p class="lead">
                Sorry there are no results found.
                @if(in_array('create', $allowed))
                    @php
                        $parent_id_for_create = isset($parent_id) && $parent_id !== null ? "?parent_id=$parent_id" : '';
                    @endphp
                    You can create one: <a href="/easy-admin/{{$url_model}}/create{{$parent_id_for_create}}"> Click Here</a>
                @endif
            </p>
        </div>
    </div>
</td></tr>
