@extends('layouts.header')
@section('content')
<h3 class="text-danger">Purchase Order</h3>
@include('layouts.breadcrumb')
<a href="{{url('srn')}}"><button class="btn btn-danger px-4 mt-2" > Cancel</button></a>
  
<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="SrnTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

            <th>Actions</th>
            <th>PO Date</th>
            <th>PO Number</th>
            <th>PO Type</th>
            <th>Supplier Name</th>
            <th>Grand Total</th>
            <th>Remarks</th>
          </tr>

          <tr class="table-info">

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

	  // table data

  $(document).ready(function () {

    var table = $('#SrnTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "getPurchaselabourData",

      columns: [

        {

          data: 'po_hdr_id',
          name: 'actions',
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
              return `
					<button type="button" class="btn btn-sm btn-primary generate_srns"
					  data-id="${row.po_hdr_id}">
				    Generate
					</button>`;
          }

        },

        { data: 'po_date', name: 'po_date' },
        { data: 'po_number', name: 'po_number' },
        { data: 'po_type', name: 'po_type' },
         { data: 'supplier_name', name: 'supplier_name' },
        { data: 'po_grand_total', name: 'po_grand_total' },
        { data: 'remarks', name: 'remarks' },
      ]
    });


    $('#SrnTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });	
	
//create
$(document).on('click', '.generate_srns', function () {
	
  const id = $(this).data('id');
	
	  var url="{{ URL::to('genratesrn') }}/"+id+'/2';
      window.location.href = url;
	
});		
	
	
</script>

@endpush
