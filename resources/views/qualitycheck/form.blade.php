@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> <?php if ($type == 'QCCHECK') { ?>Quality Check <span class="ui_close_btn"><a
        href="{{ URL::to('qualitycheck') }}"
        class="collapse-close pull-right btn-danger"></a></span><?php } else if ($type == 'QCANALYTICAL') { ?> Quality
    Analytical <span class="ui_close_btn"><a href="{{ URL::to('qcanalytical') }}"
        class="collapse-close pull-right btn-danger"></a></span> <?php  } else { ?>Quality Approval <span
      class="ui_close_btn"><a href="{{ URL::to('qaapproval') }}"
        class="collapse-close pull-right btn-danger"></a></span><?php  } ?>
  </h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body card-block">


      <form method="post" action="" id="qualitycheck" data-parsley-validate>
        {{ csrf_field()}}
        <div class="row">
          <div class="col-md-4">
            <div class="row mb-3 none">
              <label for="inputIsValid" class="form-control-label col-md-5 col-form-label">Reference No</label>
              <div class="col-md-7">
                <input class="form-control quality_spec_trx_hdr_id" id="quality_spec_trx_hdr_id"
                  name="quality_spec_trx_hdr_id" type="hidden" value="{{ $quality_spec_trx_hdr_id }}" readonly>
                <input type="hidden" name="typeurl" class="typeurl" value="{{ $type }}">
                <input class="form-control qa_submitstage_trx_hdr_id" id="qa_submitstage_trx_hdr_id"
                  name="qa_submitstage_trx_hdr_id" type="hidden" value="{{ $row->qa_submitstage_trx_hdr_id }}" readonly>
                <input type="text" id="reference_no" name="reference_no" class="form-control reference_no"
                  value="{{ $row->reference_no }}" readonly>
              </div>
            </div>

            <div class="row mb-3 none">
              <label class="form-control-label col-md-5 col-form-label">Job No</label>
              <div class="col-md-7">
                <select name="job_hdr_id" class="form-control job_no select2" id="job_no" data-show-subtext="true"
                  data-live-search="true">
                  {!! $job_no !!}
                </select>
              </div>
            </div>

            <div class="row mb-3 none">
              <label class="form-control-label col-md-5 col-form-label">Product</label>
              <div class="col-md-7">
                <select name="product_id" id="product_id" class="form-control product_id select2" required>
                  {!! $product_id !!}
                </select>
              </div>
            </div>

            <div class="row mb-3 none">
              <label class="form-control-label col-md-5 col-form-label">Batch No</label>
              <div class="col-md-7">
                <input type="text" name="batch_no" class="form-control batch_no" id="batch_no" value="{!! $batch_no !!}">
              </div>
            </div>

            <div class="row mb-3 none" style="display:none;">
              <label class="form-control-label col-md-5 col-form-label"><span
                  style="color:red;">*</span>Subinventory</label>
              <div class="col-md-7 read">
                <select name="subinventory_id" class="form-control subinventory_id select2" readonly>
                  {!! $subinventory_id !!}
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <label class="form-control-label col-md-5 col-form-label rej">Rejection Type</label>
              <div class="col-md-7 read">
                <select name="rejection_type" class="form-control rejection_type select2" id="rejection_type">
                  {!! $rejection_type !!}
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <label class="form-control-label col-md-5 col-form-label">Quality Type</label>
              <div class="col-md-7">
                <input type="text" id="quality_type" name="quality_type" class="form-control quality_type"
                  value="{{ $row->quality_type }}" style="pointer-events:none;">
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="row mb-3 none">
              <label class="form-control-label col-md-5 col-form-label">Qa Trx Date</label>
              <div class="col-md-7">
                <div class="input-group">
                  <input class="form-control qatrx_date datepicker" id="qatrx_date" name="qatrx_date" type="text"
                    value="{{ $row->qatrx_date }}">
                </div>
              </div>
            </div>

            <div class="row mb-3 none">
              <label class="form-control-label col-md-5 col-form-label">Job Date</label>
              <div class="col-md-7">
                <div class="input-group">
                  <input class="form-control job_date datepicker" id="job_date" name="job_date" type="text"
                    value="{{ $row->job_date }}">
                </div>
              </div>
            </div>

            <div class="row mb-3 none">
              <label class="form-control-label col-md-5 col-form-label">Uom Code</label>
              <div class="col-md-7">
                <select name="uom_code_id" id="uom_code_id" class="form-control uom_code_id select2">
                  {!! $uom_code_id !!}
                </select>
              </div>
            </div>

            <div class="row mb-3 none">
              <label class="form-control-label col-md-5 col-form-label">Verifier</label>
              <div class="col-md-7">
                <select name="verifier" class="form-control verifier select2" readonly>
                  {!! $verifier !!}
                </select>
              </div>
            </div>

            <div class="row mb-3 none">
              <label class="form-control-label col-md-5 col-form-label">Manufacturer Date</label>
              <div class="col-md-7">
                <input class="form-control manufacturer_date" id="manufacturer_date" name="manufacturer_date" type="text"
                  value="{{ $row->manufacturer_date }}" readonly>
              </div>
            </div>

            <div class="row mb-3">
              <label class="form-control-label col-md-5 col-form-label">Remarks</label>
              <div class="col-md-7">
                <input type="text" id="remarks" name="remarks" class="form-control remarks" value="{{ $row->remarks }}">
              </div>
            </div>

            <div class="row mb-3 none" style="display:none;">
              <label class="form-control-label col-md-5 col-form-label"><span
                  style="color:red;">*</span>Sublocator</label>
              <div class="col-md-7 read">
                <select name="sublocator_id" class="form-control sublocator_id select2" readonly>
                  {!! $sublocator_id !!}
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="row mb-3 ">
              <label class="form-control-label col-md-5 col-form-label"><span class="req" style="color:red;">*</span>Trx
                Status</label>
              <div class="col-md-7 read">
                <select name="trx_status" class="form-control trx_status select2" id="trx_status" required>
                  {!! $trx_status !!}
                </select>
              </div>
            </div>

            <div class="row mb-3 none">
              <label class="form-control-label col-md-5 col-form-label">Qa Status</label>
              <div class="col-md-7">
                <select name="qa_status" class="form-control qa_status select2" id="qa_status" data-show-subtext="true"
                  data-live-search="true">
                  {!! $qa_status !!}
                </select>
              </div>
            </div>

            <div class="row mb-3 none">
              <label class="form-control-label col-md-5 col-form-label">Jobcard Qty</label>
              <div class="col-md-7">
                <input type="text" id="jobcard_qty" name="jobcard_qty" class="form-control jobcard_qty"
                  value="{{ $row->jobcard_qty }}">
              </div>
            </div>

            <div class="row mb-3 none">
              <label class="form-control-label col-md-5 col-form-label">Production Qty</label>
              <div class="col-md-7">
                <input type="text" name="production_qty" class="form-control production_qty input_qty_width"
                  value="{{ $row->production_qty }}" required>
              </div>
            </div>

            <div class="row mb-3 none">
              <label class="form-control-label col-md-5 col-form-label">Product Expiry Date</label>
              <div class="col-md-7 pdate">
                <input class="form-control product_expiry_date datepicker" id="product_expiry_date"
                  name="product_expiry_date" type="text" value="{{ $row->product_expiry_date }}">
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="col-md-12 read">
              <div class="form-check form-check-inline">
                <input class="form-check-input pids" type="radio" name="qc_type" id="qc_type_batchwise" value="batchwise"
                  data-index="batchwise" {{ ($row->qc_type == 'batchwise' || $row->qc_type == '') ? 'checked' : '' }}>
                <label class="form-check-label" for="qc_type_batchwise">Batchwise</label>
              </div>

              <div class="form-check form-check-inline">
                <input class="form-check-input pids" type="radio" name="qc_type" id="qc_type_qtywise" value="qtywise"
                  data-index="qtywise" {{ ($row->qc_type == 'qtywise') ? 'checked' : '' }}>
                <label class="form-check-label" for="qc_type_qtywise">Qtywise</label>
              </div>
            </div>

            <div class="batchwise">
              <div class="linesscroll">
                <div class="row">
                  <div class="col-12 linetable">
                    <div class="table-responsive">
                      <table class="table table-bordered clone_table">
                        <thead class="table-light">
                          <tr>
                            <th style="width: 80px;">Line No</th>
                            <th>Parameter </th>
                            <th>Specification Criteria</th>
                            <th>Spec Value From</th>
                            <th>Spec Value To</th>
                            <th>Uom</th>
                            <?php if ($job_status == "REWORK") { ?>
                            <th>Old Observation</th>
                            <?php } ?>
                            <th>Observation</th>
                            <th>Comments</th>
                          </tr>
                        </thead>
                        <tbody class="clone_lines_body">
                          @if(count($linedata) > 0)
                            @foreach($linedata as $key => $value)
                                                  <tr class="line-row">
                                                    <td class="read">
                                                      <input type="hidden" name="bulk_quality_spec_trx_line_id[]"
                                                        class="form-control input-sm bulk_quality_spec_trx_line_id"
                                                        value="{{ $value->quality_spec_trx_line_id }}">

                                                      <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no"
                                                        value="{{ $key + 1 }}" readonly="readonly">
                                                    </td>
                                                    <td class="read">
                                                      <input type="text" name="bulk_parameter[]"
                                                        class="form-control input-sm bulk_parameter input_qty_width"
                                                        value="{{ $value->parameter }}" readonly="readonly">
                                                    </td>

                                                    <td class="read">
                                                      <select name="bulk_spec_criteria[]" readonly
                                                        class="form-control input-sm bulk_spec_criteria input_qty_width">
                                                        {!! $value->spec_criteria !!}
                                                      </select>
                                                    </td>
                                                    <td class="read">
                                                      <input type="text" name="bulk_spec_value_from[]"
                                                        class="form-control input-sm bulk_spec_value_from input_qty_width"
                                                        value="{{ $value->spec_value_from }}" readonly="readonly">

                                                    </td>
                                                    <td class="read">
                                                      <input type="text" name="bulk_spec_value_to[]"
                                                        class="form-control input-sm bulk_spec_value_to input_qty_width"
                                                        value="{{ $value->spec_value_to }}" readonly="readonly">

                                                    </td>
                                                    <td class="read">
                                                      <input type="text" name="bulk_uom[]" class="form-control input-sm bulk_uom input_qty_width"
                                                        value="{{ $value->uom }}" readonly="readonly">

                                                    </td>
                                                    <?php    if ($job_status == "REWORK") {
                                if ($value->spec_criteriacode != "PASS/FAIL") { ?>
                                                    <td>
                                                      <input type="text" name="bulk_old_measurement[]"
                                                        class="form-control input-sm bulk_old_measurement input_qty_width"
                                                        value="{{ $value->old_measurement }}" readonly>
                                                    </td>
                                                    <?php      } else { ?>
                                                    <td>
                                                      <select name="bulk_old_measurement[]"
                                                        class="input-sm bulk_old_measurement select2 input_qty_width" readonly>
                                                        <option value="">--Please Select--</option>
                                                        <option value="1" <?php        if ($value->old_measurement == '1')
                                    echo "selected";?>>Pass</option>
                                                        <option value="2" <?php        if ($value->old_measurement == '2')
                                    echo "selected";?>>Fail</option>
                                                      </select>
                                                    </td>
                                                    <?php      }
                              } ?>
                                                    <?php    if ($value->spec_criteriacode != "PASS/FAIL") { ?>
                                                    <td class="read">
                                                      <input type="text" name="bulk_measurement[]"
                                                        class="form-control input-sm bulk_measurement input_qty_width"
                                                        value="{{ $value->measurement }}">
                                                    </td>
                                                    <?php    } else { ?>
                                                    <td class="read">
                                                      <select name="bulk_measurement[]" class="input-sm bulk_measurement select2 input_qty_width">
                                                        <option value="">--Please Select--</option>
                                                        <option value="1" <?php      if ($value->measurement == '1')
                                  echo "selected";?>>Pass</option>
                                                        <option value="2" <?php      if ($value->measurement == '2')
                                  echo "selected";?>>Fail</option>
                                                      </select>
                                                    </td>
                                                    <?php    } ?>
                                                    <td>
                                                      <?php    if ($type == "QCCHECK" || $type == 'QCANALYTICAL') { ?>
                                                      <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments"
                                                        value="{{ $value->comments }}">
                                                      <?php    } else { ?>
                                                      <select name="bulk_qc_comments[]" class="input-sm bulk_qc_comments select2 input_qty_width">
                                                        {!! $value->qc_comments !!}
                                                      </select>

                                                      <?php    }?>
                                                    </td>
                                                  </tr>
                            @endforeach
                          @else
                            <tr class="line-row">

                              <td></td>
                              <td colspan="3"></td>
                              <td>
                                <p> There is no Product Specification for this product<a
                                    href="{{URL::to('productspec')}}/{{ $prdid}}/0" target="_blank"> Create</a></p>
                              </td>
                            </tr>
                          @endif
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>


                <input type="hidden" name="enable-masterdetail" value="true">
              </div>
            </div>

            <div class="qtywise">
              <div class="row mt-2">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-5">Accepted Qty</label>
                    <div class="col-md-7">
                      <input type="text" id="accepted_qty" name="accepted_qty" class="form-control accepted_qty"
                        value="{{$row->accepted_qty}}" row="5">
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-5">Rejected Qty</label>
                    <div class="col-md-7">
                      <input type="text" id="rejected_qty" name="rejected_qty" class="form-control rejected_qty"
                        value="{{$row->rejected_qty}}" row="5">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!--*******************-Linedata End*******************************-->
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12 col-md-12 mt-4">
            <div class="form-group text-center">
              <?php if ($type == 'QCCHECK') { ?>
              <button type="button" class="btn btn-success saved saveform px-4 me-2" value="SAVE">Submit</button>
              <button type="button" class="btn btn-primary qccheck px-4 me-2">Check</button>
              <a href="{{ URL::to('qualitycheck') }}" class='btn btn-secondary px-4 me-2'>Cancel</a>
              <?php } else if ($type == 'QCANALYTICAL') { ?>
              <button type="button" class="btn btn-success saved saveform px-4 me-2" value="SAVE">Submit</button>
              <button type="button" class="btn btn-primary qccheck px-4 me-2">Check</button>
              <a href="{{ URL::to('qcanalytical') }}" class='btn btn-secondary px-4 me-2'>Cancel</a>
              <?php  } else if ($type == 'QCAPPROVE') { ?>
              <button type="button" class="btn btn-primary saved saveform"
                value="Conditional Acceptance px-4 me-2">Conditional Acceptance</button>
              <button type="button" class="btn btn-success saved saveform approved px-4 me-2"
                value="APPROVED">Approve</button>
              <button type="button" class="btn btn-danger saved saveform rejected px-4 me-2"
                value="REJECTED">Reject</button>
              <a href="{{ URL::to('qaapproval') }}" class='btn btn-secondary px-4 me-2'>Cancel</a>
              <?php  } ?>
            </div>
          </div>
        </div>
      </form>

    </div>
  </div>

