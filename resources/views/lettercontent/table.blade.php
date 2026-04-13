@extends('layouts.header')
@section('content')
<h3 class="text-danger">Letter Content</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3"></div>


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="PosTbl" class="table table-bordered table-striped w-100">
        <thead>
  <tr class="table-warning">
      <th>Letter type</th>
      <th>Active</th>
      <th>Actions</th>
  </tr>
  <tr class="table-info">
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

    var table = $('#PosTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('lettercontentgriddata') }}",
      columns: [
        { data: 'lookup_meaning', name: 'a_lookuplines_t.lookup_meaning' },
        { data: 'active', name: 'active' },

        {
          data: 'id',
          name: 'actions',
          orderable: false,
          searchable: false,
		      className: 'text-center',
          width: '140px', 
        render: function (data, type, row) {
            let buttons = '';
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                    buttons += `
				<button type="button" class="btn btn-sm btn-primary edit-btn"
				  data-id="${row.id}">
				  <i class="bi bi-pencil"></i>
				</button>`;
            }
            return buttons;
          }
        }
      ]
    });
  
    // Individual column search
    $('#PosTbl thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });



// create function
	 $(".create").click(function()
{
  var url="{{ URL::to('lettercontentcreate')}}";
    window.location.replace(url);
});

	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('lettercontentedit') }}/" + id;
    window.location.href = url;
    });	
	
// Add create button purpose
	      $(document).ready(function () {
        if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
          $('#toolbar-container').append(`
            <button class="btn btn-primary create me-2">Create
              <i class="bi bi-plus-circle"></i> 
            </button>
          `);
        }
      });	
	

</script>

@endpush
