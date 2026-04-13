@extends('layouts.header')
@section('content')
<h3 class="text-danger">
<?php if($pageMethod=="batchconversion"){?>
Batch Conversion
<?php }else{?>
Batch Conversion Approval
<?php } ?>
</h3>
@include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="BatchTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Conversion Number</th>
            <th>Date</th>
            <th>Type</th>
            <th>Status</th>
            <th>Created By</th>
            <th>SubInventory Name</th>
            <th>Locator</th>
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
	
    // data table funcrion	

    var pageurl='<?php echo $pageMethod; ?>';

    $(document).ready(function () {
      var table = $('#BatchTbl').DataTable({
        processing: true,
        serverSide: true,
        order: [[1, 'desc']],
        ajax:  "getconversionData?pageurl="+pageurl,
        columns: [
            { data: 'bc_number', name: 'bc_number' },
            { data: 'convert_date', name: 'convert_date' },
            { data: 'type', name: 'type' },
            { data: 'status', name: 'status' },
            { data: 'first_name', name: 'first_name' },
            { data: 'subinventory_name', name: 'subinventory_name' },
            { data: 'locator_name', name: 'locator_name' },

          {
            data: 'id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                buttons += `
        <button class="btn btn-sm btn-success approve-btn" data-id="${row.id}" data-status="${row.status}">
         Approve
        </button>`;
              }

              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#BatchTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });	
	
	// approve
    $(document).on('click', '.approve-btn', function () {

         const id = $(this).data('id');
         const status = $(this).data('status');

		if(status=="INITIATED")
		{
			
		var url = "{{ URL::to('approveconversion') }}";
        var editUrl = url + '?approval=' + id;
		window.location.replace(editUrl);
	}else{
		showCustomAlert("Please Select INITIATED Records.","warning");
	}
    });
  </script>

@endpush
