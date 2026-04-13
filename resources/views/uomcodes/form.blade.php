@extends('layouts.header')
@section('content')
<h3 class="text-danger">Uom Code</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <form method="post" action="" id="uomcodesave" data-parsley-validate>
      @csrf
      <input type="hidden" name="savestatus" id="savestatus">
      <input type="hidden" name="edit_id" id="edit_id" value="{{ $row->uom_code_id }}">

      <div class="row g-4">
        <!-- Left Column -->
        <div class="col-md-6">
          <!-- UOM Code -->
          <div class="mb-3 row">
            <label class="col-md-5 col-form-label">
              <span class="text-danger">*</span> UOM Code
            </label>
            <div class="col-md-7">
              <input type="text" id="uom_code" name="uom_code" 
                     class="form-control uom_code"
                     value="{{ $row->uom_code }}" required tabindex="1">
              <span class="badge bg-danger dup_name d-none"></span>
            </div>
          </div>

          <!-- Active -->
          <div class="mb-3 row">
            <label class="col-md-5 col-form-label">Active</label>
            <div class="col-md-7">
              <select name="active" class="form-select active select2" tabindex="3">
                <option value="Yes" @if($row->active=="Yes") selected @endif>Yes</option>
                <option value="No" @if($row->active=="No") selected @endif>No</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
          <!-- UOM Code Meaning -->
          <div class="mb-3 row">
            <label class="col-md-5 col-form-label">UOM Code Meaning</label>
            <div class="col-md-7">
              <input type="text" id="code_meaning" name="code_meaning" 
                     class="form-control code_meaning"
                     value="{{ $row->code_meaning }}" tabindex="2">
            </div>
          </div>

          <!-- Created By -->
          <div class="mb-3 row none">
            <label class="col-md-5 col-form-label">Created By</label>
            <div class="col-md-7">
              <select name="created_by" id="created_by" class="form-select select2">
                {!! $created_by !!}
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Save Button -->
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
            <th>Uom Code</th>
            <th>Code Meaning</th>
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
        ajax: "getGridUomData",
        columns: [
            { data: 'uom_code', name: 'uom_code' },
            { data: 'code_meaning', name: 'code_meaning' },
            { data: 'active', name: 'active' },
            { data: 'first_name', name: 'first_name' },

          {
            data: 'uom_code_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data, type, row) {
              let buttons = '';
                      if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `<button class="btn btn-sm btn-info me-1 edit-btn" 
                  data-id="${row.uom_code_id}" 
                  data-code="${row.uom_code}" 
                  data-name="${row.code_meaning}" 
                  data-active="${row.active}">
                  <i class="bi bi-pencil"></i>
                </button>`;
                }
                if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                      buttons += `
                <button class="btn btn-sm btn-danger delete-btn" data-id="${row.uom_code_id}">
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
	
	
$(document).ready(function(){	

    var dup_chk = true;
        function duplicate_validate()
        {

            var uom_code = $(".uom_code").val();
            var edit_id = $("#edit_id").val();
            var url = "{{URL::to('uomcodescheckname')}}";

       $.ajax({
                cache: false,
                url: url, /*this is your uri*/
                type: 'GET',
                dataType: 'json',
                async : false,
                data: {uom_code : uom_code,edit_id : edit_id},
                success: function(response)
                {
                    console.log(response);
                if (response == 1)
                {
                    $('.dup_name')
                        .html('UOM Code: ' + uom_code + ' already exists')
                        .removeClass('d-none')
                        .addClass('d-block');

                    $(".uom_code").val('');
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

        }

	
			// save function


		$(document).on('click', '.saveform', function () {

		var form = $("#uomcodesave");
		form.parsley().validate();
		duplicate_validate();
		if (form.parsley().isValid() && dup_chk == true) {
      var $btn = $(this);            
			$btn.prop('disabled', true);
			$.ajax({
				url: "{{ URL::to('uomcodessave') }}",
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
         	url: "{{ url('uomcodesdelete') }}/" + deleteId,
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
	
	
            $('.uom_code').on('keyup', function () {
                this.value = this.value.toUpperCase();
            });

   });	
	
	

</script>


@endpush
