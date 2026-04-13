@extends('layouts.header')
@section('content')
<h3 class="text-danger">Tax Category</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <form id="tax_category_save" action="" method="post" data-parsley-validate>
      {{ csrf_field() }}
      <input type="hidden" name="savestatus" id="savestatus">
      <input type="hidden" name="edit_id" id="edit_id" class="tax_category_id" value="">

      <div class="row g-4">
        <!-- Left Column -->
        <div class="col-md-4">
          <div class="mb-3">
            <label for="tax_category_name" class="form-label">
              <span class="text-danger">*</span> Tax Category Name
            </label>
            <input type="text" name="tax_category_name" id="tax_category_name"
                   class="form-control tax_category_name" required tabindex="1">
            <span class="btn btn-danger mt-2 dup_name d-none"></span>
          </div>

          <div class="mb-3">
            <label for="tax_location_type" class="form-label">
              <span class="text-danger">*</span> Tax Location Type
            </label>
            <select name="tax_location_type" id="tax_location_type"
                    class="form-select select2 tax_location_type" required tabindex="2">
              {!! $tax_location_type !!}
            </select>
          </div>
        </div>

        <!-- Middle Column -->
        <div class="col-md-4">
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" name="description" id="description"
                   class="form-control description" tabindex="3">
          </div>

          <div class="mb-3 none">
            <label for="created_by" class="form-label">Created By</label>
            <select name="created_by" id="created_by"
                    class="form-select created_by select2" tabindex="4">
              {!! $created_by !!}
            </select>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-4">
          <div class="mb-3">
            <label for="active" class="form-label">Active</label>
            <select name="active" id="active" class="form-select active select2" tabindex="5">
              <option value="">--Please Select--</option>
              <option value="Yes" {{ $active == 'Yes' ? 'selected' : '' }}>Yes</option>
              <option value="No" {{ $active == 'No' ? 'selected' : '' }}>No</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="mt-4 text-center">
        <button type="button" class="btn btn-success px-4 save_form">Save</button>
      </div>
    </form>
  </div>
</div>



<div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
        <table id="AccountsTbl" class="table table-bordered table-striped w-100">
            <thead>
                <tr class="table-warning">
                    <th>Tax Category</th>
                    <th>Location Type</th>
                    <th>Description</th>
                    <th>Active</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
                <tr class="table-danger">
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
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
        var table = $('#AccountsTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('getTaxcategoryData') }}",
            columns: [

                { data: 'tax_category_name', name: 'tax_category_name' },
                { data: 'tax_location_type', name: 'tax_location_type' },
                { data: 'description', name: 'description' },
                { data: 'active', name: 'active' },
                { data: 'first_name', name: 'tb_users.first_name' },

                {
                    data: 'tax_category_id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',

                    render: function (data, type, row) {
                        let buttons = '';

                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                            buttons += `
          <button class="btn btn-sm btn-primary edit-btn" data-id="${row.tax_category_id}" data-name="${row.tax_category_name}" data-desc="${row.description}" data-type="${row.tax_location_type}" data-active="${row.active}">
            <i class="bi bi-pencil"></i>
          </button>`;
                        }

                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                            buttons += `
          <button class="btn btn-sm btn-danger delete-btn" data-id="${row.tax_category_id}">
            <i class="bi bi-trash"></i>
          </button>`;
                        }
                        return buttons;
                    }
                }
            ]
        });

        // Individual column search
        $('#AccountsTbl thead').on('keyup change', ".column-search", function () {
            var colIndex = $(this).parent().index();
            table.column(colIndex).search(this.value).draw();
        });
    });
	

	var dup_chk = true;
	function duplicate_validate()
	{
		var tax_category_name = $(".tax_category_name").val();
		var edit_id = $("#edit_id").val();

		$.ajax({
			cache: false,
			url: "{{ URL::to('taxcategorycheckname/') }}",
			type: 'GET',
			dataType: 'json',
			async: false,
			data: {tax_category_name: tax_category_name, edit_id: edit_id},
			success: function (response){
				console.log(response);
				if (response == 1)
				{
					$('.dup_name').html('Tax Category:' + tax_category_name + ' Already Exists');
					$('.dup_name').show();
					$(".tax_category_name").val('');
					dup_chk = false;

				} else if (response == 0)
				{
					var html = "";
					$('.dup_name').hide();
					dup_chk = true;
				}
			},
			error: function (xhr, resp, text)
			{
				console.log(xhr, resp, text);
			}
		});
	}


		$(document).ready(function () {
		$('.tax_location_type').prop("required",true);
        
         $(document).on('click','.save_form',function()
                {
                    var url	="{{URL::to('taxcategorysave')}}";
                    var form = $('#tax_category_save');
                    form.parsley().validate();
                    var form = $('#tax_category_save');
                    form.parsley().validate();
                     duplicate_validate();
                    
                    var data	= $('#tax_category_save').serialize();
						if (form.parsley().isValid()&&dup_chk==true)
					{
                    $.post(url, data, function (data) {
                        var status = data.status;
                        var msg = data.message;
						showCustomAlert(msg, status);
						$('#AccountsTbl').DataTable().ajax.reload();
                    });
                }
                });
                
		/* Purpose for Upper Case */
		$('.tax_category_name').on('keyup', function () {
			this.value = this.value.toUpperCase();
		});


    // edit function
    $(document).on('click', '.edit-btn', function () {

        const id = $(this).data('id');
        const name = $(this).data('name');
        const type = $(this).data('type');
        const desc = $(this).data('desc');
        const active = $(this).data('active');

        var url = "{{ URL::to('tdsslabedit') }}/" + id;

        $.get(url, function (data) {
            var data = $.trim(data);
            if (data == 0) {

                // Fill form fields
                $('input[name="edit_id"]').val(id);
                $('input[name="tax_category_name"]').val(name);
                $('input[name="description"]').val(desc);

                $('select[name="tax_location_type"]').val(type).trigger('change');
                $('select[name="active"]').val(active).trigger('change');

            } else {

                showCustomAlert("You Can't be Edit this Tax Category Name, Already used", "warning");

            }
        });

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
                url: "{{ url('taxcategorydelete') }}/" + deleteId,
                type: "GET",
                success: function (data) {
                    if (data == '0') {
                        $('#globalDeleteModal').modal('hide');
                        showCustomAlert('Deleted successfully!', 'success');
                        $('#AccountsTbl').DataTable().ajax.reload();
                    }
                    if (data == '1') {
                        $('#globalDeleteModal').modal('hide');
                        showCustomAlert("You Can't delete , Used in SomeWhere.", 'error');
                        $('#AccountsTbl').DataTable().ajax.reload();
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

});
	
</script>


@endpush
