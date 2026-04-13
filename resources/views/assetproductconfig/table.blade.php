@extends ('layouts.header')
@section('content')
  <h3 class="text-danger">Asset Product Config</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3 mt-1"></div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="InvTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Product Name</th>
            <th>Brand Name</th>
            <th>Quantity</th>
            <th>Warranty</th>
            <th>Purchase Date</th>
            <th>Supplier Name</th>
            <th>Status</th>
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

    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
                <button class="btn btn-primary text-white px-4 create me-2">Create
                  <i class="bi bi-plus-circle"></i> 
                </button>
              `);
      }
    });


    // data table funcrion	
    $(document).ready(function () {
      var table = $('#InvTbl').DataTable({
        processing: true,
        serverSide: true,
        order: [[4, 'desc']],
        ajax: "assetproductconfiggridData?pagemethod={{ $pageMethod }}",
        columns: [

          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'brand_name', name: 'brand_name' },
          { data: 'qty', name: 'qty' },
          { data: 'warrenty', name: 'warrenty' },
          { data: 'purchase_date', name: 'purchase_date' },
          { data: 'supplier_name', name: 'supplier_name' },
         // { data: 'sub_department_name', name: 'sub_department_name' },
          { data: 'asset_status', name: 'asset_status' },

          {
            data: 'asset_config_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
          <button class="btn btn-sm btn-warning view-btn" data-id="${row.asset_config_id}">
            <i class="bi bi-eye"></i>
          </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
          <button class="btn btn-sm btn-primary edit-btn" data-id="${row.asset_config_id}">
            <i class="bi bi-pencil"></i>
          </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
          <button class="btn btn-sm btn-danger delete-btn" data-id="${row.asset_config_id}">
            <i class="bi bi-trash"></i>
          </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#InvTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    // create function
    $(".create").click(function () {
      var url = "{{ URL::to('assetproductconfigcreate')}}";
      window.location.replace(url);
    });
    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('assetproductconfigedit') }}/" + id;
      window.location.href = url;
    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('assetproductconfigview') }}/" + id;
      window.location.href = url;
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
          url: "{{ url('assetproductconfigdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {
            if (data == '0') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Deleted successfully!', 'success');
              $('#InvTbl').DataTable().ajax.reload();
            }
            if (data == '1') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert("You Can't delete , Product Used in SomeWhere.", 'error');
              $('#InvTbl').DataTable().ajax.reload();
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