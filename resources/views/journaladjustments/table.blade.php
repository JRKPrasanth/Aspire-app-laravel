@extends('layouts.header')
@section('content')
<h3 class="text-danger">Adjustments</h3>
@include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3 mt-1"></div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
			<th>Adjustment Date</th>
			<th>Account Type</th>
			<th>Adjustment Status</th>
			<th>Adjustment Amount</th>
			<th>Reason Code</th>
			<th>Account Code</th>
           <th>Actions</th>
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

    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
              <button class="btn btn-primary text-white px-4 create me-2">Create
                <i class="bi bi-plus-circle"></i> 
              </button>
            `);
      }
    });
	
    // data table funcrion	
    $(document).ready(function () {


      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "getJournalAdjustmentsData",
		    order: [[2, 'desc']],
        columns: [

		{ data: 'adjustment_date', name: 'adjustment_date' },
		{ data: 'account_type', name: 'account_type' },
		{ data: 'adjustment_status', name: 'adjustment_status' },
		{ data: 'adjustment_amount', name: 'adjustment_amount' },
		{ data: 'reason_code', name: 'reason_code' },
		{ data: 'concatenated_segments', name: 'concatenated_segments' },

    {
            data: 'adjustment_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
				
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
        <button class="btn btn-sm btn-warning view-btn" data-id="${row.adjustment_id}">
          <i class="bi bi-eye"></i>
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.adjustment_id}">
          <i class="bi bi-pencil"></i>
        </button>`;
              }

			 if (window.toolbarButtons?.some(btn => btn.attr.id === 'approvalview')) {
                buttons += `
        <button class="btn btn-sm btn-success app-btn" data-id="${row.adjustment_id}">
         Approve
        </button>`;
              }
				

              return buttons;
            }
          }

        ]
      });

      // Individual column search
      $('#AccTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });	
	
	
    // create function
    $(".create").click(function () {
      var url = "{{ URL::to('journaladjustmentscreate')}}";
      window.location.replace(url);
    });
	
    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('journaladjustmentscreate') }}/" + id;
      window.location.href = url;
    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('journaladjustmentsview') }}/" + id;
      window.location.href = url;
    });	
	
	
// Approve

     $(document).on('click', '.app-btn', function () {
		 
		const id = $(this).data('id');

		window.location.replace('adjustmentsapprovalview/' +id);

  });	

	
</script>

@endpush
