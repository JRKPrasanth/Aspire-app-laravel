@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Sales Return</h3>
  @include('layouts.breadcrumb')
  <style>
    <?php if ($return_source == "INVOICE") { ?>
    .bulk_product_id,
    .bulk_uom_code_id,
    .return_source,
    .return_status,
    .customerid,
    .organization_id,
    .reference_no,
    .return_date,
    .inv,
    .stdivhide {
      pointer-events: none;
    }

    <?php } else { ?>
    .bulk_uom_code_id,
    .return_source,
    .return_status,
    .organization_id {
      pointer-events: none;
    }

    <?php } ?>
  </style>


  <form method="post" action="" id="salesreturn" data-parsley-validate>
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">

      <div class="card-body card-block headerdiv1">






        <div class="row g-4">

          <!-- Left Column -->
          <div class="col-md-4">

            <!-- RMA Ref Number -->
            <div class="mb-3 row">
              <label for="rma_ref_no" class="col-sm-4 col-form-label">RMA Ref Number</label>
              <div class="col-sm-8">
                <input type="hidden" name="return_source" id="return_source" class="form-control" value="">
                <input type="hidden" name="so_rma_hdr_id" id="so_rma_hdr_id" value="{{ $so_rma_hdr_id }}">
                <input type="text" id="rma_ref_no" name="rma_ref_no" class="form-control rma_ref_no"
                  value="{{ $rma_ref_no }}" readonly>
              </div>
            </div>

            <!-- Return Source -->
            <div class="mb-3 row none">
              <label class="col-sm-4 col-form-label">Return Source</label>
              <div class="col-sm-8">
                <select name="return_source" id="return_source" class="form-select select2 return_source">
                  <option value="">--Select--</option>
                  <option value="INVOICE" {{ $return_source == "INVOICE" ? 'selected' : '' }}>INVOICE</option>
                  <option value="DIRECT" {{ $return_source == "DIRECT" ? 'selected' : '' }}>DIRECT</option>
                </select>
              </div>
            </div>

            <!-- Return Date -->
            <div class="mb-3 row">
              <label class="col-sm-4 col-form-label">Return Date</label>
              <div class="col-sm-8">
                <input type="text" id="return_date" name="return_date" value="{{ $return_date }}"
                  class="form-control return_date">
              </div>
            </div>

            <!-- Return Status -->
            <div class="mb-3 row">
              <label class="col-sm-4 col-form-label">Return Status</label>
              <div class="col-sm-8">
                <select name="return_status" id="return_status" class="form-select return_status" required>
                  <option value="INITIATED" {{ $return_status == "INITIATED" ? 'selected' : '' }}>INITIATED</option>
                  <option value="RETURN" {{ $return_status == "RETURN" ? 'selected' : '' }}>RETURN</option>
                  <option value="DRAFT" {{ $return_status == "DRAFT" ? 'selected' : '' }}>DRAFT</option>
                  <option value="APPROVED" {{ $return_status == "APPROVED" ? 'selected' : '' }}>APPROVED</option>
                  <option value="REJECTED" {{ $return_status == "REJECTED" ? 'selected' : '' }}>REJECTED</option>
                </select>
              </div>
            </div>

            <!-- Invoice Currency -->
            <div class="mb-3 row">
              <label class="col-sm-4 col-form-label">Invoice Currency</label>
              <div class="col-sm-8">
                <select name="invoice_currency" class="form-select select2 invoice_currency" required>
                  {!! $invoice_currency !!}
                </select>
              </div>
            </div>

            <!-- Remarks -->
            <div class="mb-3 row">
              <label class="col-sm-4 col-form-label">Remarks</label>
              <div class="col-sm-8">
                <input type="text" id="remarks" name="remarks" class="form-control remarks" value="{{ $remarks }}">
              </div>
            </div>
          </div>

          <!-- Middle Column -->
          <div class="col-md-4">

            <!-- Reference No -->
            <div class="mb-3 row">
              <label class="col-sm-4 col-form-label">Reference No</label>
              <div class="col-sm-8">
                <input type="text" id="reference_no" name="reference_no" value="{{ $reference_no }}"
                  class="form-control reference_no">
                <input type="hidden" id="reference_source_id" name="reference_source_id" class="reference_source_id"
                  value="{{ $reference_source_id }}">
              </div>
            </div>

            <!-- Customer -->
            <div class="mb-3 row">
              <label class="col-sm-4 col-form-label text-danger">*Customer</label>
              <div class="col-sm-8">
                <select name="customerid" class="form-select select2 customerid" required>
                  {!! $customerid !!}
                </select>
              </div>
            </div>

            <!-- TDS Applicable -->
            <div class="mb-3 row">
              <label class="col-sm-4 col-form-label">TDS Applicable</label>
              <div class="col-sm-8">
                <select name="tds_applicable" class="form-select select2 tds_applicable" required>
                  <option value="">--Please Select--</option>
                  <option value="YES" {{ $tds_applicable == 'YES' ? 'selected' : '' }}>YES</option>
                  <option value="NO" {{ $tds_applicable == 'NO' ? 'selected' : '' }}>NO</option>
                </select>
              </div>
            </div>

            <!-- TDS % -->
            <div class="mb-3 row">
              <label class="col-sm-4 col-form-label">TDS %</label>
              <div class="col-sm-8">
                <input type="text" id="tds_prcnt" name="tds_prcnt" value="{{ $tds_prcnt }}" class="form-control tds_prcnt"
                  readonly>
              </div>
            </div>

            <!-- TDS Amount -->
            <div class="mb-3 row">
              <label class="col-sm-4 col-form-label">TDS Amount</label>
              <div class="col-sm-8">
                <input type="text" id="tds_amount" name="tds_amount" value="{{ $tds_amount }}"
                  class="form-control tds_amount" readonly>
              </div>
            </div>

            <!-- TDS Account -->
            <div class="mb-3 row none">
              <label class="col-sm-4 col-form-label">TDS Account</label>
              <div class="col-sm-8">
                <select name="tds_account_id" class="form-select select2 tds_account_id">
                  {!! $tds_account_id !!}
                </select>
              </div>
            </div>

            <!-- Total Amount -->
            <div class="mb-3 row">
              <label class="col-sm-4 col-form-label">Total Amount</label>
              <div class="col-sm-8">
                <input type="text" id="total_amount" name="total_amount" value="{{ $total_amount }}"
                  class="form-control total_amount" readonly>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-md-4">

            <!-- Bill To Address -->
            <div class="mb-3 row">
              <label class="col-sm-4 col-form-label">Bill To</label>
              <div class="col-sm-8">
                <input type="hidden" name="bill_to_address_id" id="bill_to_address_id" value="{{ $bill_to_address_id }}"
                  class="form-control bill_to_address_id">
                <textarea id="billing_to_address_txt" name="billing_to_address_txt"
                  class="form-control billing_to_address_txt" rows="3" readonly>{{ $bill_to_address }}</textarea>
              </div>
            </div>

            <!-- Ship To Address -->
            <div class="mb-3 row">
              <label class="col-sm-4 col-form-label">Ship To</label>
              <div class="col-sm-8">
                <input type="hidden" name="ship_to_address_id" id="ship_to_address_id" value="{{ $ship_to_address_id }}"
                  class="form-control ship_to_address_id">
                <textarea id="shipping_to_address_txt" name="shipping_to_address_txt"
                  class="form-control shipping_to_address_txt" rows="3">{{ $ship_to_address }}</textarea>
                <div class="mt-2">
                  <button type="button" class="btn btn-success btn-sm changeaddress" value="shipto">
                    <i class="fa fa-address-book"></i> Change Address
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>




        <!-------------------------Linedata -------------------------------->

        <div class="row mt-4">
          <div class=" col-md-12">
            <div id="preview-area" class="table-responsive">
              <table class="table table-bordered clone_table" style="width:200% !important;">

                <thead class="table-light">
                  <tr>
                    <th>Line No</th>
                    <th class="pdtdiv">Product </th>
                    <th>Uom Code </th>
                    <?php if ($return_source == "INVOICE") { ?>
                    <th>Invoice Qty</th>
                    <?php } ?>
                    <th>Return Qty</th>
                    <th>Batch Number</th>
                    <th>Tax Group</th>
                    <th>Manufracture Date</th>
                    <th>Expiry Date</th>
                    <th>Rate</th>
                    <th>Buy Discount</th>
                    <th>Returned Qty</th>
                    <th>Total Amount</th>
                    <th>Reason Comments</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="clone_lines_body">
                  <?php

  if (count($linedata) >= 1) {  ?>
                  @foreach($linedata as $key => $value)



                    <tr class="rcopy clone clonedInput">
                      <td>
                        <input type="hidden" name="bulk_so_rma_line_id[]" class="form-control input-sm bulk_so_rma_line_id"
                          value="">
                        <input type="hidden" name="reference_line_id[]" class="form-control input-sm reference_line_id"
                          value="{{ $value->reference_line_id}}">

                        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no"
                          value="{{ $key + 1 }}" readonly="readonly">
                      </td>
                      <td class="pdtdiv">
                        <select name="bulk_product_id[]" id="bulk_product_id" class="bulk_product_id select2" value=""
                          required="required">
                          {!! $value->product_id !!}
                        </select>
                      </td>
                      <td>
                        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id" value="">
                          {!! $value->uom_code_id !!}
                        </select>
                      </td>
                      <?php    if ($return_source == "INVOICE") { ?>
                      <td>
                        <input type="text" name="bulk_invoice_qty[]"
                          class="form-control input-sm bulk_invoice_qty input_qty_width" value="{{ $value->invoice_qty }}"
                          readonly="readonly">
                      </td>

                      <?php    } ?>
                      <td>
                        <input type="text" name="bulk_return_qty[]"
                          class="form-control input-sm bulk_return_qty input_qty_width" value="{{ $value->return_qty }}"
                          required="required">
                      </td>
                      <td>
                        <input type="text" name="bulk_batch_number[]"
                          class="form-control input-sm bulk_batch_number input_qty_width" value="{{ $value->batch_number }}"
                          required="required">
                      </td>
                      <td class="stdivhide">
                        <select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="select2 bulk_tax_group_id">
                          {!! $value->taxgroup_id !!}
                        </select>
                      </td>
                      <td>
                        <input type="text" name="bulk_manufracture_date[]"
                          class="form-control input-sm bulk_manufracture_date   input_qty_width"
                          value="{{ $value->manufracture_date }}">

                      </td>
                      <td>
                        <input type="text" name="bulk_expiry_date[]"
                          class="form-control input-sm bulk_expiry_date datepicker input_qty_width"
                          value="{{ $value->expiry_date }}">

                      </td>
                      <td>
                        <input type="text" name="bulk_rate[]" class="form-control input-sm bulk_rate input_qty_width"
                          value="{{ $value->rate }}">
                        <input type="hidden" name="bulk_returnqty[]"
                          class="form-control input-sm bulk_returnqty input_qty_width" value="{{ $value->returnqty }}">
                      </td>

                      <td>
                        <input type="text" name="bulk_buy_discount[]"
                          class="form-control input-sm bulk_buy_discount input_qty_width"
                          value="{{ $value->buy_discount }}">
                        <input type="hidden" name="bulk_discount_amount[]"
                          class="form-control input-sm bulk_discount_amount input_qty_width"
                          value="{{ $value->discount_amount }}">
                      </td>

                      <td>
                        <input type="text" name="bulk_returned_qty[]"
                          class="form-control input-sm bulk_returned_qty input_qty_width" value="{{ $value->returned_qty}}"
                          readonly="true">
                      </td>

                      <td>
                        <input type="text" name="bulk_total_amount[]"
                          class="form-control input-sm bulk_total_amount input_qty_width" value="{{ $value->total_amount}}">
                        <input type="hidden" class="form-control input-sm bulk_taxamt input_qty_width" value="">
                      </td>

                      <td>
                        <input type="text" name="bulk_reason_comments[]"
                          class="form-control input-sm bulk_reason_comments input_qty_width"
                          value="{{ $value->reason_comments}}">
                      </td>
                      <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-row">
                          <i class="fas fa-minus-circle"></i>
                        </button>
                      </td>
                    </tr>
                  @endforeach
                  <?php }
  if (count($linedata) < 1) { ?>
                  <tr class="rcopy clone clonedInput">

                    <td><input type="hidden" name="bulk_so_rma_line_id[]"
                        class="form-control input-sm bulk_so_rma_line_id" value="">
                      <input type="hidden" name="reference_line_id[]" class="form-control input-sm reference_line_id"
                        value="">

                      <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1"
                        readonly="readonly">
                    </td>
                    <td class="pdtdiv">
                      <select name="bulk_product_id[]" id="bulk_product_id" class="bulk_product_id select2 "
                        required="required">{!! $product_id !!}</select>
                    </td>
                    <td>
                      <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id">
                        {!! $uom_code_id !!}
                      </select>
                    </td>
                    <?php  if ($return_source == "INVOICE") { ?>
                    <td>
                      <input type="text" name="bulk_invoice_qty[]"
                        class="form-control input-sm bulk_invoice_qty input_qty_width" value="" readonly="readonly">
                    </td>
                    <?php  } ?>
                    <td>
                      <input type="text" name="bulk_return_qty[]"
                        class="form-control input-sm bulk_return_qty input_qty_width" value="" required="required">
                    </td>

                    <td>
                      <input type="text" name="bulk_batch_number[]"
                        class="form-control input-sm bulk_batch_number input_qty_width" value="" required="required">
                    </td>
                    <td class="stdivhide">
                      <select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="select2 bulk_tax_group_id">
                        {!! $taxgroup_id !!}
                      </select>
                    </td>
                    <td>
                      <input type="text" name="bulk_manufracture_date[]"
                        class="form-control input-sm bulk_manufracture_date  input_qty_width" value="">
                    </td>
                    <td>
                      <input type="text" name="bulk_expiry_date[]"
                        class="form-control input-sm bulk_expiry_date datepicker input_qty_width" value="">
                    </td>
                    <td>
                      <input type="text" name="bulk_rate[]" class="form-control input-sm bulk_rate input_qty_width"
                        value="">
                      <input type="hidden" name="bulk_returnqty[]"
                        class="form-control input-sm bulk_returnqty input_qty_width" value="">
                    </td>
                    <td>
                      <input type="text" name="bulk_buy_discount[]"
                        class="form-control input-sm bulk_buy_discount input_qty_width" value="">
                      <input type="hidden" name="bulk_discount_amount[]"
                        class="form-control input-sm bulk_discount_amount input_qty_width" value="">
                    </td>
                    <td>
                      <input type="text" name="bulk_returned_qty[]"
                        class="form-control input-sm bulk_returned_qty input_qty_width" value="" readonly="true">
                    </td>

                    <td>
                      <input type="text" name="bulk_total_amount[]"
                        class="form-control input-sm bulk_total_amount input_qty_width" value="">
                      <input type="hidden" class="form-control input-sm bulk_taxamt input_qty_width" value="">
                    </td>

                    <td>
                      <input type="text" name="bulk_reason_comments[]"
                        class="form-control input-sm bulk_reason_comments input_qty_width" value="">
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
              <div class="text-end">
                <button type="button" class="btn btn-success btn-sm add-row">
                  <i class="fas fa-plus-circle"></i> Add Row
                </button>
              </div>

              <input type="hidden" name="enable-masterdetail" value="true">
            </div>
          </div>
        </div>



        <div class="row mt-4 mb-3">
          <div class="col-lg-12 col-md-12">
            <div class="form-group text-center">
              <?php 
             if ($pageMethod == 'salesreturncreate' || $pageMethod == 'salesreturnfrominvoice') { ?>
              <button type="button" class="btn btn-secondary saveform px-4 me-2" value="APPLYCHANGES">Draft</button>
              <button type="button" class="btn btn-success saveform px-4 me-2" value="save">Save</button>
              <?php } else { ?>
              <button type="button" class="btn btn-success px-4 me-2 saveform" value="APPROVED">Approve</button>
              <button type="button" class="btn btn-danger px-4 me-2 saveform" value="REJECTED">Reject</button>
              <?php } ?>
              <a href="{{ url($pageurl) }}" class='btn btn-danger px-4 me-2'>Cancel</a>

            </div>
          </div>
        </div>

      </div>



      <div class="modal fade" id="dispatchdetailsModal" style="overflow-y:hidden;">
        <div class="modal-dialog" style="width:100%;">
          <div class="modal-content">
            <!--Moda Header-->
            <div class="modal-header">
              <h4 class="modal-title"> Batch Details </h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <!-- Modal Body -->
            <div class="modal-body qtydetail">
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
            </div>

          </div>
        </div>
      </div>

      <!-- Rajalakshmi purpose customer search jqgrid model-->
      <div class="modal fade" id="customerModal">
        <div class="modal-dialog" style="width:80%;">
          <div class="modal-content">
            <!--Moda Header-->
            <div class="modal-header">
              <h4 class="modal-title"> Customer Details </h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <input type="hidden" class="qtyindex" value="">
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
              <table id="customergrid"></table>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
            </div>

          </div>
        </div>
      </div>
      <!--end-->

    </div>


  </form>




@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      $('.dispatchdetails').click(function () {
        $('#dispatchdetailsModal').modal('show');
        $('#dispatchdetailsModal').width("48%").css('margin', 'auto');
        var index = $(this).closest('tr').index();
        var reference_source_id = $('.reference_source_id').val();
        var bulk_product_id = $('.bulk_product_id' + index).select2('val');
        var bulk_returnqty_a = $('.bulk_returnqty' + index).val();
        if (bulk_returnqty_a == "") {
          var bulk_returnqty = 0;
        } else {
          var bulk_returnqty = bulk_returnqty_a;
        }
        var url = "{{URL::to('dispatchreturndatadetails') }}/" + reference_source_id + "/" + bulk_product_id + "?bulk_returnqty=" + bulk_returnqty;
        $('.qtyindex').val(index);
        $.get(url, function (data) {
          $('.qtydetail').html(data);
        });
      });

      $(document).on('change', '.returnqty', function () {
        var index = $(this).attr('data-index');
        var issue_qty = parseFloat($('.issue_qty' + index).val());
        var returnedqty = parseFloat($('.returnedqty' + index).val());
        var qtycheck = issue_qty - returnedqty;

        var qty = parseFloat($(this).val());
        var returnqty = parseFloat(qty).toFixed("{{\Session::get('decimal')}}");
        if (qtycheck < returnqty) {
          showCustomAlert("Return qty Not greater than Issue qty", "warning");
          $('.returnqty' + index).val('');
        }
      });

      $(document).on('click', '.qtyok', function () {

        var add = 0;
        var returnqty_a = [];
        var batch_number_a = [];
        var issue_qty_a = [];
        var returnedqty_a = [];
        var dispatched_qty_a = [];
        $('.returnqty').each(function (k) {
          var val = parseInt($(this).val());
          if (isNaN(val)) {
            val = 0;
          }
          add = add + val;
          returnqty_a[k] = val;
        });
        $('.batch_number').each(function (k, v) {

          if (($(this).val()) != '') {
            var batch_number = $(this).val();
          }
          else {
            var batch_number = 0;
          }
          batch_number_a[k] = batch_number;
        });

        $('.issue_qty').each(function (k, v) {
          if (($(this).val()) != '') {
            var issue_qty = $(this).val();
          }
          else {
            var issue_qty = 0;
          }
          issue_qty_a[k] = issue_qty;
        });
        $('.returnedqty').each(function (k, v) {
          if (($(this).val()) != '') {
            var returnedqty = $(this).val();
          }
          else {
            var returnedqty = 0;
          }
          returnedqty_a[k] = returnedqty;
        });
        $('.dispatched_qty').each(function (k, v) {
          if (($(this).val()) != '') {
            var dispatched_qty = $(this).val();
          }
          else {
            var dispatched_qty = 0;
          }
          dispatched_qty_a[k] = dispatched_qty;
        });

        var index = $('.qtyindex').val();

        $('.bulk_batch_number' + index).val(batch_number_a);
        $('.bulk_dispatched_qty' + index).val(dispatched_qty_a);
        $('.bulk_issue_qty' + index).val(issue_qty_a);
        $('.bulk_returnedqty' + index).val(returnedqty_a);
        $('.bulk_returnqty' + index).val(returnqty_a);
        if (add == 0) {
          $('.bulk_return_qty' + index).val('');
          showCustomAlert('Please Enter Return Qty', 'warning');
          $('#dispatchdetailsModal').modal('show');
        } else {
          $('.bulk_return_qty' + index).val(add.toFixed("{{\Session::get('decimal')}}"));
          $('#dispatchdetailsModal').modal('hide');

        }

      });




      $(document).on('change', '.customerid', function (event) {
        var customer_id = $('.customerid').select2('val');
        if (customer_id != '') {
          var url = "{{ URL::to('salesreturnaddress') }}/" + customer_id;
          $.get(url, function (data) {
            if (data[1] != '' && data[0] != '') {
              var result_data = data[1].split('~');
              $('.shipping_to_address_txt').val(result_data[0]);
              $('.ship_to_address_id').val(result_data[1]);

              var result_data = data[0].split('~');
              $('.billing_to_address_txt').val(result_data[0]);
              $('.bill_to_address_id').val(result_data[1]);

            }
            if (data[1] == "" || data[0] != "") {
              if (data[1] != '') {
                var result_data = data[1].split('~');
                $('.shipping_to_address_txt').val(result_data[0]);
                $('.ship_to_address_id').val(result_data[1]);

              }
              else {
                $('.shipping_to_address_txt').val('');
                showCustomAlert('Please Assingn Ship To Address !!!', 'info');

                if (data[0] != "")
                  $('.billing_to_address_txt').val(data[0]);
                $('.ship_to_address_id').val('');
              }
              if (data[0] != '') {
                var result_data = data[0].split('~');
                $('.billing_to_address_txt').val(result_data[0]);
                $('.bill_to_address_id').val(result_data[1]);
              }

            }
            if (data[1] == '' && data[0] == '') {
              $('.billing_to_address_txt,.shipping_to_address_txt').val('');
              showCustomAlert('Please Assingn Ship To and Ship To Address !!!', 'warning');
            }
          });
        }
        else {
          $("#billing_to_address_txt").val('');
          $("#shipping_to_address_txt").val('');
          event.preventDefault();
        }


      });


      /*  Purpose for TDS */
      var decimal = '<?php echo \Session::get('decimal'); ?>';
      $(".tds_applicable").change(function () {
        var customer_id = $('.customerid').val();
        var tds = $(".tds_applicable option:selected").val();
        if (customer_id) {
          if (tds == "YES") {
            var url = "{{ URL::to('salesloadtds') }}/" + customer_id + "/" + tds;
            $.get(url, function (data) {

              if (data.tds_percentage != "") {
                $('.tds_prcnt').val(data.tds_percentage);
                $('.tds_account_id').val(data.tds_account_id).change();
                var sum = 0;
                var sumwithtds = 0;
                var total = 0;
                var data1 = data.tds_percentage;
                $(".bulk_total_amount").each(function (index) {
                  var totamt = parseFloat($(".bulk_total_amount" + index).val());
                  totamt = totamt ? totamt : 0;

                  total = totamt;
                });
                if (data1 != '') {
                  var tds_amount = parseFloat(total * (data1 / 100)).toFixed(decimal);
                  tds_amount = tds_amount ? tds_amount : 0;
                  $('.tds_amount').val(tds_amount);
                  sumwithtds = parseFloat((total) - (tds_amount)).toFixed(decimal);
                  sumwithtds = (isNaN(sumwithtds) ? 0 : sumwithtds);
                  $('.total_amount').val(sumwithtds);

                }
              }
              else {
                notyMsg("info", "TDS Percentage Not Set For This Customer");
              }

            });
            $('.tds_prcnt,.tds_amount').attr('required', true);
          }
          else {
            $('.tds_prcnt').val('');
            $('.tds_amount').val('');
            $('.tds_prcnt,.tds_amount').attr('required', false);
          }
        }
      });
      /*end*/



      $(document).on('click', '.approve', function () {
        $(".return_status").val("APPROVED").change();
      });

      $(document).on('click', '.reject', function () {
        $(".return_status").val("REJECTED").change();
      });

      $(document).on('click', '.applychanges', function () {
        $(".return_status").val("DRAFT").change();

      });

      $(".applychanges").click(function () {
        draft_save('salesreturn_form', 'salesreturncreate', 'salesreturn', 'salesreturnedit');
      });


      $(document).on('click', '.saveform', function (e) {
        var btnval = $(this).val();

        if (btnval == 'APPLYCHANGES') {
          $('.remarks').attr('required', false);
          $('.customerid').attr('required', true);
          $("#return_status").val('DRAFT');
        } else if (btnval == 'APPROVED') {
          $('.remarks').attr('required', false);
          $("#return_status").val('APPROVED');
        } else if (btnval == 'REJECTED') {
          $('.remarks').attr('required', true);
          $("#return_status").val('REJECTED');
        } else {
          $('.remarks').attr('required', false);
          $("#return_status").val('INITIATED');
        }

        var url = "{{ url('salesreturnsave') }}";
        var red_url = "{{ url($pageurl) }}";
        var create_url = "{{ url('salesreturncreate') }}/0";
        var form = $('#salesreturn');
        var formdata = form.serialize();

        if (btnval != 'APPLYCHANGES') {
          if (btnval == 'save' || btnval == 'APPROVED' || btnval == 'REJECTED') {
            form.parsley().validate();
            if (form.parsley().isValid()) {
              var $btn = $(this);
              $btn.prop('disabled', true);
              $.post(url, formdata, function (data) {
                var status = data.status;
                var msg = data.message;
                showCustomAlert(msg, status);
                setTimeout(function () {
                  window.location.href = red_url;
                }, 1500);
              });
            }
          }
        } else { // APPLYCHANGES case
          var customer = $('.customerid').val();

          if (customer == '' || customer == null) {
            showCustomAlert('Please select a Customer before saving as Draft.', 'error');
            $('.customerid').focus();
            return false;
          }

          $.post(url, formdata, function (data) {
            $('.customerid').attr('required', true);
            var status = data.status;
            var msg = data.message;
            var id = data.id;
            var edit_url = "{{ url('salesreturncreate') }}/" + id;

            showCustomAlert(msg, 'success');
            setTimeout(function () {
              window.location.href = edit_url;
            }, 1500);
          });
        }

      });


      // When product changes, fetch details and populate fields for that SAME row
      $(document).on('change', '.bulk_product_id', function () {
        const $row = $(this).closest('tr');                 // work within the row
        const productId = $(this).val();
        const type = "0";
        const plid = 0;
        const cusid = $('.customerid').val() || 0;          // adjust if your customer id lives elsewhere

        if (!productId) return;

        // 1) QOH / basic product facts
        $.get("{{ URL::to('dispatchproductqoh') }}/" + productId, function (data) {
          // expects: data.uomcode, data.manufacturer, data.expiry
          $row.find('.bulk_uom_code_id').val(data.uomcode).trigger('change');
          $row.find('.bulk_manufracture_date').val(data.manufacturer).trigger('change');
          $row.find('.bulk_expiry_date').val(data.expiry).trigger('change');
        });

        // 2) Pricing, tax group, etc.
        $.get("{{ url::to('productdetails_so') }}/" + productId + "/" + plid + "/" + cusid + "/" + type, function (data) {
          // expects (typical): data.uom_code_id, data.tax_group_id, data.unit_price or data.rate, data.buy_discount
          const unitPrice = (data.rate != null ? data.rate : data.unit_price);

          if (Number(unitPrice) === 0) {
            // reset row if price is zero
            $row.find('.bulk_uom_code_id').val('').trigger('change');
            $row.find('.bulk_rate').val('0');
            $row.find('.bulk_tax_group_id').val('').trigger('change');
            // keep the product selection unless you really want to clear it:
            // $row.find('.bulk_product_id').val('').trigger('change');
            return;
          }

          // set UOM and Tax Group
          if (data.uom_code_id) $row.find('.bulk_uom_code_id').val(data.uom_code_id).trigger('change');
          if (data.tax_group_id) $row.find('.bulk_tax_group_id').val(data.tax_group_id).trigger('change');

          // set Rate / Discount if provided by API
          if (unitPrice != null) $row.find('.bulk_rate').val(unitPrice);
          if (data.buy_discount != null) $row.find('.bulk_buy_discount').val(data.buy_discount);
        });
      });


    $(document).on('change', '.bulk_batch_number', function () {

        const $row = $(this).closest('tr');
        const productId = $row.find('.bulk_product_id').val();
        const batch = $(this).val();

        $.get("{{ url('dispatchproductexpiry') }}", {
            id: productId,
            batch: batch
        }, function(data){
            $row.find('.bulk_manufracture_date').val(data.manufacturer);
            $row.find('.bulk_expiry_date').val(data.expiry);
        });

    });

      // Helpers
      function toNum(v) {
        const n = parseFloat(v);
        return isNaN(n) ? 0 : n;
      }

      function decimals() {
        // Session decimal places; default to 2 if missing
        const d = parseInt("{{ \Session::get('decimal') }}", 10);
        return isNaN(d) ? 2 : d;
      }

      function computeRowTotals($row) {
        const qty = toNum($row.find('.bulk_return_qty').val());
        const rate = toNum($row.find('.bulk_rate').val());
        const discountP = toNum($row.find('.bulk_buy_discount').val());

        // mirror qty into hidden "bulk_returnqty"
        $row.find('.bulk_returnqty').val(qty);

        const lineTotal = qty * rate;                              // before discount/tax
        const discountAmt = (lineTotal * discountP) / 100;         // absolute
        const afterDisc = lineTotal - discountAmt;

        // Tax %
        let taxPercent = 0;
        const sel = $row.find('.bulk_tax_group_id option:selected');
        if (sel.length) {
          const dAttr = sel.attr('data-display');
          taxPercent = toNum(dAttr);
        }

        const taxAmt = (afterDisc * taxPercent) / 100;
        const totalWithTax = afterDisc + taxAmt;

        const dec = decimals();
        $row.find('.bulk_discount_amount').val(discountAmt.toFixed(dec));
        $row.find('.bulk_taxamt').val(taxAmt.toFixed(dec));
        $row.find('.bulk_total_amount').val(totalWithTax.toFixed(dec));
      }

      function computeGrandTotal() {
        let grand = 0;
        $('.clone_lines_body tr').each(function () {
          grand += toNum($(this).find('.bulk_total_amount').val());
        });
        // TDS (if applicable)
        const tds = toNum($('.tds_amount').val());
        grand = grand - tds;

        $('.total_amount').val(grand.toFixed(decimals()));
      }

      /* 1) Return qty should not exceed invoice qty and leftover allowance */
      $(document).on('change', '.bulk_return_qty', function () {
        const $row = $(this).closest('tr');

        const invoiceQtyEl = $row.find('.bulk_invoice_qty');  // exists only if source == INVOICE
        const hasInvoice = invoiceQtyEl.length > 0;

        const invoiceQty = toNum(invoiceQtyEl.val());
        const alreadyReturned = toNum($row.find('.bulk_returned_qty').val());
        const rtnQty = toNum($(this).val());

        if (hasInvoice) {
          // Hard cap against invoice quantity
          if (rtnQty > invoiceQty) {
            showCustomAlert("Return qty should not exceed Invoice Qty.", "error");
            $(this).val('');
            computeRowTotals($row);
            computeGrandTotal();
            return;
          }

          // Remaining allowance (invoice - already returned)
          let remaining = invoiceQty - alreadyReturned;
          if (remaining < 0) remaining = 0;

          if (rtnQty > remaining) {
            showCustomAlert(remaining === 0 ? "Invoice Qty is fully returned." :
              "Return qty exceeds remaining invoice balance.", "error");
            $(this).val('');
            computeRowTotals($row);
            computeGrandTotal();
            return;
          }
        }

        // Passed validations → recalc
        computeRowTotals($row);
        computeGrandTotal();

        // If you need rate-calculation side-effects elsewhere:
        $row.find('.bulk_rate').trigger('change');
      });

      /* 2) Discount change → recompute row and grand total */
      $(document).on('change', '.bulk_buy_discount', function () {
        const $row = $(this).closest('tr');
        computeRowTotals($row);
        computeGrandTotal();
        $('.tds_applicable').trigger('change'); // keep your existing side-effect
      });

      /* 3) Rate change → recompute row and grand total */
      $(document).on('change', '.bulk_rate', function () {
        const $row = $(this).closest('tr');
        computeRowTotals($row);
        computeGrandTotal();
        $('.tds_applicable').trigger('change'); // keep your existing side-effect
      });



      $('.customersearch').click(function () {
        $('#customerModal').modal('show');
        $('#customerModal').width("100%");
      });


      $('.shipto').click(function () {
        var cid = $('.customerid').val();
        var c_name = $.trim($('.customerid option:selected').text()).split("-");
        if (cid == "") {
          notyMsg('info', "Please select Customer first");
        }
        else {

          $('#customerModal').modal('show');
          $('#customerModal').width("100%");

          var ct = $(this).val();
          $('.custype').val(ct);
          var custype = $('.custype').val();

          if (ct == "shipto") {
            var site_type = "SHIP_TO";
          }

          $(mygrid).jqGrid('setGridParam',
            {
              postData: { "site_type": site_type, "cid": cid }
            }).trigger('reloadGrid');
          $("#gs_customer_name").val($.trim(c_name[1]));
          $("#gs_site_type").val(site_type);
          $("#gs_customer_number").val($.trim(c_name[0]));
          $("#gs_customer_name,#gs_site_type,#gs_customer_number").attr('readonly', true);
        }
      });

      mygrid.jqGrid('navGrid', pagerSelector,
        { cloneToTop: true, edit: false, add: false, del: false, search: true });
      myAddButton({
        caption: "Select Customer",
        title: "Customer",
        buttonicon: 'ui-icon-plus',
        onClickButton: function () {
          var gr = jQuery(mygrid).jqGrid('getGridParam', 'selrow');
          var customerdata = jQuery(mygrid).jqGrid('getRowData', gr, 'customer_id');
          //console.log(customerdata.customer_id);
          if (gr) {
            $('.customer_id').val(customerdata.customer_id);
            $('.shipping_to_address_txt').val(" " + customerdata.customer_site_name + "," + customerdata.address + "," + customerdata.city + "," + customerdata.state + "-" + customerdata.pincode + "," + customerdata.country + "." + "Contact No:" + customerdata.contact_number);
            $('#customerModal').modal('hide');
          }
          else {
            notyMsg('info', "Please Select a row.")
          }
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

    $(document).on("focus", ".return_date", function () {

      $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showAnim: "slideDown",
        yearRange: "-25:+0",

      });
    });		
  </script>


@endpush