@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Leave Balance</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white rounded-top-4">
        </div>
        <div class="card-body p-4">
            <form action="" id="saveleavebalance" data-parsley-validate>
                @csrf
                <input type="hidden" name="edit_id" id="edit_id" />

                <div class="row g-4">
                    <!-- Column 1 -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Employee Name
                            </label>
                            <div class="input-group">
                                <select name="employee_name" id="employee_name" class="form-select select2 employee_name"
                                    required></select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> CL Balance
                            </label>
                            <input type="text" id="cl_balance" name="cl_balance" class="form-control cl_balance" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> CL Opening
                            </label>
                            <input type="text" id="cl_opening" name="cl_opening" class="form-control cl_opening" required>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> EL Balance
                            </label>
                            <input type="text" id="el_balance" name="el_balance" class="form-control el_balance" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Comp Off Balance
                            </label>
                            <input type="text" id="comp_off_balance" name="comp_off_balance"
                                class="form-control comp_off_balance" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> EL Opening
                            </label>
                            <input type="text" id="el_opening" name="el_opening" class="form-control el_opening" required>
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> SL Balance
                            </label>
                            <input type="text" id="sl_balance" name="sl_balance" class="form-control sl_balance" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remarks</label>
                            <input type="text" id="remarks" name="remarks" class="form-control remarks">
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-success px-4 save_form">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card">
        <div class="container mt-4 table-responsive">
            <table id="leaveBal" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th style="display:none"></th>
                        <th>Employee Number</th>
                        <th>Employee Name</th>
                        <th>department</th>
                        <th>CL Balance</th>
                        <th>EL Balance</th>
                        <th>SL Balance</th>
                        <th>Comp-Off Balance</th>
                        <th>Remarks</th>
                        <th>Opening Cl</th>
                        <th>Opening El</th>
                        <th>Actions</th>

                    </tr>
                    <tr class="table-info">
                        <th style="display:none"><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                          <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
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
        // dropdown		

        var employeeUrl = "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:first_name";

        $.ajax({
            url: employeeUrl,
            type: 'GET',
            success: function (data) {
                if (typeof data === "string") {
                    try {
                        data = JSON.parse(data);
                    } catch (e) {
                        console.error("Invalid JSON response:", data);
                        return;
                    }
                }

                $('#employee_name').html('<option value="">-- Select Employee --</option>');

                $.each(data, function (i, item) {
                    let selected = item.val == "" ? 'selected' : '';
                    $('#employee_name').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                });

                $('#employee_name').trigger('change.select2');
            }
        });

        // table data		
        $(document).ready(function () {
            var table = $('#leaveBal').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                scrollY: "50vh",
                ajax: "{{ route('getleavebalancegriddata') }}",
                columns: [
                    { data: 'employee_id', name: 'employee_id', visible: false },
                    { data: 'employee_number', name: 'employee_number' },
                    { data: 'first_name', name: 'first_name' },
                    { data: 'sub_department_name', name: 'sub_department_name' },
                    { data: 'causal_leave', name: 'causal_leave' },
                    { data: 'earn_leave', name: 'earn_leave' },
                    { data: 'sick_leave', name: 'sick_leave' },
                    { data: 'comp_off_leave', name: 'comp_off_leave' },
                    { data: 'remarks', name: 'remarks' },
                    { data: 'ocl', name: 'ocl' },
                    { data: 'oel', name: 'oel' },

                    {
                        data: 'leave_balance_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
                    <button type="button" class="btn btn-sm btn-primary edit-btn"
                      data-id="${row.leave_balance_id}"
                      data-name="${row.employee_id}"
                      data-clopen="${row.ocl}"
                      data-elopen="${row.oel}"
                      data-el="${row.earn_leave}"
                      data-cl="${row.causal_leave}"
                      data-sl="${row.sick_leave}"
                      data-comf="${row.comp_off_leave}"
                      data-remark="${row.remarks}">
                      <i class="bi bi-pencil"></i>
                    </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                          data-id="${row.leave_balance_id}">
                          <i class="bi bi-trash"></i>
                        </button>`;
                            }
                            return buttons;
                        }

                    }
                ],

                                          initComplete: function () {
          var api = this.api();

          // get the real visible header inside the scroll container
          var $scrollHead = $(api.table().container())
            .find('.dataTables_scrollHead thead');

          // second header row (index 1) has the inputs
          $scrollHead.find('tr:eq(1) th').each(function (colIndex) {
            var th = this;
            $('input.column-search', th).on('keyup change', function () {
              if (api.column(colIndex).search() !== this.value) {
                api.column(colIndex).search(this.value).draw();
              }
            });
          });
        },
        
            });


            $('#leaveBal thead').on('keyup change', '.column-search', function () {
                let index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });
        });

        /** duplicate validation for employee **/
        $(document).on('change', '#employee_name', function () {

            var employee_id = $('#employee_name').select2('val');
            var edit_id = $('#edit_id').val();
            if (employee_id != '') {

                // already payproposal generated check
                var url = "{{ URL::to('empleavebalancecheck') }}?edit_id=" + edit_id + '&employee_id=' + employee_id;
                $.get(url, function (data) {
                    if ($.trim(data) == 1) {
                        var employee_name = "{{\Session::get('id')}}";
                        $('#employee_name').select2('val', ['']);
                        $('#cl_balance').val('');
                        $('#el_balance').val('');
                        $('#sl_balance').val('');
                        $('#cl_opening').val('');
                        $('#el_opening').val('');
                        $('#comp_off_balance').val('');
                        $('#remarks').val('');
                        showCustomAlert('Leave balance already generated for the Employee', 'error');
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
                    url: "{{ url('leavebalance/delete') }}/" + deleteId,
                    type: "GET",
                    success: function (response) {
                        $('#globalDeleteModal').modal('hide');
                        showCustomAlert('Deleteed Successfully', 'success');
                        $('#leaveBal').DataTable().ajax.reload();

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
            const clopen = $(this).data('clopen');
            const elopen = $(this).data('elopen');
            const el = $(this).data('el');
            const cl = $(this).data('cl');
            const sl = $(this).data('sl');
            const comf = $(this).data('comf');
            const remark = $(this).data('remark');


            // Fill form fields
            $('input[name="edit_id"]').val(id);
            $('input[name="cl_balance"]').val(cl);
            $('input[name="el_balance"]').val(el);
            $('input[name="sl_balance"]').val(sl);
            $('input[name="comp_off_balance"]').val(comf);
            $('input[name="el_opening"]').val(elopen);
            $('input[name="cl_opening"]').val(clopen);
            $('input[name="remarks"]').val(remark);



            // For select2 fields, use .val().trigger('change')
            $('select[name="employee_name"]').val(name).trigger('change');
        });



        //save function
        $(document).on('click', '.save_form', function () {
            let dup_chk = true;
            var form = $("#saveleavebalance");
            form.parsley().validate();

            if (form.parsley().isValid() && dup_chk == true) {
                var $btn = $(this);
                $btn.prop('disabled', true);
                $.ajax({
                    url: "{{ URL::to('leavebalancesave') }}",
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
            } else {
                showCustomAlert('Fill Required Fields', 'warning');
            }
        });


        /* purpose:leavebalance validation*/
        $(document).on('keypress', '.el_balance', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });
        $(document).on('keypress', '.sl_balance', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });
        $(document).on('keypress', '.cl_balance', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });

        $(document).on('keypress', '.cl_opening', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });

        $(document).on('keypress', '.el_opening', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });


        $(document).on('keypress', '.comp_off_balance', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });
     

    </script>


@endpush