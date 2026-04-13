@extends('layouts.header')
@section('content')
<h3 class="text-danger">Machine Details report</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">

<div class="card-body card-block">
  <form method="post" action="" id="job_card_reprot" class="org_form" data-parsley-validate enctype="multipart/form-data">
 
{{ csrf_field() }}
   
	  
  
<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Machine Name</label>
            <div class="col-md-6">
                  <select id="machine_id" name='machine_id' rows='5'  class='form-control machine_id select2' tabindex="1" data-show-subtext="true" data-live-search="true" required>
                    {!! $machine_id !!}
            </select>
            </div>
        </div>
    </div>
  

            <div class="col-md-2">
                <div class="form-group text-center">
  <button type="button" class="btn btn-success report_search" id="report_search" value="SAVE">Search</button>
               </div>
            </div>
    </form>
</div>
	
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3"></div>
    <div class="table-responsive">
      <table id="ReportTbl" class="table table-striped table-bordered">
        <thead>
          <tr class="table-warning">
            <th>Machine Code</th>
            <th>Machine Name</th>
            <th>Electricity Cost</th>
            <th>Assigned To</th>
            <th>Product Type</th>
            <th>Machine Capacity</th>

          </tr>
          <tr class="table-success">
            <th><input type="text" placeholder="Search" /><span style="display: none;">Machine Code</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Machine Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Electricity Cost</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Assigned To</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Product Type</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Machine Capacity</span></th>

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
        url: "{{ url('mainmachinedetails') }}",
        type: "GET",
        data: function(d) {
		  d.machine_id = $('#machine_id').val();
        }
      },
      columns: [
        { data: 'machine_code'},
        { data: 'machine_name'},
        { data: 'electricity_cost'},
        { data: 'assigned_to'},
        { data: 'product_type'},
        { data: 'machine_capacity'},
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
