@extends('layouts.header')
@section('content')
<h3 class="text-danger">Shift Upload</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
        <form action="" id="shiftupload" data-parsley-validate>
            {{ csrf_field() }}
            <input type="hidden" name="edit_id" value="{{ $id }}" id="edit_id" />

            <div class="row mb-4">
                <!-- Employee Dropdown -->
                <div class="col-md-4" style="pointer-events: none;">
                    <label for="employee_id" class="form-label">
                        <span class="text-danger">*</span> Employee
                    </label>
                    <select name="employee_id" id="employee_id" class="form-select select2" required>
                      
                    </select>
                </div>

                <!-- Shift Data -->
                <div class="col-md-8">
                    <label for="shift_type" class="form-label">
                        <span class="text-danger">*</span> Shift Data
                    </label>
                    <textarea name="shift_type" id="shift_type" class="form-control" rows="5" required>{{ 
                        collect($shift_details)->map(function($item, $key) use (&$i) { return ($key + 1) . '-' . $item[0]; })->implode(', ')
                    }}</textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="row text-center">
                <div class="col-md-12">
                    <button type="button" id="save" class="btn btn-success save_form me-2">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <a href="{{ url('shiftupload') }}" class="btn btn-secondary">
                        <i class="fa fa-times"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>




@endsection
@push('scripts')

<script>
	
	$(document).ready(function()
    {
        // employee dropdown
    
var logged_id = '{{$name}}';

loadDropdown(
    "#employee_id",
    "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:employee_number|first_name",
    logged_id,
    "-- Select Employee --"
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
		
		
// save 
           $(document).on('click','.save_form',function()
                {
                    var url	="{{URL::to('shiftuploadupdate')}}";
                   
                    var form = $('#shiftupload');
                    form.parsley().validate();                    
                    if (form.parsley().isValid())
                    {
                         var data	= $('#shiftupload').serialize();
                        $.post(url,data,function(data1)
                        {
                            if(data1 == 1)
                            {                               
                              showCustomAlert('Upload data  updated  Successfully','success');
                              window.location.href="{{URL::to('shiftupload')}}";
                               
                            }
                          
                        });
                    }
                    
                    });	
    });
    
    </script>


@endpush
