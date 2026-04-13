@extends('layouts.header')
@section('content')
<h3 class="text-danger"> Purchase Enquiry</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="mb-3 mt-2"></div>



<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="PurchaseTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Enq Id</th>
            <th>Supplier Id</th>
            <th>Enquiry Number</th>
            <th>Enquiry Date</th>
            <th>Supplier Name</th>
            <th>Enquiry Type</th>
            <th>Enquiry Status</th>
            <th>Source</th>
            <th>Remarks</th>
            <th>Supplier Site Name</th>
            <th>Actions</th>

          </tr>
          <tr class="table-info">
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







<!-- popup-->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="max-width: 850px;">
    <div class="modal-content" style="max-height: 90vh; overflow-y: auto;">

      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="contactModalLabel">Contact</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Form Start -->
      <form id="enquirymail" method="POST" enctype="multipart/form-data" class="p-3">
        {{ csrf_field() }}

        <!-- Supplier Info -->
        <div class="mb-3">
          <label for="supplier_name" class="form-label">Supplier Name</label>
          <input type="text" name="supplier_name" class="form-control supplier_name" readonly />
          <input type="hidden" name="enquiry_hdr_id" class="enquiry_hdr_id" />
        </div>

        <!-- Contact Table -->
        <div class="table-responsive mb-4">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Contact Person</th>
                <th>Contact Number</th>
                <th>Contact Mail</th>
                <th></th>
              </tr>
            </thead>
            <tbody class="mcontent2"></tbody>
          </table>
        </div>

        <!-- CC Field -->
        <div class="mb-3 row align-items-center">
          <label for="cc" class="col-md-1 col-form-label">CC</label>
          <div class="col-md-6">
            <input type="text" name="cc[]" class="form-control cc" />
          </div>
        </div>

        <!-- Message -->
        <div class="mb-3">
          <label for="msg" class="form-label">Message</label>
          <textarea name="msg" class="msg tinymce form-control" rows="6">
Thanks and Regards,
Purchase Department,
JRKS,
Kundrathur.
          </textarea>
          <input type="hidden" name="hdr_id" class="msg hdr_id" />
        </div>

        <!-- Attachments -->
        <div class="mb-3">
          <label class="form-label">Add Attachments</label>
          <input type="file" name="email_attachment[]" class="form-control email_attachment" multiple />
        </div>

        <div class="mb-3 form-check">
          <input type="checkbox" name="attchment" class="form-check-input attchment" value="">
          <label class="form-check-label">Attach PDF</label>
        </div>

        <!-- Preview Area -->
        <div class="preview mb-3"></div>

        <!-- Iframe Preview -->
        <div class="mb-4">
          <iframe id="iframepdf" class="w-100" height="400" style="display:none"></iframe>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
          <button type="button" class="btn btn-success sendmail" id="sentmail_id">Send</button>
        </div>

        <div class="ajaxLoading" style="display: none;"></div>
      </form>
      <!-- Form End -->

    </div>
  </div>
</div>



@endsection
@push('scripts')

