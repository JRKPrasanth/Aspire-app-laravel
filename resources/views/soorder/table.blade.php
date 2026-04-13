@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Sales Order</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="mb-3 mt-2"></div>
  

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="SalesTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th><input type="checkbox" id="select-all"></th>
            <th></th>
            <th>Actions</th>
            <th></th>
            <th>Sales Order No</th>
            <th>SO Date</th>
            <th>Customer Name</th>
            <th>Employee Name</th>
            <th>Order Amount</th>
            <th>Order Type</th>
            <th>Order Status</th>
            <th>Created By</th>
            <th>Approved By</th>
            <th>Proforma Inv</th>

          </tr>
          <tr class="table-info">
            <th></th>
            <th></th>
            <th></th>
            <th></th>

            <th data-name="sales_order_no"><input class="form-control form-control-sm column-search"></th>
            <th data-name="sales_order_date"><input class="form-control form-control-sm column-search"></th>
            <th data-name="customer_name"><input class="form-control form-control-sm column-search"></th>
            <th data-name="first_name"><input class="form-control form-control-sm column-search"></th>
            <th data-name="order_total"><input class="form-control form-control-sm column-search"></th>
            <th data-name="order_type_id"><input class="form-control form-control-sm column-search"></th>
            <th data-name="order_status_id"><input class="form-control form-control-sm column-search"></th>
            <th data-name="created_by"><input class="form-control form-control-sm column-search"></th>
            <th data-name="last_updated_by"><input class="form-control form-control-sm column-search"></th>
            <th data-name="proforma_invoice"><input class="form-control form-control-sm column-search"></th>
          </tr>


        </thead>
  <tfoot>
    <tr class="table-info fw-bold">
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
    </tr>
    <tr class="table-success fw-bold">
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
    </tr>
    </tfoot>   
        <tbody>
        </tbody>
      </table>
    </div>
  </div>



  <!-- Closing Order Confirmation -->
  <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4 border-0">
        <div class="modal-header bg-danger bg-gradient text-white rounded-top-4">
          <h5 class="modal-title">
            <i class="bi bi-exclamation-triangle me-2"></i> Closing Order Confirmation
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <h5>Do you want to close this order?</h5>
        </div>
        <div class="modal-footer">
          <button type="button" id="yes" class="btn btn-success px-4" value="yes">Yes</button>
          <button type="button" id="cancel" class="btn btn-secondary px-4" data-bs-dismiss="modal">No</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Upload Documents Modal -->
  <div class="modal fade" id="upload_Modal_id" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4 border-0">
        <div class="modal-header bg-primary bg-gradient text-white rounded-top-4">
          <h5 class="modal-title">
            <?php if ($status_type != 'INITIATED') { ?>
            <i class="bi bi-upload me-2"></i> Add Documents
            <?php } else { ?>
            <i class="bi bi-download me-2"></i> Download Documents
            <?php } ?>
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <form method="post" action="{{URL::to('salesorderupload')}}" id="add_file_name" enctype="multipart/form-data">
            {{csrf_field()}}

            <div class="mb-3">
              <?php if ($status_type != 'INITIATED') { ?>
              <label for="choosefile" class="form-label fw-bold">Documents</label>
              <input id="choosefile" name="choosefile[]" type="file" class="form-control" multiple required>
              <?php } ?>
            </div>

            <table class="table table-bordered table-hover align-middle text-center">
              <thead class="table-light">
                <tr>
                  <th style="width:10%">S.No</th>
                  <th style="width:60%">File</th>
                  <th style="width:10%">Action</th>
                </tr>
              </thead>
              <tbody class="sales_body"></tbody>
            </table>

            <div class="text-center my-3">
              <?php if ($status_type != 'INITIATED') { ?>
              <button type="submit" id="file_save_id" class="btn btn-primary px-4">Save</button>
              <?php } ?>
              <input type="hidden" name="salesorder_id" class="salesorder_id">
            </div>

            <div class="text-center">
              <iframe src="" id="p_pvw" class="border rounded d-none" frameborder="0" width="400" height="600"></iframe>
              <input type="hidden" name="doc_name" id="doc_name" class="doc_name" readonly>
              <button type="button" id="download_btn" class="btn btn-outline-secondary mt-2 d-none">
                <i class="bi bi-download"></i> Download
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- View Documents Modal -->
  <div class="modal fade" id="view_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4 border-0">
        <div class="modal-header bg-info bg-gradient text-white rounded-top-4">
          <h5 class="modal-title"><i class="bi bi-file-earmark-text me-2"></i> Download Documents</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered table-hover text-center">
            <thead class="table-light">
              <tr>
                <th style="width:10%">S.No</th>
                <th style="width:60%">File</th>
                <th style="width:10%">Action</th>
              </tr>
            </thead>
            <tbody class="sales_view"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Contact Modal -->
  <div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content" style="height: 550px;overflow-y: auto;">
        <div class="modal-header bg-success bg-gradient text-white rounded-top-4">
          <h5 class="modal-title"><i class="bi bi-person-lines-fill me-2"></i> Contact</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <form method="POST" action="" id="somail" class="needs-validation" enctype="multipart/form-data" novalidate>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label fw-bold">Customer Name</label>
              <input type="text" name="customer_name" class="form-control customer_name" readonly>
              <input type="hidden" name="sohdrid" class="sohdrid">
            </div>

            <div class="table-responsive mb-3">
              <table class="table table-bordered text-center">
                <thead class="table-light">
                  <tr>
                    <th>Contact Person</th>
                    <th>Contact Number</th>
                    <th>Contact Mail</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="mcontent2"></tbody>
              </table>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">CC</label>
              <input type="text" name="cc[]" class="form-control cc">
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Message</label>
              <textarea name="msg" class="form-control msg tinymce" rows="6">
      Thanks and Regards,
      Dispatch Department,
      JRKS,
      Kundrathur.
                  </textarea>
              <input type="hidden" name="hdr_id" class="msg hdr_id">
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Attachments</label>
              <input type="file" name="email_attachment[]" class="form-control email_attachment" multiple>
            </div>

            <div class="form-check mb-3">
              <input type="checkbox" name="attchment" class="form-check-input attchment" id="attachPdf">
              <label for="attachPdf" class="form-check-label">Attach PDF</label>
            </div>

            <iframe id="iframepdf" class="w-100 border rounded d-none" height="400"></iframe>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
            <button type="submit" id="sentmail_id" class="btn btn-success px-4">
              <i class="bi bi-envelope-fill me-1"></i> Send
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>


