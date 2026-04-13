@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Look Ups</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3"></div>
  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="companyTable" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Lookup Name</th>
              <th>Actions</th>
            </tr>
            <tr class="table-info">
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Name" /></th>
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

      var table = $('#companyTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('lookuptblData') }}",
        columns: [
          { data: 'lookup_type', name: 'lookup_type' },

          {
            data: 'lookuphdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              let buttons = '';
              console.log(window.toolbarButtons);
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
                                 <button  class="btn btn-sm btn-primary me-1 edit-btn me-1" data-id="${data}"><i class="bi bi-pencil"></i></button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `<button class="btn btn-sm btn-warning me-1 view-btn" data-id="${data}"><i class="bi bi-eye"></i></button>`;
              }


              return buttons;
            }

          }
        ]
      });

      // Individual column search
      $('#companyTable thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    // create function
    $(".create").click(function () {
      var url = "{{ URL::to('lookcreate')}}";
      window.location.replace(url);
    });

    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('lookupcreate') }}/" + id;
      window.location.href = url;
    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('lookupsaveview') }}/" + id;
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