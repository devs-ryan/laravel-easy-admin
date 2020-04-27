<tr><td colspan="{{ count($index_columns) + 1 }}">
    <div class="jumbotron jumbotron-fluid mb-0">
        <div class="container text-center">
            <h1 class="display-4"><i class="far fa-question-circle"></i> No Results Found</h1>
            <p class="lead">
                Sorry there are no results found.
                @if(in_array('create', $allowed))
                    You can create one: <a href="/easy-admin/{{$url_model}}/create"> Click Here</a>
                @endif
            </p>
        </div>
    </div>
</td></tr>