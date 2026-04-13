@extends('layouts.header')
@section('content')
  <h3 class="text-danger">

    <?php if ($pageMethod == 'schemesapproval') { ?>
    Schemes Approval - Level 1
    <?php } else if ($pageMethod == 'schemeslevel2approval') { ?>
    Schemes Approval - Level 2
    <?php  } else { ?>
    Schemes
    <?php  } ?>
  </h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3 mt-1"></div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="SalesTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Actions</th>
            <th>Schemes Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Level -1 Approve Status</th>
            <th>Level -2 Approve Status</th>
            <th>Created By</th>
            <th>Level -1 Approver</th>
            <th>Level -2 Approver</th>

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


    // create function
    $(".create").click(function () {
      var url = "{{URL::to('schemescreate')}}/0";
      window.location.replace(url);
    });
    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('schemesedit') }}/" + id;
      window.location.href = url;
    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('schemesview') }}/" + id;
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
          url: "{{ url('schemesdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {

            if (data == '1') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert("You Can't delete , Subinventory Used in SomeWhere.", 'error');
              $('#SalesTbl').DataTable().ajax.reload();
            } else {

              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Deleted successfully!', 'success');
              $('#SalesTbl').DataTable().ajax.reload();

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


    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
                <button class="btn btn-success text-white px-4 create me-2">Create
                  <i class="bi bi-plus-circle"></i> 
                </button>
              `);
      }
    });


    // data table funcrion	
    $(document).ready(function () {
      var table = $('#SalesTbl').DataTable({
        processing: true,
        serverSide: false,
        order: [[2, 'desc']],
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "getSchemesData?pagemethod={{ $pageMethod }}",
        columns: [

          {
            data: 'schemes_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
          <button class="btn btn-sm btn-warning view-btn" data-id="${row.schemes_hdr_id}">
            <i class="bi bi-eye"></i>
          </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve1')) {
                buttons += `
          <button class="btn btn-sm btn-success approve1-btn px-2" data-id="${row.schemes_hdr_id}">
            Approve
          </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve2')) {
                buttons += `
          <button class="btn btn-sm btn-success approve2-btn px-2" data-id="${row.schemes_hdr_id}">
            Approve
          </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
          <button class="btn btn-sm btn-primary edit-btn" data-id="${row.schemes_hdr_id}">
            <i class="bi bi-pencil"></i>
          </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
          <button class="btn btn-sm btn-danger delete-btn" data-id="${row.schemes_hdr_id}">
            <i class="bi bi-trash"></i>
          </button>`;
              }
              return buttons;
            }
          },
          { data: 'schemes_name', name: 'schemes_name' },
          { data: 'start_date', name: 'start_date' },
          { data: 'end_date', name: 'end_date' },
          { data: 'savestatus', name: 'savestatus' },
          { data: 'approvestatus', name: 'approvestatus' },
          { data: 'first_name', name: 'first_name' },
          { data: 'lvl1appr', name: 'lvl1appr' },
          { data: 'lvl2appr', name: 'lvl2appr' },



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



    //Approve1 function
    $(document).on('click', '.approve1-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('schemesapprovalcreate') }}/" + id;
      window.location.href = url;
    });

    //approve2 function
    $(document).on('click', '.approve2-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('schemeslevel2approvalcreate') }}/" + id;
      window.location.href = url;
    });

  </script>

@endpush