<script>

  // button purpose
  // Add create button purpose
  $(document).ready(function () {
    if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
      $('#toolbar-container').append(`
            <button class="btn btn-primary create_standard me-2">Create Standard
               <i class="bi bi-plus-circle"></i> 
            </button>
          `);
    }

    if (window.toolbarButtons?.some(btn => btn.attr.id === 'create1')) {
      $('#toolbar-container').append(`
            <button class="btn btn-success create_labour me-2">Create Labour
               <i class="bi bi-plus-circle"></i> 
            </button>
          `);
    }

  });


  // table data
  $(document).ready(function () {

    var table = $('#PurchaseTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "getenquiryData?status={{$status}}&pagemethod={{$pageMethod}}",
      columns: [
        { data: 'enquiry_hdr_id', name: 'enquiry_hdr_id', visible: false },
        { data: 'supplier_id', name: 'supplier_id', visible: false },
        { data: 'enquiry_number', name: 'enquiry_number' },
        { data: 'enquiry_date', name: 'enquiry_date' },
        { data: 'supplier_name', name: 'supplier_name' },
        { data: 'enquiry_type_id', name: 'enquiry_type_id' },
        { data: 'enquiry_status', name: 'enquiry_status' },
        { data: 'source', name: 'source' },
        { data: 'remarks', name: 'remarks' },
        { data: 'supplier_site_name', name: 'supplier_site_name', visible: false },


        {
          data: 'enquiry_hdr_id',
          name: 'actions',
          orderable: false,
          searchable: false,
          className: 'text-center',
          width: '140px',
          render: function (data, type, row) {
            let buttons = '';
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
              buttons += `
					<button type="button" class="btn btn-sm btn-primary edit-btn"
					  data-id="${row.enquiry_hdr_id}"
					  data-type="${row.enquiry_type_id}"
					  data-status="${row.enquiry_status}">
					  <i class="bi bi-pencil"></i>
					</button>`;
            }
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
              buttons += `
					<button type="button" class="btn btn-sm btn-warning view-btn"
					  data-id="${row.enquiry_hdr_id}">
					  <i class="bi bi-eye"></i>
					</button>`;
            }
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
              buttons += `
					<button type="button" class="btn btn-sm btn-danger delete-btn"
					  data-id="${row.enquiry_hdr_id}">
					  <i class="bi bi-trash"></i>
					</button>`;
            }
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'print')) {
              buttons += `
					<button type="button" class="btn btn-sm btn-success print-btn"
					  data-id="${row.enquiry_hdr_id}"
					  data-status="${row.enquiry_status}">
					  <i class="bi bi-printer"></i>
					</button>`;
            }

            if (window.toolbarButtons?.some(btn => btn.attr.id === 'mail')) {
              buttons += `
					<button type="button" class="btn btn-sm btn-info mail-btn"
					  data-id="${row.enquiry_hdr_id}"
					  data-status="${row.enquiry_status}"
					  data-name="${row.supplier_name}">
					  <i class="bi bi-send-check-fill"></i>
					</button>`;
            }

            return buttons;
          }
        }
      ]
    });

    // Individual column search
    $('#PurchaseTbl thead').on('keyup change', ".column-search", function () {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });


  $(document).on('click', '.create_standard', function () {
    var enqtype = "STANDARD";
    var url = "{{ url('purchaseenquirycreate') }}/0/" + enqtype;
    var red_url = "{{ url('purchaseenquiry') }}";
    window.location.replace(url);
  });

  $(document).on('click', '.create_labour', function () {
    var enqtype = "LABOUR";
    var url = "{{ url('purchaseenquirylabourcreate') }}/0/" + enqtype;
    var red_url = "{{ url('purchaseenquiry') }}";
    window.location.replace(url);
  });

  // edit

  $(document).on('click', '.edit-btn', function () {


    const id = $(this).data('id');
    const status = $(this).data('status');
    const type = $(this).data('type');


    if (status != "INITIATED") {
      window.location.replace('purchaseenquirycreate/' + id + '/' + type);

    } else {

      showCustomAlert("Submitted ENQUIRY Cannot Be Edit", 'info');

    }

  });

  // mail

  $(document).on('click', '.mail-btn', function () {

    const id = $(this).data('id');
    const status = $(this).data('status');
    const name = $(this).data('name');

    if (status != "DRAFT") {

      $('.mcontent2').html('');
      $('.supplier_name').val(supplier_name);
      $('.enquiry_hdr_id').val(enquiry_hdr_id);
      var url_print = '{{URL::to("suppliermaildetails")}}/' + id;

      $.get(url_print, function (data) {
        console.log(data);
        if (data == "") {
          showCustomAlert("No Email Contact For Current Supplier So Please Add Email First", 'warning');
        }
        else {

          $.each(data, function (key) {

            $('.mcontent2').append('<tr class="cont_row">\n\
                                    <td><input type="checkbox" name="check_mail" class="check_mail mail_name"  value="' + data[key][3] + '" data-id="' + data[key].supplier_site_id + '" data-value="' + data[key].supplier_site_id + '"></td>\n\
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
                showCustomAlert("Please Check Any Email Contact First", "info");
                $('.cont_row input').removeAttr('disabled');

              }

            });

          });

        }

      });
      $('#contactModal').modal('show');
    }
    else {
      showCustomAlert("Please Select a Initiated Only", "info");
    }


  });


  $('#contactModal').on('shown.bs.modal', function () {

    const id = $(this).data('id');



    $(document).on('click', '.sendmail_old', function () {

      var check = $('.mail_name').is(":checked");
      var form_data = new FormData(document.getElementById('enquirymail'));
      var check = $('.mail_name').is(":checked");
      if (check == true) {
        // var mail=$("input[name='check_mail']:checked").val();
        var mail = [];
        $(':checkbox:checked').each(function (i) {
          mail[i] = $(this).val();
        });
        var mail1 = mail.filter(function (v) { return v !== '' });
        console.log(mail1);
        var cc = $('.cc').val();
        var msg = $('.msg').val();
        form_data.append('id', id);
        form_data.append('mail', mail1);
        form_data.append('cc', cc);
        form_data.append('msg', msg);
        $.ajax({
          url: "{{ url('poenquiryprint') }}",
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
                //update progressbar

              }, true);
            }
            return xhr;
          }
        }).done(function (data, status) {
          if (data == 1) {
            showCustomAlert('Docket Details Send to Mail Successfully', 'success');
            $('#dispatchmail').modal('hide');
            setTimeout(function () {
              location.reload();
            }, 500);
          }
          else {
            showCustomAlert('Sorry! Mail Not Send ', 'error');
          }
        }).fail(function (data, status) {
          $(".alert-success").hide();
          $(".alert-danger").fadeIn(800);
        });
      }
      else {

        showCustomAlert("Please Check a Contact", 'info');
      }
    });



    $('.sendmail').click(function () {
      var check = $('.mail_name').is(":checked");
      var att_check = $(".attchment").is(":checked");
      var index = $("#poenquirygrid").jqGrid('getGridParam', 'selrow');
      var id = $("#poenquirygrid").jqGrid('getCell', index, 'enquiry_hdr_id');
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
          var url = "{{ URL::to('poenquiryprint') }}/" + id + '?mail=' + mail1 + '&cc=' + cc + '&msg=' + msg;
          $.get(url, function (data) {
            console.log(data);
            showCustomAlert("Mail Send Successfully", 'success');
            location.reload();

          });
          filesave();
        } else {
          showCustomAlert("Please Check a Attached Pdf", 'error');
        }
      }
      else {
        showCustomAlert("Please Check a Contact", 'warning');
      }
    });


    function filesave() {
      var form_data = new FormData(document.getElementById('enquirymail'));

      $.ajax({
        url: "{{URL::to('purchaseenquiryfilesave')}}",
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


    $(document).on('click', '.attchment', function () {

      const id = $(this).data('id');
      var en_number = $("#poenquirygrid").jqGrid('getCell', index, 'enquiry_number');

      var url = "{{ URL::to('poenquiryprint') }}/" + id + '?mails=mails';

      $.get(url, function (data) {
        $('#iframepdf').attr('src', "uploads/purchaseenquiry/P_" + en_number + ".pdf");
        $('#iframepdf').show();
      });

    });

  });

  $(".attchment").on("ifUnchecked", function () {
    $('#iframepdf').attr('src', '');
    $('#iframepdf').hide();
    $('.highlight').removeClass('highlight');
  });


  $(document).on('click', '.view-btn', function () {

    const id = $(this).data('id');
    var url = "{{$curlname}}";
    window.location.replace('purchaseenquiryview/' + id + "?return=" + url);

  });



  $('.convert').click(function () {

    const id = $(this).data('id');

    if (id) {
      window.location.replace('purchaseenquirytoquotecreate/' + id + '/0?status=ENQUIRY');
    }

  });


  /*Maruthu Purpose For Print Function*/
  $(document).on('click', '.print-btn', function () {

    const id = $(this).data('id');
    const status = $(this).data('status');

    if (status == "INITIATED") {
      window.open('poenquiryprint/' + id, '_blank');
    }
    else {
      showCustomAlert('Initated Enquiry Only can able to take Print', 'warning')
    }

  });


  $('.convert1').click(function () {

    const id = $(this).data('id');

    if (id) {
      window.location.replace('purchaseenquirytopocreate/' + id + '/0?status=ENQUIRY');
    }
  });


  /*Karthigaa Purpose For Copy Enquiry*/
  $('.copyenq').click(function () {
    var index = $("#poenquirygrid").jqGrid('getGridParam', 'selrow');
    var enqid = $("#poenquirygrid").jqGrid('getCell', index, 'enquiry_hdr_id');
    var enqtype = $("#poenquirygrid").jqGrid('getCell', index, 'enquiry_type_id');
    if (index) {
      window.location.replace('purchasecopyenquirycreate/' + enqid + '/0?status=COPYENQUIRY');
    }
    else {
      notyMsg("info", "Please Select a Row");
    }
  });

  // delete 

  let deleteId = null;

  $(document).on('click', '.delete-btn', function () {
    deleteId = $(this).data('id');
    $('#globalDeleteModal').modal('show');
  });

  $('#globalConfirmDeleteBtn').on('click', function () {
    if (deleteId) {
      $.ajax({
        url: "{{ url('purchaseenquirydelete') }}/" + deleteId,
        type: "GET",
        success: function (data) {

          if (data == '0') {



            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Deleted', 'success');
            $('#PurchaseTbl').DataTable().ajax.reload();
          }
          if (data == '1') {

            $('#globalDeleteModal').modal('hide');
            showCustomAlert("You Cant't delete , Purchase Enquiry Used in SomeWhere", 'error');
            $('#PurchaseTbl').DataTable().ajax.reload();

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


</script>

@endpush