@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Payment For Invoice</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="paymentcreate mb-3 mt-1"></div>


  <div class="container-fluid mt-4">
    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-body py-4">

        <div class="row g-4 text-center">
          <!-- Invoice Total -->
          <div class="col-md-4">
            <div class="p-1 rounded-3 border border-primary bg-light bg-opacity-25">
              <div class="text-primary fw-bold">Invoice Total</div>
              <div class="fs-5 mt-1 text-primary fw-bold">
                <i class="bi bi-currency-rupee"></i>
                {{ $sum_inv_tot[0]->sum_inv_total > 0 ? number_format($sum_inv_tot[0]->sum_inv_total, 2) : '0.00' }}
              </div>
            </div>
          </div>

          <!-- Balance Total -->
          <div class="col-md-4">
            <div class="p-1 rounded-3 border border-success bg-light bg-opacity-25">
              <div class="text-success fw-bold">Balance Total</div>
              <div class="fs-5 mt-1 text-success fw-bold">
                <i class="bi bi-currency-rupee "></i>
                {{ $sum_inv_tot[0]->sum_bal_total > 0 ? number_format($sum_inv_tot[0]->sum_bal_total, 2) : '0.00' }}
              </div>
            </div>
          </div>

          <!-- Selected Balance Total -->
          <div class="col-md-4">
            <div class="p-1 rounded-3 border border-secondary bg-light bg-opacity-25 position-relative">
              <div class="text-secondary fw-bold">Selected Balance Total</div>
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
            <th>Action</th>
            <th>Invoice Number</th>
            <th>Invoice Date</th>
            <th>Supplier Name</th>
            <th>Due Date</th>
            <th>Overdue</th>
            <th>Invoice Status</th>
            <th>Request Status</th>
            <th>Invoice Amount</th>
            <th>Balance Amount</th>
            <th>Advance Amount</th>
            <th>Credit Note Balance</th>
            <th>Debit Note Balance</th>
            <th>MSME Supplier</th>
            <th>PO Number</th>
            <th>PO Date</th>
            <th>Attachment</th>
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
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>


          </tr>
        </thead>

    <tfoot>
    <tr class="table-info fw-bold">
        <th></th>
        <th></th>
        <th class="freeze">PAGE TOTAL</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <tr class="table-success fw-bold">
        <th></th>
        <th></th>
        <th class="freeze">GRAND TOTAL</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
</tfoot>

        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- Balance Closure Modal -->
  <div class="modal fade" id="balcloseModal" tabindex="-1" aria-labelledby="balcloseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg rounded-4">

        <div class="modal-header bg-primary text-white rounded-top-4">
          <h5 class="modal-title fw-semibold" id="balcloseModalLabel">
            <i class="bi bi-cash-stack me-2"></i> Update Balance Closure
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-4">
          <div class="d-flex justify-content-between mb-3">
            <div>
              <strong>Invoice Number:</strong>
              <span class="intbal_no text-primary fw-semibold ms-1"></span>
            </div>
            <div>
              <strong>Invoice Date:</strong>
              <span class="intbal_date text-secondary fw-semibold ms-1"></span>
            </div>
          </div>

          <div class="row g-3 align-items-start">
            <div class="col-md-6">
              <label for="bal_closure_remarks" class="form-label fw-semibold">Reason for Balance Closure:</label>
            </div>
            <div class="col-md-6">
              <textarea class="form-control bal_closure_remarks" id="bal_closure_remarks" rows="4"
                placeholder="Enter reason..." required></textarea>
              <input type="hidden" class="form-control intbal_id">
            </div>

            <div class="col-md-6">
              <label for="closed_by" class="form-label fw-semibold">Balance Closed By:</label>
            </div>
            <div class="col-md-6 closed_read_by">
              <select name="closed_by" id="closed_by" class="form-select select2 closed_by" required>
                <option value="">Select user...</option>
                <!-- Options dynamically loaded -->
              </select>
              <input type="hidden" class="form-control intprd_id">
            </div>
          </div>
        </div>

        <div class="modal-footer justify-content-center border-0 pb-4">
          <button type="button" class="btn btn-success px-4 rounded-pill balclose_save" id="updateClose">
            <i class="bi bi-check-circle me-1"></i> Balance Close
          </button>
          <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
        </div>

      </div>
    </div>
  </div>


