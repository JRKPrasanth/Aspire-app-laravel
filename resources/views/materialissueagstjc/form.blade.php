@extends('layouts.header')
@section('content')
<h3 class="text-danger">Material Re Issue</h3>
@include('layouts.breadcrumb')

<style>
  .uom_code_id,
  .batch_no,
  .bulk_uom_code_id,
  .product_id,
  .job_qty,
  .mtl_issue_date,
  .w_jobs_hdr_id,
  .bulk_qoh,
  .bulk_qty,
  .uom,
  .bulk_issue_qty,
  .bulk_mtl_issue_qty,
  .bulk_issued_qty,
  .bulk_balance_qty,
  .pdtdiv {
    pointer-events: none;
  }

  <?php if ($job_status == 'OPEN') { ?>
    .pdtdiv {
      pointer-events: none;
    }

  <?php } ?>
</style>

<div class="card shadow-lg rounded-4 border-0">
<div class="card-header bg-primary text-white fw-semibold"></div>
<div class="card-body card-block headerdiv1">
<form method="post" action="" id="materialreissues" data-parsley-validate>
<input type="hidden" value="" name="savestatus" id="savestatus" />
{{ csrf_field() }}

	

	<div class="row">
	   <div class="col-md-6">
            <div class="row mb-3 status none">
				<input type="hidden" name='process' rows='5' class='form-control process' id="process" data-show-subtext="true" data-live-search="true" value="" style="width:100%;">
				  <input type="hidden" name='source' rows='5' class='form-control source' id="source"
                  data-show-subtext="true" data-live-search="true" value="" style="width:100%;">
				<input name='matissue_hdr_id'  class='form-control matissue_hdr_id' id="matissue_hdr_id" type="hidden" value="{{ $matissue_hdr_id }}" readonly>
                <label for="inputIsValid" class=" col-form-label col-md-6">Product</label>
                <div class="col-md-6">
                    <select name='product_id' rows='5' class='form-control product_id select2'  id="product_id" >
                        {!! $ass_product_id !!}
                    </select>
                </div>
            </div>
		   <div class="row mb-3 none">
                <label for="inputIsValid" class=" col-form-label col-md-6">Job No</label>
                <div class="col-md-6">
                    <select name='w_jobs_hdr_id' rows='5' class='form-control w_jobs_hdr_id select2' id="w_jobs_hdr_id" data-show-subtext="true" data-live-search="true">
             
                        {!! $w_jobs_hdr_id !!}
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">

			
            <div class="row mb-3 status none">
                <label for="inputIsValid" class=" col-form-label col-md-6">Job Qty</label>
                <div class="col-md-6">
                    <input type="text" name='job_qty' rows='5' class='form-control job_qty' id="job_qty" data-show-subtext="true" data-live-search="true" value="{{ $job_qty }}" style="width:100%;">
                </div>
            </div>


<div class="row mb-3 none">
                <label for="inputIsValid" class=" col-form-label col-md-6">Batch No</label>
                <div class="col-md-6">
                    <input type="text" name='batch_no' rows='5' class='form-control batch_no' id="batch_no" data-show-subtext="true" data-live-search="true" value="{{$batch_no}}" style="width:100%;">
                </div>
            </div>

                   </div>
	</div>


<!--*****************************-Linedata ***************************-->	
	
<div class="row mt-4">

 <div id="preview-area" class="table-responsive">
 <table class="table table-bordered clone_table" style="width: 150%;">

<thead class='table-light'>
<tr>

<th>Line No</th>
<th>Product </th>
<th>Component Uom</th>
<th>Component Qty</th>
<th>Needed Qty</th>	
<th>Issued Qty</th>	
<th>Balance Qty</th>	
<th>Issue Qty</th>
<th>Add Issue Qty</th>
<th>Comments</th>
<th>Action</th>


</tr>
</thead>
<tbody class="clone_lines_body">
<!-- edit mode-->

