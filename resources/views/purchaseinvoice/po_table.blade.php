@extends('layouts.header')
@section('content')
<h3 class="text-danger">Goods Receipt Note(GRN)</h3>
@include('layouts.breadcrumb')
<a href="{{url('purchaseinvoice')}}"><button class="btn btn-danger px-4 mt-2" > Cancel</button></a>


<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="grnTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

            <th>Actions</th>
            <th>GRN Number</th>
            <th>GRN Status</th>
            <th>DC Number</th>
            <th>DC Date</th>
            <th>PO Number</th>
            <th>PO Date</th>
            <th>Supplier Type</th>
            <th>Supplier Name</th>
            <th>Subcontract Name</th>
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

   var status ="{{$status}}";
   var pagemethod ="{{$pageMethod}}";

    var table = $('#grnTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "poData",
      order: [[0, 'desc']],
      columns: [

        {

          data: 'grn_id',
          name: 'actions',
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
              return `
				<button type="button" class="btn btn-sm btn-primary create-btn"
				  data-id="${row.grn_id}">
				Create Po
				</button>`;
          }

        },

        { data: 'grn_number', name: 'grn_number' },
        { data: 'grn_status', name: 'grn_status' },
        { data: 'dc_number', name: 'dc_number' },
         { data: 'dc_date', name: 'dc_date' },
        { data: 'po_number', name: 'po_number' },
        { data: 'po_date', name: 'po_date' },
        { data: 'supplier_type', name: 'supplier_type' },
        { data: 'supplier_name', name: 'supplier_name' },
        { data: 'subcontract_name', name: 'subcontract_name' },

      ]
    });


    $('#grnTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });
	
// create
$(document).on('click', '.create-btn', function () {
	
  const id = $(this).data('id');
	
	  var url="{{ URL::to('createpoinvoice') }}/"+id;
      window.location.href = url;
	
});		
	
	

</script>

@endpush
