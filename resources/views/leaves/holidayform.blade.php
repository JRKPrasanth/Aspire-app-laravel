@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Holiday</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body card-block">
            <form action="" id="saveholiday">
                <?php $data = \Session::get('data');
    if (isset($data[$pageMethod]['save'])) { ?>

                <input type="hidden" name="edit_id" value="" id="edit_id" />
                {{ csrf_field() }}

                <div class="row g-4 mt-2">
                    <!-- Column 1 -->
                    <div class="col-md-4">
                        <!-- Holiday Name -->
                        <div class="form-group row mb-3">
                            <label for="holiday_name" class="form-control-label col-md-5">
                                <span class="req">*</span> Holiday Name
                            </label>
                            <div class="col-md-8">
                                <input type="text" id="holiday_name" name="holiday_name" class="form-control holiday_name"
                                    value="" required>
                            </div>
                        </div>

                        <!-- Holiday Date -->
                        <div class="form-group row mb-3">
                            <label for="date" class="form-control-label col-md-5">
                                <span class="req">*</span> Holiday Date
                            </label>
                            <div class="col-md-8">
                                <input type="text" id="date" name="date" class="form-control" value="" required>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-md-4">
                        <!-- Company -->
                        <div class="form-group row mb-3">
                            <label for="company" class="form-control-label col-md-5">
                                <span class="req">*</span> Company
                            </label>
                            <div class="col-md-8">
                                <select name="company" id="company" class="select2 form-control" required>
                                </select>
                            </div>
                        </div>

                        <!-- Active -->
                        <div class="form-group row mb-3">
                            <label for="active" class="form-control-label col-md-5">Active</label>
                            <div class="col-md-8">
                                <select name="active" id="active" class="select2 form-control">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="col-md-4">
                        <!-- Location -->
                        <div class="form-group row mb-3">
                            <label for="location" class="form-control-label col-md-5">
                                <span class="req">*</span> Location
                            </label>
                            <div class="col-md-8 locationparsley">
                                <select name="location[]" id="location" class="select2 form-control" required multiple>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="row mt-4">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-success px-4 save_form">
                            Save
                        </button>
                    </div>
                </div>

                <?php } ?>
            </form>
        </div>
    </div>


    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4">
            <table id="holiTbl" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th>Holiday Name</th>
                        <th>Holiday Date</th>
                        <th>Company Name</th>
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
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
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

        // validate alphabets only 
        $(document).on('keypress', '#holiday_name', function (ev) {
            var regex = new RegExp("^[a-z,A-Z.,' ']+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });

        // jcombo
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

        // Usage
        loadDropdown('#company', "{{ URL::to('jcomboform') }}?table=m_company_t:company_id:company_name", "{{ \Session::get('companyid') }}", "-- Select Company --");
        loadDropdown('#location', "{{ URL::to('jcomboform') }}?table=m_location_t:location_id:location_name", "{{ \Session::get('location') }}", "-- Select Location --");

        // table data		

        $(document).ready(function () {
            var table = $('#holiTbl').DataTable({
                processing: true,
                serverSide: true,
                order: [[1, 'desc']],
                ajax: "{{ route('getholidaygriddata') }}",
                columns: [
                    { data: 'holiday_name', name: 'holiday_name' },
                    { data: 'date', name: 'date' },
                    { data: 'company_name', name: 'company_name' },
                    { data: 'active', name: 'active' },

                    {
                        data: 'holiday_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
                    <button type="button" class="btn btn-sm btn-primary edit-btn"
                      data-id="${row.holiday_id}"
                      data-name="${row.holiday_name}"
                      data-date="${row.date}"
                      data-active="${row.active}">
                      <i class="bi bi-pencil"></i>
                    </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                          data-id="${row.holiday_id}">
                          <i class="bi bi-trash"></i>
                        </button>`;
                            }
                            return buttons;
                        }

                    }
                ]
            });


            $('#holiTbl thead').on('keyup change', '.column-search', function () {
                let index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });
        });

        // edit

        $(document).on('click', '.edit-btn', function () {

            const id = $(this).data('id');
            const date = $(this).data('date');
            const name = $(this).data('name');
            const active = $(this).data('active');

            // Fill form fields
            $('input[name="edit_id"]').val(id);
            $('input[name="date"]').val(date);
            $('input[name="holiday_name"]').val(name);

            // For select2 fields, use .val().trigger('change')
            $('select[name="active"]').val(active).trigger('change');
        });


        //delete	

        let deleteId = null;

        $(document).on('click', '.delete-btn', function () {
            deleteId = $(this).data('id');
            $('#globalDeleteModal').modal('show');
        });

        $('#globalConfirmDeleteBtn').on('click', function () {
            if (deleteId) {
                $.ajax({
                    url: "{{ url('holiday/delete') }}/" + deleteId,
                    type: "GET",
                    success: function (response) {
                        $('#globalDeleteModal').modal('hide');
                        showCustomAlert('Deleted Succesfully', 'success');
                        $('#holiTbl').DataTable().ajax.reload();

                    },
                    error: function (xhr) {
                        $('#globalDeleteModal').modal('hide');
                        const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
                        showCustomAlert(errorMsg, 'error');
                    }
                });
            }
        });

        //save	

        $(document).on('click', '.save_form', function () {

            var form = $('#saveholiday');
            form.parsley().validate();
            if (form.parsley().isValid()) {
                var $btn = $(this);
                $btn.prop('disabled', true);
                var data = $("#saveholiday").serialize();

                $.post('holidaysave/save', data, function (data) {
                    if (data == 1) {
                        showCustomAlert('Holiday Saved Successfully','success');
                        form[0].reset();
                        $('.select2').val('').trigger('change');
                        // Reload DataTable
                        window.location.reload();
                        $(".reset").trigger('click');
                    }
                    else if (data == 2) {
                        showCustomAlert('Holiday Updated  Successfully','success');
                        form[0].reset();
                        $('.select2').val('').trigger('change');
                        // Reload DataTable
                        window.location.reload();
                    }
                });
            }
        });


        $(document).on("focus", "#date", function () {

        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            minDate: "2025-01-01",
            dateFormat: "yy-mm-dd",
            showAnim: "slideDown",
            yearRange: "-25:+0",

        });
    });

    </script>


@endpush