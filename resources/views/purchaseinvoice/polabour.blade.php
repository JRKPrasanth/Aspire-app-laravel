@extends('layouts.header')
@section('content')
<h3 class="text-danger"> Purchase Order </h3>
@include('layouts.breadcrumb')
<a href="{{url('purchaseinvoice')}}"><button class="btn btn-danger px-4 mt-2" > Cancel</button></a>
  

<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="purTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

            <th>Actions</th>
            <th>PO Number</th>
            <th>Source</th>
            <th>PO Date</th>
            <th>Supplier Name</th>
            <th>GRN Status</th>
            <th>DC Number</th>
            <th>GRN Number</th>

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

   var suppliername = "{{$suppliername}}";
   var pagemethod ="{{$pageMethod}}";

    var table = $('#purTbl').DataTable({
      processing: true,
      serverSide: true,
      order: [[3, 'desc']],
      ajax: "getPolabourData",

      columns: [

        {

          data: 'grn_id',
          name: 'actions',
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
              return `
				<button type="button" class="btn btn-sm btn-primary text-white create-btn"
				  data-id="${row.grn_id}">
				Invoice
				</button>`;
          }

        },

        { data: 'po_number', name: 'po_number' },
        { data: 'source', name: 'source' },
        { data: 'po_date', name: 'po_date' },
         { data: 'supplier_name', name: 'supplier_name' },
        { data: 'grn_status', name: 'grn_status' },
        { data: 'dc_number', name: 'dc_number' },
        { data: 'grn_number', name: 'grn_number' },


      ]
    });


    $('#purTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });
	
// create
$(document).on('click', '.create-btn', function () {
	
  const id = $(this).data('id');
	
	  var url="{{ URL::to('createpolabourinvoice') }}/"+id;
      window.location.href = url;
	
});	
	

	
</script>

@endpush