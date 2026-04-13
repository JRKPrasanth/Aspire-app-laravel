@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee EP Approve</h3>
  @include('layouts.breadcrumb')


  <div class="col-md-2 mt-2">
    <button type="button" class="btn btn-success w-100 validated_selected" id="validated_selected"><i
        class="bi bi-check2-circle"></i>
      Approve Selected
    </button>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="OtTbl" class="table table-bordered table-striped w-100" style="width: 200% !important;">
          <thead>
            <tr class="table-warning">
              <th><input type="checkbox" id="select_all"></th>
              <th style="display:none;">Emp Id</th>
              <th>Emp Number</th>
              <th class="freeze">Employee Name</th>
              <th>Department</th>
              <th>Desigination</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Mrng EP</th>
              <th>Evg EP</th>
              <th>Night EP</th>
              <th>Sunday/Holiday EP</th>
              <th>Attended EP</th>
              <th>Overall EP Hrs</th>
              <th>EP Amount</th>
              <th>Food Amount</th>
              <th>Total Amount</th>
              <th>Status</th>

            </tr>
            <tr class="table-info">

              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th style="display:none;"><input type="text" class="form-control form-control-sm column-search"
                  placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th class="freeze"><input type="text" class="form-control form-control-sm column-search"
                  placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>

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

    //table data		

    var table = $('#OtTbl').DataTable({
      processing: true,
      serverSide: true,
      dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
      ajax: "{{ url('employeeotgrid1') }}",
      lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
      columns: [
        {
          data: 'id',
          render: function (data, type, row) {
            return '<input type="checkbox" class="row_checkbox me-2" value="' + data + '">';
          },

          orderable: false,
          searchable: false
        },

        { data: 'emp_id', name: 'emp_id', visible: false },
        { data: 'emp_number', name: 'emp_number' },
        { class: 'freeze', data: 'emp_name', name: 'emp_name' },
        { data: 'sub_department_name', name: 'sub_department_name' },
        { data: 'job_title_name', name: 'job_title_name' },
        { data: 'check_in', name: 'check_in' },
        { data: 'check_out', name: 'check_out' },
        { data: 'mrng_ot', name: 'mrng_ot' },
        { data: 'evng_ot', name: 'evng_ot' },
        { data: 'night_ot', name: 'night_ot' },
        { data: 'sunday_ot', name: 'sunday_ot' },
        { data: 'ot_type', name: 'ot_type' },
        { data: 'overall_ot_hrs', name: 'overall_ot_hrs' },
        { data: 'ot_amount', name: 'ot_amount' },
        { data: 'food_amount', name: 'food_amount' },
        { data: 'total_amt', name: 'total_amt' },
        { data: 'status', name: 'status' },

      ],
            buttons: [
        {
          extend: 'colvis',
          text: '<i class="bi bi-layout-three-columns"></i> Columns',
          className: 'btn bg-primary btn-sm',
          postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
        },
        { extend: 'excelHtml5', title: "Approve EP", exportOptions: { columns: ':visible' } }
      ]
    });

    // Select all checkboxes
    $('#select_all').on('click', function () {
      $('.row_checkbox').prop('checked', this.checked);
    });

    // Approve

    $(document).on('click', '#validated_selected', function () {
      var table = $('#OtTbl').DataTable();

      // Collect checked row IDs
      var selectedIds = [];
      $('.row_checkbox:checked').each(function () {
        selectedIds.push($(this).val());
      });

      if (selectedIds.length > 0) {
        var url = "{{ URL('approvedot') }}?row_id=" + selectedIds.join(',');

        $.get(url, function (data) {
          if (data == 1) {
            showCustomAlert('EP Approved Successfully', 'success');
            table.ajax.reload();
          }
        });
      } else {
        showCustomAlert('Please select a row', 'warning');
      }
    });

  </script>

@endpush