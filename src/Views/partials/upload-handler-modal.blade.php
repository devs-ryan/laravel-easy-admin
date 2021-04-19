<div id="uploadHandlerModal" class="modal fade" tabindex="-1" role="dialog">
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
                                <div class="row mt-2">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="image-type" class="form-control form-control-sm">
                                                <option>All images</option>
                                                <option>Uploaded to this model</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="image-type" class="form-control form-control-sm">
                                                <option>All dates</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" placeholder="Search">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-sm btn-primary btn-block">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                </div>
                            </div>

                            <div class="container image-list-main py-3">
                                <div class="row">
                                    @foreach (range(1, 10) as $item)
                                        <div class="col-sm-6 col-lg-4 pb-4 image-list-col {{ $loop->index == 0 ? ' image-list-col--selected' : '' }}">
                                            <button type="button" class="btn btn-link p-0 m-0">
                                                <img
                                                    class="image-list-img img-fluid{{ $loop->index == 0 ? ' image-list-img--selected' : '' }}"
                                                    src="https://picsum.photos/600"
                                                    alt="Image Preview"
                                                />
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 bg-grey detail-list border-left">
                            <div class="container image-details">
                                <strong class="text-secondary">DETAILS:</strong>
                                <div class="row mt-3">
                                    <div class="col-lg-6">
                                        <img
                                            class="details-img img-fluid"
                                            src="https://picsum.photos/300"
                                            alt="Image Preview"
                                        />
                                    </div>
                                    <div class="col-lg-6">
                                        <strong>Name.png</strong> <br>
                                        <small>April 17th, 2021</small> <br>
                                        <small>9 KB</small> <br>
                                        <small>220w by 250h</small> <br>
                                        <button type="button" class="text-danger btn btn-sm btn-link p-0 m-0">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <hr>
                                        <form action="">
                                            <div class="form-group">
                                                <label class="small">Alt Text</label>
                                                <input type="text" class="form-control form-control-sm">
                                                <small class="form-text text-muted">
                                                    Describe the purpose of this photo.
                                                </small>
                                            </div>
                                            <div class="form-group">
                                                <label class="small">Title</label>
                                                <input required type="text" class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group">
                                                <label class="small">Description</label>
                                                <textarea class="form-control form-control-sm" rows="2"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-arrow-circle-up"></i> Update
                                                </button>
                                            </div>
                                            <div class="form-group">
                                                <label class="small">File Url</label>
                                                <input required type="text" class="form-control form-control-sm" disabled value="https://example.cpm">
                                                <button type="button" class="btn btn-sm btn-outline-dark mt-3">
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
                    <div class="drag-file-input" action="/">
                        <label class="upload-title text-center pt-5" for="img">
                            <strong>Drag files to upload or click to choose file:</strong>
                        </label>
                        <input class="drop-input" type="file" id="img" name="img" accept="image/*">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Insert Image</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function showMediaGallery() {
            $('#mediaGallery').removeClass('d-none');
            $('#uploadForm').addClass('d-none');
            $('#mediaGalleyToggle').addClass('active');
            $('#uploadFormToggle').removeClass('active');
        }
        function showUploadForm() {
            $('#mediaGallery').addClass('d-none');
            $('#uploadForm').removeClass('d-none');
            $('#mediaGalleyToggle').removeClass('active');
            $('#uploadFormToggle').addClass('active');
        }
    </script>
@endpush

@push('styles')
    <style>
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
            z-index: 9;
            width: 80%;
            height: 12rem;
            border-style: dashed;
            margin: 2rem auto;
            background-color: transparent;
        }
        @media only screen and (min-width : 992px) {
            .detail-list, .image-list {
                max-height: calc(100vh - 16rem);
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
