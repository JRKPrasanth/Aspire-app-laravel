
@extends('layouts.header')
@section('content')

<style type="text/css">
	
	@media  only screen and (min-width: 1500px) {

		.bulk_submenu_name {width: 100px !important;}
.bulk_submenu_label {width: 150px !important;}
.bulk_submenu_url {width: 150px !important;}
.bulk_submenu_icon {width: 150px !important;}
.bulk_submenu_region {width: 150px !important;}
.bulk_submenu_order {width: 150px !important;}


	}

	.bulk_submenu_name {width: 150px;}
.bulk_submenu_label {width: 150px;}
.bulk_submenu_url {width: 150px;}
.bulk_submenu_icon {width: 150px;}
.bulk_submenu_region {width: 150px;}
.bulk_submenu_order {width: 150px;}

</style>

<span class="ui_close_btn"></span>


	<!------------------------- breadcrumbs start here --------------------------->
<div class="row">
<div class="col-lg-12 col-md-12">
<!--div class='steps'>
<div class='step complete'>Dashboard</div>
<div class='step active'>Sales</div>
<div class='step'>Sales-Create</div>
<div class='step'>Step 4</div>
</div-->
</div>
</div>
	<!---------------------------------------------------------------------------->



<h2 class="heads">Tools Menu <span class="ui_close_btn"><a href="../toolsmenuconfig" class="collapse-close pull-right btn-danger" onclick="./toolsmenuconfig"></a>
</span></h2>



<form method="post" action="" id="dis_form"   data-parsley-validate>
	<input type="hidden" value="" name="save_status" id="save_status" />
 {{ csrf_field() }}
<div class="card">
<div class="card-body card-block ">
<div class="row">
		<div class="col-md-12">


			<!------------------------------------- Body content start here ---------------------------->
			
		
		
			<div class="row">
				

					<div class="form-group col-md-6">
						<label for="inputIsValid" class="form-control-label col-md-6"><span class="req">*</span> Page Module</label>

						<div class="col-md-6">
						<input class="form-control m_toolmenus_listing_hdr_id" id="m_toolmenus_listing_hdr_id" name="m_toolmenus_listing_hdr_id"  type="hidden" value="{{$row->m_toolmenus_listing_hdr_id}}">

							 <select name="pagemodule"  id="pagemodule" class="pagemodule select2" required style="width: 100%;">
								{!! $pagemodule !!}
							</select>
						</div>
						
					</div>









					<div class="form-group col-md-6">
						<label for="inputIsValid" class="form-control-label col-md-6">Description</label>
						<div class="col-md-6">
							<input type="text" id="description" name="description" class="form-control description" value="{{$row->description}}" style="width: 100%;"  >
						</div>
						
					</div>



				
			</div>
		


	
			<!------------------------------------------------------------------------------------------>

		</div>
	</div>





<div class="row">
	<div class="col-md-12">
<a  href="javascript:void(0);" class="add_row additem btn-info" ><i class="fa fa-plus" ></i> New Item</a>
<a style="display: none;" href="javascript:void(0);" id="btnAdd"  class="onclickrel btn-info"  rel=".rcopy" >
<i class="fa fa-plus"></i> New Item</a>


	<div id="preview-area" class="chandru">
    <table class="overflow-y preview disc_table">


<thead>
<tr>

<th>Submenu Name</th>
<th>Submenu Label</th>
	<th>Submenu Url</th>
<th>Submenu Icon</th>
<th>Submenu Region</th>
<th>Submenu Order</th>
	<th></th>
