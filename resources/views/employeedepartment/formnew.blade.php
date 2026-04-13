@extends('layouts.header')
@section('content')
<h3 class="text-danger">Department</h3>
@include('layouts.breadcrumb')
<style>
/* Hide first column in linedesc_table */
#linedesc_table th:nth-child(1),
#linedesc_table tbody td:nth-child(1) {
    display: none;
}

/* Table second column text color */
#linedesc_table tbody td:nth-child(2) {
    color: #FFF;
}

/* Modal styles */
.modal-dialog {
    margin: 30px auto;
    width: 70% !important;
}

/* Schedule BOM left pane */
.shedule_bom_page_lines_div_left > h5 {
    color: #fff;
    margin-left: 15px;
}

.shedule_bom_page_lines_div_left {
    background-color: rgba(26, 126, 136, 0.69);
    border: 2px solid gold;
    border-radius: 10px;
    box-shadow: 5px 0 6px 2px #888;
    padding: 5px;
}

/* Tree load icon */
.treeload_refresh > img {
    height: 20px;
    width: 20px;
}

/* Tree styles */
.sbox ol.tree {
    padding: 0 0 0 30px;
}
.sbox li {
    position: relative;
    list-style: none;
}
.sbox li input {
    position: absolute;
    left: 0;
    opacity: 0;
    cursor: pointer;
    height: 1em;
    width: 1em;
}
.sbox li input + ol {
    background: url(images/toggle-small-expand.png) 40px 0 no-repeat;
    margin-left: -44px;
    height: 1em;
}
.sbox li input + ol > li {
    display: none;
}
.sbox li label {
    background: url(images/folder.png) 15px 1px no-repeat;
    cursor: pointer;
    display: block;
    padding-left: 37px;
}
.sbox li label a {
    color: #FFFFFF;
    text-decoration: none;
}
.sbox li input:checked + ol {
    background: url(images/toggle-small.png) 40px 5px no-repeat;
    padding-left: 80px;
    height: auto;
}
.sbox li input:checked + ol > li {
    display: block;
}

/* Tree menu wrapper */
.shedule_bom_treesmenus {
    margin-top: 30px;
    height: 245px;
    overflow: auto;
}

/* Border and shadow for header */
.bom_header_div_tree.col-md-6 {
    border: 1px solid #999;
    border-radius: 10px;
    box-shadow: 0 0 10px #999;
}

/* Hide default search box */
.dataTables_filter {
    display: none;
}

/* Tree-table styles */
#tree-table {
    width: 100%;
    overflow-x: auto;
}
.treegrid-indent {
    width: 0px;
    height: 16px;
    display: inline-block;
}
.treegrid-expander {
    width: 0px;
    height: 16px;
    color: #07234e;
    display: inline-block;
    cursor: pointer;
}
#tree-table th {
    background: #07234e;
    border: none;
    color: #fff;
}

/* Tree container enhancements */
.list-group-tree {
    border-radius: 8px;
    padding: 15px;
    font-family: "Segoe UI", sans-serif;
}

.list-group-tree .list-group-item {
    border: none;
    background-color: transparent;
    color: #333;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
}

.list-group-tree .list-group-item:hover {
    background-color: #e7f3f8;
    border-left: 4px solid #1a7e88;
    color: #1a7e88;
}

.list-group-tree .collapse .list-group-item {
    padding-left: 25px;
}
.subacc_btn{
	color: red;
    font-size: 15px;
    font-weight: 700;
	}
</style>


<div class="row">
  <div class="col-md-12">
	  <div class="card shadow-lg rounded-4 border-0">
    <div class="list-group list-group-tree well">
      <?php echo $tree_menu; ?>
    </div>
  </div>
</div>
</div>


	<form action="" method="post" class="acc_code_form" id="acc_code_form" data-parsley-validate>
	<input type="hidden" value="" name="savestatus" id="savestatus" />{{ csrf_field()}}

<div class="card">

<div class="card-body card-block">

			
		
	<!------------------------------------------------------------------------------------------>


	
