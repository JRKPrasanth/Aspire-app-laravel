@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Sales Quote</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="mb-3 mt-2"></div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="SalesTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Customer Name</th>
            <th>Quote No</th>
            <th>Quote Type</th>
            <th>Quote Date</th>
            <th>Quote Status</th>
            <th>Actions</th>
          </tr>
          <tr class="table-info">
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



  <!-- Configuration Modal -->
  <div class="modal fade" id="config_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content shadow-lg rounded-4 border-0">
        <div class="modal-header bg-primary bg-gradient text-white rounded-top-4">
          <h5 class="modal-title">Configuration</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="form_body"></div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success px-4">OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Upload Modal -->
  <div class="modal fade" id="upload_Modal_id" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4 border-0" style="height: 80%;">
        <div class="modal-header bg-info bg-gradient text-white rounded-top-4">
          <h5 class="modal-title">Add Documents</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="{{URL::to('soquoteupload')}}" id="add_file_name" enctype="multipart/form-data">
            {{csrf_field()}}

            <div class="mb-3 row">
              <label class="col-sm-3 col-form-label">Documents</label>
              <div class="col-sm-9">
                <input id="choosefile" name="choosefile[]" type="file" class="form-control" multiple required>
              </div>
            </div>

            <div class="table-responsive mb-3">
              <table class="table table-striped align-middle">
                <thead class="table-light">
                  <tr>
                    <th style="width:10%">Sno</th>
                    <th style="width:60%">File</th>
                    <th style="width:10%"></th>
                    <th style="width:20%">Action</th>
                  </tr>
                </thead>
                <tbody class="sales_body"></tbody>
              </table>
            </div>

            <div class="text-center mb-3">
              <input id="file_save_id" class="btn btn-primary px-4" type="submit" value="Save">
              <input type="hidden" name="salesorder_id" class="salesorder_id">
            </div>

            <div class="text-center">
              <input type="text" name="doc_name" id="doc_name" class="form-control mb-2" readonly>
              <button type="button" id="download_btn" class="btn btn-outline-secondary w-100 mb-2" style="display:none">
                <i class="bi bi-download"></i> Download
              </button>
              <iframe src="" id="p_pvw" class="w-100 border rounded" frameborder="0" height="400"
                style="display:none"></iframe>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- View Modal -->
  <div class="modal fade" id="view_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4 border-0" style="height: 70%;">
        <div class="modal-header bg-secondary bg-gradient text-white rounded-top-4">
          <h5 class="modal-title">Download Documents</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th style="width:10%">Sno</th>
                  <th style="width:60%">File</th>
                  <th style="width:10%"></th>
                  <th style="width:20%">Action</th>
                </tr>
              </thead>
              <tbody class="sales_view"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Contact Modal -->
  <div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4 border-0">
        <div class="modal-header bg-warning bg-gradient text-dark rounded-top-4">
          <h5 class="modal-title">Contact</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" action="" id="enquirymail" class="needs-validation" novalidate>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Customer Name</label>
              <input type="text" name="customer_name" class="form-control customer_name" readonly>
              <input type="hidden" name="quote" class="quote">
            </div>

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

            <div class="mb-3 row">
              <label class="col-sm-1 col-form-label">CC</label>
              <div class="col-sm-11">
                <input type="text" name="cc[]" class="form-control cc">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Message</label>
              <textarea name="msg" class="form-control tinymce" rows="5">Thanks and Regards,
    Sales Department,
    JRKS,
    Kundrathur.</textarea>
              <input type="hidden" name="hdr_id" class="hdr_id">
            </div>

            <div class="mb-3">
              <label class="form-label">Add Attachments</label>
              <input type="file" name="email_attachment[]" class="form-control email_attachment" multiple>
            </div>
            <div class="form-check mb-3">
              <input type="checkbox" class="form-check-input attchment" name="attchment" value="">
              <label class="form-check-label">Attach PDF</label>
            </div>

            <div class="preview mb-3"></div>

            <iframe id="iframepdf" class="w-100 border rounded mb-3" height="400" style="display:none"></iframe>
            <button type="button" class="btn btn-success px-4" id="sentmail_id">Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>