</tr>
</thead>
<tbody class="disc_lines_body">
	<?php
	//dd($linedata);
	if(count($linedata)>=1) { ?>
@foreach($linedata as $key=>$value)

<tr class="clone rcopy">
    <td>
        <input type="hidden" name="bulk_m_toolmenus_listing_line_id[]" class="form-control input-sm bulk_m_toolmenus_listing_line_id" value="{{$value->m_toolmenus_listing_line_id}}">
    </td>
    <td style="display: none;">
        <input type="hidden" name="bulk_m_toolmenus_listing_hdr_id[]" class="form-control input-sm bulk_m_toolmenus_listing_hdr_id" value="{{$value->m_toolmenus_listing_hdr_id}}">
    </td>
    <td>
        <input type="text" name="bulk_submenu_name[]" required class="form-control input-sm bulk_submenu_name" value="{{$value->submenu_name}}">
    </td>
    <td>
        <input type="text" name="bulk_submenu_label[]" required class="form-control input-sm bulk_submenu_label input_qty_width" value="{{$value->submenu_label}}">
    </td>
    <td>
        <input type="text" name="bulk_submenu_url[]" required class="form-control input-sm bulk_submenu_url" value="{{$value->submenu_url}}">
    </td>
    <td>
        <input type="text" name="bulk_submenu_icon[]" required class="form-control input-sm bulk_submenu_icon" value="{{$value->submenu_icon}}">
    </td>
    <td>
        <input type="text" name="bulk_submenu_region[]" required class="form-control input-sm bulk_submenu_region" value="{{$value->submenu_region}}">
    </td>
    <td>
        <input type="text" name="bulk_submenu_order[]" required class="form-control input-sm bulk_submenu_order" value="{{$value->submenu_order}}">
    </td>

    <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
        <input type="hidden" name="counter[]">
    </td>
</tr>
@endforeach
<?php } if(count($linedata) < 1 ) { ?>
    <tr class="clone cloneRow rcopy">
        <td>
            <input type="hidden" name="bulk_m_toolmenus_listing_line_id[]" class="form-control input-sm bulk_m_toolmenus_listing_line_id" value="">
        </td>
        <td style="display: none;">
            <input type="hidden" name="bulk_m_toolmenus_listing_hdr_id[]" class="form-control input-sm bulk_m_toolmenus_listing_hdr_id" value="">
        </td>
        <td>
            <input type="text" required name="bulk_submenu_name[]" class="form-control input-sm bulk_submenu_name" value="">
        </td>
        <td>
            <input type="text" required name="bulk_submenu_label[]" class="form-control input-sm bulk_submenu_label" value="">
        </td>
        <td>
            <input type="text" required name="bulk_submenu_url[]" class="form-control input-sm bulk_submenu_url" value="">
        </td>
        <td>
            <input type="text" required name="bulk_submenu_icon[]" class="form-control input-sm bulk_submenu_icon" value="">
        </td>
        <td>
            <input type="text" required name="bulk_submenu_region[]" class="form-control input-sm bulk_submenu_region" value="">
        </td>
        <td>
            <input type="text" required name="bulk_submenu_order[]" class="form-control input-sm bulk_submenu_order" value="">
        </td>

        <td>
            <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
            <input type="hidden" name="counter[]">
        </td>
    </tr>

<?php } ?>
</tbody>
</table>
<input type="hidden" name="enable-masterdetail" value="true">
</div>
</div>
</div>
<!-------------------------Linedata End-------------------------------->





	<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
			
			<button type="button" class="btn save saveform" value="save">Save</button>
			<button type="button" class="btn save saveform"   value="savenew">Save and New</button>
			  <a href="{{ url('toolsmenuconfig') }}" class='btn cancel'>Cancel</a>
		</div>
	</div>
</div>


</div>

</div>

	</form>

	<script>



	$(document).ready(function(){



		 $(document).on('click','.saveform',function()
    {


            var btnval		= $(this).val();

            var url			="{{ url('toolsmenuconfighdrsave') }}";
            var url			="{{ url('toolsmenuconfighdrsave') }}";

            var red_url		="{{ url('toolsmenuconfig') }}";
            var create_url	="{{ url('toolsmenuconfighdrcreate') }}/0";
            validationrule('dis_form');
            var formdata	= $('#dis_form').serialize();
            var form = $('#dis_form');
            $('#save_status').val(btnval);

            if(btnval !='applychanges')
            {


                    //form.parsley().validate();
                    var form = $('#dis_form');

                    form.parsley().validate();
                    if (form.parsley().isValid())
                    {

										$.post(url,formdata,function(data)

                    {

                    var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;
                    var edit_url	="{{ url('toolsmenuconfighdrcreate') }}/"+id;
                    if(btnval !='save')
                    {
                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=create_url;
                            }, 1500);
                    }
                    else
                    {
                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=red_url;
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
                    var edit_url	="{{ url('toolsmenuconfighdrcreate') }}/"+id;
                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=edit_url;
                            }, 1500);

                    });
            }
    });