<?php if(count($linedata)>=1) { ?>
    @foreach($linedata as $key=>$value)
    
    <?php if ($value->balance_qty > 0) { ?>
 <tr class="rcopy clone">
        <td>
            <input type="hidden" name="bulk_w_materialissue_line_id[]" class="form-control input-sm bulk_w_materialissue_line_id" value="">

            <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
        </td>
        <td class="pdtdiv">
            <select name="bulk_product_id[]" id="bulk_product_id" class="bulk_product_id form-control  select2 " required="required">{!! $value->product_id !!}</select>
        </td>
	
        <td class="uom">
            <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control bulk_uom_code_id select2" readonly>
                <option value=''> {!! $value->uom_code_id !!} </option>

            </select>
        </td>

        <td>
            <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="{{ $value->qty }}">
        </td>
	
        <td>
            <input type="text" name="bulk_issue_qty[]" class="form-control input-sm bulk_issue_qty input_qty_width" value="{{ $value->issue_qty }}">
        </td>
	 <!--purpose hide field for material request-->
		
	 <td>
            <input type="text" name="bulk_issued_qty[]" class="form-control input-sm bulk_issued_qty input_qty_width" value="{{ $value->issued_qty }}">
        </td>
	 <td>
            <input type="text" name="bulk_balance_qty[]" class="form-control input-sm bulk_balance_qty input_qty_width" value="{{ $value->balance_qty }}">
        </td>
	 <td>
            <input type="text" name="bulk_mtl_issue_qty[]" class="form-control input-sm bulk_mtl_issue_qty input_qty_width" value="" required>
        </td>
  
		 <td> <a href="#" class="subinvdetails" title="Add Issue Qty"> <i class="fa fa-plus"></i></a></td>
	 <input type="hidden" name="bulk_subinventory_id[]" class="form-control bulk_subinventory_id" value="">
	 <input type="hidden" name="bulk_locator_id[]" class="form-control bulk_locator_id" value="">
	 <input type="hidden" name="bulk_issueqty[]" class="form-control bulk_issueqty" value="">
	 <input type="hidden" name="bulk_batchnumber[]" class="form-control bulk_batchnumber" value="">

       <!-- end-->

        <td>
            <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value="{{ $value->comments }}">
        </td>
 
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger remove-row">
            <i class="fas fa-minus-circle"></i>
          </button>
        </td>

    </tr>
    <?php } ?>
    @endforeach
	<!-- create mode -->
	<?php   } if(count($linedata) < 1 ) { ?>
<tr class="rcopy clone">
        <td>
            <input type="hidden" name="bulk_w_materialissue_line_id[]" class="form-control input-sm bulk_w_materialissue_line_id" value="">
            <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="" readonly="readonly">
        </td>
        <td class="pdtdiv">
            <select name="bulk_product_id[]" id="bulk_product_id" class="form-control select2 bulk_product_id  " required="required">{!! $product_id !!}</select>
        </td>
	
        <td class="uom">
            <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control bulk_uom_code_id select2" >
                <option value=''> {!! $uom_code_id !!} </option>

            </select>
        </td>

        <td>
            <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value=" ">
        </td>
        <td>
            <input type="text" name="bulk_issue_qty[]" class="form-control input-sm bulk_issue_qty input_qty_width" value=" ">
        </td>
	<td>
            <input type="text" name="bulk_issued_qty[]" class="form-control input-sm bulk_issued_qty input_qty_width" value="{{ $value->issued_qty }}" >
        </td>
	 <td>
            <input type="text" name="bulk_balance_qty[]" class="form-control input-sm bulk_balance_qty input_qty_width" value="{{ $value->balance_qty }}" >
        </td>
		    <td>
            <input type="text" name="bulk_mtl_issue_qty[]" class="form-control input-sm bulk_mtl_issue_qty input_qty_width" value="" required>
        </td>
      
     <td> <a href="#" class="subinvdetails" title="Add Issue Qty"> <i class="fa fa-plus"></i></a></td>
	<input type="hidden" name="bulk_subinventory_id[]" class="form-control bulk_subinventory_id" value="">
	 <input type="hidden" name="bulk_locator_id[]" class="form-control bulk_locator_id" value="">
	 <input type="hidden" name="bulk_issueqty[]" class="form-control bulk_issueqty" value="">
	 <input type="hidden" name="bulk_batchnumber[]" class="form-control bulk_batchnumber" value="">

        <td>
            <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value=" ">
        </td>
 
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger remove-row">
            <i class="fas fa-minus-circle"></i>
          </button>
        </td>
      
    </tr>
    <?php }?>

</tbody>
</table>
	 
  <div class="text-end">
    <button type="button" class="btn btn-success btn-sm add-row">
      <i class="fas fa-plus-circle"></i> Add Row
    </button>
  </div>
	 
<input type="hidden" name="enable-masterdetail" value="true">
</div>
</div>




<div class="row mt-4 mb-3">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
			 <button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
				<a href="{{ url('materialissueagainstjc') }}"  class='btn btn-danger px-4 me-2'>Cancel</a>
		</div>
	</div>
