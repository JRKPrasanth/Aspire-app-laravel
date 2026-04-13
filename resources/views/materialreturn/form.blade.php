@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Material Return Details</h3>
  @include('layouts.breadcrumb')



  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold"></div>
    <div class="card-body card-block">
      <form method="post" action="" id="materialreturn" data-parsley-validate>
        <input type="hidden" value="" name="save_status" id="save_status" />
        {{ csrf_field()}}


        <div class="row">


          <div class="col-md-4">
            <div class="row mb-3 none">
              <label for="inputIsValid" class="col-form-label col-md-5">Reference No</label>

              <div class="col-md-7">
                <input class="form-control qa_submitstage_trx_hdr_id" id="qa_submitstage_trx_hdr_id"
                  name="qa_submitstage_trx_hdr_id" size="16" type="hidden" value="{{$row->qa_submitstage_trx_hdr_id}}"
                  readonly>
                <input type="text" id="reference_no" name="reference_no" class="form-control reference_no"
                  value="{{$row->reference_no}}" row="5" readonly style="width:100%;">
              </div>
            </div>


            <div class="row mb-3 none">
              <label for="Product" class="col-form-label col-md-5">Product</label>
              <div class="col-md-7">

                <select name="product_id" id="product_id" class="form-control product_id select2" required="required">
                  {!! $product_id !!}
                </select>
              </div>
            </div>
          </div>


          <div class="col-md-4 none">
            <div class="row mb-3">
              <label for="inputIsValid" class="col-form-label col-md-5">Job No</label>

              <div class="col-md-7">
                <input type="hidden" class="form-control w_jobs_hdr_id" id="w_jobs_hdr_id"
                  value="{{$row->w_jobs_hdr_id}}" />
                <input type="text" id="job_no" name="job_no" class="form-control job_no" value="{{$row->job_no}}" row="5"
                  readonly style="width:100%;">
              </div>

            </div>
          </div>


          <div class="col-md-4">

            <div class="row mb-3 none">
              <label for="active" class="col-form-label col-md-5">Batch No</label>
              <div class="col-md-7">
                <input type="text" name='batch_no' rows='5' class='form-control batch_no' id="batch_no"
                  data-show-subtext="true" data-live-search="true" value="{!! $batch_no !!}">
              </div>
            </div>
          </div>

        </div>




        <div class="row mt-4">
          <div class="col-md-12">

            <div id="preview-area" class="table-responsive">
              <table class="table table-bordered clone_table">

                <thead class="table-light">
                  <tr>

                    <th>Line No</th>
                    <th>Product </th>
                    <th>Uom Code</th>
                    <th>Material Issued Qty</th>
                    <th>Production Qty</th>
                    <th>Return Qty</th>
                    <th>Batch Number
                    <th>
                    <th>Subinventory</th>
                    <th>Locator</th>
                    <th>Comments</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="clone_lines_body">


                  <?php if (count($linedata) >= 1) {
    ?>
                  @foreach($linedata as $key => $value)
                    <tr class="clone cloneRow rcopy">
                      <td>
                        <input type="hidden" name="bulk_qa_materialreturn_id[]"
                          class="form-control input-sm bulk_qa_materialreturn_id"
                          value="{{ $value->qa_materialreturn_id }}">
                        <input type="hidden" name="bulk_qa_submitstage_trx_line_id[]"
                          class="form-control input-sm bulk_qa_submitstage_trx_line_id"
                          value="{{ $value->qa_submitstage_trx_line_id }}">
                        <input type="hidden" name="bulk_material_return_line_id[]"
                          class="form-control input-sm bulk_material_return_line_id" value="">

                        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no"
                          value="{{ $key + 1 }}" readonly="readonly">
                      </td>
                      <td class="pdtdiv">
                        <select name="bulk_product_id[]" id="bulk_product_id" class="bulk_product_id select2"
                          required="required">{!! $value->product_id !!}</select>
                      </td>

                      <td class="uomdiv">
                        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id"
                          data-show-subtext="true" data-live-search="true" readonly>
                          {!! $value->uom_code_id !!}

                        </select>
                      </td>

                      <td>
                        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width"
                          value="{{ $value->qty }}" minlength="1">
                      </td>
                      <td>
                        <input type="text" name="bulk_production_qty[]"
                          class="form-control input-sm bulk_production_qty input_qty_width"
                          value="{{ $value->production_qty}}" minlength="1" readonly="readonly">
                      </td>
                      <td>
                        <input type="text" name="bulk_return_qty[]"
                          class="form-control input-sm bulk_return_qty input_qty_width" value="{{ $value->return_qty}}"
                          minlength="1">
                      </td>
                      <td>
                        <select name="bulk_batchno[]" id="bulk_batchno" class="select2  bulk_batchno">
                          {!! $value->batchno !!}

                        </select>
                      </td>
                      <td></td>
                      <td style="pointer-events:none;">
                        <select name="bulk_subinventory_id[]" id="bulk_subinventory_id"
                          class="select2  bulk_subinventory_id">
                          {!! $value->subinventory_id !!}

                        </select>
                      </td>
                      <td>
                        <select name="bulk_sublocator_id[]" id="bulk_sublocator_id" class="select2 bulk_sublocator_id">
                          {!! $value->sublocator_id !!}
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
                  <?php }?>

                </tbody>
              </table>
              <!--  <div class="text-end">
      <button type="button" class="btn btn-success btn-sm add-row">
        <i class="fas fa-plus-circle"></i> Add Row
      </button>
    </div>	 -->
              <input type="hidden" name="enable-masterdetail" value="true">
            </div>
            <!--*******************-Linedata End*******************************-->
          </div>
        </div>




        <div class="row mt-4 mb-3">
          <div class="col-lg-12 col-md-12">
            <div class="form-group text-center">
              <button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Submit</button>
              <a href="{{ URL::to($pageurl) }}" class='btn btn-danger px-4'>Cancel</a>
            </div>
          </div>
        </div>

      </form>


    </div>
  </div>


@endsection
@push('scripts')

  <script>


    $(document).ready(function () {

      $(".hove").hover(function () {
        var data = $(".product_id option:selected").text();
        $(this).css('cursor', 'pointer').attr('title', data);
      });



      $(document).on('keypress', '.bulk_return_qty', function (ev) {
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
          return true;
        }
        ev.preventDefault();
        return false;
      });


      // purpose:based on subinventory,locator should be load*/
      $(".bulk_subinventory_id").change(function () {
        var index = $(this).closest('tr').index();
        var subinventory = $(this).val();
        $('.bulk_sublocator_id').attr('disabled', true);
        if (subinventory != '') {
          $('.bulk_sublocator_id').prop('disabled', false);
          $(".bulk_sublocator_id" + index).jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code') }}&parent=subinventory_id=" + subinventory + "&order_by=locator_code asc");
        }
      });

      
      //save
      $(document).on('click', '.saveform', function () {
        var btnval = $(this).val();
        var url = "{{ URL::to('materialreturnsave') }}";
        var red_url = "{{ URL::to('materialreturn') }}";
        var form = $('#materialreturn');
        form.parsley().validate();

        if (form.parsley().isValid()) {
          var $btn = $(this);
          $btn.prop('disabled', true);
          var formdata = $('#materialreturn').serialize();

          $.post(url, formdata, function (data) {
            var status = data.status;
            var msg = data.message;
            var id = data.id;
            showCustomAlert(msg, status);
            setTimeout(function () {
              window.location.href = red_url;
            }, 1500);

          });
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



  </script>


@endpush