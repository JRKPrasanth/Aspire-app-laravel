@extends('layouts.header')
@section('content')
<h3 class="text-danger">Uom Code Conversion</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-header bg-primary text-white fw-semibold">

  </div>

  <div class="card-body">
    <form method="post" action="" id="uomcodeconsave" data-parsley-validate>
      @csrf
      <input type="hidden" name="savestatus" id="savestatus">
      <input type="hidden" name="edit_id" id="edit_id" value="{{ $row->uom_conversion_id }}">

      <div class="row g-4">
        <!-- Product -->
        <div class="col-md-4">
          <label class="form-label">
            <span class="text-danger">*</span> Product
          </label>
          <select class="form-select select2 product_id" name="product_id" id="product_id" required tabindex="1">
            {!! $product_id !!}
          </select>
        </div>

        <!-- Primary UOM -->
        <div class="col-md-4">
          <label class="form-label">
            <span class="text-danger">*</span> Primary UOM
          </label>
          <select class="form-select select2 primary_uom" name="primary_uom" id="primary_uom" required tabindex="2">
            {!! $primary_uom !!}
          </select>
        </div>

        <!-- UOM Value -->
        <div class="col-md-4">
          <label class="form-label">
            <span class="text-danger">*</span> UOM Value
          </label>
          <input type="text" class="form-control uom_value" 
                 name="uom_value" id="uom_value"
                 value="{{ $row->uom_value }}" 
                 required tabindex="3">
          <span class="badge bg-danger dup_name d-none"></span>
        </div>

        <!-- Active -->
        <div class="col-md-4">
          <label class="form-label">Active</label>
          <select name="active" class="form-select select2 active" tabindex="4">
            <option value="Yes" @if($row->active=="Yes") selected @endif>Yes</option>
            <option value="No" @if($row->active=="No") selected @endif>No</option>
          </select>
        </div>

        <!-- Trx UOM -->
        <div class="col-md-4">
          <label class="form-label">
            <span class="text-danger">*</span> Trx UOM
          </label>
          <select class="form-select select2 trx_uom" name="trx_uom" id="trx_uom" required tabindex="5">
            {!! $trx_uom !!}
          </select>
        </div>

        <!-- Created By -->
        <div class="col-md-4 none">
          <label class="form-label">Created By</label>
          <select class="form-select select2 created_by" name="created_by" id="created_by">
            {!! $created_by !!}
          </select>
        </div>
      </div>

      <!-- Buttons -->
      <div class="row mt-4">
        <div class="col text-center">
          <button type="button" id="save" class="btn btn-success px-4 saveform">
            Save
          </button>
        </div>
      </div>
    </form>
  </div>
</div>




  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="InvTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Product Code</th>
            <th>Product Name</th>
            <th>Primary Uom</th>
            <th>Trx Uom</th>
            <th>Uom Value</th>
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
      var table = $('#InvTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "getGridUomconData",
        columns: [
            { data: 'product_code', name: 'product_code' },
            { data: 'concatenated_product', name: 'concatenated_product' },
            { data: 'uom_code', name: 'uom_code' },
            { data: 'trx_code', name: 'trx_code' },
            { data: 'uom_value', name: 'uom_value' },
            { data: 'active', name: 'active' },
            { data: 'first_name', name: 'first_name' },

          {
            data: 'uom_conversion_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data, type, row) {
              let buttons = '';
                      if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `<button class="btn btn-sm btn-info me-1 edit-btn" 
                  data-id="${row.uom_conversion_id}" 
                  data-name="${row.uom_code}" 
                  data-code="${row.code_meaning}" 
                  data-active="${row.active}">
                  <i class="bi bi-pencil"></i>
                </button>`;
                }
                if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                      buttons += `
                <button class="btn btn-sm btn-danger delete-btn" data-id="${row.uom_conversion_id}">
                  <i class="bi bi-trash"></i>
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
	  
	  
	  
    /*Duplicate Validation Function*/
     var dup_chk = true;
         function duplicate_validate()
                        {
            var product_id = $(".product_id").val();
            var primuom =$(".primary_uom").val(); 
            var trxmuom =$(".trx_uom").val(); 
      
            var edit_id = $("#edit_id").val();

            $.ajax({
                cache: false,
                url: "{{URL::to('uomconversioncheckname')}}", //this is your uri
                type: 'GET',
                dataType: 'json',
                async : false,
                data: {product_id : product_id,edit_id : edit_id,primary_uom :primuom,trx_uom :trxmuom},
                success: function(response)
                {
                    console.log(response);
                    if(response == 1)
                    {
                        $('.dup_name').html('Uom combination Already Exists');
                        $('.dup_name').show();
                        $(".product_id").select2('val',['']);
                        dup_chk = false;


                    }
                    else if(response == 0)
                    {
                        var html ="";
                            $('.dup_name').hide();
                        dup_chk = true;

                    }

                },
                error: function(xhr, resp, text)
                {
                    console.log(xhr, resp, text);
                }

            });
            return false;
        }

     $('.read').css('pointer-events','none');

    
   /*Primary Uom get Function*/ 
    $(document).on('change','.product_id',function(){
   
          var product_id = $(this).val();
        if(product_id!=''){   
var url = "{{ URL::to('productprimaryuom') }}/"+product_id;
               
           $.get(url , function(data)
           {
            console.log(data);
           if(data[0]!=''){
           $('.primary_uom').select2('val',[data[0]]);
           $('.primary_uom').parsley().destroy();
           }if(data[1]!=''){
           $('.trx_uom').select2('val',[data[1]]);
           }
           });
        }     
                       
        });
	  

      $(document).on('keypress','.uom_value', function(ev){
          var regex = new RegExp("^[0-9.]+$");
          var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
          if (regex.test(str)) {
            return true;
          }
          ev.preventDefault();
          return false;
      });

	  
       $('.uom_value').bind("cut copy paste", function(e) {
        e.preventDefault();
            })

    $(".select2").select2({width:"90%"});
    
			// save function


		$(document).on('click', '.saveform', function () {

		var form = $("#uomcodeconsave");
		form.parsley().validate();
		duplicate_validate();
		if (form.parsley().isValid() && dup_chk == true) {
      var $btn = $(this);            
			$btn.prop('disabled', true);
			$.ajax({
				url: "{{ URL::to('uomconversionsave') }}",
				type: "POST",
				data: form.serialize(),
				success: function (data1) {
				   showCustomAlert('Saved successfully!', 'success');
					form[0].reset();
					$('.select2').val('').trigger('change');
					window.location.reload();
				},
				error: function (xhr) {
				  showCustomAlert('Save failed. Try again.', 'error');
				}
			});
		}
		});	
	

	    // delete function
    let deleteId = null;

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      const type = $(this).data('type');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
         	url: "{{ url('uomconversiondelete') }}/" + deleteId,
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
	
	
	// edit function
	$(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const code = $(this).data('code');
    const name = $(this).data('name');
    const active = $(this).data('active');

	var url ="{{ URL::to('uomedit') }}/" +id; 
	
	$.get(url,function(data){
    var data = $.trim(data);
     if(data == 0) {
						
    // Fill form fields
    $('input[name="edit_id"]').val(id);
    $('input[name="uom_code"]').val(code);
    $('input[name="code_meaning"]').val(name);
    $('select[name="active"]').val(active).trigger('change');
		 
	}else{
		
       showCustomAlert("You Can't be Edit this  Uomcode, Already used","warning");
		
       }
    }); 	 
		 
});

    
	  
  </script>

@endpush