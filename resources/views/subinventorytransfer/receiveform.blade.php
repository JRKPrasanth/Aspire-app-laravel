 @extends('layouts.header')
@section('content')
<h3 class="text-danger">Subinventory Transfer Receive</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-header bg-primary text-white fw-semibold">
    Subinventory Transfer Receive
  </div>

  <div class="card-body">
    <form method="post" id="subinv_transfer_form">
      @csrf
      <input type="hidden" class="subinventory_transfer_line_id" name="subinventory_transfer_line_id" value="{{ $subinventory_transfer_line_id }}">
      <input type="hidden" class="pdtindex" value="">

      <div class="row g-4">
        <!-- Left Column -->
        <div class="col-md-3">
          <div class="mb-3">
            <label class="form-label">Subinventory Transfer No</label>
            <input type="text" id="subtransfer_no" name="subtransfer_no"
                   class="form-control subtransfer_no" value="{{ $subtransfer_no }}" readonly>
          </div>

          <div class="mb-3 none">
            <label class="form-label text-danger">* Product Group Name</label>
            <select id="product_group_id" name="product_group_id" class="form-select select2 product_group_id" required>
              {!! $product_group_id !!}
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label text-danger">* Batch No</label>
            <input type="text" id="batch_no" name="batch_no"
                   class="form-control batch_no" value="{{ $batch_no }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Remarks</label>
            <input type="text" id="remarks" name="remarks"
                   class="form-control remarks" value="{{ $remarks }}" readonly>
          </div>
        </div>

        <!-- Middle Column -->
        <div class="col-md-5">
          <div class="mb-3 none">
            <label class="form-label">Product Name</label>
            <select name="product_id" class="form-select select2 product_id" required>
              {!! $product_id !!}
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Transfer Qty</label>
            <input type="text" id="from_transfer_qty" name="from_transfer_qty"
                   class="form-control from_transfer_qty" value="{{ $from_transfer_qty }}" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label text-danger">* Receive Qty</label>
            <input type="text" id="receive_qty" name="receive_qty"
                   class="form-control receive_qty" required>
          </div>

          <div class="mb-3">
            <label class="form-label">QOH</label>
            <input type="text" id="qoh" name="qoh"
                   class="form-control qoh" value="{{ $qoh }}" readonly>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label">To Subinventory</label>
            <select id="to_subinv_id" name="to_subinv_id"
                    class="form-select select2 to_subinv_id" required>
              {!! $to_subinv_id ?? '' !!}
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">To Locator</label>
            <select name="to_loc_id" class="form-select select2 to_loc_id" required>
              {!! $to_loc_id ?? '' !!}
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label text-danger">* Product Expiry Date</label>
            <input type="text" id="product_expire_date" name="product_expire_date"
                   class="form-control product_expire_date" value="{{ $product_expire_date }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label text-danger">* Manufacturer Date</label>
            <input type="text" id="manufacture_date" name="manufacture_date"
                   class="form-control manufacture_date" value="{{ $manufacture_date }}" required>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="text-center mt-4">
        <input type="hidden" name="submit_type" class="submit_type" value="">
        <button type="submit" class="btn btn-success px-4 me-2 saveform" value="RECEIVE">
          <i class="bi bi-check-circle"></i> Receive
        </button>
        <a href="{{ url('subinventorytransferreceive') }}" class="btn btn-secondary px-4">
          <i class="bi bi-x-circle"></i> Cancel
        </a>
      </div>
    </form>
  </div>
</div>








@endsection
@push('scripts')

<script>
	
