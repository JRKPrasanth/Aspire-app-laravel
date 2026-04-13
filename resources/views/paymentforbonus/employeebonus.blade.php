@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    Payment For Bonus
  </h3>
  @include('layouts.breadcrumb')
  <button class="paymentrequested mt-2 px-4 btn btn-success">Create Payment</button>
  <?php error_reporting(0); ?>


  <div class="container-fluid mt-4">
    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-body py-4">

        <div class="row g-4 text-center">
          <!-- Invoice Total -->
          <div class="col-md-4">
            <div class="p-1 rounded-3 border border-primary bg-light bg-opacity-25">
              <div class="text-primary fw-bold">Bonus Total</div>
              <div class="fs-5 mt-1 text-primary fw-bold">
                <i class="bi bi-currency-rupee"></i>
                {{ $sum_inv_tot[0]->total > 0 ? number_format($sum_inv_tot[0]->total, 2) : '0.00' }}
              </div>
            </div>
          </div>


          <!-- Selected Balance Total -->
          <div class="col-md-4">
            <div class="p-1 rounded-3 border border-secondary bg-light bg-opacity-25 position-relative">
              <div class="text-secondary fw-bold">Selected Total</div>
              <div class="fs-5 mt-1">
                <a id="inv_search_tot" class="btn btn-sm btn-outline-dark px-3 shadow-sm inv_search_tot">
                  INR. 0.00
                </a>
              </div>
              <span class="badge bg-secondary position-absolute top-0 end-0 mt-2 me-2">
                Click to calculate
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th><input type="checkbox" id="select-all"></th>
            <th>Employee Number</th>
            <th>Employee Name</th>
            <th>Department</th>
            <th>Status</th>
            <th>Zone</th>
            <th>Last Salary Revision</th>
            <th>Date of Joining</th>
            <th>Bonus 1</th>
            <th>Bonus 2</th>
            <th>Bonus 3</th>
            <th>Bonus 4</th>
            <th>From Bonus 1</th>
            <th>To Bonus 1</th>
            <th>Payable 1</th>
            <th>From Bonus 2</th>
            <th>To Bonus 2</th>
            <th>Payable 2</th>
            <th>From Bonus 3</th>
            <th>To Bonus 3</th>
            <th>Payable 3</th>
            <th>From Bonus 4</th>
            <th>To Bonus 4</th>
            <th>Payable 4</th>
            <th>From Last Period</th>
            <th>To Last Period</th>
            <th>Last Period Payable</th>
            <th>Arrear Bonus</th>
            <th>Total Bonus Payable</th>
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
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>

          </tr>
        </thead>
        <tbody></tbody>
      </table>
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
        ajax: "employeebonusforpaygrid",
        columns: [
          {
            data: 'employee_id',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data) {
              return `<input type="checkbox" class="row-checkbox" value="${data}">`;
            }
          },

          { data: 'employee_number' },
          { data: 'employee_name' },
          { data: 'department' },
          { data: 'status' },
          { data: 'zone' },
          { data: 'last_revision_date' },
          { data: 'doj' },
          { data: 'bonus1' },
          { data: 'bonus2' },
          { data: 'bonus3' },
          { data: 'bonus4' },
          { data: 'from1' },
          { data: 'to1' },
          { data: 'payable1' },
          { data: 'from2' },
          { data: 'to2' },
          { data: 'payable2' },
          { data: 'from3' },
          { data: 'to3' },
          { data: 'payable3' },
          { data: 'from4' },
          { data: 'to4' },
          { data: 'payable4' },
          { data: 'last_period' },
          { data: 'end_date' },
          { data: 'final_payable' },
          { data: 'arrear_bonus' },
          { data: 'total_bonus' },

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


    // bal amount count	
    $("#inv_search_tot").click(function () {
      var sum_tot = 0;

      // Loop through all checked rows in the DataTable
      $("#AccTbl tbody input.row-checkbox:checked").each(function () {
        // Get the row data using DataTables API
        var rowData = $('#AccTbl').DataTable().row($(this).closest('tr')).data();

        if (rowData && rowData.total_bonus) {
          sum_tot += parseFloat(rowData.total_bonus) || 0;
        }
      });

      // Display the total or 0.00 if nothing selected
      if (sum_tot > 0) {
        $(".inv_search_tot").html("INR. " + sum_tot.toFixed(2));
      } else {
        $(".inv_search_tot").html("INR. 0.00");
      }
    });


    // payment request	

    $(document).on('click', '.paymentrequested', function () {
      var selected = [];
      $("#AccTbl tbody input.row-checkbox:checked").each(function () {
        selected.push($(this).val());
      });

      if (selected.length === 0) {
        showCustomAlert("Please select at least one row", "info");
        return;
      }


      var url = "{{ URL::to('paymentforemployeebonuscreate') }}/" + selected.join(",");
      window.location.replace(url);

    });



  </script>


@endpush