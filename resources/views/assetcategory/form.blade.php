@extends('layouts.header')
@section('content')
<h3 class="text-danger">   Asset Category  </h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    </div>

    <div class="card-body">
    <form id="asset_category_form" action="" method="POST" data-parsley-validate>
      <?php $data = \Session::get('data'); if (isset($data[$pageMethod]['save'])) { ?>
      {{ csrf_field() }}
      
      <input type="hidden" name="savestatus" id="savestatus" value="">
      <input type="hidden" name="edit_id" id="edit_id" value="{{ $row->asset_category_id ?? '' }}" class="asset_category_id">

      <!-- Body Content -->
      <div class="row g-4">
        <div class="col-md-12">
          <div class="row g-4">
            
            <!-- Asset Category Name -->
            <div class="col-md-4">
              <div class="form-group row">
                <label class="form-control-label col-md-5">
                  <span class="text-danger">*</span> Category Name
                </label>
                <div class="col-md-7">
                  <input type="text" name="asset_category_name" id="asset_category_name" 
                         value="{{ $row->asset_category_name ?? '' }}" 
                         class="form-control asset_category_name" required>
                  <span class="btn btn-danger dup_name" style="display:none;"></span>
                </div>
              </div>
            </div>

            <!-- Asset Type -->
            <div class="col-md-4">
              <div class="form-group row">
                <label class="form-control-label col-md-5">
                  <span class="text-danger">*</span> Asset Type Name
                </label>
                <div class="col-md-7">
                  <select name="asset_type_id" id="asset_type_id" 
                          class="form-control select2 asset_type_id" required>
                    {!! $asset_type_id !!}
                  </select>
                </div>
              </div>
            </div>

            <!-- Description -->
            <div class="col-md-4">
              <div class="form-group row">
                <label class="form-control-label col-md-5">Description</label>
                <div class="col-md-7">
                  <input type="text" name="description" id="description" 
                         value="{{ $row->description ?? '' }}" 
                         class="form-control description">
                </div>
              </div>
            </div>

            <!-- Active -->
            <div class="col-md-4">
              <div class="form-group row">
                <label class="form-control-label col-md-5">Active</label>
                <div class="col-md-7">
                  <select name="active" class="form-control select2 active">
                    <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No"  {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Created By -->
            <div class="col-md-4">
              <div class="form-group row" style="pointer-events:none;">
                <label class="form-control-label col-md-5">Created By</label>
                <div class="col-md-7">
                  <select name="created_by" id="created_by" class="select2 form-control created_by">
                    {!! $created_by !!}
                  </select>
                </div>
              </div>
            </div>

          </div> <!-- row end -->

          <!-- Submit Button -->
          <div class="row mt-4">
            <div class="col-md-12 text-center">
              <button type="button" class="btn btn-success px-4 save_form">Save</button>
              <?php include('toolbar.php'); ?>
            </div>
          </div>

        </div>
      </div>

      <?php } ?>
    </form>
  </div>
</div>


<div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
        <table id="AccTbl" class="table table-bordered table-striped w-100">
            <thead>
                <tr class="table-warning">
					<th>Asset Category Name</th>
                    <th>Asset Type Name</th>
                    <th>Description</th>
                    <th>Active</th>
                    <th>Created By</th>
                    <th>Actions</th>
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
					<th></th>
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
        var table = $('#AccTbl').DataTable({
            processing: true,
            serverSide: true,
			order: [[2, 'desc']],
            ajax: "{{ route('getAssetcategoryData') }}",
            columns: [
                { data: 'asset_category_name', name: 'asset_category_name' },
                { data: 'asset_type_name', name: 'asset_type_name' },
                { data: 'description', name: 'description' },
                { data: 'active', name: 'active' },
                { data: 'first_name', name: 'tb_users.first_name' },
				{ data: 'asset_type_id', name: 'asset_type_id', visible:false },
                {
                    data: 'asset_category_id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',

                    render: function (data, type, row) {
                        let buttons = '';

                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                            buttons += `
        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.asset_category_id}"
			 data-active="${row.active}"
			 data-name="${row.asset_category_name}" 
			 data-desc="${row.description}"
		     data-type="${row.asset_type_id}">
          <i class="bi bi-pencil"></i>
        </button>`;
                        }

                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                            buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.asset_category_id}">
          <i class="bi bi-trash"></i>
        </button>`;
                        }
                        return buttons;
                    }
                }
            ]
        });

        // Individual column search
        $('#AccTbl thead').on('keyup change', ".column-search", function () {
            var colIndex = $(this).parent().index();
            table.column(colIndex).search(this.value).draw();
        });
    });
	
	
	
	var dup_chk = true;
	function duplicate_validate()
	{
    var asset_category_name = $(".asset_category_name").val();
    var edit_id = $("#edit_id").val();

    $.ajax({
        cache: false,
        url: "{{ URL::to('assetcategorycheckname/') }}",
        type: 'GET',
        dataType: 'json',
        async: false,
        data: {asset_category_name: asset_category_name, edit_id: edit_id},
        success: function (response){
            if (response == 1)
            {
                $('.dup_name').html('Asset Category:' + asset_category_name + ' Already Exists');
                $('.dup_name').show();
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
/*end*/
        
				 $(document).on('click','.save_form',function(){
					 
                    var url	="{{URL::to('assetcategorysave')}}";
                    var form = $('#asset_category_form');
                    form.parsley().validate();
                    var form = $('#asset_category_form');
                    form.parsley().validate();
                    duplicate_validate();
                    var data	= $('#asset_category_form').serialize();
		    if (form.parsley().isValid()&&dup_chk==true)
		{
                    $.post(url, data, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        showCustomAlert(msg,status);
						form[0].reset();
						$('.select2').val('').trigger('change');
						// Reload DataTable
						$('#AccTbl').DataTable().ajax.reload();
                    });
                }
                });
            
		/* Purpose for Upper Case */
		$('.asset_category_name').on('keyup', function () {
			this.value = this.value.toUpperCase();
		});


    // edit function
    $(document).on('click', '.edit-btn', function () {

        const id = $(this).data('id');
        const name = $(this).data('name');
        const desc = $(this).data('desc');
        const type = $(this).data('type');
        const active = $(this).data('active');


        // Fill form fields
        $('input[name="edit_id"]').val(id);
        $('input[name="asset_category_name"]').val(name);
        $('input[name="description"]').val(desc);
        $('select[name="active"]').val(active).trigger('change');
        $('select[name="asset_type_id"]').val(type).trigger('change');

    });
    
</script>

@endpush
