@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Purchase Invoice</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="mb-3 mt-2"></div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <div class="table-responsive">
        <table id="purInvTbl" class="table table-bordered table-striped w-100" style="width:220% !important;">
          <thead>
            <tr class="table-warning">

              <th><input type="checkbox" id="select_all"></th>
              <th>Actions</th>
              <th class="freeze">Invoice Number</th>
              <th>Invoice Date</th>
              <th>Invoice Status</th>
              <th>GRN Number</th>
              <th>GRN Date</th>
              <th>Supplier Name</th>
              <th>PO Number</th>
              <th>PO Date</th>
              <th>Invoice Amount</th>
              <th>Remarks</th>
              <th>Created By</th>
              <th>Approved/Rejected By</th>
              <th>Credit Taken</th>
              <th>Credit Taken Month</th>
              <th></th>
            </tr>

            <tr class="table-info">

              <th></th>
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
              <th></th>
            </tr>
          </thead>
          <tbody>
            {{-- DataTable will populate via AJAX --}}
          </tbody>
        </table>
      </div>
    </div>
  </div>


  <!-- Reverse Invoice Confirmation Modal -->
<div class="modal fade" id="reverseConfirmModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">

      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Confirm Invoice Cancellation</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">
        <p class="fs-5 mb-0">Are you sure you want to cancel this invoice?</p>
      </div>

      <div class="modal-footer justify-content-center">
        <input type="hidden" id="reverse_invoice_id">

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          No
        </button>

        <button type="button" class="btn btn-danger" id="confirmReverseBtn">
          Yes, Cancel Invoice
        </button>
      </div>

    </div>
  </div>
</div>

  <!--popup for creditupdate-->
  <!-- Credit Update Details Modal -->
  <div class="modal fade" id="creditModal" tabindex="-1" aria-labelledby="creditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-4">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title fw-bold" id="creditModalLabel">Credit Update Details</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
          <form method="post" action="" id="credit_update_form" data-parsley-validate>
            <input type="hidden" class="invoice_id" value="">

            <!-- Credit Taken -->
            <div class="mb-3">
              <label class="form-label fw-semibold col-sm-3">Credit Taken</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="credit_taken" id="credit_taken_yes" value="Yes">
                <label class="form-check-label" for="credit_taken_yes">Yes</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="credit_taken" id="credit_taken_no" value="No">
                <label class="form-check-label" for="credit_taken_no">No</label>
              </div>
            </div>

            <!-- Date -->
            <div class="mb-3 row align-items-center">
              <label for="credit_date" class="col-sm-3 col-form-label fw-semibold">Date</label>
              <div class="col-sm-5">
                <input type="text" class="form-control start_date previousdates" id="credit_date" name="credit_date"
                  placeholder="Select date">
              </div>
            </div>

            <!-- Extra Section -->
            <div class="row mb-3">
              <div class="emppopup"></div>
            </div>

            <!-- Modal Footer -->
            <div class="text-center mt-4">
              <button type="button" class="btn btn-success px-4" id="credit_update_val" value="SAVE">
                <i class="bi bi-check-circle me-1"></i> Update
              </button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>


  <!--end-->


