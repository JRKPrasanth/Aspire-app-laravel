@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Dispatch</h3>
  @include('layouts.breadcrumb')
  <?php error_reporting(0); ?>
<style>
.select2-container--open { z-index: 200000 !important; }
</style>	

  <form method="post" action="" id="dispatch" data-parsley-validate>
    {{ csrf_field() }}


    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-body card-block headerdiv1">

        <div class="row g-4">

          <!-- Dispatch Number -->
          <div class="col-md-4">
            <label class="form-label">Dispatch Number</label>
            <input type="hidden" class="form-control dispatch_source_id" id="dispatch_source_id" name="dispatch_source_id"
              value="">
            <input type="hidden" class="form-control so_dispatch_hdr_id" id="so_dispatch_hdr_id" name="so_dispatch_hdr_id"
              value="{{ $so_dispatch_hdr_id }}">
            <input type="text" id="dispatch_number" name="dispatch_number" class="form-control dispatch_number"
              value="{{ $dispatch_number }}" readonly>
          </div>

          <!-- Dispatch Source -->
          <div class="col-md-4 none">
            <label class="form-label">Dispatch Source</label>
            <select name="dispatch_source" id="dispatch_source" class="form-select select2 dispatch_source">
              <option value="">-- Select --</option>
              <option value="SALES ORDER" {{ $dispatch_source == "SALES ORDER" ? 'selected' : '' }}>SALES ORDER</option>
              <option value="PICK ORDER" {{ $dispatch_source == "PICK ORDER" ? 'selected' : '' }}>PICK ORDER</option>
              <option value="INVOICE" {{ $dispatch_source == "INVOICE" ? 'selected' : '' }}>INVOICE</option>
              <option value="MANUAL" {{ $dispatch_source == "MANUAL" ? 'selected' : '' }}>MANUAL</option>
              <option value="DISPATCH" {{ $dispatch_source == "DISPATCH" ? 'selected' : '' }}>DISPATCH</option>
              <option value="SELF DISPATCH" {{ $dispatch_source == "SELF DISPATCH" ? 'selected' : '' }}>SELF DISPATCH
              </option>
              <option value="REPLACEMENT" {{ $dispatch_source == "REPLACEMENT" ? 'selected' : '' }}>REPLACEMENT</option>
            </select>
          </div>

          <!-- Dispatch Date -->
          <div class="col-md-4">
            <label class="form-label">Dispatch Date</label>
            <input type="text" id="dispatch_date" name="dispatch_date" class="form-control dispatch_date"
              value="{{ $dispatch_date }}">
          </div>

          <!-- Reference No -->
          <div class="col-md-4">
            <label class="form-label">Reference No</label>
            <input type="text" name="reference_no" id="reference_no" value="{{ $reference_no }}"
              class="form-control reference_no" readonly>
            <input type="hidden" name="reference_source_id" id="reference_source_id" value="{{ $reference_source_id }}">
            <input type="hidden" class="ar_sales_hdr_id" name="ar_sales_hdr_id" id="ar_sales_hdr_id"
              value="{{ $ar_sales_hdr_id }}">
          </div>

          <!-- No of Box -->
          <div class="col-md-4">
            <label class="form-label text-danger">* No of Box</label>
            <input type="text" name="packaging_qty" id="packaging_qty" value="{{ $packaging_qty }}"
              class="form-control packaging_qty" required readonly>
          </div>

          <!-- Customer -->
          <div class="col-md-4 cusdiv">
            <label class="form-label"><span class="" style="color:red;">*</span> Customer</label>
            <div class="input-group">
              <select name="ship_to_customer_id" class="form-select select2 ship_to_customer_id">
                {!! $ship_to_customer_id !!}
              </select>
            </div>
          </div>

          <!-- Freight Carrier -->
          <div class="col-md-4">
            <label class="form-label text-danger">* Freight Carrier</label>
            <div class="input-group">
              <select name="freight_carrier_id" class="form-select select2 freight_carrier_id" required>
                {!! $freight_carrier_id !!}
              </select>
            </div>
          </div>

          <!-- Dispatch Status -->
          <div class="col-md-4 none">
            <label class="form-label">Dispatch Status</label>
            <select name="dispatch_status" class="form-select select2 dispatch_status" required>
              <option value="OPEN" {{ $dispatch_status == "OPEN" ? 'selected' : '' }}>OPEN</option>
              <option value="DISPATCH" {{ $dispatch_status == "DISPATCH" ? 'selected' : '' }}>DISPATCH</option>
              <option value="SHIPPED" {{ $dispatch_status == "SHIPPED" ? 'selected' : '' }}>SHIPPED</option>
              <option value="DRAFT" {{ $dispatch_status == "DRAFT" ? 'selected' : '' }}>DRAFT</option>
            </select>
          </div>

          <!-- Pack Weight -->
          <div class="col-md-4">
            <label class="form-label text-danger">* Pack Weight</label>
            <input type="text" name="pack_weight" id="pack_weight" value="{{ $pack_weight }}"
              class="form-control pack_weight" required>
          </div>

          <!-- Total Qty -->
          <div class="col-md-4">
            <label class="form-label">Total Qty</label>
            <input type="text" name="total_qty" id="total_qty" value="{{ $total_qty }}" class="form-control total_qty"
              readonly>
          </div>

					<div class="col-md-4 form-group row empdiv mt-4">
					<label class="form-control-label" for="employee_id"><span class="" style="color:red;">*</span>Employee</label>
					<div class="rdonlydiv">
					<select name='employee_id'  class='form-control employee_id select2' id="employee_id">
					{!! $employee_id !!}
					</select>
					</div>
					</div>


          <!-- Pricelist -->
          <div class="col-md-4 none">
            <label class="form-label text-danger">* Pricelist</label>
            <select name="pricelist_id" class="form-select select2 pricelist_id">
              {!! $pricelist_id !!}
            </select>
          </div>

          <!-- Location -->
          <div class="col-md-4 none">
            <label class="form-label">Location</label>
            <select name="location_id" class="form-select select2 location_id">
              <option value="1">Chennai</option>
            </select>
          </div>

          <!-- Deliver To Location -->
          <div class="col-md-8">
            <label class="form-label">Deliver To Location</label>
            <textarea name="deliver_to_location_txt" id="deliver_to_location_txt"
              class="form-control deliver_to_location_txt" rows="3" readonly>{{ $deliver_to_location_txt }}</textarea>
            <input type="hidden" name="deliver_to_location" value="{{ $deliver_to_location }}"
              class="deliver_to_location">
            <button type="button" class="btn btn-sm btn-success mt-2">
              <i class="fa fa-address-book"></i> Change Address
            </button>
          </div>

        </div>


        <!-- Additional Details Section -->
        <div class="card shadow-sm">
          <div class="card-header bg-secondary text-white fw-bold">
            Additional Details
          </div>
          <div class="card-body">
            <div class="row g-4">
              <!-- Example: Prepare Date -->
              <div class="col-md-4 none">
                <label class="form-label">Prepare Date</label>
                <input type="text" id="prepare_date" name="prepare_date" class="form-control prepare_date"
                  value="{{ $prepare_date }}" readonly>
              </div>

              <!-- Preparer Name -->
              <div class="col-md-4 none">
                <label class="form-label">Preparer Name</label>
                <select name="preparer_id" class="form-select select2 preparer_id">
                  {!! $preparer_id !!}
                </select>
              </div>

              <!-- Remarks -->
              <div class="col-md-4">
                <label class="form-label">Remarks</label>
                <input type="text" name="remarks" id="remarks" class="form-control remarks" value="">
              </div>
            </div>
          </div>
        </div>


        <!--*******************-Linedata ************************-->

        <div class="row mt-4">
          <div class="col-md-12">

            <div id="preview-area" class="table-responsive">
              <table class="table table-bordered clone_table" style="width: 150%;">
                <thead class="table-light">
                  <tr>
                    <th>Line No</th>
                    <th class="pdtdiv">Product</th>
                    <th></th>
                    <th>Customer Part No</th>
                    <th>Uom Code </th>
                    <th class="dis">So Qty</th>
                    <th>Dispatch Qty</th>
                    <th class="dis">So Qty Details</th>
                    <th>Issue QOH</th>
                    <?php if ($dispatch_source != "SELF DISPATCH") {?>
                    <?php  if ($dispatch_source == "SALES ORDER" || $dispatch_source == "DISPATCH" || $dispatch_source == "REPLACEMENT") { ?>
                    <th>Dispatched Qty</th>
                    <th>Free Qty</th>
                    <?php  } elseif ($dispatch_source == "PICK ORDER") { ?>
                    <th>Picked Qty</th>
                    <?php  } else { ?>
                    <th>Invoiced Qty</th>
                    <?php    if ($pageurl != "replacement") {?>
                    <th class="replacement">Dispatched Qty</th>
                    <?php    } ?>
                    <?php  }
  } ?>
                    <th class="invoice">QOH</th>
                    <th>Comments</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="clone_lines_body">
                  <?php if (count($linedata) >= 1) { ?>
                  @foreach($linedata as $key => $value)
                    <tr class="rcopy clone clonedInput">
                      <td>
                        <input type="hidden" name="bulk_so_dispatch_line_id[]"
                          class="form-control input-sm bulk_so_dispatch_line_id" value="{{$value->so_dispatch_line_id}}">
                        <input type="hidden" name="reference_hdr_id[]" class="form-control input-sm reference_hdr_id"
                          value="{{ $value->reference_hdr_id}}">
                        <input type="hidden" name="reference_line_id[]" class="form-control input-sm reference_line_id"
                          value="{{ $value->reference_line_id}}">
                        <input type="hidden" name="ar_sales_line_id[]" class="form-control input-sm ar_sales_line_id"
                          value="{{ $value->ar_sales_line_id}}">
                        <input type="hidden" name="source_code[]" class="form-control input-sm source_code"
                          value="{{ $value->source_code }}">
                        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no"
                          value="{{ $key + 1 }}" readonly="readonly">
                      </td>
                      <td class="pdtdiv styleproduct">
                        <select name="bulk_product_id[]" id="bulk_product_id"
                          class="select2 bulk_product_id form-control parsley-validated" required="required">
                          {!! $value->product_id !!}
                        </select>
                      </td>
                      <td>
                        <i class="fa fa-search productsearch"></i>
                      </td>
                      <td class="pdtdiv" style="pointer-events:none;">
                        <select name="bulk_part_no[]" id="bulk_part_no" class="bulk_part_no select2 ">
                          {!! $value->part_no !!}
                        </select>
                      </td>
                      <td class="uomread" style="pointer-events:none;">
                        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                          class="select2 form-control form-control bulk_uom_code_id">
                          {!! $value->uomcode_id !!}
                        </select>
                      </td>
                      <td class="dis text-center">
                        <input type="text" name="bulk_so_qty[]" class="form-control input-sm bulk_so_qty input_qty_width"
                          value="{{ $value->so_qty }}" readonly="readonly">
                      </td>
                      <td>
                        <input type="text" name="bulk_dispatch_qty[]"
                          class="form-control input-sm bulk_dispatch_qty input_qty_width" value="{{ $value->dispatch_qty }}"
                          required="required" readonly required>
                      </td>
                      <td class="dis text-center">
                        <a class="dispatchqty" title="Add Dispatch Qty"> <i class="fa fa-plus"></i></a>
                        <input type="hidden" name="sowise_qty[]" class="sowise_qty">
                        <input type="hidden" name="soorder_id[]" class="soorder_id">
                        <input type="hidden" name="soorder_lineid[]" class="soorder_lineid">
                        <input type="hidden" name="sototqty[]" class="sototqty">
                        <input type="hidden" name="sototfree_qty[]" class="sototfree_qty">
                        <input type="hidden" name="sofree_qty[]" class="sofree_qty">

                        <input type="hidden" name="soorder_qty[]" class="soorder_qty">
                      </td>
                      <td class="text-center">
                        <a class="salesdetails" title="Add Issue Qty"> <i class="fa fa-plus"></i></a>
                        <input type="hidden" name="p_s_dispatched_qty_id[]" class="p_s_dispatched_qty_id"
                          value="{{$value->s_dispatched_qty_id}}">
                          
                        <input type="hidden" name="p_line_no[]" class="p_line_no" value="{{$value->p_line_no}}">
                        <input type="hidden" name="free_val[]" class="free_val" value="{{$value->free_val}}">

                        <input type="hidden" name="p_box_no[]" class="p_box_no" value="{{$value->p_box_no}}">
                        <input type="hidden" name="" class="p_box_no_edit" value="{{$value->p_box_no}}">
                        <input type="hidden" name="p_kit_pack_no[]" class="p_kit_pack_no" value="{{$value->p_kit_pack_no}}">
                        <input type="hidden" name="p_batch_no[]" class="p_batch_no" value="{{$value->p_batch_no}}">
                        <input type="hidden" name="p_subinventory_id[]" class="p_subinventory_id"
                          value="{{$value->p_subinventory_id}}">
                        <input type="hidden" name="p_sublocator_id[]" class="p_sublocator_id"
                          value="{{$value->p_sublocator_id}}">
                        <input type="hidden" name="p_qoh[]" class="p_qoh" value="">
                        <input type="hidden" name="p_issue_qoh[]" class="p_issue_qoh" value="{{$value->p_issue_qoh}}">
                        <input type="hidden" name="p_manu_date[]" class="p_manu_date" value="{{$value->p_manu_date}}">
                        <input type="hidden" name="p_exp_date[]" class="p_exp_date" value="{{$value->p_exp_date}}">
                        <input type="hidden" class="p_product_id" value="">
                      </td>
                      <td>
                        <input type="text" name="bulk_dispatched_qty[]"
                          class="form-control input-sm bulk_dispatched_qty input_qty_width"
                          value="<?php    echo number_format($value->dispatched_qty, \Session::get('decimal'), '.', ''); ?>"
                          readonly>
                      </td>
                      <td>
                        <input type="text" name="bulk_free_qty[]"
                          class="form-control input-sm bulk_free_qty input_qty_width" readonly
                          value="<?php    echo number_format($value->free_qty, \Session::get('decimal'), '.', ''); ?>">
                      </td>

                      <?php    if ($dispatch_source == "INVOICE") {  ?>
                      <td>
                        <input type="text" class="form-control input-sm  input_qty_width"
                          value="{{$value->dispatcheds_qty}}" readonly>
                      </td>

                      <?php    } ?>

                      <td class="invoice">
                        <input type="text" name="bulk_qoh[]" class="form-control input-sm bulk_qoh input_qty_width" readonly
                          value="{{ $value->qoh_qty }}">
                      </td>
                      <td>
                        <input type="text" name="bulk_comments[]" row="5"
                          class="form-control input-sm bulk_comments input_qty_width" value="{{ $value->comments }}">
                      </td>
                      <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                          <i class="fas fa-minus-circle"></i>
                        </button>
                      </td>
                    </tr>
                  @endforeach
                  <?php }
  if (count($linedata) < 1) {  ?>
                  <tr class="rcopy clone clonedInput">
                    <td>
                      <input type="hidden" name="bulk_so_dispatch_line_id[]"
                        class="form-control input-sm bulk_so_dispatch_line_id" value="">
                      <input type="hidden" name="reference_hdr_id[]" class="form-control input-sm reference_hdr_id"
                        value="">
                      <input type="hidden" name="reference_line_id[]" class="form-control reference_line_id" value="">
                      <input type="hidden" name="source_code[]" class="form-control input-sm source_code" value="">
                      <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1"
                        readonly="readonly">
                    </td>
                    <td class="pdtdiv">
                      <select name="bulk_product_id[]" id="bulk_product_id"
                        class="bulk_product_id select2 form-control parsley-validated" required="required">
                        {!! $product_id !!}
                      </select>
                    </td>
                    <td>
                      <i class="fa fa-search productsearch"></i>
                    </td>
                    <td class="pdtdiv" style="pointer-events:none;">
                      <select name="bulk_part_no[]" id="bulk_part_no"
                        class="bulk_part_no select2">{!! $part_no !!}</select>
                    </td>
                    <td class="uomread">
                      <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                        class="select2 form-control bulk_uom_code_id">
                        {!! $uomcode_id !!}
                      </select>
                    </td>
                    <td class="dis text-center">
                      <input type="text" name="bulk_so_qty[]" class="form-control input-sm bulk_so_qty input_qty_width"
                        value="" readonly="readonly">
                    </td>
                    <td>
                      <input type="text" name="bulk_dispatch_qty[]"
                        class="form-control input-sm bulk_dispatch_qty input_qty_width" value="" readonly required>
                    </td>
                    <td class="dis text-center">
                      <a class="dispatchqty" title="Add Dispatch Qty"> <i class="fa fa-plus"></i></a>
                      <input type="hidden" name="sowise_qty[]" class="sowise_qty">
                      <input type="hidden" name="soorder_id[]" class="soorder_id">
                      <input type="hidden" name="soorder_lineid[]" class="soorder_lineid">
                      <input type="hidden" name="sototqty[]" class="sototqty">
                      <input type="hidden" name="sototfree_qty[]" class="sototfree_qty">
                      <input type="hidden" name="sofree_qty[]" class="sofree_qty">

                      <input type="hidden" name="soorder_qty[]" class="soorder_qty">
                    </td>
                  <td class="text-center">
                      <a class="salesdetails" title="Add Issue Qty"> <i class="fa fa-plus"></i></a>
                      <input type="hidden" name="p_s_dispatched_qty_id[]" class="p_s_dispatched_qty_id" value="">
                      <input type="hidden" name="p_line_no[]" class="p_line_no" value="">
                      <input type="hidden" name="free_val[]" class="free_val" value="">

                      <input type="hidden" name="p_box_no[]" class="p_box_no" value="">
                      <input type="hidden" name="" class="p_box_no_edit" value="">
                      <input type="hidden" name="p_kit_pack_no[]" class="p_kit_pack_no" value="">
                      <input type="hidden" name="p_batch_no[]" class="p_batch_no" value="">
                      <input type="hidden" name="p_subinventory_id[]" class="p_subinventory_id" value="">
                      <input type="hidden" name="p_sublocator_id[]" class="p_sublocator_id" value="">
                      <input type="hidden" name="p_qoh[]" class="p_qoh" value="">
                      <input type="hidden" name="p_issue_qoh[]" class="p_issue_qoh" value="">
                      <input type="hidden" name="p_manu_date[]" class="p_manu_date" value="">
                      <input type="hidden" name="p_exp_date[]" class="p_exp_date" value="">
                      <input type="hidden" class="p_product_id" value="">
                    </td>
                    <td>
                      <input type="text" name="bulk_dispatched_qty[]"
                        class="form-control input-sm bulk_dispatched_qty input_qty_width" value="" readonly>
                    </td>
                    <td>
                      <input type="text" name="bulk_free_qty[]"
                        class="form-control input-sm bulk_free_qty input_qty_width" value="">
                    </td>
                    <td class="invoice">
                      <input type="text" name="bulk_qoh[]" class="form-control input-sm bulk_qoh input_qty_width "
                        readonly value="">
                    </td>
                    <td>
                      <input type="text" name="bulk_comments[]"
                        class="form-control input-sm bulk_comments input_qty_width" row="5" value="">
                    </td>

                    <td class="text-center">
                      <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="fas fa-minus-circle"></i>
                      </button>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>

              <?php if ($pageurl == "dispatch") { ?>

              <div class="text-end">
                <button type="button" class="btn btn-success btn-sm add-row">
                  <i class="fas fa-plus-circle"></i> Add Row
                </button>
              </div>

              <?php } ?>

              <input type="hidden" name="enable-masterdetail" value="true">
            </div>
          </div>
        </div>
        <!--******************Linedata End *****************************-->


        <div class="row mt-4 mb-3">
          <div class="col-lg-12 col-md-12">
            <div class="form-group text-center">
              <button type="button" class="btn btn-secondary saveform px-4 me-2" value="DRAFT">Draft</button>
              <button type="button" class="btn btn-success px-4 me-2 saveform" value="save">Save</button>
              <a href="{{ url($redirecturl) }}" class='btn btn-danger px-4'>Cancel</a>
            </div>
          </div>
        </div>

      </div>
    </div>

  </form>

  <input type="hidden" class="pdtindex" value="" />



  <!-- popups  -->

  <!-- Customer Modal -->
  <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content shadow-lg">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title fw-bold" id="customerModalLabel">Customer Details</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table id="customergrid" class="table table-bordered table-striped w-100"></table>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Product Modal -->
  <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content shadow-lg">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title fw-bold" id="productModalLabel">Product Details</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table id="productgrid" class="table table-bordered table-striped w-100"></table>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Pick Order Details Modal -->
  <div class="modal fade" id="pickorderdetailsModal" tabindex="-1" aria-labelledby="pickorderdetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content shadow-lg">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title fw-bold" id="pickorderdetailsModalLabel">QOH Details</h5>
          <input type="hidden" class="soindex" value="">
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body sodetail">
          <!-- jqGrid or dynamic content -->
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Dispatch Qty Modal -->
  <div class="modal fade" id="dispatchqtyModal" tabindex="-1" aria-labelledby="dispatchqtyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content shadow-lg">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title fw-bold" id="dispatchqtyModalLabel">Dispatched Qty Details</h5>
          <input type="hidden" class="qtyindex" value="">
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body qtydetail">
          <!-- dynamic content -->
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>



@endsection
@push('scripts')

  <script>

    $(document).ready(function () {


      $('input').keyup(function (e) {
        if (e.which == 39) { // right arrow
          $(this).closest('td').next().find('input').focus();

        } else if (e.which == 37) { // left arrow
          $(this).closest('td').prev().find('input').focus();

        } else if (e.which == 40) { // down arrow
          $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();

        } else if (e.which == 38) { // up arrow
          $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
        }
      });

      <?php  if ($inv_type == "SAMPLE") { ?>
      $('.ship_to_customer_id').attr('required', false);employee_id
      $('.cusdiv').css('pointer-events', 'none');
      $('.employee_id').attr('required', true);
      $('.empdiv').css('pointer-events', 'auto');
      <?php } else { ?>
      $('.ship_to_customer_id').attr('required', true);
      $('.cusdiv').css('pointer-events', 'auto');
      $('.employee_id').attr('required', false);
      $('.empdiv').css('pointer-events', 'none');
      <?php } ?>


      <?php if (($dispatch_source == "SALES ORDER") || ($dispatch_source == "PICK ORDER") || ($dispatch_source == "INVOICE") || ($dispatch_source == "REPLACEMENT")) { ?>
      $('.shipto,.jcr_freight_carrier_id,.productsearch,.customersearch').css('display', 'none');
      $('.styleproduct').css('pointer-events', 'none');
      <?php }
  if ($dispatch_source != "") {   ?>
      $('.invoice').hide();
      <?php } ?>


      var dis = $('.dispatch_source').val();
      if (dis == "DISPATCH") {
        $('.bulk_product_id,.bulk_uom_code_id,.customer_id').css('pointer-events', 'none');
        $('.bulk_dispatched_qty').attr('readonly', true);
      } else {
        $('.bulk_product_id,.bulk_uom_code_id,.customer_id').css('pointer-events', 'auto');
      }

      $('.uomread').css('pointer-events', 'none');

      <?php  if ($pageurl == 'dispatch') { ?>
      $(document).on('change', '.ship_to_customer_id', function () {
        var customer_id = $('.ship_to_customer_id').val();
        var url = "{{ URL::to('sodispatchaddress') }}/" + customer_id + "?pid=0&condition=dispatch";;
        if (customer_id != '') {
          $.get(url, function (data) {

            if (data[1] != '') {
              var result_data = data[0].split('~');
              $('.deliver_to_location_txt').val(result_data[0]);
              $('.deliver_to_location').val(result_data[1]);
            }
            else {
              $('.deliver_to_location_txt').val('');
              $('.deliver_to_location').val('');
              notyMsg("info", "There is noDelivery Location for this customer...");
            }
            $('.pricelist_id').select2('val', [data.pricelist_id]);
            $('.bulk_product_id').html(data.productid);
          });
        }
        else {
          $('.deliver_to_location_txt').val('');
          $('.deliver_to_location').val('');
        }

      });

      <?php } else { ?>
      $('.cusedit').css('pointer-events', 'none');
      <?php } ?>


      var url = "<?php echo $pageurl; ?>";
      var soconvert_status = "<?php echo $soconvert_status; ?>";

      if (url == "dispatch" || url == "invoice" || url == "replacement") {
        if (soconvert_status == "SALES ORDER")
          $('.dis').show();
        else
          $('.dis').hide();
      } else {
        $('.dis').show();
      }


    });


    $(document).on('click', '.saveform', function (e) {

      var btnval = $(this).val();
      var url = "{{ url('dispatchsave') }}";
      var red_url = "{{ url('dispatch') }}";
      var create_url = "{{ url('dispatchcreate') }}/0";

      if (btnval == 'save') {
        $(".dispatch_status").val(["DISPATCH"]);
        var form = $('#dispatch');
        form.parsley().validate();
        if (form.parsley().isValid()) {

          var formdata = $('#dispatch').serialize();
          $.post(url, formdata, function (data) {
            var status = data.status;
            var msg = data.message;
            showCustomAlert(msg, status);
            setTimeout(function () {
              window.location.href = red_url;
            }, 1500);
          });
        }
      } else {

        $(".dispatch_status").val(["DRAFT"]);
        var formdata = $('#dispatch').serialize();
        var customer = $('.ship_to_customer_id').val();

        if (customer != "") {
          $.post(url, formdata, function (data) {
            var status = data.status;
            var msg = data.message;
            var edit_url = "{{ url('dispatchcreate') }}/" + data.id;
            showCustomAlert(msg, status);
            window.location.href = edit_url;
          });

        } else {

          showCustomAlert("Please Choose Customer...", "info");

        }
      }
    });


    <?php  if ($pageurl == 'dispatch') { ?>
    $(document).on('change', '.ship_to_customer_id', function () {
      var customer_id = $('.ship_to_customer_id').val();
      var url = "{{ URL::to('sodispatchaddress') }}/" + customer_id + "?pid=0&condition=dispatch";;
      if (customer_id != '') {
        $.get(url, function (data) {

          if (data[1] != '') {
            var result_data = data[0].split('~');
            $('.deliver_to_location_txt').val(result_data[0]);
            $('.deliver_to_location').val(result_data[1]);
          }
          else {
            $('.deliver_to_location_txt').val('');
            $('.deliver_to_location').val('');
            notyMsg("info", "There is noDelivery Location for this customer...");
          }
          $('.pricelist_id').select2('val', [data.pricelist_id]);
          $('.bulk_product_id').html(data.productid);
        });
      }
      else {
        $('.deliver_to_location_txt').val('');
        $('.deliver_to_location').val('');
      }

    });

    <?php } else { ?>
    $('.cusedit').css('pointer-events', 'none');
    <?php } ?>


    // When product changes on any row
    $(document).on('change', '.bulk_product_id', function (e) {
      var $row = $(this).closest('tr');         // scope to the current row
      var product_id = $(this).val();
      var c_id = $('.ship_to_customer_id').val();
      var index = $row.index();                  // still available if pdtcheck needs it

      // Require customer first
      if (!c_id) {
        $(this).val('').trigger('change.select2');
        e.preventDefault();
        showCustomAlert("Please select customer", "info");
        return;
      }

      if (!product_id) {
        // cleared
        $row.find('.bulk_uom_code_id').val(null).trigger('change');
        $row.find('.bulk_qoh').val('');
        $row.find('.bulk_part_no').val(null).trigger('change');
        $row.find('.p_product_id').val('');
        return;
      }

      // If you have a duplicate-check function, use it
      if (typeof pdtcheck === 'function') {
        var pdtcount = pdtcheck(product_id, index);
        if (pdtcount > 0) {
          showCustomAlert('Product Already Selected', 'info');
          $(this).val('').trigger('change.select2');
          e.preventDefault();
          return;
        }
      }

      // Fetch row-specific data
      var url = "{{ URL::to('dispatchproductqoh') }}/" + product_id + "?c_id=" + encodeURIComponent(c_id);
      $.get(url, function (data) {
        // Update ONLY within this row
        $row.find('.bulk_uom_code_id').val(data['uomcode']).trigger('change');
        $row.find('.bulk_qoh').val(data['qoh_qty']);
        $row.find('.bulk_part_no').val(data['manufactpartno']).trigger('change');
        $row.find('.p_product_id').val(product_id);
      });
    });

    // Row-aware handler for the "Add Issue Qty" / salesdetails button
// ---- Dispatch Details Button Click ----
$(document).on('click', '.salesdetails', function (e) {
    e.preventDefault();

    const $row = $(this).closest('tr');
    const index = Number($row.index());
    const dispatch_status = $('.dispatch_status').val();
    const source = $('.dispatch_source').val();
    const pageurl = "<?php echo $pageurl; ?>";
    const soconvert_status = "<?php echo $soconvert_status; ?>";
    const sample = "<?php echo $sample; ?>";

    const product_id = $row.find('.bulk_product_id').val();
    if (!product_id) { showCustomAlert('Please Select Product', 'info'); return; }
    $row.find('.p_product_id').val(product_id);

    const sototqty = Number($row.find('.sototqty').val() || 0);
    const solnid = $row.find('.bulk_so_pickrelease_line_id').val();
    const displayqty = $row.find('.sototqty').val();

    // Determine qty and freeqty
    let qty = '0', freeqty = '0';
    switch (source) {
        case 'INVOICE':
            qty = $row.find('.bulk_dispatched_qty').val() || '0';
            freeqty = $row.find('.bulk_free_qty').val() || '0';
            break;
        case 'SALES ORDER':
        case 'REPLACEMENT':
            qty = $row.find('.bulk_so_qty').val() || '0';
            freeqty = $row.find('.bulk_free_qty').val() || '0';
            break;
        default:
            qty = $row.find('.bulk_dispatched_qty').val() || '0';
            freeqty = $row.find('.bulk_free_qty').val() || '0';
    }

    $('.soindex').val(index); // store index for modal

    // Fetch modal HTML from server
    const displayqtyid = $row.find('.p_s_dispatched_qty_id').val();
    $.get("{{URL::to('dispatchlines')}}/" + product_id + "?qty=" + qty + "&dispid=" + displayqtyid + "&free_qty=" + freeqty, function (data) {
        $('.sodetail').html(data);
        $('.divhide').html('Dispatch Qty: ' + displayqty + "<p>Free Qty : " + freeqty + "</p>");
        $('.pre_pro').val(product_id);
        $('.subread').css('pointer-events', 'none');

        $('#pickorderdetailsModal').modal('show');

        // Restore previous values if exist
        const prevData = {
            issue: $row.find('.p_issue_qoh').val(),
            box: $row.find('.p_box_no').val(),
            qoh: $row.find('.p_qoh').val(),
            manu: $row.find('.p_manu_date').val(),
            exp: $row.find('.p_exp_date').val(),
            kit: $row.find('.p_kit_pack_no').val(),
            subinv: $row.find('.p_subinventory_id').val(),
            subloc: $row.find('.p_sublocator_id').val(),
            batch: $row.find('.p_batch_no').val()
        };

        if (prevData.issue) restoreIssueDetails(prevData);
    });
});

// ---- Restore Modal Data ----
function restoreIssueDetails(s) {
    mapCSV('.issue_qoh', s.issue);
    mapCSV('.box_no', s.box);
    mapCSV('.qoh', s.qoh);
    mapCSV('.manufacture_date', s.manu);
    mapCSV('.expiry_date', s.exp);
    mapTilde('.kit_pack_no', s.kit);
    mapCSV('.subinventory_id', s.subinv, true);
    mapCSV('.sublocator_id', s.subloc, true);

    if (s.batch) {
        const arr = s.batch.split(',');
        $('.batch_no').each(function (i) {
            if (arr[i] && arr[i] !== '0') {
                const cond = " batch_number in('" + arr[i] + "')";
                $(this).jCombo(
                    "{{ URL::to('jcomboformcomp?table=i_qoh_detail_t:batch_number:batch_number') }}&order_by=batch_number asc&parent=" + cond,
                    { selected_value: arr[i] }
                );
            }
        });
    }
}

function mapCSV(sel, data, trigger = false) {
    if (!data) return;
    const arr = data.split(',');
    $(sel).each(function (i) {
        if (arr[i] && arr[i] !== '0') {
            $(this).val(arr[i]);
            if (trigger) $(this).trigger('change');
        }
    });
}

function mapTilde(sel, data) {
    if (!data) return;
    const arr = data.split('~');
    $(sel).each(function (i) { if (arr[i] && arr[i] !== '0') $(this).val(arr[i]); });
}

// ---- Add QOH Button ----
$(document).on('click', '.addqohqty', function () {
    const index = parseInt($('.soindex').val(), 10);
    if (Number.isNaN(index)) {
        showCustomAlert('Row index missing. Please reopen the line.', 'info');
        return;
    }

    const num = v => {
        const s = (v ?? '').toString().replace(/,/g, '').trim();
        const n = parseFloat(s);
        return Number.isNaN(n) ? 0 : n;
    };

    const sample = "<?php echo $sample; ?>";
    const pageurl = "<?php echo $pageurl; ?>";
    const soconvert_status = "<?php echo $soconvert_status; ?>";

    // Arrays to collect values
    const issue_qoh = [], qoh_list = [], free_type_list = [],
        box_edit_vals = [], batch_list = [], sub_list = [],
        loc_list = [], manu_dates = [], exp_dates = [],
        line_no_list = [], disp_line_ids = [], kit_pack_list = [];

    let needToKeepModalOpen = false;

    const trimTrail = (s, ch) => (s || '').replace(new RegExp(ch + '+$'), '');
    const isEmpty = v => v === undefined || v === null || v === '';

    // Expand numeric range like "1-5" -> [1,2,3,4,5]
    function expandNumericRange(str) {
        const m = String(str).match(/^\s*(-?\d+)\s*-\s*(-?\d+)\s*$/);
        if (!m) return [str.trim()];
        const start = parseInt(m[1], 10), end = parseInt(m[2], 10);
        const out = [], step = start <= end ? 1 : -1;
        for (let n = start; step > 0 ? n <= end : n >= end; n += step) out.push(String(n));
        return out;
    }

    // Expand alphanumeric range like "A01-A05" -> ["A01","A02","A03","A04","A05"]
    function expandAlphaNumRange(str) {
        const m = String(str).match(/^([A-Za-z]+)(0*\d+)-\1(0*\d+)$/);
        if (!m) return null;
        const prefix = m[1];
        const start = parseInt(m[2], 10);
        const end = parseInt(m[3], 10);
        const len = Math.max(m[2].length, m[3].length);
        const out = [], step = start <= end ? 1 : -1;
        for (let n = start; step > 0 ? n <= end : n >= end; n += step) {
            out.push(prefix + String(n).padStart(len, '0'));
        }
        return out;
    }

    // Collect issue QOH and validate
    let totalIssue = 0;
    $('.issue_qoh').each(function (rowIdx) {
        const val = num($(this).val());
        issue_qoh[rowIdx] = val;
        totalIssue += val;

        if (val > 0) {
            const boxVal = $('.box_no').eq(rowIdx).val();
            if (isEmpty(boxVal)) {
                showCustomAlert('Please enter box number', 'error');
                needToKeepModalOpen = true;
            }
            if (sample !== 'Yes') {
                const kitVal = $('.kit_pack_no').eq(rowIdx).val();
                if (isEmpty(kitVal)) {
                    showCustomAlert('Please enter kit pack number', 'error');
                    needToKeepModalOpen = true;
                }
            }
        }
    });

    // Free type
    $('.free_type').each(function (i) {
        const v = $(this).val();
        free_type_list[i] = isEmpty(v) ? '0' : v;
    });

    // QOH per modal row
    $('.qoh').each(function (i) { qoh_list[i] = num($(this).val()); });

    // Boxes
    let box_s_val = '';
    let maxBoxNumeric = null;
    $('.box_no').each(function (i) {
        const raw = ($(this).val() || '').trim();
        box_edit_vals[i] = raw;
        let expanded = expandAlphaNumRange(raw) || expandNumericRange(raw);
        expanded.forEach(x => {
            const n = num(x);
            if (!Number.isNaN(n)) maxBoxNumeric = (maxBoxNumeric === null || n > maxBoxNumeric) ? n : maxBoxNumeric;
        });
        box_s_val += expanded.join(',') + '~';
    });
    box_s_val = trimTrail(box_s_val, '~');

    // Kit pack
    let k_pack_val = '';
    $('.kit_pack_no').each(function (i) {
        const raw = ($(this).val() || '').trim();
        kit_pack_list[i] = raw;
        let expanded = expandAlphaNumRange(raw) || expandNumericRange(raw);
        k_pack_val += expanded.join(',') + '~';
    });
    k_pack_val = trimTrail(k_pack_val, '~');

    // Batch/Sub/Subloc/Dates/Lines/IDs
    $('.batch_no').each(function (i) { batch_list[i] = isEmpty($(this).val()) ? 0 : $(this).val(); });
    $('.subinventory_id').each(function (i) { sub_list[i] = isEmpty($(this).val()) ? 0 : $(this).val(); });
    $('.sublocator_id').each(function (i) { loc_list[i] = isEmpty($(this).val()) ? 0 : $(this).val(); });
    $('.dis_line_no').each(function (i) { line_no_list[i] = isEmpty($(this).val()) ? 0 : $(this).val(); });
    $('.dispatched_qty_id').each(function (i) { disp_line_ids[i] = isEmpty($(this).val()) ? 0 : $(this).val(); });
    $('.manufacture_date').each(function (i) { manu_dates[i] = isEmpty($(this).val()) ? 0 : $(this).val(); });
    $('.expiry_date').each(function (i) { exp_dates[i] = isEmpty($(this).val()) ? 0 : $(this).val(); });

    // Target dispatch = SO + Free
    const dis_qty1 = num($('.sototqty').eq(index).val());
    const sototfree_qty = num($('.sototfree_qty').eq(index).val());
    const targetDispatch = dis_qty1 + sototfree_qty;

    if ((pageurl !== 'dispatch') && (soconvert_status === 'SALES ORDER') && (num(totalIssue) !== num(targetDispatch))) {
        showCustomAlert('Dispatch qty and issue qty must be same qty', 'info');
        $('.issue_qoh').val('');
        return;
    }

    if (!needToKeepModalOpen) $('#pickorderdetailsModal').modal('hide');
    else { $('#pickorderdetailsModal').modal('show'); return; }

    // Write back into table row safely
    const $row = $('.salesdetails').eq(index).closest('tr');
    $row.find('.free_val').val(free_type_list.join(','));
    $row.find('.p_line_no').val(line_no_list.join(','));
    $row.find('.p_s_dispatched_qty_id').val(disp_line_ids.join(','));
    $row.find('.p_box_no').val(box_s_val);
    $row.find('.p_box_no_edit').val(box_edit_vals.join(','));
    $row.find('.p_kit_pack_no').val(k_pack_val);
    $row.find('.p_batch_no').val(batch_list.join(','));
    $row.find('.p_subinventory_id').val(sub_list.join(','));
    $row.find('.p_sublocator_id').val(loc_list.join(','));
    $row.find('.p_qoh').val(qoh_list.join(','));
    $row.find('.p_issue_qoh').val(issue_qoh.join(','));
    $row.find('.p_manu_date').val(manu_dates.join(','));
    $row.find('.p_exp_date').val(exp_dates.join(','));

    if (maxBoxNumeric !== null) $('.packaging_qty').val(maxBoxNumeric);

    // Invoice cap check
    const dispatched_qty_cap = num($row.find('.bulk_dispatched_qty').val() || 0);
    if ("<?php echo $redirecturl ?? ''; ?>" === "dispatchfrminvoice" && num(totalIssue) > dispatched_qty_cap) {
        showCustomAlert('Greater Than Invoice Qty', 'warning');
        $row.find('.bulk_dispatch_qty').val(0);
        return;
    }

    // Set dispatch qty
    $row.find('.bulk_dispatch_qty').val(totalIssue === 0 ? '' : num(totalIssue).toFixed({{\Session::get('decimal')}}));

    // Recompute table total
    let tableSum = 0;
    $('.bulk_dispatch_qty').each(function () {
        const n = num($(this).val());
        tableSum += n;
    });
    $('#total_qty').val(tableSum.toFixed({{\Session::get('decimal')}}));

    // Trigger change events
    $('.bulk_dispatch_qty').trigger('change');
});




    // Validate dispatch qty per row
    $(document).on('change', '.bulk_dispatch_qty', function () {
      const $row = $(this).closest('tr');

      // helpers
      const num = v => {
        const n = parseFloat(v);
        return Number.isNaN(n) ? 0 : n;
      };

      const soQty = num($row.find('.bulk_so_qty').val());          // base SO qty
      const alreadyPicked = num($row.find('.bulk_dispatched_qty').val());  // already dispatched/picked qty
      const freeQty = num($row.find('.bulk_free_qty').val());        // free qty
      const enteredQty = num($row.find('.bulk_dispatch_qty').val());    // newly entered dispatch qty

      // Total release allowed and remaining
      const releaseTotal = soQty + freeQty;
      const remaining = Math.max(0, releaseTotal - alreadyPicked);

      // If nothing has been picked yet, remaining === releaseTotal — one check is enough
      if (enteredQty > remaining) {
        showCustomAlert('Dispatch qty greater than Release Qty.', 'info');
        $row.find('.bulk_dispatch_qty').val('');
        return;
      }

    });


    $(document).on('keyup', '.issue_qty', function () {
      var iss_qty = $(this).val();
      var index = ($(this).closest('tr').index());
      var qty = $('.qty' + index).val();
      if (parseInt(iss_qty) > parseInt(qty)) {
        showCustomAlert('Issue Qty not more than qty', 'info');
        $('.issue_qty' + index).val('');
      }
    });

    $(document).on('keyup', '.issue_qoh', function () {
      var index_qoh = $(this).closest('tr').index();
      var qoh = $('.qoh' + index_qoh).val();
      var issue_qoh = $(this).val();

    })

$(document).on('click', '.qtyok', function () {

  let add = 0;
  let freeadd = 0;

  let issue = [];
  let sorder_id = [];
  let sorder_lineid = [];
  let sorder_qty = [];
  let free_qty = [];

  let hasError = false;

  $('.sales_hdr_id').each(function (i) {
    sorder_id[i] = $(this).val() || 0;
  });

  $('.sales_line_id').each(function (i) {
    sorder_lineid[i] = $(this).val() || 0;
  });

  $('.qty').each(function (i) {
    sorder_qty[i] = parseFloat($(this).val()) || 0;
  });

  $('.free_qty').each(function (i) {
    let fq = parseFloat($(this).val()) || 0;
    free_qty[i] = fq;
    freeadd += fq;
  });

  $('.dis_issue_qty').each(function (i) {

    let val = parseFloat($(this).val()) || 0;
    let qty = parseFloat($('.qty' + i).val()) || 0;
    let dis_qty = parseFloat($('.so_dis_qty' + i).val()) || 0;
    let qoh_qty = parseFloat($('.qoh_qty' + i).val()) || 0;

    let balanceQty = qty - dis_qty;

    if (val <= 0) {
      hasError = true;
      showCustomAlert('Please enter dispatch qty', 'info');
      return false;
    }

    if (val > balanceQty) {
      hasError = true;
      showCustomAlert('Issue qty should not exceed SO balance qty', 'error');
      return false;
    }

    if (val > qoh_qty) {
      hasError = true;
      showCustomAlert('Issue qty should not exceed QOH qty', 'error');
      return false;
    }

    add += val;
    issue[i] = val;
  });

  if (hasError) {
    $('#dispatchqtyModal').modal('show');
    return false;
  }
  // ✅ GET CORRECT MAIN ROW INDEX
  let index = $('#dispatchqtyModal').data('rowIndex');

  // ✅ ASSIGN VALUES TO MAIN TABLE
  $('.sowise_qty').eq(index).val(JSON.stringify(issue));
  $('.soorder_id').eq(index).val(JSON.stringify(sorder_id));
  $('.reference_hdr_id').eq(index).val(JSON.stringify(sorder_id));
  $('.reference_line_id').eq(index).val(JSON.stringify(sorder_lineid));
  $('.soorder_qty').eq(index).val(JSON.stringify(sorder_qty));
  $('.sofree_qty').eq(index).val(JSON.stringify(free_qty));

  $('.sototfree_qty').eq(index).val(freeadd.toFixed("{{ \Session::get('decimal') }}"));
  $('.bulk_free_qty').eq(index).val(freeadd.toFixed("{{ \Session::get('decimal') }}"));
  $('.sototqty').eq(index).val(add.toFixed("{{ \Session::get('decimal') }}"));
  $('.bulk_dispatch_qty').eq(index).val(add.toFixed("{{ \Session::get('decimal') }}"));

  $('#dispatchqtyModal').modal('hide');
});



  $(document).on('click', '.dispatchqty', function (e) {
  e.preventDefault();

  const $row = $(this).closest('tr');
  const index = $row.index();

  const prdid = $row.find('.bulk_product_id').val();
  const soid = $('.ar_sales_hdr_id').val() || '0';
  const source = $('.dispatch_source').val() || '';
  const soqty = $row.find('.sowise_qty').val() || '';

  // ✅ STORE INDEX FOR qtyok
  $('#dispatchqtyModal').data('rowIndex', index);

  if (!prdid) {
    showCustomAlert('Please select Product', 'info');
    return;
  }

  const url = "{{ URL::to('dispatchqty') }}/"
    + encodeURIComponent(prdid) + "/"
    + encodeURIComponent(soid)
    + "?source=" + encodeURIComponent(source);

  $.get(url, function (data) {

    $('.qtydetail').html(data);

    if (soqty) {
      const sQty = String(soqty).split(',');
      $('.dis_issue_qty').each(function (i) {
        const v = sQty[i];
        $(this).val(v && v !== '0' ? v : '');
      });
    }

    $('#dispatchqtyModal').modal('show');
  })
  .fail(function () {
    showCustomAlert('Could not load dispatch quantities. Please try again.', 'info');
  });
});







    $('.clone_lines_body tr').each(function () {
      const $row = $(this);
      const soqty = $row.find('.sowise_qty').val(); // CSV like "1,2,3"
      if (!soqty) return;

      // Example: reflect the total into the row's dispatch qty (adjust to your needs)
      const total = String(soqty)
        .split(',')
        .map(x => parseFloat(x) || 0)
        .reduce((a, b) => a + b, 0);

      // Only write if empty to avoid clobbering user edits
      if (!$row.find('.bulk_dispatch_qty').val()) {
        $row.find('.bulk_dispatch_qty').val(total).trigger('change');
      }
    });


$(document).on('change', '.batch_no', function () {

    const $modalRow = $(this).closest('tr');
    const batchNo = String($(this).val() || '').trim();
    const productId = $('.product_id').val(); // adjust if needed

    if (!batchNo) return;

    let copiedFromExisting = false;

    // 1️⃣ Copy from existing row if same batch already selected
    $('.batch_no').each(function () {

        const $otherRow = $(this).closest('tr');

        if ($otherRow.is($modalRow)) return;

        const otherBatch = String($(this).val() || '').trim();

        if (otherBatch === batchNo) {

            const subInv = $otherRow.find('.subinventory_id').val();
            const subLoc = $otherRow.find('.sublocator_id').val();
            const manu   = $otherRow.find('.manufacture_date').val();
            const exp    = $otherRow.find('.expiry_date').val();
            const qoh    = $otherRow.find('.qoh').val();

            setSelect2Value($modalRow.find('.subinventory_id'), subInv);
            setSelect2Value($modalRow.find('.sublocator_id'), subLoc);

            $modalRow.find('.manufacture_date').val(manu);
            $modalRow.find('.expiry_date').val(exp);
            $modalRow.find('.qoh').val(qoh);

            copiedFromExisting = true;
            return false; // break loop
        }
    });

    if (copiedFromExisting) return;

    // 2️⃣ Otherwise fetch from server
    const url = "{{ URL::to('productstockdetails') }}/" +
        encodeURIComponent(productId) +
        "?batch_no=" + encodeURIComponent(batchNo);

    $.get(url, function (data) {

        if (!Array.isArray(data) || data.length < 5) {
            showCustomAlert('No stock details found for this batch.', 'info');
            return;
        }

        const [qoh, exp, manu, subInv, subLoc] = data;

        setSelect2Value($modalRow.find('.subinventory_id'), subInv);
        setSelect2Value($modalRow.find('.sublocator_id'), subLoc);

        $modalRow.find('.manufacture_date').val(manu);
        $modalRow.find('.expiry_date').val(exp);
        $modalRow.find('.qoh').val(qoh);

    }).fail(function () {
        showCustomAlert('Failed to fetch stock details for this batch.', 'info');
    });
});

function setSelect2Value($select, value) {
    if (!value) return;

    // If option doesn't exist, add it
    if ($select.find("option[value='" + value + "']").length === 0) {
        $select.append(new Option(value, value, true, true));
    }

    $select.val(value).trigger('change.select2');
}


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


    // Renumber Line Nos
    function updateLineNumbers() {
      $('.clone_lines_body1 tr').each(function (index) {
        $(this).find('.bulk_line_no').val(index + 1);
      });
    }

    // Remove button
    $(document).on('click', '.remove-row', function () {
      const rowCount = $('.clone_lines_body tr').length;
      if (rowCount > 1) {
        $(this).closest('tr').remove();
        updateLineNumbers();
      } else {
        showCustomAlert("You Can't Delete Atleast One row should be There", "info");
      }
    });


        // Add Row
// Function to update line numbers
function updateLineNumbers1() {
    $('.clone_lines_body1 tr').each(function (index) {
        $(this).find('.dis_line_no').val(index + 1);
    });
}

// Function to initialize select2
function initSelect2($row) {
    $row.find('select.select2').each(function () {
        $(this).select2({
            width: '100%' // adjust as needed
        });
    });
}

// Add row -1
$(document).on('click', '.add-row1', function () {

    const $tbody = $('.clone_lines_body1');
    const $lastRow = $tbody.find('tr:last');
    const $newRow = $lastRow.clone();

    // Destroy Select2 before cloning
    $newRow.find('select.select2').each(function () {
        if ($(this).data('select2')) {
            $(this).select2('destroy');
        }
    });

    // Remove select2 containers
    $newRow.find('span.select2').remove();

    // Clear input values
    $newRow.find('input').each(function () {
        if (!$(this).hasClass('dispatched_qty_id')) {
            $(this).val('');
        }
    });

    // Reset select values BUT KEEP OPTIONS
    $newRow.find('select').val(null);

    // Fix duplicate IDs
    $newRow.find('[id]').each(function () {
        this.id = this.id + '_' + Math.floor(Math.random() * 100000);
    });

    // Append new row
    $tbody.append($newRow);

    // Reinitialize Select2
    $newRow.find('.select2').select2({
        width: '100%',
        dropdownParent: $('#preview-area') // VERY IMPORTANT for modals
    });

    // Update line numbers
    updateLineNumbers1();
});



        // Remove button
        $(document).on('click', '.remove-row1', function () {
            const rowCount = $('.clone_lines_body1 tr').length;
            if (rowCount > 1) {
                $(this).closest('tr').remove();
                updateLineNumbers();
            } else {
                showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
            }
        });


    var monfree_status = "{{ $monfrez_active }}";
    if (monfree_status == "Yes") {
      var min = "{{ $mindate1 }}";
      var max = "{{ $maxdate1 }}";
    } else {
      var min = "{{ $mindate2 }}";
      var max = "{{ $maxdate2 }}";
    }

    $(document).on("focus", ".dispatch_date", function () {

      $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        minDate: min,
        maxDate: max,
        showAnim: "slideDown",
        yearRange: "-25:+0",

      });
    });

  </script>

@endpush