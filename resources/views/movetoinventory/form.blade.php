@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Move To Inventory</h3>
  @include('layouts.breadcrumb')



  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <form id="popupsave">
        {{ csrf_field() }}

        <div class="row mb-3">
          <div class="col-md-6">
            <div class="row mb-3">
              <label for="qc_number" class="col-md-4 col-form-label">GRN Number</label>
              <div class="col-md-8">
                <input type="hidden" class="form-control qc_line_id" id="qc_line_id" name="qc_line_id"
                  value="{{ $qc_line_id }}">
                <input type="hidden" class="form-control qc_header_id" id="qc_header_id" name="qc_header_id"
                  value="{{ $qc_header_id }}">
                <input type="hidden" class="form-control grn_id" id="grn_id" name="grn_id"
                  value="{{ $invdata[0]->grn_number }}">
                <input type="text" class="form-control grn_number" id="grn_number" name="grn_number"
                  value="{{ $invdata[0]->grn_name }}" readonly>
                <input type="hidden" class="form-control quality_check" id="quality_check" name="quality_check"
                  value="{{ $qccheckdata[0]->quality_check }}" readonly>
              </div>
            </div>

            @if($qccheckdata[0]->quality_check == "Yes")
              <div class="row mb-3">
                <label for="qc_number" class="col-md-4 col-form-label">QC Number</label>
                <div class="col-md-8">
                  <input type="hidden" class="form-control" id="qc_line_id" name="qc_line_id" value="{{ $qc_line_id }}">
                  <input type="hidden" class="form-control" id="qc_header_id" name="qc_header_id"
                    value="{{ $qc_header_id }}">
                  <input type="text" class="form-control" id="qc_number" name="qc_number"
                    value="{{ $invdata[0]->qc_number }}" readonly>
                </div>
              </div>
            @endif
          </div>

          <div class="col-md-6 none">
            <div class="row mb-3">
              <label for="move_date" class="col-md-4 col-form-label">
                <span class="text-danger">*</span>Move to Inventory Date
              </label>
              <div class="col-md-8">
                <input type="text" class="form-control" id="move_date" name="move_date" value="{{ date('Y-m-d') }}"
                  required>
                <input type="hidden" id="qc_date" value="">
              </div>
            </div>
          </div>
        </div>

        <input type="hidden" class="form-control" id="po_number" name="po_number" value="{{ $invdata[0]->po_hdr_id }}">

        <div class="row mb-3">
          <div class="col-md-12">
            <div id="preview-area" class="chandru">
              <table class="table table-bordered" style="table-layout: fixed; width: 100%;">
                <tbody class="batch_lines"></tbody>
              </table>
              <input type="hidden" name="enable-masterdetail" value="true">
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

      $(document).on('click', '.btn-secondary', function () {
        var red_url = "{{ URL::to('movetoinventory') }}";
        window.location.href = red_url;
      });

      $('.uomdiv,.prddiv').css('pointer-events', 'none');
      $('.bulk_accept_qty,.bulk_uom_code_id,.bulk_qoh_qty').attr('readonly', true);



      $(document).on('change keyup', '.bulk_product_id,.bulk_allocated_qty', function () {

        var index = $(this).closest('tr').index();

        var prd = $('.bulk_product_id' + index).val();

        var acptqty = $('.bulk_allocated_qty' + index).val();


        var total = $('.accept' + prd).val();
        var qoh_qty = $('.qoh_qty' + prd).val();
        var uom = $('.uom' + prd).val();
        $('.bulk_uom_code_id' + index).val(uom).change();
        var acceptqty = 0;


        $('.bulk_product_id').each(function (k) {
          var product_id = $('.bulk_product_id' + k).val();
          var uom_code_id = $('.bulk_uom_code_id').val();
          if (prd == product_id) {

            acceptqty = parseFloat(acceptqty) + parseFloat($('.bulk_allocated_qty' + k).val());
          }

          if (acceptqty > total) {
            showCustomAlert("Exceeds Accept Qty", "warning");
            $('.bulk_allocated_qty' + k).val("0");
          }

        });
        $('.bulk_accept_qty' + index).val(total);
        $('.bulk_qoh_qty' + index).val(qoh_qty);

      });



      $('select').attr("required", true);
      $('.sublocator_id').prop("required", true);

      $(document).on('click', '.addbox', function () {
        /* Purpose for Serial wise Qty Checking*/
        var qty = 0;
        var accept_qty = $('.accept_qty').val();
        $(".box_product_qty").each(function () {
          qty += +$(this).val();
        });
        console.log(qty);
        console.log(accept_qty);
        if (qty > accept_qty || qty < accept_qty) {
          showCustomAlert("Box Qty Should Not Exceeds QC Accept Qty", "error");
          $('.box_product_qty').val("");
        }
        /*End*/
        var url = "{{ URL::to('inventorysave') }}";
        var red_url = "{{ URL::to('movetoinventory') }}";
        var formdata = $('#popupsave').serialize();
        var form = $('#popupsave');
        form.parsley().validate();
        var form = $('#popupsave');
        form.parsley().validate();
        if (form.parsley().isValid()) {
          $.post(url, formdata, function (data) {
            var status = data.status;
            var msg = data.message;
            var id = data.id;
            var auto_no = data.auto_no;

            showCustomAlert(msg, status);
            setTimeout(function () {
              window.location.href = red_url;
            }, 1500);


          });
        }
      });


      var qc_line_id = $('.qc_line_id').val();
      var grn_id = $('.grn_id').val();

      var quality_check = $('.quality_check').val();
      var url = "{{ URL::to('movetoinventoryupdate') }}/" + qc_line_id + "/" + grn_id + "/" + quality_check;
      $.get(url, function (data) {
        $('.batch_table tbody').html('');
        if (data != "") {
          $('.batch_lines').append(data);
          $('#boxModal').modal('show');
          $('#boxModal').width("100%");
        
        }else {
          var red_url = "{{ URL::to('movetoinventory') }}";
          showCustomAlert("There is no accepted quantity","error");
          setTimeout(function () {
            window.location.href = red_url;
          }, 1500);
        }
      }); 


      $(document).on('click', '.saveform', function () {
        var btnval = $(this).val();
        $('#savestatus').val(btnval);
        var url = "{{ URL::to('moveinvsave') }}";
        var red_url = "{{ URL::to('movetoinventory') }}";
        var formdata = $('#mvinv_form').serialize();
        var form = $('#mvinv_form');
        form.parsley().validate();
        var form = $('#mvinv_form');
        form.parsley().validate();
        if (form.parsley().isValid()) {
          var $btn = $(this);
          $btn.prop('disabled', true);
          $.post(url, formdata, function (data) {
            var status = data.status;
            var msg = data.message;
            var id = data.id;
            var auto_no = data.auto_no;

            if (btnval == 'SAVE') {
              showCustomAlert(msg,status);
              setTimeout(function () {
                window.location.href = red_url;
              }, 1500);
            }

          });
        }

      });


$(document).on('change', '.subinventory_id', function () {

    var subinventory_id = $(this).val();

    $.ajax({
        url: "{{ URL::to('jcomboform') }}",
        type: "GET",
        dataType: "json",
        data: {
            table: "m_sublocators_t:sublocator_id:locator_name",
            parent: "subinventory_id=" + subinventory_id,
            order_by: "locator_name asc"
        },
        success: function (response) {

            var $sublocator = $('.sublocator_id');
            $sublocator.empty();
            $sublocator.append('<option value="">-- Select Sublocator --</option>');

            $.each(response, function (key, value) {
                $sublocator.append(
                    '<option value="' + value.sublocator_id + '">' +
                        value.option_name +
                    '</option>'
                );
            });


        }
    });

});




      $('.bulk_product_id > option').each(function () {

        var j;
        var values = $(this).val();

        if (jQuery.inArray(values, myarray) != '-1') {

        }
        else {
          j = $(this).val();
          $(".bulk_product_id  option[value='" + j + "']").remove();
        }
      });

    });



  </script>


@endpush