@endsection
@push('scripts')

  <script>

    // button purpose
    // Add create button purpose
    $(document).ready(function () {

      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
                <button class="btn btn-secondary bg-gradient create_std me-2">Standard Invoice
                </button>
              `);
      }

      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_labour')) {
        $('#toolbar-container').append(`
                <button class="btn btn-success bg-gradient create_labour me-2">Labour Invoice PO
                </button>
              `);
      }

      if (window.toolbarButtons?.some(btn => btn.attr.id === 'direct_labour')) {
        $('#toolbar-container').append(`
                <button class="btn btn-danger bg-gradient direct_labour me-2">Direct Labour Invoice
                </button>
              `);
      }

      if (window.toolbarButtons?.some(btn => btn.attr.id === 'credit_update_old')) {
        $('#toolbar-container').append(`
                <button class="btn btn-primary text-white bg-gradient credit_update me-2"> Credit Update 
                </button>
              `);
      }



    });


    $(document).ready(function () {
      var status = "{{$status}}";
      var pagemethod = "{{$pageMethod}}";

      var table = $('#purInvTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        order: [[16, 'desc']],
        ajax: "getinvoiceData?status=" + status + "&pagemethod=" + pagemethod,
        columns: [
          {   // Checkbox column
            data: 'po_invoice_id',
            render: function (data) {
              return '<input type="checkbox" class="row_checkbox" value="' + data + '">';
            },
            orderable: false,
            searchable: false
          },
          {   // Actions column
            data: null,
            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-primary edit-btn"
                                    data-id="${row.po_invoice_id}"
                                    data-status="${row.po_invoice_status}">
                                    <i class="bi bi-pencil"></i>
                                </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-warning view-btn"
                                    data-id="${row.po_invoice_id}">
                                    <i class="bi bi-eye"></i>
                                </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'print')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-success print-btn"
                                    data-id="${row.po_invoice_id}">
                                    <i class="bi bi-printer"></i> 
                                </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'poview')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-primary poview-btn"
                                    data-id="${row.po_invoice_id}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="PO View">
                               <i class="bi bi-view-list"></i>
                                </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_approve')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-success approve-btn"
                                    data-id="${row.po_invoice_id}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Invoice Approve">
                                   <i class="bi bi-check2-circle"></i>
                                </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'poinvreverse')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-danger reverse-btn"
                                    data-id="${row.po_invoice_id}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="PO Invoice Reverse" data-status="${row.po_invoice_status}" data-paystatus="${row.payment_status}">
                                   <i class="bi bi-bootstrap-reboot"></i>
                                </button>`;
              }

              return buttons;
            },

          },
          { class: 'freeze', data: 'bill_number', name: 'bill_number' },
          { data: 'invoice_date', name: 'invoice_date' },
          { data: 'po_invoice_status', name: 'po_invoice_status' },
          { data: 'grn_number', name: 'grn_number' },
          { data: 'grn_date', name: 'grn_date' },
          { data: 'supplier_name', name: 'supplier_name' },
          { data: 'po_number', name: 'po_number' },
          { data: 'po_date', name: 'po_date' },
          { data: 'invoice_grand_total', name: 'invoice_grand_total' },
          { data: 'remarks', name: 'remarks' },
          { data: 'first_name', name: 'first_name' },
          { data: 'approvedby', name: 'approvedby' },
          { data: 'credit_taken', name: 'credit_taken' },
          { data: 'credit_date', name: 'credit_date' },
          { data: 'po_invoice_id', name: 'po_invoice_id', visible: false },
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
        },
      });

      // Select all checkboxes
      $('#select_all').on('click', function () {
        $('.row_checkbox').prop('checked', this.checked);
      });

      // Clear filter
      $(".clear").click(function () {
        table.search('').columns().search('').draw();
      });

    });


    // create std

    $(document).on('click', '.create_std', function () {

      var url = "{{ url('purchaseinvoicetable') }}";
      window.location.replace(url);

    });

    // 	labour

    $(document).on('click', '.create_labour', function () {

      var url = "{{ url('polabourtable') }}";
      window.location.replace(url);

    });

    // direct labour	


    $(document).on('click', '.direct_labour', function () {
      var url = "{{ url('createpolabourinvoice') }}";
      window.location.replace(url);

    });

    // view	

    $(document).on('click', '.view-btn', function () {

      const id = $(this).data('id');
      var url = "{{$pageMethod}}";
      window.location.replace('invoiceDataview/' + id + '?return=' + url);

    });


    // edit

    $(document).on('click', '.edit-btn', function () {

      const id = $(this).data('id');
      const status = $(this).data('status');

      if (status != "INITIATED" && status != "APPROVED") {

        window.location.replace('invoiceDataedit/' + id);
      }
      else {
        showCustomAlert("Unable To Edit PO Invoice", 'warning');
      }


    });


    // print	

    $(document).on('click', '.print-btn', function () {

      const id = $(this).data('id');
      window.open('poinvoiceprint/' + id, '_blank');

    });

    // creadit update

    $(document).on('click', '.credit_update', function () {
      var table = $('#purInvTbl').DataTable();
      var cellvalues = [];
      var chk = 0;

      // Loop over all checked checkboxes in the table
      $('#purInvTbl tbody input.row_checkbox:checked').each(function () {
        var row = table.row($(this).closest('tr')).data();

        if (row) {
          cellvalues.push(row.po_invoice_id);

          if (row.po_invoice_status !== "APPROVED") {
            chk = 1; // Found a non-approved row
          }
        }
      });

      if (cellvalues.length > 0) {
        if (chk === 0) {
          $('#creditModal').modal('show');
          $('.invoice_id').val(cellvalues.join(',')); // comma separated IDs
        } else {
          showCustomAlert("Please Select Approved Rows Only", 'info');
        }
      } else {
        showCustomAlert("Please Select a Row", 'info');
      }
    });

    // approve

    $(document).on('click', '.approve-btn', function () {


      const id = $(this).data('id');
      const status = $(this).data('status');

      window.location.replace('poinvapprove/' + id + '/1');


    });


    $(document).on('click', '#credit_update_val', function () {
      var ivoiceid = $('.invoice_id').val();
      var url = "{{URL::to('creditupdate')}}/" + ivoiceid;
      var formdata = $('#credit_update_form').serialize();

      $.post(url, formdata, function (data) {
        var status = data.status;
        var msg = data.message;


        showCustomAlert(msg, status);

        var red_url = "{{ URL::to('purchaseinvoice') }}";
        window.location.href = red_url;

      });


    });



    // po view 
    $(document).on('click', '.poview-btn', function () {


      const id = $(this).data('id');

      var url = "{{URL::to('poviewinvoice')}}/" + id + "/?invoice=invoice";
      window.location.replace(url);


    });

    // reverse button click
$(document).on('click', '.reverse-btn', function () {

    const id = $(this).data('id');
    const status = $(this).data('status');
    const payment_status = $(this).data('paystatus');

    if (status !== 'APPROVED') {
        showCustomAlert('Only APPROVED invoices can be cancelled', 'warning');
        return;
    }

    if (payment_status != 0) {
        showCustomAlert('Invoice with payment cannot be cancelled','warning');
        return;
    }

    // store invoice id in modal
    $('#reverse_invoice_id').val(id);

    // show modal
    $('#reverseConfirmModal').modal('show');
});

$('#confirmReverseBtn').on('click', function () {

    var invoiceId = $('#reverse_invoice_id').val();

    $.ajax({
        url: "{{ url('poinvoiceReverse') }}/" + invoiceId,
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function (response) {

            $('#reverseConfirmModal').modal('hide');

            showCustomAlert('Purchase Invoice Cancelled', 'success');

            // reload table without refresh
            $('#purInvTbl').DataTable().ajax.reload(null, false);
        },
        error: function () {
            showCustomAlert('Unable to cancel invoice', 'error');
        }
    });

});




  </script>

@endpush