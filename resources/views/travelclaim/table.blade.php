@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Travel Claim Request</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3"></div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="PosTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th>Employee Name</th>
              <th>Claim Title</th>
              <th>Claim Date</th>
              <th>Description</th>
              <th>Travel Purpose</th>
              <th>Approve by</th>
              <th>Claim Status</th>

            </tr>
            <tr class="table-info">
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Claim
                  Title</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Claim
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Description</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Travel
                  Purpose</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Approve
                  by</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Claim
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;"></span></th>
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

      var table = $('#PosTbl').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollY: "50vh",
        ajax: "{{ route('travelclaimgriddata') }}",
        columns: [
          {
            data: 'travel_claim_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-primary edit-btn"
              data-id="${row.travel_claim_id}">
              <i class="bi bi-pencil"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
              <button type="button" class="btn btn-sm btn-danger delete-btn"
                data-id="${row.travel_claim_id}">
                <i class="bi bi-trash"></i>
              </button>`;
              }
              return buttons;
            }
          },
          { data: "employee_name", name: "employee_name" },
          { data: "claim_title", name: "claim_title" },
          { data: "travel_date", name: "travel_date" },
          { data: "description", name: "description" },
          { data: "travel_purpose", name: "travel_purpose" },
          { data: "reporting_name", name: "reporting_name" },
          { data: "approved_status", name: "approved_status" },


        ]
      });

      // Individual column search
      $('#PosTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });



    // create function
    $(".create").click(function () {
      var url = "{{ URL::to('requestclaim/0')}}";
      window.location.replace(url);
    });
    //edit function
    $(document).on('click', '.edit-btn', function () {

      var table = $('#PosTbl').DataTable();
      var tr = $(this).closest('tr');
      var rowData = table.row(tr).data();
      const id = $(this).data('id');

      var approved_status = rowData.approved_status;



      if (approved_status != "Approved") {
        var url = "{{URL::to('requestclaim')}}/" + id;
        window.location.href = url;

      } else {

        showCustomAlert('Approved Status Not Able to Edit', 'error');

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
          url: "{{ url('travelclaim/delete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Deleted successfully!', 'success');
            $('#PosTbl').DataTable().ajax.reload();

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
                <button class="btn btn-primary create me-2">Create
                  <i class="bi bi-plus-circle"></i> 
                </button>
              `);
      }
    });


  </script>

@endpush