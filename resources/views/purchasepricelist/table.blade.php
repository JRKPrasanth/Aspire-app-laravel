@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
<?php  if($urlname=="purchasepricelist" ||$urlname=="purchasepricelistapproval" ) { ?>
	Purchase Pricelist
<?php }else if($urlname == "purchasepricelistcopy") { ?>
	Purchase Pricelist Copy
<?php }else if($urlname == "salespricelistcopy") { ?>
	Sales Pricelist Copy
<?php }else { ?>
	Sales Pricelist
<?php } ?>
</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3 mt-1"></div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="InvTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Pricelist Name</th>
            <th>Price List Type</th>
            <th>Description</th>
            <th>Status</th>
            <th>Active</th>
            <th>Created By</th>
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
      var table = $('#InvTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "getPurchasepricelistData/{{$index_data}}?pagemethod={{$urlname}}",
        columns: [
            { data: 'pricelist_name', name: 'pricelist_name' },
            { data: 'price_list_type', name: 'price_list_type' },
            { data: 'description', name: 'description' },
            { data: 'savestatus', name: 'savestatus' },
            { data: 'active', name: 'active' },
            { data: 'first_name', name: 'first_name' },

          {
            data: 'pricelist_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
        <button class="btn btn-sm btn-warning view-btn me-1" data-id="${row.pricelist_hdr_id}">
          <i class="bi bi-eye"></i>
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.pricelist_hdr_id}">
          <i class="bi bi-pencil"></i>
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.pricelist_hdr_id}" data-type="${row.price_list_type}">
          <i class="bi bi-trash"></i>
        </button>`;
              }
	   if (window.toolbarButtons?.some(btn => btn.attr.id === 'copypricelist')) {
                buttons += `
        <button class="btn btn-sm btn-primary copy-btn" data-id="${row.pricelist_hdr_id}">
          Copy
        </button>`;
              }	
			if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                buttons += `
        <button class="btn btn-sm btn-success approve-btn" data-id="${row.pricelist_hdr_id}">
          Approve
        </button>`;
              }		
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#InvTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });
	
	
    // create function
    $(".create").click(function () {
			<?php
	        if($urlname=="purchasepricelist")

			{?>
				var purchaseurl="{{  URL::to('purchasepricelistcreate')}}";
				   window.location.replace(purchaseurl);
		   <?php }
		     else
			{ ?>
		var salesurl="{{  URL::to('salespricelistcreate')}}";
		window.location.replace(salesurl);
		<?php } ?>
    });
    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
		    var url = '<?php echo $urlname.'edit'; ?>';
        var editUrl = url + '/' + id;
		    window.location.replace(editUrl);
    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
     var url= '<?php echo $urlname.'view'; ?>';
     var viewurl = url+'/'+id;
     window.location.replace(viewurl);
    });


    // delete function
    let deleteId = null;
	let type = null;
	
    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      const type = $(this).data('type');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('purchasepricelistdelete/destroy') }}/" + deleteId + "/" + type,
          type: "GET",
          success: function (data) {
            if (data == '0') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Deleted successfully!', 'success');
              $('#InvTbl').DataTable().ajax.reload();
            }
            if (data == '1') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert("You Can't delete , Used in SomeWhere.", 'error');
              $('#InvTbl').DataTable().ajax.reload();
            }
          },
          error: function (xhr) {
            $('#globalDeleteModal').modal('hide');
            const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
            showCustomAlert(errorMsg, 'error');
          }
        });
      }
    });	
	
	
//
    $(document).on('click', '.copy-btn', function () {
		
      const id = $(this).data('id');
		
		var url = '<?php echo $urlname.'edit'; ?>';
        var editUrl = url + '/' + id;
        window.location.replace(editUrl);

});	
	
    //approve function
    $(document).on('click', '.approve-btn', function () {
      const id = $(this).data('id');
		    var url = '<?php echo $urlname.'edit'; ?>';
        var editUrl = url + '/' + id;
		    window.location.replace(editUrl);
    });
	
</script>

@endpush
