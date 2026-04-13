@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Work Order</h3>
  @include('layouts.breadcrumb')


  <form method="post" action="" id="workorder" data-parsley-validate>
    <input type="hidden" value="" name="savestatus" id="savestatus" />

    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-header bg-primary text-white fw-semibold"></div>
      <div class="card-body card-block headerdiv1">


        <!------------------------------------- Body content start here ---------------------------->
        <div class="row g-4">
          <!-- Left Column -->
          <div class="col-md-4">
            <div class="mb-3">
              <label for="workorder_no" class="form-label">Workorder No</label>
              <input type="hidden" id="workorder_hdr_id" name="workorder_hdr_id" value="{{ $row->workorder_hdr_id }}">
              <input type="text" id="workorder_no" name="workorder_no" class="form-control"
                value="{{ $row->workorder_no }}" readonly>
            </div>

            <div class="mb-3 none">
              <label for="workorder_date" class="form-label">Workorder Date</label>
              <input type="text" id="workorder_date" name="workorder_date" class="form-control datepicker workorder_date"
                value="{{ old('workorder_date', ($row->workorder_date ?: now()->format('Y-m-d'))) }}">
            </div>

            <div class="mb-3">
              <label for="shift" class="form-label">Shift</label>
              <input type="text" id="shift" name="shift" class="form-control shift" value="{{ $row->shift }}">
            </div>
          </div>

          <!-- Middle Column -->
          <div class="col-md-4">
            <div class="mb-3 none">
              <label for="source" class="form-label source">Workorder Source</label>
              <select id="source" name="source" class="form-select source select2">
                <option value="">-- Select --</option>
                <option value="STANDARD" {{ $row->source == "STANDARD" ? 'selected' : '' }}>STANDARD</option>
                <option value="SALESORDER" {{ $row->source == "SALESORDER" ? 'selected' : '' }}>SALESORDER</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="remarks" class="form-label">Remarks</label>
              <input type="text" id="remarks" name="remarks" class="form-control remarks" value="{{ $row->remarks }}">
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-md-4">
            <div class="mb-3">
              <label for="so_reference_number" class="form-label">SO Reference No</label>
              <input type="hidden" id="reference_id" name="reference_id" value="{{ $row->reference_id }}">
              <input type="text" id="so_reference_number" name="so_reference_number"
                class="form-control so_reference_number" value="{{ $row->so_reference_number }}" readonly>
            </div>

            <div class="mb-3 none">
              <label for="created_by" class="form-label">Created By</label>
              <select id="created_by" name="created_by" class="form-select created_by select2">
                {!! $created_by !!}
              </select>
            </div>

            <!-- Hidden Organization Field -->
            <div class="mb-3 d-none">
              <label for="organization_id" class="form-label">Organization</label>
              <select id="organization_id" name="organization_id" class="form-select" disabled>
                {!! $organization_id !!}
              </select>
            </div>
          </div>
        </div>





        <div class="row mt-4">
          <div class="col-12 linetable">
            <div class="table-responsive">
              <table class="table table-bordered clone_table">
                <thead class="table-light">
                  <tr>
                    <th>Line No</th>
                    <th class="pdtdiv">Product</th>
                    <th>Uom Code </th>
                    <th>Qty</th>
                    <th>Due Date</th>
                    <th>Comments</th>
                    <th style="width: 60px;"></th>
                  </tr>
                </thead>
                <tbody class="clone_lines_body">
                  @if(count($linedata) > 0)
                    @foreach($linedata as $key => $value)
                      <tr class="line-row">
                        <td>
                          <input type="hidden" name="bulk_workorder_line_id[]"
                            class="form-control input-sm bulk_workorder_line_id" value="{{ $value->workorder_line_id }}">

                          <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no"
                            value="{{ $key + 1 }}" readonly="readonly">
                        </td>
                        <td class="pdtdiv">
                          <select name="bulk_product_id[]" id="bulk_product_id" class="bulk_product_id select2"
                            required="required">
                            {!! $value->product_id !!}
                          </select>
                        </td>
                        <td class="uom none">
                          <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id"
                            data-show-subtext="true" data-live-search="true" style="pointer-events:none;">
                            <option>Select</option>
                            <option value=''> {!! $value->uomcode_id !!} </option>

                          </select>
                        </td>
                        <td>
                          <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width"
                            value="{{ $value->qty }}" required="required">
                        </td>
                        <td>
                          <div class="input-group m-b">
                            <input type="text" name="bulk_due_date[]" class="form-control bulk_due_date"
                              value="{{$value->due_date}}" required="required" />
                          </div>
                        </td>

                        <td>
                          <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments"
                            value="{{ $value->comments }}">
                        </td>

                        <td class="text-center">
                          <?php    if ($row->source != 'SALESORDER') { ?>
                          <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-minus-circle"></i>
                          </button>
                          <?php    } ?>
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr class="line-row">
                      <td>
                        <input type="hidden" name="bulk_workorder_line_id[]"
                          class="form-control input-sm bulk_workorder_line_id" value="">

                        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1"
                          readonly="readonly">
                      </td>
                      <td class="pdtdiv">
                        <select name="bulk_product_id[]" id="bulk_product_id" class="bulk_product_id select2"
                          required="required">{!! $product_id !!}</select>
                      </td>
                      <td class="uom none">
                        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id"
                          data-show-subtext="true" data-live-search="true">
                          {!! $uomcode_id !!}
                        </select>
                      </td>
                      <td>
                        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value=""
                          required="required">
                      </td>
                      <td>
                        <div class="input-group m-b">
                          <input type="text" name="bulk_due_date[]" class="form-control bulk_due_date " value=""
                            required="required" />
                        </div>
                      </td>

                      <td>
                        <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value="">
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
        <!-------------------------Linedata End-------------------------------->

        <div class="row mt-4">
          <div class="col-lg-12 col-md-12">
            <div class="form-group text-center">

              <button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
              <a class='btn btn-danger px-4' onclick='location.href="{{ URL::to($pageModule) }}"'>Cancel</a>
            </div>
          </div>
        </div>


      </div>

    </div>
  </form>


  <!-- purpose:Product Search Modal-->
  <div class="modal fade" id="productModal">
    <div class="modal-dialog" style="width:80%;">
      <div class="modal-content">
        <!--Moda Header-->
        <div class="modal-header">
          <h4 class="modal-title"> Product Details </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal Body -->
        <div class="modal-body">
          <table id="productgrid"></table>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
        </div>

      </div>
    </div>
  </div>
  <input type="hidden" class="pdtindex" value="" />
  <!--end-->


