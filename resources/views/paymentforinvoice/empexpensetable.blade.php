@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Payment For Expense</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="paymentcreate mt-2 px-4"></div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>

          <tr class="table-warning">
            <th><input type="checkbox" id="select-all"></th>
            <th>Action</th>
            <th>Expense No</th>
            <th>Expense Date</th>
            <th>Employee Name</th>
            <th>Bill No</th>
            <th>Expense Status</th>
            <th>Expense Amount</th>
            <th>Balance Amount</th>
            <th>Narration</th>
            <th>Created By</th>
            <th>Created By</th>
          </tr>

          <tr class="table-danger">
            <th></th>
            <th></th>
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

    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
                <button class="btn btn-success text-white px-4 me-2">Create Payment
                </button>
              `);
      }
    });


    $(document).ready(function () {

      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "getpaymentempExpenseData",
        columns: [
          {
            data: 'expense_line_id',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data) {
              return `<input type="checkbox" class="row-checkbox" value="${data}">`;
            }
          },
          {
            data: 'expense_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                <button class="btn btn-sm btn-warning view-btn" data-id="${row.expense_id}">
                  <i class="bi bi-eye"></i>
                </button>`;
              }
              return buttons;
            }
          },
          { data: 'expense_no' },
          { data: 'expense_date' },
          { data: 'employee_name' },
          { data: 'bill_no' },
          { data: 'expense_status' },
          { data: 'expense_line_amount' },
          { data: 'balance_amounts' },
          { data: 'remarks' },
          { data: 'first_name' },
          { data: 'expense_line_id', visible: false }
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


    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');

      window.location.replace('empexpensesview/' + id);

    });


    // create payment

    $(document).on('click', '.paymentcreate', function () {
      // Get all checked checkboxes
      var selected = [];
      $('.row-checkbox:checked').each(function () {
        selected.push($(this).val());
      });

      if (selected.length === 0) {
        showCustomAlert('Please select at least one row', 'error');
        return;
      }

      // Redirect with selected IDs
      var url = 'paymentforempexpensecreate/' + selected.join(',');
      window.location.href = url;
    });



    $(document).on('click', '#approve', function () {
      var index = $("#expensegrid").jqGrid('getGridParam', 'selrow');
      var id = $("#expensegrid").jqGrid('getCell', index, 'expense_id');
      var expense_status = $("#expensegrid").jqGrid('getCell', index, 'expense_status');

      if (id != false) {
        window.location.replace('empexpenseapproval/' + id + '/' + expense_status);
      }
      else {
        notyMsg("info", "Please Select Row");
      }

    });




    $("#expensepay").click(function () {
      var index = $("#expensegrid").jqGrid('getGridParam', 'selrow');
      var id = $("#expensegrid").jqGrid('getCell', index, 'expense_id');

      if (index) {
        window.location.replace('empexpensespaycreate/' + id);

      }
      else {
        notyMsg("info", "Please Select Row");
      }
    });


    $("#edit").click(function () {
      var index = $("#expensegrid").jqGrid('getGridParam', 'selrow');
      var id = $("#expensegrid").jqGrid('getCell', index, 'expense_id');
      var status = $("#expensegrid").jqGrid('getCell', index, 'expense_status');

      if (index) {
        if (status != "APPROVED") {
          window.location.replace('empexpensescreate/' + id);
        }
        else {
          notyMsg("error", "Approved Data Cant Edit");
        }
      }
      else {
        notyMsg("info", "Please Select Row");
      }

    });

  </script>

@endpush