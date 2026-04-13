@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    <?php if ($pageMethod == "salesinvoice") { ?>
    Sales invoice
    <?php } else if ($pageMethod == "salesinvoiceapproval") { ?>
    Sales invoice Approval
    <?php  } else if ($pageMethod == 'dispatchfrminvoice') { ?>
    Dispatch From Invoice
    <?php  } ?>
  </h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="mb-3 mt-2"></div>
  <style>
    .select2-container--open {
      z-index: 200000 !important;
    }
  </style>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="SalesInvTbl" class="table table-bordered table-striped">
        <thead>
          <tr class="table-warning">
            <th></th>
            <th>Actions</th>
            <th></th>
            <th>Invoice Number</th>
            <th>Invoice Type</th>
            <th>Invoice Date</th>
            <th>Customer Name</th>
            <th>Employee Name</th>
            <th>SO Invoice Status</th>
            <th>Shipped Status</th>
            <th>Sales Order Number</th>
            <th>Dispatch Number</th>
            <th>Pricelist Name</th>
            <th>Remarks</th>
            <th>Invoice Amount</th>
            <th>LR Number</th>
            <th>LR Date</th>
            <th>LR Status</th>
            <th>ACK Number</th>
            <th>Cheque Number</th>
            <th>Cheque Date</th>
            <th>Transport Name</th>
            <th>Created By</th>
            <th>Approved By</th>

          </tr>
          <tr class="table-info">
            <th data-column="0"><input class="form-control form-control-sm column-search"></th>
            <th data-column="1"><input class="form-control form-control-sm column-search"></th>
            <th data-column="2"><input class="form-control form-control-sm column-search"></th>
            <th data-column="3"><input class="form-control form-control-sm column-search"></th>
            <th data-column="4"><input class="form-control form-control-sm column-search"></th>
            <!-- <th data-column="4"><input class="form-control form-control-sm column-search"></th> -->
            <th data-column="5">
                <div class="d-flex gap-1">
              <input type="text" id="inv_from_date"
                     class="form-control form-control-sm comman_date"
                     placeholder="From">

              <input type="text" id="inv_to_date"
                     class="form-control form-control-sm comman_date"
                     placeholder="To">
                 </div>    
          </th>

            <th data-column="6"><input class="form-control form-control-sm column-search"></th>
            <th data-column="7"><input class="form-control form-control-sm column-search"></th>
            <th data-column="8"><input class="form-control form-control-sm column-search"></th>
            <th data-column="9"><input class="form-control form-control-sm column-search"></th>
            <th data-column="10"><input class="form-control form-control-sm column-search"></th>
            <th data-column="11"><input class="form-control form-control-sm column-search"></th>
            <th data-column="12"><input class="form-control form-control-sm column-search"></th>
            <th data-column="13"><input class="form-control form-control-sm column-search"></th>
            <th data-column="14"><input class="form-control form-control-sm column-search"></th>
            <th data-column="15"><input class="form-control form-control-sm column-search"></th>
            <th data-column="16"><input class="form-control form-control-sm column-search"></th>
            <th data-column="17"><input class="form-control form-control-sm column-search"></th>
            <th data-column="18"><input class="form-control form-control-sm column-search"></th>
            <th data-column="19"><input class="form-control form-control-sm column-search"></th>
            <th data-column="20"><input class="form-control form-control-sm column-search"></th>
            <th data-column="21"><input class="form-control form-control-sm column-search"></th>
            <th data-column="22"><input class="form-control form-control-sm column-search"></th>
            <th data-column="23"><input class="form-control form-control-sm column-search"></th>
          </tr>
        </thead>
        <tfoot>
    <tr class="table-info fw-bold">
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
    </tr>
    <tr class="table-success fw-bold">
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
    </tr>
