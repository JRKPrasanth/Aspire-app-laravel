@extends('layouts.header')
@section('content')

  <h3 class="text-danger">Primary and Secondary Data Upload</h3>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form method="post" action="" id="prmyscdyupload" enctype="multipart/form-data">
        {{ @csrf_field() }}

        <!-- Hidden Input -->
        <input type="text" name="batch_name" class="form-control d-none batch_name" />

        <!-- Upload Controls -->
        <div class="row g-3 mb-4 align-items-end">
          <!-- Download Template -->
          <div class="col-md-3">
            <a href="{{ asset('Uploads/PRIMARY_AND_SECONDARY_DB_TEMPLATE.csv') }}	" class="btn btn-outline-primary w-100"
              download>
              <i class="bi bi-download me-1"></i> Download Template
            </a>
          </div>

          <!-- File Input -->
          <div class="col-md-4">
            <div class="form-floating">
              <input class="form-control" type="file" id="choosefile" name="choosefile" required>
              <input type="hidden" name="batch_name" id="batch_name1" class="form-control" readonly
                value="BATCH-{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
              <label for="choosefile"><i class="bi bi-file-earmark-arrow-up me-1"></i> Choose File</label>
            </div>
          </div>

          <!-- Upload Button -->
          <div class="col-md-3">
            <button type="button" id="upload_popup" class="btn btn-success w-100 upload_popup">
              <i class="bi bi-cloud-arrow-up me-1"></i> Upload
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="PrimSecTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Batch Name</th>
            <th>Batch Status</th>
            <th>Batch Date</th>
            <th>Month-Yr</th>
            <th>Zone</th>
            <th>Region</th>
            <th>State</th>
            <th>Hq</th>
            <th>Stock/Dist Name</th>
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
            <th></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="myModal1" tabindex="-1" aria-labelledby="batchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow rounded-4">

        <!-- Modal Header -->
        <div class="modal-header  bg-info">
          <h5 class="modal-title" id="batchModalLabel">Batch Name</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
          <div class="row g-3">

            <!-- Readonly Batch Name 1 -->
            <div class="col-md-5">
              <input type="text" name="batch_name1" id="batch_name1" class="form-control" readonly
                value="BATCH-{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
            </div>

            <div class="col-md-1 text-center">-</div>

            <!-- Editable Batch Name 2 -->
            <div class="col-md-6">
              <input type="text" name="batch_name2" id="batch_name2" class="form-control" placeholder="Enter suffix">
            </div>

            <!-- Go Button -->
            <div class="col-12 text-center mt-3">
              <button type="button" class="index btn btn-success px-4" data-val="modal">
                <i class="bi bi-check2-circle me-1"></i> Go
              </button>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!--end-->

@endsection
@push('scripts')

  <script>

    $(document).ready(function () {
      var table = $('#PrimSecTbl').DataTable({

        processing: true,
        serverSide: true,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "{{ route('getprmyscdyuploadData') }}",
        columns: [
          { data: 'batch_name', name: 'batch_name' },
          { data: 'batch_status', name: 'batch_status' },
          { data: 'batch_date', name: 'batch_date' },
          { data: 'month_y', name: 'month_y' },
          { data: 'zone', name: 'zone' },
          { data: 'region', name: 'region' },
          { data: 'state', name: 'state' },
          { data: 'HQ_name', name: 'HQ_name' },
          { data: 'stockist_dist_name', name: 'stockist_dist_name' },
          {
            data: 'prmyscdy_upload_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              return `
              <button type="button" class="btn btn-sm btn-info edit-btn" data-id="${data}">
                <i class="bi bi-pencil"></i>
              </button>
            `;
            }
          }
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

    });

    // upload popup

    $('.upload_popup').click(function () {
      var flname = $("#choosefile").val();
      if (flname != "") {
        $('#myModal1').modal('show');
        $('.modal-dialog').width('40%');
        $("#myModal1").modal({ backdrop: "static" });
      }
      else {
        showCustomAlert('Please choose file', 'info');
      }
    });

    // 	save function

    $('#myModal1').on('shown.bs.modal', function () {
      // save function
      let dup_chk = true;

      $(document).on('click', '.index', function () {

        var form = $("#prmyscdyupload");
        form.parsley().validate();

        var $btn = $(this);            
			  $btn.prop('disabled', true);
        if (form.parsley().isValid() && dup_chk == true) {

          var formData = new FormData(form[0]); // Correct way for file upload

          $.ajax({
            url: "{{ URL::to('prmyscdyuploaddata') }}",
            type: "POST",
            data: formData,
            enctype: 'multipart/form-data', // good to include
            processData: false,  // required for FormData
            contentType: false,  // required for FormData
            success: function (data) {
              // Show success message
              showCustomAlert('Saved successfully!', 'success');
              $('#myModal1').modal('hide');
              form[0].reset();
              // Reload DataTable
              $('#PrimSecTbl').DataTable().ajax.reload();
            },
            error: function (xhr) {
              $('#myModal1').modal('hide');
              showCustomAlert('Save failed. Try again.', 'error');
            }
          });
        }

      });
    });

    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('prmyscdyuploadedit') }}/" + id;
      window.location.href = url;
    });
  </script>
@endpush