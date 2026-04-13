@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Dispatch</h3>
  @include('layouts.breadcrumb')
  <style>
    .select2-container--open {
      z-index: 200000 !important;
    }
  </style>
  <div id="toolbar-container" class="dispatched mb-3 mt-2"></div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="DispatchTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th><input type="checkbox" id="select-all"></th>
            <th>Actions</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>

            <th>Dispatch No</th>
            <th>Dispatch Date</th>
            <th>Dispatch Source</th>
            <th>Total Qty</th>
            <th>Customer Name</th>
            <th>Employee Name</th>
            <th>Reference No</th>
            <th>Lr No</th>
            <th>Dispatch Status</th>

          </tr>
          <tr class="table-info">
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
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>





  <!-- Popups -->

  <!-- Contact Modal -->
  <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content shadow-lg border-0 rounded-3" style="height: 500px;overflow-y: scroll;">

        <!-- Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="contactModalLabel">Contact</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Form -->
        <form method="POST" id="enquirymail" class="needs-validation" novalidate enctype="multipart/form-data">
          <div class="modal-body">

            <!-- Customer Info -->
            <div class="mb-3">
              <label class="form-label">Customer Name</label>
              <input type="text" name="customer_name" class="form-control customer_name" readonly>
              <input type="hidden" name="dispatch_hdr_id" class="dispatch_hdr_id">
            </div>

            <!-- Contacts Table -->
            <div class="table-responsive mb-3">
              <table class="table table-bordered align-middle">
                <thead class="table-light">
                  <tr>
                    <th></th>
                    <th>Contact Person</th>
                    <th>Contact Number</th>
                    <th>Contact Mail</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="mcontent2"></tbody>
              </table>
            </div>

            <!-- CC Input -->
            <div class="mb-3 row">
              <label for="cc" class="col-sm-2 col-form-label">CC</label>
              <div class="col-sm-10">
                <input type="text" name="cc[]" class="form-control cc">
              </div>
            </div>

            <!-- Message -->
            <div class="mb-3">
              <label class="form-label">Message</label>
              <textarea name="msg" class="form-control tinymce" rows="6">
        Thanks and Regards,
        Dispatch Department,
        JRKS,
        Kundrathur.
                    </textarea>
              <input type="hidden" name="hdr_id" class="hdr_id">
            </div>

            <!-- Attachments -->
            <div class="mb-3">
              <label class="form-label">Attachments</label>
              <input type="file" name="email_attachment[]" class="form-control email_attachment" multiple>
            </div>
            <div class="form-check mb-3">
              <input type="checkbox" class="form-check-input attchment" name="attchment">
              <label class="form-check-label">Attach PDF</label>
            </div>

            <!-- Preview -->
            <div class="preview mb-3"></div>

            <!-- PDF Preview -->
            <iframe id="iframepdf" class="w-100 border rounded d-none" style="height:400px;"></iframe>

          </div>

          <!-- Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success sendmail" id="sentmail_id">Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Edit Popup -->
  <div class="modal fade" id="Editpopup" tabindex="-1" aria-labelledby="EditpopupLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-3">

        <!-- Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="EditpopupLabel">Update Qty & Pack Weight</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">No of Box <span class="text-danger">*</span></label>
            <input type="text" name="packaging_qty" id="packaging_qty" class="form-control packaging_qty" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Pack Weight <span class="text-danger">*</span></label>
            <input type="text" name="pack_weight" id="pack_weight" class="form-control pack_weight" required>
            <input type="hidden" name="so_hdr_id" id="so_hdr_id" class="so_hdr_id">
          </div>

          <div class="mb-3">
            <label class="form-label">Freight Carrier <span class="text-danger">*</span></label>
            <select name="carrier_name" id="carrier_name" class="form-select select2 carrier_name" required>
              {!! $freight_carrier_id !!}
            </select>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-success edit_save px-4" id="edit_save">Save</button>
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>



