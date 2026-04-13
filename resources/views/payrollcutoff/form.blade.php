@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Payroll Cut Off</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white fw-semibold"></div>
        <div class="card-body">
            <form action="" method="POST" id="save">
                @csrf
                <input type="hidden" name="edit_id" id="edit_id" />

                <div class="row g-4">
                    <!-- Company -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <span class="text-danger">*</span> Company
                        </label>
                        <div class="input-group">
                            <select name="company" id="company" class="form-select select2 company" required></select>
                        </div>
                    </div>

                    <!-- Payroll Cut-off Date -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <span class="text-danger">*</span> Payroll Cut-off Date
                        </label>
                        <input type="text" name="payroll_cuttoff" id="payroll_cuttoff"
                            class="form-control start_date payroll_cuttoff" required>
                    </div>

                    <!-- Description -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Description</label>
                        <input type="text" name="description" id="description" class="form-control">
                    </div>

                    <!-- Active -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <span class="text-danger">*</span> Active
                        </label>
                        <select name="active" id="active" class="form-select select2 active" required>
                            <option value="">-- Select --</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="button" id="save" class="btn btn-success px-4 save_form">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>



    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
            </div>
            <div class="table-responsive">
                <table id="payTbl" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr class="table-warning">

                            <th class="freeze">Company Name</th>
                            <th>Cut-Off Date</th>
                            <th>Description</th>
                            <th>Active</th>
                            <th>Company Name</th>
                            <th>Actions</th>


                        </tr>
                        <tr class="table-info">
                            <th class="freeze"><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection
@push('scripts')

    <script>

        // on change		
        var companyid = "{{ \Session::get('company') }}";
        var condition = "active='Yes'";
        var url = "{{ URL::to('jcomboformlogin') }}?table=m_company_t:company_id:company_name&order_by=company_name &parent=" + encodeURIComponent(condition);

        loadDropdown(
            ".company",
            url,
            companyid,
            "-- Select Company --"
        );
        function loadDropdown(selector, url, selectedValue, defaultText = "-- Select --") {
            $.ajax({
                url: url,
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

                    $(selector).html(`<option value="">${defaultText}</option>`);

                    $.each(data, function (i, item) {
                        let selected = item.val == selectedValue ? 'selected' : '';
                        $(selector).append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                    });

                    $(selector).trigger('change.select2');
                }
            });
        }


        // table data
        $(document).ready(function () {
            var table = $('#payTbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('payrollcutoffgriddata') }}",
                columns: [

                    { class: 'freeze', data: 'company_name', name: 'company_name' },
                    { data: 'cutoff_date', name: 'cutoff_date' },
                    { data: 'description', name: 'description' },
                    { data: 'active', name: 'active' },
                    { data: 'company', name: 'company', visible: false },

                    {
                        data: 'id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-primary edit-btn"
                                data-id="${row.id}"
                                data-company="${row.company}"
                                data-cutoff_date="${row.cutoff_date}"
                                data-description="${row.description}"
                                data-active="${row.active}">
                          <i class="bi bi-pencil"></i>
                        </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `
                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                              data-id="${row.id}">
                              <i class="bi bi-trash"></i>
                            </button>`;
                            }
                            return buttons;
                        }

                    }

                ]
            });


            $('#payTbl thead').on('keyup change', '.column-search', function () {
                let index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });
        });


        // save	
        $(document).on('click', '.save_form', function () {

            var data;
            var url = "{{URL::to('setting')}}";
            var form = $('#save');
            form.parsley().validate();
            if (form.parsley().isValid()) {
                var $btn = $(this);
                $btn.prop('disabled', true);
                data = $("#save").serialize();
                $.post('payrollcuttoffsave', data, function (data) {
                    if (data == 1) {
                        showCustomAlert('Saved Successfully', 'success');
                        window.location.reload();
                        form[0].reset();
                        $('.select2').val('').trigger('change');
                    }
                    else if (data == 2) {
                        showCustomAlert('Updated Successfully', 'success');
                        window.location.reload();
                        form[0].reset();
                        $('.select2').val('').trigger('change');
                    }
                });
            }
        });

        // delete  	

        $(document).on('click', '.delete-btn', function () {
            deleteId = $(this).data('id');
            $('#globalDeleteModal').modal('show');
        });

        $('#globalConfirmDeleteBtn').on('click', function () {
            if (deleteId) {
                $.ajax({
                    url: "{{ url('payrollcutoffdelete') }}/" + deleteId,
                    type: "GET",
                    success: function (response) {
                        $('#globalDeleteModal').modal('hide');
                        showCustomAlert('Deleted Successfully!', 'success');
                        $('#payTbl').DataTable().ajax.reload();

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
            const description = $(this).data('description');
            const cutoff_date = $(this).data('cutoff_date');
            const company = $(this).data('company');
            const active = $(this).data('active');

            // Fill form fields
            $('input[name="edit_id"]').val(id);
            $('input[name="payroll_cuttoff"]').val(cutoff_date);
            $('input[name="description"]').val(description);


            $('select[name="company"]').val(company).trigger('change');
            $('select[name="active"]').val(active).trigger('change');
        });


    </script>


@endpush