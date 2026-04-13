@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Machine</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3 mt-2"></div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <div class="table-responsive">
        <table id="wipTbl" class="table table-bordered table-striped w-100" style="width:217% !important;">
          <thead>
            <tr class="table-warning">

              <th>Actions</th>
              <th>Machine Code</th>
              <th>Machine Name</th>
              <th>Department</th>
              <th>Capacity</th>
              <th>Location</th>
              <th>Active</th>
              <th>Relocated Date</th>
              <th>Purchased Date</th>
              <th>Machine Make</th>
              <th>Cost</th>
              <th>Vendor</th>
              <th>Amc Vendor</th>
              <th>Renewal Date</th>
              <th>From Date</th>
              <th>To Date</th>
              <th>Remarks</th>
              <th>Created By</th>

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
            {{-- DataTable will populate via AJAX --}}
          </tbody>
        </table>
      </div>
    </div>
  </div>



@endsection
@push('scripts')

  <script>

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
      var url = "{{ URL::to('machinecreate')}}";
      window.location.replace(url);
    });


    // table data

    $(document).ready(function () {

      var table = $('#wipTbl').DataTable({
        processing: true,
        serverSide: false,
        order: [[7, 'desc']],
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "machineData",

        columns: [

          {

            data: 'machine_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-primary edit-btn"
                                  data-id="${row.machine_hdr_id}">
                                  <i class="bi bi-pencil"></i>
                              </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-warning view-btn"
                                  data-id="${row.machine_hdr_id}">
                                  <i class="bi bi-eye"></i>
                              </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-danger delete-btn"
                                  data-id="${row.machine_hdr_id}">
                                  <i class="bi bi-trash"></i>
                              </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'print')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-success print-btn"
                                  data-id="${row.machine_hdr_id}">
                                  <i class="bi bi-printer"></i> 
                              </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approval')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-success approve-btn"
                                  data-id="${row.machine_hdr_id}">
                                 <i class="bi bi-check2-circle"></i> Approve
                              </button>`;
              }

              return buttons;
            },

          },

          { data: 'machine_code', name: 'machine_code', className: 'text-center' },
          { data: 'machine_name', name: 'machine_name', className: 'text-center' },
          { data: 'department_name', name: 'department_name', className: 'text-center' },
          { data: 'capacity', name: 'capacity', className: 'text-center' },
          { data: 'location', name: 'location', className: 'text-center' },
          { data: 'active', name: 'active', className: 'text-center' },
          { data: 'relocated_date', name: 'relocated_date', className: 'text-center' },
          { data: 'purchased_date', name: 'purchased_date', className: 'text-center' },
          { data: 'machine_make', name: 'machine_make', className: 'text-center' },
          { data: 'cost', name: 'cost', className: 'text-center' },
          { data: 'vendorname', name: 'vendorname', className: 'text-center' },
          { data: 'amcvendor', name: 'amcvendor', className: 'text-center' },
          { data: 'renewal_date', name: 'renewal_date', className: 'text-center' },
          { data: 'from_date', name: 'from_date', className: 'text-center' },
          { data: 'to_date', name: 'to_date', className: 'text-center' },
          { data: 'remarks', name: 'remarks', className: 'text-center' },
          { data: 'first_name', name: 'first_name', className: 'text-center' },

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


    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('machineedit') }}/" + id;
      window.location.href = url;
    });


    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('machineview') }}/" + id;
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
          url: "{{ url('machinedelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {

            if (data == '0') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Deleted successfully!', 'success');
              $('#wipTbl').DataTable().ajax.reload();
            }
            if (data == '1') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Machine Used in SomeWhere!', 'error');
              $('#wipTbl').DataTable().ajax.reload();
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