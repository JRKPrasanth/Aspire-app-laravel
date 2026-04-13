@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Sub Inventory And Locator</h3>
  @include('layouts.breadcrumb')
  <?php error_reporting(0);?>


  <form method="post" action="" id="subinventoryform" data-parsley-validate>
    <input type="hidden" value="" name="savestatus" id="savestatus" />
    {{ csrf_field() }}


    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-header bg-primary text-white fw-semibold"></div>
      <div class="card-body card-block">


        <div class="row">
          <div class="col-md-6">
            <!-- Hidden IDs -->
            <input type="hidden" class="form-control" id="subinventory_id" name="subinventory_id"
              value="{!! $datas['subinventory_id'] !!}" readonly>
            <input type="hidden" class="form-control status" id="status" name="status" value="{!! $datas['status'] !!}" readonly>

            <!-- Subinventory Name -->
            <div class="form-group row mb-3">
              <label class="col-md-5 col-form-label">
                <span class="text-danger">*</span> Subinventory Name
              </label>
              <div class="col-md-7">
                <input type="text" id="subinventory_name" name="subinventory_name" class="form-control subinventory_name"
                  value="{!! $datas['subinventory_name'] !!}" required tabindex="1">
                <span class="btn btn-danger dup_name mt-1" style="display:none;"></span>
              </div>
            </div>

            <!-- Description -->
            <div class="form-group row mb-3">
              <label class="col-md-5 col-form-label">Description</label>
              <div class="col-md-7">
                <input type="text" id="description" name="description" class="form-control description"
                  value="{!! $datas['description'] !!}" tabindex="2">
              </div>
            </div>

            <!-- Production Store -->
            <div class="form-group row mb-3">
              <label class="col-md-5 col-form-label">
                <span class="text-danger">*</span> Production Store
              </label>
              <div class="col-md-7">
                <select name="production_store" class="form-control select2 production_store" tabindex="3" required>
                  <option value="">--- Please Select ---</option>
                  <option value="Yes" {{ $datas['production_store'] == 'Yes' ? 'selected' : '' }}>Yes</option>
                  <option value="No" {{ $datas['production_store'] == 'No' ? 'selected' : '' }}>No</option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <!-- Active -->
            <div class="form-group row mb-3">
              <label class="col-md-5 col-form-label">Active</label>
              <div class="col-md-7">
                <select name="active" class="form-control select2 active" tabindex="4">
                  <option value="Yes" {{ $datas['active'] == 'Yes' ? 'selected' : '' }}>Yes</option>
                  <option value="No" {{ $datas['active'] == 'No' ? 'selected' : '' }}>No</option>
                </select>
              </div>
            </div>

            <!-- Created By -->
            <div class="form-group row mb-3 none">
              <label class="col-md-5 col-form-label">Created By</label>
              <div class="col-md-7">
                <select name="created_by" class="form-control select2 created_by" id="created_by">
                  {!! $created_by !!}
                </select>
              </div>
            </div>
          </div>
        </div>



        <!-------------------------Linedata -------------------------------->
        <div class="row mt-2">
          <div class="col-md-12">

            <div id="preview-area" class="table-responsive">
              <table class="table table-bordered clone_table">
                <thead class="table-light">
                  <tr>

                    <th>Line No</th>
                    <th>Rack No </th>
                    <th>Row No</th>
                    <th>Bin No</th>
                    <th>Locator Code</th>
                    <th>Locator Name</th>
                    <th>Active</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="clone_lines_body">
                  <?php if (count($linedata) >= 1) { ?>
                  @foreach($linedata as $key => $value)
                                <tr class="clone rcopy">
                                  <td><input type="hidden" name="bulk_sublocator_id[]" class="form-control input-sm bulk_sublocator_id"
                                      value="{{$value->sublocator_id}}" value="">
                                    <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1"
                                      readonly="readonly">
                                  </td>
                                  <td><input type="text" name="bulk_rack_no[]" class="form-control input-sm bulk_rack_no nos"
                                      value="{{$value->rack_no}}" required></td>
                                  <td><input type="text" name="bulk_row_no[]" class="form-control input-sm bulk_row_no nos"
                                      value="{{$value->row_no}}" required> </td>
                                  <td><input type="text" name="bulk_bin_no[]" class="form-control input-sm bulk_bin_no nos"
                                      value="{{$value->bin_no}}" required></td>
                                  <td><input type="text" name="bulk_locator_code[]" class="form-control input-sm bulk_locator_code"
                                      value="{{$value->locator_code}}"></td>
                                  <td><input type="text" name="bulk_locator_name[]" class="form-control input-sm bulk_locator_name"
                                      value="{{$value->locator_name}}" required> </td>
                                  <td>
                                    <select name="bulk_active[]" class="select2 bulk_active " id="bulk_active" readonly>
                                      <option value="Yes" <?php    if ($value->active == 'Yes') {
                      echo "selected";
                    }?>>Yes</option>
                                      <option value="No" <?php    if ($value->active == 'No') {
                      echo "selected";
                    }?>>No</option>
                                    </select>
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

                  <tr class="cloneRow clone rcopy"> <?php  //dd("create");?>
                    <td><input type="hidden" name="bulk_sublocator_id[]" class="form-control input-sm bulk_sublocator_id"
                        value="">
                      <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1"
                        readonly="readonly">
                    </td>
                    <td><input type="text" name="bulk_rack_no[]" class="form-control input-sm bulk_rack_no nos" required>
                    </td>
                    <td><input type="text" name="bulk_row_no[]" class="form-control input-sm bulk_row_no nos" required>
                    </td>
                    <td><input type="text" name="bulk_bin_no[]" class="form-control input-sm bulk_bin_no nos" required>
                    </td>
                    <td><input type="text" name="bulk_locator_code[]" class="form-control input-sm bulk_locator_code">
                    </td>
                    <td><input type="text" name="bulk_locator_name[]" class="form-control input-sm bulk_locator_name"
                        required></td>
                    <td>
                      <select name="bulk_active[]" class="select2 bulk_active " id="bulk_active" readonly>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                      </select>
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
              <input type="hidden" name="enable-masterdetail" value="true">

              <div class="text-end">
                <button type="button" class="btn btn-success btn-sm add-row">
                  <i class="fas fa-plus-circle"></i> Add Row
                </button>
              </div>

            </div>
          </div>
        </div>


        <!-------------------------Linedata End-------------------------------->
        <div class="row mt-4 mb-3">
          <div class="col-lg-12 col-md-12">
            <div class="form-group text-center">
              <input type="hidden" name="submit_type" class="submit_type" id="submit_type">

              <button type="button" id="save" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
              <a class='btn btn-danger px-4' onclick="location.href ='{{URL::to('subinventory')}}'">Cancel</a>
            </div>
          </div>
        </div>

      </div>
    </div>
    <input type="hidden" class="pdtindex" value="" />

  </form>


@endsection
@push('scripts')


  <script>

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

    $(document).ready(function () {

      /*Duplicate Function*/
      var dup_chk = true;

      function duplicate_validate() {
        $('.ajaxLoading').hide();
        var subinventory_name = $(".subinventory_name").val();
        var edit_id = $("#subinventory_id").val();

        $.ajax({
          cache: false,
          url: "{{URL::to('subinventorycheckname/')}}", //this is your uri
          type: 'GET',
          dataType: 'json',
          async: false,
          data: { subinventory_name: subinventory_name, edit_id: edit_id },
          success: function (response) {
            console.log(response);
            if (response == 1) {
              $('.dup_name').html('Subinventory Name:' + subinventory_name + ' Already Exists');
              $('.dup_name').show();
              $(".subinventory_name").val('');
              dup_chk = false;

            }
            else if (response == 0) {
              var html = "";
              $('.dup_name').hide();
              //$(".ajaxLoading").show();
              dup_chk = true;

            }

          },
          error: function (xhr, resp, text) {
            console.log(xhr, resp, text);
          }
        });
      }
      /*End*/



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


      $('.organization_iddiv').css('pointer-events', 'none');
      $('.subinventory_name').on('keyup', function () {
        this.value = this.value.toUpperCase();
      });


      var url_name = "<?php echo ($pageurl); ?>";
      // alert(url_name);

      if (url_name == "subinventorycreate") {
        $('.subinventory_name').attr('readonly', false);
      }
      else if (url_name == "subinventoryedit") {
        $('.subinventory_name').attr('readonly', true);
      }


      /*Saveform Function*/
      $(document).on('click', '.saveform', function () {

        var btnval = $(this).val();
        $('.submit_type').val("save");

        var url = "{{ URL::to('subinventorysave') }}";
        var red_url = "{{ URL::to('subinventory') }}";
        var create_url = "{{ URL::to('subinventorycreate') }}";

        var formdata = $('#subinventoryform').serialize();
        var form = $('#subinventoryform');
        form.parsley().validate();
        duplicate_validate();
        if (form.parsley().isValid()) {
          if (dup_chk == true) {
            var $btn = $(this);            
			      $btn.prop('disabled', true);
            $.post(url, formdata, function (data) {

              var status = data.status;
              var msg = data.message;
              var id = data.id;
              var edit_url = "{{ URL::to('subinventorytransfer') }}/" + id;
              if (btnval != 'SAVE') {
                showCustomAlert(msg, status);
                setTimeout(function () {
                  window.location.href = create_url;
                }, 1500);
              }else {
                showCustomAlert(msg, status);
                setTimeout(function () {
                  window.location.href = red_url;
                }, 1500);
              }
            });
          }
        }


      });



      $('.bulk_locator_code').attr('readonly', true);

      var index = $(this).closest('tr').index();

      $(document).on('keyup', '.nos', function () {

        var index = $(this).closest('tr').index();

        var rackno = $('.bulk_rack_no' + index).val() ? $('.bulk_rack_no' + index).val() : 0;
        var rowno = $('.bulk_row_no' + index).val() ? $('.bulk_row_no' + index).val() : 0;
        var binno = $('.bulk_bin_no' + index).val() ? $('.bulk_bin_no' + index).val() : 0;
        var res = rackno + '-' + rowno + '-' + binno;
        var rowCount = $('.sub_inv_table tbody tr').length;
        $('.bulk_locator_code' + index).val(res);
        $(".bulk_bin_no").each(function (index1) {
          var locator = $('.bulk_locator_code' + index1).val();
          if (index1 != index) {
            if (res == locator) {
              showCustomAlert("Locator Code Already Exists", "info");
              $('.bulk_locator_code' + index).val('');
              $('.bulk_rack_no' + index).val('');
              $('.bulk_row_no' + index).val('');
              $('.bulk_bin_no' + index).val('');
            }
          }
        });

      });


      $(".applychanges1").click(function () {
        $('.submit_type').val("applychanges");
        var editid = $('.subinventory_id').val();
        var editurl = "{{URL::to('subinventoryedit')}}" + editid;
        var saveurl = "{{URL::to('subinventorysave')}}";
        var createurl = "{{URL::to('subinventorycreate')}}";
        draft_save('subinventoryform', saveurl, "{{URL::to('subinventory')}}", createurl, editurl);
      });



    });


  </script>

@endpush