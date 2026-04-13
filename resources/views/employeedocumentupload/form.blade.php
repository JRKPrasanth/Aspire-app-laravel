@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Employee Document Upload</h3>
    @include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">

    <form action="" id="save" enctype="multipart/form-data">
        <div class="card-body p-4">

            <input type="hidden" name="edit_id" value="{{ $id }}" id="edit_id" />
            <input type="hidden" name="employee_id" value="{{ $employee_id }}" id="employee_id" />
            {{ csrf_field() }}

            <!-- Employee Info -->
            <div class="row g-4 mb-3">

                <div class="col-md-4">
                    <label class="form-label">Employee Name</label>
                    <select id="employee_name" name="employee_name"
                            class="form-select select2" style="pointer-events:none;" readonly>
                        {!! $employee_name !!}
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Mobile</label>
                    <input type="text" id="mobile" name="mobile"
                           value="{{ $mobile_number }}"
                           class="form-control" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Mail</label>
                    <input type="text" id="mail" name="mail"
                           value="{{ $email }}"
                           class="form-control" readonly>
                </div>

            </div>

            <!-- COMPANY PROVISION -->
            <fieldset class="border rounded p-3 mb-4">
                <legend class="float-none w-auto px-3 fs-6 fw-bold text-primary">
                    Company’s Provision
                </legend>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Document Name</th>
                                <th>Date of Provision</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>

                        <tbody>

                        @php
                            $emp_remark = isset($company_remarks) ? json_decode($company_remarks) : [];
                            $count = isset($company_provision_data) ? json_decode($company_provision_data) : [];
                        @endphp

                        @foreach ($company_provision as $key => $value)

                            @php
                                $checked = '';
                                $date = '';
                                $remarks = '';

                                if (!empty($count)) {
                                    foreach ($count as $k => $row) {
                                        if ($row[0] == $value->id) {
                                            $checked = 'checked';
                                            $date = date('Y-m-d', strtotime($row[1]));
                                            $remarks = $emp_remark[$k][1] ?? '';
                                        }
                                    }
                                }
                            @endphp

                            <tr>
                                <td>
                                    <label class="d-flex align-items-center gap-2">
                                        <input type="checkbox" name="company_doc{{ $key }}"
                                               value="{{ $value->id }}"
                                               data-target="datebox-{{ $key }}"
                                               {{ $checked }}
                                               class="form-check-input">
                                        {{ $value->document }}
                                    </label>
                                </td>

                                <td style="width: 250px;">
                                    <div id="datebox-{{ $key }}" class="{{ $checked ? '' : 'd-none' }}">
                                        <input type="date" name="provision_date[]"
                                               value="{{ $date }}"
                                               class="form-control">
                                    </div>
                                </td>

                                <td style="width: 300px;">
                                    <textarea name="company_remarks[]" rows="3"
                                              class="form-control">{{ $remarks }}</textarea>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </fieldset>

            <!-- EMPLOYEE PROVISION -->
            <fieldset class="border rounded p-3">
                <legend class="float-none w-auto px-3 fs-6 fw-bold text-primary">
                    Employee’s Provision
                </legend>

                <div class="d-flex justify-content-end mb-2">
                    <a class="btn btn-sm btn-primary add_row add-row newitem">
                        <i class="fa fa-plus"></i> Add Item
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered employee_provision align-middle clone_table">
                        <thead class="table-light">
                            <tr>
                                <th>Document Name</th>
                                <th>File Upload</th>
                                <th style="width: 18%">Remarks</th>
                                <th style="width: 8%">Action</th>
                            </tr>
                        </thead>

                        <tbody class="clone_lines_body">

                        @php
                            $emp_remarks = isset($employee_remarks) ? json_decode($employee_remarks) : [];
                            $count1 = isset($employee_provision_data) ? json_decode($employee_provision_data) : [];
                        @endphp

                        @if (!empty($count1))
                            @foreach ($count1 as $key => $row)

                            <tr class="clone rcopy">
                                <td>
                                    <select name="document[]" class="form-select select2" required>
                                        {!! config('global.CONT')->jCombo(
                                            'm_employee_doc_check_list',
                                            'id',
                                            'emp_document',
                                            $row[0]
                                        ) !!}
                                    </select>
                                </td>

                                <td>
                                    <input type="file" name="file_upload[]" class="form-control mb-1">

                                    @if ($row[1] != "")
                                        <input type="hidden" name="ex_file[]" value="{{ $row[1] }}">
                                        <a download
                                           class="text-primary fw-semibold"
                                           href="{{ '../documentupload/'.$employee_id.'/'.$row[1] }}">
                                            {{ $row[1] }}
                                            <img src="{{ URL::to('') }}/images/download.png"
                                                 width="20" class="ms-1">
                                        </a>
                                    @else
                                        <input type="hidden" name="ex_file[]" value="">
                                    @endif
                                </td>

                                <td>
                                    <textarea name="employee_remarks[]" rows="3"
                                              class="form-control">{{ $emp_remarks[$key][1] ?? '' }}</textarea>
                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
                                </td>
                            </tr>

                            @endforeach

                        @else

                            <tr class="clone rcopy">
                                <td>
                                    <select name="document[]" class="form-select select2" required>
                                        {!! $employee_provision !!}
                                    </select>
                                </td>

                                <td>
                                    <input type="file" name="file_upload[]" class="form-control">
                                </td>

                                <td>
                                    <textarea name="employee_remarks[]" rows="3"
                                              class="form-control"></textarea>
                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
                                </td>
                            </tr>

                        @endif

                        </tbody>
                    </table>
                </div>
            </fieldset>

            <!-- BUTTONS -->
            <div class="text-center mt-4">
                <button type="button" class="btn btn-success px-4 me-2 save_form">Save</button>
                <a class="btn btn-secondary px-4"
                   href="{{ url('empuploaddoc') }}">Cancel</a>
            </div>

        </div>
    </form>

