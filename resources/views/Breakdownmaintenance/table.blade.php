@extends('layouts.header')
@section('content')
  <?php if ($pageMethod == "allocateengineer") {?>
  <h3 class="text-danger">Allocate Engineer</h3>
  <?php } else if ($pageMethod == "allocatetechnician") { ?>
  <h3 class="text-danger">Allocate Technician</h3>
  <?php  } else if ($pageMethod == "requestraise") {?>
  <h3 class="text-danger">Ticket Closure Request</h3>
  <?php  } else if ($pageMethod == "approverequest") { ?>
  <h3 class="text-danger">Ticket Closure Approval</h3>
  <?php  } else if ($pageMethod == "closerequest") {?>
  <h3 class="text-danger">Close Request</h3>
  <?php  } else if ($pageMethod == "sopupload") {?>
  <h3 class="text-danger">SOP</h3>
  <?php  } else { ?>
  <h3 class="text-danger">Create Issue</h3>
  <?php  } ?>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3"></div>




  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="breakTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">

              <th>Machine Name</th>
              <th>Breakdown Type</th>
              <th>Ticket Number</th>
              <th>Issue Date</th>
              <th>Breakdown Severity</th>
              <th>Causes of Breakdown</th>
              <th>Actions</th>

            </tr>
            <tr class="table-info">
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th></th>
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

      var method = "{{$pageMethod}}";
      var url = "{{URL::to('issueData')}}?status=" + method;


      var table = $('#breakTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: url,
        columns: [
          { data: 'machine_name', name: 'machine_name' },
          { data: 'breakdown_name', name: 'breakdown_name' },
          { data: 'ticket_number', name: 'ticket_number' },
          { data: 'issue_date', name: 'issue_date' },
          { data: 'severity_name', name: 'severity_name' },
          { data: 'causes', name: 'causes' },

          {
            data: 'id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '150px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
            <button  class="btn btn-sm btn-warning me-1 view" data-id="${data}"><i class="bi bi-eye"></i></button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'allocate')) {
                buttons += `
            <button  class="btn btn-sm btn-primary me-1 allocate" data-id="${data}"
            		data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                title=""><i class="bi bi-check2-circle"></i> </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'upload')) {
                buttons += `
                         <button  class="btn btn-sm btn-primary me-1 upload_btn" data-id="${data}"><i class="bi bi-upload"></i> </button>`;
              }

            <?php if($pageMethod=="sopupload"){ ?>
                buttons += `
            <button  class="btn btn-sm btn-warning me-1 view_btn" data-id="${data}"><i class="bi bi-eye"></i> </button>`;

                            buttons += `
            <button  class="btn btn-sm btn-primary me-1 create_btn" data-id="${data}"><i class="bi bi-plus"></i> </button>`;

                buttons += `
            <button  class="btn btn-sm btn-success me-1 download_btn" data-id="${data}" data-file="${row.files}"><i class="bi bi-download"></i> </button>`;
              <?php } ?>

              return buttons;
            }

          }
        ]
      });

      // Individual column search
      $('#breakTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    // view function
    $(document).on('click', '.view', function () {
      const id = $(this).data('id');
      const url = "{{ url('userview') }}/" + id;
      window.location.href = url;
    });


    // allocate engineer
    $(document).on('click', '.allocate', function () {
      var btnval = "{{$pageMethod}}";
      const id = $(this).data('id');
      const url = "{{ url('engineerallocate') }}/" + id + '?btnval=' + btnval;
      window.location.href = url;
    });

    $(document).on('click', '.create_btn', function () {

          var btnval="{{$pageMethod}}";

        const id = $(this).data('id');
		    window.location.replace('engineerallocate/'+id+'?btnval='+"sopupload");

});
    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
              <button class="btn btn-primary create me-2">Create
                <i class="bi bi-plus-circle"></i> 
              </button>
            `);
      }
    });

    // create function
    $(".create").click(function () {
      var url = "{{ URL::to('issuecreate')}}";
      window.location.replace(url);
    });

    // sop create
    $(document).on('click', '.upload_btn', function () {
      var btnval = "{{$pageMethod}}";
      const id = $(this).data('id');
      const url = "{{ url('engineerallocate') }}/" + id + '?btnval=' + btnval;
      window.location.href = url;
    });


    // sop view 	

    $(document).on('click', '.view_btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('sopview') }}/" + id;
      window.location.href = url;
    });


    // sop download

    $(document).on('click', '.download_btn', function () {

      var files = $(this).data('file');
      var file_path = 'upload/sop/' + files;

      if (files) {
        var a = document.createElement('A');
        a.href = file_path;
        a.download = file_path.substring(file_path.lastIndexOf('/') + 1);
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
      } else {
        showCustomAlert('There is No file to Download', 'warning');
      }

    });


  </script>
@endpush