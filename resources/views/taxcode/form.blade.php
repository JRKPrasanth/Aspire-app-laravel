@extends('layouts.header')
@section('content')
<h3 class="text-danger">Tax Slab(%)</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <form id="tax_code_save" action="" data-parsley-validate>
      <?php $data=\Session::get('data'); if(isset($data[$pageMethod]['save'])) { ?>
      <input type="hidden" value="" name="savestatus" id="savestatus" />
      <input type="hidden" name="edit_id" value="{{$row->tax_code_id}}" id="edit_id" class="tax_code_id" />
      {{ csrf_field() }}

      <div class="row g-4">
        <!-- Left Column -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label">
              <span class="text-danger">*</span> Tax Code Name
            </label>
            <input type="text" name="tax_code_name" id="tax_code_name" value="{{$row->tax_code_name}}"
              class="form-control" required tabindex="1">
            <span class="badge bg-danger mt-2 dup_name" style="display:none;"></span>
          </div>

          <div class="mb-3">
            <label class="form-label">
              <span class="text-danger">*</span> Tax Category
            </label>
            <div class="d-flex align-items-center">
              <select name="tax_category_id" class="form-select tax_category_id select2" required tabindex="2">
                {!! $tax_category_id !!}
              </select>
            </div>
          </div>
        </div>

        <!-- Middle Column -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" id="description" name="description" class="form-control"
              value="{{$row->description}}" tabindex="3">
          </div>

          <div class="mb-3 none">
            <label class="form-label">Created By</label>
            <select name="created_by" id="created_by" class="form-select select2 created_by" tabindex="4">
              {!! $created_by !!}
            </select>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-4">
          <div class="mb-3">
            <label class="form-label">Tax Code Percentage (%)</label>
            <input type="text" id="tax_code_percent" name="tax_code_percent" class="form-control"
              value="{{$row->tax_code_percent}}" tabindex="5">
          </div>

          <div class="mb-3">
            <label class="form-label">Active</label>
            <select name="active" class="form-select select2" tabindex="6">
              <option value="Yes" <?php if ($row->active == 'Yes') echo "selected"; ?>>Yes</option>
              <option value="No" <?php if ($row->active == 'No') echo "selected"; ?>>No</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="text-center mt-4">
        <button type="button" class="btn btn-success px-4 save_form"> Save
        </button>
      </div>
      <?php } ?>
    </form>
  </div>
</div>





<div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
        <table id="AccountsTbl" class="table table-bordered table-striped w-100">
            <thead>
                <tr class="table-warning">
					  <th></th>	
                    <th>Tax Code</th>
                    <th>Tax Category Name</th>
                    <th>Description</th>
                    <th>Tax Code Percentage</th>
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
            ajax: "{{ route('getTaxcodeData') }}",
            columns: [
				 { data: 'tax_category_id', name: 'tax_category_id', visible:false },
                { data: 'tax_code_name', name: 'tax_code_name' },
                { data: 'tax_category_name', name: 'tax_category_name' },
                { data: 'description', name: 'description' },
                { data: 'tax_code_percent', name: 'tax_code_percent' },
                { data: 'active', name: 'active' },
                { data: 'first_name', name: 'tb_users.first_name' },

                {
                    data: 'tax_code_id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',

                    render: function (data, type, row) {
                        let buttons = '';

                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                            buttons += `
          <button class="btn btn-sm btn-primary edit-btn" data-id="${row.tax_code_id}" data-name="${row.tax_code_name}" data-present="${row.tax_code_percent}" data-desc="${row.description}" data-cat="${row.tax_category_id}" data-active="${row.active}">
            <i class="bi bi-pencil"></i>
          </button>`;
                        }

                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                            buttons += `
          <button class="btn btn-sm btn-danger delete-btn" data-id="${row.tax_code_id}">
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
    var tax_code_name = $(".tax_code_name").val();
    var edit_id = $("#edit_id").val();

    $.ajax({
        cache: false,
        url: "{{ URL::to('taxcodecheckname/') }}",
        type: 'GET',
        dataType: 'json',
        async: false,
        data: {tax_code_name: tax_code_name, edit_id: edit_id},
        success: function (response){
            console.log(response);
            if (response == 1)
            {
                $('.dup_name').html('Tax Code:' + tax_code_name + ' Already Exists');
                $('.dup_name').show();
                $(".tax_code_name").val('');
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


         $(document).on('click','.save_form',function()
                {
                    var url	="{{URL::to('taxcodesave')}}";
                    var form = $('#tax_code_save');
                    form.parsley().validate();
                    var form = $('#tax_code_save');
                    form.parsley().validate();
                    duplicate_validate();
                    
                    var data	= $('#tax_code_save').serialize();
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
                

    $('.tax_category_name').on('keyup', function () {
        this.value = this.value.toUpperCase();
    });



    // edit function
    $(document).on('click', '.edit-btn', function () {

        const id = $(this).data('id');
        const name = $(this).data('name');
        const present = $(this).data('present');
        const cat = $(this).data('cat');
        const desc = $(this).data('desc');
        const active = $(this).data('active');


                // Fill form fields
                $('input[name="edit_id"]').val(id);
                $('input[name="tax_code_name"]').val(name);
                $('input[name="description"]').val(desc);
                $('input[name="tax_code_percent"]').val(present);
                $('select[name="tax_category_id"]').val(cat).trigger('change');
                $('select[name="active"]').val(active).trigger('change');


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
                url: "{{ url('taxcodedelete') }}/" + deleteId,
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
