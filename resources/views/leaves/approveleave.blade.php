@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Leave Approval</h3>
  @include('layouts.breadcrumb')

  <style>
    .type-selector {
      display: flex;
      gap: 1.5rem;
      padding: 1rem 0;
      background: #f9f9f9;
      border-radius: 1rem;
      margin-top: 1rem;
    }

    .type-selector .form-check-input {
      display: none;
    }

    .type-selector .form-check-label {
      padding: 0.6rem 1.5rem;
      border: 2px solid #ccc;
      border-radius: 2rem;
      background-color: #fff;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
      font-weight: 500;
      color: #333;
    }

    .type-selector .form-check-input:checked+.form-check-label {
      background-color: #358a35;
      color: #fff;
      border-color: #009c00;
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .leave-tabs {
      display: flex;
      gap: 1rem;
      padding: 0.75rem;
      border-radius: 14px;
    }

    .leave-tab {
      position: relative;
      padding: 10px 28px;
      border-radius: 999px;
      background: #fff;
      border: 2px solid #dcdcdc;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.25s ease;
      color: #333;
    }

    .leave-tab:hover {
      background: #f0f9f0;
      border-color: #2e7d32;
    }

    .leave-tab.active {
      background: linear-gradient(135deg, #2e7d32, #43a047);
      color: #fff;
      border-color: #2e7d32;
      box-shadow: 0 6px 15px rgba(46, 125, 50, 0.25);
    }

    /* Badge */
    .count-badge {
      position: absolute;
      top: -6px;
      right: -6px;
      background: #e53935;
      color: #fff;
      border-radius: 999px;
      font-size: 12px;
      padding: 2px 8px;
      font-weight: 700;
    }
  </style>

  <!-- Radio Button Controls -->
  <div class="leave-tabs mb-4">
    <label class="leave-tab active me-4" id="tab-leave">
      <input type="radio" name="request_type" value="leave_tbl" checked hidden>
      Leave
      <span class="count-badge d-none" id="leave_count">0</span>
    </label>

    <label class="leave-tab me-4" id="tab-permission">
      <input type="radio" name="request_type" value="permission_tbl" hidden>
      Permission
      <span class="count-badge d-none" id="permission_count">0</span>
    </label>

    <label class="leave-tab me-4" id="tab-od">
      <input type="radio" name="request_type" value="od_tbl" hidden>
      OD
      <span class="count-badge d-none" id="od_count">0</span>
    </label>
  </div>


  <!-- Table -->
  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="table-responsive">
        <table id="leavTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th>Employee Name</th>
              <th>Reporting Name</th>
              <th>Leave Type</th>

              <!-- Leave -->
              <th><span class="header-4"></span></th>
              <th><span class="header-5"></span></th>
              <th><span class="header-6"></span></th>

              <!-- Permission -->
              <th><span class="header-7"></span></th>
              <th><span class="header-8"></span></th>
              <th><span class="header-9"></span></th>

              <!-- OD -->
              <th><span class="header-10"></span></th>
              <th><span class="header-11"></span></th>
              <th><span class="header-12"></span></th>

              <th>Approve Status</th>
              <th>Leave Apply Date</th>

            </tr>
            <tr class="table-info">
              @for ($i = 0; $i < 15; $i++)
                <th><input type="text" class="form-control form-control-sm column-search" /></th>
              @endfor
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>


@endsection
@push('scripts')

  <script>

    // new scripts -- VIGNESH M


    // table data	
    $(document).ready(function () {
      var table = $('#leavTbl').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 5,

        ajax: {
          url: "{{ route('leaveapproveData') }}",
          type: "GET",
          data: function (d) {
            const selectedType = $("input[name='request_type']:checked").val();
            if (selectedType === 'leave_tbl') d.leave_type = 'LEAVE';
            else if (selectedType === 'permission_tbl') d.leave_type = 'PERMISSION';
            else if (selectedType === 'od_tbl') d.leave_type = 'ON-DUTY';
          },
          dataSrc: function (json) {
            updateTabCounts(json);
            return json.data;
          }
        },

        columns: [
          {
            data: 'leave_id',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                buttons += `
                <button type="button" class="btn btn-sm btn-success edit-btn"
                  data-id="${row.leave_id}">Approve
                </button>`;
              }
              return buttons;
            }
          },
          { data: 'employee_name' },
          { data: 'forwarded_name' },
          { data: 'leave_type' },

          // Leave
          { data: 'start_date' },
          { data: 'end_date' },
          { data: 'no_of_days' },

          // Permission
          { data: 'start_date_time' },
          { data: 'end_date_time' },
          { data: 'no_of_hrs' },

          // OD
          { data: 'od_start_date' },
          { data: 'od_end_date' },
          { data: 'od_no_of_days' },

          { data: 'leave_status' },
          { data: 'created_at' }
        ]
      });



      const leaveCols = [3, 4, 5];
      const permissionCols = [6, 7, 8];
      const odCols = [9, 10, 11];

      function toggleColumns(type) {
        [...leaveCols, ...permissionCols, ...odCols].forEach(i => table.column(i).visible(false));
        if (type === 'leave_tbl') leaveCols.forEach(i => table.column(i).visible(true));
        if (type === 'permission_tbl') permissionCols.forEach(i => table.column(i).visible(true));
        if (type === 'od_tbl') odCols.forEach(i => table.column(i).visible(true));
      }

      function updateHeaderText(type) {
        const headers = {
          4: '', 5: '', 6: '', 7: '', 8: '', 9: '',
          10: '', 11: '', 12: ''
        };
        if (type === 'leave_tbl') {
          headers[4] = 'Start Date';
          headers[5] = 'End Date';
          headers[6] = 'No of Days';
        } else if (type === 'permission_tbl') {
          headers[7] = 'Start Date Time';
          headers[8] = 'End Date Time';
          headers[9] = 'No of Hours';
        } else if (type === 'od_tbl') {
          headers[10] = 'OD Start Date';
          headers[11] = 'OD End Date';
          headers[12] = 'OD No of Days/Hrs';
        }
        Object.entries(headers).forEach(([index, text]) => {
          $('.header-' + index).text(text);
        });
      }

      $("input[name='request_type']").change(function () {
        const type = $(this).val();
        toggleColumns(type);
        updateHeaderText(type);
        table.ajax.reload();
      });

      const initialType = $("input[name='request_type']:checked").val();
      toggleColumns(initialType);
      updateHeaderText(initialType);

      $('#leavTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    $(document).on('click', '.edit-btn', function () {

      const id = $(this).data('id');

      var url = "{{URL::to('approveleave')}}/" + id;
      window.location.href = url;
    });


    function updateTabCounts(json) {
      toggleBadge('#leave_count', json.leaveCount);
      toggleBadge('#permission_count', json.permissionCount);
      toggleBadge('#od_count', json.odCount);
    }

    function toggleBadge(selector, count) {
      if (count > 0) {
        $(selector).text(count).removeClass('d-none');
      } else {
        $(selector).addClass('d-none');
      }
    }

    $('input[name="request_type"]').on('change', function () {
      $('.leave-tab').removeClass('active');
      $(this).closest('.leave-tab').addClass('active');
    });

  </script>

@endpush