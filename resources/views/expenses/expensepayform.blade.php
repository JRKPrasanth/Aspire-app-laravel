@extends('layouts.header')
@section('content')
<h3 class="text-danger">Expenses Pay</h3>
@include('layouts.breadcrumb')


<form method="post" action="" id="expenses_form" data-parsley-validate>
  {{ csrf_field() }}

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white rounded-top-4">
    </div>

    <div class="card-body p-4">
      <div class="row g-4">

        <!-- Left Column -->
        <div class="col-md-4">
          <input type="hidden" id="expense_id" name="expense_id" value="{{ $row->expense_id }}">
		  <input type="hidden" id="source" name="source" value="">
          <input type="hidden" id="expense_status" name="expense_status" value="{{ $row->expense_status }}">

          <div class="mb-3">
            <label class="form-label fw-semibold">Expense No</label>
            <input type="text" id="expense_no" name="expense_no" class="form-control expense_no" value="" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold"><span class="text-danger">*</span> Expense Date</label>
            <div class="input-group">
              <input type="text" id="expense_date" name="expense_date"
                     class="form-control datepicker expense_date" value="{{ $row->expense_date }}" required>
              <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold"><span class="text-danger">*</span> Expense Type</label>
            <div class="d-flex gap-3">
              <div class="form-check">
                <input class="form-check-input expense_type" type="radio" name="expense_type" value="Goods"
                       {{ $row->expense_type == 'Goods' ? 'checked' : '' }}>
                <label class="form-check-label">Goods</label>
              </div>
              <div class="form-check">
                <input class="form-check-input expense_type" type="radio" name="expense_type" value="Labour"
                       {{ $row->expense_type == 'Labour' ? 'checked' : '' }}>
                <label class="form-check-label">Labour</label>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">HSN Code</label>
            <input type="text" id="gst_code_id" name="gst_code_id" class="form-control gst_code_id" value="{{ $row->gst_code_id }}" required>
          </div>
        </div>

        <!-- Middle Column -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label fw-semibold"><span class="text-danger">*</span> Expense Account</label>
            <select id="expense_account_id" name="expense_account_id" class="form-select select2 expense_account_id" required>
              {!! $expense_account_id !!}
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Tax Group</label>
            <select id="tax_group_id" name="tax_group_id" class="form-select select2 tax_group_id">
              {!! $tax_group_id !!}
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Supplier Name</label>
            <select id="supplier_id" name="supplier_id" class="form-select select2 supplier_id">
              {!! $supplier_id !!}
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Applicable for Reverse Charge?</label>
            <div class="form-check form-switch">
              <input class="form-check-input reverse_charge" type="checkbox" name="reverse_charge[]" value="1"
                     {{ $row->reverse_charge == '1' ? 'checked' : '' }}>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label fw-semibold">Remarks</label>
            <input type="text" id="remarks" name="remarks" class="form-control remarks" value="{{ $row->remarks }}">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold"><span class="text-danger">*</span> Invoice</label>
            <input type="text" id="invoice" name="invoice" class="form-control invoice" value="{{ $row->invoice }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold"><span class="text-danger">*</span> Expense Amount</label>
            <input type="text" id="expense_amount" name="expense_amount" class="form-control expense_amount"
                   value="{{ $row->expense_amount }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold"><span class="text-danger">*</span> Pay Amount</label>
            <input type="text" id="pay_amount" name="pay_amount" class="form-control pay_amount"
                   value="{{ $row->pay_amount }}" required>
          </div>
        </div>

      </div>
    </div>

    <div class="card-footer text-center bg-light rounded-bottom-4 py-3">
      <input type="hidden" name="submit_type" class="submit_type" value="">
      <button type="button" class="btn btn-success px-4 me-2 saveform" value="INITIATED">
        <i class="bi bi-check-circle me-1"></i> Submit
      </button>
      <a href="{{ url($pageModule) }}" class="btn btn-secondary px-4">
        <i class="bi bi-x-circle me-1"></i> Cancel
      </a>
    </div>
  </div>
</form>
            
     
@endsection
@push('scripts')

<script>

	
    $(document).ready(function(){
        

        
    $(document).on('change', '.expense_type', function() {
        var radio=$('input[name=expense_type]:checked').val();
        if(radio=="Labour"){
			$('#hsn').text("SAC Code");
        }
        else{
             $('#hsn').text("HSN Code");
        }
    });
		
    /*Validation*/
	$(document).on('keypress','.expense_amount,.pay_amount', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});
	/*End*/
        
 /* Purpose For Save Function*/      
      
    $(document).on('click', '.saveform', function() {
    var btnval = $(this).val();
    
		if(btnval == 'INITIATED')
		{
			$("#expense_status").val('INITIATED');
		}

	    else if(btnval == 'APPROVED')
	    {
			$("#expense_status").val('APPROVED');
	    }
		else
		{
			$("#expense_status").val('REJECTED');
		}
    $('#savestatus').val(btnval);
    var url = "{{ url('expensessave') }}";
    var red_url = "{{url('expenses')}}";
    var app_url = "{{url('expenseapproval')}}";
      
        validationrule('expenses_form');
		var form = $('#expenses_form');
        form.parsley().validate();
        if (form.parsley().isValid())
        {

            var formdata	= $('#expenses_form').serialize();
                     
            $.post(url, formdata, function(data)
            {
            var status = data.status;
            var msg = data.message;
            var id = data.id;
            var edit_url = "{{ url('expensescreate') }}/" + id;
            if (btnval != 'SAVE' && btnval != 'DRAFT')
            {
		    showCustomAlert(msg,status);
            setTimeout(function(){
            window.location.href = app_url;
            }, 1500);
            }
            else
            {
            showCustomAlert(msg,status);
            setTimeout(function(){
            window.location.href = red_url;
            }, 1500);
            }
            });
            }
    });
		
    });

</script>
  

@endpush
