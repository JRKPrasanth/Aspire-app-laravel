@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Leave Application Approval</h3>
  @include('layouts.breadcrumb')


  <!-- Leave History Modal -->
  <div class="modal fade" id="empleaveModal" tabindex="-1" aria-labelledby="empleaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4 border-0">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="empleaveModalLabel">Employee Leave History</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body px-4 py-3">

          <div class="row">
            <div class="col">
              <div class="border rounded-3 overflow-auto" style="height: 450px;">
                <div id="Empleavetbl" class="p-3">
                  <!-- Table will be injected here -->
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
      <form action="" id="leave" data-parsley-validate>
        <input type="hidden" name="edit_id" value="{{ $edit_id }}" id="edit_id" />
        {{ csrf_field() }}

        <!-- Leave Applied On -->
        <div class="row mb-4">
          <div class="col-md-4">
            <div class="alert alert-danger p-2 mb-0 shadow-sm rounded-3 p-2 text-center viewLeave" role="button">
              <strong>Leave History</strong>
            </div>
          </div>
          <div class="col-md-4"></div>
          <div class="col-md-4">
            <div class="alert alert-success p-2 mb-0 shadow-sm rounded-3 p-2">
              <strong>Leave Applied On:</strong> {{ $applied_on }}
            </div>
          </div>
        </div>

        <div class="row g-4">
          <!-- Column 1 -->
          <div class="col-md-4">
            <div class="mb-3" style="pointer-events:none;">
              <label for="employee_id" class="form-label"><span class="text-danger">*</span> Employee</label>
              <select name="employee_id" id="employee_id" class="form-select select2 employee_id"></select>
            </div>

            <div class="mb-3" style="pointer-events:none;">
              <label for="leave_type" class="form-label"><span class="text-danger">*</span> Leave Type</label>
              <select name="leave_type" id="leave_type" class="form-select select2 leave_type"></select>
            </div>

            <div class="mb-3 leave_mode_hide" style="pointer-events:none;">
              <label class="form-label"><span class="text-danger">*</span> Start Date</label>
              <input class="form-control datepicker" name="start_date" type="text" value="{{ $start_date }}">
            </div>

            <div class="mb-3 leave_mode_show" style="pointer-events:none;">
              <label class="form-label"><span class="text-danger">*</span> Start Date Time</label>
              <input class="form-control" id="start_date_time" name="start_date_time" type="text"
                value="{{ $start_date_time }}">
            </div>

            <div class="mb-3 od_mode_hide" style="pointer-events:none;">
              <label class="form-label"><span class="text-danger">*</span> OD Start Date</label>
              <input class="form-control" id="od_start_date" name="od_start_date" type="text"
                value="{{ $od_start_date }}">
            </div>

            <div class="mb-3" style="pointer-events:none;">
              <label for="leave_mode" class="form-label"><span class="text-danger">*</span> Leave Mode</label>
              <select name="leave_mode" id="leave_mode" class="form-select select2"></select>
            </div>

            <div class="mb-3 leave_session_show" style="pointer-events:none;">
              <label for="session" class="form-label"><span class="text-danger">*</span> Session</label>
              <select name="session" id="session" class="form-select select2 session"></select>
            </div>

          </div>

          <!-- Column 2 -->
          <div class="col-md-4">
            <div class="mb-3 leave_mode_hide">
              <label for="no_of_days" class="form-label"><span class="text-danger">*</span> No of Days</label>
              <input type="text" id="no_of_days" name="no_of_days" class="form-control" value="{{ $no_of_days }}"
                readonly>
            </div>

            <div class="mb-3 leave_mode_show">
              <label for="no_of_hrs" class="form-label"><span class="text-danger">*</span> No of Hrs</label>
              <input type="text" id="no_of_hrs" name="no_of_hrs" class="form-control" value="{{ $no_of_hrs }}" readonly>
            </div>

            <div class="mb-3 od_mode_hide">
              <label for="od_no_of_days" class="form-label"><span class="text-danger">*</span> OD No of Hrs</label>
              <input type="text" id="od_no_of_days" name="od_no_of_days" class="form-control" value="{{ $od_no_of_days }}"
                readonly>
            </div>

            <div class="mb-3">
              <label for="reason" class="form-label"><span class="text-danger">*</span> Reason</label>
              <input type="text" id="reason" class="form-control" name="reason" value="{{ $leave_reason }}" readonly>
            </div>

            <div class="mb-3 leave_mode_hide" style="pointer-events:none;">
              <label class="form-label"><span class="text-danger">*</span> End Date</label>
              <input class="form-control datepicker" name="end_date" type="text" value="{{ $end_date }}">
            </div>

            <div class="mb-3 leave_mode_show" style="pointer-events:none;">
              <label class="form-label"><span class="text-danger">*</span> End Date Time</label>
              <input class="form-control" id="end_date_time" name="end_date_time" type="text"
                value="{{ $end_date_time }}">
            </div>

            <div class="mb-3 od_mode_hide" style="pointer-events:none;">
              <label class="form-label"><span class="text-danger">*</span> OD End Date</label>
              <input class="form-control" id="od_end_date" name="od_end_date" type="text" value="{{ $od_end_date }}">
            </div>

          </div>

          <!-- Column 3 -->
          <div class="col-md-4">
            <div class="mb-3" style="pointer-events:none;">
              <label for="forwarded_id" class="form-label"><span class="text-danger">*</span> Forwarded To</label>
              <select id="forwarded_id" name="forwarded_id" class="form-select select2"></select>
            </div>

            <div class="mb-3 leave_mode_hide">
              <label for="alloted_days" class="form-label"><span class="text-danger">*</span> Alloted Days</label>
              <input type="text" id="alloted_days" name="alloted_days" class="form-control"
                value="" required>
            </div>

            <div class="mb-3 leave_mode_show">
              <label for="alloted_hrs" class="form-label"><span class="text-danger">*</span> Alloted Hrs</label>
              <input type="text" id="alloted_hrs" name="alloted_hrs" class="form-control alloted_hrs" value="{{ $alloted_hrs }}">
            </div>

            <div class="mb-3 od_mode_hide">
              <label for="od_alloted_days" class="form-label"><span class="text-danger">*</span> Alloted OD Hrs</label>
              <input type="text" id="od_alloted_days" name="od_alloted_days" class="form-control od_alloted_days"
                value="{{ $od_alloted_days }}">
            </div>

            <div class="mb-3" style="pointer-events:none;">
              <label for="leave_status" class="form-label"><span class="text-danger">*</span> Leave Status</label>
              <select name="leave_status" id="leave_status" class="form-select select2"></select>
            </div>

            <div class="mb-3">
              <label for="approval_reason" class="form-label"><span class="text-danger">*</span> Approval Reason</label>
              <input type="text" id="approval_reason" name="approval_reason" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="approval_comments" class="form-label">Approval Comments</label>
              <input type="text" id="approval_comments" name="approval_comments" class="form-control">
            </div>
          </div>
        </div>

        <!-- Leave Balances -->
        <div class="row mt-4 text-center">
          <div class="col-md-3">
            <div class="alert alert-primary p-2 mb-0 shadow-sm rounded-3 p-2">
              <strong>Casual Leave:</strong> {{ $c_l }}
            </div>
          </div>
          <div class="col-md-3">
            <div class="alert alert-primary p-2 mb-0 shadow-sm rounded-3 p-2">
              <strong>Sick Leave:</strong> {{ $s_l }}
            </div>
          </div>
          <div class="col-md-3">
            <div class="alert alert-primary p-2 mb-0 shadow-sm rounded-3 p-2">
              <strong>Earn Leave:</strong> {{ $e_l }}
            </div>
          </div>
          <div class="col-md-3">
            <div class="alert alert-primary p-2 mb-0 shadow-sm rounded-3 p-2">
              <strong>Comp-Off Leave:</strong> {{ $c_o_l }}
            </div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="row mt-4 text-center">
          <div class="col-12">
            <button type="button" class="btn btn-success  save_form approved me-2 px-4" value="APPROVE">Approve</button>
            <button type="button" class="btn btn-danger  save_form rejected me-2 px-4" value="REJECT">Reject</button>
            <a href="{{ url('leaveapproval') }}"><button type="button" class="btn btn-secondary me-2 deled px-4" id="delete">Cancel</button></a>
          </div>
        </div>
      </form>
    </div>
  </div>




