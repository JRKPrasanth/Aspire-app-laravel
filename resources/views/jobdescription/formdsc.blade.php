@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Description</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body mt-2">

            <form action="" id="jobdescription" data-parsley-validate>
                <?php $data = \Session::get('data');
    if (isset($data[$pageMethod]['save'])) { ?>

                {{ csrf_field() }}
                <input type="hidden" name="edit_id" id="edit_id" />
                <input type="hidden" name="description_id" id="description_id" />

                <div class="row g-4">

                    <!-- Description Name -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Description Name
                            </label>
                            <input type="text" id="description_name" name="description_name" class="form-control -sm"
                                required>
                            <span class="badge bg-danger dup_name d-none"></span>
                        </div>
                    </div>

                    <!-- Upload -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Upload File
                            </label>
                            <input type="file" name="file" id="file" class="form-control -sm">
                        </div>
                    </div>

                    <!-- Active -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Active</label>
                            <select name="active" id="active" class="form-select -sm select2">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                </div>

                <!-- Buttons -->
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-success px-4   save_form">
                        Save
                    </button>

                </div>

                <?php } ?>

            </form>

        </div>
    </div>



    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4">
            <table id="Dectbl" class="table table-bordered table-striped w-100">

                <thead>

                    <tr class="table-warning">
                        <th>Description Name</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                    <tr class="table-info">
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

@endsection
@push('scripts')

    <script>

        // data table funcrion	
        $(document).ready(function () {

            var table = $('#Dectbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('descriptionformgriddata') }}",
                columns: [

                    { data: 'description_name', name: 'description_name' },
                    { data: 'active', name: 'active' },

                    {
                        data: 'description_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '140px',
                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `<button class="btn btn-sm btn-primary me-1 edit-btn" 
                      data-id="${row.description_id}" 
                      data-name="${row.description_name}" 
                      data-active="${row.active}">
                      <i class="bi bi-pencil"></i>
                    </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${row.customer_type_id}">
                      <i class="bi bi-trash"></i>
                    </button>`;
                            }
                            return buttons;
                        }
                    }
                ]
            });

            // Individual column search
            $('#Dectbl thead').on('keyup change', ".column-search", function () {
                var colIndex = $(this).parent().index();
                table.column(colIndex).search(this.value).draw();
            });



            $(document).on('keyup', '.description_name', function () {

                $('.dup_name').hide();
            });



            var dup_chk = true;
            function duplicate_validate() {
                var description_name = $(".description_name").val();
                var edit_id = $("#edit_id").val();
                var dept = $('#department_id').select2('val');
                $.ajax({
                    cache: false,
                    url: 'description/checkname?dept=' + dept, //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async: false,
                    data: { description_name: description_name, edit_id: edit_id },
                    success: function (response) {

                        if (response == 1) {
                            $('.dup_name')
                                .html('Descrption: ' + description_name + ' already exists')
                                .removeClass('d-none')
                                .addClass('d-block');

                            $(".description_name").val('');
                            dup_chk = false;
                        }
                        else if (response == 0) {
                            var html = "";
                            $('.dup_name').hide();
                            dup_chk = true;

                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                    }
                });
            }


            $(document).on('click', '.save_form', function () {
                var url = "{{url('jobdescriptionsavedesc')}}";
                var data = $('#jobdescription').serialize();
                var form = $('#jobdescription');
                var form_data = new FormData(document.getElementById('jobdescription'));
                form.parsley().validate();
                var form = $('#jobdescription');
                form.parsley().validate();
                duplicate_validate();
                if (form.parsley().isValid() && dup_chk) {
                    var $btn = $(this);
                    $btn.prop('disabled', true);
                    $.ajax({
                        url: "{{ url('jobdescriptionsavedesc')}}",
                        type: "POST",
                        data: form_data,
                        enctype: 'multipart/form-data',
                        processData: false,  // tell jQuery not to process the data
                        contentType: false,   // tell jQuery not to set contentType
                        async: true,
                        xhr: function () {
                            var xhr = $.ajaxSettings.xhr();
                            if (xhr.upload) {
                                xhr.upload.addEventListener('progress', function (event) {
                                }, true);
                            }
                            return xhr;
                        }
                    }).done(function (data, status) {

                        if (data == 1) {
                            showCustomAlert('Description Saved Successfully', 'success');
                            window.location.reload();
                        }
                        if (data == 2) {
                            showCustomAlert('Description Updated Successfully', 'success');
                            window.location.reload();
                        }
                        else {
                            $(".alert-success").hide();
                            $(".alert-danger").fadeIn(800);

                        }
                    }).fail(function (data, status) {


                        $(".alert-success").hide();
                        $(".alert-danger").fadeIn(800);

                    });

                }
            });


            // delete function
            let deleteId = null;

            $(document).on('click', '.delete-btn', function () {
                deleteId = $(this).data('id');
                $('#globalDeleteModal').modal('show');
            });

            $('#globalConfirmDeleteBtn').on('click', function () {
                if (deleteId) {
                    $.ajax({
                        url: "{{ url('jobdescriptiondesc/delete/') }}/" + deleteId,
                        type: "GET",
                        success: function (data) {
                            if (data == '1') {
                                $('#globalDeleteModal').modal('hide');
                                showCustomAlert('Deleted successfully!', 'success');
                                $('#CustomerTbl').DataTable().ajax.reload();
                            }
                            if (data == '2') {
                                $('#globalDeleteModal').modal('hide');
                                showCustomAlert("You Can't delete , Used in SomeWhere.", 'error');
                                $('#CustomerTbl').DataTable().ajax.reload();
                            }
                        },
                        error: function (xhr) {
                            $('#globalDeleteModal').modal('hide');
                            const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
                            showCustomAlert(errorMsg, 'error');
                        }
                    });
                }
            });

            // edit function
            $(document).on('click', '.edit-btn', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const active = $(this).data('active');

                // Fill form fields
                $('input[name="edit_id"]').val(id);
                $('input[name="description_name"]').val(name);
                $('select[name="active"]').val(active).trigger('change');

            });

            $(document).on('keypress', '.description_name', function (ev) {
                var regex = new RegExp("^[a-z,A-Z.,' ']+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });


        });

    </script>

@endpush