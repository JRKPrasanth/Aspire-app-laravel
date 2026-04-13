@extends('layouts.header')
@section('content')
  <h3 class="text-danger">

    <?php if ($return_url == 'materialbomapprovalcreate') { ?>
    Product Bom Approval
    <?php } else { ?>
    Product Bom
    <?php } ?>

  </h3>
  @include('layouts.breadcrumb')


  <form method="post" action="" id="materialbom" data-parsley-validate>
    <input type="hidden" value="" name="savestatus" id="savestatus" />

    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-body container">



        <!------------------------------------- Body content start here ---------------------------->
        <div class="row">
          <div class="col-12">

            <!-- Product Info -->
            <div class="row g-4">
              <!-- Production Product -->
              <div class="col-md-4">
                <div class="mb-3 row align-items-center">
                  <label class="col-sm-4 col-form-label">
                    <span class="text-danger">*</span> Product
                  </label>
                  <div class="col-sm-8">
                    <input type="hidden" name="material_bom_hdr_id" id="material_bom_hdr_id"
                      value="{{ $row->material_bom_hdr_id }}">
                    <select name="assembly_product_id" id="assembly_product_id"
                      class="form-select select2 assembly_product_id" required tabindex="1">
                      {!! $assembly_product_id !!}
                    </select>
                  </div>

                  <div class="col-12 mt-1">
                    <span class="badge bg-danger dup_name d-none"></span>
                  </div>
                </div>

                <div class="mb-3 row align-items-center">
                  <label class="col-sm-4 col-form-label">Active</label>
                  <div class="col-sm-8">
                    <select name="active" class="form-select select2 active" tabindex="4">
                      <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                      <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- UOM / Process -->
              <div class="col-md-4">
                <div class="mb-3 row align-items-center none">
                  <label class="col-sm-4 col-form-label">UOM Code</label>
                  <div class="col-sm-8">
                    <select name="uom_code_id" id="uom_code_id" class="form-select select2 uom_code_id" tabindex="2">
                      {!! $uom_code_id !!}
                    </select>
                  </div>
                </div>

                <div class="mb-3 row align-items-center">
                  <label class="col-sm-4 col-form-label">Process</label>
                  <div class="col-sm-8 d-flex align-items-center">
                    <input class="form-check-input process me-2" type="checkbox" name="process[]" value="1" {{
    $row->process == "1" ? 'checked' : '' }}>
                    <label class="form-check-label mb-0">Enable</label>
                  </div>
                </div>

              </div>

              <!-- Remarks / Created By -->
              <div class="col-md-4">
                <div class="mb-3 row align-items-center">
                  <label class="col-sm-5 col-form-label">Remarks</label>
                  <div class="col-sm-7">
                    <input type="text" name="remarks" id="remarks" class="form-control remarks"
                      value="{{ $row->remarks }}" tabindex="3">
                  </div>
                </div>

                <div class="mb-3 row align-items-center none">
                  <label class="col-sm-5 col-form-label">Created By</label>
                  <div class="col-sm-7">
                    <select name="created_by" id="created_by" class="form-select select2 created_by" tabindex="6">
                      {!! $created_by !!}
                    </select>
                  </div>
                </div>

                <div class="mb-3 row d-none">
                  <label class="col-sm-4 col-form-label">Total Component Qty</label>
                  <div class="col-sm-6">
                    <input type="text" name="total_component_qty" id="total_component_qty"
                      class="form-control totalcompqty" value="1" readonly>
                  </div>
                </div>
              </div>
            </div>

            <!-- Additional Details -->
            <h5 class="mt-4 mb-3 text-primary border-bottom pb-2">Additional Details</h5>
            <div class="row g-4">
              @foreach($enabled_columns as $index => $val)
                @if($val->column_name == 'project_id' && $val->active == 1)
                        <div class="col-md-4">
                          <div class="mb-3 row align-items-center project_id_cfg">
                            <label class="col-sm-4 col-form-label">
                              @if($val->action == '1')
                                <span class="text-danger">*</span>
                              @endif
                              Project
                            </label>
                            <div class="col-sm-8">
                              <select name="project_id" class="form-select select2 project_id" {{ $val->action == '1' ? 'required' :
                  '' }} tabindex="4">
                                {!! $project_id !!}
                              </select>
                            </div>
                          </div>
                        </div>
                @endif
              @endforeach
            </div>

          </div>
        </div>

        <!-------------------------Linedata -------------------------------->

        <div class="row mt-4">

          <div class="col-12 linetable">
            <div id="preview-area" class="table-responsive">
              <table class="table table-bordered clone_table">
                <thead class="table-light">
                  <tr>
                    <th>Line No</th>
                    <th>Component Product </th>
                    <!--    <th>&nbsp;</th>  -->
                    <th>Component Uom</th>
                    <th>Component Qty</th>
                    <th class="proces">Process Level</th>
                    <th class="proces">Process Name</th>
                    <th class="proces">Machine</th>
                    <th>Comments</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="clone_lines_body">
                  @if(count($linedata) >= 1)
                    @foreach($linedata as $key => $value)
                      <tr class="rcopy clone">
                        <td>
                          <input type="hidden" name="bulk_material_bom_line_id[]"
                            class="form-control input-sm bulk_material_bom_line_id"
                            value="{{ $value->material_bom_line_id }}">

                          <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no"
                            value="{{ $key + 1 }}" readonly="readonly">
                        </td>
                        <select id="component_product_master" style="display:none;">{!! $assembly_product_id1 !!}</select>
                        <td class="pdtdiv">
                          <select name="bulk_component_product_id[]" id="bulk_component_product_id"
                            class="bulk_component_product_id select2">{!! $value->component_product_id !!}</select>
                        </td>
                        <!--  <td><i class="fa fa-search productsearch"></i></td> -->
                        <td class="uom none">
                          <select name="bulk_component_uom_code_id[]" id="bulk_component_uom_code_id"
                            class="select2 bulk_component_uom_code_id" data-show-subtext="true" data-live-search="true"
                            readonly>
                            {!! $value->component_uom_code_id !!}
                          </select>
                        </td>
                        <td>
                          <input type="text" name="bulk_component_qty[]"
                            class="form-control input-sm bulk_component_qty input_qty_width"
                            value="{{ $value->component_qty }}">
                        </td>
                        <td class="proces">
                          <select name="bulk_process_level[]" id="bulk_process_level" class="select2 bulk_process_level"
                            data-show-subtext="true" data-live-search="true" readonly>
                            {!! $value->process_level !!}

                          </select>
                        </td>
                        <td class="proces">
                          <select name="bulk_process_name[]" id="bulk_process_name" class="select2 bulk_process_name"
                            data-show-subtext="true" data-live-search="true" readonly>
                            {!! $value->process_name !!}

                          </select>
                        </td>
                        <td class="proces">
                          <select name="bulk_machine_name[]" id="bulk_machine_name" class="select2 bulk_machine_name"
                            data-show-subtext="true" data-live-search="true" readonly>
                            {!! $value->machine_name !!}

                          </select>
                        </td>
                        <td>
                          <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments"
                            value="{{ $value->comments }}">
                        </td>
                        <td class="text-center">
                          <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-minus-circle"></i>
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr class="rcopy clone">
                      <td>
                        <input type="hidden" name="bulk_material_bom_line_id[]"
                          class="form-control input-sm bulk_material_bom_line_id" value="">

                        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1"
                          readonly="readonly">
                      </td>
                      <td class="pdtdiv">
                        <select name="bulk_component_product_id[]" id="bulk_component_product_id"
                          class="bulk_component_product_id select2 ">{!! $component_product_id !!}</select>
                      </td>
                      <!--    <td><i class="fa fa-search productsearch"></i></td> -->
                      <td class="uom none">
                        <select name="bulk_component_uom_code_id[]" id="bulk_component_uom_code_id"
                          class="select2 bulk_component_uom_code_id" data-show-subtext="true" data-live-search="true"
                          readonly>
                          {!! $component_uom_code_id !!}
                        </select>
                      </td>
                      <td>
                        <input type="text" name="bulk_component_qty[]"
                          class="form-control input-sm bulk_component_qty input_qty_width" value="">
                      </td>
                      <td class="proces">
                        <select name="bulk_process_level[]" id="bulk_process_level" class="select2 bulk_process_level"
                          data-show-subtext="true" data-live-search="true" readonly>
                          {!! $process_level !!}

                        </select>
                      </td>
                      <td class="proces">
                        <select name="bulk_process_name[]" id="bulk_process_name" class="select2 bulk_process_name"
                          data-show-subtext="true" data-live-search="true" readonly>
                          {!! $process_name !!}

                        </select>
                      </td>
                      <td class="proces macame">
                        <select name="bulk_machine_name[]" id="bulk_machine_name" class="select2 bulk_machine_name"
                          data-show-subtext="true" data-live-search="true" readonly>
                          {!! $machine_name !!}

                        </select>
                      </td>
                      <td>
                        <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" row="5"
                          value="">
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

              <div class="text-end">
                <button type="button" class="btn btn-success btn-sm add-row">
                  <i class="fas fa-plus-circle"></i> Add Row
                </button>
              </div>

            </div>
          </div>
        </div>



        <div class="row mt-4">
          <div class="col-lg-12 col-md-12">
            <div class="form-group text-center">
              <?php if ($return_url != 'materialbomapprovalcreate') { ?>
              <button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
              <a href="{{ URL::to('materialbom') }}" class='btn btn-danger px-4 me-2'>Cancel</a>
              <?php } else { ?>
              <button type="button" name="submit" class="btn btn-success px-4 me-2 saveform"
                value="APPROVED">Approve</button>
              <button type="button" name="submit" class="btn btn-danger px-4 me-2 saveform" value="REJECT">Reject</button>
              <a class='btn btn-outline-danger px-4'
                onclick="location.href = '{{URL::to('materialbomapproval')}}'">Cancel</a>
              <?php } ?>
            </div>
          </div>
        </div>


      </div>

      <!-- Product Details Modal -->
      <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content shadow-lg border-0">

            <!-- Modal Header -->
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title" id="productModalLabel">
                <i class="bi bi-box-seam"></i> Product Details
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
              <div class="table-responsive">
                <table id="productTable" class="table table-bordered table-striped" style="width:100%">
                  <thead>
                    <tr>
                      <th>Product Code</th>
                      <th>Product Group</th>
                      <th>Product Category</th>
                      <th>Product Name</th>
                      <th>Select</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle"></i> Close
              </button>
            </div>
          </div>
        </div>
      </div>
      <!--end-->
      <input type="hidden" class="pdtindex" value="" />

    </div>

  </form>



