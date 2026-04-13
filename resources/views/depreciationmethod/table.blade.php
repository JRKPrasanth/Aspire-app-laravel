@extends('layouts.header')
@section('content')
  <h3 class="text-danger">    Depreciation Method   </h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3 mt-1"></div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Depreciation Method Name</th>
            <th>Asset Type</th>
            <th>PO Number</th>
            <th>Product Name</th>
			 <th>Salvage Value</th>
			 <th>Depreciation Percentage</th>
            <th>Actions</th>
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
      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getdepreciationData') }}",
        columns: [

        { data: "lookup_code"},
        { data: "asset_type_name"},
        { data: "po_number"},
        { data: "concatenated_product"},
        { data: "salvage_value"},
        { data: "depreciation_value"},

          {
            data: 'depreciation_method_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
        <button class="btn btn-sm btn-warning view-btn" data-id="${row.depreciation_method_id}">
          <i class="bi bi-eye"></i>
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.depreciation_method_id}">
          <i class="bi bi-pencil"></i>
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.depreciation_method_id}">
          <i class="bi bi-trash"></i>
        </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#AccTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });	  
	  
	  
    // create function
    $(".create").click(function () {
      var url = "{{ URL::to('depreciationmethodcreate')}}";
      window.location.replace(url);
    });
    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('depreciationmethodcreate') }}/" + id;
      window.location.href = url;
    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('depreciationmethodview') }}/" + id;
      window.location.href = url;
    });	  
	  
 </script>

@endpush
