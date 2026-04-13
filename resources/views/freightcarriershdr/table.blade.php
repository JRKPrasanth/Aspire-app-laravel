@extends('layouts.header')
@section('content')
<h3 class="text-danger">Freight Carriers</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3 mt-2"></div>


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="FreightTbl" class="table table-bordered table-striped w-100">
        <thead>
    <tr class="table-warning">
      <th>Carrier Name</th>
      <th>Source Type</th>
      <th>Currency</th>
      <th>Location</th>
      <th>Active</th>
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

        <tbody></tbody>
      </table>
    </div>
  </div>
</div>


@endsection
@push('scripts')


<script>
	
// table data
	$(document).ready(function() {

    var table = $('#FreightTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "getfreightcarrierData/{{$index_data}}",
      columns: [
        { data: 'carrier_name', name: 'carrier_name' },
        { data: 'source_type_id', name: 'source_type_id' },
        { data: 'currency_code', name: 'currency_code' },
        { data: 'location_name', name: 'location_name' },
        { data: 'active', name: 'active' },

        {
          data: 'ar_frieghtcarriers_hdr_id',
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
					  data-id="${row.ar_frieghtcarriers_hdr_id}"
					  data-source="${row.source_type_id}">
					  <i class="bi bi-pencil"></i>
					</button>`;
            }
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                    buttons += `
					<button type="button" class="btn btn-sm btn-warning view-btn"
					  data-id="${row.ar_frieghtcarriers_hdr_id}">
					  <i class="bi bi-eye"></i>
					</button>`;
            }
           if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                    buttons += `
					<button type="button" class="btn btn-sm btn-danger delete-btn"
					  data-id="${row.ar_frieghtcarriers_hdr_id}">
					  <i class="bi bi-trash"></i>
					</button>`;
            } 
            return buttons;
          }
        }
      ]
    });
  
    // Individual column search
    $('#FreightTbl thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
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
	
	
	
// create function
	 $(".create").click(function()
{
		 
	var pageMethod = "<?php echo $pageMethod; ?>";
	
	if(pageMethod =="freightcarriershdr"){
    var url="{{ URL::to('freightcarriershdrcreate/0')}}";
	}else{
		var url="{{ URL::to('purchasefreightcarriershdrcreate/0')}}";
	}
		 
    window.location.replace(url);
});
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const source = $(this).data('source');
    var type = "<?php echo $urlname ?>";

    var url ="{{ URL::to('freightcarnamechk') }}/" +id; 

 $.get(url,function(data)
        { 
          var frieghtcount=$.trim(data);
             if(frieghtcount =='0')
          { 
            var url = "{{ url('freightcarriershdredit') }}/"+id+"/"+source;
            var url_pur = "{{ url('purchasefreightcarriershdredit') }}/"+id+"/"+source;
                    if(type == "purchasefreightcarriershdr" )
                    {    
                      window.location.replace(url_pur);
                    }
                    else
                    {
                       window.location.replace(url);
                    }
          }
           else
           {
               var url = "{{ url('freightcarriershdredit') }}/"+id+"/"+source+"?status=edit";
            var url_pur = "{{ url('purchasefreightcarriershdredit') }}/"+id+"/"+source+"?status=edit";
                  
                    if(type == "purchasefreightcarriershdr" )
                    {    
                      window.location.replace(url_pur);
                    }
                    else
                    {
                       window.location.replace(url);
                    }
           }
        });
    });
	
	//view function
$(document).on('click', '.view-btn', function () {
  const id = $(this).data('id');
  var type = "<?php echo $urlname ?>";

    var url = "{{ url('freightcarriershdrview') }}/";
    var url_pur = "{{ url('purchasefreightcarriershdrview') }}/";

        if(type == "purchasefreightcarriershdr" ){
      window.location.replace(url_pur+id);
        }else{
      window.location.replace(url +id);
	}


});

	// delete function
	let deleteId = null; 

	$(document).on('click', '.delete-btn', function () {
		deleteId = $(this).data('id');
         const source = $(this).data('source');
		$('#globalDeleteModal').modal('show'); 
	});

	$('#globalConfirmDeleteBtn').on('click', function () {
	  	deleteId = $(this).data('id');
		 const source = $(this).data('source');
		if (deleteId) {
				$.ajax({
					url: "{{ url('freightcarriershdrdelete') }}/" +deleteId+"/"+source, 
					type: "GET",
					success: function (data) {

                            if(data =='0')
                        {
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Deleted successfully!', 'success');
						$('#FreightTbl').DataTable().ajax.reload();
                        }
                        if(data =='1')
                        {
						$('#globalDeleteModal').modal('hide');
						showCustomAlert("You Can't delete  Used in SomeWhere", 'info');
						$('#FreightTbl').DataTable().ajax.reload();
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
	
	
	

	
</script>

@endpush