@endsection
@push('scripts')

  <script>


    // data table funcrion	
$(document).ready(function () {

  var opt = "{{$opt}}";

  <?php if ($pageMethod == "invoicefromorder" || $pageMethod == "dispatchfrmso") { ?>
    var sele = true;
  <?php } else { ?>
    var sele = false;
  <?php } ?>

  var table = $('#SalesTbl').DataTable({
    processing: true,
    serverSide: false,
    scrollX: true,
    scrollY: "50vh",
    orderCellsTop: true,
    order: [[0, 'desc']],
    ajax: "soordergriddata?status={{$status_type}}&pagemethod={{ $pageMethod }}",

    columns: [
              {
                data: 'sales_hdr_id',
                visible: false,   
                searchable: false
              },
      {
        data: 'sales_hdr_id',
        orderable: false,
        searchable: false,
        className: 'text-center',
        render: function (data) {
          return `<input type="checkbox" class="row-checkbox" value="${data}">`;
        }
      },

      {
        data: 'sales_hdr_id',
        name: 'actions',
        searchable: false,
        className: 'text-center',
        render: function (data, type, row) {
          let buttons = '';

          if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
            buttons += `
              <button class="btn btn-sm btn-warning view-btn me-1" data-id="${row.sales_hdr_id}">
                <i class="bi bi-eye"></i>
              </button>`;
          }

          if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
            buttons += `
              <button class="btn btn-sm btn-primary edit-btn" data-id="${row.sales_hdr_id}"
                      data-status="${row.order_status_id}" data-type="${row.order_type_id}">
                <i class="bi bi-pencil"></i>
              </button>`;
          }

          if (window.toolbarButtons?.some(btn => btn.attr.id === 'mail')) {
            buttons += `
              <button class="btn btn-sm btn-success mail-btn" data-id="${row.sales_hdr_id}"
                      data-status="${row.order_status_id}" data-name="${row.customer_name}"
                      data-cusid="${row.ship_to_customer_id}">
                <i class="bi bi-envelope"></i>
              </button>`;
          }

          if (window.toolbarButtons?.some(btn => btn.attr.id === 'print')) {
            buttons += `
              <button class="btn btn-sm btn-secondary print-btn" data-id="${row.sales_hdr_id}">
                <i class="bi bi-printer"></i>
              </button>`;
          }

          if (window.toolbarButtons?.some(btn => btn.attr.id === 'dispatch')) {
            buttons += `
              <button class="btn btn-sm btn-success dispatch-btn" data-id="${row.sales_hdr_id}"
                      data-bs-toggle="tooltip" data-bs-placement="top" title="Dispatch">
                <i class="bi bi-truck"></i>
              </button>`;
          }

          if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
            buttons += `
              <button class="btn btn-sm btn-danger delete-btn" data-id="${row.sales_hdr_id}">
                <i class="bi bi-trash"></i>
              </button>`;
          }

          if (window.toolbarButtons?.some(btn => btn.attr.id === 'copysoorder')) {
            buttons += `
              <button class="btn btn-sm btn-primary copy-btn" data-id="${row.sales_hdr_id}"
                      data-type="${row.order_type_id}">
                Copy
              </button>`;
          }

          if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
            buttons += `
              <button class="btn btn-sm btn-success approve-btn me-1" data-id="${row.sales_hdr_id}"
                      data-type="${row.order_type_id}"
                      data-bs-toggle="tooltip" data-bs-placement="top" title="Approve">
                <i class="bi bi-check2-circle"></i>
              </button>`;
          }

          if (window.toolbarButtons?.some(btn => btn.attr.id === 'cancel')) {
            buttons += `
              <button class="btn btn-sm btn-danger cancel-btn me-1" data-id="${row.sales_hdr_id}"
                      data-type="${row.order_type_id}" data-status="${row.order_status_id}">
                Cancel
              </button>`;
          }

          if (window.toolbarButtons?.some(btn => btn.attr.id === 'convert1')) {
            buttons += `
              <button type="button" class="btn btn-sm btn-success convert-btn" data-id="${row.sales_hdr_id}"
                      data-bs-toggle="tooltip" data-bs-placement="top" title="Convert To Invoice">
                <i class="bi bi-input-cursor"></i>
              </button>`;
          }

          <?php if ($pageMethod == "soorder") { ?>
            buttons += `
              <button class="btn btn-sm btn-info close-btn me-1" data-id="${row.sales_hdr_id}"
                      data-status="${row.order_status_id}"
                      data-bs-toggle="tooltip" data-bs-placement="top" title="Close Order">
                <i class="bi bi-x"></i>
              </button>`;
          <?php } ?>

          <?php if ($pageMethod == "soorder" || $pageMethod == "salesorderapproval") { ?>
            buttons += `
              <button class="btn btn-sm btn-primary proforma-btn" data-id="${row.sales_hdr_id}"
                      data-invoice="${row.proforma_invoice}" data-status="${row.order_status_id}"
                      data-type="${row.order_type_id}"
                      data-bs-toggle="tooltip" data-bs-placement="top" title="Proforma Invoice">
                <i class="bi bi-building-add"></i>
              </button>`;
          <?php } ?>

          return buttons;
        }
      },

      { data: 'ship_to_customer_id', name: 'ship_to_customer_id', visible: false },
      { data: 'sales_order_no', name: 'sales_order_no' },
      { data: 'sales_order_date', name: 'sales_order_date' },
      { data: 'customer_name', name: 'customer_name' },
      { data: 'first_name', name: 'first_name' },
      { data: 'order_total', name: 'order_total' },
      { data: 'order_type_id', name: 'order_type_id' },
      { data: 'order_status_id', name: 'order_status_id' },
      { data: 'created_by', name: 'created_by' },
      { data: 'last_updated_by', name: 'last_updated_by' },
      { data: 'proforma_invoice', name: 'proforma_invoice' }
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

      // order_total column index = 8 (based on your columns array)
      let totalColIndex = 8;

      // PAGE TOTAL
      let pageTotal = api.column(totalColIndex, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

      // GRAND TOTAL
      let grandTotal = api.column(totalColIndex).data()
        .reduce((a, b) => num(a) + num(b), 0);

      // Assuming tfoot has 2 rows
      let $tfoot = $(api.table().footer()).closest('tfoot');
      $tfoot.find('tr:eq(0) th:eq(' + totalColIndex + ')').html(pageTotal.toFixed(2));
      $tfoot.find('tr:eq(1) th:eq(' + totalColIndex + ')').html(grandTotal.toFixed(2));
    },

        initComplete: function () {
          var api = this.api();

          // get the real visible header inside the scroll container
          var $scrollHead = $(api.table().container())
            .find('.dataTables_scrollHead thead');
$scrollHead.find('input.column-search').on('keyup change clear', function () {
  let colName = $(this).closest('th').data('name');
  if (!colName) return; // th without data-name

  // example: skip date if you handle it differently
  if (colName === 'sales_order_date') return;

  api.column(colName + ':name').search(this.value).draw();
});

       }
  });
  

  // ✅ OUTSIDE DataTable init config (this was breaking your code)
  $('#select-all').on('click', function () {
    var rows = table.rows({ search: 'applied' }).nodes();
    $('input[type="checkbox"].row-checkbox', rows).prop('checked', this.checked);
  });

  $('#SalesTbl tbody').on('change', '.row-checkbox', function () {
    var checked = $(this).is(':checked');

    if (checked) {
      // Get all currently checked Customers
      var checkedCustomers = [];
      $('#SalesTbl tbody input.row-checkbox:checked').each(function () {
        var rowData = table.row($(this).closest('tr')).data();
        if (rowData && rowData.customer_name) {
          checkedCustomers.push(rowData.customer_name);
        }
      });

      var uniqueCustomers = [...new Set(checkedCustomers)];
      if (uniqueCustomers.length > 1) {
        showCustomAlert("Can't select rows from different Customers!", "error");
        $(this).prop('checked', false);
        return;
      }
    } else {
      var el = $('#select-all').get(0);
      if (el && el.checked && ('indeterminate' in el)) {
        el.indeterminate = true;
      }
    }
  });

});



    // button purpose
    // Add create button purpose
    $(document).ready(function () {

      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_standard')) {
        $('#toolbar-container').append(`
                  <button class="btn btn-success create_standard me-2">Create Standard
                     <i class="bi bi-plus-circle"></i> 
                  </button>
                `);
      }

      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_labour')) {
        $('#toolbar-container').append(`
                  <button class="btn btn-info create_labour me-2">Create Labour
                     <i class="bi bi-plus-circle"></i> 
                  </button>
                `);
      }


      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_export')) {
        $('#toolbar-container').append(`
                  <button class="btn btn-warning create_export me-2">Create Export
                     <i class="bi bi-plus-circle"></i> 
                  </button>
                `);
      }

      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_sample')) {
        $('#toolbar-container').append(`
                  <button class="btn btn-primary create_sample me-2">Create Sample
                     <i class="bi bi-plus-circle"></i> 
                  </button>
                `);
      }

      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_export_sample')) {
        $('#toolbar-container').append(`
                  <button class="btn btn-danger create_export_sample me-2">Create Export Sample
                     <i class="bi bi-plus-circle"></i> 
                  </button>
                `);
      }

            if (window.toolbarButtons?.some(btn => btn.attr.id === 'dispatch')) {
            $('#toolbar-container').append(`
                <button class="btn btn-primary dispatched" text-white px-4 me-2">Dispatch
                </button>
              `);
      }

    });


    //edit function
    $(document).on('click', '.edit-btn', function () {

      const id = $(this).data('id');
      const status = $(this).data('status');
      const type = $(this).data('type');

      if (status != "APPROVED" && status != "INITIATED" && status != "CANCELLED" && status != "CLOSED" && status != "COMPLETED") {

        window.location.replace('soordercreate/' + id + "/" + type);
      } else {

        showCustomAlert("This Sales Order Cannot Be Edit!!!", "info");

      }

    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      var pagemethod = "{{ $pageMethod }}";

      if (pagemethod == "dispatchfrmso") {
        var url = 'dispatchsoorderview/' + id + '?return=' + pagemethod;
      } else {
        var url = 'soorderview/' + id + '?return=' + pagemethod;
      }
      window.location.replace(url);

    });


    //create button

    $(document).on('click', '.create_standard', function () {
      var enqtype = "STANDARD";
      var url = "{{ url('soordercreate') }}/0/" + enqtype;
      window.location.replace(url);
    });

    $(document).on('click', '.create_labour', function () {
      var enqtype = "LABOUR";
      var url = "{{ url('soordercreate') }}/0/" + enqtype;
      window.location.replace(url);
    });


    $(document).on('click', '.create_export', function () {
      var enqtype = "EXPORT";
      var url = "{{ url('soordercreate') }}/0/" + enqtype;
      window.location.replace(url);
    });

    $(document).on('click', '.create_sample', function () {
      var enqtype = "SAMPLE";
      var url = "{{ url('soordercreate') }}/0/" + enqtype;
      window.location.replace(url);
    });

    $(document).on('click', '.create_export_sample', function () {
      var enqtype = "EXPORT SAMPLE";
      var url = "{{ url('soordercreate') }}/0/" + enqtype;
      window.location.replace(url);
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
          url: "{{ url('soorderdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {

            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Deleted successfully!', 'success');
            $('#SalesTbl').DataTable().ajax.reload();

            if (data == '1') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert("You Can't delete , Sales Order Used in SomeWhere.", 'error');
              $('#SalesTbl').DataTable().ajax.reload();
            } else {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Deleted successfully!', 'success');
              $('#SalesTbl').DataTable().ajax.reload();
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


    // mail
    $(document).on('click', '.mail-btn', function () {

      const id = $(this).data('id');
      const order_status = $(this).data('status');
      const sup_name = $(this).data('name');
      const cusid = $(this).data('cusid');


      if (order_status != "INITIATED" && order_status != "CANCELLED" && order_status != "DRAFT") {

        $('.mcontent2').html('');
        $('.customer_name').val(sup_name);
        $('.sohdrid').val(id);
        var url_print = '{{URL::to("customermaildetails")}}/' + cusid;
        $.get(url_print, function (data) {
          if (data == "") {
            showCustomAlert("No Email Contact For Current Customer...Please Add Email First...", "info");
          }
          else {

            $.each(data, function (key) {

              $('.mcontent2').append('<tr class="cont_row">\n\
                          <td><input type="checkbox" name="check_mail" class="check_mail mail_name"  value="' + data[key][3] + '" data-id="' + data[key].customer_site_id + '" data-value="' + data[key].customer_site_id + '" required></td>\n\
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
                  showCustomAlert("Please Check Any Email Contact First...", "info");
                  $('.cont_row input').removeAttr('disabled');

                }

              });

            });

          }

        });

        $('#contactModal').modal('show');
      } else {
        showCustomAlert("Please Select Approved Only", "info");
      }

    });


    // print

    $(document).on('click', '.print-btn', function () {

      const id = $(this).data('id');

      var url = "soorderprint";
      var print_url = url + '/' + id;
      window.open(print_url, '_blank');

    });


    //  close order

    $(document).on('click', '.close-btn', function () {

      const id = $(this).data('id');
      const order_status = $(this).data('status');

      if (order_status != "COMPLETED" && order_status != "CANCELLED" && order_status != "DRAFT") {

        $('#myModal').modal('show');
        $('#yes').click(function () {
          var val = $(this).val();
          if (val == 'yes') {
            $.get("{{URL::to('soorderupdatestatus')}}/" + id, function (data) {
              showCustomAlert("Sales Order Closed Successfully", "success");
              location.reload();

            });
          }
        });
      } else {
        showCustomAlert("Please select Initiated/Approved Records", "info");
      }

    });


    // proformo invoice
    $(document).on('click', '.proforma-btn', function () {


      const id = $(this).data('id');
      const order_status = $(this).data('status');
      const order_type_id = $(this).data('type');
      const proforma_invoice = $(this).data('invoice');


      if (order_status != "COMPLETED" && order_status != "CANCELLED") {

        if (proforma_invoice == 'YES' && order_type_id == 'STANDARD') {
          var s_url = "proformastdprint";
          var std_print_url = s_url + '/' + id;
          window.open(std_print_url, '_blank');
        } else if (proforma_invoice == 'YES' && (order_type_id == 'EXPORT' || order_type_id == 'EXPORT SAMPLE')) {
          var e_url = "proformaexpprint";
          var exp_print_url = e_url + '/' + id;
          window.open(exp_print_url, '_blank');
        } else {
          showCustomAlert("Please select Proforma enabled Orders!", "info");
        }
      } else {
        showCustomAlert("Please select Initiated/Approved Records", "info");
      }

    });


    // copy order

    $(document).on('click', '.copy-btn', function () {

      const id = $(this).data('id');
      const ordertype = "COPYSO";

      window.location.replace('soordercreate/' + id + "/" + ordertype);

    });

    // approve
    $(document).on('click', '.approve-btn', function () {

      const id = $(this).data('id');

      window.location.replace('soorderapproved/' + id);

    });


    // cancel so
    $(document).on('click', '.cancel-btn', function () {

      const id = $(this).data('id');
      const ordertype = $(this).data('type');
      const status = $(this).data('status');


      if (status != "Approved" && status != "Rejected") {

        window.location.replace('socancellation/' + id + '/' + ordertype);
      } else {

        showCustomAlert("Already" + status, 'warning');
      }


    });

    // dispatch

    $(document).on('click', '.dispatch-btn', function () {

      var id = $(this).data('id');

      var url = "{{url('soorderqtycheck')}}/" + id + "?status=dispacth";
      $.get(url, function (data) {
        var data = $.trim(data);
        if (data == 0) {
          showCustomAlert("Sale order Qty is fully dispatched", "success");
        } else if (data == "order") {
          showCustomAlert("Please select same discount product order", "info");
          $("#grid1")[0].triggerToolbar();
        } else {
          window.location.replace('dispatchcreate/' + id + '?status=SALESORDER');
        }
      });

    });

    // convert function

    $(document).on('click', '.convert-btn', function () {

      const id = $(this).data('id');
      console.log(id);
      var url = "{{url('soorderqtycheck')}}/" + id + "?status=invoice";
      $.get(url, function (data) {
        if (data == 0) {
          showCustomAlert("Sale order Qty is fully invoiced", "info");
        } else if (data == "order") {
          showCustomAlert("Please select same discount product order", "info");

        } else {

          var status = 'invoicefromorder';
          window.location.replace('createinvoicefromorder/' + id + '?status=' + status);

        }
      });
    });




    $(document).ready(function () {


      /*deepika purpose:convert salesorder to workorder*/
      $(".workorder").click(function () {

        var gr = jQuery("#grid1").jqGrid('getGridParam', 'selrow');
        var cellValue = jQuery("#grid1").jqGrid('getCell', gr, 'sales_hdr_id');
        var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
          cellvalues = [];
        var j = 0;
        for (i = 0, n = selIds.length; i < n; i++) {
          var v = $grid.jqGrid("getCell", selIds[i], "sales_hdr_id");
          console.log(v);
          if (v != false)
            cellvalues.push(v);
        }
        if (gr) {
          window.location.replace('workordercreate/' + cellvalues + '?source=SALESORDER');
        }
        else {
          notyMsg('info', "Please Select Row");
        }

      });
      /*end*/


      $('#contactModal').on('shown.bs.modal', function () {

        var index = $("#grid1").jqGrid('getGridParam', 'selrow');

        $('.sendmail').click(function () {
          var id = $("#grid1").jqGrid('getCell', index, 'sales_hdr_id');
          var suid = $("#grid1").jqGrid('getCell', index, 'ship_to_customer_id');
          var check = $('.mail_name').is(":checked");
          var att_check = $(".attchment").is(":checked");
          // var mail=$("input[name='check_mail']:checked").val();
          var mail = [];
          $(':checkbox:checked').each(function (i) {
            mail[i] = $(this).val();
          });
          var mail1 = mail.filter(function (v) { return v !== '' });
          console.log(mail1);
          var cc = $('.cc').val();
          var msg = $('.msg').val();
          if (check == true) {
            if (att_check == true) {
              var url = "{{ URL::to('soorderprint') }}/" + id + '?mail=' + mail1 + '&cc=' + cc + '&msg=' + msg;
              $.get(url, function (data) {
                notyMsg('info', "Mail Send Successfully");
                location.reload();

              });
              filesave();
            } else {
              notyMsg('info', "Please Check Attach Pdf");
            }
          }
          else {
            notyMsg('info', "Please Check Contact");
          }
        });
        function filesave() {
          var form_data = new FormData(document.getElementById('somail'));
          $.ajax({
            url: "{{URL::to('sofilesave')}}",
            type: "POST",
            data: form_data,
            enctype: 'multipart/form-data',
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
        var index = $("#grid1").jqGrid('getGridParam', 'selrow');
        var id = $("#grid1").jqGrid('getCell', index, 'sales_hdr_id');
        var sales_order_no = $("#grid1").jqGrid('getCell', index, 'sales_order_no');
        var url = "{{ URL::to('soorderprint') }}/" + id + '?mails=mails';
        $.get(url, function (data) {
          $('#iframepdf').attr('src', "Uploads/salesorderupload/SO_" + sales_order_no + ".pdf");
          $('#iframepdf').show();
        });

      });


      $(".attach").click(function () {
        var id = $('#grid1').jqGrid('getGridParam', 'selrow');
        var cellvalue = $('#grid1').jqGrid('getCell', id, 'sales_hdr_id');
        $('.salesid').val(cellvalue);
        $('.so_order_table').hide();
        if (id) {
          var url = "{{URL::to('salesorderuploaddata')}}/" + cellvalue;
          $.getJSON(url, function (data) {
            console.log(data[0]);
            if (data) {
              $('.so_order_table').show();
              var html = "";
              $.each(data, function (index, value) {
                var i = index + 1;

                html += "<tr class='rcopy clone clonedInput'><td></td><td>" + i + "</td><td>" + value + "</td><td><input type='hidden' name='file[]' value='" + value + "'></td><td><a class='remove'><i class='fa fa-trash' aria-hidden='true'></i></a></td></tr>";



              });
              $('.sales_body').html(html);
            }

          });
          $(document).on('click', '.remove', function () {
            $(this).closest('tr').remove();
          });



          $('.upload_Modal_id').modal('show');
        } else {
          notyMsg('info', "Please Select Row");
        }

      });

      $(".attach_view").click(function () {
        var id = $('#grid1').jqGrid('getGridParam', 'selrow');
        var cellvalue = $('#grid1').jqGrid('getCell', id, 'sales_hdr_id');
        $('.salesid').val(cellvalue);
        $('.so_order_table').hide();
        if (id) {
          var url = "{{URL::to('salesorderuploaddata')}}/" + cellvalue;
          $.getJSON(url, function (data) {
            console.log(data[0]);
            if (data) {
              $('.so_order_table').show();
              var html = "";
              $.each(data, function (index, value) {
                var i = index + 1;

                html += "<tr class=''><td></td><td>" + i + "</td><td>" + value + "</td><td><input type='hidden' class='download' name='file[]' value='" + value + "'></td><td><a href='../Uploads/salesorderupload/SO" + cellvalue + "/" + value + "'  download><i class='fa fa-download downloadss' aria-hidden='true' ></i></a></td></tr>";
              });


              $('.sales_body').html(html);
            }

          });

          $(document).on('click', '.remove', function () {
            $(this).closest('tr').remove();
          });



          $('.upload_Modal_id').modal('show');
        } else {
          notyMsg('info', "Please Select Row");
        }

      });

      $(document).on('click', '.downloads', function () {
        var id = $('#grid1').jqGrid('getGridParam', 'selrow');
        var cellvalue = $('#grid1').jqGrid('getCell', id, 'sales_hdr_id');
        var name = $(this).data('value');
        var url = "{{URL::to('salesorderdownload')}}/" + name + "/" + cellvalue;
        $.getJSON(url, function (data) {

        });

      });

    });

     $(document).on('click', '.dispatched', function () {

      var table = $("#SalesTbl").DataTable();
      var ids = [];
      var poIds = [];

      // Loop through all checked checkboxes
      $("#SalesTbl tbody input.row-checkbox:checked").each(function () {
        var rowData = table.row($(this).closest("tr")).data();

        if (rowData) {
          if (rowData.sales_hdr_id) ids.push(rowData.sales_hdr_id);
          
        }
      });
      
      // If at least one row is selected
      if (ids.length > 0) {
        window.location.replace(
        "dispatchcreate/" + ids.join(',') + '?status=SALESORDER'
      );
      } else {
       showCustomAlert("Please Select a Row", "info");
      }
    });

  </script>

@endpush