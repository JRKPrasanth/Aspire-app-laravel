@extends('layouts.header')
@section('content')
<h3 class="text-danger">Machine Capacity</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3 mt-2"></div>

<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="mactbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

                <th>Actions</th>
                <th>Machine Name</th>
                <th>Created By</th>
          </tr>

          <tr class="table-info">

            <th></th>
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
	
	
// create function
	 $(".create").click(function()
{
  var url="{{ URL::to('materialequipmentscreate')}}";
    window.location.replace(url);
});	
	
	  // table data

  $(document).ready(function () {

    var table = $('#mactbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "materialequipmentsData",

      columns: [

        {

          data: 'machine_equipments_hdr_id',
          name: 'actions',
          orderable: false,
          searchable: false,
                render: function (data, type, row) {
                    let buttons = '';

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-primary edit-btn"
                                data-id="${row.machine_equipments_hdr_id}">
                                <i class="bi bi-pencil"></i>
                            </button>`;
                    }

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-warning view-btn"
                                data-id="${row.machine_equipments_hdr_id}">
                                <i class="bi bi-eye"></i>
                            </button>`;
                    }
                    return buttons;
                },

        },

        { data: 'machine_name', name: 'machine_name', className: 'text-center' },
        { data: 'first_name', name: 'tb_users.first_name', className: 'text-center' },

      ]
    });


    $('#mactbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });	
	
	
	
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('materialequipmentsedit') }}/" + id;
    window.location.href = url;
    });	
	
	
	//view function
$(document).on('click', '.view-btn', function () {
  const id = $(this).data('id');
  const url = "{{ url('materialequipmentsview') }}/" + id;
  window.location.href = url;
});		
	

</script>

@endpush
