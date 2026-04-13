@extends('layouts.header')
@section('content')
<h3 class="text-danger"> Production Indent Status </h3>
@include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="IndentTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Indent Number</th>
            <th>Indent Date</th>
            <th>Requestor Name</th>
            <th>Indent Status</th>
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
    $(document).ready(function () {
      var table = $('#IndentTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getProductionindentData') }}",
        columns: [
          { data: 'indent_no', name: 'indent_no' },
          { data: 'indent_date', name: 'indent_date' },
          { data: 'first_name', name: 'first_name' },
          { data: 'indent_status', name: 'indent_status' },
		  { data: 'remarks', name: 'remarks' },
			
          {
            data: 'w_requisition_indent_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
        <button class="btn btn-sm btn-warning view-btn" data-id="${row.w_requisition_indent_hdr_id}">
          <i class="bi bi-eye"></i>
        </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#IndentTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });	
	
	
      //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('productionindentview') }}/" + id;
      window.location.href = url;
    });
	
	

	
</script>

@endpush
