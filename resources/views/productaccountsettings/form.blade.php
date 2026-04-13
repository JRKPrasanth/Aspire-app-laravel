@extends('layouts.header')
@section('content')
<h3 class="text-danger">Product Account Settings</h3>
@include('layouts.breadcrumb')


<form method="post" action="" id="prdaccset_form" data-parsley-validate>
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-md-6">

                    <input type="hidden" id="product_accountsetting_id" 
                           name="product_accountsetting_id" 
                           value="{{ $row->product_accountsetting_id }}">

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">
                            <span class="text-danger">*</span> Product Group
                        </label>
                        <div class="col-md-8">
                            <select name="product_group_id" class="form-select select2 product_group_id" required>
                                {!! $product_group_id !!}
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">
                            <span class="text-danger">*</span> Product Category
                        </label>
                        <div class="col-md-8">
                            <select name="product_category_id" class="form-select select2 product_category_id" required>
                                {!! $product_category_id !!}
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">
                            <span class="text-danger">*</span> Product SubCategory
                        </label>
                        <div class="col-md-8">
                            <select name="product_subcategory_id" class="form-select select2 product_subcategory_id" required>
                                {!! $product_subcategory_id !!}
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">
                            <span class="text-danger">*</span> Active
                        </label>
                        <div class="col-md-8">
                            <select name="active" class="form-select active select2">
                                <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                </div>

                <!-- Right Column -->
                <div class="col-md-6">

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">
                            <span class="text-danger">*</span> Product Account Code
                        </label>
                        <div class="col-md-8">
                            <select name="product_acccode_id" class="form-select select2 product_acccode_id" required>
                                {!! $product_acccode_id !!}
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">
                            <span class="text-danger">*</span> Discount Account Code
                        </label>
                        <div class="col-md-8">
                            <select name="disc_acccode_id" class="form-select select2 disc_acccode_id" required>
                                {!! $disc_acccode_id !!}
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-4 col-form-label">
                            <span class="text-danger">*</span> Control Account Code
                        </label>
                        <div class="col-md-8">
                            <select name="control_acccode_id" class="form-select select2 control_acccode_id" required>
                                {!! $control_acccode_id !!}
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row none">
                        <label class="col-md-4 col-form-label">Created By</label>
                        <div class="col-md-8">
                            <select name="created_by" class="form-select select2 created_by">
                                {!! $created_by !!}
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-center gap-2">
                <input type="hidden" name="submit_type" class="submit_type" value="">
                <button type="button" class="btn btn-success px-4 me-2 saveform">Save</button>
                <a href="{{ url($pageModule) }}" class="btn btn-secondary px-4">Cancel</a>
            </div>
        </div>
    </div>
</form>




@endsection
@push('scripts')

<script>
	
$(document).ready(function(){


    
	 <?php if($group_name=='SEMI FINISHED GOODS'){?>
             $('.discount_div,.control_div').hide();  
             $('.control_acccode_id,.disc_acccode_id').attr("required",false);
    <?php } ?>

    <?php if($group_name=='FINISHED GOODS'){?>
             $('.control_div').hide();
             $('.control_acccode_id').attr("required",false);
    <?php } ?>
    
    
    $(document).on('change','.product_group_id',function(){
			var prdgroup=$('.product_group_id').select2('val');
			var condition = ' product_group_id='+prdgroup; 	 
			if(prdgroup != ''){
				$(".product_category_id").jCombo("{{ URL::to('jcomboform?table=m_product_category_t:product_category_id:category_name') }}&order_by=category_name asc"+'&parent='+condition,
				{selected_value:""});
			}
                       
			 
		});
	
           $(document).on('change','.product_category_id',function(){ 
		   	var prdgroup=$('.product_group_id').select2('val');
		   	var category=$('.product_category_id').select2('val');
                        
		   	var condition = ' product_category_id='+category;
		   	if(prdgroup=="")
			{
                           
			    showCustomAlert("Please select product group name","info");
				$('.product_category_id').val('').select2();
                                 
			}
			if(category != ""){
				$(".product_subcategory_id").jCombo("{{ URL::to('jcomboform?table=m_product_subcategory_t:product_subcategory_id:subcategory_name') }}&order_by=subcategory_name asc"+'&parent='+condition,
					{selected_value:""});
			}
                        else{
                            $('.product_subcategory_id').select2('val',['']);
                        }
		});
                
	
    $(document).on('change','.product_group_id',function(){
        
            var product_group =$('.product_group_id option:selected').text();
            if(product_group=="FINISHED GOODS"){
                    $('.control_div').hide();
                    $('.control_acccode_id').attr("required",false);
            }
            else if(product_group=="SEMI FINISHED GOODS"){
                 $('.control_div').hide();
                 $('.discount_div').hide();
                 $('.control_acccode_id').attr("required",false);
                 $('.disc_acccode_id').attr("required",false);
            }
            else{
             $('.control_div').show();
             $('.discount_div').show();   
            }
   	 });
	

    $(document).on('click', '.saveform', function() {
    var btnval = $(this).val();
    $('#savestatus').val(btnval);
    var url = "{{ url('productaccountstngsave') }}";
    var red_url = "{{url('productaccountsettings')}}"
    var formdata = $('#prdaccset_form').serialize();
    var form = $('#prdaccset_form');
    form.parsley().validate();
    var form = $('#prdaccset_form');
    form.parsley().validate();
    if (form.parsley().isValid())
    {

    $.post(url, formdata, function(data)
    {
    var status = data.status;
    var msg = data.message;
    var id = data.id;
    var edit_url = "{{ url('prdaccsettingcreate') }}/" + id;
    if (btnval != 'SAVE' && btnval != 'DRAFT')
    {
    showCustomAlert(msg,status);
    setTimeout(function(){
    window.location.href = red_url;
    }, 1500);
    }
    else
    {
    showCustomAlert(msg,status);
    setTimeout(function(){
    window.location.href = red_url;
    }, 1500);
    }
    });
    }
    });
    });
	
    </script>


@endpush
