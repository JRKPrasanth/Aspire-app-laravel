@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Machine Log</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3"></div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="MaclogTable" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Department Name</th>
              <th>Machine Name</th>
              <th>Product Name</th>
              <th>Batch Number</th>
              <th>Quantity</th>
              <th>Damages</th>
              <th>Activity Date</th>
              <th>Running Time</th>
              <th>Remarks</th>
              <th>Created By</th>
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

      var table = $('#MaclogTable').DataTable({

        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "{{ route('getmachinelogData') }}",

        columns: [
          {
            data: 'id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '120px',
            render: function (data, type, row) {
              let buttons = '';
              console.log(window.toolbarButtons);
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
            <button  class="btn btn-sm btn-info me-1 edit-btn" data-id="${data}"><i class="bi bi-pencil"></i></button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `<button class="btn btn-sm btn-warning me-1 view-btn" data-id="${data}"><i class="bi bi-eye"></i></button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${data}" data-url="{{ url('companydelete') }}"><i class="bi bi-trash"></i>  </button>`;
              }

              return buttons;
            }

          },
          { data: 'process_dept', name: 'process_dept' },
          { data: 'machine_name', name: 'w_machine_hdr_t.machine_name' },
          { data: 'concatenated_product', name: 'm_products_t.concatenated_product' },
          { data: 'batch_number', name: 'batch_number' },
          { data: 'quantity', name: 'quantity' },
          { data: 'damages', name: 'damages' },
          { data: 'date', name: 'date' },
          { data: 'running_hours', name: 'running_hours' },
          { data: 'remarks', name: 'remarks' },
          { data: 'first_name', name: 'tb_users.first_name' },

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

    // create function
    $(".create").click(function () {
      var url = "{{ URL::to('machinelogcreate')}}";
      window.location.replace(url);
    });
    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('machinelogedit') }}/" + id;
      window.location.href = url;
    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('machinelogview') }}/" + id;
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