</div>


	
<!-- Subinventory Details Modal -->
<div class="modal fade" id="subinvdetailsModal" tabindex="-1" aria-labelledby="subinvdetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl"> <!-- XL for 80% width -->
    <div class="modal-content shadow-lg rounded-3 border-0">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="subinvdetailsModalLabel">
          <i class="fa fa-warehouse me-2"></i> Subinventory Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        		  <input type="hidden" class="soindex" value="">
      </div>

      <!-- Modal Body -->
      <div class="modal-body sodetail text-center">
        <!-- Dynamic Content Will Load Here -->
      </div>

      <!-- Action Links -->
	  <div class="text-end">
		<button type="button" class="btn btn-success btn-sm addrow_reissue">
		  <i class="fas fa-plus-circle"></i> Add Row
		</button>
	  </div>

      <!-- Buttons -->
      <div class="px-4 pb-4 text-center">
        <button type="button" class="btn btn-primary shadow-sm mtlisqty" id="mtlisqty">
          <i class="fa fa-cubes me-1"></i> Add Material Issue Qty
        </button>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer bg-light border-top-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fa fa-times me-1"></i> Close
        </button>
      </div>

    </div>
  </div>
</div>
	
	
	
	
</form>
</div>
</div>





@endsection
@push('scripts')

<script>
/* purpose:to check qoh empty or not empty*/
   var com_qoh = true;
    function qoh_empty(){
        $('.bulk_qoh').each(function(i,v){
            var com_qoh_qty =$('.bulk_qoh'+i).val();
            if(com_qoh_qty == 0 || com_qoh_qty == ''){
                com_qoh = false;
            }else{
                com_qoh=true;
            }
        });
    }

