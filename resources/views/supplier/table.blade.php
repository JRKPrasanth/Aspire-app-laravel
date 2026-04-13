@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Supplier</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3 mt-2"></div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="supplierTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Supplier Number</th>
              <th>Supplier Name</th>
              <th>Supplier Type</th>
              <th>PriceList Name</th>
              <th>Active</th>
              <th>MSME Supplier</th>
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

          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

@endsection
@push('scripts')

  <script>

    // table data
    $(document).ready(function () {

      var price = "{{$price}}";
      var supplier = "{{$supplier}}";
      var pageMethod = "{{ $pageMethod }}";

      var table = $('#supplierTbl').DataTable({
        processing: true,
        serverSide: true,
        order: [[7, 'desc']],
        ajax: "{{ route('getsupplierData') }}?pagemethod=" + pageMethod,
        columns: [

          { data: 'supplier_number', name: 'supplier_number' },
          { data: 'supplier_name', name: 'supplier_name' },
          { data: 'suppliertype_name', name: 'suppliertype_name' },
          { data: 'pricelist_name', name: 'pricelist_name' },
          { data: 'active', name: 'active' },
          { data: 'msme_status', name: 'msme_status' },
          { data: 'savestatus', name: 'savestatus' },


          {
            data: 'supplier_id',
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
              data-id="${row.supplier_id}">
              <i class="bi bi-pencil"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-warning view-btn"
              data-id="${row.supplier_id}">
              <i class="bi bi-eye"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-danger delete-btn"
              data-id="${row.supplier_id}">
              <i class="bi bi-trash"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-success app-btn"
              data-id="${row.supplier_id}">
              <i class="bi bi-check2-circle"></i> Approve
            </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#supplierTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
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
      var url = "{{ URL::to('suppliercreate/0')}}";
      window.location.replace(url);
    });

    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');

      var url = "{{ URL::to('suppliernamechkused') }}/" + id;

      $.get(url, function (data) {
        var frieghtcount = $.trim(data);
        if (frieghtcount == '0') {
          var url = "{{ url('supplieredit') }}/" + id;

          window.location.replace(url);

        }
        else {
          var url = "supplieredit";
          var editUrl1 = url + '/' + id + "?status=edit";
          window.location.replace(editUrl1);
        }
      });
    });

    //view function

    $(document).on('click', '.view-btn', function () {

      const id = $(this).data('id');

      var url = "{{ url('supplierview') }}/" + id;

      window.location.replace(url);



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
          url: "{{ url('supplierdelete') }}/" + deleteId,
          type: "DELETE",
          data: {
            _token: "{{ csrf_token() }}"
          },
          success: function (response) {
            $('#globalDeleteModal').modal('hide');

            if (response == 0) {
              showCustomAlert(response.message || 'Delete Successfully', 'success');
            } else if (response == 2) {
              showCustomAlert(response.message || "You Can't delete  Used in SomeWhere", 'info');
            }

            $('#supplierTbl').DataTable().ajax.reload();
          },
          error: function (xhr) {
            $('#globalDeleteModal').modal('hide');
            const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
            showCustomAlert(errorMsg, 'error');
          }
        });
      }
    });

    // approve

    $(document).on('click', '.app-btn', function () {

      const id = $(this).data('id');

      var url = "{{ url('supplierapprovalcreate') }}/" + id;

      window.location.replace(url);



    });

  </script>

@endpush