</tfoot>

        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modals -->
  <!-- Contact Modal -->
  <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content" style="max-height: 80vh; overflow-y: auto;">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="contactModalLabel">Contact</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form method="POST" id="enquirymail" class="needs-validation" novalidate enctype="multipart/form-data">
          <div class="modal-body">

            <!-- Customer Info -->
            <div class="mb-4">
              <label class="form-label fw-bold">Customer Name</label>
              <input type="text" name="customer_name" class="form-control customer_name" readonly>
              <input type="hidden" name="invoice_hdr_id" class="invoice_hdr_id">
            </div>

            <!-- Contact Table -->
            <div class="table-responsive mb-4">
              <table class="table table-bordered align-middle">
                <thead class="table-light">
                  <tr>
                    <th>#</th>
                    <th>Contact Person</th>
                    <th>Contact Number</th>
                    <th>Contact Email</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="mcontent2"></tbody>
              </table>
            </div>

            <!-- CC Field -->
            <div class="mb-3">
              <label class="form-label fw-bold">CC</label>
              <input type="text" name="cc[]" class="form-control cc" placeholder="Enter CC emails (comma-separated)">
            </div>

            <!-- Message -->
            <div class="mb-4">
              <label class="form-label fw-bold">Message</label>
              <textarea name="msg" class="form-control msg tinymce msg_editor" rows="6"></textarea>
              <input type="hidden" name="hdr_id" class="hdr_id">
            </div>

            <!-- Attachments -->
            <div class="mb-4">
              <label class="form-label fw-bold">Attachments</label>
              <input type="file" name="email_attachment[]" class="form-control email_attachment" multiple>
            </div>

            <!-- PDF Attach Option -->
            <div class="form-check mb-4">
              <input class="form-check-input attchment" type="checkbox" name="attchment" value="">
              <label class="form-check-label">Attach PDF</label>
            </div>

            <div class="preview mb-3"></div>

            <!-- PDF Preview -->
            <iframe id="iframepdf" class="w-100 border rounded d-none" height="400"></iframe>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success sendmail" id="sentmail_id">Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Invoice Upload Modal -->
  <div class="modal fade" id="invoice_Modal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="invoiceModalLabel">Invoice Upload</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="d-flex justify-content-between mb-3">
            <div><strong>Invoice Number:</strong> <input class="s_invoice_number text-primary fw-bold"></div>
            <div><strong>Invoice Type:</strong> <input class="s_invoice_type text-primary fw-bold"></div>
          </div>

          <form id="UploadInvoiceform" enctype="multipart/form-data">
            <input type="hidden" name="id" class="form-control inv_hdr_id">
            <div class="mb-3">
              <label class="form-label fw-bold">Choose File</label>
              <input type="file" name="doc_attachment" class="form-control doc_attachment" multiple>
            </div>
          </form>
        </div>

        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-success invoice_uploc_save" id="invoice_uploc_save">Upload</button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Eway Bill Modal -->
  <div class="modal fade" id="myEwayModal" tabindex="-1" aria-labelledby="ewayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="ewayModalLabel">Eway Bill Number Update</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <div class="d-flex justify-content-between mb-3">
            <div><strong>Invoice Number:</strong> <span class="inv_no"></span></div>
            <div><strong>Invoice Date:</strong> <span class="inv_date"></span></div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Eway Bill No.</label>
              <input type="text" class="form-control eway_billno" placeholder="Enter Eway Bill Number">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Eway Bill Date</label>
              <input type="text" class="form-control eway_date" placeholder="Select Date">
              <input type="hidden" class="form-control inv_id">
            </div>
          </div>

        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-success eway_save" id="updateClose">Update</button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- LR Number Update Modal -->
  <div class="modal fade" id="myLRModal" tabindex="-1" aria-labelledby="lrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="lrModalLabel">LR Number Update</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="d-flex justify-content-between mb-3">
            <div><strong>Invoice Number:</strong> <span class="inv_no"></span></div>
            <div><strong>Invoice Date:</strong> <span class="inv_date"></span></div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">LR No.</label>
              <input type="text" class="form-control lr_number" placeholder="Enter LR Number">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">LR Date</label>
              <input type="text" class="form-control lr_date" placeholder="Select LR Date">
              <input type="hidden" class="form-control inv_id">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">LR Status</label>
              <select class="form-select select2 lr_status" id="lr_status" name="lr_status">
                <!-- Options populated dynamically -->
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Delivery Date</label>
              <input type="text" class="form-control delivery_date" placeholder="Select Delivery Date">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Cheque Number</label>
              <input type="text" class="form-control cheque_number" placeholder="Enter Cheque Number">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Cheque Date</label>
              <input type="text" class="form-control cheque_date" placeholder="Select Cheque Date">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Cheque Amount</label>
              <input type="text" class="form-control cheque_amount" placeholder="Enter Amount">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Cheque Received Date</label>
              <input type="text" class="form-control cheque_received_date" placeholder="Select Received Date">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">ACK No</label>
              <input type="text" class="form-control irn_no" placeholder="Enter ACK Number">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">ACK Date</label>
              <input type="text" class="form-control irn_date" placeholder="Select ACK Date">
            </div>
          </div>

        </div>

        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-success lr_save" id="updateClose">Update</button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Upload Documents Modal -->
  <div class="modal fade" id="upload_Modal_id" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content" style="max-height:80vh;overflow-y:auto;">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="uploadModalLabel">
            <?php if ($pageMethod == "salesinvoice") { ?>
            Add Documents
            <?php } elseif ($pageMethod == "salesinvoiceapproval") { ?>
            Download Documents
            <?php } ?>
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <form method="POST" action="{{ URL::to('salesinvoiceuploads') }}" id="add_file_name"
            enctype="multipart/form-data">
            {{ csrf_field() }}

            <?php if ($pageMethod == "salesinvoice") { ?>
            <div class="mb-3">
              <label class="form-label fw-bold">Upload Documents</label>
              <input type="file" id="choosefile" name="choosefile[]" class="form-control" multiple required>
            </div>
            <?php } ?>

            <!-- File List -->
            <div class="table-responsive mb-3">
              <table class="table table-bordered align-middle">
                <thead class="table-light">
                  <tr>
                    <th style="width:10%">S.No</th>
                    <th style="width:60%">File</th>
                    <th style="width:15%">Preview</th>
                    <th style="width:15%">Action</th>
                  </tr>
                </thead>
                <tbody class="sales_body"></tbody>
              </table>
            </div>

            <!-- Save Button -->
            <div class="text-center mb-3 save_img_div">
              <input id="file_save_id" type="submit" value="Save" class="btn btn-primary px-5 file_save_cls">
              <input type="hidden" name="salesorder_id" class="salesorder_id">
            </div>

            <!-- File Preview -->
            <div class="text-center file_prvw_div">
              <iframe src="" id="p_pvw" class="border rounded" frameborder="0" scrolling="no" width="400"
                height="600"></iframe>
              <br>
              <input type="hidden" name="doc_name" id="doc_name" class="doc_name" readonly>
              <button type="button" id="download_btn" class="btn btn-outline-secondary mt-3" style="display:none;">
                <i class="fa fa-download"></i> Download
              </button>
              <input type="hidden" name="invoice_id" id="invoice_id" class="invoice_id">
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>


