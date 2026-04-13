@extends('layouts.header')
@section('content')
<h3 class="text-danger">Miss Punch</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg border-0 rounded-4">
    <form method="post" action="" id="saveForm" data-parsley-validate>
        <?php  
        $data = \Session::get('data'); 
        if ($pageMethod == "punchrequest") { 
        ?>
        <div class="card-body card-block">
            <input type="hidden" name="edit_id" value="" id="edit_id" />
            {{ csrf_field() }}

            <div class="row g-4">
                <!-- Employee -->
                <div class="form-group col-md-4 employee_pointer">
                    <label for="employee_id" class="form-control-label col-md-5">
                        <span class="req">*</span>Employee
                    </label>
                    <div class="col-md-8">
                        <select name="employee_id" id="employee_id" class="select2 form-control" rows="5" required></select>
                    </div>
                </div>

                <!-- Request For -->
                <div class="form-group col-md-4">
                    <label class="form-control-label col-md-5">
                        <span class="req">*</span>Request For
                    </label>
                    <div class="col-md-8">
                        <select name="misspunch" id="misspunch" class="form-control select2 misspunch" required>
                            {!! $misspunch !!}
                        </select>
                        <span class="btn btn-danger dup_name" style="display:none;"></span>
                    </div>
                </div>

                <!-- Date -->
                <div class="form-group col-md-4">
                    <label class="form-control-label col-md-5">
                        <span class="req">*</span>Date
                    </label>
                    <div class="col-md-8">
                        <input class="form-control date start_date" id="date" name="date" type="text" required readonly>
                    </div>
                </div>

                <!-- Actual In Time -->
                <div class="form-group col-md-4 contribute">
                    <label class="form-control-label col-md-5">
                        <span class="req">*</span>Actual In Time
                    </label>
                    <div class="col-md-8">
                        <input type="text" id="in_time" name="in_time" class="form-control start_date_time in_time" required>
                    </div>
                </div>

                <!-- Actual Out Time -->
                <div class="form-group col-md-4 contribute1">
                    <label class="form-control-label col-md-5">
                        <span class="req">*</span>Actual Out Time
                    </label>
                    <div class="col-md-8">
                        <input type="text" id="out_time" name="out_time" class="form-control start_date_time out_time" required>
                    </div>
                </div>
				
                <!-- Reason -->
                <div class="form-group col-md-4">
                    <label class="form-control-label col-md-5">
                        <span class="req">*</span>Reason
                    </label>
                    <div class="col-md-8">
                        <input type="text" id="reason" name="reason" class="form-control reason" required>
                    </div>
                </div>

                <!-- Misspunch Status -->
                <div class="form-group col-md-4" style="pointer-events: none;">
                    <label class="form-control-label col-md-5">
                        <span class="req">*</span>Misspunch Status
                    </label>
                    <div class="col-md-8 pointer">
                        <select name="status" id="status" class="select2 form-control" required>
                            <option value="INITIATED">INITIATED</option>
                            <option value="APPROVED">APPROVED</option>
                            <option value="REJECTED">REJECTED</option>
                        </select>
                    </div>
                </div>

                <!-- Forwarded To -->
                <div class="form-group col-md-4">
                    <label class="form-control-label col-md-5">
                        <span class="req">*</span>Forwarded To
                    </label>
                    <div class="col-md-8">
                        <select id="forwarded_id" class="select2 forwarded_id form-control" name="forwarded_id" required></select>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-success save_form px-4">
                        Save
                    </button>
                </div>
            </div>
        </div>
        <?php } ?>
    </form>
</div>


<div class="card shadow-lg border-0 rounded-4">
<div class="container mt-4">
  <table id="holiTbl" class="table table-bordered table-striped w-100">
    <thead>
          <tr class="table-warning">
        <th>Employee Id</th>
        <th>Employee Name</th>
        <th>Reporting Name</th>
        <th>Missed Punch Name</th>
        <th>Date</th>
        <th>Reason</th>
        <th>Status</th>
        <th>Apply Date</th>

      </tr>
      <tr class="table-info">
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>

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
   

        var forwarded_id = '{{$forwarded_id}}';
        var logged_id = '{{$logged_id}}';

/* ==== small helpers ==== */
function populateSelect({ el, url, selected, placeholder = '-- Select --', after }) {
    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            if (typeof data === 'string') {
                try { 
                    data = JSON.parse(data); 
                } catch (e) {
                    console.error('Invalid JSON response:', data);
                    return;
                }
            }

            const $el = $(el);
            $el.html(`<option value="">${placeholder}</option>`);

            $.each(data, function (i, item) {
                const isSel = String(item.val) === String(selected) ? 'selected' : '';
                $el.append(`<option value="${item.val}" ${isSel}>${item.option_name}</option>`);
            });

            // If Select2 is used
            $el.trigger('change.select2');

            if (typeof after === 'function') after($el, data);
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
}

/* ==== calls ==== */
// forwarded_id (employees)
populateSelect({
    el: '#forwarded_id',
    url: "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:first_name",
    selected: forwarded_id,
    placeholder: '-- Select Forwarded To --'
});

// employee_id (employees)
populateSelect({
    el: '#employee_id',
    url: "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:first_name",
    selected: logged_id,
    placeholder: '-- Select Employee --'
});


$('.contribute').hide();
$('.contribute1').hide();

	
$(document).on('change', '.misspunch', function () {
  const ltype = $('#misspunch').val();

    if (ltype == 279) {
     $('.contribute').show();
     $('.contribute1').hide();
    }

     if (ltype == 280) {
    $('.contribute').hide();
    $('.contribute1').show();

    }
    
    if (ltype == 672) {
        $('.contribute').show();
        $('.contribute1').show();
    }
	  });
	
// table data
$(document).ready(function () {
  var table = $('#holiTbl').DataTable({
    processing: true,
    serverSide: true,
    order: [[4, 'desc']],
    ajax: "{{ route('missdata') }}",
    columns: [
      { data: 'employee_id', name: 'employee_id', visible:false },
      { data: 'first_name', name: 'first_name' },
      { data: 'reporting_name', name: 'reporting_name' },
      { data: 'lookup_meaning', name: 'lookup_meaning' },
      { data: 'date', name: 'date' },
      { data: 'reason', name: 'reason' },
      { data: 'status', name: 'status' },
      { data: 'created_at', name: 'created_at' },
    ]
  });

  // Column search
  $('#holiTbl thead').on('keyup change', '.column-search', function () {
    let index = $(this).closest('th').index();
    table.column(index).search(this.value).draw();
  });
});


	
// save

	$(document).on('click', '.save_form', function () {
	
    var form = $("#saveForm");
    form.parsley().validate();
	let dup_chk = true;
		
    if ( dup_chk == true) {
        			var $btn = $(this);            
			$btn.prop('disabled', true);
        $.ajax({
            url: "{{ URL::to('misspunchsaveap') }}",
            type: "POST",
            data: form.serialize(),
            success: function (data) {
                // Show success message
               showCustomAlert('Saved successfully!', 'success');
                window.location.reload();
            },
            error: function (xhr) {
              showCustomAlert('Save failed. Try again.', 'error');
            }
        });
    }
});	
	
	
	
	</script>

@endpush