<div class="row g-3">
    <div class="col-md-6">
        <div class="form-group row align-items-center">
            <label for="parent_account_code" class="form-label col-md-6 align1 text-primary fw-bold">Parent Department Code</label>
            <div class="col-md-6">
                <input type="text" name="parent_account_code" class="form-control parent_account_code" id="parent_account_code">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group row align-items-center">
            <label for="parent_account_code_meaning" class="form-label col-md-6 align1 text-primary fw-bold">Parent Department Name</label>
            <div class="col-md-6">
                <input type="text" name="parent_account_code_meaning" class="form-control parent_account_code_meaning" id="parent_account_code_meaning">
            </div>
        </div>

        <input type="hidden" name="child_parent_id" class="form-control child_parent_id">
        <input type="hidden" name="acc_parent_id" class="form-control acc_parent_id">
    </div>
</div>

    
        <!-------------------------Linedata -------------------------------->
				<div class="row">
			  <div class="col-12 linetable">
			<div class="table-responsive">
			  <table class="table table-bordered clone_table">
				<thead class="table-light">
				  <tr>
					<th style="width: 80px;">Line No</th>
					 <th>Sub Department Code</th>
					  <th>Sub Department Name</th>
					  <th>Active</th>
					<th style="width: 60px;"></th>
				  </tr>
				</thead>
			<tbody class="clone_lines_body">
			  @if(count($linedata) > 0)
				@foreach($linedata as $key => $value)
				  <tr class="line-row">
                                 
                            <td>
                                <input type="hidden" name="bulk_department_line_id[]" class="form-control input-sm bulk_department_line_id" value="{{ $value->department_line_id }}">
								 <input type="hidden" name="parent_id[]" class="bulk_parent_id">

                          
                           <input type="hidden" name="bulk_department_id[]" class="form-control input-sm bulk_department_id" value="" >
                           
                                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no"  value="" readonly="readonly">
                            </td>
                            <td >
                                <input type="text" name="bulk_sub_department_code[]" class="form-control input-sm bulk_sub_department_code" tabindex="1" value="" required>
                            </td>
                            <td >
                                <input type="text" name="bulk_sub_department_name[]" class="form-control input-sm bulk_sub_department_name " tabindex="2"  value="" required>
                            </td>
                            
                            <td >
                                <select name="bulk_active[]" class="form-select select2 bulk_active" required  tabindex="3">
                                    <option value="">--Please Select--</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </td>  

        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger remove-row">
            <i class="fas fa-minus-circle"></i>
          </button>
        </td>
      </tr>
    @endforeach
  @else
    <tr class="line-row">
       
                                <td>
									<input type="hidden" name="parent_id[]" class="bulk_parent_id">
                                    <input type="hidden" name="bulk_department_line_id[]" class="form-control input-sm bulk_department_line_id" value="">
                          
                                    <input type="hidden" name="bulk_department_id[]" class="form-control input-sm bulk_department_id" value="">
                             
                               
                                    <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="">
                                </td>
                                <td >
                                    <input type="text" name="bulk_sub_department_code[]" class="form-control input-sm bulk_sub_department_code" value="" required>
                                </td>
                                <td >
                                    <input type="text" name="bulk_sub_department_name[]" class="form-control input-sm bulk_sub_department_name " value="" required>
                                </td>
                               
                                <td >
                                    <select name="bulk_active[]" class="form-select select2 bulk_active" required >
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>

                                    </select>
                                </td>
      <td class="text-center">
        <button type="button" class="btn btn-sm btn-danger remove-row">
          <i class="fas fa-minus-circle"></i>
        </button>
      </td>
    </tr>
  @endif
</tbody>

  </table>

  <div class="text-end">
    <button type="button" class="btn btn-success btn-sm add-row">
      <i class="fas fa-plus-circle"></i> Add Row
    </button>
  </div>
</div>

  </div>
</div>
<!-- END -->

  <div class="text-center mt-4">
    <button type="button" class="btn btn-success saveform">Save</button>
  </div>
	
</div>
</div>   

<!-------------------------Linedata End-------------------------------->	

	</form>
	

@endsection
@push('scripts')


<script>
	
	// Add Row