@endsection
@push('scripts')

  <script>

    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'paymentinv')) {
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
        ajax: "getInvoicedetailsData",
        columns: [
          {
            data: 'po_invoice_id',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data) {
              return `<input type="checkbox" class="row-checkbox" value="${data}">`;
            }
          },
          {
            data: 'po_invoice_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                <button class="btn btn-sm btn-warning view-btn" data-id="${row.po_invoice_id}">
                  <i class="bi bi-eye"></i>
                </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'balclosure')) {
                buttons += `
                <button class="btn btn-sm btn-primary close-btn" data-id="${row.po_invoice_id}"
                  data-bs-toggle="tooltip" 
                  data-bs-placement="top" 
                  title="Balance closure">
                  <i class="bi bi-plus"></i>
                </button>`;
              }
              return buttons;
            }
          },
          { data: 'bill_number' },
          { data: 'invoice_date' },
          { data: 'supplier_name' },
          { data: 'due_date' },
          { data: 'over_due' },
          { data: 'po_invoice_status' },
          { data: 'requeststatus' },
          { data: 'invoice_grand_total' },
          { data: 'balance_amount' },
          { data: 'advance_amount' },
          { data: 'credit_note_balance' },
          { data: 'debit_note_balance' },
          { data: 'msme_status' },
          { data: 'po_number' },
          { data: 'po_date' },
          { data: 'attachfile_name' }
        ],

    footerCallback: function () {

    let api = this.api();

    let num = function (i) {
        return typeof i === 'string'
            ? i.replace(/,/g, '') * 1
            : typeof i === 'number'
            ? i
            : 0;
    };

    // -------------------------
    // PAGE TOTAL (visible rows)
    // -------------------------
    

    let pageDebit = api.column(8, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageCredit = api.column(9, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageBalance = api.column(10, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageCredt = api.column(11, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);
    let pageDebt = api.column(12, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);    



    // -------------------------
    // GRAND TOTAL (ALL rows)
    // -------------------------
    

    let grandDebit = api.column(8).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandCredit = api.column(9).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandBalance = api.column(10).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandCredt = api.column(11, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);
    let grandDebt = api.column(12, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);    


    // PAGE TOTAL row (1st footer row)
    
    $(api.column(8).footer()).closest('tfoot').find('tr:eq(0) th:eq(8)')
        .html(pageDebit.toFixed(2));
    $(api.column(9).footer()).closest('tfoot').find('tr:eq(0) th:eq(9)')
        .html(pageCredit.toFixed(2));
    $(api.column(10).footer()).closest('tfoot').find('tr:eq(0) th:eq(10)')
        .html(pageBalance.toFixed(2));
    $(api.column(11).footer()).closest('tfoot').find('tr:eq(0) th:eq(7)')
        .html(pageCredt.toFixed(2));
    $(api.column(12).footer()).closest('tfoot').find('tr:eq(0) th:eq(7)')
        .html(pageDebt.toFixed(2));        
    
    // GRAND TOTAL row (2nd footer row)
    
    $(api.column(8).footer()).closest('tfoot').find('tr:eq(1) th:eq(8)')
        .html(grandDebit.toFixed(2));
    $(api.column(9).footer()).closest('tfoot').find('tr:eq(1) th:eq(9)')
        .html(grandCredit.toFixed(2));
    $(api.column(10).footer()).closest('tfoot').find('tr:eq(1) th:eq(10)')
        .html(grandBalance.toFixed(2));
    $(api.column(11).footer()).closest('tfoot').find('tr:eq(1) th:eq(7)')
        .html(grandCredt.toFixed(2));
    $(api.column(12).footer()).closest('tfoot').find('tr:eq(1) th:eq(7)')
        .html(grandDebt.toFixed(2));        
    },

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


      // Select all
      $('#select-all').on('click', function () {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"].row-checkbox', rows).prop('checked', this.checked);
      });

      // Checkbox change
      $('#AccTbl tbody').on('change', '.row-checkbox', function () {
        var checked = $(this).is(':checked');
        var currentRow = table.row($(this).closest('tr')).data();
        var currentSupplier = currentRow.supplier_name;

        if (checked) {
          // Get all currently checked suppliers
          var checkedSuppliers = [];
          $('#AccTbl tbody input.row-checkbox:checked').each(function () {
            var rowData = table.row($(this).closest('tr')).data();
            if (rowData && rowData.supplier_name) {
              checkedSuppliers.push(rowData.supplier_name);
            }
          });

          // Check if all suppliers are the same
          var uniqueSuppliers = [...new Set(checkedSuppliers)];
          if (uniqueSuppliers.length > 1) {
            // Different suppliers selected - prevent checking
            showCustomAlert("Can't select rows from different suppliers!", "error");
            $(this).prop('checked', false);
            return;
          }
        } else {
          // If unchecked, update select-all indeterminate
          var el = $('#select-all').get(0);
          if (el && el.checked && ('indeterminate' in el)) {
            el.indeterminate = true;
          }
        }
      });

    });



    $(document).ready(function () {
      var cleared_by = "{{ \Session::get('emp_id') }}";

      var url = "{{ URL::to('jcomboform') }}" +
        "?table=hr_employee_t:employee_id:employee_number|first_name" +
        "&parent=active='Yes'" +
        "&order_by=employee_id asc";

      $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
          // Ensure data is JSON
          if (typeof data === "string") {
            try { data = JSON.parse(data); } catch (e) { console.error("Invalid JSON response:", data); return; }
          }

          var $select = $(".closed_by");
          $select.empty().append('<option value="">-- Select Employee --</option>');

          $.each(data, function (i, item) {
            var selected = item.val == cleared_by ? 'selected' : '';
            $select.append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
          });

          $select.trigger('change.select2'); // refresh Select2 if used
        },
        error: function () {
          console.error("Failed to load employees");
        }
      });
    });



    $("#inv_search_tot").click(function () {
      var sum_tot = 0;

      // Loop through all checked rows in the DataTable
      $("#AccTbl tbody input.row-checkbox:checked").each(function () {
        // Get the row data using DataTables API
        var rowData = $('#AccTbl').DataTable().row($(this).closest('tr')).data();

        if (rowData && rowData.balance_amount) {
          sum_tot += parseFloat(rowData.balance_amount) || 0;
        }
      });

      // Display the total or 0.00 if nothing selected
      if (sum_tot > 0) {
        $(".inv_search_tot").html("INR. " + sum_tot.toFixed(2));
      } else {
        $(".inv_search_tot").html("INR. 0.00");
      }
    });


    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      var rtnurl = "paymentforinvoice";

      window.location.replace('invoiceDataview/' + id + '?return=' + rtnurl);

    });

    // create payment	

    $(".paymentcreate").click(function () {
      var table = $("#AccTbl").DataTable();
      var poInvoiceIds = [];
      var poIds = [];

      // Loop through all checked checkboxes
      $("#AccTbl tbody input.row-checkbox:checked").each(function () {
        var rowData = table.row($(this).closest("tr")).data();

        if (rowData) {
          if (rowData.po_invoice_id) poInvoiceIds.push(rowData.po_invoice_id);
          if (rowData.po_id) poIds.push(rowData.po_id);
        }
      });

      // If at least one row is selected
      if (poInvoiceIds.length > 0) {
        window.location.replace(
          "paymentforinvoicecreate/" + poInvoiceIds.join(",") + "/0/0"
        );
      } else {
        showCustomAlert("Please Select a Row", "info");
      }
    });


    // balance close
    $(document).on('click', '.close-btn', function () {

      var table = $('#AccTbl').DataTable();
      var rowData = table.row($(this).closest('tr')).data();

      if (rowData.po_invoice_status === 'APPROVED') {
        // Populate modal fields
        $(".intbal_no").html(rowData.bill_number);
        $(".intbal_date").html(rowData.invoice_date);
        $(".intbal_id").val(rowData.po_invoice_id);
        $(".intprd_id").val(rowData.supplier_name);

        // Show modal
        $("#balcloseModal").modal('show');

        // Fetch closure details via AJAX
        var url = "{{ URL::to('balcloedit') }}?id=" + rowData.po_invoice_id;
        $.get(url, function (data) {
          $('.bal_closure_remarks').val(data.bal_closure_remarks);

          if (data.update !== "create") {
            $('.closed_read_by').css("pointer-events", "none");
            $('.bal_closure_remarks').attr("required", true);
          } else {
            $('.closed_read_by').css("pointer-events", "none");
            $('.bal_closure_remarks').attr("required", true);
          }
        });
      } else {
        showCustomAlert("Please select APPROVED invoices only!", "info");
      }
    });


    // balance close save	
    $(document).on('click', '.balclose_save', function () {

      var id = $(".intbal_id").val();
      var intprd_id = $(".intprd_id").val();
      var closed_by = $(".closed_by").val();
      var bal_closure_remarks = $(".bal_closure_remarks").val();
      if (!bal_closure_remarks) {
        alert('Reason for Balance Close is mandatory');
        return;
      } else {
        $.get("balcloseupdate?id=" + id + "&closed_by=" + closed_by + "&bal_closure_remarks=" + bal_closure_remarks + "&intprd_id=" + intprd_id, function (data) {

          if ($.trim(data) == '1') {
            showCustomAlert("Balance Closed Successfully", "success");
            $('#AccTbl').DataTable().ajax.reload();
          } else {
            showCustomAlert('Please Try Again', 'error');
            $('#AccTbl').DataTable().ajax.reload();
          }
        });
        $("#balcloseModal").modal('hide');
      }

    });



  </script>

@endpush