$(document).ready(function(){
	
// Preselect Subinventory
var loc = "{{$to_subinv_id ?? ''}}";

$.ajax({
    url: "{{ URL::to('jcomboform') }}?table=m_subinventory_t:subinventory_id:subinventory_name&order_by=subinventory_name asc",
    type: 'GET',
    success: function (data) {
        if (typeof data === "string") {
            try { data = JSON.parse(data); } catch (e) { console.error("Invalid JSON:", data); return; }
        }

        // Reset and populate
        $('.to_subinv_id').html('<option value="">-- Select Subinventory --</option>');
        $.each(data, function (i, item) {
            let selected = (item.val == loc) ? 'selected' : '';
            $('.to_subinv_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
        });

        // Refresh select2 if used
        $('.to_subinv_id').trigger('change.select2');

        // Auto-load locators if preselected
        if (loc) {
            loadLocators(loc, "{{$to_loc_id ?? ''}}");
        }
    }
});


// Change Event: Load Locators
$(document).on('change', '.to_subinv_id', function () {
    $(".to_loc_id").css("pointer-events", "auto");
    var to_subinv_id = $(this).val();
    if (to_subinv_id) {
        loadLocators(to_subinv_id, "");
    } else {
        $('.to_loc_id').html('<option value="">-- Select Locator --</option>').trigger('change.select2');
    }
});


// Helper function to populate locators
function loadLocators(subinvId, selectedVal = "") {
    var url = "{{ URL::to('jcomboform') }}?table=m_sublocators_t:sublocator_id:locator_code&parent=subinventory_id=" + subinvId + "&order_by=locator_code asc";

    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            if (typeof data === "string") {
                try { data = JSON.parse(data); } catch (e) { console.error("Invalid JSON:", data); return; }
            }

            $('.to_loc_id').html('<option value="">-- Select Locator --</option>');
            $.each(data, function (i, item) {
                let selected = (item.val == selectedVal) ? 'selected' : '';
                $('.to_loc_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
            });

            $('.to_loc_id').trigger('change.select2');
        }
    });
}

	
	
$('.product_id,.product_group_id').attr('readonly','readonly').css('pointer-events','none');
	
$(document).on('keyup','.receive_qty',function()	{

        var qoh=parseInt($('.qoh').val());
        var rcv_qty=parseInt($('.receive_qty').val());
	if(rcv_qty>qoh)
	{
	showCustomAlert('Receive qty is more than Qoh','info');
	$('.receive_qty').val('');
	return true;
	}
	
	});

$(document).on('keyup','.receive_qty',function()	{

	 
        var from_qty=parseInt($('.from_transfer_qty').val());
        var rcv_qty=parseInt($('.receive_qty').val());
	if(rcv_qty>from_qty)
	{
	showCustomAlert('Receive qty is more than Transfered Qty','info');
	$('.receive_qty').val('');
	return true;
	}
	
	});



        /*qty receive function*/
	
    $(document).on('click', '.saveform', function(event) {
    event.preventDefault();
    var btn = $('#btn');
    btn.prop('disabled', true);
    btn.text('RECEIVE');

    var id = $(".subinventory_transfer_line_id").val();
    var receive_qty = $(".receive_qty").val();
    var sub = $(".to_subinv_id").val();
    var loc = $(".to_loc_id").val();
    var expiryDate = $(".product_expire_date").val();
    var mfgDate = $(".manufacture_date").val();
    var form = $('#subinv_transfer_form');
    form.parsley().validate();

    if (form.parsley().isValid()) {
      			var $btn = $(this);            
		      	$btn.prop('disabled', true);

        var url = "{{ URL::to('Receiveupdate')}}/" + id + "/" + receive_qty + "?sub=" + sub + "&loc=" + loc + "&expiryDate=" + expiryDate + "&mfgDate=" + mfgDate;
        $.get(url, function(data) {
            var status = data.status;
            var msg = data.message;
            showCustomAlert(msg,status);
            setTimeout(function() {
                window.location.href = "{{url('subinventorytransferreceive')}}";
            }, 1500);
        }).fail(function() {
            btn.prop('disabled', false);
            btn.text('RECEIVE');
        });
    } else {
        btn.prop('disabled', false);
        btn.text('RECEIVE');
    }
  });



});

var dateToday = new Date();


        $(document).on("focus", ".product_expire_date", function () {

            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                minDate: 0,
                maxDate: '+4Y',
                showAnim: "slideDown",
                yearRange: "c:+4",

            });
        });
	
</script>


@endpush
