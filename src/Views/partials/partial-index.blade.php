<div class="px-md-5">
    <div class="jumbotron">
        <h3>
            <i class="fas fa-folder-open"></i>
            {{ ucwords(str_replace('-', ' ', $url_model)) }} Partials
        </h3>
        <hr class="my-4">

        <ul class="list-group">
            @foreach($nav_items as $link => $nav_title)
                @if (in_array($nav_title, $model_partials))
                    <a href="/easy-admin/{{ $link }}/index?parent_id={{ $id }}">
                        <li class="list-group-item">{{ $nav_title }}</li>
                    </a>
                @endif
            @endforeach
        </ul>
    </div>
</div>
