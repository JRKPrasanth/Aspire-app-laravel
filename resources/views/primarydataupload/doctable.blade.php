@extends('layouts.header')
@section('content')

  <h3 class="text-danger">Doctor Call Average Upload</h3>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form method="post" action="" id="docupload" enctype="multipart/form-data">
        {{ @csrf_field() }}

        <!-- Hidden Input -->
        <input type="text" name="batch_name" class="form-control d-none batch_name" />

        <!-- Upload Controls -->
        <div class="row g-3 mb-4 align-items-end">

          <!-- File Input -->
          <div class="col-md-4 me-4">
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
      <table id="PrimaryTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Emp Name</th>
            <th>Desigination</th>
            <th>Zone</th>
            <th>Region</th>
            <th>State</th>
            <th>Hq</th>
            <th>Mon-Yr</th>
            <th>Doc Coverage</th>
             <th>Doc Call Avg</th>
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
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>


@endsection
@push('scripts')

  <script>

    $(document).ready(function () {
      var table = $('#PrimaryTbl').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "{{ route('docavguploaddata') }}",
        columns: [
          { data: 'employee_name', name: 'employee_name' },
          { data: 'designation', name: 'designation' },
          { data: 'zone', name: 'zone' },
          { data: 'region', name: 'region' },
          { data: 'state', name: 'state' },
          { data: 'hq_name', name: 'hq_name' },
          { data: 'month', name: 'month' },
          { data: 'doctor_coverage', name: 'doctor_coverage' },
          { data: 'doctor_call_average', name: 'doctor_call_average' }

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

       let dup_chk = true;
        var form = $("#docupload");
        form.parsley().validate();

        var $btn = $(this);            
		$btn.prop('disabled', true);

        if (form.parsley().isValid() && dup_chk == true) {

          var formData = new FormData(form[0]); // Correct way for file upload

          $.ajax({
            url: "{{ url('docuploaddatasave') }}",
            type: "POST",
            data: formData,
            enctype: 'multipart/form-data', // good to include
            processData: false,  // required for FormData
            contentType: false,  // required for FormData
            success: function (data) {
              // Show success message
              showCustomAlert('Saved successfully!','success');
                window.location.reload();
            },
            error: function (xhr) {
              showCustomAlert('Save failed. Try again.','error');
            }
          });
        }

      }else {
        showCustomAlert('Please choose file', 'info');
      }


    });


  </script>

@endpush