@endsection
@push('scripts')

  <script>

    $(document).ready(function () {



      /* ==== blade vars ==== */
      const forwarded_id = '{{ $forwarded_id ?? "" }}';
      const logged_id = '{{ $logged_id ?? "" }}';
      const leave_type = '{{ $leave_type ?? "" }}';
      const leave_mode = '{{ $leave_mode ?? "" }}';
      const leave_session = '{{ $leave_session ?? "" }}';
      const organization_id = '{{ $organization_id ?? "" }}';
      const currentStatus = 'APPROVE'; // as in your jCombo

      /* ==== small helpers ==== */
      function populateSelect({ el, url, selected, placeholder = '-- Select --', after }) {
        $.ajax({
          url,
          type: 'GET',
          success: function (data) {
            if (typeof data === 'string') {
              try { data = JSON.parse(data); } catch (e) {
                console.error('Invalid JSON response:', data);
                return;
              }
            }

            const $el = $(el);
            $el.html(`<option value="">${placeholder}</option>`);

            $.each(data, function (i, item) {
              const isSel = String(item.val) === String(selected) ? 'selected' : '';
              $el.append(`<option value="${item.val}" ${isSel}>${item.option_name}</option>`);
            });

            // if Select2 is used
            $el.trigger('change.select2');

            if (typeof after === 'function') after($el, data);
          },
          error: function (xhr, status, error) {
            console.error('AJAX Error:', error);
          }
        });
      }

      function lookupUrl(lookupType, valueCol = 'lookuplines_id', textCol = 'lookup_code', orderBy = 'lookuplines_id asc') {
        const parent = encodeURIComponent(`and lookup_type="${lookupType}"`);
        const order_by = encodeURIComponent(orderBy);
        return `{{ URL::to('jcomboform1') }}?table=a_lookuplines_t:${valueCol}:${textCol}&parent=${parent}&order_by=${order_by}`;
      }

      /* ==== calls ==== */

      // forwarded_id (employees)
      populateSelect({
        el: '#forwarded_id',
        url: `{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:first_name`,
        selected: forwarded_id,
        placeholder: '-- Select Forwarded To --'
      });

      // employee_id (employees)
      populateSelect({
        el: '#employee_id',
        url: `{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:first_name`,
        selected: logged_id,
        placeholder: '-- Select Employee --'
      });

      // leave_type
      populateSelect({
        el: '#leave_type',
        url: lookupUrl('leave_type', 'lookuplines_id', 'lookup_code'),
        selected: leave_type,
        placeholder: '-- Select Leave Type --'
      });

      // leave_mode
      populateSelect({
        el: '#leave_mode',
        url: lookupUrl('leave_mode', 'lookuplines_id', 'lookup_code'),
        selected: leave_mode,
        placeholder: '-- Select Leave Mode --'
      });

      // leave_status (note: your jCombo used value=lookup_code, text=lookup_code)
      populateSelect({
        el: '#leave_status',
        url: lookupUrl('leave_status', 'lookup_code', 'lookup_code'),
        selected: currentStatus,
        placeholder: '-- Select Status --'
      });

      // session
      populateSelect({
        el: '#session',
        url: lookupUrl('LEAVE_SESSION', 'lookuplines_id', 'lookup_code'),
        selected: leave_session,
        placeholder: '-- Select Session --'
      });

      // organization
      (function () {
        const order_by = encodeURIComponent('organization_name asc');
        const url = `{{ URL::to('jcomboform') }}?table=m_organizations_t:organization_id:organization_name&order_by=${order_by}`;
        populateSelect({
          el: '#organization_id',
          url,
          selected: organization_id,
          placeholder: '-- Select Organization --'
        });
      })();


      $('.alloted_days').on('input', function () {
        let value = $(this).val();
        let leave_mode = $('#leave_mode').val();
        let no_of_day = parseFloat($('#no_of_days').val());
        if (leave_mode === "135" && (value == 0)) {
          showCustomAlert('Alloted days cannot be 0, Alloted Days should be greater than 0 or between' + no_of_day, 'error');
          $(this).val('');
        }
      });


      setTimeout(function () {

        const mode  = Number('{{ $leave_type }}');   // Permission = 265
        const ltype = Number('{{ $leave_mode }}');

        // Hide all sections
        $('.leave_mode_show, .leave_mode_hide, .od_mode_hide, .leave_session_show').hide();

        // Remove required from ALL conditional inputs
        $('#alloted_days, #alloted_hrs, #od_alloted_days')
            .removeAttr('required');

        /* ==========================
          PERMISSION LEAVE
        ========================== */
        if (mode === 265) {
            $('.leave_mode_show').show();
            $('#alloted_hrs').attr('required', true);
        }

        /* ==========================
          ON DUTY
        ========================== */
        else if (mode === 133 && ltype === 134) {
            $('.od_mode_hide').show();
            $('#od_alloted_days').attr('required', true);
        }

        /* ==========================
          REGULAR LEAVE + SESSION
        ========================== */
        else if (ltype === 134) {
            $('.leave_mode_hide').show();
            $('.leave_session_show').show();
            $('#alloted_days').attr('required', true);
        }

        /* ==========================
          DEFAULT
        ========================== */
        else {
            $('.leave_mode_hide').show();
            $('#alloted_days').attr('required', true);
        }

      }, 500);


      // Cancel button redirect
      $(document).on('click', '.cancel', function () {
        window.location.href = "{{ URL::to('leaveapproval') }}";
      });

      // Alloted Days Validation
      $(document).on('blur', '#alloted_days', function () {
          const no_of_days = parseFloat($('#no_of_days').val()) || 0;
          const alloted_days = parseFloat($(this).val());

          if (isNaN(alloted_days)) return;

          if (alloted_days < 0.5 || alloted_days > no_of_days) {
              showCustomAlert('Alloted Days must be ≥ 0.5 and ≤ ' + no_of_days, 'error');
              $(this).val('');
          }
      });


      // Alloted Hours Validation
      $(document).on('keyup', '#alloted_hrs', function () {
        const no_of_hrs = parseFloat($('#no_of_hrs').val()) || 0;
        const alloted_hrs = parseFloat($(this).val()) || 0;

        if (alloted_hrs > no_of_hrs) {
          showCustomAlert('Alloted Hrs must be ≤ ' + no_of_hrs, 'warning');
          $(this).val(no_of_hrs);
        }
      });

      // save function
      $(document).on('click', '.save_form', function () {
        var status = $(this).val();
        $('#leave_status').val(status).change();
        var url = "{{URL::to('approvesave')}}";
        var form = $('#leave');
        form.parsley().validate();
        var form = $('#leave');
        form.parsley().validate();


        if (form.parsley().isValid()) {
          var $btn = $(this);
          $btn.prop('disabled', true);

          var data = $('#leave').serialize();
          $.post(url, data, function (data1) {
            if (data1 == 1) {
              showCustomAlert('Leave ' + status + ' Successfully', 'success');
              setTimeout(function () {
                var url = "{{URL::to('leaveapproval')}}";
                window.location.href = url;
              }, 1500);
            }
            else {
              showCustomAlert('Leave ' + status + ' Successfully', 'success');
              setTimeout(function () {
                var url = "{{URL::to('leaveapproval')}}";
                window.location.href = url;
              }, 1500);
            }
          });
        }
      });

      window.ParsleyConfig = {
        excluded: 'input[type=hidden], :hidden'
      };


    });





    // modal for date wise work rpt
    $(document).ready(function () {
      $('.viewLeave').click(function () {
        // Show the modal
        $('#empleaveModal').modal('show');

        var id = "{{ $logged_id }}";
        var type = "{{ $leave_type }}";
        // Build the query string
        var params = $.param({
          id: id,
          type:type
        });

        // Build the full URL for the AJAX request
        var url = "{{ route('employeleavepopup') }}" + "?" + params;

        // AJAX request to fetch data
        $.get(url, function (data) {
          // Update HTML content with received data
          $('#Empleavetbl').html(data);
        });
      });

      // Close modal button to close the sidebar
      $('#closeButton').click(function () {
        $('#empleaveModal').modal('hide');
      });

      // Clear modal content when it is hidden
      $('#empleaveModal').on('hidden.bs.modal', function () {
        $('#Empleavetbl').html('');
      });
    });

  </script>

@endpush