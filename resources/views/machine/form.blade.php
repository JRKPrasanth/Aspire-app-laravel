@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Machine</h3>
  @include('layouts.breadcrumb')


  <form method="post" action="" id="machine" enctype='multipart/form-data' data-parsley-validate>
    <input type="hidden" value="" name="savestatus" id="savestatus" />

    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">

      <div class="card-body card-block headerdiv1">
        <!------------------------------------- Body content start here ---------------------------->

        <div class="row g-4">

          <!-- Left Column -->
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label">Machine Code <span class="text-danger">*</span></label>
              <input type="hidden" class="form-control" id="machine_hdr_id" name="machine_hdr_id"
                value="{{ $row->machine_hdr_id }}">
              <input type="text" class="form-control" id="machine_code" name="machine_code"
                value="{{ $row->machine_code }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Machine Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control machine_name" id="machine_name" name="machine_name"
                value="{{ $row->machine_name }}" required>
              <span class="btn btn-sm btn-danger mt-2 dup_name" style="display:none;"></span>
            </div>

            <div class="mb-3">
              <label class="form-label">Department Name <span class="text-danger">*</span></label>
              <div class="d-flex">
                <select name="department_id" id="department_id" class="form-select select2 flex-grow-1 department_id"
                  required>
                  {!! $department_id !!}
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Location <span class="text-danger">*</span></label>
              <div class="d-flex">
                <select name="locationid" id="locationid" class="form-select select2 flex-grow-1 locationid" required>
                  {!! $locationid !!}
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Critical</label>
              <select name="critical" class="form-select critical select2">
                <option {{ $row->critical == "high" ? "selected" : "" }} value="high">High</option>
                <option {{ $row->critical == "medium" ? "selected" : "" }} value="medium">Medium</option>
                <option {{ $row->critical == "low" ? "selected" : "" }} value="low">Low</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Machine Remarks</label>
              <textarea name="remarks" id="remarks" class="form-control remarks" rows="3">{{ $row->remarks }}</textarea>
            </div>
          </div>

          <!-- Middle Column -->
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label">Assigned To <span class="text-danger">*</span></label>
              <select multiple name="assigned_to[]" id="assigned_to" class="form-select select2 assigned_to" required>
                {!! $assigned_to !!}
              </select>
            </div>

            <div class="mb-3" style="pointer-events:none;">
              <label class="form-label">Created By</label>
              <select name="created_by" id="created_by" class="form-select select2 created_by">
                {!! $created_by !!}
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Machine Capacity</label>
              <input type="text" class="form-control capacity" name="capacity" id="capacity" value="{{ $row->capacity }}">
            </div>

            <div class="mb-3">
              <label class="form-label">Machine Cost</label>
              <input type="text" class="form-control machine_cost" name="machine_cost" id="machine_cost"
                value="{{ $row->cost }}">
            </div>

            <div class="mb-3">
              <label class="form-label">Upload Image</label>
              <input type="file" class="form-control choosefile" name="choosefile" id="">
            </div>

            <div class="mb-3">
              <label class="form-label">Active <span class="text-danger">*</span></label>
              <select name="active" class="form-select select2 active" required>
                <option value="">--Please Select--</option>
                <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
              </select>
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label">Electricity Cost <span class="text-danger">*</span></label>
              <input type="text" class="form-control electricity_cost" name="electricity_cost" id="electricity_cost"
                value="{{ $row->electricity_cost }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Asset Code</label>
              <input type="text" class="form-control asset_code" name="asset_code" id="asset_code"
                value="{{ $row->asset_code }}">
            </div>

            <div class="mb-3">
              <label class="form-label">Relocated Date</label>
              <input type="text" class="form-control relocated_date start_date" name="relocated_date" id="relocated_date"
                value="{{ $row->relocated_date }}">
            </div>

            <div class="mb-3">
              <label class="form-label">Purchased Date</label>
              <input type="text" class="form-control purchased_date start_date" name="purchased_date" id="purchased_date"
                value="{{ $row->purchased_date }}">
            </div>

            <div class="mb-3">
              <label class="form-label">Machine Make</label>
              <input type="text" class="form-control machine_make" name="machine_make" id="machine_make"
                value="{{ $row->machine_make }}">
            </div>
          </div>
        </div>





        <div class="row g-4">
          <div class="col-12">
            <h3 class="fw-bold text-primary border-bottom pb-2">Vendor Details</h3>
          </div>

          <!-- Vendor -->
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label">
                <span class="text-danger">*</span> Vendor
              </label>
              <div class="input-group">
                <select name="vendor_id" class="form-select vendor_id select2" required>
                  {!! $vendor_id !!}
                </select>
              </div>
            </div>
          </div>

          <!-- AMC Vendor -->
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label">
                <span class="text-danger">*</span> AMC Vendor
              </label>
              <div class="input-group">
                <select name="amc_vendor_id" class="form-select select2 amc_vendor_id" required>
                  {!! $amc_vendor_id !!}
                </select>
              </div>
            </div>
          </div>

          <!-- Renewal Date -->
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label">Renewal Date</label>
              <div class="input-group">
                <input type="text" class="form-control renewal_date start_date" id="renewal_date" name="renewal_date"
                  value="{{ $row->renewal_date }}">
              </div>
            </div>
          </div>

          <!-- From Date -->
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label">From Date</label>
              <div class="input-group">
                <input type="text" class="form-control from_date start_date" id="from_date" name="from_date"
                  value="{{ $row->from_date }}">
              </div>
            </div>
          </div>

          <!-- To Date -->
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label">To Date</label>
              <div class="input-group">
                <input type="text" class="form-control to_date start_date" id="to_date" name="to_date"
                  value="{{ $row->to_date }}">
              </div>
            </div>
          </div>
        </div>




        <!-- add machine end -->

        <div class="row mt-4">
          <div class="col-md-12">

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-3" id="machineTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="prdtype-tab" data-bs-toggle="tab" data-bs-target="#prdtype1"
                  type="button" role="tab">
                  Product Type With Capacity
                </button>
              </li>

              <li class="nav-item" role="presentation">
                <button class="nav-link" id="prevmaintenance-tab" data-bs-toggle="tab" data-bs-target="#prevmaintenance1"
                  type="button" role="tab">
                  Preventive Maintenance
                </button>
              </li>
            </ul>

            <div class="tab-content">

              <!-- ================= TAB 1: Product Type With Capacity ================= -->
              <div class="tab-pane fade show active" id="prdtype1" role="tabpanel">
                <div class="col-12 linetable">
                  <div class="table-responsive">
                    <table class="table table-bordered clone_table">
                      <thead class="table-light">
                        <tr>
                          <th style="width:60px;">#</th>
                          <th>Product Type</th>
                          <th>Machine Capacity</th>
                          <th style="width:90px;">Action</th>
                        </tr>
                      </thead>

                      <tbody class="clone_lines_body">
                        @if(count($linedata) >= 1)
                          @foreach($linedata as $value)
                            <tr class="rcopy clone">
                              <td class="text-center">
                                <span class="bulk_line_no">{{ $loop->iteration }}</span>
                                <input type="hidden" name="bulk_machine_line_id[]" value="{{ $value->machine_line_id }}">
                              </td>

                              <td>
                                <select name="bulk_product_type_id[]" class="form-select bulk_product_type_id select2"
                                  required>
                                  {!! $value->product_type_id !!}
                                </select>
                              </td>

                              <td>
                                <input type="text" name="bulk_machine_capacity[]" class="form-control bulk_machine_capacity"
                                  value="{{ $value->machine_capacity }}" required>
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
                            <td class="text-center">
                              <span class="bulk_line_no">1</span>
                              <input type="hidden" name="bulk_machine_line_id[]" value="">
                            </td>

                            <td>
                              <select name="bulk_product_type_id[]" class="form-select bulk_product_type_id select2"
                                required>
                                {!! $product_type_id !!}
                              </select>
                            </td>

                            <td>
                              <input type="text" name="bulk_machine_capacity[]" class="form-control bulk_machine_capacity"
                                required>
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

              <!-- ================= TAB 2: Preventive Maintenance ================= -->
              <div class="tab-pane fade" id="prevmaintenance1" role="tabpanel">
                <div class="table-responsive linetable">
                  <table class="table table-bordered clone_table1">
                    <thead class="table-light">
                      <tr>
                        <th style="width:60px;">#</th>
                        <th>Frequency</th>
                        <th>Date</th>
                        <th style="width:90px;">Action</th>
                      </tr>
                    </thead>

                    <tbody class="clone_lines_body1">
                      @if(count($linedata) >= 1)
                        @foreach($linedata as $value)
                          @php
                            $freqdate = ($value->frequency_date != "01-01-1970") ? $value->frequency_date : "";
                          @endphp

                          <tr class="rcopy1 clone">
                            <td class="text-center">
                              <span class="bulk_line_no1">{{ $loop->iteration }}</span>
                              {{-- IMPORTANT: Different name to avoid collision --}}
                              <input type="hidden" name="bulk_machine_line_id_pm[]" value="{{ $value->machine_line_id }}">
                            </td>

                            <td>
                              <select name="bulk_frequency_id[]" class="form-select select2 bulk_frequency_id" required>
                                {!! $value->frequency_id !!}
                              </select>
                            </td>

                            <td>
                              <input type="text" name="bulk_frequency_date[]" class="form-control bulk_frequency_date"
                                value="{{ $freqdate }}" autocomplete="off" required>
                            </td>

                            <td class="text-center">
                              <button type="button" class="btn btn-sm btn-danger remove-row1">
                                <i class="fas fa-minus-circle"></i>
                              </button>
                            </td>
                          </tr>
                        @endforeach
                      @else
                        <tr class="rcopy1 clone">
                          <td class="text-center">
                            <span class="bulk_line_no1">1</span>
                            <input type="hidden" name="bulk_machine_line_id_pm[]" value="">
                          </td>

                          <td>
                            <select name="bulk_frequency_id[]" class="form-select select2 bulk_frequency_id" required>
                              {!! $frequency_id !!}
                            </select>
                          </td>

                          <td>
                            <input type="text" name="bulk_frequency_date[]" class="form-control bulk_frequency_date"
                              autocomplete="off" required>
                          </td>

                          <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger remove-row1">
                              <i class="fas fa-minus-circle"></i>
                            </button>
                          </td>
                        </tr>
                      @endif
                    </tbody>
                  </table>

                  <div class="text-end">
                    <button type="button" class="btn btn-success btn-sm add-row1">
                      <i class="fas fa-plus-circle"></i> Add Row
                    </button>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="row mt-4 mb-3">
          <div class="col-lg-12 col-md-12">
            <div class="form-group text-center">
              <button type="button" class="btn btn-success saveform px-4 me-2" value="SAVE">Save</button>
              <a href="{{ URL::to('machine') }}" class='btn btn-danger px-4 me-2'>Cancel</a>
            </div>
          </div>
        </div>


