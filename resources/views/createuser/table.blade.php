@extends('layouts.header')
@section('content')
  <h2 class="text-danger">User</h2>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3"></div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="UserTable" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>User Name</th>
              <th>Email</th>
              <th>Mobile No</th>
              <th>Group Name</th>
              <th>Actions</th>
            </tr>
            <tr class="table-info">
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Username" />
              </th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Email" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Mobile" />
              </th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Group" /></th>
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

      var table = $('#UserTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('Useraccessdata') }}",
        columns: [
          { data: 'username', name: 'tb_users.username' },
          { data: 'email', name: 'tb_users.email' },
          { data: 'mobile_no', name: 'tb_users.mobile_no' },
          { data: 'group_name', name: 'a_m_group_t.group_name' },
          {
            data: 'id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
                <button  class="btn btn-sm btn-primary me-1 edit-btn" data-id="${data}"><i class="bi bi-pencil"></i></button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += ` <button class="btn btn-sm btn-warning me-1 view-btn" data-id="${data}"><i class="bi bi-eye"></i></button>`;
              }
              return buttons;
            }

          }
        ]
      });

      // Individual column search
      $('#UserTable thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });

    // create function
    $(".create").click(function () {
      var url = "{{ URL::to('createuser')}}";
      window.location.replace(url);
    });
    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('useredit') }}/" + id;
      window.location.href = url;
    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('userview') }}/" + id;
      window.location.href = url;
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
  </script>
@endpush