$(document).ready(function(){

	const $modal = $(this);
  // ----- Save button inside modal -----
  $modal.off('click', '.mtlisqty').on('click', '.mtlisqty', function () {
    const $tbody = $modal.find('tbody');
    let ok = true;
    let total = 0;

    const subinvOut = [];
    const locOut    = [];
    const qtyOut    = [];
    const batchOut  = [];

    $tbody.find('tr').each(function () {
      const $r   = $(this);
      const sub  = $r.find('.subinventory_id').val() || '';
      const bat  = $r.find('.batch_number').val()    || '';
      const loc  = $r.find('.locator_id').val()      || '';
      const qty  = parseFloat($r.find('.mtlqty').val() || '0');

      // If any row has a value, require all fields on that row
      const anyFilled = (sub || bat || loc || qty);
      if (anyFilled && (!sub || !bat || !loc || !qty)) {
        ok = false;
      }

      if (anyFilled) {
        subinvOut.push(sub);
        locOut.push(loc);
        batchOut.push(bat);
        qtyOut.push(qty);
        total += qty;
      }
    });

    if (!ok) {
      showCustomAlert('Please enter all fields', 'error');
      return;
    }

    // write back to the source row as comma strings
    $row.find('.bulk_mtl_issue_qty').val(total);
    $row.find('.bulk_locator_id').val(locOut.join(','));
    $row.find('.bulk_subinventory_id').val(subinvOut.join(','));
    $row.find('.bulk_issueqty').val(qtyOut.join(','));
    $row.find('.bulk_batchnumber').val(batchOut.join(','));

    $('#subinvdetailsModal').modal('hide');
  });


/* purpose:to show modal popup*/
	$('.subinvdetails').click(function(){
	 $('#subinvdetailsModal').modal('show');
	var index=$(this).closest('tr').index();
	$('.soindex').val(index);

});
	
	
$('#subinvdetailsModal').on('shown.bs.modal', function () {
  const $modal = $(this);

  const index   = Number($('.soindex').val() || 0);
  const $row    = $('.clone_lines_body tr').eq(index); // source row
  const product = $row.find('.bulk_product_id').val();
  const needqty = $row.find('.bulk_issue_qty').val();
  const source  = $('.source').val();

  $.get("{{ URL::to('prdsubinventorydetails') }}", {
    index, product, needqty, source
  }, function (html) {
    $modal.find('.sodetail').html(html);

    // ----- prefill from source row (comma-separated values) -----
    const subinvStr = ($row.find('.bulk_subinventory_id').val() || '').toString();
    const batchStr  = ($row.find('.bulk_batchnumber').val() || '').toString();
    const locStr    = ($row.find('.bulk_locator_id').val() || '').toString();
    const qtyStr    = ($row.find('.bulk_issueqty').val() || '').toString();

    const subinvArr = subinvStr ? subinvStr.split(',') : [];
    const batchArr  = batchStr  ? batchStr.split(',')  : [];
    const locArr    = locStr    ? locStr.split(',')    : [];
    const qtyArr    = qtyStr    ? qtyStr.split(',')    : [];

    const rowsNeeded = Math.max(subinvArr.length, batchArr.length, locArr.length, qtyArr.length);

    // Add extra modal rows if needed
    for (let k = 1; k < rowsNeeded; k++) {
      $modal.find('.add_row1').trigger('click');
    }

    // Fill modal rows (no index-suffixed classes!)
    const $tbody = $modal.find('tbody');
    for (let k = 0; k < rowsNeeded; k++) {
      const $mrow = $tbody.find('tr').eq(k);
      if (!$mrow.length) break;

      const subVal = subinvArr[k] || '';
      const batVal = batchArr[k]  || '';
      const locVal = locArr[k]    || '';
      const qtyVal = qtyArr[k]    || '';

      // set values and keep select2 in sync if used
      $mrow.find('.subinventory_id').val(subVal).trigger('change.select2');
      $mrow.find('.batch_number').val(batVal).trigger('change.select2');
      $mrow.find('.locator_id').val(locVal).trigger('change.select2');
      $mrow.find('.mtlqty').val(qtyVal);
    }
  });

  // ----- Save button inside modal -----
  $modal.off('click', '.mtlisqty').on('click', '.mtlisqty', function () {
    const $tbody = $modal.find('tbody');
    let ok = true;
    let total = 0;

    const subinvOut = [];
    const locOut    = [];
    const qtyOut    = [];
    const batchOut  = [];

    $tbody.find('tr').each(function () {
      const $r   = $(this);
      const sub  = $r.find('.subinventory_id').val() || '';
      const bat  = $r.find('.batch_number').val()    || '';
      const loc  = $r.find('.locator_id').val()      || '';
      const qty  = parseFloat($r.find('.mtlqty').val() || '0');

      // If any row has a value, require all fields on that row
      const anyFilled = (sub || bat || loc || qty);
      if (anyFilled && (!sub || !bat || !loc || !qty)) {
        ok = false;
      }

      if (anyFilled) {
        subinvOut.push(sub);
        locOut.push(loc);
        batchOut.push(bat);
        qtyOut.push(qty);
        total += qty;
      }
    });

    if (!ok) {
      showCustomAlert('Please enter all fields','error');
      return;
    }

    // write back to the source row as comma strings
    $row.find('.bulk_mtl_issue_qty').val(total);
    $row.find('.bulk_locator_id').val(locOut.join(','));
    $row.find('.bulk_subinventory_id').val(subinvOut.join(','));
    $row.find('.bulk_issueqty').val(qtyOut.join(','));
    $row.find('.bulk_batchnumber').val(batchOut.join(','));

    $('#subinvdetailsModal').modal('hide');
  });

  // ----- Per-row live validation (optional) -----
  $modal.off('change', '.mtlqty').on('change', '.mtlqty', function () {
    // Example: you could validate that subinventory+batch is chosen if qty > 0
    const $r = $(this).closest('tr');
    const qty = parseFloat($(this).val() || '0');
    if (qty > 0) {
      const sub = $r.find('.subinventory_id').val();
      const bat = $r.find('.batch_number').val();
      if (!sub || !bat) {
        showCustomAlert('Please choose Subinventory and Batch Number','error');
      }
    }
  });
});


	/* purpose:qty validation*/
		$(document).on('keypress','.mtlqty', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});

	
// Batch change inside the MODAL
$('#subinvdetailsModal').on('change', '.batch_number', function () {
  const $modal = $('#subinvdetailsModal');
  const $row   = $(this).closest('tr');
  const batch  = $(this).val();

  const product = $modal.find('.product').val();   // hidden in modal HTML
  const source  = $('.source').val();

  const $subinv = $row.find('.subinventory_id');
  const $locator = $row.find('.locator_id');
  const $qoh = $row.find('.qoh');

  if (!batch) {
    $subinv.html('<option value="">-- Select Subinventory --</option>').trigger('change.select2');
    $locator.html('<option value="">-- Select Locator --</option>').trigger('change.select2');
    $qoh.val('');
    return;
  }

  if (!product) {
    showCustomAlert('Product not found for this modal session','info');
    $subinv.html('<option value="">-- Select Subinventory --</option>').trigger('change.select2');
    $locator.html('<option value="">-- Select Locator --</option>').trigger('change.select2');
    $qoh.val('');
    return;
  }

  const url = "{{ URL::to('prdstockdata') }}/" + encodeURIComponent(product)
            + "?batch="  + encodeURIComponent(batch)
            + "&source=" + encodeURIComponent(source || '');

  $.get(url, function (resp) {
    const inv = resp && resp.inventory ? resp.inventory : '';

    if (inv) {
      const condition = 'subinventory_id in(' + inv + ')';

      if (typeof $subinv.jCombo === 'function') {
        $subinv.jCombo(
          "{{ URL::to('jcomboform?table=m_subinventory_t:subinventory_id:subinventory_name') }}"
          + "&parent=" + encodeURIComponent(condition)
          + "&order_by=subinventory_name asc",
          { selected_value: "" }
        );
      } else {
        const jurl = "{{ URL::to('jcomboform') }}"
                   + "?table=m_subinventory_t:subinventory_id:subinventory_name"
                   + "&parent=" + encodeURIComponent(condition)
                   + "&order_by=subinventory_name asc";
        $.get(jurl, function (data) {
          if (typeof data === 'string') { try { data = JSON.parse(data); } catch { data = []; } }
          if (!Array.isArray(data)) data = [];
          $subinv.html('<option value="">-- Select Subinventory --</option>');
          $.each(data, function (_, item) {
            $subinv.append(`<option value="${item.val}">${item.option_name}</option>`);
          });
          $subinv.trigger('change.select2');
        });
      }

      $locator.html('<option value="">-- Select Locator --</option>').trigger('change.select2');
      $qoh.val('');
    } else {
      $subinv.html('<option value="">-- Select Subinventory --</option>').trigger('change.select2');
      $locator.html('<option value="">-- Select Locator --</option>').trigger('change.select2');
      $qoh.val('');
    }
  });
});

	  


// Subinventory changed → load locators for THIS row and set QOH
// Subinventory change inside the MODAL → load locators + set QOH
$('#subinvdetailsModal').on('change', '.subinventory_id', function () {
  const $modal = $('#subinvdetailsModal');
  const $row   = $(this).closest('tr');

  const subid   = $(this).val();
  const product = $modal.find('.product').val();
  const batch   = $row.find('.batch_number').val();
  const source  = $('.source').val();

  const $locator = $row.find('.locator_id');
  const $qoh     = $row.find('.qoh');

  if (!subid || !product || !batch) {
    $locator.html('<option value="">-- Select Locator --</option>').trigger('change.select2');
    $qoh.val('');
    return;
  }

  const url = "{{ URL::to('prdstocksubinvdata') }}/" + encodeURIComponent(product)
            + "?batch="  + encodeURIComponent(batch)
            + "&source=" + encodeURIComponent(source || '')
            + "&subid="  + encodeURIComponent(subid);

  $.get(url, function (resp) {
    if (!resp) {
      $locator.html('<option value="">-- Select Locator --</option>').trigger('change.select2');
      $qoh.val('');
      return;
    }

    const locIds = resp.locator || '';
    const cond = 'sublocator_id in(' + locIds + ')';
    const jurl = "{{ URL::to('jcomboform') }}"
               + "?table=m_sublocators_t:sublocator_id:locator_code"
               + "&parent=" + encodeURIComponent(cond)
               + "&order_by=locator_code asc";

    $.get(jurl, function (data) {
      if (typeof data === 'string') { try { data = JSON.parse(data); } catch { data = []; } }
      if (!Array.isArray(data)) data = [];
      $locator.html('<option value="">-- Select Locator --</option>');
      $.each(data, function (_, item) {
        $locator.append(`<option value="${item.val}">${item.option_name}</option>`);
      });
      $locator.trigger('change.select2');
    });

    $qoh.val(resp.qty ?? '');
  });
});


	  
// Locator changed → refresh QOH for THIS row
// Locator change inside the MODAL → refresh QOH
$('#subinvdetailsModal').on('change', '.locator_id', function () {
  const $modal = $('#subinvdetailsModal');
  const $row   = $(this).closest('tr');

  const subloc  = $(this).val();
  const subinv  = $row.find('.subinventory_id').val();
  const product = $modal.find('.product').val();
  const batch   = $row.find('.batch_number').val();
  const source  = $('.source').val();

  const $qoh = $row.find('.qoh');

  if (!subloc || !subinv || !product || !batch) {
    $qoh.val('');
    return;
  }

  const url = "{{ URL::to('prdstocksublocdata') }}/" + encodeURIComponent(product)
            + "?batch="   + encodeURIComponent(batch)
            + "&source="  + encodeURIComponent(source || '')
            + "&subid="   + encodeURIComponent(subinv)
            + "&sublocid="+ encodeURIComponent(subloc);

  $.get(url, function (resp) {
    $qoh.val(resp && resp.qty ? resp.qty : '');
  });
});

	  
   

    /*  purpose: based on product uom,qoh,subinventory,locator*/
$(document).on('change', '.bulk_product_id', function (event) {

    var product_id = $(this).val();
    var $row = $(this).closest('tr');   // current row
    var index = $row.index();           // row index

    if (product_id !== '') {

        // Check duplicate product
        var pdtcount = pdtcheck(product_id, index);

        if (pdtcount <= 0) {

            var url = "{{ URL::to('prduom') }}/" + product_id;

            $.get(url, function (data) {

                // Set values in SAME ROW only
                $row.find('.bulk_uom_code_id').val(data.uomcode).trigger('change');
                $row.find('.bulk_qoh').val(data.qoh);
                $row.find('.bulk_subinventory_id').val(data.sub);
                $row.find('.bulk_locator_id').val(data.subloc).trigger('change');

            });

        } else {

            // Duplicate Product
            var message = 'Product Already Selected';
            showCustomAlert(message, 'warning');

            // Reset current row product
            $(this).val('').trigger('change');

            event.preventDefault();
        }
    }
});

	/* purpose:qty validation*/
		$(document).on('keypress','.bulk_issue_qty', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});

	
	/* purpose: to validate component qty*/
