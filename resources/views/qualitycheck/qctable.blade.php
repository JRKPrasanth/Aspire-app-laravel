@extends('layouts.header')
@section('content')
<h3 class="text-danger">Quality Check</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="QcTblview" class="table table-bordered table-striped w-100">
        <thead>
      <tr class="table-warning">
      <th>Reference No</th>
      <th>QA Status</th>
      <th>Job No</th>
      <th>Batch No</th>
      <th>Product Code</th>
      <th>Product</th>
      <th>Production Qty</th>
      <th>Remarks</th>
      <th>Actions</th>
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
    <th></th>
  </tr>
</thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

@endsection
@push('scripts')

<script>
	
		$(document).ready(function() {

    var status="{{ $status }}";
    var pageMethod="{{ $pageMethod }}";
    var url = "qualitycheckData?status="+status+"&pageMethod="+pageMethod;
    var table = $('#QcTblview').DataTable({
      processing: true,
      serverSide: true,
      order: [[0, 'desc']],
      ajax: url,
      columns: [
        { data: 'reference_no', name: 'reference_no' },
        { data: 'status', name: 'status' },
        { data: 'job_no', name: 'job_no' },
        { data: 'batch_no', name: 'batch_no' },
        { data: 'product_code', name: 'product_code' },
        { data: 'concatenated_product', name: 'concatenated_product' },
        { data: 'production_qty', name: 'production_qty' },
        { data: 'remarks', name: 'remarks' },

        {
          data: 'quality_spec_trx_hdr_id',
          name: 'actions',
          orderable: false,
          searchable: false,
		      className: 'text-center',
          width: '140px', 
        render: function (data, type, row) {
            let buttons = '';

            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                    buttons += `
					<button type="button" class="btn btn-sm btn-warning view-btn"
					  data-id="${row.quality_spec_trx_hdr_id}">
					  <i class="bi bi-eye"></i>
					</button>`;
            }
			       if (window.toolbarButtons?.some(btn => btn.attr.id === 'print')) {
                    buttons += `
					<button type="button" class="btn btn-sm btn-primary printer"
					  data-id="${row.quality_spec_trx_hdr_id}" data-status="${row.status}">
					  <i class="bi bi-printer"></i>
					</button>`;
            }
            return buttons;
          }
        }
      ]
    });
  
    // Individual column search
    $('#QcTblview thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).closest('th').index();
      table.column(colIndex).search(this.value).draw();
    });
  });
	
// view function
	

	$(document).on('click', '.view-btn', function () {

		cellValue = $(this).data('id'); 
        var url="{{URL::to('qualitycheckview')}}/";
        window.location.replace(url+cellValue);


	});	
	
// print function
	
		$(document).on('click', '.printer', function () {


		qualityhdr = $(this).data('id'); 
        var cellValue = $(this).data('status'); 

        if(cellValue=="APPROVED"){
            window.open("{{URL::to('qualitycheckprint')}}/" +qualityhdr,'_blank');
        }else{
            showCustomAlert("Please Approve  Quality Check","warning");
        }
	});

	
</script>

@endpush
