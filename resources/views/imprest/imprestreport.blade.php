@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Imprest Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="ImpTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">


            <th>Employee Name</th>
            <th>Reporting Employee</th>
            <th>Department</th>
            <th>Imprest Date</th>
            <th>Reason</th>
            <th>Amount</th>
            <th>Status</th>


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

    // table data	


    $(document).ready(function () {

      var table = $('#ImpTbl').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ url('imprestreportData') }}",

        columns: [

          { data: 'employee_name', name: 'employee_name' },
          { data: 'reporting_name', name: 'reporting_name' },
          { data: 'department', name: 'department' },
          { data: 'imprest_date', name: 'imprest_date' },
          { data: 'reason', name: 'reason' },
          { data: 'amount', name: 'amount' },
          { data: 'status', name: 'status' }


        ]

      });


      // Column search
      $('#ImpTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });
  </script>


@endpush