@php
    $token = urlencode((substr(env('APP_KEY'), 0, 7) === "base64:") ? substr(env('APP_KEY'), 7) : env('APP_KEY'));
@endphp


<div
    id="uploadHandlerModal"
    data-target="69420"
    class="modal fade"
    tabindex="-1"
    role="dialog"
>
    <div class="modal-dialog modal-xl py-2 py-md-4 my-0" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Select / Upload Images
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pr-0">

                {{-- Tabs --}}
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a
                            id="mediaGalleyToggle"
                            class="nav-link active"
                            href="#mediaGallery"
                            data-target="#mediaGallery"
                            onclick="showMediaGallery();"
                        >
                            Media Gallery
                        </a>
                    </li>
                    <li class="nav-item">
                        <a
                            id="uploadFormToggle"
                            class="nav-link"
                            href="#uploadForm"
                            data-target="#uploadForm"
                            onclick="showUploadForm();"
                        >
                            Upload Images
                        </a>
                    </li>
                </ul>


                {{-- media Gallery --}}
                <div class="mediaGallery" id="mediaGallery">
                    <div class="row container px-0 mx-0">
                        <div class="col-lg-8 image-list">

                            <div class="container image-filters">
                                <strong class="text-secondary">FILTERS:</strong>
                                <input id="current_page_number" type="hidden" name="current_page_number" value="1">
                                <div class="row mt-2">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select id="img_model_filter" name="model_filter" class="form-control form-control-sm">
                                                <option value="all">All Images</option>
                                                <option id="img_model_filter_model_option" value="{{ $model }}" selected>Current Model</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select id="img_date_filter" name="date_filter" class="form-control form-control-sm">
                                                <option value="all">All dates</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input id="img_search" type="text" name="search" class="form-control form-control-sm" placeholder="Search">
                                                <div class="input-group-append clear-button" onclick="this.previousElementSibling.value = '';">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-eraser"></i>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button onclick="filtersUpdated();" class="btn btn-sm btn-primary btn-block">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                </div>
                            </div>

                            <div class="container image-list-main py-3">
                                {{-- delete message --}}
                                <div id="img-delete-msg" class="form-group d-none">
                                    <div class="alert alert-success w-100 d-inline-block" role="alert">
                                        <small>
                                            <i class="far fa-check-square"></i>
                                            Image deleted successfully
                                        </small>
                                    </div>
                                </div>
                                {{-- Results --}}
                                <div class="row d-none" id="image-results-container"></div>
                                {{-- Loading/NoResults Messages --}}
                                <div class="row" id="image-messages-container">
                                    <div class="col-12">
                                        <div class="jumbotron jumbotron-fluid mb-0">
                                            <div class="container text-center">
                                                {{-- Loading --}}
                                                <div id="image-results-loading">
                                                    <h2>Loading...</h2>
                                                    <p class="lead">
                                                        <i class="fas fa-spinner fa-pulse fa-2x"></i>
                                                    </p>
                                                </div>

                                                {{-- NO RESULTS --}}
                                                <div id="image-results-no-results" class="d-none">
                                                    <h2 class="display-4"><i class="far fa-question-circle"></i> No Results Found</h2>
                                                    <p class="lead">
                                                        Sorry there are no results found.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <nav class="d-flex justify-content-center" aria-label="Image Results Links">
                                    <ul id="img-results-links" class="pagination">
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-lg-4 bg-grey detail-list border-left">
                            <div id="image-details" class="container image-details">
                                <strong class="text-secondary">DETAILS:</strong>
                                <div class="row mt-3">
                                    <div class="col-lg-6">
                                        <img
                                            id="image-result-preview"
                                            class="details-img img-fluid"
                                            src=""
                                            alt="Image Preview"
                                        />
                                    </div>
                                    <div class="col-lg-6">
                                        <small><strong id="image-result-file-name" class="wrap">Name.png</strong></small> <br>
                                        <small id="image-result-date">April 20th, 6969</small> <br>
                                        <small id="image-result-size">9 KB</small> <br>
                                        <small>
                                            <span id="image-result-width">
                                                250
                                            </span>w by
                                            <span id="image-result-height">
                                                250
                                            </span>h
                                        </small> <br>
                                        <form id="image-result-delete-form" method="POST" action="{{ route('easy-admin-image-delete', 0) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger btn btn-sm btn-link p-0 m-0">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <hr>
                                        <form id="image-result-update-form" method="POST" action="{{ route('easy-admin-image-update', 0) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="form-group">
                                                <label class="small">Alt Text</label>
                                                <input id="image-result-alt" name="alt" type="text" class="form-control form-control-sm">
                                                <small class="form-text text-muted">
                                                    Describe the purpose of this photo.
                                                </small>
                                            </div>
                                            <div class="form-group">
                                                <label class="small">Title</label>
                                                <input id="image-result-title" name="title" required type="text" class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group">
                                                <label class="small">Description</label>
                                                <textarea id="image-result-description" name="description" class="form-control form-control-sm" rows="2"></textarea>
                                            </div>
                                            <div id="img-update-msg" class="form-group d-none">
                                                <div class="alert py-2 m-0 alert-success w-100 d-inline-block" role="alert">
                                                    <small>
                                                        <i class="far fa-check-square"></i>
                                                        Image updated successfully
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-arrow-circle-up"></i> Update
                                                </button>
                                            </div>
                                            <div class="form-group">
                                                <label class="small">File Url</label>
                                                <input id="image-result-copy-url" required type="text" class="form-control form-control-sm" value="" readonly>
                                                <button onclick="copyImgFileUrl();" type="button" class="btn btn-sm btn-outline-dark mt-3">
                                                    <i class="fas fa-copy"></i> Copy to clipboard
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- upload form --}}
                <div class="uploadForm d-none" id="uploadForm">
                    <form id="image-submit-form" method="POST" class="drag-file-input" action="{{ route('easy-admin-image-store') }}" enctype="multipart/form-data">
                        @csrf
                        <label class="upload-title text-center pt-5" for="img">
                            <strong id="upload-message">Drag files to upload or click to choose file:</strong>
                        </label>
                        <input onChange="document.getElementById('image-submit-button').click();" class="drop-input" type="file" id="img" name="img" accept="image/*">
                        <input type="hidden" name="model" value="{{ $model }}">
                        <button id="image-submit-button" class="d-none" type="submit">Submit</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button id="insert-image-button" onclick="insertImage();" type="button" class="btn btn-info" data-dismiss="modal">Insert Image</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // filters updated
        function filtersUpdated() {
            page = $('#current_page_number').val();
            getImages(page);
        }

        //reset search inputs
        function resetSearch() {
            $('#img_model_filter').val($('#img_model_filter_model_option').val());
            $('#img_date_filter').val('all');
            $('#img_search').val('');
        }

        // insert image
        function insertImage() {
            const targetId = $('#uploadHandlerModal').attr('data-target');

            if ($('#uploadHandlerModal').attr('data-target-input') == 'true') {
                const inputBox = $('#input-text-' + targetId);
                const imageName = $('#image-result-preview').attr('src').replace('/square/', '/original/').split('/').pop();
                inputBox.val(imageName);
            }
            else {
                const inputBox = $('#wysiwyg-content-' + targetId);
                const currentHtml = inputBox.html();
                const imgPath = $('#image-result-preview').attr('src').replace('/square/', '/original/');
                inputBox.html(currentHtml.replace('<p><br></p>', '') + `<p><img style="width: 25%;" src="${imgPath}"/></p>`);
            }
        }

        // changes tab to image gallery
        function showMediaGallery() {
            $('#mediaGallery').removeClass('d-none');
            $('#uploadForm').addClass('d-none');
            $('#mediaGalleyToggle').addClass('active');
            $('#uploadFormToggle').removeClass('active');
            $('#insert-image-button').removeClass('d-none');
        }

        // changes tab to upload form
        function showUploadForm() {
            $('#mediaGallery').addClass('d-none');
            $('#uploadForm').removeClass('d-none');
            $('#mediaGalleyToggle').removeClass('active');
            $('#uploadFormToggle').addClass('active');
            $('#insert-image-button').addClass('d-none');
        }

        function copyImgFileUrl() {
            const copyText = document.getElementById("image-result-copy-url");
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */
            document.execCommand("copy");
        }

        //conver date string to month and year
        function convertDate(date) {
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            date = date.substring(0, 10);
            let seperated = date.split('-');
            const year = seperated[0];
            const month = months[parseInt(seperated[1], 10)];
            const day = seperated[2];

            return {
                year, month, day
            };
        }

        // select an image
        function selectImage(imgId, src, fileName, title, alt, description, width, height, size, created_at) {
            const dateInfo = convertDate(created_at);
            let dayExt = 'th';
            if (dateInfo.day == 1) dayExt = 'st';
            if (dateInfo.day == 2) dayExt = 'nd';
            if (dateInfo.day == 3) dayExt = 'rd';

            // get base URL
            const getUrl = window.location;
            const baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

            $('#image-result-file-name').html(title);
            $('#image-result-title').val(title);
            $('#image-result-alt').val(alt);
            $('#image-result-description').val(description);
            $('#image-result-size').html(size);
            $('#image-result-width').html(width);
            $('#image-result-height').html(height);
            $('#image-result-copy-url').val(src);
            $('#image-result-preview').attr("src", src);
            $('#image-result-date').html(`${dateInfo.month} ${dateInfo.day}, ${dateInfo.year}`)

            // update form actions
            const currentAction = $('#image-result-update-form').attr('action');
            const newAction = currentAction.substr(0, currentAction.lastIndexOf("/")) + `/${imgId}`;
            $('#image-result-update-form').attr('action', newAction);
            $('#image-result-delete-form').attr('action', newAction);

            //selected outline update
            $(".image-list-col--selected").removeClass("image-list-col--selected");
            $(".image-list-img--selected").removeClass("image-list-img--selected");
            $(`#image-list-item-col-${imgId}`).addClass("image-list-col--selected");
            $(`#image-list-item-img-${imgId}`).addClass("image-list-img--selected");
        }

        // store
        $("#image-submit-form").submit(function(e) {
            e.preventDefault();

            $('#upload-message').html('Uploading...<br><i class="fas fa-spinner fa-pulse fa-2x"></i>');
            resetSearch();

            var form = $(this);
            var formData = new FormData(this);
            var url = form.attr('action');

            // check if this is for a specific field
            var appendText = "";
            if ($('#uploadHandlerModal').attr('data-target-input') == 'true') {
                const field_name = $('#uploadHandlerModal').attr('data-target-input-name');
                appendText = `&field=${field_name}`;
            }

            $.ajax({
                type: "POST",
                url: `${url}/?token={{$token}}${appendText}`,
                data: formData,
                success: function (data) {
                    $('#img').val('');
                    $('#upload-message').html('Drag files to upload or click to choose file:');
                    getImages();
                    showMediaGallery();
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });

        // update
        $("#image-result-update-form").submit(function(e) {

            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url + '/?token={{$token}}',
                data: form.serialize(), // serializes the form's elements.
                success: function(data) {
                    // show success
                    $('#img-update-msg').removeClass('d-none');
                    setTimeout(function(){
                        $('#img-update-msg').addClass('d-none');
                     }, 3000);

                     // update select button attributes
                    const id = url.substr(url.lastIndexOf('/') + 1);
                    const onclickAction = $(`#image-list-item-btn-${id}`).attr("onclick");


                    const urlParams = new URLSearchParams(form.serialize());
                    const alt = urlParams.get('alt');
                    const title = urlParams.get('title');
                    const desc = urlParams.get('description');


                    let vars = onclickAction.replace(/\s+/g, '').replace('selectImage(', '').replace(')', '');
                    vars = vars.split(',');
                    let args = "";
                    $.each(vars, function( index, value ) {
                        if (index == 3) value = `'${title}'`;
                        if (index == 4) value = `'${alt}'`;
                        if (index == 5) value = `'${desc}'`;

                        if (args != "") args += ", " + value;
                        else args += value;
                    });

                    $(`#image-list-item-btn-${id}`).attr("onclick", `selectImage(${args})`);
                }
            });
        });

        // delete
        $("#image-result-delete-form").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.

            var form = $(this);
            var url = form.attr('action');

            // check if this is for a specific field
            var appendText = "";
            if ($('#uploadHandlerModal').attr('data-target-input') == 'true') {
                const field_name = $('#uploadHandlerModal').attr('data-target-input-name');
                appendText = `&field=${field_name}`;
            }

            $.ajax({
                type: "POST",
                url: `${url}/?token={{$token}}${appendText}`,
                data: form.serialize(), // serializes the form's elements.
                success: function(data) {
                    // show success
                    $('#img-delete-msg').removeClass('d-none');
                    setTimeout(function(){
                        $('#img-delete-msg').addClass('d-none');
                    }, 3000);

                    getImages();
                }
            });
        });

        // index
        function getImages(page = 1) {
            let results = "";
            $('#image-messages-container').removeClass('d-none');
            $('#image-results-loading').removeClass('d-none');
            $("#image-results-container").addClass('d-none');
            $('#image-results-no-results').addClass('d-none');
            $('#image-details').removeClass('d-none');
            $('#insert-image-button').removeClass('d-none');
            $('#current_page_number').val(page);

            // check if this is for a specific field
            var appendText = "";
            if ($('#uploadHandlerModal').attr('data-target-input') == 'true') {
                const field_name = $('#uploadHandlerModal').attr('data-target-input-name');
                $('#img_model_filter').attr('disabled','disabled');
                appendText = `&field_filter=${field_name}`;
            }

            // get search inputs
            const model_filter = $('#img_model_filter').val();
            const date_filter = $('#img_date_filter').val();
            const search = $('#img_search').val();

            $.ajax({
                url: `/easy-admin/api/images/?token={{$token}}&page=${page}&model_filter=${model_filter}&date_filter=${date_filter}&search=${search}${appendText}`,
                success: function(result) {

                const images = result.image_results.data;
                if (images.length == 0) {
                    $('#image-results-loading').addClass('d-none');
                    $('#image-results-no-results').removeClass('d-none');
                    $('#image-details').addClass('d-none');
                    $('#insert-image-button').addClass('d-none');
                    return;
                }
                // build up image results
                $.each(images, function( index, value ) {
                    let appendCol = '', appendImg = '';
                    if (index == 0) {
                        appendCol = index == 0 ? ' image-list-col--selected' : '';
                        appendImg = index == 0 ? ' image-list-img--selected' : '';
                        selectImage(
                            value.id,
                            value.file_path,
                            value.file_name,
                            value.title,
                            value.alt ? value.alt : '',
                            value.description ? value.description : '',
                            value.width,
                            value.height,
                            value.size,
                            value.created_at
                        )
                    }

                    results += `
                    <div id="image-list-item-col-${value.id}" class="col-sm-6 col-lg-4 pb-4 image-list-col${appendCol}">
                            <button
                                id="image-list-item-btn-${value.id}"
                                onclick="selectImage(
                                    ${value.id},
                                    '${value.file_path.replace('/original/', '/square/')}',
                                    '${value.file_name}',
                                    '${value.title}',
                                    '${value.alt ? value.alt : ''}',
                                    '${value.description ? value.description : ''}',
                                    '${value.width}',
                                    '${value.height}',
                                    '${value.size}',
                                    '${value.created_at}'
                                )"
                                type="button"
                                class="btn btn-link p-0 m-0"
                            >
                                <img
                                    id="image-list-item-img-${value.id}"
                                    class="image-list-img img-fluid${appendImg}"
                                    src="${value.file_path.replace('/original/', '/square/')}"
                                    alt="${value.alt}"
                                />
                            </button>
                        </div>
                    `;
                });

                // build up search dates
                var date_options = '<option value="all">All dates</option>';
                $.each(result.dates, function( index, value ) {
                    const val = `${value.month}|${value.year}`;

                    date_options += `<option value="${val}" ${val == date_filter ? ' selected' : ''}>
                        ${value.month_name}, ${value.year}
                    </option>`;
                });
                $('#img_date_filter').html(date_options);

                // build up paginators
                if (result.image_results.links.length > 3) {
                    const links = result.image_results.links;
                    var linksHtml = "";

                    $.each(links, function( index, value ) {
                        var page = null;
                        var model_filter = null;
                        var date_filter = null;
                        var search = null;

                        if (value.url) {
                            const url = new URL(value.url);
                            const urlParams = new URLSearchParams(url.search);
                            page = urlParams.get('page');
                        }

                        const functionClick = page ? `onclick="getImages(${page});"` : '';

                        linksHtml += `<li class="page-item${value.active ? ' active' : ''}">
                            <a
                                class="page-link"
                                href="javascript:void(0);"
                                ${functionClick}
                            >
                                ${value.label}
                            </a>
                        </li>`;
                    });
                    $('#img-results-links').html(linksHtml);
                }

                $("#image-results-container").html(results);
                $('#image-results-loading').addClass('d-none');
                $('#image-messages-container').addClass('d-none');
                $("#image-results-container").removeClass('d-none');

            }});
        }
    </script>
