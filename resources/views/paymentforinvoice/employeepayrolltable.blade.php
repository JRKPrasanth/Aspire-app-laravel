@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Payment For Employee</h3>
  @include('layouts.breadcrumb')
  <button class="btn btn-success text-white px-4 me-2 createpay">Create Payment</button>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th><input type="checkbox" id="select-all"></th>
            <th>Action</th>
            <th>Employee No</th>
            <th>Employee Name</th>
            <th>Employee Type</th>
            <th>Month</th>
            <th>Year</th>
            <th>Date</th>
            <th>Gross Salary</th>
            <th>Net Salary</th>
            <th>Balance Salary</th>
            <th>Department</th>
            <th>Bank Name</th>
          </tr>

          <tr class="table-danger">
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


  <!-- Reason Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
      <div class="modal-content rounded-4 shadow-lg">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="reasonModalLabel">Enter Reason</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3 row align-items-center">
            <label for="reason" class="col-md-4 col-form-label">Reason</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="reason" placeholder="Enter reason">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success hold_release">Submit</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>



@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "employeepayrolforpaygrid",
        columns: [
          {
            data: 'id',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data) {
              return `<input type="checkbox" class="row-checkbox" value="${data}">`;
            }
          },
          {
            data: 'id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
          <button class="btn btn-sm btn-primary hold-btn" data-id="${row.id}">
            Hold
          </button>`;
              }


              return buttons;
            }
          },
          { data: 'employee_number' },
          { data: 'first_name' },
          { data: 'lookup_meaning' },
          { data: 'month' },
          { data: 'year' },
          { data: 'date' },
          { data: 'gross_salary' },
          { data: 'net_salary' },
          { data: 'balance' },
          { data: 'department' },
          { data: 'bank_name' },

        ],

        initComplete: function () {
          var api = this.api();

          // get the real visible header inside the scroll container
          var $scrollHead = $(api.table().container())
            .find('.dataTables_scrollHead thead');

          // second header row (index 1) has the inputs
          $scrollHead.find('tr:eq(1) th').each(function (colIndex) {
            var th = this;
            $('input.column-search', th).on('keyup change', function () {
              if (api.column(colIndex).search() !== this.value) {
                api.column(colIndex).search(this.value).draw();
              }
            });
          });
        }

      });


      $('#AccTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });


      $('#select-all').on('click', function () {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"].row-checkbox', rows).prop('checked', this.checked);
      });


      $('#AccTbl tbody').on('change', '.row-checkbox', function () {
        if (!this.checked) {
          var el = $('#select-all').get(0);
          if (el && el.checked && ('indeterminate' in el)) {
            el.indeterminate = true;
          }
        }
      });
    });


    // create pay

    $(document).on('click', '.createpay', function () {
      var table = $("#AccTbl").DataTable();
      var selectedIds = [];

      // Loop through all checked checkboxes
      $("#AccTbl tbody input.row-checkbox:checked").each(function () {
        var rowData = table.row($(this).closest("tr")).data();
        if (rowData && rowData.id) {
          selectedIds.push(rowData.id);
        }
      });

      if (selectedIds.length > 0) {
        var url = "{{ URL::to('paymentforemployeecreate') }}/" + selectedIds.join(",");
        window.location.replace(url);
      } else {
        showCustomAlert("Please Select a Row", "info");
      }
    });



    // When Hold button is clicked
    $(document).on('click', '.hold-btn', function () {
      const id = $(this).data('id');  // Row ID
      $('#myModal').data('row-id', id).modal('show'); // Store ID in modal
    });

    // When Hold Release button in modal is clicked
    $(document).on('click', '.hold_release', function () {
      var reason = $('.reason').val();
      var rowId = $('#myModal').data('row-id'); // Get stored row ID

      if (rowId) {
        var url = "{{URL('holdpaymentfremp')}}/?row_id=" + rowId + "&reason=" + encodeURIComponent(reason);
        $.get(url, function (data) {
          if (data == 1) {
            showCustomAlert('Payment Hold Successfully', 'success');
            location.reload();
          } else {
            showCustomAlert('Failed to hold payment', 'error');
          }
        });
      } else {
        showCustomAlert('No row selected', 'info');
      }
    });






  </script>

@endpush