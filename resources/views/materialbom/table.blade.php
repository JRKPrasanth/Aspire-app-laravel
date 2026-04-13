@extends('layouts.header')
@section('content')
<h3 class="text-danger">
<?php if($pageMethod=='materialbomapproval') { ?>
Product Bom Approval
<?php } else { ?>
Product Bom
<?php } ?>
</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3 mt-2"></div>


<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="BomTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

                <th>Actions</th>
   
                <th>Product Code</th>
                <th>Product</th>
                <th>Uom Code</th>
                <th>Project</th>
                <th>BOM Status</th>
                <th>Created By</th>


          </tr>

          <tr class="table-info">
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
  var url="{{ URL::to('materialbomcreate')}}";
    window.location.replace(url);
});		
	
	
  // table data

  $(document).ready(function () {

    var table = $('#BomTbl').DataTable({
      processing: true,
      serverSide: true,
      orderable: true,   
      order: [[0, 'desc']], 
      ajax: "materialbomData?pagemethod={{ $pageMethod }}",

      columns: [

        {

          data: 'material_bom_hdr_id',
          name: 'actions',
          orderable: false,
          searchable: false,
                render: function (data, type, row) {
                    if (type === 'sort' || type === 'type') {
                            return data; // IMPORTANT: return numeric id for sorting
                            }

                    let buttons = '';

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-primary edit-btn"
                                data-id="${row.material_bom_hdr_id}">
                                <i class="bi bi-pencil"></i>
                            </button>`;
                    }

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-warning view-btn"
                                data-id="${row.material_bom_hdr_id}">
                                <i class="bi bi-eye"></i>
                            </button>`;
                    }


                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-success approve-btn"
                                data-id="${row.material_bom_hdr_id}">
                               <i class="bi bi-check2-circle"></i> Approve
                            </button>`;
                    }
					
                    return buttons;
                },

        },
        
        { data: 'product_code', name: 'product_code' },
        { data: 'concatenated_product', name: 'concatenated_product' },
        { data: 'uom_code', name: 'uom_code' },
        { data: 'project_name', name: 'project_name' },
        { data: 'savestatus', name: 'savestatus' },
        { data: 'first_name', name: 'tb_users.first_name' },


      ]
    });


    $('#BomTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });	
	
	
	
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('materialbomedit') }}/" + id;
    window.location.href = url;
    });	
	
	
	//view function
$(document).on('click', '.view-btn', function () {
  const id = $(this).data('id');
  const url = "{{ url('materialbomview') }}/" + id;
  window.location.href = url;
});	
		
	
//approve	


	$(document).on('click', '.approve-btn', function () {
	
		  const id = $(this).data('id');
		
          window.location.replace('materialbomapprovalcreate/'+id);
            
        });

	
	
</script>

@endpush
