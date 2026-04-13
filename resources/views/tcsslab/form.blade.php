@extends('layouts.header')
@section('content')
<h3 class="text-danger">TCS Slab(%)</h3>
@include('layouts.breadcrumb')



<div class="card shadow-lg rounded-4 border-0">
<div class="card-body card-block">
    <form id="tcs_form" action="" data-parsley-validate>
        <?php $data = \Session::get('data');
        if (isset($data[$pageMethod]['save'])) { ?>

            <input type="hidden" value="" name="savestatus" id="savestatus" />

            <input type="hidden" name="edit_id" value="{{$row->tcs_slab_id}}" id="edit_id" /> {{ csrf_field()}}
            <!------------------------------------- Body content start here ---------------------------->
            <div class="row">
                <div class="col-md-12">



                    <div class="row">
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-5">
                                    <span style="color:red;">*</span> TCS Percentage</label>
                                <div class="col-md-7">
                                    <input type="text" name="tcs_percentage" id="tcs_percentage"
                                        value="{{$row->tcs_percentage}}" class="form-control tcs_percentage" required
                                        tabindex="1">
                                    <span class="btn btn-danger dup_name" style="display:none;"></span>
                                </div>
                            </div>
                            <div class="row mb-3" style="display:none;">
                                <label for="start_date" class="col-form-label col-md-5">Start Date</label>
                                <div class="col-md-7">
                                    <div class="input-group date form_date col-md-12" data-date=""
                                        data-date-format="dd MM yyyy" data-link-field="dtp_input2"
                                        data-link-format="yyyy-mm-dd">
                                        <input class="form-control start_date " id="start_date" name="start_date"
                                            class="start_date" size="16" type="text" value="{{$row->start_date}}"
                                            style="width: 100%;">
                                        <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 none">
                                <label for="created_by" class="col-form-label col-md-5">Created By</label>
                                <div class="col-md-7">
                                    <select name='created_by' rows='5' class='form-control created_by select2' required
                                        tabindex="4">
                                        {!! $created_by !!}
                                    </select>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-5">
                                    <span style="color:red;">*</span> TCS Types</label>
                                <div class="col-md-7">
                                    <input type="text" name="tcs_type" id="tcs_type" value="{{$row->tcs_type}}"
                                        class="form-control tcs_type" required tabindex="2">
                                </div>
                            </div>
                            <div class=" row mb-3" style="display:none;">
                                <label for="end_date" class="col-form-label col-md-5">End Date</label>
                                <div class="col-md-7">
                                    <div class="input-group date form_date col-md-12" data-date=""
                                        data-date-format="dd MM yyyy" data-link-field="dtp_input2"
                                        data-link-format="yyyy-mm-dd">
                                        <input class="form-control end_date " id="end_date" name="end_date" class="end_date"
                                            size="16" type="text" value="" style="width:100%;">
                                        <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-4">
                            <div class="row mb-3">
                                <label for="inputIsValid" class="col-form-label col-md-5">Active</label>
                                <div class="col-md-7">
                                    <select name="active" class="form-control active select2" tabindex="3">
                                        <option value="Yes" <?php if ($row->active == 'Yes') {
                                            echo "selected";
                                        } ?>>Yes
                                        </option>
                                        <option value="No" <?php if ($row->active == 'No') {
                                            echo "selected";
                                        } ?>>No
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-success px-4 save_form">Save</button>
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
      <table id="AccountsTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>TCS Percentage</th>
            <th>TCS Type</th>
            <th>Active</th>
            <th>Created By</th>
            <th>Actions</th>
          </tr>
          <tr class="table-danger">
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
      var table = $('#AccountsTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getTcsslabData') }}",
        columns: [

          { data: 'tcs_percentage', name: 'tcs_percentage' },
          { data: 'tcs_type', name: 'tcs_type' },
          { data: 'active', name: 'active' },
          { data: 'first_name', name: 'tb_users.first_name' },

          {
            data: 'tds_slab_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
          <button class="btn btn-sm btn-primary edit-btn" data-id="${row.tds_slab_id}" data-percentage="${row.tcs_percentage}" data-type="${row.tcs_type}" data-active="${row.active}">
            <i class="bi bi-pencil"></i>
          </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
          <button class="btn btn-sm btn-danger delete-btn" data-id="${row.tds_slab_id}">
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
    var tcs_percentage = $(".tcs_percentage").val();
    var edit_id = $("#edit_id").val();

    $.ajax({
        cache: false,
        url: "{{ URL::to('tcsslabcheckname/') }}",
        type: 'GET',
        dataType: 'json',
        async: false,
        data: {tcs_percentage: tcs_percentage, edit_id: edit_id},
        success: function (response){
            if (response == 1)
            {
                $('.dup_name').html('TCS Percentage:' + tcs_percentage + ' Already Exists');
                $('.dup_name').show();
                $(".tcs_percentage").val('');
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


			
         $(document).on('click','.save_form',function()
                {
                    var url	="{{URL::to('tcsslabsave')}}";
                    var form = $('#tcs_form');
                    form.parsley().validate();
                    var form = $('#tcs_form');
                    form.parsley().validate();
                    duplicate_validate();

                    var data	= $('#tcs_form').serialize();
					if (form.parsley().isValid()&&dup_chk==true){
                    $.post(url, data, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        showCustomAlert(msg,status);
                       $('#AccountsTbl').DataTable().ajax.reload();

                    });
                }
                });


// edit function
	$(document).on('click', '.edit-btn', function () {

    const id = $(this).data('id');
    const percentage = $(this).data('percentage');
    const type = $(this).data('type');
    const active = $(this).data('active');

	var url ="{{ URL::to('tcsslabedit') }}/" +id; 
	
	$.get(url,function(data){
    var data = $.trim(data);
     if(data == 0) {
						
    // Fill form fields
    $('input[name="edit_id"]').val(id);
    $('input[name="tcs_percentage"]').val(percentage);
    $('input[name="tcs_type"]').val(type);
    $('select[name="active"]').val(active).trigger('change');
		 
	}else{
		
       showCustomAlert("You Can't be Edit this TCS, Already used","warning");
		
       }
    }); 	 
		 
	});

	$(document).on('keypress change', '.tcs_percentage', function(ev){
            		var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});
			
			
  /*copy past validation*/
  $('.tcs_percentage').bind("cut copy paste", function(e) {
        e.preventDefault();
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
					url: "{{ url('tcsslabdelete') }}/" + deleteId,
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
			
			
</script>

@endpush