@endsection
@push('scripts')

  <script>

    $('.trx_status').change(function () {
      var trxstatus = $('.trx_status').select2('val');
      if (trxstatus == "REJECTED" || trxstatus == "QC CHECKED") {
        $('.rejection_type').attr("required", true);
        $('.rejtype').show();
        $(".rej").append('<span class="rejtype" style="color:red;">*</span>');
      } else {
        $('.rejection_type').attr("required", false);
        $('.rejtype').hide();
      }
    })
    $('.qtywise').hide();
    $('.batchwise').show();

    var bat = $(".pids").data('index');
    $('.bulk_measurement').attr("required", true);
    $('.trx_status').attr("required", true);
    $('.accepted_qty').attr("required", false);
    $('.rejected_qty').attr("required", false);
    $(".req").show();

    $('.pids').change(function () {


      var batch = $(this).data('index');

      if (batch == 'batchwise') {
        var returnurl = '<?php echo $type; ?>';

        if (returnurl != 'QCAPPROVE') {
          $('.saved').attr('disabled', true);
        }

        $('.qtywise').hide();
        $('.batchwise').show();
        $('.qccheck').show();
        $('.pids').val("batchwise");
        $('.bulk_measurement').attr("required", true);
        $('.trx_status').attr("required", true);
        $('.accepted_qty').attr("required", false);
        $('.rejected_qty').attr("required", false);
        $(".req").show();
      } else {
        $('.saved').attr('disabled', false);
        $('.qccheck').hide();
        $('.qtywise').show();
        $('.accepted_qty').attr("required", true);
        $('.rejected_qty').attr("required", true);
        $('.rejection_type').attr("required", true);
        $('.bulk_measurement').attr("required", false);
        $('.batchwise').hide();
        $('.trx_status').attr("required", false);
        $(".req").hide();
        $('.pids').val("qtywise");
        var trxstatus = $('.trx_status').select2('val', ['QC CHECKED']);
      }
    });

    var batch = $("input[name='qc_type']:checked").attr('data-index');
    if (batch == 'batchwise') {
      var returnurl = '<?php echo $type; ?>';

      if (returnurl != 'QCAPPROVE') {
        $('.saved').attr('disabled', true);
      }

      $('.qtywise').hide();
      $('.batchwise').show();
      $('.qccheck').show()
      $('.pids').val("batchwise");
      $('.bulk_measurement').attr("required", true);
      $('.trx_status').attr("required", true);
      $('.accepted_qty').attr("required", false);
      $('.rejected_qty').attr("required", false);
      $(".req").show();
    } else if (batch == 'qtywise') {
      $('.saved').attr('disabled', false);
      $('.qccheck').hide();
      $('.qtywise').show();
      $('.accepted_qty').attr("required", true);
      $('.rejected_qty').attr("required", true);
      $('.rejection_type').attr("required", true);
      $('.bulk_measurement').attr("required", false);
      $('.batchwise').hide();
      $('.trx_status').attr("required", false);
      $(".req").hide();
      $('.pids').val("qtywise");
      var trxstatus = $('.trx_status').select2('val', ['QC CHECKED']);
    }

    var returnurl = '<?php echo $type; ?>';
    if (returnurl == 'QCAPPROVE') {
      $('.read').css('pointer-events', 'none');
      $('.bulk_measurement,.accepted_qty,.rejected_qty').attr('readonly', true);
    }

    $('.accepted_qty').change(function () {
      var accpqty = $(this).val();
      if (accpqty == "") {
        accpqty = 0;
      } else {
        accpqty = accpqty;
      }
      var prdqty = parseFloat($('.production_qty').val());
      if (parseFloat(accpqty) > prdqty) {
        showCustomAlert("Accepted Qty Should not be Greater Than Production Qty...",'error');
        $('.accepted_qty').val('');
      } else {
        var rqty = parseFloat(prdqty) - parseFloat(accpqty);
        $('.rejected_qty').val(rqty);
      }

    });


    $(document).on('keypress', '.accepted_qty,.rejected_qty', function (ev) {
      var regex = new RegExp("^[0-9.]+$");
      var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
      if (regex.test(str)) {
        return true;
      }
      ev.preventDefault();
      return false;
    });

    $(".qccheck").click(function () {
      var check = 0;

      $('.bulk_measurement').each(function (i, v) {
        var measure = $(this).val();
        var toval = $('.bulk_spec_value_to' + i).val();
        var fromval = $('.bulk_spec_value_from' + i).val();

        var spec = $(".bulk_spec_criteria" + i + " option:selected").text();
        if (measure != "") {
          if (spec == "GREATER THAN") {
            if (Number(toval) >= Number(measure)) {
              check = 1;

            }
          } else if (spec == "LESS THAN") {
            if (Number(toval) <= Number(measure)) {


              check = 1;
            }
          } else if (spec == "EQUAL") {
            if (Number(toval) != Number(measure)) {

              check = 1;
            }
          }
          else if (spec == "PASS/FAIL") {

            if (Number(measure) != 1) {

              check = 1;
            }
          } else if (spec == "BETWEEN") {

            if ((Number(fromval) <= Number(measure)) && (Number(toval) >= Number(measure))) {

            }
            else {

              check = 1;

            }
          } $('.saved').attr('disabled', false);
        } else {
          check = 2;

        }

      });

      if (check == 1) {
        showCustomAlert("Quality Reject",'error');
        $('.trx_status').select2('val', ['REJECTED']);
      } else if (check == 2) {
        showCustomAlert('Please enter Observation','warning');
      }
      else {
        showCustomAlert("Quality Accepted",'success');
        $('.trx_status').select2('val', ['ACCEPTED']);
      }
    });


    $(".subinventory_id").change(function () {
      var subinventory = $(this).select2('val');
      $('.sublocator_id').attr('disabled', true);
      if (subinventory != '') {
        $('.sublocator_id').prop('disabled', false);
        $(".sublocator_id").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code') }}&parent=subinventory_id=" + subinventory + "&order_by=locator_code asc");
      }
    })



    $(document).on('click', '.saveform', function () {

      var btnval = $(this).val();
      if (btnval == 'APPROVED') {
        $('.qa_status').val('APPROVED')
      } else if (btnval == 'REJECTED') {
        $('.qa_status').val('REJECTED')
      } else if (btnval == 'Conditional Acceptance') {
        $(".qa_status").val('APPROVED');
        $(".trx_status").select2('val', ['CONDITIONAL ACCEPTED']);

      }
      var url = "{{ url('qualitychecksave') }}";

      var pagemethod = "<?php echo $pageMethod ?>";
      if (pagemethod == "qcanalytical") {
        var red_url = "{{ url('qcanalytical') }}";
      } else if (pagemethod == "qualitycheck") {
        var red_url = "{{ url('qualitycheck') }}";
      } else {
        var red_url = "{{ url('qaapproval') }}";
      }
      var form = $('#qualitycheck');
      form.parsley().validate();

      if (form.parsley().isValid()) {
        var formdata = $('#qualitycheck').serialize();

        var $btn = $(this);
        $btn.prop('disabled', true);

        $.post(url, formdata, function (data) {
          var status = data.status;
          var msg = data.message;
          var id = data.id;

          showCustomAlert('Saved successfully!','success');
          setTimeout(function () {
            window.location.href = red_url;
          }, 1500);

        });
      }


    });

  </script>

@endpush