</div>






@endsection
@push('scripts')


    <script>

        // date coloum hide view
        $().ready(function () {
            $('[id^="company_doc"]').on('click', function () {
                var targetId = $(this).data('target');
                var inputField = $('#' + targetId);

                if ($(this).is(':checked')) {
                    inputField.show();
                } else {
                    inputField.hide();
                    inputField.find('input').val('');
                }
            });
        });
        // show date if value there
        $().ready(function () {
            $('[id^="provision_date"]').each(function () {
                var inputField = $(this);

                if (inputField.val() !== '') {
                    inputField.closest('.input-group').show();
                } else {
                    inputField.closest('.input-group').hide();
                }
            });
        });
        // End


            var dup_chk = true;
            function duplicate_validate() {
                var department_name = $(".department_name").val();
                var edit_id = $("#edit_id").val();

                $.ajax({
                    cache: false,
                    url: 'employeedepartment/checkname', //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async: false,
                    data: { department_name: department_name, edit_id: edit_id },
                    success: function (response) {

                        if (response == 1) {
                            $('.dup_name')
                                .html('Department Name: ' + department_name + ' already exists')
                                .removeClass('d-none')
                                .addClass('d-block');

                            $(".department_name").val('');
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



            $(document).on('click', '.save_form', function (e) {

                    var form = $("#save");
                    form.parsley().validate();
                    duplicate_validate();
                    if (form.parsley().isValid() && dup_chk == true) {

                    $.ajax({
                        url: "{{ url('documentssave')}}",
                        type: "POST",
                        data: form.serialize(),
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
                            showCustomAlert('Document Uploadedlly', "success");
                            setTimeout(function () {
                                var url = "{{ URL::to('empuploaddoc') }}";
                                window.location.href = url;
                            }, 2000);
                        }
                        if (data == 2) {
                            showCustomAlert('Document Updated Successfully', 'success');
                            setTimeout(function () {
                                var url = "{{ URL::to('empuploaddoc') }}";
                                window.location.href = url;
                            }, 2000);
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



       // Add Row
$(document).on('click', '.add-row', function () {
    const $tbody   = $('.clone_lines_body');
    const $lastRow = $tbody.find('tr:last');

    // 1) Clone the last row (keep events = false)
    const $newRow = $lastRow.clone(false, false);

    // 2) Clean up cloned row's Select2 markup and values
    $newRow.find('select.select2').each(function () {
        // remove Select2 wrapper from cloned DOM, if any
        $(this).next('.select2').remove();

        // remove Select2-specific attributes/classes from the cloned element
        $(this)
            .removeClass('select2-hidden-accessible')
            .removeAttr('data-select2-id')
            .off(); // remove cloned events

        // clear the value
        $(this).val(null);
    });

    // 3) Clear other inputs in the cloned row
    $newRow.find('.bulk_company_line_id').val('');
    $newRow.find('.bulk_line_no').val('');   // will be set by updateLineNumbers()
    $newRow.find('.bulk_description').val('');

    // 4) (Optional but recommended) fix duplicate IDs in cloned row
    $newRow.find('[id]').each(function () {
        const newId = $(this).attr('id') + '_' + Date.now();
        $(this).attr('id', newId);
    });

    // 5) Append cloned row
    $tbody.append($newRow);

    // 6) Initialize Select2 only for the new row's selects
    $newRow.find('select.select2').select2({ width: '100%' });

    // 7) Update line numbers
    updateLineNumbers();
});

// Remove button
$(document).on('click', '.remove-row', function () {
    const rowCount = $('.clone_lines_body tr').length;
    if (rowCount > 1) {
        $(this).closest('tr').remove();
        updateLineNumbers();
    } else {
        showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
    }
});

// Renumber Line Nos
function updateLineNumbers() {
    $('.clone_lines_body tr').each(function (index) {
        $(this).find('.bulk_line_no').val(index + 1);
    });
}



    </script>


@endpush