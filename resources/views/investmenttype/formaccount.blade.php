@extends('layouts.header')
@section('content')
<h3 class="text-danger">HRMS Allowance Account Settings</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">

    <form id="save" enctype="multipart/form-data" data-parsley-validate>
        <div class="card-body card-block">
            <input type="hidden" name="edit_id" value="{{$account_allowance_setting_id}}" id="edit_id" />
            {{ csrf_field()}}

            <div class="row">
                <div class="form-group col-md-5">
                    <label for="inputIsValid" class="col-form-label col-md-5"><span class="req">*</span>Department
                        Name</label>
                    <div class="col-md-8">
                        <select id="department_id" name="department_id" class="select2 department_id" required>
                        </select>
                    </div>
                </div>


            </div>


            <div class="row mt-4">
                <div class="col-md-12">
                    <div id="preview-area" class="table-responsive">
                        <table class="table table-bordered clone_table">

                            <thead class="table-light">
                                <tr>
                                    <th>Allowance</th>
                                    <th>Account Structure</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody class="clone_lines_body">

                                @if(count($line_data)>0)
                                @foreach($line_data as $key => $value)
                                <tr class="clone clonedInput rcopy">


                                    <td>
                                        <input type="hidden" name="account_allowance_setting_line_id[]"
                                            class="account_allowance_setting_line_id"
                                            id="account_allowance_setting_line_id"
                                            value="{{$value->account_allowance_setting_line_id}}" />
                                        <select class="select2 allowance_id" id="allowance_id" name="allowance_id[]"
                                            required />
                                        {!!$value->allowance_id!!}
                                        </select>
                                    </td>
                                    <td>
                                        <select id="account_structure_id" name="account_structure_id[]"
                                            class="select2 account_structure_id" required />
                                        {!!$value->account_structure_id!!}
                                        </select>
                                    </td>

                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                            <i class="fas fa-minus-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="clone clonedInput rcopy">
                                    <td>
                                        <input type="hidden" name="account_allowance_setting_line_id[]"
                                            class="account_allowance_setting_line_id"
                                            id="account_allowance_setting_line_id" value="" />

                                        <select class="select2 allowance_id" id="allowance_id" name="allowance_id[]"
                                            required />
                                        {!!$allowance_id!!}
                                        </select>
                                    </td>
                                    <td>
                                        <select id="account_structure_id" name="account_structure_id[]"
                                            class="select2 account_structure_id" required />
                                        {!!$account_structure_id!!}
                                        </select>
                                    </td>

                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                            <i class="fas fa-minus-circle"></i>
                                        </button>
                                    </td>
                                </tr>

                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>


            <div class="row mt-4 mb-3">
                <div class="col-md-12 text-center ">
                    <button type="button" class="btn  btn-success px-4 me-2 save_form">Save</button>
                    <a href="{{ url('hrmsallowancesettings')}}"><button type="button" class="btn btn-secondary px-4">Cancel</button></a>
                </div>
            </div>

        </div>
    </form>

</div>







@endsection
@push('scripts')

<script>




    $(document).ready(function () {
		
var url = "{{ URL::to('jcomboform1') }}?table=m_department_lines_t:department_line_id:sub_department_code|sub_department_name&parent=and parent_class_id=" + 0 + "&order_by=department_line_id asc";

			$.ajax({
				url: url,
				type: 'GET',
	success: function (data) {
		// Parse JSON string if needed
		if (typeof data === "string") {
			try {
				data = JSON.parse(data);
			} catch (e) {
				console.error("Invalid JSON response:", data);
				return;
			}
		}

		$('.department_id').html('<option value="">-- Select Department --</option>');

		$.each(data, function (i, item) {
			let selected = item.val == "{{$department_id}}" ? 'selected' : '';
			$('.department_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
		});

		$('.department_id').trigger('change.select2'); 
	}


			});
		




        $(document).on('keypress', '#name,#inv_name', function (ev) {
            var regex = new RegExp("^[a-z,A-Z.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });



        $(document).on('keypress', '.inv_limit', function (ev) {
            var regex = new RegExp("^[0-9]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });




        /** Save data Start **/
        $(document).on('click', '.save_form', function () {

            var data;
            data = $("#save").serialize();

            var form = $('#save');
            form.parsley().validate();
            if (form.parsley().isValid()) {
                $.post("{{ URL::to('hrmsallowancesettingssave')}}", data, function (data) {
                    if (data == 1) {

                        showCustomAlert('HRMS Allowance Settings Saved Successfully', 'success');
                        setTimeout(function () {
                            var url = "{{URL::to('hrmsallowancesettings')}}";
                            window.location.href = url;
                        }, 1300);
                    }
                    else if (data == 2) {
                        showCustomAlert('HRMS Allowance Settings Updated Successfully', 'success');
                        var url = "{{URL::to('hrmsallowancesettings')}}";
                        window.location.href = url;
                    }
                });
            }

        });

    });


    // Add Row
    $(document).on('click', '.add-row', function () {
        const $lastRow = $('.clone_lines_body tr:last');
        const $newRow = $lastRow.clone(false, false); // clone without events or data

        // Clear all input and select values in the cloned row
        $newRow.find('input').val('');
        $newRow.find('select').val('').trigger('change');

        // Remove any Select2 artifacts before reinitializing
        $newRow.find('select.select2').each(function () {
            if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
                $(this).select2('destroy');
            }
            $(this).removeAttr('data-select2-id');
            $(this).next('.select2').remove(); // remove the select2 container
        });

        // Append the cleaned-up cloned row
        $('.clone_lines_body').append($newRow);

        // Reinitialize select2
        $newRow.find('select.select2').select2({ width: '100%' });

        // Update line numbers
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