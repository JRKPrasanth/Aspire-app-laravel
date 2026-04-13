@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Month End Freeze</h3>
    @include('layouts.breadcrumb')

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <form action="" id="savemonthendfreeze">
                <input type="hidden" name="edit_id" id="edit_id" />
                {{ csrf_field() }}

                <div class="row g-3">

                    <!-- Month-End Date -->
                    <div class="col-md-4">
                        <label for="monthend_date" class="form-label">
                            <span class="text-danger">*</span>Month-End Date
                        </label>
                        <input type="text" class="form-control monthend_date" id="monthend_date" name="monthend_date"
                            required>
                    </div>

                    <!-- Active -->
                    <div class="col-md-4">
                        <label for="active" class="form-label">
                            <span class="text-danger">*</span>Active
                        </label>
                        <select id="active" name="active" class="form-select active select2" required>
                            <option value="">--- Please Select ---</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <!-- Freeze Type -->
                    <div class="col-md-4">
                        <label for="monthend_type" class="form-label">
                            <span class="text-danger">*</span>Freeze Type
                        </label>
                        <select id="monthend_type" name="monthend_type" class="form-select select2" required>
                            {!! $type !!}
                        </select>
                    </div>

                    <!-- Created By -->
                    <div class="col-md-4 none">
                        <label for="created_by" class="form-label">
                            <span class="text-danger">*</span>Created By
                        </label>
                        <select id="created_by" name="created_by" class="form-select select2" required>
                            {!! $created_by !!}
                        </select>
                    </div>

                    <!-- Save Button -->
                    <div class="col-md-12 text-center mt-4">
                        <button type="button" class="btn btn-success save_form px-4">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4">
            <table id="MonthTbl" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th>Month-End Date</th>
                        <th>Month-End Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                        <th></th>
                    </tr>
                    <tr class="table-info">
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th></th>
                        <th></th>
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
            var table = $('#MonthTbl').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'desc']],
                ajax: "{{ route('getmonthendfreezegriddata') }}",
                columns: [
                    { data: 'monthend_date', name: 'monthend_date' },
                    { data: 'lookup_code', name: 'lookup_code' },
                    { data: 'active', name: 'active' },
                    { data: 'monthend_type', name: 'monthend_type', visible: false },

                    {
                        data: 'monthend_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
                                <button type="button" class="btn btn-sm btn-primary edit-btn"
                                  data-id="${row.monthend_id}"
                                  data-date="${row.monthend_date}"
                                  data-name="${row.lookup_code}"
                                  data-active="${row.active}"
                                  data-type="${row.monthend_type}">
                                  <i class="bi bi-pencil"></i>
                                </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `
                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                      data-id="${row.monthend_id}">
                                      <i class="bi bi-trash"></i>
                                    </button>`;
                            }
                            return buttons;
                        }

                    },
                    { data: 'lookuplines_id', name: 'lookuplines_id', visible:false },
                ]
            });


            $('#MonthTbl thead').on('keyup change', '.column-search', function () {
                let index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });



        });

        // edit
        $(document).on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            const date = $(this).data('date');
            const active = $(this).data('active');
            const name = $(this).data('name');
            const type = $(this).data('type');

            // Fill form fields
            $('input[name="edit_id"]').val(id);
            $('input[name="monthend_date"]').val(date);
            $('select[name="monthend_type"]').val(type).trigger('change');
            $('select[name="active"]').val(active).trigger('change');

        });

        // save function
        let dup_chk = true;

        $(document).on('click', '.save_form', function () {

            var form = $("#savemonthendfreeze");
            form.parsley().validate();

            if (form.parsley().isValid() && dup_chk == true) {
                var $btn = $(this);
                $btn.prop('disabled', true);

                $.ajax({
                    url: "{{ URL::to('monthendfreezesave/save') }}",
                    type: "POST",
                    data: form.serialize(),
                    success: function (data) {
                        // Show success message
                        showCustomAlert('Saved successfully!', 'success');
                        // Clear the form (optional)
                        form[0].reset();
                        $('.select2').val('').trigger('change');
                        // Reload DataTable
                        window.location.reload();
                    },
                    error: function (xhr) {
                        showCustomAlert('Save failed. Try again.', 'error');
                    }
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
                    url: "{{ url('monthendfreeze/delete') }}/" + deleteId,
                    type: "GET",
                    success: function (response) {
                        $('#globalDeleteModal').modal('hide');
                        showCustomAlert(response.message, 'success');
                        $('#MonthTbl').DataTable().ajax.reload();

                    },
                    error: function (xhr) {
                        $('#globalDeleteModal').modal('hide');
                        const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
                        showCustomAlert(errorMsg, 'error');
                    }
                });
            }
        });

        $(document).on("focus", ".monthend_date", function () {

            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                minDate: -30,
                maxDate: +30,
                showAnim: "slideDown",
                yearRange: "-25:+0",

            });
        });

    </script>
@endpush