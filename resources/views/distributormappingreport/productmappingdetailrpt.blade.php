@extends('layouts.header')
@section('content')
<h3 class="text-danger"> Product Mapping Details Report</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0 p-3 mb-4">
        <form method="post" action="" id="job_card_reprot" class="org_form" data-parsley-validate enctype="multipart/form-data">
            @csrf
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="row mb-3 align-items-center">
                        <label for="start_date" class="col-sm-4 col-form-label">From Date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control start_date1" id="start_date" name="start_date" required autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row mb-4 align-items-center">
                        <label for="end_date" class="col-sm-4 col-form-label">To Date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control end_date1" id="end_date" name="end_date" required autocomplete="off" required>
                        </div>
                    </div>
                </div>
          

            <div class="col-md-4 align-center">
        
                    <button type="button" class="btn btn-primary report_search px-4" id="report_search" value="SAVE"> <i class="bi bi-search me-1"></i>  Search</button>
                </div>
            </div>
			
        </form>
    </div>

  
<div class="card shadow-lg rounded-4 border-0 p-3 mb-4">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3"></div>
    <div class="table-responsive">
      <table id="ReportTbl" class="table table-striped table-bordered w-100">
        <thead>
          <tr class="table-warning">
            <th>Employee Number</th>
            <th>Employee Name</th>
            <th>Product Name</th>
            <th>Product Group Name</th>
            <th>Product Category Name</th>
            <th>Active</th>

          </tr>
          <tr class="table-success">
            <th><input type="text" placeholder="Search" /><span style="display: none;">Employee Number</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Employee Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Product Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Product Group Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Product Category Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Active</span></th>

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

    $('#ReportTbl').DataTable({
      processing: true,
      serverSide: false,
      ajax: {
        url: "{{ url('getproductmappingdetailrpt') }}",
        type: "GET",
        data: function(d) {
          d.start_date = $('#start_date').val();
		  d.end_date = $('#end_date').val();
        }
      },
      columns: [
        { data: 'employee_number'},
        { data: 'first_name'},
        { data: 'concatenated_product'},
        { data: 'group_name'},
        { data: 'category_name'},
        { data: 'active'}
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