@endsection
      @push('scripts')

        <script>

          // ================== TABLE 1 ==================
          $(document).on('click', '.add-row', function () {
            const $lastRow = $('.clone_lines_body tr:last');
            const $newRow = $lastRow.clone(false, false);

            // Clear inputs
            $newRow.find('input').val('');
            $newRow.find('select').val('').trigger('change');

            // Clear line number span
            $newRow.find('.bulk_line_no').text('');

            // Destroy select2 before append
            $newRow.find('select.select2').each(function () {
              if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
                $(this).select2('destroy');
              }
              $(this).removeAttr('data-select2-id');
              $(this).next('.select2').remove();
            });

            $('.clone_lines_body').append($newRow);

            // Reinit select2
            $newRow.find('select.select2').select2({ width: '100%' });

            updateLineNumbersTable1();
          });

          $(document).on('click', '.remove-row', function () {
            const rowCount = $('.clone_lines_body tr').length;
            if (rowCount > 1) {
              $(this).closest('tr').remove();
              updateLineNumbersTable1();
            } else {
              showCustomAlert("You Can't Delete. At least one row should be there.", "warning");
            }
          });

          function updateLineNumbersTable1() {
            $('.clone_lines_body tr').each(function (index) {
              $(this).find('.bulk_line_no').text(index + 1);
            });
          }

          // ================== TABLE 2 ==================
          $(document).on('click', '.add-row1', function () {
            const $lastRow = $('.clone_lines_body1 tr:last');
            const $newRow = $lastRow.clone(false, false);

            // Clear inputs
            $newRow.find('input').val('');
            $newRow.find('select').val('').trigger('change');

            // Clear line number span
            $newRow.find('.bulk_line_no1').text('');

            // Datepicker reinit
            $newRow.find('.bulk_frequency_date').each(function () {
              if ($(this).hasClass('hasDatepicker')) {
                $(this).removeClass('hasDatepicker').removeAttr('id');
              }
              $(this).datepicker({ dateFormat: 'dd-mm-yy' });
            });

            // Destroy select2 before append
            $newRow.find('select.select2').each(function () {
              if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
                $(this).select2('destroy');
              }
              $(this).removeAttr('data-select2-id');
              $(this).next('.select2').remove();
            });

            $('.clone_lines_body1').append($newRow);

            // Reinit select2
            $newRow.find('select.select2').select2({ width: '100%' });

            updateLineNumbersTable2();
          });

          $(document).on('click', '.remove-row1', function () {
            const rowCount = $('.clone_lines_body1 tr').length;
            if (rowCount > 1) {
              $(this).closest('tr').remove();
              updateLineNumbersTable2();
            } else {
              showCustomAlert("You Can't Delete. At least one row should be there.", "warning");
            }
          });

          function updateLineNumbersTable2() {
            $('.clone_lines_body1 tr').each(function (index) {
              $(this).find('.bulk_line_no1').text(index + 1);
            });
          }

          // Run once on page load (important when editing)
          $(document).ready(function () {
            updateLineNumbersTable1();
            updateLineNumbersTable2();
          });




          var dup_chk = true;
          function duplicate_validate() {
            $('.ajaxLoading').hide();
            var machine_name = $(".machine_name").val();
            var edit_id = $("#machine_hdr_id").val();

            $.ajax({
              cache: false,
              url: "{{URL::to('machinenamechk')}}",
              type: 'GET',
              dataType: 'json',
              async: false,
              data: { machine_name: machine_name, edit_id: edit_id },
              success: function (response) {
                if (response == 1) {
                  $('.dup_name').html('Machine Name:' + machine_name + ' Already Exists');
                  $('.dup_name').show();
                  $(".machine_name").val('');
                  dup_chk = false;

                }
                else if (response == 0) {
                  var html = "";
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
          function capacityrequiredvalid() {
            $('.bulk_machine_capacity').each(function (i) {
              var val = $(this).val();
              if (val == 0) {
                $('.bulk_machine_capacity' + i).val('');
              }
            });
          }



          /* purpose:to check product type already selected for next item*/
          $(".bulk_product_type_id").change(function () {
            var product_type = $(this).val();
            var index = $(this).closest('tr').index();
            if (product_type != '') {
              var pdtcount = prdtypecheck(product_type, index);
              if (pdtcount <= 0) {
              } else {
                var msg = $(".bulk_product_type_id" + index + ' option:selected').text();
                var message = '<span style="color:#fdff65">' + msg + '</span>' + ' Product Type Already Selected';
                showCustomAlert(message, 'info');
                $(".bulk_product_type_id" + index).val('');
              }

            }

          });


          /* purpose:to uppercase validation*/
          $(".machine_name,.machine_code").keyup(function () {
            $(this).val($(this).val().toUpperCase());
          });

          /* purpose:qty validation*/
          $(document).on('keypress', '.bulk_machine_capacity,.electricity_cost', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
              return true;
            }
            ev.preventDefault();
            return false;
          });



          $('.bulk_machine_capacity').bind("cut copy paste", function (e) {
            e.preventDefault();
          });


          $('#savestatus').val('');
          $(document).on('click', '.saveform', function () {

            var btnval = $(this).val();
            if (btnval == 'APPLYCHANGES')
              var savestatus = 'APPLY CHANGES';
            else if (btnval == 'SAVE' || btnval == 'SAVENEW')
              var savestatus = 'SAVE';


            $('#savestatus').val(savestatus);
            var url = "{{ URL::to('machinesave') }}";
            var red_url = "{{ URL::to('machine') }}";
            var create_url = "{{ URL::to('machinecreate') }}";
            capacityrequiredvalid();

            var form = $('#machine');
            if (btnval != 'APPLYCHANGES') {
              form.parsley().validate();
              var form = $('#machine');
              form.parsley().validate();

              if (form.parsley().isValid()) {

                duplicate_validate();

                if (dup_chk == true) {

                  var formdata = $('#machine').serialize();
                  var $btn = $(this);
                  $btn.prop('disabled', true);

                  $.post(url, formdata, function (data) {
                    var status = data.status;
                    var msg = data.message;
                    var id = data.id;
                    var edit_url = "{{ URL::to('machineedit') }}/" + id;
                    if (btnval != 'SAVE' && btnval != 'DRAFT') {

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
                } return false;
              }
            }
            else {
              duplicate_validate();
              if (dup_chk == true) {

                var formdata = $('#machine').serialize();

                $.post(url, formdata, function (data) {

                  var status = data.status;
                  var msg = data.message;
                  var id = data.id;
                  var edit_url = "{{ URL::to('machineedit') }}/" + id;
                  showCustomAlert(msg, status);
                  setTimeout(function () {
                    window.location.href = edit_url;
                  }, 1500);

                });
              } return false;
            }

          });
          /*end*/





          $("#prevmaintenance").on("click", function (e) {
            e.preventDefault();
            if ($("#prevmaintenance1").hasClass("tabHide")) {
              $("#prevmaintenance1").removeClass("tabHide");
              $("#prdtype1").addClass("tabHide");
            }
          });
          $("#prdtype").on("click", function (e) {
            e.preventDefault();
            if ($("#prdtype1").hasClass("tabHide")) {
              $("#prdtype1").removeClass("tabHide");
              $("#prevmaintenance1").addClass("tabHide");
            }

          });

          $(document).on("focus", ".bulk_frequency_date", function () {

            $(this).datepicker({
              changeMonth: true,
              changeYear: true,
              dateFormat: "yy-mm-dd",
              minDate: "2024-04-01",
              showAnim: "slideDown",
              yearRange: "-25:+0",

            });
          });
        </script>


      @endpush