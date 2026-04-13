@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee Document Upload Report</h3>
  @include('layouts.breadcrumb')



  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="RptTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>Employee Number</th>
              <th class="freeze">Employee Name</th>
              <th>Active</th>
              <th>Zone</th>
              <th>Relieve Date</th>
              <th>Company Issued Docs</th>
              <th>Employee Issued Docs</th>
              <th>Attached Docs</th>
              <th>Created User</th>
              <th>Created On</th>
              <th>Updated On</th>

            </tr>
            <tr class="table-danger">

              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Number</span></th>
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Employee Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Active</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Zone</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Relieve
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Company Issued
                  Docs</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Issued Docs</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Attached
                  Docs</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Created
                  User</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Created
                  On</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Updated
                  On</span></th>

            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

@endsection
@push('scripts')


  <script>

    $(document).ready(function () {

      var table = $('#RptTbl').DataTable({
        processing: true,
        serverSide: false,
        order: [[4, 'desc']],
        scrollX: true,
        scrollY: "50vh",
        ajax: {
          url: "{{ url('getemployeedocument') }}",
          type: "GET",
        },
        columns: [

          { data: "employee_number" },
          { class: "freeze", data: "first_name" },
          { data: "active" },
          { data: "zone_id" },
          { data: "date_of_leaving" },
          { data: "co_doc_name" },
          { data: "emp_doc_name" },
          { data: "docs_col" },
          { data: "creaed_by" },
          { data: "created_on" },
          { data: "last_update" }

        ]

      });



      // Column search
      $('#RptTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });


  </script>

@endpush