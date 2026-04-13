@extends('layouts.header')
@section('content')
<h3 class="text-danger">Account Codes</h3>
@include('layouts.breadcrumb')



<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body card-block">

        <div class="table-responsive">
            <table id="AccCodeTbl" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th>Account Class Name</th>
                        <th>Main Account Code</th>
                        <th>Description</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                    <tr class="table-danger">
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
</div>


@endsection
@push('scripts')

<script>
	
    // data table funcrion	
    $(document).ready(function () {
      var table = $('#AccCodeTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getAccountcodesnewData') }}",
        columns: [
          { data: 'account_class_name', name: 'account_class_name' },
          { data: 'main_account_code', name: 'main_account_code' },
          { data: 'description', name: 'description' },
          { data: 'active', name: 'active' },

          {
            data: 'account_class_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'adddata')) {
                buttons += `
        <button class="btn btn-sm btn-primary add-btn" data-id="${row.account_class_id}">
          Add
        </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#AccCodeTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });
	
	
// add data
	
 $(document).on('click', '.add-btn', function () {

	  const id = $(this).data('id');

		window.location.replace('accountcodesnewcreate/' +id+'/'+'0');

});
	
</script>

@endpush