@endsection
@push('scripts')

  <script>

    // button purpose
    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_standard')) {
        $('#toolbar-container').append(`
                <button class="btn btn-primary create_standard me-2">Create Standard
                   <i class="bi bi-plus-circle"></i> 
                </button>
              `);
      }

      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_labour')) {
        $('#toolbar-container').append(`
                <button class="btn btn-success create_labour me-2">Create Labour
                   <i class="bi bi-plus-circle"></i> 
                </button>
              `);
      }

    });

    $(document).on('click', '.create_standard', function () {
      var enqtype = "STANDARD";
      var url = "{{ url('soquotecreate') }}/0/" + enqtype;
      var red_url = "{{ url('soquote') }}";
      window.location.replace(url);
    });

    $(document).on('click', '.create_labour', function () {
      var enqtype = "LABOUR";
      var url = "{{ url('soquotecreate') }}/0/" + enqtype;
      var red_url = "{{ url('soquote') }}";
      window.location.replace(url);
    });


    // data table funcrion	
    $(document).ready(function () {

      var status = "{{ $status }}";
      var quotetype = "{{$pageMethod}}";

      var table = $('#SalesTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ URL::to('soquotegriddata') }}?status=" + status + "&quotetype=" + quotetype,
        columns: [


          { data: 'quote_no', name: 'quote_no' },
          { data: 'quote_name', name: 'quote_name' },
          { data: 'quote_type', name: 'quote_type' },
          { data: 'quote_date', name: 'quote_date' },
          { data: 'customer_name', name: 'customer_name' },

          {
            data: 'quote_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
              <button class="btn btn-sm btn-warning view-btn" data-id="${row.quote_hdr_id}">
                <i class="bi bi-eye"></i>
              </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
              <button class="btn btn-sm btn-primary edit-btn" data-id="${row.quote_hdr_id}">
                <i class="bi bi-pencil"></i>
              </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
              <button class="btn btn-sm btn-danger delete-btn" data-id="${row.quote_hdr_id}">
                <i class="bi bi-trash"></i>
              </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#SalesTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });

    $(document).ready(function () {
      var status = "{{ $status }}";
      var quotetype = "{{$pageMethod}}";
      var date_format = "{{\Session::get('p_date_format')}}";
      $("#soquotegrid").jqGrid({
        url: "{{ URL::to('soquotegriddata') }}?status=" + status + "&quotetype=" + quotetype,
        datatype: "json",
        mtype: "GET",
        colModel: [
          { name: "quote_hdr_id", label: "id", hidden: true },
          { name: "savestatus", label: "Save Status", hidden: true },
          { name: "customer_name", label: "Customer Name", editable: true },
          { name: "quote_no", label: "Quote No", editable: true, editrules: { date: true }, },
          { name: "quote_date", label: "Quote Date", editable: true, editrules: { date: true }, formatter: 'date', formatoptions: { srcformat: 'Y-m-d', newformat: 'Y-m-d' } },
          { name: "quote_type", label: "Quote Type", editable: true, editrules: { date: true } },
          { name: "quote_status", label: "Quote Status", editable: true, editrules: { savestatus: true } },

          { name: "customer_id", label: "Customer Name", editable: true, hidden: true },
          { name: "remarks", label: "Remarks ", editable: true, editrules: { savestatus: true } },
        ],

        rowNum: 10,
        viewrecords: true,
        footerrow: true,
        rownumbers: true,
        userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
        width: 780,
        height: 300,
        rowList: [10, 20, 50, 100, 250, 500, 1000],
        pager: "#soquotegrid",
        sortorder: "desc"
      });


      jQuery("#soquotegrid").jqGrid('filterToolbar', { stringResult: true, searchOnEnter: false });
      $("#soquotegrid").jqGrid("setLabel", "rn", "S.No");
      $("#gs_quote_date").attr("placeholder", "Eg:2018-10-31");
      showcolumn('soquotegrid');

      $(document).on('click', ".exportpdf", function () {
        $("#soquotegrid").jqGrid('exportToPdf', {
          title: null,
          orientation: 'portrait',
          pageSize: 'A4',
          description: null,
          onBeforeExport: null,
          download: 'download',
          includeLabels: true,
          includeGroupHeader: true,
          includeFooter: true,
          fileName: "Sales Quote.pdf",
          mimetype: "application/pdf"
        });
      });
      $(document).on('click', ".exportexcel", function () {
        $("#soquotegrid").jqGrid("exportToExcel", {
          includeLabels: true,
          includeGroupHeader: true,
          includeFooter: true,
          fileName: "Sales Quote.xlsx"

        })
      });


      $('.mail').on('click', function () {
        var index = $("#soquotegrid").jqGrid('getGridParam', 'selrow');
        var returnstatus = $("#soquotegrid").jqGrid('getCell', index, 'quote_status');
        var customer_name = $("#soquotegrid").jqGrid('getCell', index, 'customer_name');
        var quote_hdr_id = $("#soquotegrid").jqGrid('getCell', index, 'quote_hdr_id');
        if (index) {
          if (returnstatus == "APPROVED") {

            var id = $("#soquotegrid").jqGrid('getCell', index, 'customer_id');
            $('.mcontent2').html('');
            $('.customer_name').val(customer_name);
            $('.quote').val(quote_hdr_id);
            var url_print = '{{URL::to("customermaildetails")}}/' + id;

            $.get(url_print, function (data) {
              console.log(data);
              if (data == "") {
                notyMsg('info', "No Email Contact For Current customer...Please Add Email First...");
              }
              else {

                $.each(data, function (key) {

                  $('.mcontent2').append('<tr class="cont_row">\n\
                                        <td><input type="checkbox" name="check_mail" class="check_mail mail_name"  value="' + data[key][3] + '" data-id="' + data[key].customer_site_id + '" data-value="' + data[key].customer_site_id + '"></td>\n\
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
                      notyMsg('info', "Please Check Any Email Contact First...");
                      $('.cont_row input').removeAttr('disabled');

                    }

                  });

                });

              }

            });
            $('#contactModal').modal('show');
          }
          else {
            notyMsg("info", "Please Select Approved Only");
          }
        }
        else {
          notyMsg("info", "Please Select Row");
        }

      });

      function ValidateEmail(email) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
      };
      /*

      */
      $('.sendmail').click(function () {
        var index = $("#soquotegrid").jqGrid('getGridParam', 'selrow');
        var id = $("#soquotegrid").jqGrid('getCell', index, 'quote_hdr_id');
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
            if (cc != '') {

              var url = "{{ URL::to('soquoteprint') }}/" + id + '?mail=' + mail + '&cc=' + cc + '&msg=' + msg;
              $.get(url, function (data) {
                console.log(data);
                notyMsg('info', "Mail Send Successfully");
                location.reload();

              });
              filesave();
              /*}*/
            }
            else {
              var url = "{{ URL::to('soquoteprint') }}/" + id + '?mail=' + mail + '&cc=' + cc + '&msg=' + msg;
              $.get(url, function (data) {
                console.log(data);
                notyMsg('info', "Mail Send Successfully");
                location.reload();

              });
              filesave();
            }

          } else {
            notyMsg('info', "Please Check Attach Pdf");

          }



        }
        else {
          notyMsg('info', "Please Check Contact");
        }
      });
      function filesave() {
        var form_data = new FormData(document.getElementById('enquirymail'));
        $.ajax({
          url: "{{URL::to('soquotefilesave')}}",
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
        var check = ($(this).prop('checked'));
        if (check == true) {
          var index = $("#soquotegrid").jqGrid('getGridParam', 'selrow');
          var id = $("#soquotegrid").jqGrid('getCell', index, 'quote_hdr_id');
          var quote_no = $("#soquotegrid").jqGrid('getCell', index, 'quote_no');
          var url = "{{ URL::to('soquoteprint') }}/" + id + '?mails=mails';
          $.get(url, function (data) {
            $('#iframepdf').attr('src', "uploads/soquoteupload/S_" + quote_no + ".pdf");
            $('#iframepdf').show();
          });
        }
        else {
          $('#iframepdf').attr('src', '');
          $('#iframepdf').hide();
          $('.highlight').removeClass('highlight');
        }

      });


      $(document).on('click', '.create', function () {
        var quotetype = $(this).val();
        if (quotetype != "LABOUR") {
          var url = "{{URL::to('soquotecreate')}}/0/" + quotetype;
        } else {
          var url = "{{URL::to('soquotecreatelab')}}/0/" + quotetype;
        }
        window.location.replace(url);
      });

      /*Karthigaa Purpose For Edit Function*/
      $("#edit").click(function () {

        var index = $("#soquotegrid").jqGrid('getGridParam', 'selrow');
        var quote_status = $("#soquotegrid").jqGrid('getCell', index, 'quote_status');
        var quoteid = $("#soquotegrid").jqGrid('getCell', index, 'quote_hdr_id');
        var quotetype = $("#soquotegrid").jqGrid('getCell', index, 'quote_type');
        // var quotetype = $(this).val();
        // alert(quotetype);
        if (index) {
          if (quote_status != "APPROVED" && quote_status != "INITIATED") {
            window.location.replace('soquotecreate/' + quoteid + '/' + quotetype);
          }
          else {
            notyMsg('info', "<i class='fa fa-exclamation-circle' style='font-size:16px'></i> Approved or Submitted Quote Cannot Be Edit!!!");
          }
        }
        else {
          notyMsg('info', "Please Select Row");
        }
      });
      /*Karthigaa Purpose For Edit Function*/
      $("#edit_approv").click(function () {
        var index = $("#soquotegrid").jqGrid('getGridParam', 'selrow');
        var quotehdrid = $("#soquotegrid").jqGrid('getCell', index, 'quote_hdr_id');
        var quotetype = $("#soquotegrid").jqGrid('getCell', index, 'quote_type');
        var status = $("#soquotegrid").jqGrid('getCell', index, 'status');

        if (index) {
          if (status != "Approved" && status != "Rejected") {

            window.location.replace('salesquoteapprovalview/' + quotehdrid + '/' + quotetype);
          } else {
            notyMsg('info', "Already" + status);
          }
        }

        else {
          notyMsg('info', "Please Select Row");
        }

      });


      /*Maruthu Purpose For View Function*/
      $("#view").click(function () {
        //alert('sa');
        var index = $("#soquotegrid").jqGrid('getGridParam', 'selrow');
        var quoteid = $("#soquotegrid").jqGrid('getCell', index, 'quote_hdr_id');
        var return1 = "{{$pageMethod}}";
        if (index) {
          var msg = "quote";
          window.location.replace('soquoteview/' + quoteid + "/" + msg + '?return=' + return1);
        }
        else {
          notyMsg('info', "Please Select Row");
        }
      });


      $("#clearsearch").click(function () {

        var grid = $("#soquotegrid");
        grid.jqGrid('setGridParam', { search: false });
        var postData = grid.jqGrid('getGridParam', 'postData');
        $.extend(postData, { filters: "" });
        grid.trigger("reloadGrid", [{ page: 1 }]);
        $('input[id*="gs_"]').val("");

      });

      /*Maruthu Purpose For Delete Function*/
      $("#delete").click(function () {
        var index = $("#soquotegrid").jqGrid('getGridParam', 'selrow');
        var quoteid = $("#soquotegrid").jqGrid('getCell', index, 'quote_hdr_id');
        if (index) {
          swal({
            title: "Are you sure?",
            text: "You want to delete!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: !1,
            closeOnCancel: !1
          }, function (e) {

            if (e == true) {

              var url = "{{ URL::to('soquotedelete')}}/" + quoteid;
              var red_url = "{{  URL::to('soquote') }}";

              $.get(url, function (data) {
                var data = $.trim(data);
                console.log(data);
                if (data == "1") {
                  notyMsg('error', "You Can't delete , Sales  Quote Used in SomeWhere!!!", red_url);
                  $('.cancel').trigger('click');
                }
                else {
                  notyMsg('success', 'Deleted Successfully!!!', red_url);

                  window.location.href = red_url;

                }
              });
            }
            else {
              $('.apply').css('display', 'none');
              swal("Cancelled");
            }

          })
          $('.apply').css('display', 'none');
        }
        else {
          notyMsg('info', "Please Select Row");
        }
      });




      $(".convert").click(function () {

        var index = $("#soquotegrid").jqGrid('getGridParam', 'selrow');
        var quoteid = $("#soquotegrid").jqGrid('getCell', index, 'quote_hdr_id');

        if (index) {

          var type = "savestatus";
          var url = "{{ url('soquotestatus')}}/" + quoteid + "/" + type;
          $.get(url, function (data) {

            var data = $.trim(data);
            if (data == "SAVE") {
              window.location.replace('salesorderfromqo/' + quoteid);
            }
            else {
              notyMsg('warning', "<i class='fa fa-exclamation-circle' style='font-size:16px'></i> Please Save Enquiry First. Which is in " + data + " Status!!!");
            }
          });

        }
        else {

          notyMsg('info', "Please Select Row");
        }
      });
      /*Maruthu Purpose For View Function*/
      $("#copyquote").click(function () {
        var index = $("#soquotegrid").jqGrid('getGridParam', 'selrow');
        var quoteid = $("#soquotegrid").jqGrid('getCell', index, 'quote_hdr_id');
        if (index) {
          window.location.replace('copysalesquote/' + quoteid);
        }
        else {
          notyMsg('info', "Please Select Row");
        }
      });


      /***** Delete Row ********/
      /***** Karthigaa Purpose For CLEAR search ********/
      $("#clearsearch").click(function () {
        var grid = $("#soquotegrid");
        grid.jqGrid('setGridParam', { search: false });

        var postData = grid.jqGrid('getGridParam', 'postData');
        $.extend(postData, { filters: "" });
        grid.trigger("reloadGrid", [{ page: 1 }]);

      });
      /*pavan purpose:attach file and view*/
      $(".attach").click(function () {
        var id = $('#soquotegrid').jqGrid('getGridParam', 'selrow');
        var cellvalue = $('#soquotegrid').jqGrid('getCell', id, 'quote_hdr_id');
        $('.salesid').val(cellvalue);
        $('.so_order_table').hide();
        if (id) {
          var url = "{{URL::to('soquoteuploaddata')}}/" + cellvalue;
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
        var id = $('#soquotegrid').jqGrid('getGridParam', 'selrow');
        var cellvalue = $('#soquotegrid').jqGrid('getCell', id, 'quote_hdr_id');
        $('.salesid').val(cellvalue);
        $('.so_order_table').hide();
        if (id) {
          var url = "{{URL::to('soquoteuploaddata')}}/" + cellvalue;
          $.getJSON(url, function (data) {
            console.log(data[0]);
            if (data) {
              $('.so_order_table').show();
              var html = "";
              $.each(data, function (index, value) {
                var i = index + 1;

                html += "<tr class=''><td></td><td>" + i + "</td><td>" + value + "</td><td><input type='hidden' class='download' name='file[]' value='" + value + "'></td><td><a href='../public/uploads/soquoteupload/SQ" + cellvalue + "/" + value + "'  download><i class='fa fa-download downloadss' aria-hidden='true' ></i></a></td></tr>";
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
      /*end*/
    });
    /*End*/


    /*************** print *****************/
    $('.print').on('click', function () {

      var id = $('#soquotegrid').jqGrid('getGridParam', 'selrow');
      var cellvalue = $('#soquotegrid').jqGrid('getCell', id, 'quote_hdr_id');

      if (id) {

        var url = "soquoteprint";
        var print_url = url + '/' + cellvalue;
        window.open(print_url, '_blank');
      }
      else {
        notyMsg('info', "Please Select Row");
      }
    });


  </script>

@endpush