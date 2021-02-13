<div class="card">
    <div class="card-body p-0 table-responsive">
        <table class="table mb-0 table-hover sortable">
            <thead class="thead-light">
                <tr>
                    @foreach($index_columns as $index_column)
                        <th scope="col">{{ ucfirst($index_column) }}</th>
                    @endforeach
                    <th scope="col" style="min-width: 114px !important;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($row->makeVisible($index_columns)->toArray() as $key => $column)
                            @if(in_array($key, $index_columns))
                                <td>
                                    @if($column === null)
                                        NULL
                                    @elseif($column === '')
                                        EMPTY
                                    @elseif(
                                        ($column == true || $column == false) &&
                                        (DevsRyan\LaravelEasyAdmin\Services\HelperService::inputType($key, $model_path) === 'boolean')
                                    )
                                        {{ ($column == true) ? 'true' : 'false' }}
                                    @elseif(in_array($key, $file_fields))
                                        {{ $column }}
                                        @if (DevsRyan\LaravelEasyAdmin\Services\FileService::getFileLink($model, $key, $column) !== null)
                                            <br>
                                            <a target="_blank" href="{{ DevsRyan\LaravelEasyAdmin\Services\FileService::getFileLink($model, $key, $column) }}">
                                                <i class="fas fa-eye"></i>
                                                VIEW EXISTING FILE
                                            </a>
                                        @else
                                            <br>
                                            <span class="alert-info">FILE NOT FOUND</span>
                                        @endif
                                    @else
                                        {{ $column }}
                                    @endif
                                </td>
                            @endif
                        @endforeach
                        <th>
                            <a href="/easy-admin/{{$url_model}}/{{ $row->id }}/edit" class="btn btn-info" role="button">
                                @if(in_array('update', $allowed))
                                   <i class="fas fa-edit"></i>
                                @else
                                    <i class="fas fa-eye"></i>
                                @endif
                            </a>
                            @if(in_array('delete', $allowed))
                                <form class="float-right" action="/easy-admin/{{$url_model}}/{{ $row->id }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </th>
                    </tr>
                @endforeach

                @if($data->count() == 0)
                    @include('easy-admin::partials.no-results')
                @endif
            </tbody>
        </table>
    </div>
</div>
<div class="pt-3 d-flex justify-content-center">
    {{ $data->links() }}
</div>