$(document).on('click', '.add-row', function () {
    const $lastRow = $('.clone_lines_body tr:last');
    const $newRow = $lastRow.clone(false, false); // clone without events or data

    // Clear all input and select values in the cloned row
    $newRow.find('input').val('');
    $newRow.find('select').val('').trigger('change');

    // Remove any Select2 artifacts before reinitializing
    $newRow.find('select.select2').each(function () {
        if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
        $(this).removeAttr('data-select2-id'); 
       $(this).next('.select2').remove(); // remove the select2 container
    });

    // Append the cleaned-up cloned row
    $('.clone_lines_body').append($newRow);

    // Reinitialize select2
    $newRow.find('select.select2').select2({ width: '100%' });

		// Update line numbers
		updateLineNumbers();
	});



  // Remove button
  $(document).on('click', '.remove-row', function () {
    const rowCount = $('.clone_lines_body tr').length;
    if (rowCount > 1) {
      $(this).closest('tr').remove();
      updateLineNumbers();
    } else {
      showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
    }
  });

  // Renumber Line Nos
  function updateLineNumbers() {
    $('.clone_lines_body tr').each(function (index) {
      $(this).find('.bulk_line_no').val(index + 1);
    });
  }	
	
	
	
	
$(document).ready(function() {
    $(".list-group-tree").on('click', "[data-toggle=collapse]", function (e) {
        e.preventDefault();
        $(this).toggleClass('in');
        $(this).next(".list-group.collapse").collapse('toggle');
    });
});	
	
// save function

		$(document).on('click','.saveform',function(){
		   var btnval		= $(this).val();
			
			if(btnval == 'APPLYCHANGES')
                var savestatus = 'APPLY CHANGES';
			else(btnval == 'SAVE')
                var savestatus = 'SAVE';
			
			 $('#savestatus').val(savestatus);
			 $('.submit_type').val("save");
			
			 var url		= "{{ URL::to('departmentnewsave') }}"; 
			var red_url = "{{ URL::to('accountcodesnew') }}";
			 var formdata	= $('#acc_code_form').serialize();
			 var form = $('#acc_code_form');
			 if(btnval != 'APPLYCHANGES'){ 
			     form.parsley().validate();
		          var form = $('#acc_code_form');
		          form.parsley().validate();
				  if(form.parsley().isValid()){
				    $.post(url,formdata,function(data)	
					  {
					      var status      = data.status;
						  var msg         = data.message;
						    var id          = data.id;
					
						
						if(btnval !='SAVE')
                              {                        
                               showCustomAlert(msg,status);
                                setTimeout(function(){ 
							 location.reload();
                                }, 1500);
                              }
                            else{
                                showCustomAlert(msg,status);
                                setTimeout(function(){
                                    location.reload();

                                }, 1500);
                               }
					  });
				 }
			  }
			else
			{
			   $.post(url,formdata,function(data)
				{
						var status = data.status;
						var msg    = data.message;
						var id     = data.id;
						var edit_url	="{{ URL::to('employeedepartmentnew') }}";
								showCustomAlert(msg,status);
								setTimeout(function(){
								window.location.href=edit_url;
								}, 1500);
					});
				}
		});	

			 /*Save Function end*/

	/*** sub module open start **/
	 $(document).on('click','.subacc_btn',function(){
        var id=$(this).data("id");
           addfunction(id);
		$(".subacc_btn").each(function(index){
           if(index!=0)
		 {
		 $('.bulk_sub_department_code'+index).closest('tr').remove();
		 }
		 else
		 {
                    $('.bulk_department_line_id0').val('');
                    $('.bulk_sub_department_code0').val('');
                    $('.bulk_sub_department_name0').val('');
                    $('.bulk_description0').val('');
                    $('.bulk_active0').val('Yes').change();
		 }
             })
    });
	
  /***   sub module add function start ***/
			function addfunction(id){

		     $.get("{{ URL::to('subdepartment') }}/"+id,function(data){
             $('.parent_account_code_meaning').val(data['query'][0].sub_department_name);
             $('.parent_account_code').val(data['query'][0].sub_department_code);
             $('.child_parent_id').val(data['query'][0].department_line_id);
             $('.acc_parent_id').val(data['query'][0].department_id);
             
               var sub=data.sub;
           
$.each(sub, function(k, value) {
    if (k != 0) {
        $('.add-row').trigger('click');
    }

    $('.bulk_department_line_id').eq(k).val(value.department_line_id);
    $('.bulk_sub_department_code').eq(k).val(value.sub_department_code);
    $('.bulk_sub_department_name').eq(k).val(value.sub_department_name);
    $('.bulk_active').eq(k).val(value.active).change();
    $('.bulk_parent_id').eq(k).val(value.parent_class_id);
})

               
            });
		} 

</script>

@endpush