@endpush

@push('styles')
    <style>
        .wrap {
            white-space: pre-wrap;      /* CSS3 */
            white-space: -moz-pre-wrap; /* Firefox */
            white-space: -pre-wrap;     /* Opera <7 */
            white-space: -o-pre-wrap;   /* Opera 7 */
            word-wrap: break-word;      /* IE */
        }
        .drag-file-input {
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .upload-title {
            position: absolute;
            top: 4rem;
            left: 50%;
            font-size: 1.3rem;
            font-weight: normal;
            transform: translateX(-50%);
            z-index: 6;
        }
        .drop-input {
            position: relative;
            z-index: 9;
            width: 80%;
            height: 12rem;
            border-style: dashed;
            margin: 2rem auto;
            background-color: transparent;
        }
        .drop-input::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 2rem;
            background-color: white;
            left: 0;
            top: 0;
        }
        @media only screen and (min-width : 992px) {
            .detail-list, .image-list {
                max-height: calc(100vh - 16.5rem);
                overflow-y: scroll;
            }
        }

        .image-list-img--selected {
            position: relative;
            border: 0.4rem #17a2b8 solid;
        }
        .image-list-col--selected::after {
            content: '\2713';
            color: white;
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.4rem;
            height: 2rem;
            width: 2rem;
            top: -0.5rem;
            right: 0;
            background-color: #17a2b8;
            border: 0.2rem white solid;
            box-shadow: 0 0 0 1px black;
        }
    </style>
@endpush