var index = $('.clone').closest('tr').index();
changeclassfields();

$(document).on('click','.add_row',function(){
// alert();
	$('.onclickrel').trigger('click');
	changeclassfields();

});







		$(document).on('click', '.jcr_default_organization_id', function () {

			$(".organization_id").jCombo("{{ URL::to('jcomboform?table=m_organizations_t:organization_id:organization_name') }}&order_by=organization_name asc",
								{ selected_value: '' });
		});

		$(document).on('click', '.jcr_discount_currency_id', function () {

			$(".discount_currency_id").jCombo("{{ URL::to('jcomboform?table=m_currency_t:currency_id:currency_code') }}&order_by=currency_code asc",
								{selected_value: '' });
		});



	$('.discount_name').on('keyup',function(){
	this.value= this.value.toUpperCase();
	});
$(document).on('keypress',".description",function (e) {
    if (String.fromCharCode(e.keyCode).match(/[^a-zA-Z]/g)) return false;
});
$(document).on('keypress',".bulk_submenu_region,.bulk_submenu_order",function (e) {
    if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
});
$(document).on('click','.add_row_old',function(){
	var cloned = $('.disc_table').find('tr:eq(1)').clone();
	cloned.find("input[type=text],input[type=hidden], textarea,input[type=date],select").val("");
	cloned.appendTo('.disc_lines_body');
	//$('.disc_table').find('tr:eq(1)').clone().appendTo('.disc_lines_body');
	//var rowCount = $('.disc_table tbody tr').length;
//alert();
	 //var cloned = $('.disc_table').find('.cloneRow').clone();
	// //cloned.removeClass('cloneRow').find("tr:gt(1)").remove();
	// cloned.removeClass('cloneRow').find("input[type=text], textarea,input[type=date]").val("");
	// cloned.appendTo('.disc_lines_body');

  //cloneRow('disc_table','disc_lines_body');

changeclassfields();

});

$(document).on('click','.remove',function(){
	var rowCount = $('.disc_table tbody tr').length;
	if(rowCount > 1)
	{
	$($(this).closest("tr")).remove();
		changeclassfields();

	}
	else
	{
		notyMsgs('info',"You Can't Delete Atleast One row should be there");
	}
});


    $(document).on('change','#pagemodule',function(){
                  var employee_id=$('#pagemodule').select2('val');
                  // alert(employee_id);
                  // var edit_id=$('#m_toolmenus_listing_hdr_id').val();
                   var url ="{{ URL::to('toolsmenucheck') }}"+'?employee_id='+employee_id;
                  $.get(url, function(data)
                   {
            if($.trim(data)==1)
            {
                notyMsg('info','Already Choosed this Module');
                $("button[type=button]").attr("disabled", "disabled");
            }else{
                $("button[type=button]").removeAttr("disabled");
            }
            
                   });
                });

function changeClassName(className){
$('.' + className).each(function (index) {
$(this).addClass(className);
$(this).addClass(className + index);
if (className == "bulk_line_no") {
$(this).val(index + 1).attr("readonly", 1);
}
});
}


function changeclassfields()
{

		changeClassName('bulk_ar_discount_line_id');
		changeClassName('bulk_discount_percentage');
		changeClassName('bulk_discount_days');
		changeClassName('bulk_comments');
}

	});
	</script>
@include('layouts.php_js_validation')
@endsection
