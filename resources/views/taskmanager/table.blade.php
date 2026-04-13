@extends('layouts.header')
@section('content')

  <h3 class="text-danger">
    <?php if ($pageMethod == "taskmanager") { ?>
    WebOps Tracker
    <?php } else if ($pageMethod == "taskmanagerupdate") { ?>
    WebOps Track - Status Update
    <?php  } else if ($pageMethod == "taskmanagerfinalapproval") { ?>
    WebOps Track - Final Approval
    <?php  } else { ?>
    WebOps Track - Head Approval
    <?php  } ?>
  </h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3"></div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="TaskTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th>Ticket Number</th>
              <th>Assigned To</th>
              <th>Status</th>
              <th>Department Name</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Task</th>
              <th>Created By</th>

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
      let status = '{{ $pageMethod }}';
      if (status === "taskmanagerapproval") status = "approval";
      else if (status === "taskmanagerupdate") status = "update";
      else if (status === "taskmanagerfinalapproval") status = "finalapproval";
      else status = '';

      var table = $('#TaskTbl').DataTable({
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        ajax: {
          url: "{{ route('gettaskmanData') }}",
          data: { status: status }
        },
        columns: [
          {
            data: 'taskmanager_id',
            autoWidth: false,
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '100px',
            render: function (data, type, row) {
              let buttons = '';
              console.log(window.toolbarButtons);
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
            <button  class="btn btn-sm btn-primary me-1 edit-btn" data-id="${data}" data-status="${row.status}"><i class="bi bi-pencil"></i></button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `<button class="btn btn-sm btn-warning me-1 view-btn" data-id="${data}"><i class="bi bi-eye"></i></button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${data}" data-url="{{ url('taskmanagerdelete') }}"><i class="bi bi-trash"></i>  </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                buttons += `<button class="btn btn-sm btn-success approve-btn" data-id="${data}" data-url="{{ url('taskmanagercreate') }}">Approve</button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'finalapprove')) {
                buttons += `<button class="btn btn-sm btn-success finalapprove-btn" data-id="${data}" data-status="${row.status}"  data-url="{{ url('taskmanagercreate') }}">Approve</button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'update')) {
                buttons += `<button class="btn btn-sm btn-primary update-btn" data-id="${data}" data-url="{{ url('taskmanagercreate') }}">Update</button>`;
              }
              return buttons;
            }

          },
          { data: 'ticket_number', name: 'a_taskmanager_t.ticket_number' },
          { data: 'first_name', name: 'hr_employee_t.first_name' },
          { data: 'status', name: 'status' },
          { data: 'sub_department_name', name: 'm_department_lines_t.sub_department_name' },
          { data: 'start_date', name: 'start_date' },
          { data: 'end_date', name: 'end_date' },
          { data: 'task', name: 'task' },
          { data: 'first_name', name: 'tb_users.first_name' },


        ]
      });

      $('#TaskTbl thead').on('keyup change', ".column-search", function () {
        let colIndex = $(this).closest('th').index();
        table.column(colIndex).search(this.value).draw();
      });
    });

    // Create	
    $(".create").click(function () {
      var url = "{{ URL::to('taskmanagercreate/0')}}";
      window.location.replace(url);

    });

    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const status = $(this).data('status');

      if(status=='INITIATED'){
      const url = "{{ url('taskmanageredit') }}/" + id;
      window.location.href = url;
      }else{
        showCustomAlert('only INITIATED data can edit','warning')
      }
    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('taskmanagerview') }}/" + id;
      window.location.href = url;
    });

    // Approve button
    $(document).on('click', '.approve-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('taskmanagercreate') }}/" + id;
      window.location.replace(url + "?status=approve");
    });

    // update status button
    $(document).on('click', '.update-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('taskmanagercreate') }}/" + id;
      window.location.replace(url + "?status=update");
    });

    // Final Approve button
    $(document).on('click', '.finalapprove-btn', function () {

      const id = $(this).data('id');
      const status = $(this).data('status');

      if (status === "CLOSED") {
        showCustomAlert('CLOSED tickets cannot be Approved', 'info');
      } else {
        var url = "{{ url('taskmanagercreate') }}/";
        window.location.replace(url + id + "?status=finalapprove");
      }

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

    // delete function
    let deleteId = null;

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('taskmanagerdelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Deleted successfully!', 'success');
            $('#TaskTbl').DataTable().ajax.reload();

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