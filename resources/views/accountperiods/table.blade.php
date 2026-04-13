@extends('layouts.header')
@section('content')
<h3 class="text-danger">  Account Periods</h3>
@include('layouts.breadcrumb')
 <div id="toolbar-container" class="create mb-2 mt-1"></div>



<div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
        <table id="AccountsTbl" class="table table-bordered table-striped w-100">
            <thead>
                <tr class="table-warning">
                    <th>Period Name</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Period Status</th>
                    <th>Created By</th>
                    <th style="width: 15%;">Actions</th>
                </tr>
                <tr class="table-danger">
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
            <tbody></tbody>
        </table>
    </div>
</div>

@endsection
@push('scripts')

<script>

    // data table funcrion	
    $(document).ready(function () {
      var table = $('#AccountsTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getAccountperiodsData') }}",
        columns: [
            { data: 'period_name', name: 'm_company_t.period_name' },
            { data: 'month', name: 'month' },
            { data: 'year', name: 'year' },
            { data: 'from_date', name: 'from_date' },
            { data: 'to_date', name: 'to_date' },
            { data: 'period_status', name: 'period_status', visible:false },
            { data: 'first_name', name: 'first_name' },

          {
            data: 'account_period_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
        <button class="btn btn-sm btn-warning view-btn" data-id="${row.account_period_id}">
          <i class="bi bi-eye"></i>
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.account_period_id}">
          <i class="bi bi-pencil"></i>
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.account_period_id}">
          <i class="bi bi-trash"></i>
        </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#AccountsTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });
	

	
    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
              <button class="btn btn-success text-white px-4 create me-2">Create
                <i class="bi bi-plus-circle"></i> 
              </button>
            `);
      }
    });
	
// create
	
      $(".create").click(function(){
	var url="{{  URL::to('accountperiodscreate')}}";
	window.location.replace(url);
	});	
	
	
	
    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
		
      window.location.replace('accountperiodscreate/' +id);
    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('accountperiodsview') }}/" + id;
      window.location.href = url;
    });	
	
	

</script>

@endpush
