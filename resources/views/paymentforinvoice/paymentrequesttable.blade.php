@extends('layouts.header')
@section('content')
  <h3 class="text-danger">

    <?php if ($pageMethod == "paymentrequestapproval") { ?>
    Supplier - Payment Request Approval
    <?php } else { ?>
    Supplier - Payment Request
    <?php } ?>

  </h3>
  @include('layouts.breadcrumb')

  <div id="toolbar-container" class="paymentrequested mb-3 mt-1"></div>


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
            <th>GRN Date</th>
            <th>Entry Date</th>
            <th>Due Date</th>
            <th>Invoice Status</th>
            <th>Request Status</th>
            <th>Supplier Name</th>
            <th>MSME Supplier</th>
            <th>Invoice Amount</th>
            <th>PO Number</th>
            <th>PO Date</th>
            <th>Balance Amount</th>
            <th>Advance Amount</th>
            <th>Credit Note Balance</th>
            <th>Debit Note Balance</th>
            <th>Over Due</th>
            <th>MSME Due</th>
            <th>Attachment</th>
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
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'paymentrequest')) {
        $('#toolbar-container').append(`
          <button class="btn btn-success text-white px-4 me-2" value="request">Payment Request</button>
        `);
      }

      if (window.toolbarButtons?.some(btn => btn.attr.id === 'paymentrequestapprove')) {
        $('#toolbar-container').append(`
          <button class="btn btn-success text-white px-4 me-2" value="approve">Payment Request Approve</button>
        `);
      }
    });

    $(document).ready(function () {

      const pagemethod = "{{ $pageMethod }}";
      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "getRequestforpaymentData?pagemethod=" + pagemethod,
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


              return buttons;
            }
          },
          { data: 'bill_number' },
          { data: 'invoice_date' },
          { data: 'grn_date' },
          { data: 'dc_date' },
          { data: 'due_date' },
          { data: 'po_invoice_status' },
          { data: 'requeststatus' },
          { data: 'supplier_name' },
          { data: 'msme_status' },
          { data: 'invoice_grand_total' },
          { data: 'po_number' },
          { data: 'po_date' },
          { data: 'balance_amount' },
          { data: 'advance_amount' },
          { data: 'credit_note_balance' },
          { data: 'debit_note_balance' },
          { data: 'over_due' },
          { data: 'msme_over_due' },
          { data: 'attachfile_name' },

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
      const url = "{{$pageMethod}}";

      window.location.replace('invoiceDataview/' + id + '?return=' + url);

    });


    // payment request 	
    $(document).on('click', '.paymentrequested button', function () {
      var selected = [];
      $("#AccTbl tbody input.row-checkbox:checked").each(function () {
        selected.push($(this).val());
      });

      if (selected.length === 0) {
        showCustomAlert("Please select at least one row", "info");
        return;
      }

      var val = $(this).val();
      var status = (val === "approve") ? "2" : "1";

      var url = "{{ URL::to('getpaymentreq') }}/" + selected.join(",") + "?status=" + status;

      $.get(url, function (data) {
        var msg = data.message;
        showCustomAlert(msg, data.status);
        setTimeout(function () {
          location.reload();
        }, 1500);
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


  </script>

@endpush