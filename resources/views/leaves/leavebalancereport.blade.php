@extends('layouts.header')
@section('content')
<h3 class="text-danger">Leave Balance Report</h3>
@include('layouts.breadcrumb')

   <div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3"></div>
    <div class="table-responsive">
      <table id="ReportTbl" class="table table-striped table-bordered">
        <thead>
          <tr class="table-warning">
<th>
                
                Employee Number
            </th>
            <th class="freeze">
                
                Employee Name
            </th>
            <th>
                
                Employee Type
            </th>
            <th>
                
                Opening CL
            </th>
            <th>
                
                CL Taken
            </th>
            <th>
                
                Remaining CL
            </th>
            <th>
                
                Opening EL Balance
            </th>
            <th>
                
                EL Taken
            </th>
            <th>
                
                Remaining EL
            </th>
            <th>
                
                Comp-Off Leave Balance
            </th>


          </tr>
          <tr class="table-danger">
            <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Employee Number</span>
            </th>
            <th class="freeze">
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Employee Name</span>
            </th>
            <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Employee Type</span>
            </th>
            <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Opening CL</span>
            </th>
            <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">CL Taken</span>
            </th>
            <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Remaining CL</span>
            </th>
            <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Opening EL Balance</span>
            </th>
            <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">EL Taken</span>
            </th>
            <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Remaining EL</span>
            </th>
            <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Comp-Off Leave Balance</span>
            </th>


          </tr>
        </thead>
        <tbody>
          <!-- Your dynamic row data goes here -->
        </tbody>
      </table>
    </div>
  </div>
</div>


@endsection
@push('scripts')


<script>


		$(document).ready(function () {

       var table = $('#ReportTbl').DataTable({
      processing: true,
      serverSide: false,
      ajax: {
        url: "{{ url('getleavebalancegrid') }}",
        type: "GET",
      },
      columns: [
    { data: 'employee_number' },
    { class:'freeze', data: 'first_name' },
    { data: 'lookup_code' },
    { data: 'ocl' },
    { data: 'cl_taken' },
    { data: 'causal_leave' },
    { data: 'oel' },
    { data: 'el_taken' },
    { data: 'earn_leave' },
    { data: 'comp_off_leave' }
    
      ]

    });
    
    // Trigger search
    $('.report_search').on('click', function () {
      $('#ReportTbl').DataTable().ajax.reload();
    });
      
      
        // Column search
        $('#ReportTbl thead').on('keyup change', ".column-search", function () {
          var index = $(this).closest('th').index();
          table.column(index).search(this.value).draw();
        });
      });	
  
  </script>

@endpush
