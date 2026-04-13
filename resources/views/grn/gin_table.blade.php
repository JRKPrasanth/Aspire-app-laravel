@extends('layouts.header')
@section('content')
<h3 class="text-danger">Goods Inward Note</h3>
@include('layouts.breadcrumb')
<a href="{{url('grn')}}"><button class="btn btn-danger px-4 mt-2" > Cancel</button></a>


<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="PurorderTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

            <th>Actions</th>
            <th>GIN Number</th>
            <th>Supplier Name</th>
            <th>DC Number</th>
            <th>DC Date</th>
            <th>Total Packs</th>
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

    var table = $('#PurorderTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "goodsinwardData",

      columns: [

        {

          data: 'p_gin_hdr_id',
          name: 'actions',
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
              return `
					<button type="button" class="btn btn-sm btn-primary generate_grns"
					  data-id="${row.p_gin_hdr_id}">
					 Generate
					</button>`;
          }

        },

        { data: 'gin_number', name: 'gin_number' },
        { data: 'supplier_name', name: 'supplier_name' },
        { data: 'dc_number', name: 'dc_number' },
        { data: 'dc_date', name: 'dc_date' },
        { data: 'total_packs', name: 'total_packs' },
      ]
    });


    $('#PurorderTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });	
	
	
// generate grn 
	
$(document).on('click', '.generate_grns', function () {
	
  const id = $(this).data('id');
	
	  var url="{{ URL::to('genrategrns') }}/"+id+'/2';
      window.location.href = url;
	
});	
	
</script>


@endpush
