@extends('layouts.header')
@section('content')
    <h2 class="text-danger"> Request For Training</h2>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <form id="prdsubcat" method="post" action="" data-parsley-validate>
                {{ csrf_field() }}
                <input type="hidden" value="" name="savestatus" id="savestatus" />
                <input type="hidden" name="edit_id" value="" id="edit_id" />

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- Topic -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-5 col-form-label">
                                <span class="text-danger">*</span> Topic
                            </label>
                            <div class="col-md-6">
                                <select name="topic_id" class="form-select select2 topic_id" id="topic_id">
                                    {!! $topic_id !!}
                                </select>
                            </div>
                        </div>

                        <!-- Employee (Multi-select) -->
                        <div class="row mb-3 employee_div align-items-center">
                            <label class="col-md-5 col-form-label">
                                <span class="text-danger">*</span> Employee
                            </label>
                            <div class="col-md-6">
                                <select name="employee_id[]" class="form-select select2 employee_id" id="employee_id"
                                    multiple>
                                    <option value="">-- Select Option --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <!-- Request Type -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-5 col-form-label">
                                <span class="text-danger">*</span> Request Type
                            </label>
                            <div class="col-md-6">
                                <select name="request_type" class="form-select select2 request_type" id="request_type">
                                    <option value="">-- Select Option --</option>
                                    <option value="SELF">SELF</option>
                                    <option value="TEAM">Team</option>
                                    <option value="CUSTOM">CUSTOM</option>
                                </select>
                            </div>
                        </div>

                        <!-- Active -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-5 col-form-label">Active</label>
                            <div class="col-md-6">
                                <select name="active" class="form-select select2 active" id="active">
                                    <option value="Yes" selected>Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <!-- Created By (Hidden) -->
                        <div class="row mb-3 d-none">
                            <label class="col-md-5 col-form-label">Created By</label>
                            <div class="col-md-6">
                                <select name="created_by" class="form-select select2 created_by" id="created_by" disabled>
                                    {!! $created_by !!}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mt-4">
                    <div class="col text-center">
                        <button type="submit" class="btn btn-success saveform px-4">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
            </div>
            <div class="table-responsive">
                <table id="TopicTbl" class="table table-striped table-bordered w-100">
                    <thead>
                        <tr class="table-warning">
                            <th>Department</th>
                            <th>Topic</th>
                            <th>Requested By</th>
                            <th>Request Type</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>

                        <tr class="table-success">
                            <th><input type="text" placeholder="Search" /></th>
                            <th><input type="text" placeholder="Search" /></th>
                            <th><input type="text" placeholder="Search" /></th>
                            <th><input type="text" placeholder="Search" /></th>
                            <th><input type="text" placeholder="Search" /></th>
                            <th></th>
                        </tr>

                    </thead>
                </table>
            </div>
        </div>
    </div>


@endsection
@push('scripts')

    <script>
        // data table funcrion	
        $(document).ready(function () {
            var table = $('#TopicTbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('gettrainingrequestgrid') }}",
                columns: [
                    { data: 'sub_department_name', name: 'm_department_lines_t.sub_department_name' },
                    { data: 'topic_name', name: 'topic_name' },
                    { data: 'employee_name', name: 'employee_name' },
                    { data: 'request_type', name: 'request_type' },
                    { data: 'first_name', name: 'first_name' },

                    {
                        data: 'training_request_id',
                        name: 'actions',
                        width: '100px',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-info edit-btn"
                          data-id="${row.topic_id}"
                          data-name="${row.topic_name}"
                          data-remark="${row.remarks}"
                          data-active="${row.active}">
                          <i class="bi bi-pencil"></i>
                        </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `
                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                              data-id="${row.topic_id}">
                              <i class="bi bi-trash"></i>
                            </button>`;
                            }
                            return buttons;
                        }

                    }
                ]
            });


            $('#TopicTbl thead').on('keyup change', '.column-search', function () {
                let index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });
        });

        // save function

        $(document).on('click', '.saveform', function () {
            let dup_chk = true;
            var form = $("#prdsubcat");
            form.parsley().validate();

            if (form.parsley().isValid() && dup_chk == true) {

                var $btn = $(this);
                $btn.prop('disabled', true);

                $.ajax({
                    url: "{{ URL::to('trainingrequestsave') }}",
                    type: "POST",
                    data: form.serialize(),
                    success: function (data) {
                        // Show success message
                        showCustomAlert('Saved successfully!', 'success');
                        // Clear the form (optional)
                        form[0].reset();
                        $('.select2').val('').trigger('change');
                        // Reload DataTable
                        $('#TopicTbl').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        showCustomAlert('Save failed. Try again.', 'error');
                    }
                });
                window.location.reload();
            }
        });

        // edit function
        $(document).on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            const remark = $(this).data('remark');
            const name = $(this).data('name');
            const active = $(this).data('active');

            // Fill form fields
            $('input[name="topic_id"]').val(id);
            $('input[name="topic_name"]').val(name);
            $('input[name="remarks"]').val(remark);

            // For select2 fields, use .val().trigger('change')

            $('select[name="active"]').val(active).trigger('change');
        });


        // delete function

        $(document).on('click', '.delete-btn', function () {
            let deleteId = null;
            deleteId = $(this).data('id');
            $('#globalDeleteModal').modal('show');
        });

        $('#globalConfirmDeleteBtn').on('click', function () {
            if (deleteId) {
                $.ajax({
                    url: "{{ url('trainingrequestdelete') }}/" + deleteId,
                    type: "GET",
                    success: function (response) {
                        $('#globalDeleteModal').modal('hide');
                        showCustomAlert(response.message, 'success');
                        $('#TopicTbl').DataTable().ajax.reload();

                    },
                    error: function (xhr) {
                        $('#globalDeleteModal').modal('hide');
                        const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
                        showCustomAlert(errorMsg, 'error');
                    }
                });
            }
        });


    </script>
@endpush