@endsection
@push('scripts')

  <script>


     // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'convertinvoice')) {
        $('#toolbar-container').append(`
                <button class="btn btn-primary" text-white px-4 me-2">Convert to Invoice
                </button>
              `);
      }
    });

    // data table funcrion	
    $(document).ready(function () {

      var freightcarrier = "{{ $freightcarrier}}";
      var status = "<?php echo $dispatch_status; ?>"
      var invoice = "<?php echo $invoice; ?>"

      var table = $('#DispatchTbl').DataTable({
        processing: true,
        serverSide: true,
        order: [[6, 'desc']],
        ajax: "dispatchdata?status=" + status + "&invoice=" + invoice,
        columns: [

          {
                    data: 'so_dispatch_hdr_id',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function (data) {
                      return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                    }
                },

          {
            data: 'so_dispatch_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                  <button class="btn btn-sm btn-warning view-btn" data-id="${row.so_dispatch_hdr_id}">
                    <i class="bi bi-eye"></i>
                  </button>
              <button class="btn btn-sm btn-primary edit-btn" data-id="${row.so_dispatch_hdr_id}" data-status="${row.dispatch_status}">
                    <i class="bi bi-pencil"></i>
                  </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'email')) {
                buttons += `
                  <button class="btn btn-sm btn-success mail-btn" data-id="${row.so_dispatch_hdr_id}"
              data-cusid="${row.customer_id}"
              data-name="${row.customer_name}"
              data-status="${row.dispatch_status}">
                    <i class="bi bi-envelope"></i>
                  </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'print')) {
                buttons += `
                  <button class="btn btn-sm btn-secondary print-btn me-1" data-id="${row.so_dispatch_hdr_id}"
              data-cusid="${row.customer_id}"
              data-name="${row.customer_name}"
              data-status="${row.dispatch_status}">
                    <i class="bi bi-printer"></i>
                  </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'convertinvoice')) {
                buttons += `
                  <button class="btn btn-sm btn-secondary convert-btn" data-id="${row.so_dispatch_hdr_id}"
                      data-bs-toggle="tooltip" 
                      data-bs-placement="top" 
                      title="Convert To Invoice">
                <i class="bi bi-arrows-move"></i>
                  </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'packingslip')) {
                buttons += `
                  <button class="btn btn-sm btn-info packing-btn me-1" data-id="${row.so_dispatch_hdr_id}"
                      data-bs-toggle="tooltip" 
                      data-bs-placement="top" 
                      title="Packing Slip">
                <i class="bi bi-box-seam"></i>
                  </button>`;
              }

              <?php if ($pageMethod == "salesinvoicefromdispatch") { ?>
              buttons += ` <button type="button" class="btn btn-sm btn-info btn-replacement me-1" data-id="${row.so_dispatch_hdr_id}"
                    data-bs-toggle="tooltip" 
                      data-bs-placement="top" 
                      title="Create Replacement"><i class="bi bi-repeat"></i></button>`

              <?php } ?>

              <?php if ($pageMethod == "dispatch") { ?>

              buttons += `<button type="button" class="btn btn-sm btn-primary btn-appoinment me-1" 
                   data-bs-toggle="tooltip" 
                      data-bs-placement="top" 
                      title="Update CB/PW/Trans"
               data-id="${row.so_dispatch_hdr_id}"
                data-weight="${row.pack_weight}"
                data-qty="${row.packaging_qty}"
                data-status="${row.dispatch_status}"
                    data-carrier="${row.freight_carrier_id}"
                    data-lr_no="${row.lr_no}"><i class="bi bi-check2-circle"></i></button>`

              <?php } ?>

              return buttons;
            }
          },
          { data: 'pack_weight', name: 'pack_weight', visible: false },
          { data: 'packaging_qty', name: 'packaging_qty', visible: false },
          { data: 'freight_carrier_id', name: 'freight_carrier_id', visible: false },
          { data: 'customer_id', name: 'customer_id', visible: false },
          { data: 'dispatch_number', name: 'dispatch_number' },
          { data: 'dispatch_date', name: 'dispatch_date' },
          { data: 'dispatch_source', name: 'dispatch_source' },
          { data: 'total_qty', name: 'total_qty' },
          { data: 'customer_name', name: 'customer_name' },
          { data: 'first_name', name: 'first_name' },
          { data: 'sales_order_no', name: 'sales_order_no' },
          { data: 'lr_no', name: 'lr_no' },
          { data: 'dispatch_status', name: 'dispatch_status' }

        ]
      });

      // Individual column search
      $('#DispatchTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });

      $('#select-all').on('click', function () {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"].row-checkbox', rows).prop('checked', this.checked);
      });

    

    $('#DispatchTbl tbody').on('change', '.row-checkbox', function () {
        var checked = $(this).is(':checked');
        var currentRow = table.row($(this).closest('tr')).data();
        var currentCustomer = currentRow.customer_name;

        if (checked) {
          // Get all currently checked Customers
          var checkedCustomers = [];
          $('#DispatchTbl tbody input.row-checkbox:checked').each(function () {
            var rowData = table.row($(this).closest('tr')).data();
            if (rowData && rowData.customer_name) {
              checkedCustomers.push(rowData.customer_name);
            }
          });
            //console.log(rowData);
          // Check if all Customers are the same
          var uniqueCustomers = [...new Set(checkedCustomers)];
          if (uniqueCustomers.length > 1) {
            // Different Customers selected - prevent checking
            showCustomAlert("Can't select rows from different Customers!", "error");
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

    // email 	

    $(document).on('click', '.mail-btn', function () {


      const id = $(this).data('id');
      const status = $(this).data('status');
      const sup_name = $(this).data('name');
      const sup_id = $(this).data('cusid');

      if (status != "INITIATED" && status != "CANCELLED" && status != "DRAFT") {

        $('.mcontent2').html('');
        $('.customer_name').val(sup_name);
        $('.dispatch_hdr_id').val(id);
        var url_print = '{{URL::to("customermaildetails")}}/' + sup_id;
        $.get(url_print, function (data) {
          if (data == "") {
            showCustomAlert("No Email Contact For Current Customer...Please Add Email First...", "info");
          }
          else {

            $.each(data, function (key) {

              $('.mcontent2').append('<tr class="cont_row">\n\
                            <td><input type="checkbox" name="check_mail" class="check_mail mail_name"  value="' + data[key][3] + '" data-id="' + data[key].supplier_site_id + '" data-value="' + data[key].supplier_site_id + '" required></td>\n\
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
                  showCustomAlert("Please Check Any Email Contact First...", "error");
                  $('.cont_row input').removeAttr('disabled');

                }

              });

            });

          }

        });

        $('#contactModal').modal('show');
      }
      else {
        showCustomAlert("Please Select Approved Only", "error");
      }


    });



    $('#contactModal').on('shown.bs.modal', function () {

      var table = $('#DispatchTbl').DataTable();
      var tr = $(this).closest('tr');
      var rowData = table.row(tr).data();

      var id = rowData.po_hdr_id;


      $('.sendmail').click(function () {

        var table = $('#DispatchTbl').DataTable();
        var tr = $(this).closest('tr');
        var rowData = table.row(tr).data();

        var id = rowData.so_dispatch_hdr_id;
        var suid = rowData.customer_id;
        var check = $('.mail_name').is(":checked");
        var att_check = $(".attchment").is(":checked");
        var mail = [];
        $(':checkbox:checked').each(function (i) {
          mail[i] = $(this).val();
        });
        var mail1 = mail.filter(function (v) { return v !== '' });

        var cc = $('.cc').val();
        var msg = $('.msg').val();
        if (check == true) {
          if (att_check == true) {
            var url = "{{ URL::to('dispatchprint') }}/" + id + '?mail=' + mail1 + '&cc=' + cc + '&msg=' + msg;
            $.get(url, function (data) {
              showCustomAlert("Mail Send Successfully", "success");
              location.reload();

            });
            filesave();
          } else {
            showCustomAlert("Please Check Attach Pdf", "error");
          }
        }
        else {
          showCustomAlert("Please Check Contact", "error");
        }
      });

      function filesave() {
        var form_data = new FormData(document.getElementById('enquirymail'));
        $.ajax({
          url: "{{URL::to('dispatchfilesave')}}",
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

    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const status = $(this).data('status');
      var return1 = "{{$pageMethod}}";

      if (status == "DRAFT") {
        window.location.replace('dispatchcreate/' + id);
      } else {
        showCustomAlert("Please Select Draft record", "error");
      }

    });


    // replacement
    $(document).on('click', '.btn-replacement', function () {

      const id = $(this).data('id');

      window.location.replace("{{URL::to('salesreplacementfromindispatch')}}/" + id + "?pageMethod=salesinvoicefromdispatch");

    });



    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      var return1 = "{{$pageMethod}}";

      var url = "dispatchview";
      var editUrl = url + '/' + id + '/show';
      window.location.replace('dispatchview/' + id + '?return=' + return1);

    });


    //convert function
    $(document).on('click', '.convert-btn', function () {

      const id = $(this).data('id');
      const status = $(this).data('status');

      if (status != "INVOICE") {
        window.location.replace('invoicefromdispatch/' + id);
      } else {
        showCustomAlert("Dispatch Already converted to Invoice", "error");
      }

    });


    $(".dispatched").click(function () {
      var table = $("#DispatchTbl").DataTable();
      var ids = [];
      var poIds = [];

      // Loop through all checked checkboxes
      $("#DispatchTbl tbody input.row-checkbox:checked").each(function () {
        var rowData = table.row($(this).closest("tr")).data();

        if (rowData) {
          if (rowData.so_dispatch_hdr_id) ids.push(rowData.so_dispatch_hdr_id);
          
        }
      });
      
      // If at least one row is selected
      if (ids.length > 0) {
        window.location.replace(
        "invoicefromdispatch/" + ids.join(',')
      );
      } else {
       showCustomAlert("Please Select a Row", "info");
      }
    });

    // print
    $(document).on('click', '.print-btn', function () {

      const id = $(this).data('id');

      window.open('dispatchprint/' + id, '_blank');

    });


    //packing

    $(document).on('click', '.packing-btn', function () {

      const id = $(this).data('id');
      var url = "packingslip";
      var editUrl = url + '/' + id;
      window.open(editUrl, '_blank');
    });


    // update cb
    $(document).on('click', '.btn-appoinment', function () {


      var status = $(this).data('status');
      var pack_wt = $(this).data('weight');
      var box_qty = $(this).data('qty');
      var hdr_id = $(this).data('id');
      var lr_no = $(this).data('lr_no');
      var carrier_name = $(this).data('carrier');


      if (status != "DRAFT" && lr_no == '') {
        $("#Editpopup").modal('show');
        $('.pack_weight').val(pack_wt);
        $('.packaging_qty').val(box_qty);
        $('.dispatch_status').val(status);
        $('.so_hdr_id').val(hdr_id);
        $('#carrier_name').val(carrier_name).trigger('change');

      } else {
        showCustomAlert("Please Select Dispatched record", "info");
      }

    });



    // update save

    $(document).on('click', '.edit_save', function () {

      var red_url = "{{ url('dispatch') }}";
      var hdr_id = $(".so_hdr_id").val();
      var pack_wt = $(".pack_weight").val();
      var box_qty = $(".packaging_qty").val();
      var carrier_name = $(".carrier_name").val();


      $.get("dispatchstatussave?hdr_id=" + hdr_id + "&pack_wt=" + pack_wt + "&box_qty=" + box_qty + "&carrier_name=" + carrier_name, function (data) {

        if ($.trim(data) == '1') {
          showCustomAlert("Dispatch Pack weight & Box qty updated Successfully", "success");
          setTimeout(function () {
            window.location.href = red_url;
          }, 1500);
          $("#grid1")[0].triggerToolbar();

        } else {
          showCustomAlert('Please Try Again', 'error');
          $("#grid1")[0].triggerToolbar();
        }
      });
      $("#Editpopup").modal('hide');

    });



    $(document).on('click', '.attchment', function () {
      var index = $("#dispatchdata").jqGrid('getGridParam', 'selrow');
      var id = $("#dispatchdata").jqGrid('getCell', index, 'so_dispatch_hdr_id');
      var dispatch_number = $("#dispatchdata").jqGrid('getCell', index, 'dispatch_number');
      var url = "{{ URL::to('dispatchprint') }}/" + id + '?mails=mails';
      $.get(url, function (data) {
        $('#iframepdf').attr('src', "uploads/dispatch/D_" + dispatch_number + ".pdf");
        $('#iframepdf').show();
      });

    });

    $('.selfdispatch').click(function () {
      var status = "selfdispatch";
      window.location.replace('dispatchcreate/0?status=' + status);
    });


    $('.soorder').click(function () {
      var url = "{{ URL::to('dispatchfrmso')}}";
      window.location.replace(url);

    });
    $('#pickorder').click(function () {

      var url = "{{ URL::to('dispatchfrmpickorder')}}";
      window.location.replace(url);

    });
    $('#invoice').click(function () {
      //alert();
      var url = "{{ URL::to('dispatchfrminvoice')}}";
      window.location.replace(url);

    });


  </script>

@endpush