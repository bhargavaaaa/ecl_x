@extends('layouts.app')

@section('title')
    Edit Form
@endsection

@section('css')
    @include('layouts.includes.datatable_styles')
    @include('layouts.includes.datatable_sticky_lastcolumn')
    @include('layouts.includes.sweetalert2_styles')
    @include('layouts.includes.magnific_popup_styles')
@endsection

@section('content')
    <div class="content">

        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="row w-100">
                    <div class="col-auto">
                        <h3 class="block-title">
                            Edit Form
                        </h3>
                    </div>
                    <div class="col text-end">
                        <div class="row d-flex justify-content-end">
                            <div class="col-auto p-0 pe-1">
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#add_rich_text_model">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    Add Rich Text
                                </a>
                            </div>
                            <div class="col-auto p-0 pe-1">
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#add_image_model">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    Add Image
                                </a>
                            </div>
                            <div class="col-auto p-0 pe-1">
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#add_title_model">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    Add Title
                                </a>
                            </div>
                            <div class="col-auto p-0">
                                <a href="javascript:history.back()" class="btn btn-sm btn-primary">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                    Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <form action="{{ route('form.update', ['form' => $form->id]) }}"
                        method="POST" id="form_form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" placeholder="Name.." value="{{ old('name', $form->name) }}">
                                @error('name')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="slug">Slug</label>
                                <input type="text" class="form-control"
                                    id="slug" name="slug" placeholder="Slug.." value="{{ old('slug', $form->slug) }}" disabled>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                    <!-- END Form Labels on top - Default Style -->
                </div>
                <hr/>
                <label>Titles</label>
                <hr/>
                <div class="row mt-3">
                    <table class="table table-striped title_datatable table-nowrap w-100">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <hr/>
                <label>Images</label>
                <hr/>
                <div class="row mt-3">
                    <table class="table table-striped image_datatable table-nowrap w-100">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <hr/>
                <label>Rich Texts</label>
                <hr/>
                <div class="row mt-3">
                    <table class="table table-striped rich_text_datatable table-nowrap w-100">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Text</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('form.add_title')
    @include('form.edit_title')
    @include('form.add_image')
    @include('form.edit_image')
    @include('form.add_rich_text')
    @include('form.edit_rich_text')
@endsection