$(document).on('keyup','.bulk_issue_qty',function()	{
	var index = $(this).closest('tr').index();
var qty=$(this).val();
var qoh=$('.bulk_qoh'+index).val();
	
if(Number(qty) > Number(qoh)){
showCustomAlert("Issue qty is More Than Qoh ",'info');	
	$('.bulk_issue_qty'+index).val("");
}
});


/* purpose: to save function*/
    $('#savestatus').val('');
    $(document).on('click','.saveform',function()
    {
        
        var btnval		= $(this).val();
        if(btnval == 'APPLYCHANGES')
         var savestatus = 'APPLY CHANGES';
        else if(btnval == 'SAVE' || btnval == 'SAVENEW')
            var savestatus = 'SAVE';

        $('#savestatus').val(savestatus);

        var url		= "{{ URL::to('materialreissuesave') }}";
        var red_url		= "{{ URL::to('materialissueagainstjc') }}";
        
        var formdata	= $('#materialreissues').serialize();
        var form = $('#materialreissues');
        form.parsley().validate();
        qoh_empty();
        if(com_qoh == true){
           	form.parsley().destroy();				
               
                var form = $('#materialreissues');
				        qtyrequired();
                form.parsley().validate();
                 if (form.parsley().isValid())
                {
                  			var $btn = $(this);            
                        $btn.prop('disabled', true);
                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     =     data.message;
                        var id          = data.id;
                        if(btnval !='SAVE' && btnval !='DRAFT')
                        {

                            showCustomAlert(msg,status);
                            setTimeout(function(){
                            window.location.href=red_url;
                            }, 1500);
                        }
                        else
                        {
                            showCustomAlert(msg,status);
                            setTimeout(function(){
                            window.location.href=red_url;
                            }, 1500);
                        }
                    });
                }            
           
        }else{

            showCustomAlert("component qoh can't empty","warning");
        }


    });

	});

	
/* purpose:qty validation for required*/
function qtyrequired()
        {
         $(".bulk_mtl_issue_qty").each(function(index)
         {
          var req=$(this).val();
            if(req=='')
            {
                $(".bulk_mtl_issue_qty"+index).val('');
                
            }});
        }

// Add Row
$(document).on('click', '.add-row', function () {

    const $lastRow = $('.clone_lines_body tr:last');
    const $newRow = $lastRow.clone(false, false); 

    // Clear inputs & selects
    $newRow.find('input').val('');
    $newRow.find('select').val('').trigger('change');

    // FIX: enable dropdown clicking
    $newRow.find('.pdtdiv').css('pointer-events', 'auto');

    // Remove previous Select2 instances
    $newRow.find('select.select2').each(function () {
        if ($(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
        $(this).removeAttr('data-select2-id');
        $(this).next('.select2').remove();
    });

    // Append row
    $('.clone_lines_body').append($newRow);

    // Reinitialize only in new row
    $newRow.find('select.select2').select2({ width: '100%' });

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