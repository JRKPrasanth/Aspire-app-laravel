@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee Upload Update</h3>
  @include('layouts.breadcrumb')

  <div class="card">
    <div class="card-body">
      <div class="row g-4">

        <!-- File Upload Form -->
        <div class="col-md-6">
          <form id="file_up">
            <input type="hidden" name="edit_id" id="edit_id" value="" />
            {{ csrf_field() }}

            <div class="row mb-3 align-items-center">
              <label for="file_upload" class="col-md-3 col-form-label">File:</label>
              <div class="col-md-9">

                <input type="file" id="file_upload" name="file_upload" class="form-control file_upload" required>
                <span class="b_name text-muted small"></span>
              </div>
            </div>

            <div class="d-flex justify-content-center ms-3 gap-3">
              <button type="button" id="upload" class="btn btn-success bg-gradient upload-image">
                <i class="bi bi-cloud-upload"></i> Upload
              </button>
              <a href="{{ url('/download/employee_upload.csv') }}" class="btn btn-warning bg-gradient" download>
                <i class="bi bi-download"></i> Download Template
              </a>
            </div>
          </form>
        </div>

        <!-- Validate and Load Form -->
        <div class="col-md-6">
          <form id="validate_form" action="">
            {{ csrf_field() }}

            <div class="row mb-3">
              <label for="batch_number" class="col-md-4 col-form-label">Batch Number:</label>
              <div class="col-md-8">
                <select id="batch_number" name="batch_number" class="select2 form-control batch_number" required>
                  {!! $batch_no !!}
                </select>
              </div>
            </div>

            <div class="d-flex justify-content-center ms-4 gap-3">
              <button type="button" class="btn btn-primary bg-gradient validate_btn">
                <i class="bi bi-check2-circle"></i> Validate
              </button>
              <button type="button" class="btn btn-info bg-gradient loaded load_btn text-white">
                <i class="bi bi-file-arrow-up"></i> Load
              </button>
            </div>
          </form>
        </div>

        <!-- Action Buttons 
        <div class="col-12 mt-4 text-center">
          <a id="editdata" class="btn btn-warning me-2">
            <i class="bi bi-pencil-square"></i> Edit
          </a>
          <a id="viewdata" class="btn btn-secondary me-2">
            <i class="bi bi-eye"></i> View
          </a>
          <button type="button" class="btn btn-danger">
            <i class="bi bi-trash"></i> Delete
          </button>
        </div>-->

      </div>
    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="EmpTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th class='freeze'>Employee Number</th>
              <th>Company</th>
              <th>Batch No</th>
              <th>Prefix</th>
              <th>Employee Name</th>
              <th>E-mail</th>
              <th>Batch Status</th>

            </tr>
            <tr class="table-info">
              <th><input type="text" class="column-search" placeholder="Search"></th>
              <th class='freeze'><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Employee Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Company</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Prefix</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">E-mail</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  Status</span></th>

            </tr>
          </thead>

          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>


