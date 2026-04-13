@extends('layouts.header')
@section('content')
<h3 class="text-danger">
<?php if($pageMethod=="quickcustomer"){ ?>
	Quick Customers
<?php 	} else{ ?>
	Customers <?php } ?>
</h3>
@include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3 mt-1"></div>
<style>
.select2-container--open { z-index: 200000 !important; }
</style>	


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="CustomerTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Customer Number</th>
            <th>Customer Name</th>
            <th>Customer Type</th>
            <th>Active</th>
            <th>Status</th>
            <th>Created By</th>
            <th style="width: 16%;">Actions</th>
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


<div class="modal fade" id="schemesModal" tabindex="-1" aria-labelledby="schemesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4 border-0">
      
      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-semibold" id="schemesModalLabel">Customer Schemes Update</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <div class="d-flex justify-content-between mb-3">
          <div><strong>Customer Name:</strong> <span class="cname"></span></div>
          <div><strong>Customer Status:</strong> <span class="cstatus"></span></div>
        </div>

        <div class="mb-3">
          <label for="schemes" class="form-label">Schemes</label>
          <select name="schemes" id="schemes" class="form-select select2 schemes"></select>
          <input type="hidden" class="form-control cid">
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-primary schemes_save" id="updateClose">Update</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

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
              <button class="btn btn-success text-white px-4 create me-2">Create
                <i class="bi bi-plus-circle"></i> 
              </button>
            `);
      }
    });


    // data table funcrion	
    $(document).ready(function () {
      var table = $('#CustomerTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "getcustomerData?pageMethod={{$pageMethod}}",
        columns: [
          
          { data: 'customer_number', name: 'customer_number' },
          { data: 'customer_name', name: 'customer_name' },
          { data: 'customer_type', name: 'customer_type' },
          { data: 'active', name: 'active' },
          { data: 'savestatus', name: 'savestatus' },
          { data: 'created_by', name: 'created_by' },


          {
            data: 'customer_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
        <button class="btn btn-sm btn-warning view-btn me-1" data-id="${row.customer_id}">
          <i class="bi bi-eye"></i>
        </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                buttons += `
        <button class="btn btn-sm btn-success approve-btn me-1" data-id="${row.customer_id}">
          Approve
        </button>`;
              }
	  if (window.toolbarButtons?.some(btn => btn.attr.id === 'quickcustomer')) {
                buttons += `
        <button class="btn btn-sm btn-primary customer-btn" data-id="${row.customer_id}">
        Create
        </button>`;
              }
				
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.customer_id}">
          <i class="bi bi-pencil"></i>
        </button>
		
		    	<button class="btn btn-sm btn-success scheme-btn px-2" data-id="${row.customer_id}"
		data-name="${row.customer_name}"
		data-status="${row.savestatus}"
		data-active="${row.active}"
		data-bs-toggle="tooltip" 
		data-bs-placement="top" 
		title="Schemes Update">
        <i class="bi bi-arrows-move"></i>
        </button>`;
				  
				  
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.customer_id}">
          <i class="bi bi-trash"></i>
        </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#CustomerTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });	
	
	
	
    // create function
    $(".create").click(function () {
      var url = "{{ URL::to('customerscreate/0')}}";
      window.location.replace(url);
    });
	
    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      var url = "{{ url('geteditcustomer') }}/" + id;
      $.get(url, function (data) {
        var data = $.trim(data);

        if (data == '0') {

          window.location.replace('customersedit/' + id);
        }
        else {
          window.location.replace('customersedit/' + id + '?edit=readonly');
        }
      });
      window.location.href = url;
    });

	
    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
		var return1 = "{{$pageMethod}}";
		
      var url = "customersview";
      var editUrl = url + '/' + id + '/show';
      window.location.replace('customersview/' + id + '?return=' + return1);
    });


    // delete function
    let deleteId = null;

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('customersdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {
            if (data == '0') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Deleted successfully!', 'success');
              $('#CustomerTbl').DataTable().ajax.reload();
            }
            if (data == '1') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert("You Can't delete , Subinventory Used in SomeWhere.", 'error');
              $('#CustomerTbl').DataTable().ajax.reload();
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
	
// schemes

	var url = "{{ URL::to('jcomboform') }}?table=s_schemes_hdr_t:schemes_hdr_id:schemes_name&parent=active='yes'&order_by=schemes_name asc";


			$.ajax({
				url: url,
				type: 'GET',

success: function (data) {
    // Parse JSON string if needed
    if (typeof data === "string") {
        try {
            data = JSON.parse(data);
        } catch (e) {
            console.error("Invalid JSON response:", data);
            return;
        }
    }

    $('.schemes').html('<option value="">-- Please Select --</option>');

    $.each(data, function (i, item) {
        let selected = item.val == "{{ $row->schemes ?? '' }}" ? 'selected' : '';
        $('.schemes').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
    });

    $('.schemes').trigger('change.select2'); 
}
			});
	
$(document).on('click', '.scheme-btn', function () {
const id = $(this).data('id');
const name = $(this).data('name');
const status = $(this).data('status');
const active = $(this).data('active');


if (active == 'Yes') {

    $(".cname").html(name);
    $(".cstatus").html(status);
    $(".cid").val(id);
    $("#schemesModal").modal('show');
    var url = "{{URL::to('cusschmesedit')}}?id=" + id;
    $.get(url, function (data) {
        $('.schemes').select2('val', [data.schemes]);
    });
}
else {
    showCustomAlert("Please Select Active Customer", "warning");
}
});

	

$(document).on('click', '.schemes_save', function () {

    var id = $(".cid").val();
    var schemes = $(".schemes").val();

    $.get("cusschemesupdate?id=" + id + "&schemes=" + schemes, function (data) {

        if ($.trim(data) == '1') {
            showCustomAlert("Schemes Updated Successfully", "success");
            $("#grid1")[0].triggerToolbar();
        } else {
            notyMsg('error', 'Please Try Again');
            $("#grid1")[0].triggerToolbar();
        }
    });
    $("#schemesModal").modal('hide');

});

	
// quick customer
    $(document).on('click', '.customer-btn', function () {

    var red_url = "{{  URL::to('quickcustomer/0')}}";

    window.location.replace(red_url);
});



    $(document).on('click', '.approve-btn', function () {
		
      const id = $(this).data('id');

        window.location.replace('customersapprovalcreate/' + id);

});
	
</script>

@endpush
