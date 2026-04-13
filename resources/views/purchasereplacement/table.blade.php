@extends('layouts.header')
@section('content')
<h3 class="text-danger">Purchase Replacement</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="mb-3 mt-2"></div>


  <div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="ReplTbl" class="table table-bordered table-striped w-100">
        <thead>

          <tr class="table-warning">
            <th>Actions</th>
            <th>Replacement No</th>
            <th>Replacement Date</th>
            <th>Replacement Status</th>
            <th>GRN Number</th>
            <th>Supplier Name</th>
			<th></th>
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


<!-- modal -->
<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-3">
      
      <!-- Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="contactModalLabel">Contact</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Form -->
      <form method="POST" id="enquirymail" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="modal-body">

          <!-- Supplier Details -->
          <div class="mb-3">
            <label class="form-label">Supplier Name</label>
            <input type="text" name="supplier_name" class="form-control supplier_name" readonly>
            <input type="hidden" name="replacement_hdr_id" class="replacement_hdr_id">
          </div>

          <!-- Contacts Table -->
          <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
              <thead class="table-light">
                <tr>
                  <th style="width:5%"></th>
                  <th>Contact Person</th>
                  <th>Contact Number</th>
                  <th>Contact Mail</th>
                  <th style="width:5%"></th>
                </tr>
              </thead>
              <tbody class="mcontent2"></tbody>
            </table>
          </div>

          <!-- CC -->
          <div class="mb-3 row align-items-center">
            <label for="cc" class="col-sm-2 col-form-label">CC</label>
            <div class="col-sm-8">
              <input type="text" name="cc[]" class="form-control cc">
            </div>
          </div>

          <!-- Message -->
          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="msg" class="form-control tinymce" rows="6">
Thanks and Regards,
Purchase Department,
JRKS,
Kundrathur.
            </textarea>
            <input type="hidden" name="hdr_id" class="hdr_id">
          </div>

          <!-- Attachments -->
          <div class="mb-3">
            <label class="form-label fw-bold">Attachments</label>
            <input type="file" name="email_attachment[]" class="form-control mb-2" multiple>
            <div class="form-check">
              <input class="form-check-input attchment" type="checkbox" name="attchment" value="">
              <label class="form-check-label">Attach PDF</label>
            </div>
            <div class="preview mt-2"></div>
          </div>

          <!-- PDF Preview -->
          <div class="mb-3">
            <iframe id="iframepdf" width="100%" height="400" class="border rounded" style="display:none;"></iframe>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success sendmail" id="sentmail_id">Send</button>
        </div>
      </form>
    </div>
  </div>
</div>


@endsection
@push('scripts')

<script>

	
	$(document).ready(function () {
    var status = "{{$status}}";

    var table = $('#ReplTbl').DataTable({
        processing: true,
        serverSide: true,
        order: [[2, 'desc']],
        ajax: "getpurchasereplacementData?status=" + status,
        columns: [
            { 
                data: 'replacement_hdr_id',
                render: function (data, type, row) {
                    let buttons = '';

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-primary edit-btn"
                                data-id="${row.replacement_hdr_id}"
                                data-status="${row.replacement_status}">
                                <i class="bi bi-pencil"></i>
                            </button>`;
                    }

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-warning view-btn"
                                data-id="${row.replacement_hdr_id}">
                                <i class="bi bi-eye"></i>
                            </button>`;
                    }

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'print')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-success print-btn"
                                data-id="${row.replacement_hdr_id}"
								data-status="${row.replacement_status}"
								data-ponumber="${row.po_number}">
                                <i class="bi bi-printer"></i> 
                            </button>`;
                    }

                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'approval')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-success approve-btn"
                                data-id="${row.replacement_hdr_id}">
                               <i class="bi bi-check2-circle"></i> Approve
                            </button>`;
                    }
					
                    return buttons;
                },
                orderable: false,
                searchable: false
            },

            { data: 'replacement_no', name: 'replacement_no' },
            { data: 'replacement_date', name: 'replacement_date' },
            { data: 'replacement_status', name: 'replacement_status' },
            { data: 'grn_number', name: 'grn_number' },
            { data: 'supplier_name', name: 'supplier_name' },
			{ data: 'po_number', name: 'po_number', visible:false },

        ],
    
    });


    // Per-column search
    $('#ReplTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
    });
		
		
    if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
      $('#toolbar-container').append(`
            <button class="btn btn-primary bg-gradient replacement me-2">Replacement
            </button>
          `);
    }		
});
	

// create
	
$(document).on('click', '.replacement', function () {	

var url="{{ url('grntablerplt') }}";

window.location.replace(url);

});	
	
	
//approve	
	
	
$(document).on('click', '.approve-btn', function () {	

		const id = $(this).data('id');	
	
         window.location.replace('purchasereplacementapprove/' +id+'/1');

  }); 

	
// print

$(document).on('click', '.print-btn', function () {	
	
     const id = $(this).data('id');	
	 const status = $(this).data('status');	
	 const ponumber = $(this).data('ponumber');	
	
	
      window.open('purchasereplacementprint/' +id+'/'+ponumber);
	
       if(status =="APPROVED"){
          window.open('purchasereplacementprint/'+id);
            }else{
				
            showCustomAlert("Approved Record only Able to Print",'info');
				
            }

 });

	
// edit
	
$(document).on('click', '.edit-btn', function () {	
	
	 const id = $(this).data('id');	
	 const status = $(this).data('status');	
	
	 if(status !="INITIATED" && status!="APPROVED")
         {

		window.location.replace('purchasereplacementedit/'+id);
        }
         else
         {
           showCustomAlert("Unable to edit Purchase Return",'warning');
         }

});		
	
// view	
	
$(document).on('click', '.view-btn', function () {	
	
	 const id = $(this).data('id');	
   var url = "{{$pageMethod}}";
   window.location.replace('purchasereplacementview/' +id+'?return='+url);
	
});	
	
	
</script>

@endpush