@section('js')
    @include('layouts.includes.datatable_scripts')
    @include('layouts.includes.validation_scripts')
    @include('layouts.includes.sweetalert2_scripts')
    @include('layouts.includes.magnific_popup_scripts')
    @include('layouts.includes.ckeditor_scripts')
    <script>
        $(function() {
            let isLoading = false;
            Codebase.helpersOnLoad(['js-ckeditor', 'jq-magnific-popup', 'jq-validation']);

            jQuery("#form_form").validate({
                rules: {
                    "name": {
                        required: true,
                        remote: '{{ route('form.check-name-unique', $form) }}'
                    }
                },
            });

            let title_datatable = $(".title_datatable").DataTable({
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('form.titles.item_get', $form) }}",
                columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                    {
                        data: 'name'
                    },
                    {
                        data: 'value'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $(document).on('submit', '#add_title_form', function (e) {
                e.preventDefault();
                if (isLoading) {
                    return false;
                }

                isLoading = true;

                let formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: '{{ route("form.titles.item_add", $form) }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status == true) {
                            $(".add_title_close").trigger("click");
                            title_datatable.ajax.reload();
                            Codebase.helpers('jq-notify', {
                                align: 'right',
                                from: 'top',
                                type: 'success',
                                icon: 'fa fa-check me-1',
                                message: response.message
                            });
                        } else {
                            Codebase.helpers('jq-notify', {
                                z_index: 99999,
                                align: 'right',
                                from: 'top',
                                type: 'danger',
                                icon: 'fa fa-times me-1',
                                message: response.message
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Codebase.helpers('jq-notify', {
                            z_index: 99999,
                            align: 'right',
                            from: 'top',
                            type: 'danger',
                            icon: 'fa fa-times me-1',
                            message: (xhr.status === 422) ? xhr.responseJSON.message : xhr.responseText
                        });
                    },
                    complete: function () {
                        isLoading = false;
                    }
                });
            });

            $(document).on('click', '.update_title_item', function () {
                let modal = $('#edit_title_model');

                modal.find('input[name="detail_id"]').val($(this).data('id'));
                modal.find('input[name="name"]').val($(this).data('name'));
                modal.find('input[name="value"]').val($(this).data('value'));

                modal.modal('show');
            });

            $(document).on('submit', '#edit_title_form', function (e) {
                e.preventDefault();
                if (isLoading) {
                    return false;
                }

                isLoading = true;

                let formData = new FormData(this);
                let detail_id = $(this).find('input[name="detail_id"]').val();
                $.ajax({
                    type: 'POST',
                    url: ('{{ route("form.titles.item_update", [$form, ':detail_id']) }}').replace(':detail_id', detail_id),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status == true) {
                            $(".edit_title_close").trigger("click");
                            title_datatable.ajax.reload();
                            Codebase.helpers('jq-notify', {
                                align: 'right',
                                from: 'top',
                                type: 'success',
                                icon: 'fa fa-check me-1',
                                message: response.message
                            });
                        } else {
                            Codebase.helpers('jq-notify', {
                                z_index: 99999,
                                align: 'right',
                                from: 'top',
                                type: 'danger',
                                icon: 'fa fa-times me-1',
                                message: response.message
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Codebase.helpers('jq-notify', {
                            z_index: 99999,
                            align: 'right',
                            from: 'top',
                            type: 'danger',
                            icon: 'fa fa-times me-1',
                            message: (xhr.status === 422) ? xhr.responseJSON.message : xhr.responseText
                        });
                    },
                    complete: function () {
                        isLoading = false;
                    }
                });
            });

            $(document).on("click", ".delete_title_item", function(e) {
                let deleteId = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this title!",
                    icon: "warning",
                    showCancelButton: !0,
                    customClass: {
                        confirmButton: "btn btn-danger m-1",
                        cancelButton: "btn btn-secondary m-1",
                    },
                    confirmButtonText: "Yes, delete it!",
                    html: !1,
                    preConfirm: (e) =>
                        new Promise((e) => {
                            setTimeout(() => {
                                e();
                            }, 50);
                        }),
                }).then((resp) => {
                    if(resp.value) {
                        jQuery.ajax({
                            url: ('{{ route('form.titles.item_delete', [$form, ':detailid']) }}').replace(':detailid', deleteId),
                            method: 'DELETE',
                            success: function(result) {
                                if(result.status == true) {
                                    title_datatable.ajax.reload();
                                    Codebase.helpers('jq-notify', {
                                        align: 'right',
                                        from: 'top',
                                        type: 'success',
                                        icon: 'fa fa-check me-1',
                                        message: result.message
                                    });
                                } else {
                                    Codebase.helpers('jq-notify', {
                                        z_index: 99999,
                                        align: 'right',
                                        from: 'top',
                                        type: 'danger',
                                        icon: 'fa fa-times me-1',
                                        message: result.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Codebase.helpers('jq-notify', {
                                    z_index: 99999,
                                    align: 'right',
                                    from: 'top',
                                    type: 'danger',
                                    icon: 'fa fa-times me-1',
                                    message: (xhr.status === 422) ? xhr.responseJSON.message : xhr.responseText
                                });
                            }
                        });
                    }
                });
            });

            let image_datatable = $(".image_datatable").DataTable({
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('form.images.item_get', $form) }}",
                columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                    {
                        data: 'name'
                    },
                    {
                        data: 'image'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $(document).on('submit', '#add_image_form', function (e) {
                e.preventDefault();
                if (isLoading) {
                    return false;
                }

                isLoading = true;

                let formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: '{{ route("form.images.item_add", $form) }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status == true) {
                            $(".add_image_close").trigger("click");
                            image_datatable.ajax.reload();
                            Codebase.helpers('jq-notify', {
                                align: 'right',
                                from: 'top',
                                type: 'success',
                                icon: 'fa fa-check me-1',
                                message: response.message
                            });
                        } else {
                            Codebase.helpers('jq-notify', {
                                z_index: 99999,
                                align: 'right',
                                from: 'top',
                                type: 'danger',
                                icon: 'fa fa-times me-1',
                                message: response.message
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Codebase.helpers('jq-notify', {
                            z_index: 99999,
                            align: 'right',
                            from: 'top',
                            type: 'danger',
                            icon: 'fa fa-times me-1',
                            message: (xhr.status === 422) ? xhr.responseJSON.message : xhr.responseText
                        });
                    },
                    complete: function () {
                        isLoading = false;
                    }
                });
            });

            $(document).on('click', '.update_image_item', function () {
                let modal = $('#edit_image_model');

                modal.find('input[name="detail_id"]').val($(this).data('id'));
                modal.find('input[name="name"]').val($(this).data('name'));

                modal.modal('show');
            });

            $(document).on('submit', '#edit_image_form', function (e) {
                e.preventDefault();
                if (isLoading) {
                    return false;
                }

                isLoading = true;

                let formData = new FormData(this);
                let detail_id = $(this).find('input[name="detail_id"]').val();
                $.ajax({
                    type: 'POST',
                    url: ('{{ route("form.images.item_update", [$form, ':detail_id']) }}').replace(':detail_id', detail_id),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status == true) {
                            $(".edit_image_close").trigger("click");
                            image_datatable.ajax.reload();
                            Codebase.helpers('jq-notify', {
                                align: 'right',
                                from: 'top',
                                type: 'success',
                                icon: 'fa fa-check me-1',
                                message: response.message
                            });
                        } else {
                            Codebase.helpers('jq-notify', {
                                z_index: 99999,
                                align: 'right',
                                from: 'top',
                                type: 'danger',
                                icon: 'fa fa-times me-1',
                                message: response.message
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Codebase.helpers('jq-notify', {
                            z_index: 99999,
                            align: 'right',
                            from: 'top',
                            type: 'danger',
                            icon: 'fa fa-times me-1',
                            message: (xhr.status === 422) ? xhr.responseJSON.message : xhr.responseText
                        });
                    },
                    complete: function () {
                        isLoading = false;
                    }
                });
            });

            $(document).on("click", ".delete_image_item", function(e) {
                let deleteId = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this image!",
                    icon: "warning",
                    showCancelButton: !0,
                    customClass: {
                        confirmButton: "btn btn-danger m-1",
                        cancelButton: "btn btn-secondary m-1",
                    },
                    confirmButtonText: "Yes, delete it!",
                    html: !1,
                    preConfirm: (e) =>
                        new Promise((e) => {
                            setTimeout(() => {
                                e();
                            }, 50);
                        }),
                }).then((resp) => {
                    if(resp.value) {
                        jQuery.ajax({
                            url: ('{{ route('form.images.item_delete', [$form, ':detailid']) }}').replace(':detailid', deleteId),
                            method: 'DELETE',
                            success: function(result) {
                                if(result.status == true) {
                                    image_datatable.ajax.reload();
                                    Codebase.helpers('jq-notify', {
                                        align: 'right',
                                        from: 'top',
                                        type: 'success',
                                        icon: 'fa fa-check me-1',
                                        message: result.message
                                    });
                                } else {
                                    Codebase.helpers('jq-notify', {
                                        z_index: 99999,
                                        align: 'right',
                                        from: 'top',
                                        type: 'danger',
                                        icon: 'fa fa-times me-1',
                                        message: result.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Codebase.helpers('jq-notify', {
                                    z_index: 99999,
                                    align: 'right',
                                    from: 'top',
                                    type: 'danger',
                                    icon: 'fa fa-times me-1',
                                    message: (xhr.status === 422) ? xhr.responseJSON.message : xhr.responseText
                                });
                            }
                        });
                    }
                });
            });

            let rich_text_datatable = $(".rich_text_datatable").DataTable({
                serverSide: true,
                scrollX: true,
                ajax: "{{ route('form.rich-texts.item_get', $form) }}",
                columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                    {
                        data: 'name'
                    },
                    {
                        data: 'value'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $(document).on('shown.bs.modal', '#add_rich_text_model', function () {
                if (CKEDITOR.instances.add_rich_text_value) {``
                    CKEDITOR.instances.add_rich_text_value.destroy();
                }
                CKEDITOR.replace('add_rich_text_value');

                setTimeout(function () {
                    CKEDITOR.instances.add_rich_text_value.setData("");
                }.bind(this), 100);
            });

            $(document).on('submit', '#add_rich_text_form', function (e) {
                e.preventDefault();
                if (isLoading) {
                    return false;
                }

                isLoading = true;

                let formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: '{{ route("form.rich-texts.item_add", $form) }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status == true) {
                            $(".add_rich_text_close").trigger("click");
                            rich_text_datatable.ajax.reload();
                            Codebase.helpers('jq-notify', {
                                align: 'right',
                                from: 'top',
                                type: 'success',
                                icon: 'fa fa-check me-1',
                                message: response.message
                            });
                        } else {
                            Codebase.helpers('jq-notify', {
                                z_index: 99999,
                                align: 'right',
                                from: 'top',
                                type: 'danger',
                                icon: 'fa fa-times me-1',
                                message: response.message
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Codebase.helpers('jq-notify', {
                            z_index: 99999,
                            align: 'right',
                            from: 'top',
                            type: 'danger',
                            icon: 'fa fa-times me-1',
                            message: (xhr.status === 422) ? xhr.responseJSON.message : xhr.responseText
                        });
                    },
                    complete: function () {
                        isLoading = false;
                    }
                });
            });

            $(document).on('click', '.update_rich_text_item', function () {
                let modal = $('#edit_rich_text_model');

                modal.find('input[name="detail_id"]').val($(this).data('id'));
                modal.find('input[name="name"]').val($(this).data('name'));
                modal.find('textarea[name="value"]').val($(this).data('value'));

                if (CKEDITOR.instances.edit_rich_text_value) {``
                    CKEDITOR.instances.edit_rich_text_value.destroy();
                }
                CKEDITOR.replace('edit_rich_text_value');

                setTimeout(function () {
                    CKEDITOR.instances.edit_rich_text_value.setData($(this).data('value'));
                }.bind(this), 100);

                modal.modal('show');
            });

            $(document).on('submit', '#edit_rich_text_form', function (e) {
                e.preventDefault();
                if (isLoading) {
                    return false;
                }

                isLoading = true;

                let formData = new FormData(this);
                let detail_id = $(this).find('input[name="detail_id"]').val();
                $.ajax({
                    type: 'POST',
                    url: ('{{ route("form.rich-texts.item_update", [$form, ':detail_id']) }}').replace(':detail_id', detail_id),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status == true) {
                            $(".edit_rich_text_close").trigger("click");
                            rich_text_datatable.ajax.reload();
                            Codebase.helpers('jq-notify', {
                                align: 'right',
                                from: 'top',
                                type: 'success',
                                icon: 'fa fa-check me-1',
                                message: response.message
                            });
                        } else {
                            Codebase.helpers('jq-notify', {
                                z_index: 99999,
                                align: 'right',
                                from: 'top',
                                type: 'danger',
                                icon: 'fa fa-times me-1',
                                message: response.message
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Codebase.helpers('jq-notify', {
                            z_index: 99999,
                            align: 'right',
                            from: 'top',
                            type: 'danger',
                            icon: 'fa fa-times me-1',
                            message: (xhr.status === 422) ? xhr.responseJSON.message : xhr.responseText
                        });
                    },
                    complete: function () {
                        isLoading = false;
                    }
                });
            });

            $(document).on("click", ".delete_rich_text_item", function(e) {
                let deleteId = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this rich text!",
                    icon: "warning",
                    showCancelButton: !0,
                    customClass: {
                        confirmButton: "btn btn-danger m-1",
                        cancelButton: "btn btn-secondary m-1",
                    },
                    confirmButtonText: "Yes, delete it!",
                    html: !1,
                    preConfirm: (e) =>
                        new Promise((e) => {
                            setTimeout(() => {
                                e();
                            }, 50);
                        }),
                }).then((resp) => {
                    if(resp.value) {
                        jQuery.ajax({
                            url: ('{{ route('form.rich-texts.item_delete', [$form, ':detailid']) }}').replace(':detailid', deleteId),
                            method: 'DELETE',
                            success: function(result) {
                                if(result.status == true) {
                                    rich_text_datatable.ajax.reload();
                                    Codebase.helpers('jq-notify', {
                                        align: 'right',
                                        from: 'top',
                                        type: 'success',
                                        icon: 'fa fa-check me-1',
                                        message: result.message
                                    });
                                } else {
                                    Codebase.helpers('jq-notify', {
                                        z_index: 99999,
                                        align: 'right',
                                        from: 'top',
                                        type: 'danger',
                                        icon: 'fa fa-times me-1',
                                        message: result.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Codebase.helpers('jq-notify', {
                                    z_index: 99999,
                                    align: 'right',
                                    from: 'top',
                                    type: 'danger',
                                    icon: 'fa fa-times me-1',
                                    message: (xhr.status === 422) ? xhr.responseJSON.message : xhr.responseText
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