@endsection
@push('scripts')

  <script>

    /* purpose:to check duplicate entry for bom product*/

    var dup_chk = true;

    function duplicate_check() {
      var edit_id = $('#material_bom_hdr_id').val(); // FIXED selector
      var product = $('.assembly_product_id option:selected').text();
      var productid = $('.assembly_product_id').val();

      $.ajax({
        cache: false,
        url: "{{ URL::to('materialbomchk') }}",
        type: 'GET',
        dataType: 'json',
        async: false,
        data: {
          product_id: productid,
          edit_id: edit_id || ''   // ensure parameter is included
        },
        success: function (response) {
          if (response == 1) {

            $('.dup_name')
              .text('Product: ' + product + ' Already Exists')
              .removeClass('d-none')
              .addClass('d-block');   // show visibly

            $("#assembly_product_id").val('').trigger('change');
            dup_chk = false;
          } else {
            $('.dup_name').hide();
            dup_chk = true;
          }
        },
        error: function (xhr, resp, text) {
          console.log(xhr, resp, text);
        }
      });
    }



    /* purpose:qty 0 required validation*/

    function qtyrequiredvalid() {
      $('.bulk_component_qty').each(function (i) {
        var val = $(this).val();
        if (val == 0) {
          $('.bulk_component_qty' + i).val('');
        }
      });
    }

    $(document).ready(function () {
      /* purpose:to refresh jcombo*/
      var group = '<?php echo $group; ?>';
      var cat = '<?php echo $cate_semi; ?>';
      var groupid = " 1=1 and product_group_id in(" + group + "," + cat + ")";
      $(document).on('click', '.jcr_assembly_product_id', function () {
        $(".assembly_product_id").jCombo("{{ URL::to('jcomboform?table=m_products_t:product_id:product_code|concatenated_product') }}&parent=" + groupid + "&order_by=concatenated_product asc",
          { selected_value: "" });
        $('.uom_code_id').val('');
      });



      $('.bulk_component_uom_code_id').attr("readonly", true);


      /* purpose:to uppercase validation*/
      $(".bom_name").keyup(function () {
        $(this).val($(this).val().toUpperCase());
      });
      /*end*/

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


      /* purpose:qty validation*/
      $(document).on('keypress', '.bulk_component_qty', function (ev) {
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
          return true;
        }
        ev.preventDefault();
        return false;
      });



      $('.bulk_component_qty').bind("cut copy", function (e) {
        e.preventDefault();
      });


      /* purpose:based on process checkbox column should be display*/
      <?php if ($pagemode == 'create') { ?>
      $(".proces").hide();
      <?php } else { ?>
        /*purpose to find checkbox checked or not checked*/ if ($('input[type=checkbox]').prop('checked') == true) {
        $(".proces").show(); /*show columns*/
        $('.bulk_process_level,.bulk_process_name,.bulk_machine_name').attr('required', true);/*add required for fields*/
      } else {
        $(".proces").hide();
        $('.bulk_process_level,.bulk_process_name,.bulk_machine_name').attr('required', false);/*remove required for fields*/
      }
      <?php } ?>
      $(document).on('click', '.process', function () {
        if ($('input[type=checkbox]').prop('checked') == true) {
          $(".assembly_product_id").trigger('change');
          $(".proces").show();
          $('.bulk_process_level,.bulk_process_name,.bulk_machine_name').attr('required', true);
        } else {
          $(".proces").hide();
          $('.bulk_process_level,.bulk_process_name,.bulk_machine_name').attr('required', false);
        }
      });

      /* purpose:to call save function*/
      $(document).on('click', '.saveform', function () {
        var totcompqty = $('.totalcompqty').val();

        if (totcompqty > 1) {
          showCustomAlert("Component Qty should not be more than 1..", "error");
          $('.bulk_component_qty').val('');
        } else {
          var btnval = $(this).val();

          if (btnval == 'APPLYCHANGES') {
            var savestatus = 'APPLY CHANGES';
          }

          else if (btnval == 'DRAFT') {
            var savestatus = 'DRAFT';
          }


          else if (btnval == 'REJECT') {
            var savestatus = 'REJECTED';

          }
          else if (btnval == 'APPROVED') {
            var savestatus = 'APPROVED';

          }
          else {
            var savestatus = 'INITIATED';
          }

          $('#savestatus').val(savestatus);

          var url = "{{ URL::to('materialbomsave') }}";
          var red_url = "{{ URL::to('materialbom') }}";
          var create_url = "{{ URL::to('materialbomcreate') }}";
          qtyrequiredvalid();/* to call qty function*/
          var formdata = $('#materialbom').serialize();
          var form = $('#materialbom');
          duplicate_check();

          form.parsley().validate();
          var form = $('#materialbom');
          form.parsley().validate();

          if (form.parsley().isValid()) {
            if (dup_chk == true) {
              var $btn = $(this);
              $btn.prop('disabled', true);
              $.post(url, formdata, function (data) {
                var status = data.status;
                var msg = data.message;
                var id = data.id;
                var edit_url = "{{ URL::to('materialbomedit') }}/" + id;
                if (btnval != 'SAVE' && btnval != 'DRAFT' && btnval != "APPROVED" && btnval != "REJECT") {
                  showCustomAlert(msg, status);
                  setTimeout(function () {
                    window.location.href = create_url;
                  }, 1500);
                }
                else if (btnval == "APPROVED" || btnval == "REJECT") {
                  showCustomAlert(msg, status);
                  setTimeout(function () {
                    window.location.href = "{{URL::to('materialbomapproval')}}";
                  }, 1500);

                }
                else {
                  showCustomAlert(msg, status);
                  setTimeout(function () {
                    window.location.href = red_url;
                  }, 1500);
                }
              });
            } return false;
          }

        }
      });




      /* purpose:load uom based on product*/
      $(document).on('change', '.assembly_product_id', function () {
        var product_id = $(this).val();
        if (product_id != "") {
          var url = "{{ URL::to('productuomdetails') }}/" + product_id;
          $.get(url, function (data) {
            var data = $.trim(data);
            $('.uom_code_id').val(data).trigger('change');
          });
        }
        $('.dup_name').hide();
      });

      /* purpose:to check product already selected*/
      // Component product change -> set UOM in the same row, prevent duplicates across rows
      $(document).on('change', '.bulk_component_product_id', function () {
        const $row = $(this).closest('tr');
        const product_id = $(this).val();

        if (!product_id) {
          // Clear dependent field in this row if product cleared
          $row.find('.bulk_component_uom_code_id').val(null).trigger('change.select2');
          return;
        }

        // Duplicate check across other rows
        let duplicate = false;
        $('.bulk_component_product_id').not(this).each(function () {
          if ($(this).val() == product_id) {
            duplicate = true;
            return false; // break
          }
        });

        if (duplicate) {
          const msg = $row.find('.bulk_component_product_id option:selected').text() || 'This';
          const message = `${msg} Product Already Selected`;
          showCustomAlert(message, 'info');

          // reset current select (keep select2 in sync)
          $(this).val(null).trigger('change.select2');
          return;
        }

        // Load UOM for the selected component product and set it in the same row
        const url = "{{ URL::to('workorderuom') }}/" + encodeURIComponent(product_id);
        $.get(url, function (data) {
          const uomVal = $.trim(data || '');
          $row.find('.bulk_component_uom_code_id')
            .val(uomVal)
            .trigger('change.select2'); // if this is a select2
        });
      });


      /* purpose:load machine based on product type*/
      $(".assembly_product_id").change(function () {

        var product_id = $(this).val();
        if (product_id != "") {
          if ($('input[type=checkbox]').prop('checked') == true) {
            var url1 = "{{ URL::to('getprdtype') }}/" + product_id;
            $.get(url1, function (data) {
              var data = $.trim(data);
              if (data != "") {
                $(".bulk_machine_name").jCombo("{{ URL::to('jcomboformcomp?table=w_machine_hdr_t:machine_hdr_id:machine_name') }}&parent=machine_hdr_id in (" + data + ")" + "&order_by=machine_name asc",
                  { selected_value: "" });
                $('.bulk_machine_name').val(data).trigger('change');
                $(".macame").css("pointer-events", "auto");
              } else {
                showCustomAlert('Please Create Machine Based on Production Product Type', 'info');
                $(".macame").css("pointer-events", "none");
              }
            });
          }
        }
      });


      /* purpose:product search popup*/
      $(document).on('click', '.productsearch', function () {
        var index = ($(this).closest('tr').index());
        $('.pdtindex').val(index);
        $('#productModal').modal('show');
        $('#productModal').width("100%");
      });


      var productTable = $('#productTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('getProductgridData') }}",
          data: function (d) {
            d.group_name = $('#filter_group').val();
            d.category_name = $('#filter_category').val();
            d.product_name = $('#filter_name').val();
          }
        },
        columns: [
          { data: 'product_code', name: 'product_code' },
          { data: 'group_name', name: 'group_name' },
          { data: 'category_name', name: 'category_name' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data) {
              return `<button class="btn btn-success btn-sm select-product"
                        data-id="${data.product_id}"
                        data-name="${data.concatenated_product}">
                        Select
                      </button>`;
            }
          }
        ],
        pageLength: 10
      });

      // Refresh button
      $(".refreshprd").off('click').on('click', function () {
        var quote_pricelist_id = $('.quote_pricelist_id').val();
        $(".bulk_product_id").each(function (i) {
          if (i !== 0) $(this).closest('tr').remove();
        });

        if (quote_pricelist_id) {
          $.get("{{URL::to('getpriceproduct')}}/" + quote_pricelist_id + '/0', function (data) {
            if ($.trim(data) === '<option value="">-- Please Select --</option>') {
              showCustomAlert("No Product for these Pricelist", 'info');
            }
            $('.bulk_product_id').html(data);
          });
        } else {
          showCustomAlert("Please select a Pricelist", 'info');
        }
      });

      // Row click to select product
      $('#productgrid tbody').off('click').on('click', 'tr', function () {
        var data = table.row(this).data();
        var index = $('.pdtindex').val();
        var product_id = data.product_id;

        if (product_id) {
          var pdtcount = pdtcheck(product_id, index);
          if (pdtcount <= 0) {
            $('.bulk_product_id' + index).val(product_id).trigger('change');
            $('#productModal').modal('hide');
          } else {
            var msg = data.concatenated_product;
            var message = msg + ' Product Already Selected';
            showCustomAlert(message, 'warning');
            rowdataEmpty(index);
            $('#productModal').modal('hide');
          }
        } else {
          showCustomAlert('Please Select a row');
        }
      });



    });

    // Add Row
$(document).on('click', '.add-row', function () {
  const $tbody = $('.clone_lines_body');
  const $lastRow = $tbody.find('tr:last');

  // 1) destroy select2 on last row BEFORE cloning (prevents cloning select2 artifacts)
  $lastRow.find('select.select2').each(function () {
    if ($(this).hasClass('select2-hidden-accessible')) {
      $(this).select2('destroy');
    }
    $(this).removeAttr('data-select2-id');
    $(this).next('.select2').remove();
  });

  // 2) clone a clean row
  const $newRow = $lastRow.clone(false, false);

  // 3) clear values in new row
  $newRow.find('input').val('');
  $newRow.find('select').val('');

  // if you have hidden id field, keep it empty
  $newRow.find('.bulk_material_bom_line_id').val('');

  // 4) set options (component list) for new row
  $newRow.find('.bulk_component_product_id')
    .html($('#component_product_master').html())
    .val('');

  // 5) append new row
  $tbody.append($newRow);

  // 6) re-init select2 for BOTH rows (lastRow and newRow)
  $lastRow.find('select.select2').select2({ width: '100%' });
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