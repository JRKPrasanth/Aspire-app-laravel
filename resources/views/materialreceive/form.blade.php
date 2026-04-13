@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Material Acknowledgement</h3>
  @include('layouts.breadcrumb')

  <style>
    .uom_code_id,
    .batch_no,
    .bulk_uom_code_id,
    .product_id,
    .job_qty,
    .mtl_receive_date,
    .w_jobs_hdr_id,
    .bulk_qoh,
    .bulk_qty,
    .uom,
    .bulk_issue_qty,
    .bulk_mtl_issue_qty,
    .defremove,
    .pdtdiv {
      pointer-events: none;
    }

    .plevel {
      pointer-events: none;
    }
  </style>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold"></div>
    <div class="card-body card-block headerdiv1">
      <form method="post" action="" id="materialreceive" data-parsley-validate>
        <input type="hidden" value="" name="savestatus" id="savestatus" />

        {{ csrf_field() }}


        <div class="row g-4">
          <!-- Left Side -->
          <div class="col-md-6">

            <input type="hidden" name="source" id="source" class="source" value="{{ $source }}">
            <input type="hidden" name="w_materialreceive_hdr_id" class="w_materialreceive_hdr_id"
              id="w_materialreceive_hdr_id" value="{{ $w_materialreceive_hdr_id }}">
            <input type="hidden" name="process" class="process" id="process" value="">

            <!-- Product -->
            <div class="mb-3 row">
              <label for="product_id" class="col-sm-4 col-form-label">Product</label>
              <div class="col-sm-8">
                <select name="product_id" id="product_id" class="form-select product_id select2">
                  {!! $ass_product_id !!}
                </select>
              </div>
            </div>

            <!-- Job No -->
            <div class="mb-3 row">
              <label for="w_jobs_hdr_id" class="col-sm-4 col-form-label">Job No</label>
              <div class="col-sm-8">
                <select name="w_jobs_hdr_id" id="w_jobs_hdr_id" class="form-select w_jobs_hdr_id select2"
                  data-live-search="true">
                  {!! $w_jobs_hdr_id !!}
                </select>
              </div>
            </div>

            <!-- Batch No -->
            <div class="mb-3 row">
              <label for="batch_no" class="col-sm-4 col-form-label">Batch No</label>
              <div class="col-sm-8">
                <input type="text" name="batch_no" id="batch_no" class="form-control batch_no" value="{{ $batch_no }}">
              </div>
            </div>

            <!-- Material Receive Date -->
            <div class="mb-3 row">
              <label for="mtl_receive_date" class="col-sm-4 col-form-label">Material Receive Date</label>
              <div class="col-sm-8">
                <div class="input-group">
                  <input type="text" id="mtl_receive_date" name="mtl_receive_date"
                    class="form-control datepicker mtl_receive_date" value="{{ $mtl_receive_date }}">

                </div>
              </div>
            </div>

          </div>

          <!-- Right Side -->
          <div class="col-md-6">

            <!-- Uom Code -->
            <div class="mb-3 row">
              <label for="uom_code_id" class="col-sm-4 col-form-label">Uom Code</label>
              <div class="col-sm-8">
                <select name="uom_code_id" id="uom_code_id" class="form-select uom_code_id select2"
                  data-live-search="true">
                  {!! $uomcode_id !!}
                </select>
              </div>
            </div>

            <!-- Job Qty -->
            <div class="mb-3 row">
              <label for="job_qty" class="col-sm-4 col-form-label">Job Qty</label>
              <div class="col-sm-8">
                <input type="text" name="job_qty" id="job_qty" class="form-control job_qty" value="{{ $job_qty }}">
              </div>
            </div>

            <!-- Job Status -->
            <div class="mb-3 row">
              <label for="job_status" class="col-sm-4 col-form-label">Job Status</label>
              <div class="col-sm-8">
                <input type="text" name="job_status" id="job_status" class="form-control job_status"
                  value="{{ $job_status }}" readonly>
              </div>
            </div>

            <!-- Remarks -->
            <div class="mb-3 row">
              <label for="remarks" class="col-sm-4 col-form-label">Remarks</label>
              <div class="col-sm-8">
                <input type="text" name="remarks" id="remarks" class="form-control remarks" value="{{ $remarks }}">
              </div>
            </div>

          </div>
        </div>



        <!--*****************************-Linedata ***************************-->

        <div class="row mt-4">
          <div class="col-12 linetable">
            <div id="preview-area" class="table-responsive">
              <table class="table table-bordered clone_table" style="width:150%">
                <thead class="table-light">
                  <tr>
                    <th>Line No</th>
                    <th class="freeze">Product </th>
                    <th>Component Uom</th>
                    <?php if ($source != "MATERIALRECEIVE") { ?>
                    <th>Process Level</th>
                    <th>Process Name</th>
                    <?php } ?>
                    <th>Component Qty</th>
                    <th>Needed Qty</th>
                    <th>Issue Qty</th>
                    <th>Receive Qty</th>
                    <th>Add Receive Qty</th>
                    <th>Comments</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody class="clone_lines_body">
                  @if(count($linedata) > 0)
                    @foreach($linedata as $key => $value)
                      <tr class="line-row">
                        <td>
                          <input type="hidden" name="bulk_w_materialreceive_line_id[]"
                            class="form-control input-sm bulk_w_materialissue_line_id"
                            value="{{$value->w_materialreceive_line_id}}">
                          <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no"
                            value="{{ $key + 1 }}" readonly="readonly">
                        </td>
                        <td class="pdtdiv freeze">
                          <select name="bulk_product_id[]" id="bulk_product_id" class="bulk_product_id form-control  select2 "
                            required="required">{!! $value->product_id !!}</select>
                        </td>

                        <td class="uom">
                          <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                            class="form-control bulk_uom_code_id select2" readonly>
                            <option value=''> {!! $value->uom_code_id !!} </option>

                          </select>
                        </td>
                        <?php    if ($source != "MATERIALRECEIVE") { ?>

                        <td class="plevel">
                          <select name="bulk_process_level[]" id="bulk_process_level"
                            class="form-control bulk_process_level select2">
                            {!! $value->process_level !!}

                          </select>
                        </td>
                        <td style="pointer-events:none;">
                          <select name="bulk_process_name[]" id="bulk_process_name"
                            class="form-control bulk_process_name select2">
                            {!! $value->process_name !!}

                          </select>
                        </td>
                        <?php    } ?>
                        <td>
                          <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width"
                            value="{{ $value->qty }}">
                        </td>

                        <td>
                          <input type="text" name="bulk_issue_qty[]"
                            class="form-control input-sm bulk_issue_qty input_qty_width" value="{{ $value->issue_qty }}"
                            required="required">
                        </td>

                        <td>
                          <input type="text" name="bulk_mtl_issue_qty[]"
                            class="form-control input-sm bulk_mtl_issue_qty input_qty_width"
                            value="{{ $value->mtl_issue_qty}}" required="required">
                        </td>

                        <td>
                          <input type="text" name="bulk_receive_qty[]"
                            class="form-control input-sm bulk_receive_qty input_qty_width" value="" required readonly>
                        </td>

                        <td> <a href="#" class="subinvdetails" title="Add Receive Qty"> <i class="fa fa-plus"></i></a></td>
                        <input type="hidden" name="bulk_subinventory_id[]" class="form-control bulk_subinventory_id" value="">
                        <input type="hidden" name="bulk_locator_id[]" class="form-control bulk_locator_id" value="">
                        <input type="hidden" name="bulk_issueqty[]" class="form-control bulk_issueqty" value="">
                        <input type="hidden" name="bulk_batchnumber[]" class="form-control bulk_batchnumber" value="">
                        <input type="hidden" name="bulk_receiveqty[]" class="form-control bulk_receiveqty" value="">
                        <input type="hidden" name="bulk_reference_source_hdr_id[]"
                          class="form-control bulk_reference_source_hdr_id" value="{{ $value->reference_source_hdr_id}}">
                        <input type="hidden" name="bulk_reference_source_line_id[]"
                          class="form-control bulk_reference_source_line_id" value="{{ $value->reference_source_line_id}}">
                        <input type="hidden" name="bulk_reference_source[]" class="form-control bulk_reference_source"
                          value="{{ $value->reference_source}}">

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
                    <tr class="line-row">
                      <td>
                        <input type="hidden" name="bulk_w_materialreceive_line_id[]"
                          class="form-control input-sm bulk_w_materialreceive_line_id" value="">
                        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value=""
                          readonly="readonly">
                      </td>
                      <td class="pdtdiv ">
                        <select name="bulk_product_id[]" id="bulk_product_id" class="form-control select2 bulk_product_id  "
                          required="required">{!! $product_id !!}</select>
                      </td>

                      <td class="uom">
                        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                          class="form-control bulk_uom_code_id select2">
                          <option value=''> {!! $uom_code_id !!} </option>

                        </select>
                      </td>
                      <?php  if ($source != "MATERIALRECEIVE") { ?>

                      <td style="pointer-events:none;">
                        <select name="bulk_process_level[]" id="bulk_process_level"
                          class="form-control bulk_process_level select2">
                          {!! $process_level !!}

                        </select>
                      </td>
                      <td style="pointer-events:none;">
                        <select name="bulk_process_name[]" id="bulk_process_name"
                          class="form-control bulk_process_name select2">
                          {!! $process_name !!}

                        </select>
                      </td>
                      <?php  } ?>
                      <td>
                        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width"
                          value=" ">
                      </td>
                      <td>
                        <input type="text" name="bulk_issue_qty[]"
                          class="form-control input-sm bulk_issue_qty input_qty_width" value=" ">
                      </td>
                      <td>
                        <input type="text" name="bulk_mtl_issue_qty[]"
                          class="form-control input-sm bulk_mtl_issue_qty input_qty_width" value="" readonly>
                      </td>
                      <td>
                        <input type="text" name="bulk_receive_qty[]"
                          class="form-control input-sm bulk_receive_qty input_qty_width" value="" required readonly>
                      </td>
                      <td> <a href="#" class="subinvdetails" title="Add Receive Qty"> <i class="fa fa-plus"></i></a></td>
                      <input type="hidden" name="bulk_subinventory_id[]" class="form-control bulk_subinventory_id" value="">
                      <input type="hidden" name="bulk_locator_id[]" class="form-control bulk_locator_id" value="">
                      <input type="hidden" name="bulk_issueqty[]" class="form-control bulk_issueqty" value="">
                      <input type="hidden" name="bulk_batchnumber[]" class="form-control bulk_batchnumber" value="">
                      <input type="hidden" name="bulk_receiveqty[]" class="form-control bulk_receiveqty" value="">
                      <input type="hidden" name="bulk_reference_source_hdr_id[]"
                        class="form-control bulk_reference_source_hdr_id" value="">
                      <input type="hidden" name="bulk_reference_source_line_id[]"
                        class="form-control bulk_reference_source_line_id" value="">
                      <input type="hidden" name="bulk_reference_source[]" class="form-control bulk_reference_source"
                        value="">
                      <td>
                        <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value=" ">
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

              <?php if ($source != "MATERIALRECEIVE") { ?>
              <div class="text-end">
                <button type="button" class="btn btn-success btn-sm add-row">
                  <i class="fas fa-plus-circle"></i> Add Row
                </button>
              </div>
              <?php } ?>
            </div>

          </div>
        </div>



        <div class="row mt-4 mb-3">
          <div class="col-lg-12 col-md-12">
            <div class="form-group text-center">

              <button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Material Receive</button>
              <a href="{{ url($pageurl) }}" class='btn btn-danger px-4'>Cancel</a>

            </div>
          </div>
        </div>




        <input type="hidden" name="qohcnt" class="qohcnt" value="">

        <!-- Subinventory Details Modal -->
        <div class="modal fade" id="subinvdetailsModal" tabindex="-1" aria-labelledby="subinvdetailsLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-3">

              <!-- Modal Header -->
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="subinvdetailsLabel">Subinventory Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                  aria-label="Close"></button>
                <input type="hidden" class="soindex" value="">
              </div>

              <!-- Modal Body -->
              <div class="modal-body sodetail p-4">
                <!-- Dynamic content will be loaded here -->
              </div>

              <!-- Add Button (Centered) -->
              <div class="text-center mb-3">
                <button type="button" class="btn btn-success px-4 py-2 rounded-pill shadow-sm mtlisqty" id="mtlisqty">
                  <i class="bi bi-plus-circle me-2"></i> Add Material Receive Qty
                </button>
              </div>

              <!-- Modal Footer -->
              <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
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

    var com_qoh = true;
    function qoh_empty() {
      $('.bulk_qoh').each(function (i, v) {
        var com_qoh_qty = $('.bulk_qoh' + i).val();
        if (com_qoh_qty == 0 || com_qoh_qty == '') {
          com_qoh = false;
        } else {
          com_qoh = true;
        }
      });
    }

    /* purpose:check fg product for packing process*/
    var prd = 1;
    function checkfgproduct() {
      $('.bulk_product_id').each(function (i, v) {
        var bulkproduct = $('.bulk_product_id' + i).val();
        var hdrprd = $('.product_id').val();
        if (bulkproduct != hdrprd) {
          prd = 0;
        } else {
          prd = 1;
        }
      });
    }

    $(document).ready(function () {

      $(document).on('change', '.mtlqty', function (ev) {
        var index = $(this).closest('tr').index();
        var mtlqty = parseFloat($('.mtlqty' + index).val());
        var qoh = parseFloat($('.qoh' + index).val());
        if (mtlqty > qoh) {

          showCustomAlert("Qty Should not be greater Than Qoh", 'error');
          $('.mtlqty' + index).val('');
        }
      });


      $('.subinvdetails').click(function () {
        var index = $(this).closest('tr').index();
        var prd = $('.bulk_product_id' + index).val();
        if (prd != "") {
          $('#subinvdetailsModal').modal('show');
          $('.modal-dialog').width("70%");
        } else {
          $('#subinvdetailsModal').modal('hide');
          showCustomAlert('Please Choose Product', 'info');
        }
        $('.soindex').val(index);

      });



      // Open modal and load details
      $('#subinvdetailsModal').on('shown.bs.modal', function () {
        const $modal = $(this);

        const index = Number($('.soindex').val() || 0);
        const $srcRow = $('.clone_lines_body .line-row').eq(index);

        const product = $srcRow.find('.bulk_product_id').val();
        const mtlisqty = $srcRow.find('.bulk_mtl_issue_qty').val();
        const refsrcline = $srcRow.find('.bulk_reference_source_line_id').val() || 0;
        const refsrc = $srcRow.find('.bulk_reference_source').val() || '';
        const jobid = $('.w_jobs_hdr_id').val();

        // choose src flag
        const srcFlag = (refsrcline && refsrcline != 0) ? 'mtlissue' : 'mtlreceive';
        const src1 = refsrc;

        // stash source row ref & meta on the modal for later handlers
        $modal.data({ index, $srcRow, product });

        $.get(
          "{{ URL::to('prdsubinventoryreceivedetails') }}/" + index + "/" + product
          + "?refsrcline=" + encodeURIComponent(refsrcline)
          + "&src=" + encodeURIComponent(srcFlag)
          + "&jobid=" + encodeURIComponent(jobid)
          + "&mtlisqty=" + encodeURIComponent(mtlisqty || '')
          + "&src1=" + encodeURIComponent(src1),
          function (html) {
            $modal.find('.sodetail').html(html);

            // Prefill from source row (comma lists); guard empties
            const subinvStr = ($srcRow.find('.bulk_subinventory_id').val() || '').toString();
            const batchStr = ($srcRow.find('.bulk_batchnumber').val() || '').toString();
            const locStr = ($srcRow.find('.bulk_locator_id').val() || '').toString();
            const issStr = ($srcRow.find('.bulk_issueqty').val() || '').toString();
            const rcvStr = ($srcRow.find('.bulk_receiveqty').val() || '').toString();

            const subinvArr = subinvStr ? subinvStr.split(',') : [];
            const batchArr = batchStr ? batchStr.split(',') : [];
            const locArr = locStr ? locStr.split(',') : [];
            const issArr = issStr ? issStr.split(',') : [];
            const rcvArr = rcvStr ? rcvStr.split(',') : [];

            // ensure we have enough rows in the modal table
            const $tbody = $modal.find('tbody');
            const need = Math.max(subinvArr.length, batchArr.length, locArr.length, issArr.length, rcvArr.length);
            for (let k = 1; k < need; k++) {
              $modal.find('.add_row1').trigger('click');
            }

            // Fill rows (no index-suffixed classes!)
            for (let k = 0; k < need; k++) {
              const $r = $tbody.find('tr').eq(k);
              if (!$r.length) break;

              $r.find('.subinventory_id').val(subinvArr[k] || '').trigger('change.select2');
              $r.find('.batch_number').val(batchArr[k] || '').trigger('change.select2');
              $r.find('.locator_id').val(locArr[k] || '').trigger('change.select2');
              $r.find('.mtlqty').val(issArr[k] || '');
              $r.find('.receiveqty').val(rcvArr[k] || '');
            }
          }
        );
      });

      // Save button inside modal → collect & push back to source line
      $('#subinvdetailsModal').off('click', '.mtlisqty').on('click', '.mtlisqty', function () {
        const $modal = $('#subinvdetailsModal');
        const $tbody = $modal.find('tbody');
        const $srcRow = $modal.data('$srcRow');

        let ok = true, total = 0;
        const subArr = [], locArr = [], batArr = [], issArr = [], rcvArr = [];

        $tbody.find('tr').each(function () {
          const $r = $(this);
          const sub = $r.find('.subinventory_id').val() || '';
          const bat = $r.find('.batch_number').val() || '';
          const loc = $r.find('.locator_id').val() || '';
          const iss = parseFloat($r.find('.mtlqty').val() || '0');
          const rcv = parseFloat($r.find('.receiveqty').val() || '0');

          const any = (sub || bat || loc || iss || rcv);
          if (any) {
            // require all fields if any filled
            if (!sub || !bat || !loc || !iss || !rcv) ok = false;
            subArr.push(sub);
            locArr.push(loc);
            batArr.push(bat);
            issArr.push(iss);
            rcvArr.push(rcv);
            total += rcv;
          }
        });

        if (!ok) {
          showCustomAlert('Please Enter all Fields', 'error');
          return;
        }

        // Push back to source line (commas)
        $srcRow.find('.bulk_receive_qty').val(total);         // total received
        $srcRow.find('.bulk_receiveqty').val(rcvArr.join(','));
        $srcRow.find('.bulk_locator_id').val(locArr.join(','));
        $srcRow.find('.bulk_subinventory_id').val(subArr.join(','));
        $srcRow.find('.bulk_issueqty').val(issArr.join(','));
        $srcRow.find('.bulk_batchnumber').val(batArr.join(','));

        $('#subinvdetailsModal').modal('hide');
      });

      // Modal row: received qty must be <= issued qty (row-scoped)
      $('#subinvdetailsModal').on('keyup change', '.receiveqty', function () {
        const $row = $(this).closest('tr');
        const mqty = parseFloat($row.find('.mtlqty').val() || '0');
        const rqty = parseFloat($(this).val() || '0');

        if (rqty > mqty) {
          showCustomAlert('Received Qty should not be greater than issued qty', 'error');
          $(this).val('');
        }
      });

      // Modal row: batch → load subinventories for THIS modal row
      $('#subinvdetailsModal').on('change', '.batch_number', function () {
        const $modal = $('#subinvdetailsModal');
        const $row = $(this).closest('tr');
        const batch = $(this).val();
        const product = $modal.data('product');
        const source = $('.source').val();

        const $subinv = $row.find('.subinventory_id');
        const $locator = $row.find('.locator_id');
        const $qoh = $row.find('.qoh');

        if (!batch || !product) {
          $subinv.html('<option value="">-- Select Subinventory --</option>').trigger('change.select2');
          $locator.html('<option value="">-- Select Locator --</option>').trigger('change.select2');
          $qoh.val('');
          return;
        }

        const url = "{{ URL::to('prdstockdata') }}/" + encodeURIComponent(product)
          + "?batch=" + encodeURIComponent(batch)
          + "&source=" + encodeURIComponent(source || '');

        $.get(url, function (resp) {
          const inv = resp && resp.inventory ? resp.inventory : '';

          if (inv) {
            const condition = 'subinventory_id in(' + inv + ')';
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

            $locator.html('<option value="">-- Select Locator --</option>').trigger('change.select2');
            $qoh.val('');
          } else {
            $subinv.html('<option value="">-- Select Subinventory --</option>').trigger('change.select2');
            $locator.html('<option value="">-- Select Locator --</option>').trigger('change.select2');
            $qoh.val('');
          }
        });
      });

      // Modal row: subinventory → load locators + set QOH
      $('#subinvdetailsModal').on('change', '.subinventory_id', function () {
        const $modal = $('#subinvdetailsModal');
        const $row = $(this).closest('tr');

        const subid = $(this).val();
        const product = $modal.data('product');
        const batch = $row.find('.batch_number').val();
        const source = $('.source').val();

        const $locator = $row.find('.locator_id');
        const $qoh = $row.find('.qoh');

        if (!subid || !product || !batch) {
          $locator.html('<option value="">-- Select Locator --</option>').trigger('change.select2');
          $qoh.val('');
          return;
        }

        const url = "{{ URL::to('prdstocksubinvdata') }}/" + encodeURIComponent(product)
          + "?batch=" + encodeURIComponent(batch)
          + "&source=" + encodeURIComponent(source || '')
          + "&subid=" + encodeURIComponent(subid);

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

      // Modal row: locator → refresh QOH
      $('#subinvdetailsModal').on('change', '.locator_id', function () {
        const $modal = $('#subinvdetailsModal');
        const $row = $(this).closest('tr');

        const subloc = $(this).val();
        const subinv = $row.find('.subinventory_id').val();
        const product = $modal.data('product');
        const batch = $row.find('.batch_number').val();
        const source = $('.source').val();

        const $qoh = $row.find('.qoh');

        if (!subloc || !subinv || !product || !batch) {
          $qoh.val('');
          return;
        }

        const url = "{{ URL::to('prdstocksublocdata') }}/" + encodeURIComponent(product)
          + "?batch=" + encodeURIComponent(batch)
          + "&source=" + encodeURIComponent(source || '')
          + "&subid=" + encodeURIComponent(subinv)
          + "&sublocid=" + encodeURIComponent(subloc);

        $.get(url, function (resp) {
          $qoh.val(resp && resp.qty ? resp.qty : '');
        });
      });


      /* purpose:qty validation*/
      $(document).on('keypress', '.receiveqty', function (ev) {
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
          return true;
        }
        ev.preventDefault();
        return false;
      });



      $('.bulk_process_level').each(function (i, v) {
        var pl = $(this).val();
        if (pl == "") {
          $('.plevel' + i).css("pointer-events", "auto");
        } else {
          $('.plevel' + i).css("pointer-events", "none");
        }
      });
      $('.bulk_process_level').change(function () {
        var level = $(this).val();
        var index = $(this).closest('tr').index();
        var productid = $('.product_id').val();
        if (level != "") {
          $.get("{{URL::to('prsdetails')}}/" + productid + "?level=" + level, function (data) {
            $('.bulk_process_name' + index).val(data['pname']).trigger('change');

          });
        }
      });

      $(document).on('change', '.bulk_product_id', function () {
        var product_id = $(this).val();
        var product = $('.product_id').val();
        var index = ($(this).closest('tr').index());
        if (product_id != '') {
          var pdtcount = pdtcheck(product_id, index);
          if (pdtcount <= 0) {

            var url = "{{ URL::to('prduom') }}/" + product_id;
            var url1 = "{{URL::to('getprcsdetails')}}/" + product_id + "/" + product;
            $.get(url1, function (data) {

              if (data != 0) {
                $('.bulk_process_level' + index).val(data.process_level).trigger('change');
                $('.bulk_process_name' + index).val(data.process_name).trigger('change');
                $('.bulk_reference_source' + index).val('PACKINGMATERIALRECEIVE');
              }
            });
            $.get(url, function (data) {


              $('.bulk_uom_code_id' + index).val(data.uomcode).trigger('change');

              $('.bulk_qoh' + index).val(data.qoh);
              $('.bulk_subinventory_id' + index).val(data.sub);
              $('.bulk_locator_id' + index).val(data.subloc).change();

            });

          }
          else {
            var msg = $(".bulk_product_id" + index + ' option:selected').text();
            var message = 'Product Already Selected';
            showCustomAlert(message, 'warning');
            $(".bulk_product_id" + index).val('').change();
            event.preventDefault();
          }
        }
      });



      $('#savestatus').val('');

      $(document).on('keyup', '.bulk_issue_qty', function () {
        var index = $(this).closest('tr').index();
        var qty = $(this).val();
        var qoh = $('.bulk_qoh' + index).val();

        if (Number(qty) > Number(qoh)) {
          showCustomAlert("Issue qty is More Than Qoh ", 'info');
          $('.bulk_issue_qty' + index).val("");
        }

      });


      $('.bulk_qoh').each(function (i, v) {
        var qoh = $(this).val();
        if (qoh == 0) {
          $('.qohcnt').val('1');
        }
      });

      var pagemode = "<?php echo $pagemode;?>";
      if (pagemode == "edit") {
        $('.bulk_component_qty').trigger('change');
      }


      $(document).on('click', '.saveform', function () {

        var btnval = $(this).val();
        if (btnval == 'APPLYCHANGES')
          var savestatus = 'APPLY CHANGES';
        else if (btnval == 'SAVE' || btnval == 'SAVENEW')
          var savestatus = 'SAVE';

        $('#savestatus').val(savestatus);

        var url = "{{ URL::to('materialreceivesave') }}";
        var red_url = "{{ URL::to($pageurl) }}";

        var formdata = $('#materialreceive').serialize();
        var form = $('#materialreceive');
        form.parsley().validate();
          var $btn = $(this);            
          $btn.prop('disabled', true);
        <?php if ($pageurl == "packingmaterialreceive" && $process != 'PROCESS-1') { ?>

        form.parsley().destroy();

        var form = $('#materialreceive');
        form.parsley().validate();
        qtyrequired();

        var qohcount = $('.qohcnt').val();
        if (form.parsley().isValid()) {

          if (prd == 1) {

            $.post(url, formdata, function (data) {
              var status = data.status;
              var msg = data.message;
              var id = data.id;

              if (btnval != 'SAVE' && btnval != 'DRAFT') {

                showCustomAlert(msg, status);
                setTimeout(function () {
                  window.location.href = create_url;
                }, 1500);
              }
              else {
                showCustomAlert(msg, "success");
                setTimeout(function () {
                  window.location.href = red_url;
                }, 1500);
              }
            });
          } else {

            showCustomAlert("Please select Fg Product in lines", 'warning');
          }
        }


        <?php } else { ?>

        form.parsley().destroy();
        var form = $('#materialreceive');
        form.parsley().validate();
        qtyrequired();
        var qohcount = $('.qohcnt').val();
        if (form.parsley().isValid()) {
          $.post(url, formdata, function (data) {
            var status = data.status;
            var msg = data.message;
            var id = data.id;

            if (btnval != 'SAVE' && btnval != 'DRAFT') {

              showCustomAlert(msg, status);
              setTimeout(function () {
                window.location.href = create_url;
              }, 1500);
            }
            else {
              showCustomAlert(msg, "success");
              setTimeout(function () {
                window.location.href = red_url;
              }, 1500);
            }
          });
        }

        <?php } ?>

      });


      $('.subinvdetails').each(function (index) {


        var $row = $('.clone_lines_body .line-row').eq(index);

        var product = $row.find('.bulk_product_id').val();
        var refsrcline = $row.find('.bulk_reference_source_line_id').val();
        var refsrc = $row.find('.bulk_reference_source').val();

        var src, src1;
        if (refsrcline != "" && refsrcline != 0) {
          refsrcline = refsrcline;
          src = "mtlissue";
          src1 = refsrc;
        } else {
          refsrcline = 0;
          src = "mtlreceive";
          src1 = refsrc;
        }
        var jobid = $('.w_jobs_hdr_id').val();

        $.get("{{URL::to('prdsubinventoryreceivedetails') }}/" + index + "/" + product + "?refsrcline=" + refsrcline + "&src=" + src + "&jobid=" + jobid + "&src1=" + src1, function (data) {
          $('.sodetail').html(data);
          var subinv = $('.bulk_subinventory_id' + index).val();
          var batchno = $('.bulk_batchnumber' + index).val();
          var loc = $('.bulk_locator_id' + index).val();
          var issqty = $('.bulk_issueqty' + index).val();
          var rcvqty = $('.bulk_receiveqty' + index).val();

          if (subinv != "") {
            setTimeout(function () {
              var subinventory = [];
              var batch = [];
              var locator = [];
              var issqty1 = [];
              var rcvqty1 = [];
              subinventory = subinv.split(',');
              batch = batchno.split(',');
              locator = loc.split(',');
              issqty1 = issqty.split(',');
              rcvqty1 = rcvqty.split(',');

              $.each(subinventory, function (key, val) {
                $(".subinventory_id" + key).val(val).change();
                $(".batch_number" + key).val(batch[key]).change();
                $(".locator_id" + key).val(locator[key]).change();
                $(".mtlqty" + key).val(issqty1[key]);
                $(".receiveqty" + key).val(rcvqty1[key]);

              });

            }, 5000);
          }
          $('.soindex').val(index);
          $('.mtlisqty').trigger('click');

        });

      });

    });

    /* purpose:qty validation for required*/
    function qtyrequired() {
      $(".bulk_receive_qty").each(function (index) {
        var req = $(this).val();
        if (req == 0) {
          $(".bulk_receive_qty" + index).val('');

        }
      });
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