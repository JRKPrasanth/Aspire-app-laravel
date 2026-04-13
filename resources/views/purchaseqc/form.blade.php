@extends('layouts.header')
@section('content')
    <?php
    error_reporting(0);

    if ($row->qc_header_id == '') {
        $head = "Quality Check";
    } else {
        $head = " Quality Check Approval";
    }
    ?>
    <h3 class="text-danger">{{ $head }}</h3>
    @include('layouts.breadcrumb')



    <form method="post" id="qcform" data-parsley-validate>

        {{ csrf_field() }}

        <div class="card shadow-lg rounded-4 border-0">

            <div class="card-body">
                <div class="row g-4 none">
                    <!-- Column 1 -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">QC Number</label>
                            <input type="hidden" class="form-control qc_header_id" name="qc_header_id" value="{{ $row->qc_header_id }}">
                            <input type="hidden" class="form-control source_type" name="source_type" value="{{ $row->source_type }}">
                            <input type="text" class="form-control qc_number" name="qc_number" value="{{ $row->qc_number }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><span class="text-danger">*</span> QC Date</label>
                            <input type="text" class="form-control datepicker" name="qc_date" value="{{ $row->qc_date }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control description" name="description" value="{{ $row->description }}">
                        </div>

                        <div class="mb-3 none">
                            <label class="form-label">Subcontract Supplier</label>
                            <select name="subcontract_supplier_id" class="form-select select2 subcontract_supplier_id">
                                {!! $row->subcontract_supplier_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">DC Date</label>
                            <input type="text" class="form-control dc_date" name="dc_date" value="{{ $row->dc_date }}"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">DC Number</label>
                            <input type="text" class="form-control dc_number" name="dc_number" value="{{ $row->dc_number }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Supplier Name</label>
                            <select name="supplier_id" class="form-select select2 supplier_id">
                                {!! $row->supplier_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">QC Status</label>
                            <select name="qc_status" class="form-select select2 qc_status" id="qc_status" readonly>
                                <option value="">--Please Select--</option>
                                <option value="DRAFT" {{ $row->qc_status == "DRAFT" ? 'selected' : '' }}>DRAFT</option>
                                <option value="INITIATED" {{ $row->qc_status == "INITIATED" ? 'selected' : '' }}>INITIATED
                                </option>
                                <option value="APPROVED" {{ $row->qc_status == "APPROVED" ? 'selected' : '' }}>APPROVED
                                </option>
                                <option value="REJECTED" {{ $row->qc_status == "REJECTED" ? 'selected' : '' }}>REJECTED
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">PO Number</label>
                            <select name="po_number" class="form-select select2 po_number">
                                {!! $row->po_number !!}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">GRN Number</label>
                            <select name="grn_number" class="form-select select2 grn_number" readonly>
                                {!! $row->grn_number !!}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <input type="text" class="form-control remarks" name="remarks" value="{{ $row->remarks }}">
                        </div>
                    </div>
                </div>


            <!--pop ups -->
                <!-- Purpose: serialwisemodel -->
    <div class="modal fade" id="serialModal" tabindex="-1" aria-labelledby="serialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="serialModalLabel">Quality Check</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="col-12 serialwise"></div>
                </div>

                <div class="modal-footer">
                    <div class="text-center w-100">
                        <!-- Add footer buttons if needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Purpose: qcmodel -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="qcModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="col-12 batchwise batchwise0"></div>
                </div>

                <div class="modal-footer">
                    <div class="row w-100">
                        <div class="col-12 text-center">
                            <?php if ($url == "qcapproval") { ?>
                            <button type="button" class="btn btn-success qcapprove me-2" value="APPROVE">Approve</button>
                            <?php } else { ?>
                            <button type="button" class="btn btn-success prdqc me-2" data-bs-dismiss="modal">Save</button>
                            <button type="button" class="btn btn-warning qc_check1 me-2" value="0">Check</button>
                            <?php } ?>
                            <button type="button" class="btn btn-secondary cancelqc me-2"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            <!--end pop ups-->


                <!-------------------------Linedata -------------------------------->
                <div class="row mt-2">
                    <div class="col-12 linetable">
                        <div id="preview-area" class="table-responsive">
                            <table class="table table-bordered clone_table" style="width: 125% !important;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Line No</th>
                                        <th class="pdtdiv">Product </th>
                                        <th class="qchide">Qc Check</th>
                                        <th>Uom Code</th>
                                        <th>Packed Description</th>
                                        <th>No.of Batch Number</th>
                                        <th>Total Box Qty</th>
                                        <th>No.of Accepted Batch</th>
                                        <th>No.of Rejected Batch</th>
                                        <th>Reason</th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </thead>
                                <tbody class="clone_lines_body">
                                   
                                    @if(count($linedata) > 0)
                                        @foreach($linedata as $key => $value)
                                       
                                                                    <tr class="line-row">
                                                                        <td>

                                                                            <input type="hidden" name="bulk_qc_line_id[]"
                                                                                class="form-control input-sm bulk_qc_line_id"
                                                                                value="{{ $value->qc_line_id }}">
                                                                            <input type="hidden" name="bulk_po_hdr_id[]"
                                                                                class="form-control input-sm bulk_po_hdr_id"
                                                                                value="{{ $value->po_hdr_id }}">
                                                                            <input type="hidden" name="qc_type" class="form-control input-sm qc_type"
                                                                                value="{{ $value->qc_type }}">
                                                                            <input type="hidden" name="serialno" class="form-control input-sm serialno"
                                                                                value="{{ $value->serialno }}">
                                                                            <input type="hidden" name="batchno" class="form-control input-sm batchno"
                                                                                value="{{ $value->batchno }}">
                                                                            <input type="hidden" name="grn_line_id" class="form-control input-sm grnlineid"
                                                                                value="{{ $value->grn_line_id }}">
                                                                            <input type="hidden" name="box_product_qty"
                                                                                class="form-control input-sm box_product_qty"
                                                                                value="{{ $value->box_product_qty }}">
                                                                            <input type="hidden" class="form-control input-sm pqcdata" value="0">
                                                                            <select type="hidden" name="approval_status" id="approval_status"
                                                                                class="form-control approval_status" readonly style="display:none;">
                                                                                <option value="">--Please Select--</option>
                                                                                <option <?php        if ($row->approval_status == "APPROVED") {
                                                echo "selected";
                                            } else {
                                                echo "";
                                            } ?> value="APPROVED">APPROVED</option>
                                                                                <option <?php        if ($row->approval_status == "REJECTED") {
                                                echo "selected";
                                            } else {
                                                echo "";
                                            } ?> value="REJECTED">REJECTED</option>
                                                                            </select>

                                                                            <input type="text" name="bulk_line_no[]"
                                                                                class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}"
                                                                                readonly="readonly">
                                                                        </td>
                                                                        <td class="pdtdiv">
                                                                            <select name="bulk_product_id[]"
                                                                                class="form-control bulk_product_id  parsley-validated select2"
                                                                                required="required">{!! $value->product_id !!}</select>

                                                                        </td>
                                                                        <td class="qchide">
                                                                            <i class="fa fa-check qccheck fw-bold text-center text-primary fw-bold" value="qc"></i>
                                                                        </td>
                                                                        <td>
                                                                            <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                                                class="form-control bulk_uom_code_id select2">
                                                                                {!! $value->uom_code_id !!}
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="bulk_packed_discription[]"
                                                                                class="form-control input-sm bulk_packed_discription input_qty_width"
                                                                                value="" readonly>
                                                                        </td>

                                                                        <td>
                                                                            <input type="text" name="bulk_box_qty[]"
                                                                                class="form-control input-sm bulk_box_qty input_qty_width"
                                                                                value="{{ $value->box_qty }}" required="required">
                                                                        </td>

                                                                        <td>
                                                                            <input type="text" name="bulk_total_box_qty[]"
                                                                                class="form-control input-sm bulk_total_box_qty input_qty_width"
                                                                                value="{{ $value->total_box_qty }}" required="required" readonly>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="bulk_accept_qty[]"
                                                                                class="form-control input-sm bulk_accept_qty input_qty_width"
                                                                                value="{{$value->accept_qty }}" required="required">
                                                                        </td>

                                                                        <td>
                                                                            <input type="text" name="bulk_reject_qty[]"
                                                                                class="form-control input-sm bulk_reject_qty input_qty_width"
                                                                                value="{{ $value->reject_qty }}" required="required" readonly>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="bulk_reason[]"
                                                                                class="form-control input-sm bulk_reason input_qty_width"
                                                                                value="{{ $value->reason }}" required="required">
                                                                        </td>


                                                                        <td class="text-center">
                                                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                                                <i class="fas fa-minus-circle"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                        @endforeach
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
                <!-------------------------Linedata End-------------------------------->


                <div class="row mt-4 me-2">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group text-center">

                            <?php if ($url == "qcapproval") { ?>
                            <?php if ($qc_type == "SERIALWISE") { ?>
                            <button type="button" class="btn btn-success saveform px-4 me-2"
                                value="APPROVE">Approve</button>
                            <button type="button" class="btn btn-danger saveform rejected px-4 me-2"
                                value="REJECT">Reject</button>
                            <a href="{{ url('qcapproval') }}" class='btn btn-secondary px-4 me-2'>Cancel</a>
                            <?php    } else { ?>
                            <button type="button" class="btn btn-success saveapproval px-4 me-2" value="SAVE">Save</button>
                            <a href="{{ url('qcapproval') }}" class='btn btn-secondary px-4 me-2'>Cancel</a>
                            <?php    } ?>
                            <?php } else { ?>
                            <button type="button" class="btn btn-success saveform px-4 me-2" value="SAVE">Save</button>
                            <a href="{{ url($url) }}" class='btn btn-secondary px-4 me-2'>Cancel</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--end-->
            <input type="hidden" class="qcdata" value="">
            <input type="hidden" class="btno" value="">
            <input type="hidden" class="remarks" value="">
            <input type="hidden" class="indexno" value="0">

        </div>

    </form>


@endsection
@push('scripts')

    <script>

        $(document).ready(function () {

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
                    showCustomAlert("You Can't Delete Atleast One row should be There","warning");
                }
            });

            // Renumber Line Nos
            function updateLineNumbers() {
                $('.clone_lines_body tr').each(function (index) {
                    $(this).find('.bulk_line_no').val(index + 1);
                });
            }

            // save function 
            $(document).on('click', '.saveform', function () {

                var btnval = $(this).val();

                if (btnval == 'APPLYCHANGES')
                    $("#qc_status").val('DRAFT');
                else if (btnval == 'DRAFT')
                    $("#qc_status").val('DRAFT');
                else if (btnval == 'APPROVE')
                    $("#qc_status").val('APPROVED');
                else if (btnval == 'SAVE' || btnval == 'SAVENEW')
                    $("#qc_status").val('INITIATED');
                else if (btnval == 'REJECT')
                    $("#qc_status").val('REJECTED');
                else
                    $("#qc_status").val('INITIATED');

                <?php if ($url == "qcapproval") { ?>
                <?php    if ($qc_type == "SERIALWISE") { ?>
                if (btnval == 'APPROVE')
                    $(".approval_status").val('APPROVED');
                else if (btnval == 'REJECT')
                    $("#approval_status").val('REJECTED');

                var url = "{{ url('qcsave') }}";
                <?php    } else { ?>
                var url = "{{ url('qcapprovalsave') }}";
                <?php    } ?>

                <?php } else { ?>
                var url = "{{ url('qcsave')}}";
                <?php } ?>
                var red_url = "{{ url($url) }}";
                var create_url = "{{ url('qualitychecking') }}/0";

                if (btnval != 'APPLYCHANGES') {

                    var form = $('#qcform');
                    form.parsley().validate();


                    $('#qcform').find('input, select').removeAttr('disabled');
                    if (url1 == "qcapproval") {
                        $('#qcform').find('input').removeAttr('required');
                    }
                    if (form.parsley().isValid()) {
                        var formdata = $('#qcform').serialize();
                        var $btn = $(this);
                        $btn.prop('disabled', true);
                        $.post(url, formdata, function (data) {
                            var status = data.status;
                            var msg = data.message;
                            var id = data.id;
                            var edit_url = "{{ url('qualitychecking') }}/" + id;
                            if (btnval != 'SAVE' && btnval != 'DRAFT' && btnval != 'APPROVE' && btnval != 'REJECT') {

                                showCustomAlert(msg,status);
                                setTimeout(function () {
                                    window.location.href = create_url;
                                }, 1500);
                            }
                            else {
                                showCustomAlert(msg,status);
                                setTimeout(function () {
                                    window.location.href = red_url;
                                }, 1500);
                            }
                        });
                    }
                } else {

                    var formdata = $('#qcform').serialize();
                    $.post(url, formdata, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        var edit_url = "{{ url('purchaseqcedit') }}/" + id;
                        showCustomAlert(msg,status);
                        setTimeout(function () {
                            window.location.href = edit_url;
                        }, 1500);

                    });
                }
            });

            $('.saveapproval').hide();

            $('.po_number,.bulk_box_qty,.dc_number').attr("readonly", true);
            $('.supplier_id,.bulk_product_id,.bulk_uom_code_id,.subcontract_supplier_id').attr("readonly", true);
            $('.supplier_id,.po_number,.grn_number,.bulk_product_id,.bulk_uom_code_id,.qc_status,.subcontract_supplier_id').css("pointer-events", "none");


            var url1 = "<?php echo $url; ?>";
            if (url1 == "qcapproval") {
                $('#qcform').find('input, select').attr('disabled', 'disabled');
                $(".remove").prop('disabled', true);
            }
            $(".approve").on('click', function () {
                $("#qc_status").val("APPROVED").change();
            });
            $(".rejected").on('click', function () {
                $("#qc_status").val("REJECTED").change();
            });



            $(document).on('click', '.form', function () {
                var btn_val = $(this).val();
                $('.submit_type').val(btn_val);
            });

        });





        (function ($) {
            "use strict";

            // ====== Config from server (set this in your layout if needed) ======
            // Example in Blade: <script>window.QC_IS_APPROVAL = {{ $url === 'qcapproval' ? 'true' : 'false' }};
            const isApprovalView = !!window.QC_IS_APPROVAL;

            // ---------- Helpers ----------
            const num = (v) => {
                const n = parseFloat((v ?? '').toString().replace(/,/g, '').trim());
                return Number.isFinite(n) ? n : 0;
            };

            const alertOK = (msg) => typeof showCustomAlert === 'function' ? showCustomAlert(msg,'success') : alert(msg);
            const alertInfo = (msg) => typeof showCustomAlert === 'function' ? showCustomAlert(msg,'info') : alert(msg);
            const alertErr = (msg) => typeof showCustomAlert === 'function' ? showCustomAlert(msg,'error') : alert(msg);

            const safeJSON = (str) => { try { return $.parseJSON(str || '[]'); } catch (e) { return []; } };

            // Reads a field safely from the same row
            const field = ($row, sel) => $row.find(sel);

            // ---------- Save overall approval (header-level) ----------
            $(document).on("click", ".saveapproval", function () {
                const po_number = $(".po_number").val();
                const $firstRow = $("tr.line-row").first();
                if (!$firstRow.length) return;

                const box_qty = field($firstRow, ".bulk_total_box_qty").val();
                const product_id = field($firstRow, ".bulk_product_id").val();
                const qcline = field($firstRow, ".bulk_qc_line_id").val();

                const base = "/qcapprovalstatussave";
                const url = `${base}?qcline=${encodeURIComponent(qcline)}&po_number=${encodeURIComponent(po_number)}&product_id=${encodeURIComponent(product_id)}&box_qty=${encodeURIComponent(box_qty)}`;

                // set this server-side if you need a redirect
                var red_url = "{{ url('qcapproval') }}";

                $.get(url, function () {
                    alertOK("Quality Approval Saved successfully");
                    setTimeout(function () {
                      window.location.href = red_url;
                    }, 1500);
                });
            });

            // ---------- QC approve/reject (modal action) ----------
            $(document).on("click", ".qcapprove", function () {

            var $m = $("#myModal");
            var $row = $m.data('sourceRow');

            if (!$row || !$row.length) {
                console.error("QC Approve: source row not found");
                return;
            }

            var qcstatusd = $m.find('input[type=radio][name^=qcstatus]:checked').val();
            var pqcline   = $m.find(".quality_spec_trx_id").val();
            var remarks   = $m.find(".remarks").val();
            var comments  = $m.find(".comments").val();

            // ✅ NOW THIS WORKS
            var qcline = $.trim($row.find('.bulk_qc_line_id').val());

            console.log({ qcstatusd, pqcline, qcline, remarks, comments });

            var url = "{{ URL::to('qcapprovalsave') }}" +
                "?qcstatusd=" + encodeURIComponent(qcstatusd || "") +
                "&pqcline="   + encodeURIComponent(pqcline || "") +
                "&qcline="    + encodeURIComponent(qcline || "") +
                "&remarks="   + encodeURIComponent(remarks || "") +
                "&comments="  + encodeURIComponent(comments || "");

            $.get(url, function (data) {
                if ($.trim(data) === "Accepted") {
                showCustomAlert("QC Approved Successfully", "success");
                $row.find('.approval_status').val('APPROVED');
                } else {
                showCustomAlert("QC Rejected Successfully", "success");
                $row.find('.approval_status').val('REJECTED');
                }

                $m.modal("hide");
                $(".saveapproval").show();
            });
            });




            // ---------- Per-row behavior based on qc_type ----------
            const applyQcTypeUI = ($row) => {
                const qctype = field($row, ".qc_type").val();

                if (qctype === "SERIALWISE") {
                    field($row, ".bulk_accept_qty, .bulk_reject_qty").prop("readonly", false);
                    $(".qchide").hide(); // if you want to hide the QC check column globally

                    // Live validation of accept qty against total
                    field($row, ".bulk_accept_qty").off("keyup.qc").on("keyup.qc", function () {
                        const accept = num($(this).val());
                        const total = num(field($row, ".bulk_total_box_qty").val());

                        if (accept <= 0 || accept > total) {
                            alertInfo("Exceeds Total Box Qty");
                            $(this).val("");
                            field($row, ".bulk_reject_qty").val("0");
                        } else {
                            field($row, ".bulk_reject_qty").val((total - accept).toFixed(2));
                        }
                    });
                } else {
                    // BATCHWISE or others lock direct edit
                    field($row, ".bulk_accept_qty, .bulk_reject_qty").prop("readonly", true);
                    field($row, ".qchide").show();
                }
            };

            // Initialize existing rows
            $("tr.line-row").each((_, tr) => applyQcTypeUI($(tr)));

            // If you dynamically add rows, call applyQcTypeUI on the new one after it’s in DOM
            $(document).on("click", ".add-row", function () {
                const $newRow = $("tr.line-row").last();
                applyQcTypeUI($newRow);
            });



            
            // ---------- Open per-row QC check (batch/serial modal entry) ----------

// set this somewhere from blade
window.isApprovalView = {{ $url == 'qcapproval' ? 'true' : 'false' }};
window.isApprovalView = window.isApprovalView ?? false;


/* ------------------- QC CHECK ICON CLICK ------------------- */
$(document).on('click', '.qccheck', function () {

  $('.prdqc').prop('disabled', true);

  const $row = $(this).closest('tr');
  const qctype = $.trim($row.find('.qc_type').val());
  const qc_line_id = $.trim($row.find('.bulk_qc_line_id').val());

  if (qctype !== "BATCHWISE") return;

  const $serialModal = $('#serialModal');
  const $serialContainer = $serialModal.find('.serialwise');

  // store the clicked row so snoqc knows which line is selected
    $serialModal.data('row', $row);
    $('#myModal').data('sourceRow', $row);
  $serialContainer.html('');
  $serialModal.modal('show');

  /* ---------------- QC CHECK (NOT approval) ---------------- */
  if (!window.isApprovalView) {
    const sno = safeJSON($row.find('.serialno').val());
    const batchno = safeJSON($row.find('.batchno').val());

    if (!Array.isArray(sno) || !sno.length) {
      $serialContainer.html("<div class='p-2 text-muted'>No serials found</div>");
      return;
    }

    let html = "<table class='overflow-y table'>" +
      "<thead><tr>" +
      "<th width='150px;'>Batch Number/Sno</th>" +
      "<th width='100px;'>Check</th>" +
      "<th>Status</th>" +
      "</tr></thead><tbody>";

    $.each(sno, function (i, v) {
      const idx = `${batchno[i]}/${v}`;
      html +=
        `<tr>
          <td>${idx}</td>
          <td>
            <i class='fa fa-check snoqc text-primary fw-bold'
               data-serial='${i}'
               data-index='${idx}'
               data-sub='${i}'
               style="cursor:pointer;"></i>
          </td>
          <td class='text-success fw-bold status_val status_val${i}'></td>
        </tr>`;
    });

    html += "</tbody></table>";
    $serialContainer.html(html);
  }

  /* ---------------- QC APPROVAL ---------------- */
  else {
    const url = `{{ URL::to('productqcserialdetails') }}/${qc_line_id}`;

    $.get(url, function (data) {



      let html = "<table class='overflow-y table'>" +
        "<thead><tr>" +
        "<th width='150px;'>Batch Number/Sno</th>" +
        "<th width='120px;'>Status</th>" +
        "<th width='100px;'>Check</th>" +
        "</tr></thead><tbody>";

      $.each(data.serialno, function (i, v) {
        const idx = `${data.batchno[i]}/${v}`;
        const statusTxt = data.quality_status?.[i] ?? '';
        html +=
          `<tr>
            <td>${idx}</td>
            <td class="text-success fw-bold status_val status_val${i}">${statusTxt}</td>
            <td>
              <i class='fa fa-check snoqc text-primary fw-bold'
                 data-serial='${data.id[i]}'
                 data-index='${idx}'
                 data-sub='${i}'
                 style="cursor:pointer;"></i>
            </td>
          </tr>`;
      });

      html += "</tbody></table>";
      $serialContainer.html(html);
    });
  }
});

/* enable again when modal closes */
$(document).on('hidden.bs.modal', '#serialModal', function () {
  $('.prdqc').prop('disabled', false);
});


/* ------------------- SERIAL CHECK ICON CLICK ------------------- */
$(document).on('click', '.snoqc', function () {

  const $serialModal = $('#serialModal');
  const $myModal = $('#myModal');

  // retrieve the row saved earlier
  const $row = $serialModal.data('row');
  if (!$row || !$row.length) return;

  const dataindex = $.trim($(this).attr('data-sub')); // stable index from html
  const status = $.trim($('.status_val' + dataindex).text());

  if (status !== "Accepted" && status !== "Rejected") {
    $myModal.modal('show');
    $myModal.css('width', '100%');
  } else {
    $serialModal.modal('show');
  }

  const batch = $(this).data('index');
  const lineSerial = $(this).data('serial'); // in approval: this is data.id[i] ; in check: i

  $('.qcdata').val(lineSerial);

  const pid = $.trim($row.find('.bulk_product_id').val());
  const qc_line_id = $.trim($row.find('.bulk_qc_line_id').val());

  let url;

  if (window.isApprovalView) {
    console.log("Approval view detected");
    url = `{{ URL::to('productqcspecdetails') }}/${qc_line_id}` +
      `?source=qcapproval&line_id=${qc_line_id}&serial=${dataindex}&batch=${encodeURIComponent(batch)}`;
  } else {
    console.log("view detected");
    url = `{{ URL::to('productqcspecdetails') }}/${pid}` +
      `?serial=${dataindex}&line_id=${lineSerial}&source=&batch=${encodeURIComponent(batch)}`;
  }

  // container handling
  let $container = isApprovalView ? $('.batchwise') : $('.batchwise' + dataindex);

  if (!$container.length) {
    $container = $('<div/>', { class: isApprovalView ? 'batchwise' : 'batchwise' + dataindex })
      .appendTo($myModal.find('.modal-body'));
  }

  // load always (avoids stale old html)
  $.get(url, function (data) {
    $container.html(data);
  });

  $('.serial').hide();
  $('.serial' + dataindex).show();
});





            // ---------- Numeric validation ----------
            $(document).on("keypress", ".bulk_measurement", function (ev) {
                const ok = /^[0-9.]$/.test(String.fromCharCode(ev.which || ev.keyCode));
                if (!ok) { ev.preventDefault(); return false; }
            });

            // Alphanumeric + _ . -
            $(document).on("keypress", ".measure", function (ev) {
                const ok = /^[A-Za-z0-9_.-]$/.test(String.fromCharCode(ev.which || ev.keyCode));
                if (!ok) { ev.preventDefault(); return false; }
            });

            // ---------- Run spec check and set Accept/Reject radio ----------


window.qcCache = window.qcCache || {};

// Save all measurements from modal into cache
function cacheMeasurements(serial) {
  qcCache[serial] = qcCache[serial] || { measurements: {}, status: null };

  $('.bulk_measurement' + serial).each(function () {
    const k = $(this).data('key');
    qcCache[serial].measurements[k] = $(this).val();
  });
}

// Restore all measurements from cache into modal
function restoreMeasurements(serial) {
  if (!qcCache[serial] || !qcCache[serial].measurements) return;

  Object.keys(qcCache[serial].measurements).forEach(function (k) {
    $('.bulk_measurement' + serial + k).val(qcCache[serial].measurements[k]);
  });

  // restore status label if available
  if (qcCache[serial].status) {
    $('.status_val' + serial).text(qcCache[serial].status);
  }
}


// Example: when you do ajax success: $(".batchwise0").html(data); then call:
function afterModalHtmlLoaded() {
  const serial = Number($('.qcdata').val() || 0);
  restoreMeasurements(serial);
}


// ---- CHECK button ----
$(document).on("click", ".qc_check1", function () {

    let anyFail = false;
    const serial = Number($(".qcdata").val() || 0);

    $('.bulk_measurement' + serial).each(function (i, el) {

        const measureRaw = $(el).val();
        const spec = $('.bulk_spec_criteria' + serial + i).val();
        const specNorm = (spec || '').trim().toUpperCase();

        if (specNorm === "NOR") return;

        const measure = num(measureRaw);
        const toVal   = num($('.bulk_spec_value_to' + serial + i).val());
        const fromVal = num($('.bulk_spec_value_from' + serial + i).val());

        if (specNorm !== "PASS/FAIL" && isNaN(measure)) {
            anyFail = true; return;
        }

        if (specNorm === "GREATER THAN" && !(measure > toVal)) anyFail = true;
        if (specNorm === "LESS THAN" && !(measure < toVal)) anyFail = true;
        if (specNorm === "EQUAL" && !(measure === toVal)) anyFail = true;
        if (specNorm === "PASS/FAIL" && String(measureRaw) !== "1") anyFail = true;
        if (specNorm === "BETWEEN" && !(measure >= fromVal && measure <= toVal)) anyFail = true;
    });

    $(".prdqc").prop("disabled", false);

    if (anyFail) {
        showCustomAlert("Quality Rejected", "error");
        $('.pidsr' + serial).prop("checked", true);
        $('.status_val' + serial).text("Rejected");
        qcCache[serial].status = "Rejected";
    } else {
        showCustomAlert("Quality Accepted", "success");
        $('.pidsa' + serial).prop("checked", true);
        $('.status_val' + serial).text("Accepted");
        qcCache[serial].status = "Accepted";
    }

    cacheMeasurements(serial);
});



// ---- SAVE button ---- (same behavior like your old function)
$(document).on("click", ".prdqc", function () {

  const serial = Number($(".qcdata").val() || 0);

  // cache before doing anything
  cacheMeasurements(serial);

  const qctype = $(".qc_type" + serial).val(); // "BATCHWISE" etc.

  if (qctype === "BATCHWISE") {

    const qcstatus1 = $('input[name="qcstatus' + serial + '"]:checked').val(); // Accepted/Rejected
    const s_app1    = $('input[name="serialapplicable' + serial + '"]:checked').val(); // "1" if checked

    const boxQty = Number($(".bulk_box_qty0").val() || 0);

    if (s_app1 === "1") {

      $(".bulk_accept_qty0").val("0");
      $(".bulk_reject_qty0").val("0");

      if (qcstatus1 === "Accepted") {
        $(".bulk_accept_qty0").val(boxQty);
        $(".status_val").each(function () { $(this).html("Accepted"); });
      } else {
        $(".bulk_reject_qty0").val(boxQty);
        $(".bulk_accept_qty0").val("0");
        $(".status_val").each(function () { $(this).html("Rejected"); });
      }

    } else {

      let acc = Number($(".bulk_accept_qty0").val() || 0);
      let rej = Number($(".bulk_reject_qty0").val() || 0);

      // ✅ stop increment after all qty completed (fix: "again click also accept")
      if (acc + rej >= boxQty) {
        showCustomAlert("QC already completed for all qty","info");
        $("#myModal").modal("hide");
        return;
      }

      if (qcstatus1 === "Accepted") {
        acc += 1;
        $(".bulk_accept_qty0").val(acc);
        $(".status_val" + serial).html("Accepted");
      } else {
        rej += 1;
        $(".bulk_reject_qty0").val(rej);
        $(".status_val" + serial).html("Rejected");
      }
    }

    $("#serialModal").modal("show");

  } else {

    const qcstatus1 = $('input[name="qcstatus0"]:checked').val();
    const boxQty = $(".bulk_box_qty0").val();

    if (qcstatus1 === "Accepted") {
      $(".bulk_reject_qty0").val("0");
      $(".bulk_accept_qty0").val(boxQty);
      $(".status_val0").text("Accepted");
    } else {
      $(".bulk_accept_qty0").val("0");
      $(".bulk_reject_qty0").val(boxQty);
      $(".status_val0").text("Rejected");
    }

    $("#serialModal").modal("hide");
  }

  // restore again to ensure inputs stay filled
  restoreMeasurements(serial);
});



            // ---------- Cancel QC modal ----------
            $(document).on("click", ".cancelqc", function () {
                $("#serialModal").modal("hide");
            });

            // ---------- Date pickers ----------
            if ($.fn.datepicker) {
                $(".dc_date, .qc_date").datepicker({ format: "yyyy-mm-dd", autoClose: true });
            }

        })(jQuery);






    </script>

@endpush