@endsection
@push('scripts')

  <script>

    // Add create button purpose
    $(document).ready(function () {

      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_standard')) {
        $('#toolbar-container').append(`
                  <button class="btn btn-primary bg-gradient create_std me-2">Create Standard
                  </button>
                `);
      }

      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_labour')) {
        $('#toolbar-container').append(`
                  <button class="btn btn-secondary bg-gradient create_labour me-2">Export Invoice
                  </button>
                `);
      }

    });



    // create std


    $(document).on('click', '.create_std', function () {
      var enqtype = "STANDARD";
      var url = "{{ url('salesinvoicecreate') }}/0/" + enqtype;
      var red_url = "{{ url('salesinvoice') }}";
      window.location.replace(url);
    });

    $(document).on('click', '.create_labour', function () {
      var enqtype = "EXPORT INVOICE";
      var url = "{{ url('salesinvoicecreate') }}/0/" + enqtype;
      var red_url = "{{ url('salesinvoice') }}";
      window.location.replace(url);
    });


    $.fn.dataTable.ext.search.push(
    function (settings, data) {

        let from = $('#inv_from_date').val();
        let to   = $('#inv_to_date').val();

        let invoiceDate = data[4]; // Invoice Date column

        if (!from && !to) return true;

        let invDate  = new Date(invoiceDate);
        let fromDate = from ? new Date(from) : null;
        let toDate   = to ? new Date(to) : null;

        return (
            (!fromDate || invDate >= fromDate) &&
            (!toDate || invDate <= toDate)
        ); 
    }
  );


    // data table  funcrion	
    $(document).ready(function () {

      var table = $('#SalesInvTbl').DataTable({
        processing: true,
        serverSide: false,
        order: [[0, 'desc']],
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "getSalesinvoiceData?status={{$status}}&dispatch_status={{$dispatch_status}}",
        columns: [
              {
                data: 'invoice_hdr_id',
                visible: false,   
                searchable: false
              },
          {
            data: 'invoice_hdr_id',
            name: 'actions',
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
                                  <button type="button" class="btn btn-sm btn-primary edit-btn"
                                      data-id="${row.invoice_hdr_id}"
                                      data-status="${row.invoice_status}">
                                      <i class="bi bi-pencil"></i>
                                  </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                                  <button type="button" class="btn btn-sm btn-warning view-btn me-1"
                                      data-id="${row.invoice_hdr_id}">
                                      <i class="bi bi-eye"></i>
                                  </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'dispatch')) {
                buttons += `
                                  <button type="button" class="btn btn-sm btn-success dispatch-btn"
                                      data-id="${row.invoice_hdr_id}">
                                      Dispatch
                                  </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'lr_update')) {
                buttons += `
                                  <button type="button" class="btn btn-sm btn-secondary lr-btn me-1"
                                      data-id="${row.invoice_hdr_id}" data-date="${row.invoice_date}" data-number="${row.invoice_number}" data-status="${row.invoice_status}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Update LR.No">
                                     <i class="bi bi-view-list"></i>
                                  </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                buttons += `
                                  <button type="button" class="btn btn-sm btn-success approve-btn"
                                      data-id="${row.invoice_hdr_id}"
                      data-date="${row.invoice_date}"
                      data-type="${row.invoice_type}"
                      data-currency="${row.invoice_currency}"
                      data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Approve">
                                      <i class="bi bi-check2-circle"></i>
                                  </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'print')) {
                buttons += `
                                  <button type="button" class="btn btn-sm btn-success print-btn"
                                      data-id="${row.invoice_hdr_id}"
                      data-status="${row.invoice_status}"
                      data-shipstatus="${row.shiped_status}"
                          data-bs-toggle="tooltip" 
                          data-bs-placement="top" 
                          title="Print">
                                      <i class="bi bi-printer"></i> 
                                  </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'mail')) {
                buttons += `
                                  <button type="button" class="btn btn-sm btn-secondary mail-btn"
                                      data-id="${row.invoice_hdr_id}"
                      data-status="${row.invoice_status}"
                      data-name="${row.customer_name}"
                      data-cusid="${row.customer_id}"
                      data-empname="${row.first_name}"
                      data-empid="${row.employee_id}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Mail">
                                      <i class="bi bi-envelope"></i> 
                                  </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
              <button class="btn btn-sm btn-danger delete-btn" data-id="${row.invoice_hdr_id}" data-type="${row.price_list_type}">
                <i class="bi bi-trash"></i>
              </button>`;
              }

              <?php	if ($pageMethod == "salesinvoice") {  ?>

              buttons += `
              <button class="btn btn-sm btn-success bol-btn" data-id="${row.invoice_hdr_id}" data-date="${row.invoice_date}" data-number="${row.invoice_number}" data-status="${row.invoice_status}" data-shipstatus="${row.shiped_status}" data-type="${row.invoice_type}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Upload BOL">
               <i class="bi bi-cloud-upload"></i>
              </button>`;


              buttons += `
              <button class="btn btn-sm btn-danger eway-btn" data-id="${row.invoice_hdr_id}" data-date="${row.invoice_date}" data-number="${row.invoice_number}" data-status="${row.invoice_status}" data-shipstatus="${row.shiped_status}" data-type="${row.invoice_type}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Update Eway Bill No">
                <i class="bi bi-collection"></i>
              </button>`;

              <?php	}  ?>


              buttons += `
              <button class="btn btn-sm btn-primary duplicate-btn" data-id="${row.invoice_hdr_id}" data-type="${row.price_list_type}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Duplicate Copy">
                <i class="bi bi-copy"></i>
              </button>`;

              buttons += `
              <button class="btn btn-sm btn-info transport-btn" data-id="${row.invoice_hdr_id}" data-Invstatus="${row.invoice_status}"
          data-shipstatus="${row.shiped_status}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Transport Copy">
                <i class="bi bi-copy"></i>
              </button>`;

              return buttons;
            }
          },
          { data: 'invoice_currency', name: 'invoice_currency', visible: false },
          { data: 'invoice_number', name: 'invoice_number' },
          { data: 'invoice_type', name: 'invoice_type' },
          { data: 'invoice_date', name: 'invoice_date' },
          { data: 'customer_name', name: 'customer_name' },
          { data: 'first_name', name: 'first_name' },
          { data: 'invoice_status', name: 'invoice_status' },
          { data: 'shiped_status', name: 'shiped_status' },
          { data: 'sales_order_no', name: 'sales_order_no' },
          { data: 'dispatch_number', name: 'dispatch_number' },
          { data: 'pricelist_name', name: 'pricelist_name' },
          { data: 'remarks', name: 'remarks' },
          { data: 'invoice_grand_total', name: 'invoice_grand_total' },
          { data: 'lr_no', name: 'lr_no' },
          { data: 'lr_date', name: 'lr_date' },
          { data: 'lr_status', name: 'lr_status' },
          { data: 'irn_no', name: 'irn_no' },
          { data: 'cheque_no', name: 'cheque_no' },
          { data: 'cheque_amount', name: 'cheque_amount' },
          { data: 'carrier_name', name: 'carrier_name' },
          { data: 'created_by', name: 'created_by' },
          { data: 'last_updated_by', name: 'last_updated_by' },

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
    let pageOpening = api.column(13, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    // -------------------------
    // GRAND TOTAL (ALL rows)
    // -------------------------
    let grandOpening = api.column(13).data()
        .reduce((a, b) => num(a) + num(b), 0);

      // PAGE TOTAL row (1st footer row)
    $(api.column(13).footer()).closest('tfoot').find('tr:eq(0) th:eq(13)')
        .html(pageOpening.toFixed(2));
    
    // GRAND TOTAL row (2nd footer row)
    $(api.column(13).footer()).closest('tfoot').find('tr:eq(1) th:eq(13)')
        .html(grandOpening.toFixed(2));
    
    },

        initComplete: function () {
          var api = this.api();

          // get the real visible header inside the scroll container
          var $scrollHead = $(api.table().container())
            .find('.dataTables_scrollHead thead');

          // second header row (index 1) has the inputs
            $('#inv_from_date, #inv_to_date').on('change', function () {
            $('#SalesInvTbl').DataTable().draw();
            });

      $scrollHead.find('input.column-search').on('keyup change clear', function () {

    let columnIndex = $(this).closest('th').data('column');

    // Skip Invoice Date column (handled by BETWEEN filter)
    if (columnIndex == 4) return;

    api.column(columnIndex).search(this.value).draw();
    });
/*
             $scrollHead.find('input.column-search').on('keyup change clear', function () {

                let columnIndex = $(this).closest('th').data('column');

                if (api.column(columnIndex).search() !== this.value) {
                    api.column(columnIndex).search(this.value).draw();
                }
            });
*/        }

      });

    });




    $(document).ready(function () {


      $(document).on('click', '.mail-btn', function () {

        var postatus = $(this).data('status');

        if (postatus != "INITIATED" && postatus != "CANCELLED" && postatus != "DRAFT") {

          const id = $(this).data('id');
          const sup_id = $(this).data('cusid');
          let sup_name = $(this).data('name');

          var type = "customer";

          if (sup_id == "") {
            sup_id = $(this).data('empid');
            type = "employee";
            let sup_name = $(this).data('empname');
          }

          $('.mcontent2').html('');
          $('.customer_name').val(sup_name);
          $('.invoice_hdr_id').val(id);
          var url_print = '{{URL::to("customermaildetails")}}/' + sup_id + '?type=' + type;
          $.get(url_print, function (data) {
            if (data == "") {
              showCustomAlert("No Email Contact For Current Customer...Please Add Email First...", "info");
            }
            else {

              $.each(data, function (key) {

                $('.mcontent2').append('<tr class="cont_row">\n\
                          <td><input type="checkbox" name="check_mail" class="check_mail mail_name"  value="' + data[key][3] + '" data-id="' + data[key].id + '" data-value="' + data[key].id + '" required></td>\n\
                          <td><input type="text" name="contact_person[]" class="form-control" value="' + data[key][1] + '" readonly/></td>\n\
                          <td><input type="text" name="contact_number[]" class="form-control" value="' + data[key][2] + '" readonly/></td>\n\
                          <td><input type="text" name="email_id[]" class="form-control" value="' + data[key][3] + '" readonly/></td>\n\
                          </tr>');

                $(".check_mail").click(function () {

                  var check = $(this).is(":checked");
                  var index = $(this).closest('tr').index();

                  var att_check = $(".attchment").parent('[class*="icheckbox"]').hasClass("checked");

                  if (att_check) {

                  }

                  if (check == true) {

                    var id = $(this).data('value');

                    $('.cont_row input:not(.check_mail)').attr('disabled', 'disabled');
                    $('.cont_row:eq(' + index + ') input').removeAttr('disabled');

                  }
                  else {
                    showCustomAlert("Please Check Any Email Contact First...", "warning");
                    $('.cont_row input').removeAttr('disabled');

                  }

                });

              });

            }

          });

          $('#contactModal').modal('show');

        } else {
          showCustomAlert("Please Select Approved Only", "warning");
        }

      });


      $(document).on('click', '.print-btn', function () {

        const id = $(this).data('id');


        const invoice_status = $(this).data('status');
        const shipped_status = $(this).data('shipstatus');
        console.log(shipped_status);

        if (!['DRAFT', 'INITIATED'].includes(invoice_status) && !shipped_status) {
          var url = "{{URL::to('invoiceshipconfirm')}}/" + id;
          $.get(url, function (data) {
            if (data == 1) {
              var url = "salesinvoiceprint";
              var editUrl = url + '/' + id + "?copy=Original Copy";
              window.open(editUrl, '_blank');
            } else {
              var url = "salesinvoiceprint";
              var editUrl = url + '/' + id + "?copy=Original Copy";
              window.open(editUrl, '_blank');
              showCustomAlert("Please Select Shipped Invoice", "warning");
            }
          });

        }
        else {
          showCustomAlert("DRAFT / INITIATED / SHIPPED Invoice Cannot be Print", "warning");
        }
      });


      // delete function
      let deleteId = null;

      $(document).on('click', '.delete-btn', function () {
        deleteId = $(this).data('id');
        $('#globalDeleteModal').modal('show');
      });

      $('#globalConfirmDeleteBtn').on('click', function () {
        if (deleteId) {
          $.ajax({
            url: "{{ url('salesinvoicedelete') }}/" + deleteId,
            type: "GET",
            success: function (data) {
              if (data == '1') {
                $('#globalDeleteModal').modal('hide');
                showCustomAlert("You Can't delete , Subinventory Used in SomeWhere.", 'error');
                $('#SalesInvTbl').DataTable().ajax.reload();
              } else {

                $('#globalDeleteModal').modal('hide');
                showCustomAlert('Deleted successfully!', 'success');
                $('#SalesInvTbl').DataTable().ajax.reload();
              }
            },
            error: function (xhr) {
              $('#globalDeleteModal').modal('hide');
              const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
              showCustomAlert(errorMsg, 'error');
            }
          });
        }
      });


      $(document).on('click', '.dispatch-btn', function () {

        const id = $(this).data('id');
        const source = $(this).data('source');

        if (source == "DISPATCH") {
          showCustomAlert("Sales Invoice Already Dispatched", "warning");
        } else {
          var url = "dispatchcreate";
          var editUrl = url + '/' + id + '/show';
          window.location.replace('dispatchcreate/' + id + '?status=INVOICE');
        }

      });



      //edit function
      $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        const status = $(this).data('status');
        const invoicetype = $(this).data('type');

        if (invoicetype == 'STANDARD')
          var type = 1;
        else
          var type = 2;

        if (status != "APPROVED" && status != "INITIATED") {
          window.location.replace('salesinvoicecreate/' + id + '/' + type);
        }else {
          showCustomAlert("Approved Sales In-voice Cannot Be Edit!!!","error");
        }

      });


      //view function
      $(document).on('click', '.view-btn', function () {

        const id = $(this).data('id');
        var return1 = "{{$pageMethod}}";

        if (return1 == "salesinvoiceapproval") {
          var url = "salesinvoiceapprovalview";
        } else {
          var url = "salesinvoiceview";
        }
        var editUrl = url + '/' + id + '?return=' + return1;
        window.location.replace(editUrl);
      });


      // approve
      $(document).on('click', '.approve-btn', function () {

        var invoice_hdr_id = $(this).data('id');
        var invoice_type = $(this).data('type');
        var invoice_date = $(this).data('date');
        var invoice_currency = $(this).data('currency');

        var val = $(this).val();

        if (invoice_type == 'EXPORT INVOICE' || invoice_type == 'EXPORT SAMPLE') {
          var url = "{{URL::to('exportinvratevalidation')}}/" + invoice_date + '/' + invoice_currency;
          $.get(url, function (data) {
            if (data == 1) {

              window.location.replace('salesinvoiceapprovalcreate/' + invoice_hdr_id);

            } else {

              showCustomAlert("PLEASE UPDATE EXCHANGE RATE FOR INVOICE DATE RANGE !!!", "Warning");
            }
          });
        } else {
          window.location.replace('salesinvoiceapprovalcreate/' + invoice_hdr_id);
        }
      });


      // duplicate copy


      $(document).on('click', '.duplicate-btn', function () {

        const id = $(this).data('id');

        var url = "{{URL::to('invoiceshipconfirm')}}/" + id;
        $.get(url, function (data) {
          if (data == 1) {
            var url = "salesinvoiceprint";
            var editUrl = url + '/' + id + "?copy=Duplicate Copy";
            window.open(editUrl, '_blank');
          } else {
            var url = "salesinvoiceprint";
            var editUrl = url + '/' + id + "?copy=Duplicate Copy";
            window.open(editUrl, '_blank');
            showCustomAlert("Please Select Shipped Invoice", "info");
          }
        });
      });



      // Transport Copy

      $(document).on('click', '.transport-btn', function () {


        var cellValue = $(this).data('id');
        var invoice_status = $(this).data('Invstatus');
        var shipped_status = $(this).data('shipstatus');


        if (invoice_status != 'DRAFT' && invoice_status != 'INITIATED' && shipped_status == '') {
          var url = "{{URL::to('invoiceshipconfirm')}}/" + cellValue;
          $.get(url, function (data) {
            if (data == 1) {
              var url = "salesinvoiceprint";
              var editUrl = url + '/' + cellValue + "?copy=Transport Copy";
              window.open(editUrl, '_blank');
            } else {
              var url = "salesinvoiceprint";
              var editUrl = url + '/' + cellValue + "?copy=Transport Copy";
              window.open(editUrl, '_blank');
              showCustomAlert("Please Select Shipped Invoice", "info");
            }
          });

        }
        else {
          showCustomAlert("DRAFT / INITIATED / SHIPPED Invoice Cannot be Print", "info");
        }

      });


      // lR no Update

      $(document).on('click', '.lr-btn', function () {

        var invoice_hdr_id = $(this).data('id');
        var invoice_number = $(this).data('number');
        var invoice_date = $(this).data('date');
        var invoice_status = $(this).data('status');

        if (invoice_status == 'APPROVED') {

          $(".inv_no").html(invoice_number);
          $(".inv_date").html(invoice_date);
          $(".inv_id").val(invoice_hdr_id);
          $("#myLRModal").modal('show');
          var url = "{{URL::to('lredit')}}?id=" + invoice_hdr_id;
          $.get(url, function (data) {

            $('.lr_number').val(data.lr_no);
            $('.lr_date').val(data.lr_date);
            $('.delivery_date').val(data.delivery_date);
            $('.lr_status').select2('val', [data.lr_status]);
            $('.cheque_number').val(data.cheque_number);
            $('.cheque_date').val(data.cheque_date);
            $('.cheque_amount').val(data.cheque_amount);
            $('.cheque_received_date').val(data.cheque_received_date);
            $('.irn_no').val(data.irn_no);
            $('.irn_date').val(data.irn_date);
            if (data.update != "create") {
            } else {
              $('.lr_number').attr("readonly", false);
              $('.lr_date').css("pointer-events", "auto");

            }

          });
        }
        else {
          showCustomAlert("Please Select Approved Invoice", "error");
        }

      });

      // BOL update
      $(document).on('click', '.bol-btn', function () {

        var id = $(this).data('id');
        var invoice_num = $(this).data('number');
        var invoice_type = $(this).data('type');
        var ship_status = $(this).data('shipstatus');
        var invoice_status = $(this).data('status');

        if ((invoice_type == 'EXPORT INVOICE' || invoice_type == 'EXPORT SAMPLE') && ship_status == 'SHIPPED' && invoice_status == 'APPROVED') {

          $("#invoice_Modal").modal('show');
          $('.s_invoice_type').val(invoice_type);
          $('.s_invoice_number').val(invoice_num);
          $('.inv_hdr_id').val(id);

        } else {
          showCustomAlert("Please Select Approved invoice", "info");
        }
      });


      $('.invoice_uploc_save').click(function () {
        var formData = new FormData($('#UploadInvoiceform')[0]);

        $.ajax({
          url: '{{ route('invoicedocupload') }}',
          type: 'POST',
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          contentType: false,
          processData: false,
          success: function (response) {
            if (response.success) {
              notyMsg('success', "Invoice Uploaded Successfully");
              $('#invoice_Modal').modal('hide');
            } else {
              alert('File upload failed: ' + response.error);
            }
          },
          error: function (xhr, status, error) {
            showCustomAlert('Please Try Again', 'error');
          }
        });
      });


      $(document).on('click', '.eway-btn', function () {



        var invoice_hdr_id = $(this).data('id');
        var invoice_number = $(this).data('number');
        var invoice_date = $(this).data('date');
        var invoice_status = $(this).data('status');

        if (invoice_status == 'APPROVED') {

          $(".inv_no").html(invoice_number);
          $(".inv_date").html(invoice_date);
          $(".inv_id").val(invoice_hdr_id);
          $("#myEwayModal").modal('show');
          var url = "{{URL::to('ewayedit')}}?id=" + invoice_hdr_id;
          $.get(url, function (data) {

            $('.eway_billno').val(data.eway_billno);
            $('.eway_date').val(data.eway_date);
            if (data.update != "create") {

            } else {
              $('.eway_billno').attr("readonly", false);
              $('.eway_date').css("pointer-events", "auto");
            }

          });
        } else {
          showCustomAlert("Please Select Approved Invoice", "showCustomAlert");
        }

      });

      // Update eway bill no
      $(document).on('click', '.eway_save', function () {

        var id = $(".inv_id").val();
        var eway_billno = $(".eway_billno").val();

        var eway_date = $(".eway_date").val();
        $.get("ewayupdate?id=" + id + "&eway_billno=" + eway_billno + "&eway_date=" + eway_date, function (data) {

          if ($.trim(data) == '1') {
            showCustomAlert("Eway Bill No. Updated Successfully", "success");
          } else {
            showCustomAlert('Please Try Again', 'error');
          }
          $("#myEwayModal").modal('hide');
          window.location.reload()
        });

      });

      // lr save
      $(document).on('click', '.lr_save', function () {

        var id = $(".inv_id").val();
        var lr_no = $(".lr_number").val();
        var lr_date = $(".lr_date").val();
        var lr_status = $(".lr_status").val();
        var delivery_date = $(".delivery_date").val();
        var cheque_number = $(".cheque_number").val();
        var cheque_date = $(".cheque_date").val();
        var cheque_amount = $(".cheque_amount").val();
        var cheque_received_date = $(".cheque_received_date").val();
        var irn_no = $(".irn_no").val();
        var irn_date = $(".irn_date").val();

        $.get("lrupdate?id=" + id + "&lr_no=" + lr_no + "&lr_date=" + lr_date + "&lr_status=" + lr_status + "&delivery_date=" + delivery_date + "&cheque_number=" + cheque_number + "&cheque_date=" + cheque_date + "&cheque_amount=" + cheque_amount + "&cheque_received_date=" + cheque_received_date + "&irn_no=" + irn_no + "&irn_date=" + irn_date, function (data) {

          if ($.trim(data) == '1') {
            showCustomAlert("LR/Cheque/IRN Details Updated Successfully", "success");
            window.location.reload();
          } else {
            showCustomAlert('Please Try Again', 'error');
          }
        });
        $("#myLRModal").modal('hide');

      });

      $('#contactModal').on('shown.bs.modal', function () {

        var index = $("#salesinvoicegrid").jqGrid('getGridParam', 'selrow');

        $('.sendmail').click(function () {
          var id = $("#salesinvoicegrid").jqGrid('getCell', index, 'invoice_hdr_id');
          var suid = $("#salesinvoicegrid").jqGrid('getCell', index, 'customer_id');
          var sales_order_date = $("#salesinvoicegrid").jqGrid('getCell', index, 'sales_order_date');
          var sales_order_no = $("#salesinvoicegrid").jqGrid('getCell', index, 'sales_order_no');
          var invoice_number = $("#salesinvoicegrid").jqGrid('getCell', index, 'invoice_number');
          var check = $('.mail_name').is(":checked");
          var att_check = $(".attchment").is(":checked");
          // var mail=$("input[name='check_mail']:checked").val();
          var mail = [];
          $(':checkbox:checked').each(function (i) {
            mail[i] = $(this).val();
          });
          var mail1 = mail.filter(function (v) { return v !== '' });
          var cc = $('.cc').val();
          var msg = $('.msg').val();
          var msg = "Dear Sir/Madam, <br> Your order has been processed and the details of the despatch are follows. <br><br> Order Date: " + sales_order_date + "<br> Order Number: " + sales_order_no + "<br> Invoice Number: " + invoice_number + "<br> Regards,<br> Logistics Team";
          //var msg=$('.msg').html(html);
          if (check == true) {
            if (att_check == true) {
              var url = "{{ URL::to('salesinvoiceprint') }}/" + id + '?mail=' + mail1 + '&cc=' + cc + '&msg=' + msg;
              $.get(url, function (data) {
                showCustomAlert("Mail Send Successfully", "success");
                location.reload();

              });
              filesave();
            } else {
              showCustomAlert("Please Check Attach Pdf", "warning");
            }
          }
          else {
            showCustomAlert("Please Check Contact", "info");
          }
        });
        function filesave() {
          var form_data = new FormData(document.getElementById('enquirymail'));
          console.log(form_data);
          $.ajax({
            url: "{{URL::to('invoicefilesave')}}",
            type: "POST",
            data: form_data,
            enctype: 'multipart/form-data',
            //enctype: 'text/html',
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            async: true,
            xhr: function () {
              var xhr = $.ajaxSettings.xhr();
              if (xhr.upload) {
                xhr.upload.addEventListener('progress', function (event) {
                  var percent = 0;
                  var position = event.loaded || event.position;
                  var total = event.total;
                  if (event.lengthComputable) {
                    percent = Math.ceil(position / total * 100);

                  }


                }, true);
              }
              return xhr;

            }
          }).done(function (data) {

          });
        }

        $(".attchment").on("ifUnchecked", function () {
          $('#iframepdf').attr('src', '');
          $('#iframepdf').hide();
          $('.highlight').removeClass('highlight');
        });

      });


      $(document).on('click', '.attchment', function () {
        var index = $("#salesinvoicegrid").jqGrid('getGridParam', 'selrow');
        var id = $("#salesinvoicegrid").jqGrid('getCell', index, 'invoice_hdr_id');
        var invoice_number = $("#salesinvoicegrid").jqGrid('getCell', index, 'invoice_number');
        var url = "{{ URL::to('salesinvoiceprint') }}/" + id + '?mails=mails';
        $.get(url, function (data) {
          $('#iframepdf').attr('src', "uploads/soinvoiceupload/SOINV_" + invoice_number + ".pdf");
          $('#iframepdf').show();
        });

      });


      $.ajax({
        url: "{{ URL::to('jcomboform') }}",
        type: "GET",
        data: {
          table: "a_lookuplines_t:lookup_code:lookup_code",
          parent: "lookup_type='LR_STATUS'",
          order_by: "lookup_code asc"
        },
        success: function (data) {
          // Ensure data is parsed
          if (typeof data === "string") {
            try {
              data = JSON.parse(data);
            } catch (e) {
              console.error("Invalid JSON response:", data);
              return;
            }
          }

          // Clear and populate dropdown
          var $dropdown = $(".lr_status");
          $dropdown.empty().append('<option value="">-- Select LR Status --</option>');

          $.each(data, function (i, item) {
            $dropdown.append(
              `<option value="${item.val}">${item.option_name}</option>`
            );
          });

          // Trigger change/select2 if needed
          $dropdown.trigger("change.select2");
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error:", error);
        }
      });


    });

    $(document).on("focus", ".delivery_date", function () {

      $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showAnim: "slideDown",
        yearRange: "-25:+0",

      });
    });

        $(document).on("focus", ".irn_date", function () {

      $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showAnim: "slideDown",
        yearRange: "-25:+0",

      });
    });

            $(document).on("focus", ".eway_date", function () {

      $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showAnim: "slideDown",
        yearRange: "-25:+0",

      });
    });
    
        $(document).on("focus", ".lr_date", function () {

      $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showAnim: "slideDown",
        yearRange: "-25:+0",

      });
    });

    $(document).on("focus", ".cheque_date", function () {

      $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: -365,
        maxDate: 90,
        dateFormat: "yy-mm-dd",
        showAnim: "slideDown",
        yearRange: "-25:+0",

      });
    });

    $(document).on("focus", ".cheque_received_date", function () {

      $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: -365,
        maxDate: 90,
        dateFormat: "yy-mm-dd",
        showAnim: "slideDown",
        yearRange: "-25:+0",

      });
    });

  </script>

@endpush