@endsection
@push('scripts')

  <script>


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


    /* purpose:qty 0 required validation*/
    function qtyrequiredvalid() {
      $('.bulk_qty').each(function (i) {
        var val = $(this).val();
        if (val == 0) {
          $('.bulk_qty' + i).val('');
        }
      });
    }
    /*end*/
    $(document).ready(function () {
      /* purpose:hide(salesorder) & show(workorder) search based on page*/

      var source = '<?php echo $row->source; ?>';

      if (source == "SALESORDER") {
        $('.productsearch').hide();
      } else {
        $('.productsearch').show();
      }
      /*end*/

      /**********Up/down/left/right arrow navigation start*******/
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

      /**********Up/down/left/right arrow navigation end *******/


      /*deepika purpose:qty validation*/
      $(document).on('keypress', '.bulk_qty,.shift', function (ev) {
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
          return true;
        }
        ev.preventDefault();
        return false;
      });

      /*copy paste validation*/
      $('.bulk_qty').bind("cut copy paste", function (e) {
        e.preventDefault();
      });

      /*deepika purpose: to call save function & validate form*/
      $(document).on('click', '.saveform', function () {
        var btnval = $(this).val();
        if (btnval == 'SAVE' || btnval == 'SAVENEW')
          var savestatus = 'SAVE';

        $('#savestatus').val(savestatus);

        var url = "{{ URL::to('workordersave') }}";
        var red_url = "{{ URL::to('workorder') }}";
        var create_url = "{{ URL::to('workordercreate') }}/0";
        qtyrequiredvalid();

        var form = $('#workorder');

        form.parsley().validate();
        var form = $('#workorder');
        form.parsley().validate();

        if (form.parsley().isValid()) {

          var $btn = $(this);            
			    $btn.prop('disabled', true);
          var formdata = $('#workorder').serialize();
          $.post(url, formdata, function (data) {
            var status = data.status;
            var msg = data.message;
            var id = data.id;
            var edit_url = "{{ URL::to('workordercreate') }}/" + id;
            if (btnval != 'SAVE') {

              showCustomAlert(msg, status);
              setTimeout(function () {
                window.location.href = create_url;
              }, 1500);
            }
            else {
              showCustomAlert(msg, status);
              setTimeout(function () {
                window.location.href = red_url;
              }, 1500);
            }
          });
        }

      });


      // PRODUCT change → load UOM for this row; prevent duplicates via pdtcheck
      $(document).on('change', '.bulk_product_id', function () {
        const $row = $(this).closest('tr');
        const product_id = $(this).val();
        const rowIndex = $row.index();

        if (!product_id) {
          // Clear dependent UOM on empty selection
          $row.find('.bulk_uom_code_id').val(null).trigger('change.select2');
          return;
        }

        // Your existing duplicate check helper
        const isDup = (typeof pdtcheck === 'function') ? (pdtcheck(product_id, rowIndex) > 0) : false;

        if (isDup) {
          const msg = $row.find('.bulk_product_id option:selected').text() || 'This';
          showCustomAlert(`<span style="color:#fdff65">${msg}</span> Product Already Selected`, 'info');
          // Reset current product (keep select2 synced if used)
          $(this).val(null).trigger('change.select2');
          return;
        }

        // Load UOM and set in THIS row
        const url = "{{ URL::to('workorderuom') }}/" + encodeURIComponent(product_id);
        $.get(url, function (data) {
          const uomVal = $.trim(data || '');
          $row.find('.bulk_uom_code_id').val(uomVal).trigger('change.select2'); // or just .val(uomVal) if plain input
        });
      });

      // DUE DATE change → if edited in first row, copy to all rows
      $(document).on('change', '.bulk_due_date', function () {
        const $row = $(this).closest('tr');
        const isFirstRow = ($row.index() === 0);
        if (!isFirstRow) return;

        const dueDate = $(this).val() || '';
        // Write to every row’s due date input (no index-suffixed classes)
        $(this).closest('tbody').find('.bulk_due_date').each(function () {
          // Only update if value differs to avoid redundant events
          if ($(this).val() !== dueDate) $(this).val(dueDate).trigger('change');
        });
      });


      /* purpose:product search modal*/

      $(document).on('click', '.productsearch', function () {
        var index = ($(this).closest('tr').index());
        $('.pdtindex').val(index);
        $('#productModal').modal('show');
        $('#productModal').width("100%");
      });

      var mypdtgrid = $("#productgrid"),
        pagerSelector = "#pager",
        myAddButton = function (options) {
          mypdtgrid.jqGrid('navButtonAdd', pagerSelector, options);
          mypdtgrid.jqGrid('navButtonAdd', '#' + mypdtgrid[0].id + "_toppager", options);
        };
      var groupname = "'FINISHED GOODS'";
      var grp = [];
      grp.push(groupname);
      mypdtgrid.jqGrid({
        url: "{{ URL::to('getProductgridData') }}?prggrp=" + grp,
        datatype: "json",
        mtype: "GET",
        height: 320,
        width: 1000,
        colModel: [
          { name: "product_code", label: "Product Code", width: 55 },
          { name: "group_name", label: "Product Group", width: 55 },
          { name: "category_name", label: "Product Category", width: 55 },
          { name: "concatenated_product", label: "Product Name", width: 55 },
          { name: "product_id", label: "id", hidden: true, width: 55 }
        ],

        iconSet: "fontAwesome",
        rowNum: 10,
        rowList: [10, 20, 50, 100, 250, 500, 1000],
        sortorder: "asc",
        viewrecords: true,
        gridview: true,
        rownumbers: true,
        pager: pagerSelector,
        toppager: true,
        searching: {
          defaultSearch: "cn"
        }
      });
      jQuery(mypdtgrid).jqGrid('filterToolbar', { stringResult: true, searchOnEnter: false });
      mypdtgrid.jqGrid('navGrid', pagerSelector,
        { cloneToTop: true, edit: false, add: false, del: false, search: true });
      myAddButton({
        caption: "Select Product",
        title: "Product",
        buttonicon: 'ui-icon-plus',
        onClickButton: function () {
          var index = $('.pdtindex').val();
          var gr = jQuery(mypdtgrid).jqGrid('getGridParam', 'selrow');
          var product = jQuery(mypdtgrid).jqGrid('getCell', gr, 'product_id');
          if (gr) {
            $('.bulk_product_id' + index).val(product);
            $('.bulk_product_id' + index).trigger('change');
            $('#productModal').modal('hide');
          }
          else {
            notyMsgs("info", 'Please Select one row');
          }
        }
      });

    });


    /*end*/

    $(document).on("focus", ".bulk_due_date", function () {

      $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        minDate: 0,
        maxDate: +30,
        showAnim: "slideDown",

      });
    });

  </script>


@endpush