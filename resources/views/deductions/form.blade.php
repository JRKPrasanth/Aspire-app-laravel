@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Deductions</h3>
    @include('layouts.breadcrumb')

    <form action="" id="saveForm">
        <?php $data = \Session::get('data');
    if (isset($data[$pageMethod]['save'])) { ?>
        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-header bg-primary text-white fw-semibold"></div>
            <div class="card-body">
                <input type="hidden" name="edit_id" value="" id="edit_id" />
                {{ csrf_field() }}

                <div class="row g-3">
                    <!-- Components -->
                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">*</span> Components</label>
                        <div class="d-flex align-items-center">
                            <select name="component" id="component" class="form-select select2 component" required>
                                {!! $deduction_type !!}
                            </select>
                            <span class="ms-2 text-danger dup_name d-none"></span>
                        </div>
                    </div>

                    <!-- Employee Type -->
                    <div class="col-md-4 employee_type_hide">
                        <label class="form-label"><span class="text-danger">*</span> Employee Type</label>
                        <div class="d-flex align-items-center">
                            <select name="employee_type" id="employee_type" class="form-select select2 employee_type"
                                style="width: 100%;"></select>
                        </div>
                    </div>

                    <!-- Limit -->
                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">*</span> Limit</label>
                        <input type="text" id="limit" name="limitto" class="form-control limits" required>
                    </div>

                    <!-- Date -->
                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">*</span> Date</label>
                        <input type="text" id="date" name="date" class="form-control date start_date"
                            value="<?= date('Y-m-d'); ?>" required>
                    </div>

                    <!-- Employer Contribute -->
                    <div class="col-md-4 contribute">
                        <label class="form-label"><span class="text-danger">*</span> Employer Contribute (%)</label>
                        <input type="text" id="employeer_contribute" name="employeer_contribute"
                            class="form-control employeer_contribute" required>
                    </div>

                    <!-- Company Contribute -->
                    <div class="col-md-4 contribute">
                        <label class="form-label"><span class="text-danger">*</span> Company Contribute (%)</label>
                        <input type="text" id="company_contribute" name="company_contribute"
                            class="form-control company_contribute" required>
                    </div>

                    <!-- Company Contribute1 -->
                    <div class="col-md-4 contribute1">
                        <label class="form-label"><span class="text-danger">*</span> Company Contribute1 (%)</label>
                        <input type="text" id="company_contribute1" name="company_contribute1"
                            class="form-control company_contribute1" required>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col text-center">
                        <button type="button" id="save" class="btn btn-success px-4 save_form">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </form>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4 table-responsive">
            <table id="DecTbl" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th>Components Name</th>
                        <th>Components Id</th>
                        <th>Components Id</th>
                        <th>Limit</th>
                        <th>Date</th>
                        <th>Employee Contribute</th>
                        <th>Company Contribute</th>
                        <th>Company Contribute1</th>
                        <th>Employee Type</th>
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

        // table data

        $(document).ready(function () {
            var table = $('#DecTbl').DataTable({
                processing: true,
                serverSide: true,
                order: [[4, 'desc']],
                ajax: "{{ route('deductiongriddata') }}",
                columns: [
                    { data: 'lookup_meaning', name: 'lookup_meaning' },
                    { data: 'components', name: 'components', visible: false },
                    { data: 'employee_type', name: 'employee_type', visible: false },
                    { data: 'limitto', name: 'limitto' },
                    { data: 'date', name: 'date' },
                    { data: 'employeer_contribute', name: 'employeer_contribute' },
                    { data: 'company_contribute', name: 'company_contribute' },
                    { data: 'company_contribute1', name: 'company_contribute1' },
                    { data: 'employee_type_name', name: 'employee_type_name' },


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
                              data-type="${row.employee_type}"
                                data-comp="${row.components}"
                                 data-limit="${row.limitto}"
                                  data-date="${row.date}"
                                   data-con1="${row.employeer_contribute}"
                                    data-con2="${row.company_contribute}"
                                      data-con3="${row.company_contribute1}">
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


            $('#DecTbl thead').on('keyup change', '.column-search', function () {
                let index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });
        });


        //	dropdown 
        var condition = 'lookup_type="EMPLOYEE_TYPE"';
        var url = "{{ URL::to('jcomboformlogin') }}?table=a_lookuplines_t:lookuplines_id:lookup_code&parent=" + encodeURIComponent(condition) + "&order_by=lookup_code";

        loadDropdown(
            "#employee_type",
            url,
            "",
            "-- Select Employee Type --"
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

        $('.employee_type_hide').hide();
        $('.contribute,.contribute1').hide();

        // Validation
        $(document).on('keypress', '.limits,.limitto,.employeer_contribute,.company_contribute', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });



        $(document).on('change', ".limitto,.limits", function () {
            var limitto = $('.limitto').val();
            var limits = $('.limits').val();
            if (limits != '' && limitto != '') {
                if (limitto < limits) {
                    $(this).val('');
                    showCustomAlert('To Limit  is  greater than From limit', 'warning');
                }
            }
        });


        // on change

        $(document).on('change', '#component', function () {
            $('.dup_name').hide();
            var result = $("#component option:selected").text();

            if (result == "ESI" || result == "PF") {
                $('.contribute').show();
                $('.employeer_contribute,.company_contribute').attr('required', true);
                if (result == "PF") {
                    $('.contribute1').show();
                    $('.company_contribute1').attr('required', true);
                } else {
                    $('.contribute1').hide();
                    $('.company_contribute1').removeAttr('required');
                }
                $('.employee_type_hide').hide();
                $('.employee_type').removeAttr('required');
            } else if (result == "HRA" || result == "DA") {
                $('.employee_type_hide').show();
                $('.employee_type').attr('required', true);
                $('.contribute').hide();
                $('.contribute1').hide();
                $('.employeer_contribute,.company_contribute,.company_contribute1').removeAttr('required');
            }
            else {
                $('.contribute').hide();
                $('.contribute1').hide();
                $('.employee_type_hide').hide();
                $('.employeer_contribute,.company_contribute,.limitto,.company_contribute1,.employee_type').removeAttr('required');
            }
        });


        var dup_chk = true;
        function duplicate_validate() {
            var component_name = $("#component").select2('val');
            var employee_type = $("#employee_type").select2('val');
            var edit_id = $("#edit_id").val();

            $.ajax({
                cache: false,
                url: 'deductioncategory/checkid', //this is your uri
                type: 'GET',
                dataType: 'json',
                async: false,
                data: { component_name: component_name, employee_type: employee_type, edit_id: edit_id },
                success: function (response) {

                    console.log(response);
                    if (response[0] == 1) {
                        $('.dup_name').html('Component Name:' + response[1] + ' Already Exists');
                        $('.dup_name').show();
                        $("#dup_name").val('').select();
                        dup_chk = false;

                    }
                    else if (response[0] == 0) {
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


        // save
        $(document).on('click', '.save_form', function () {

            var data;
            duplicate_validate();
            var form = $('#saveForm');
            form.parsley().validate();

            if (form.parsley().isValid() && dup_chk) {
                var $btn = $(this);
                $btn.prop('disabled', true);
                data = $("#saveForm").serialize();
                $.post('deductions/save', data, function (data) {
                    if (data == 1) {
                        showCustomAlert('Saved Successfully', 'success');
                        window.location.reload();

                    }
                    else if (data == 2) {
                        showCustomAlert('Updated Successfully', 'success');
                        window.location.reload();
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
                    url: "{{ url('deductiondelete') }}/" + deleteId,
                    type: "GET",
                    success: function (response) {
                        $('#globalDeleteModal').modal('hide');
                        showCustomAlert('Deleted successfully!', 'success');
                        $('#DecTbl').DataTable().ajax.reload();

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

            var id = $(this).data('id');
            var type = $(this).data('type');
            var comp = $(this).data('comp');
            var limit = $(this).data('limit');
            var date = $(this).data('date');
            var con1 = $(this).data('con1');
            var con2 = $(this).data('con2');
            var con3 = $(this).data('con3');

            // Set basic values
            $('#edit_id').val(id);
            $('.limits').val(limit);

            // Parse and set date in required format
            if (date) {
                let formattedDate = $.datepicker.formatDate("dd-mm-yy", $.datepicker.parseDate("yy-mm-dd", date));
                $('.date').val(formattedDate);
            }

            // Set values in select2 fields
            $('#component').val(comp).trigger('change');
            $('#employee_type').val(type).trigger('change');

            // Set contribution values
            $('.employeer_contribute').val(con1);
            $('.company_contribute').val(con2);
            $('.company_contribute1').val(con3);


            setTimeout(function () {
                var result = $("#component option:selected").text();

                if (result === "ESI" || result === "PF") {
                    $('.contribute').show();
                    $('.employeer_contribute, .company_contribute').attr('required', true);

                    if (result === "PF") {
                        $('.contribute1').show();
                        $('.company_contribute1').attr('required', true);
                    } else {
                        $('.contribute1').hide();
                        $('.company_contribute1').removeAttr('required');
                    }

                    $('.employee_type_hide').hide();
                    $('.employee_type').removeAttr('required');

                } else if (result === "HRA" || result === "DA") {
                    $('.employee_type_hide').show();
                    $('.employee_type').attr('required', true);
                    $('.contribute, .contribute1').hide();
                    $('.employeer_contribute, .company_contribute, .company_contribute1').removeAttr('required');
                } else {
                    $('.contribute, .contribute1, .employee_type_hide').hide();
                    $('.employeer_contribute, .company_contribute, .company_contribute1').removeAttr('required');
                    $('.employee_type').removeAttr('required');
                }
            }, 300);
        });



    </script>

@endpush