@extends('layouts.header')
@section('content')
  <?php if ($pageMethod == "allocateengineer") {?>
  <h3 class="text-danger">Allocate Engineer</h3>
  <?php } else if ($pageMethod == "allocatetechnician") { ?>
  <h3 class="text-danger">Allocate Technician</h3>
  <?php  } else if ($pageMethod == "requestraise") {?>
  <h3 class="text-danger">Ticket Closure Request</h3>
  <?php  } else if ($pageMethod == "approverequest") { ?>
  <h3 class="text-danger">Ticket Closure Approval</h3>
  <?php  } else if ($pageMethod == "closerequest") {?>
  <h3 class="text-danger">Close Request</h3>
  <?php  } else if ($pageMethod == "sopupload") {?>
  <h3 class="text-danger">SOP</h3>
  <?php  } else { ?>
  <h3 class="text-danger">Ticket Generate</h3>
  <?php  } ?>
  @include('layouts.breadcrumb')


  <form autocomplete="off" action=" " id="user_form" class="user_form" data-parsley-validate autocomplete="off">
    {{ csrf_field() }}
    <input type="hidden" value="" name="request_status" id="request_status" />

    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-body card-block">
        <div class="row">
          <div class="col-md-4">
            <div class="row mb-3 ticket pagemethod">
              <label class="col-form-label col-md-4"><span class="text-danger">*</span>Ticket Number</label>
              <div class="col-md-8">
                <input type="text" name="ticket_number" id="causes" class="form-control ticket_number"
                  value="{{ $row->ticket_number }}" readonly tabindex="4">
              </div>
            </div>

            <div class="row mb-3 department_id pagemethod">
              <label class="col-form-label col-md-4"><span class="text-danger">*</span>Department</label>
              <div class="col-md-8">
                <input type="hidden" name="id" id="id" value="<?php echo $row->id; ?>" />
                <select name="department_id" class="form-select select2 department_id" id="department_id"
                  required>{!! $row->department_id !!}</select>
              </div>
            </div>

            <div class="row mb-3 machine_div">
              <label class="col-form-label col-md-4"><span class="text-danger">*</span>Machine</label>
              <div class="col-md-8 pagemethod">
                <select name="machine_id" class="form-select select2 machine_id" required id="machine_id"></select>
              </div>
              <div class="col-md-1 showinline bdmachine_div">
                <div data-bs-toggle="modal" data-bs-target="#bdmachine"
                  style="display: inline-block; background-color: #194bdc; color: #fff;cursor: pointer;">
                  <i class="fa fa-plus" aria-hidden="true"></i>
                </div>
              </div>

            </div>

            <div class="row mb-3 pagemethod">
              <label class="col-form-label col-md-4"><span class="text-danger">*</span>Breakdown Type</label>
              <div class="col-md-8">
                <select name="break_type_id" class="form-select select2 break_type_id"
                  required>{!! $row->break_type_id !!}</select>
              </div>
            </div>

            <?php if ($pageMethod == "requestraise" || $pageMethod == "approverequest") {
    $request_remark_re = ($pageMethod == "approverequest") ? 'readonly' : ''; ?>
            <div class="row mb-3 requestraise">
              <label class="col-form-label col-md-4" for="request_remark"><span class="text-danger">*</span>Request
                Remarks</label>
              <div class="col-md-8">
                <input class="form-control request_remark" id="request_remark" name="request_remark" type="text" <?php  echo $request_remark_re; ?> value="{{ $row->request_remark }}" required>
              </div>
            </div>
            <?php } ?>

            <div class="row mb-3 closerequest">
              <label class="col-form-label col-md-4"><span class="text-danger">*</span>Critical Spares Used</label>
              <div class="col-md-8 sel2">
                <select name="critical_spare" class="form-select select2 critical_spare">
                  <option {{$row->critical_spare == "" ? "selected" : "" }} value="">--Please Select--</option>
                  <option {{$row->critical_spare == "Yes" ? "selected" : "" }} value="Yes">Yes</option>
                  <option {{$row->critical_spare == "No" ? "selected" : "" }} value="No">No</option>
                  <option {{$row->critical_spare == "Other" ? "selected" : "" }} value="Other">Other</option>
                  {!!$row->critical_spare !!}
                </select>
              </div>
            </div>

            <div class="row mb-3 closerequest">
              <label class="col-form-label col-md-4"><span class="text-danger">*</span>Corrective Action</label>
              <div class="col-md-8">
                <textarea name="corrective_action"
                  class="form-control corrective_action">{{ $row->corrective_action }}</textarea>
              </div>
            </div>

            <div class="row mb-3 sopupload">
              <label class="col-form-label col-md-4"><span class="text-danger">*</span>Error Code</label>
              <div class="col-md-8">
                <textarea name="error_code" class="form-control error_code">{{ $row->error_code }}</textarea>
              </div>
            </div>

            <div class="row mb-3 others">
              <label class="col-form-label col-md-4"><span class="text-danger">*</span>Other Spares</label>
              <div class="col-md-8">
                <input type="text" name="others" class="form-control others" value="{{ $row->others }}" tabindex="4">
              </div>
            </div>
          </div>


          <div class="col-md-4">
            <div class="row mb-3 pagemethod">
              <label for="inputIsValid" class="col-form-label col-md-4"><span style="color:red">*</span>Maintenance
                Type</label>
              <div class="col-md-8 sel2">
                <select name='maintenance_type' rows='5' class='form-control select2 maintenance_type' required>
                  <option {{$row->maintenance_type == "Machine" ? "selected" : "" }} value="Machine">Machine</option>
                  <option {{$row->maintenance_type == "Facility" ? "selected" : "" }} value="Facility">Facility</option>
                  {!!$row->maintenance_type !!}
                </select>
              </div>
            </div>


            <div class="row mb-3 active pagemethod">
              <label for="inputIsValid" class="col-form-label col-md-4"><span style="color:red">*</span>Active</label>
              <div class="col-md-8 sel2">
                <select name='active' rows='5' class='form-control select2 active' required>
                  <option {{$row->active == "Yes" ? "selected" : "" }} value="Yes">Yes</option>
                  <option {{$row->active == "No" ? "selected" : "" }} value="No">No</option>
                  {!!$row->active !!}
                </select>
              </div>

            </div>

            <div class="row mb-3 pagemethod">
              <label for="inputIsValid" class="col-form-label col-md-4"><span style="color:red">*</span>Shift</label>
              <div class="col-md-8 sel2">
                <select name='shift' rows='5' class='form-control select2 active' required>
                  <option {{$row->shift == "1" ? "selected" : "" }} value="1">1st Shift</option>
                  <option {{$row->shift == "2" ? "selected" : "" }} value="2">2nd Shift</option>
                  <option {{$row->shift == "3" ? "selected" : "" }} value="3">3rd Shift</option>
                  <option {{$row->shift == "4" ? "selected" : "" }} value="4">4th Shift</option>
                  {!!$row->shift !!}
                </select>
              </div>

            </div>
            <div class="row mb-3 engineer">
              <label for="inputIsValid" class="col-form-label col-md-4"><span style="color:red">*</span>Allocate
                Engineer</label>
              <div class="col-md-8">
                <select name='engineer' rows='5' class='form-control select2 engineer'>
                  {!!$row->engineer !!}
                </select>
              </div>

            </div>
            <div class="row mb-3 closerequest">
              <label for="inputIsValid" class="col-form-label col-md-4">Repair Start Date</label>
              <div class="col-md-8">
                <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy"
                  data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="form-control start_date_time1" id="start_date" name="start_date" size="16" type="text"
                    value="{{ $row->start_date }}" tabindex="5" readonly>
                  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>

              </div>

            </div>
            <div class="row mb-3 closerequest">
              <label for="inputIsValid" class="col-form-label col-md-4">Repair End Date</label>
              <div class="col-md-8">
                <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy"
                  data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="form-control start_date_time1" id="end_date" name="end_date" size="16" type="text"
                    value="{{ $row->end_date }}" tabindex="5" readonly>
                  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
              </div>
            </div>
          </div>



          <div class="col-md-4">
            <div class="row mb-3 pagemethod none">
              <label class="col-form-label col-md-4" for="issue_date"><span style="color:red;">*</span>Ticket Date</label>
              <div class="col-md-8">
                <div class="input-group date form_date col-md-12 datehide">
                  <input class="form-control datetimepicker1 issue_date" id="issue_date" name="issue_date" size="16"
                    type="text" value="{{ $row->issue_date }}" required>

                </div>
              </div>
              <div class="col-md-2"></div>
            </div>


            <div class="row mb-3 pagemethod">
              <label for="inputIsValid" class="col-form-label col-md-4"><span style="color:red">*</span>Causes of
                Breakdown</label>
              <div class="col-md-8">

                <textarea name="causes" id="causes" cols="50" class="form-control causes"
                  tabindex="4"> {!!$row->causes !!}</textarea>
              </div>

            </div>
            <div class="row mb-3 pagemethod">
              <label for="inputIsValid" class="col-form-label col-md-4"><span style="color:red">*</span>Breakdown
                Severity</label>
              <div class="col-md-8 sel2">
                <select name='breakdown_severity' rows='5' class='form-control select2 breakdown_severity' required>
                  {!!$row->breakdown_sevearity !!}
                </select>
              </div>

            </div>
            <div class="row mb-3 technician_div">
              <label for="inputIsValid" class="col-form-label col-md-4">Allocate Technician</label>
              <div class="col-md-8">
                <select multiple id="technician" name='technician[]' rows='5' class='select2 technician'
                  data-show-subtext="true" data-live-search="true">
                  {!! $row->technician !!}
                </select>
              </div>
            </div>

            <?php if ($pageMethod == "approverequest") { ?>
            <div class="row mb-3 approverequest">
              <label class="col-form-label col-md-4" for="approve_remarks"><span
                  style="color:red;">*</span>Remarks</label>
              <div class="col-md-8">
                <div class="input-group col-md-12 ">
                  <input class="form-control approve_remarks" id="approve_remarks" name="approve_remarks" size="16"
                    type="text" value="{{ $row->approve_remarks }}" required>

                </div>
              </div>
              <div class="col-md-2"></div>
            </div>
            <?php } ?>




            <div class="row mb-3 closerequest">
              <label for="inputIsValid" class="col-form-label col-md-4"><span style="color:red">*</span>Preventive
                Action</label>
              <div class="col-md-8">
                <input name="preventive_action" id="preventive_action" class="form-control preventive_action"
                  value="{{ $row->preventive_action }}">

              </div>

            </div>
            <div class="row mb-3 closerequest">
              <label for="inputIsValid" class="col-form-label col-md-4"><span style="color:red">*</span>Is Breakdown
                ?</label>
              <div class="col-md-8 sel2">
                <select name='is_breakdown' rows='5' class='form-control select2 is_breakdown' required>
                  <option {{$row->is_breakdown == "Yes" ? "selected" : "" }} value="Yes">Yes</option>
                  <option {{$row->is_breakdown == "No" ? "selected" : "" }} value="No">No</option>
                </select>
              </div>

            </div>
            <div class="col-md-6 sopupload">
              <div class="row mb-3">
                <label for="active" class="col-form-label col-md-4 ">Attach File</label>
                <div class="col-md-8">
                  <input type="file" id="choosefile" name="choosefile" class="choosefile" tabindex="6">

                </div>
              </div>
            </div>
          </div>
          <!-- ... Continue with col-md-4 and col-md-4 sections for the remaining fields, preserving structure ... -->
        </div>

        <div class="row linesdiv">
          <div class="col-12 linetable">
            <div class="table-responsive">
              <table class="table table-bordered company_table">
                <thead class="table-light">
                  <tr>
                    <th style="width: 80px;">Line No</th>
                    <th>Spare Name</th>
                    <th>Inventory Stock</th>
                    <th>Quantity</th>
                    <th style="width: 60px;"></th>
                  </tr>
                </thead>
                <tbody class="company_lines_body">
                  <?php if ($parent_id >= 1) { ?>
                  @foreach($productdata as $key => $value)
                    <tr>

                    </tr>
                  @endforeach
                  <?php  }
  if ($parent_id < 1) { 
                              ?>

                  <tr class="rcopy clone">

                    <td>
                      <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1"
                        readonly="readonly">
                    </td>
                    <td>
                      <select name='bulk_spares_id[]' rows='5' class='form-control bulk_spares_id select2'
                        data-show-subtext="true" data-live-search="true">
                        {!!$row->spares_id !!}
                      </select>
                    </td>
                    <td>
                      <input type="text" name="bulk_inventory_stock[]" class="form-control input-sm bulk_inventory_stock"
                        data-value="0" value="" readonly="true">

                    </td>
                    <td>
                      <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty" data-value="0">
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
            </div>

          </div>
        </div>

        <!-- Submit Button Section -->
        <div class="row mt-4">
          <div class="col-lg-12 col-md-12">
            <div class="form-group text-center">
              <?php if ($pageMethod == "requestraise") {?>
              <button name="submit" type="button" class="btn btn-primary saveform px-4 me-2"
                value="REQUESTED">Request</button>
              <?php } else if ($pageMethod == "approverequest") {?>
              <button name="submit" type="button" class="btn btn-success saveform px-4 me-2"
                value="APPROVED">Approve</button>
              <button name="submit" type="button" class="btn btn-danger saveform px-4 me-2"
                value="REJECTED">Reject</button>
              <?php  } else if ($pageMethod == "closerequest") {?>
              <button name="submit" type="button" class="btn btn-success saveform px-4 me-2" value="CLOSED">Close</button>
              <?php  } else if ($pageMethod == "allocateengineer") {?>
              <button name="submit" type="button" class="btn btn-success saveform px-4 me-2" value="INITIATED">Allocate
                Engineer</button>
              <?php  } else if ($pageMethod == "allocatetechnician") {?>
              <button name="submit" type="button" class="btn btn-info saveform px-4 me-2" value="INITIATED">Allocate
                Technician</button>
              <?php  } else { ?>
              <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
              <button name="submit" type="button" class="btn btn-success saveform px-4 me-2" value="SAVE">Save</button>
              <?php  } ?>
              <a class="btn btn-secondary px-4 me-2" href="{{ url($pageMethod) }}">Cancel</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </form>
  <!-- pop up -->
  <div class="modal fade" id="bdmachine" tabindex="-1" aria-labelledby="bdmachineLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- modal-lg for wider layout -->
      <div class="modal-content shadow rounded-4">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="bdmachineLabel">Breakdowns</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
              <thead class="table-warning">
                <tr>
                  <th>Ticket Number</th>
                  <th>Issue Date</th>
                  <th>Maintenance Type</th>
                  <th>Corrective Action</th>
                  <th>Preventive Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1;
  foreach ($lastmaintenance as $k => $v) { ?>
                <tr class="table-light">
                  <td>{{ $v->ticket_number }}</td>
                  <td>{{ $v->issue_date }}</td>
                  <td>{{ $v->maintenance_type }}</td>
                  <td>{{ $v->corrective_action }}</td>
                  <td>{{ $v->preventive_action }}</td>
                </tr>
                <?php  $i++;
  } ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="modal-footer justify-content-end">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>


@endsection
@push('scripts')



  <script>

    // Add Row
    $(document).on('click', '.add-row', function () {
      const $lastRow = $('.company_lines_body tr:last');
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
      $('.company_lines_body').append($newRow);

      // Reinitialize select2
      $newRow.find('select.select2').select2({ width: '100%' });

      // Update line numbers
      updateLineNumbers();
    });



    // Remove button
    $(document).on('click', '.remove-row', function () {
      const rowCount = $('.company_lines_body tr').length;
      if (rowCount > 1) {
        $(this).closest('tr').remove();
        updateLineNumbers();
      } else {
        showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
      }
    });

    // Renumber Line Nos
    function updateLineNumbers() {
      $('.company_lines_body tr').each(function (index) {
        $(this).find('.bulk_line_no').val(index + 1);
      });
    }


    $(document).on('change', '#department_id', function () {
      $('.machine_div').css('pointer-events', 'auto');
      $(".machine_div").show();

      var id = $(this).val();

      if (id != '') {
        var url = "{{ URL::to('jcomboform1') }}?table=w_machine_hdr_t:machine_hdr_id:machine_code|machine_name&parent=and department_id=" + id + "&order_by=machine_hdr_id asc";

        $.ajax({
          url: url,
          type: 'GET',
          success: function (data) {
            if (typeof data === "string") {
              try {
                data = JSON.parse(data);
              } catch (e) {
                console.error("Invalid JSON response:", data);
                return;
              }
            }

            $('#machine_id').html('<option value="">-- Select Machine --</option>');

            $.each(data, function (i, item) {
              let selected = item.val == "{{ $row->machine_id ?? '' }}" ? 'selected' : '';
              $('#machine_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
            });

            $('#machine_id').trigger('change.select2');
          }
        });
      }
    });


    $(document).ready(function () {

      var condition2 = '';

      $('.bdmachine_div').css('pointer-events', 'auto');
      $('.machine_div').css('pointer-events', 'none');
      var page = '<?php echo $pageMethod; ?>';

      if (page == "createissue") {

        $('.pagemethod,.technician_div,.engineer').attr('readonly', false);
        $('.pagemethod').css('pointer-events', 'auto');
        $('.closerequest,.technician_div,.engineer,.bdmachine_div,.sopupload,.ticket,.active').hide();

      } else if (page == "allocateengineer") {
        $('.pagemethod').attr('readonly', true);
        $('.pagemethod').css('pointer-events', 'none');
        $('.bdmachine_div').css('pointer-events', 'auto');
        $('.engineer').prop('required', true);
        $('.closerequest,.sopupload,.active').hide();

      }
      else if (page == "allocatetechnician") {

        $('.pagemethod').attr('readonly', true);
        $('.pagemethod').css('pointer-events', 'none');
        $('.bdmachine_div').css('pointer-events', 'auto');
        $('.closerequest,.sopupload,.active').hide();

      } else if (page == "sopupload") {
        $('.pagemethod').attr('readonly', true);
        $('.bdmachine_div').css('pointer-events', 'auto');
        $('.pagemethod,.engineer,.technician_div,.closerequest').css('pointer-events', 'none');
        $('.closerequest').show();
        $('.active').hide();
        $('.critical_spare,.preventive_action,.corrective_action').attr('required', true);
      } else if (page == "closerequest") {
        $('.pagemethod').attr('readonly', true);
        $('.bdmachine_div').css('pointer-events', 'auto');
        $('.pagemethod,.engineer,.technician_div').css('pointer-events', 'none');
        $('.closerequest').show();
        $('.active').hide();
        $('.critical_spare,.preventive_action,.corrective_action').attr('required', true);

        $('.sopupload').hide();


        /*Lines Same Spare name duplicate validation and Image show function */
        $(document).on('change', '.bulk_spares_id', function () {
          var index = ($(this).closest('tr').index());
          var spares_id = $(this).val();
          var pdtcount = pdtcheck(spares_id, index);
          var url1 = "{{URL::to('')}}/upload/spares/";
          if (pdtcount <= 0) {
            var url = "{{URL::to('getspareqty')}}/" + spares_id;
            $.get(url, function (data) {
              // alert(data[0].qty);
              if ((data[0].qty) > 0 && data[0].qty != "NULL") {
                qty = data[0].qty
              } else {
                qty = 0;
              }
              $(".bulk_inventory_stock" + index).val(qty);

            });
          }
          else {
            var msg = $(".bulk_spares_id" + index + ' option:selected').text();
            var message = '<span style="color:#fdff65">' + msg + '</span>' + ' Spare Already Selected';
            notyMsgs('info', message);
            rowdataEmpty(index);
            $(".bulk_spares_id" + index).select2('val', ['']);
          }
        });

        /*End*/

      } else {
        $('.pagemethod').attr('readonly', true);
        $('.pagemethod,.engineer,.technician_div').css('pointer-events', 'none');
        $('.closerequest').hide();
        $('.sopupload').hide();
      }
      $('.others').hide();
      $('.linesdiv').hide();
      $('.critical_spare').change(function () {
        var val = $(this).val();
        if (val == "Other") {
          $('.others').show();
          $('.linesdiv').hide();
          $('.others').prop('required', true);
        } else if (val == "Yes") {
          $('.others').hide();
          $('.linesdiv').show();
          $('.others').prop('required', false);

        } else {
          $('.others').hide();
          $('.linesdiv').hide();
          $('.others').prop('required', false);

        }
      })



      /* Ajith Number Validation */
      $(document).on('keypress', '.bulk_qty', function (ev) {
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
          return true;
        }
        ev.preventDefault();
        return false;

      });
      /*End*/

      /* Ajith Quantity not Greater than Inventory Stock function*/
      $(document).on('change', '.bulk_qty', function () {
        var index = $(this).closest('tr').index();
        var qty = $(".bulk_qty" + index).val();

        var stock = $.trim($(".bulk_inventory_stock" + index).val());
        if (qty > stock) {

          showCustomAlert("Quantity should not be Greater than Inventory Stock", "warning");
          $(".bulk_qty" + index).val('');
        }

      });
      /*End*/

      /**** To Empty the Rowdata when product Empty ********/
      function rowdataEmpty(index) {
        $(".bulk_spares_id" + index).val('').change();
        $(".bulk_inventory_stock" + index).val('');
        $(".bulk_qty" + index).val('');
        $(".bulk_upload_image" + index).val('');
      }


      $('.read').css('pointer-events', 'none');
      $('.company').css('pointer-events', 'none');


      $('#request_status').val('');
      // Save Form

      $(document).on('click', '.saveform', function () {

        var btnval = $(this).val();
        $('#request_status').val(btnval);
        var form = $('#user_form');

        var red_url = "{{ url($pageMethod) }}";
        form.parsley().validate();
        if (form.parsley().isValid()) {

          var $btn = $(this);
          $btn.prop('disabled', true);

          var form_data = new FormData(document.getElementById('user_form'));
          $.ajax({
            url: "{{ URL::to('issuesave') }}",
            type: "POST",
            data: form_data,
            enctype: 'multipart/form-data',
            processData: false,  /*tell jQuery not to process the data*/
            contentType: false,   /*tell jQuery not to set contentType*/
            async: true,
            xhr: function () {
              var xhr = $.ajaxSettings.xhr();
              if (xhr.upload) {
                xhr.upload.addEventListener('progress', function (event) {
                  var percent = 0;
                  var position = event.loaded || event.position;
                  var total = event.total;
                  if (event.lengthComputable) {
                    percent = Math.ceil(position / total * 100);
                  }
                }, true);
              }
              return xhr;

            }
          }).done(function (data, status) {
            console.log(data);
            var msg = data.message;
            showCustomAlert("Saved Successfully !", "success");
            setTimeout(() => {
              window.location.href = red_url;
            }, 1500);

          }).fail(function (data, status) {
            $(".alert-success").hide();
            $(".alert-danger").fadeIn(800);

          });
        }


      });

      var id = "{{$row->id}}";
      if (id != '') {
        $('.department_id').trigger('change');
      }
    });


    $(document).ready(function () {


      var dateToday = new Date();
      var data = "{{\Session::get('j_date_format')}}";
      var startt_date = "{{date('Y-m-d', strtotime($row->issue_date))}}";
      const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
      const timeFormat = "HH:mm:ss";
      $(document).on("focus", ".start_date_time1", function () {
        $(this).datetimepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: dateFormat,
          timeFormat: timeFormat,
          controlType: 'select',
          oneLine: true,
          showSecond: true,
          minDate: startt_date,
          maxDate: +7,
          showAnim: "slideDown",
          yearRange: "-25:+0",
          onClose: function (selectedDateTime) {
            if (selectedDateTime) {
              const startDate = $(this).datetimepicker("getDate");

              // Reinitialize end_date_time with updated minDate
              $(".end_date_time1").datetimepicker("destroy").datetimepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: dateFormat,
                timeFormat: timeFormat,
                controlType: 'select',
                oneLine: true,
                showSecond: true,
                minDate: dateToday, // Disallow dates before start time
                maxDate: 0,
                showAnim: "slideDown",
                yearRange: "-25:+0"
              });
            }
          }
        });
      });
    });	
  </script>
@endpush