@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      var table = $('#EmpTbl').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollY: "50vh",
        ajax: "{{ route('getEmployeeupdateuploaddata') }}",
        columns: [
          {
            data: 'hr_employee_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '100px',
            render: function (data, type, row) {
              return `
                    <button class="btn btn-sm btn-primary me-1 edit-btn" data-id="${data}">
                        <i class="bi bi-pencil"></i> 
                    </button>

              <button class="btn btn-sm btn-warning me-1 view" data-id="${data}">
                        <i class="bi bi-eye"></i> 
                    </button>
             <button class="btn btn-sm btn-danger me-1 delete" data-id="${data}">
                        <i class="bi bi-trash"></i> 
                    </button>
            `;

            }
          },
          { class: 'freeze', data: "emp_number" },
          { data: "company" },
          { data: "batch_no" },
          { data: "prefix" },
          { data: "first_name" },
          { data: "email" },
          { data: "batch_status", name: 'batch_status' },

        ]
      });

      // Individual column search
      $('#EmpTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      var table = $('#EmpTbl').DataTable();
      var tr = $(this).closest('tr');
      var rowData = table.row(tr).data();
      var status = rowData.batch_status;

      if (status == "LOADED") {
        showCustomAlert("Loaded data can't be Edit", 'error');
      } else {
        const url = "{{ url('employeeedit') }}/" + id;
        window.location.href = url;
      }

    });

    //view function
    $(document).on('click', '.view', function () {
      const id = $(this).data('id');
      const url = "{{ url('employeeupload') }}/" + id;
      window.location.href = url;
    });

    // delete function
    let deleteId = null;

    $(document).on('click', '.delete', function () {
      deleteId = $(this).data('id');
      var table = $('#EmpTbl').DataTable();
      var tr = $(this).closest('tr');
      var rowData = table.row(tr).data();
      var status = rowData.batch_status;

      if (status == "LOADED") {
        showCustomAlert("Loaded data can't be Delete", 'error');
      } else {
        $('#globalDeleteModal').modal('show');
      }


    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('employeeuploadupdatedelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Deleted successfully!', 'success');
            $('#EmpTbl').DataTable().ajax.reload();

          },
          error: function (xhr) {
            $('#globalDeleteModal').modal('hide');
            const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
            showCustomAlert(errorMsg, 'error');
          }
        });
      }
    });



    $(document).ready(function () {

      // file choose
      $(document).on('change', '.file_upload', function () {
        var file = $(this).val();
        var file_name = $('.file_upload')[0].files[0].name;
        $('.b_name').html(file_name);
        //              
      });


      $(document).on('click', '.file_choose', function (e) {
        $("#file_upload").trigger("click");
      });


      /*** verify batch number start **/
      $('.validate_btn').click(function () {

        var batchname = $('.batch_number option:selected').val();
        var parm = '';
        var verify = 'verify';
        if (batchname != '') {
          $('.ajaxLoading').show();

          var parm = "?batchname=" + batchname + '&type=verify';
          var newUrl = refineUrl();//fetch new url
          var url = "{{URL::to('getEmployeeupdatevalidate')}}";

          $.get(url, { 'batchname': encodeURIComponent(batchname), 'type': verify }, function (response) {
            var data = response.status;
            var message = response.message;
            if (data == 'success') {

              showCustomAlert(message, "success");
              setTimeout(function () {
                window.location.replace(newUrl);
              }, 2000);

            }

            if (data == 'info') {
              showCustomAlert(message, 'warning');
              setTimeout(function () {
                window.location.replace(newUrl);
              }, 2000);

            }
            if (data == 'error') {
              showCustomAlert(message, 'error');
              setTimeout(function () {
                window.location.replace(newUrl);
              }, 2000);
            }
          });
        }
        else {
          showCustomAlert("Please select batchnumber", 'warning');
        }


      });

      /*** verify batch number end **/
      /*** load data in employee start **/
      $('.loaded').click(function () {

        var batchname = $('#batch_number option:selected').val();
        var parm = '';
        var load = 'load';
        if (batchname != '') {
          // alert(batchname);
          var parm = "?batchname=" + batchname + '&type=load';
          var url = "{{URL::to('getEmployeeupdatevalidate')}}";

          $.get(url, { 'batchname': encodeURIComponent(batchname), 'type': load }, function (response) {
            var data = response.status;
            var message = response.message;
            if (data == 'success') {

              showCustomAlert(message, "success");
              setTimeout(function () {
                window.location.replace(newUrl);
              }, 2000);

            }

            if (data == 'info') {
              showCustomAlert(message, 'warning');
              setTimeout(function () {
                window.location.replace(newUrl);
              }, 2000);

            }
            if (data == 'error') {
              showCustomAlert(message, "error");
              setTimeout(function () {
                window.location.replace(newUrl);
              }, 2000);
            }
          });
        }
        else {
          showCustomAlert("Please select batchnumber", 'warning');
        }
        var newUrl = refineUrl();


      });
      /*** load data in employee end **/
      function refineUrl() {

        var url = window.location.href;
        var value = url.split("?")[0];
        return value;
      }
      /*** load data in employee start **/
      $(document).on('click', '.load_btn', function () {
        var batch_number = $('.batch_number').select2('val');

        if (batch_number != '') {
          var form_data = new FormData(document.getElementById('validate_form'));
          $.ajax({
            url: "{{ url('employeeload')}}",
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
                }, true);
              }
              return xhr;
            }
          }).done(function (data, status) {
            if (data[1] == 1) {
              showCustomAlert('Employee Details Saved Successfully','success');
              setTimeout(function () {
                location.reload();
              }, 2000);
            }
            else {
              $(".alert-success").hide();
              $(".alert-danger").fadeIn(800);

            }
          }).fail(function (data, status) {

            $(".alert-success").hide();
            $(".alert-danger").fadeIn(800);

          });

        }
        else {
          showCustomAlert('Select Batch Number', 'warning');
        }
      });

      /*** upload function **/

      $(document).on('click', '#upload', function () {

        var file_upload = $('#file_upload').val();
        ;
        if (file_upload != '') {


          var form_data = new FormData(document.getElementById('file_up'));
          $.ajax({
            url: "{{URL::to('employeeuploadupdatesave')}}",
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
                }, true);
              }
              return xhr;
            }
          }).done(function (data, status) {
            if (data == 1) {

              showCustomAlert('Employee Uploaded  Successfully','success');
              setTimeout(function () {
                location.reload();
              }, 2000);
            }
            else if (data == 2) {
              showCustomAlert('Not a Valid File Extension', 'error');
              setTimeout(function () {
                location.reload();
              }, 2000);
            }
            else if (data == 3) {
              showCustomAlert('User is Already Exist in the Database. Try New User', 'error');
              setTimeout(function () {
                location.reload();
              }, 2000);
            }
            else {
              $(".alert-success").hide();
              $(".alert-danger").fadeIn(800);

            }
          }).fail(function (data, status) {

            $(".alert-success").hide();
            $(".alert-danger").fadeIn(800);

          });

        }
        else {
          showCustomAlert('Choose a File', 'warning');
        }
      });

      /** upload end*****/

    });
  </script>

@endpush