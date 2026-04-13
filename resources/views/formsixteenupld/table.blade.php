@extends('layouts.header')
@section('content')
<h3 class="text-danger">    Employee Active List for FORM16 </h3>
@include('layouts.breadcrumb')

<style>
.select2-container--open { z-index: 200000 !important; }
</style>	

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
          <th>Actions</th> 
          <th>Employee Number</th>
          <th>Employee Name</th>
          <th>Employee Type</th>
          <th>Position</th>
          <th>Job Title</th>
          <th>Active</th>
          </tr>
          <tr class="table-danger">
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

<!--popup for form16upload-->

<div class="modal fade" id="form16Modal" tabindex="-1" aria-labelledby="form16ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-4">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="form16ModalLabel">
          <i class="fa fa-file-text me-2"></i>Form16 Update
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body py-4">
        <div class="d-flex justify-content-between mb-3">
          <div>
            <strong>Employee Number:</strong> <span class="emp_no text-primary fw-semibold"></span>
          </div>
          <div>
            <strong>Employee Name:</strong> <span class="emp_name text-primary fw-semibold"></span>
          </div>
        </div>

        <form id="form16UploadForm" enctype="multipart/form-data">
          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Choose File</label>
              <input type="file" name="choosefile" class="form-control choosefile" multiple>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Remarks</label>
              <input type="text" name="remarks" class="form-control remarks" placeholder="Enter remarks">
              <input type="hidden" name="id" class="emp_id">
              <input type="hidden" name="e_mail" class="e_mail">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Financial Year</label>
              <select name="acc_year" id="acc_year" class="form-select select2 acc_year"></select>
            </div>
          </div>
        </form>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-success px-4 form16upld_save" id="updateClose">
          <i class="fa fa-upload me-1"></i> Update
        </button>
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
          <i class="fa fa-times me-1"></i> Close
        </button>
      </div>

    </div>
  </div>
</div>

<!--end-->


@endsection
@push('scripts')

<script>

$(document).ready(function () {
  var table = $('#AccTbl').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('getFormsixteenupldData') }}",
    columns: [
      {
        data: 'employee_id',
        name: 'actions',
        orderable: false,
        searchable: false,
        className: 'text-center',
        render: function (data, type, row) {
          return `
            <button class="btn btn-sm btn-primary upload-btn" 
                    data-id="${row.employee_id}" 
                    data-number="${row.employee_number}" 
                    data-name="${row.first_name}" 
                    data-email="${row.email}" 
                    data-active="${row.active}">
              Upload
            </button>`;
        }
      },
      { data: 'employee_number', name: 'employee_number' },
      { data: 'first_name', name: 'first_name' },
      { data: 'emp_type_name', name: 'emp_type_name' },
      { data: 'position_name', name: 'position_name' },
      { data: 'job_title_name', name: 'job_title_name' },
      { data: 'active', name: 'active' },
    ]
  });

  // Individual column search
  $('#AccTbl thead').on('keyup change', ".column-search", function () {
    var colIndex = $(this).parent().index();
    table.column(colIndex).search(this.value).draw();
  });

  // Upload button click event
  $(document).on('click', '.upload-btn', function () {
    var employee_id = $(this).data('id');
    var employee_number = $(this).data('number');
    var first_name = $(this).data('name');
    var email = $(this).data('email');
    var active = $(this).data('active');

    if (active === 'Yes') {
      $(".emp_no").html(employee_number);
      $(".emp_name").html(first_name);
      $(".emp_id").val(employee_id);
      $(".e_mail").val(email);

      $("#form16Modal").modal('show');

      var url = "{{ URL::to('form16upldedit') }}?id=" + employee_id;
      $.get(url, function (data) {
        $('.choosefile').val(data.choosefile);
        $('.remarks').val(data.remarks);
        $('.acc_year').val(data.acc_year).trigger('change'); // for select2

        if (data.update !== "create") {
          // fields can be readonly if needed
        } else {
          $('.choosefile').attr("readonly", false);
          $('.remarks').css("pointer-events", "auto");
        }
      });
    } else {
      showCustomAlert("Please Select Active Employee", 'info');
    }
  });
});

	
	
var urlAccYear = "{{ URL::to('jcomboformlogin') }}?table=account_year:id:year&order_by=id";

$.ajax({
    url: urlAccYear,
    type: 'GET',
    success: function (data) {
        // Parse JSON if returned as text
        if (typeof data === "string") {
            try {
                data = JSON.parse(data);
            } catch (e) {
                console.error("Invalid JSON response:", data);
                return;
            }
        }

        // Reset dropdown
        $(".acc_year").html('<option value="">-- Select Account Year --</option>');

        // Populate dropdown
        $.each(data, function (i, item) {
            $(".acc_year").append(
                `<option value="${item.val}">${item.option_name}</option>`
            );
        });

        // If select2 is applied
        $(".acc_year").trigger('change.select2');
    },
    error: function (xhr, status, error) {
        console.error("AJAX Error (acc_year):", error);
    }
});


	
    $('.form16upld_save').click(function() {
        var formData = new FormData($('#form16UploadForm')[0]);
        var red_url = '{{ route('formsixteenupld') }}';
        
        $.ajax({
            url: '{{ route('form16upldupdate') }}',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    showCustomAlert("Form16 File Uploaded Successfully",'success');
                    $('#form16Modal').modal('hide');
                } else {
                    showCustomAlert('File upload failed: ' + response.error,'error');
                }
                setTimeout(function(){
                    window.location.href = red_url;
                }, 1500);
            },
            error: function(xhr, status, error) {
               showCustomAlert("Form16 File Uploaded Successfully",'success');
                setTimeout(function(){
                    window.location.href = red_url;
                }, 1500);
            }
        });
    });	

	
 </script>

@endpush
