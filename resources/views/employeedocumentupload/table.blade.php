@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee Document Check And Print</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="DataTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Employee Number</th>
            <th>Employee Name</th>
            <th>Contact Number</th>
            <th>E-mail</th>
            <th>Active</th>
            <th>Actions</th>
          </tr>
          <tr class="table-info">
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


    // data table funcrion	
    $(document).ready(function () {
      var table = $('#DataTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('employeedocumentgriddata') }}",
        columns: [

          { data: 'employee_number', name: 'employee_number' },
          { data: 'first_name', name: 'first_name' },
          { data: 'work_telephone_number', name: 'work_telephone_number' },
          { data: 'email', name: 'email' },
          { data: 'active', name: 'active' },

          {
            data: 'docid',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'doc_check')) {
                buttons += `<button class="btn btn-sm btn-primary me-1 check-btn" 
                  data-id="${row.docid}" 
                  data-bs-toggle="tooltip" 
                  data-bs-placement="top" 
                  title="Document Check">
              <i class="bi bi-person-fill-check"></i>
            </button>`;
              }

              buttons += `
            <button class="btn btn-sm btn-warning view-btn" data-id="${row.docid}">
              <i class="bi bi-eye"></i>
            </button>`;

              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#DataTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    $(document).on('click', '.check-btn', function () {

      var id = $(this).data('id');
      if (id == "") {
        id = 0;
      }
      var emp = $('.employeeid').val();
      var document_check = "{{ URL::to('documentcreate') }}/" + id + "?empid=" + emp;
      window.location.href = document_check;
    });



    $(document).on('click', '.view-btn', function () {

      var id = $(this).data('id');
      if (id != '') {

        window.location.replace('employeedocummentview/' + id);

      } else {
        showcustomAlert("Please Select Saved records", "info");
      }
    });


  </script>

@endpush