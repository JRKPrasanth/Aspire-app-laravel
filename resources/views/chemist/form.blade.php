@extends('layouts.header')
@section('content')
<style type="text/css">
   
.bulk_doctor_address{width: 300px;}


@media only screen and (max-width: 1500px) {
    
.bulk_doctor_address{width: 500px !important;}
}

textarea.form-control {
    height: 34px;
}
.bulk_doctor_address{width:120px;}
.country_id{width:120px;}
.state_id{width:120px;}
.city_id{width:120px;}
.bulk_area{width:120px;}
.bulk_concat_address{width:120px;}

.select2-container{
    height: auto;
}
.select2-container--default .select2-selection--multiple{
  border:none !important ;
}
.select2-container .select2-selection--multiple {
    box-sizing: border-box !important ;
    cursor: pointer !important ;
    display: block !important ;
    min-height: 25px !important ;
    user-select: none !important;
    -webkit-user-select: none !important ;
}
.select2-container--default.select2-container--focus .select2-selection--multiple{
   border: none !important;

}

</style>
<div class="ajaxLoading"></div>

<h2 class="heads">  
  chemist<span class="ui_close_btn"><a href="../chemist" class="collapse-close pull-right btn-danger" ></a></span>
</h2>


<div class="card">

<div class="card-body card-block">
<form method="post" action="{{URL::to('chemistsave')}}" id="chemist" class="chemist"  enctype="multipart/form-data" >
	 {{ csrf_field() }}

<div class="row">
<div class="col-md-12">

    <!--************************ Body content start here **********************-->
      
    <div class="col-md-4">
          
        <div class="form-group row">
            <label for="tp_date" class="form-control-label col-md-5">Chemist Name<span style="color: red;" > * </span></label>
            <div class="col-md-6">
                <input class="form-control chemist_id" id="chemist_id" name="chemist_id" size="16" type="hidden" value="{{ $row->chemist_id }}" readonly>
                <input class="form-control chemist_name " id="chemist_name" required name="chemist_name" type="text" value="{{$row->chemist_name}}"  >
            </div>
        </div>

        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Chemist Phone<span style="color: red;" > * </span></label>
            <div class="col-md-6">
                <input type="text" name="chemist_phone" class="chemist_phone form-control " id="chemist_phone" required value="{{$row->chemist_phone}}" maxlength="15"><span style="font-size: 70%;">{format:numeric(3-5),special char(-),numeric(6-8)}</span>
            </div>
        </div>
                     
                            
         <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Chemist Mobile</label>
            <div class="col-md-6">
                <input type="text" name="chemist_mobile" class="chemist_mobile form-control " maxlength="10" id="chemist_mobile" value="{{$row->chemist_mobile}}">
            </div>
        </div>
        
        <!-- <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Chemist Area</label>
            <div class="col-md-6">
            <select name='chemist_area' rows='5' class='form-control select2 chemist_area' id="chemist_area" >
                
            </select>
            
            </div>
        </div> -->
    </div>

    <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Chemist Type<span style="color: red;" > * </span></label>
            <div class="col-md-6">
                <select name='chemist_type' rows='5' class='select2 chemist_type' id="chemist_type" required >
                    <option value="">--Please Select--</option>
                    <option value="Chemist" <? if($row->chemist_type == "Chemist") echo "selected"; ?> >Chemist</option>
                    <option value="Stockist" <? if($row->chemist_type == "Stockist") echo "selected"; ?> >Stockist</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Group Of Trade<span style="color: red;" > * </span></label>
            <div class="col-md-6">
                <select name='group_of_trade' rows='5' class='select2 group_of_trade' id="group_of_trade" required >
                    <option value="">--Please Select--</option>
                    <option value="Individual" <? if($row->group_of_trade == "Individual") echo "selected"; ?> >Individual</option>
                    <option value="Group" <? if($row->group_of_trade == "Group") echo "selected"; ?> >Group</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Product</label>
            <div class="col-md-6">
                <select name='product_id[]' rows='5' multiple class='select2 product_id' id="product_id" >
                    {{!!$product_id!!}}
                </select>
            </div>
        </div>
    </div>

    
    <div class="col-md-4">
        <div class="form-group row">
            <label for="chemist" class="form-control-label col-md-5">Stockist</label>
            <div class="col-md-6">
            <select name='stockist_id[]' multiple rows='5' class='form-control stockist_id select2'  id="stockist_id" >
                {!! $stockist_id !!}
            </select>
              </div>
        </div>
        
        <div class="form-group row" style="display: none;">
            <label for="active" class="form-control-label col-md-5">Created By</label>
            <div class="col-md-6" style="pointer-events:none;">
                <select name='created_by' rows='5' class='select2 created_by' id="created_by" >
              {!! $created_by !!}
                </select>
            </div>
        </div>
       
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Doctor Name<span style="color: red;" > * </span></label>
            <div class="col-md-6">
            <select name='doctor_id[]' multiple rows='5' class='form-control doctor_id  select2' required id="doctor_id" style="width:100%;" >
            {!! $doctor_id !!}
            </select>
            
                
            </div>
        </div> 
        <div class="form-group row" >
            <label for="active" class="form-control-label col-md-5">Active</label>
            <div class="col-md-6" >
                <select name='active' rows='5' class='select2 active' id="active" required >
                    <option value="Yes" <?php if($row->active == "Yes") echo "selected"; ?> >Yes</option>
                    <option value="No" <?php if($row->active == "No") echo "selected"; ?> >No</option>
                </select>
            </div>
        </div>

    </div>
    
</div>
</div>
    

    <!-- Address lines start -->

    <div class="col-md-12 ">
        <a href="javascript:void(0);" class="add_row additem" rel=".rcopy"><i class="fa fa-plus"></i> ADD</a>
        <div id="preview-area" class="chandru">
        <table class="overflow-y preview address_tbl">
            <thead>
                <tr>
                    <th>Chemist Address</th>
                    <th>Country</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Area</th>
                    <th>Pin Code</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody class="address_tbl_body">
                <?php if (count($linedata) < 1) { ?>
                
                        <tr class="rcopy clone">
                            <td style="display: none;">
                            <input type="hidden" name="bulk_chemist_detail_id[]" class="form-control input-sm bulk_chemist_detail_id"></td>
                            <td>
                
                            <textarea class="bulk_chemist_address form-control" name="bulk_chemist_address[]" id="bulk_chemist_address" >
                            </textarea>
                            </td>
        
                            <td style="display:none;">
                                <textarea id="bulk_doctor_address" name="bulk_doctor_address[]" class="form-control bulk_doctor_address"  row="5" ></textarea>
                            </td>
    
<td>
<select name='bulk_country_id[]' rows='5' class='form-control select2 bulk_country_id' id="bulk_country_id" required>
{!! $country_id !!}
</select>
</td>
<td>
<select name='bulk_state_id[]' rows='5' class='form-control select2 bulk_state_id' id="bulk_state_id" required>
{!! $state_id !!}
</select>
</td>
<td>
<select name='bulk_city_id[]' rows='5' class='form-control select2 bulk_city_id' id="bulk_city_id" required>
{!! $city_id !!}
</select>
</td>
<td>
                                <select name='bulk_area[]' rows='5' required class='form-control bulk_area select2'  id="bulk_area" >
                                    {!! $territory_name !!}
                                </select>   
                                <input type="hidden" name="bulk_concat_address[]" id="bulk_concat_address" class="bulk_concat_address form-control">
                            </td>

                            <td>
                            <input type="text" name="bulk_pincode[]" class="form-control input-sm bulk_pincode" id="bulk_pincode">
                            </td>                            
                            
                                    
                                
                            <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                            <td><input type="hidden" name="counter[]"></td>
                        </tr>
                
                <?php } if(count($linedata) >= 1 ) {  ?>
                @foreach($linedata as $key=>$value) 
                <tr class="rcopy clone">
                            <td style="display: none;"><input type="hidden" name="bulk_chemist_detail_id[]" class="form-control input-sm bulk_chemist_detail_id" value="{{$value->chemist_detail_id}}" ></td>
                            <td>
                
                <textarea class="bulk_chemist_address form-control" name="bulk_chemist_address[]" id="bulk_chemist_address" >
                {{$value->chemist_address}}</textarea>
                </td>
                            <td>
                        <select name='bulk_country_id[]' rows='5' class='form-control select2 bulk_country_id' id="bulk_country_id" required>
{!! $country_id !!}
</select>
</td>
<td>
<select name='bulk_state_id[]' rows='5' class='form-control select2 bulk_state_id' id="bulk_state_id" required>
{!! $state_id !!}
</select>
</td>
<td>
<select name='bulk_city_id[]' rows='5' class='form-control select2 bulk_city_id' id="bulk_city_id" required>
{!! $city_id !!}
</select>
</td>
<td>
<select name='bulk_area[]' rows='5' required class='form-control bulk_area select2'  id="bulk_area" >
                                    {!! $territory_name !!}
                                </select>   
</td>
<td>
                            <input type="text" value="{{$value->pincode}}" name="bulk_pincode[]" class="form-control input-sm bulk_pincode" id="bulk_pincode">
                            </td>                            
                            


                        <td style="display:none;">
                            <textarea id="bulk_doctor_address" name="bulk_doctor_address[]" class="form-control bulk_doctor_address" row="5"  >{!! $value->doctor_address !!}</textarea>
                        </td>

                        <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                        <td><input type="hidden" name="counter[]"></td>
                    </tr>
                @endforeach
                <?php } ?>


            </tbody>
        </table>
    </div>

    <!-- Address lines end -->


      <!--*******************-->
    	<div class="row">
    		<div class="col-lg-12 col-md-12">
    			<div class="form-group text-center">
    				
    				<button type="button" class="btn save saveform" value ="save" >Save</button>
    				<button type="button" class="btn save saveform"   value ="savenew" >Save and New</button>
    				<a class='btn cancel' onclick="location.href = '{{url::to('chemist')}}'">Cancel</a>
    			
    			</div>
    		</div>
    	</div>

</form>
</div>
</div>

<script type="text/javascript" src=""></script>
<script>


$(document).ready(function(){
    
window.Parsley.on('form:validated', function(){
    

    $('form').on('select2:select', function(evt) {
         $(".chemist").parsley().validate();
    });
});



    /*****************Country basted state start************************/
    $(document).on('change', '.bulk_country_id', function ()
     {
        var index=$(this).closest('tr').index();
        var country_id = $('.bulk_country_id'+index).val();
        $(".bulk_state_id"+index).jCombo("{{ URL::to('jcomboformlogin?table=m_states_t:state_id:state_name') }}&parent=country_id="+country_id+ '&order_by=state_name asc',
        {selected_value:""});

            $(".bulk_city_id"+index).find('option').not(':first').remove();
            $(".bulk_area"+index).find('option').not(':first').remove();
    });
    /*****************Country basted state end************************/
    /*****************State basted city start************************/
    $(document).on('change', '.bulk_state_id', function () {
        var index=$(this).closest('tr').index();
        var state_id = $('.bulk_state_id'+index).val();
        if(state_id){
            $(".bulk_city_id"+index).jCombo("{{ URL::to('jcomboformlogin?table=m_cities_t:city_id:city_name') }}&parent=state_id="+state_id+ '&order_by=city_name asc',
            {selected_value:""});
        }
        $(".bulk_area"+index).find('option').not(':first').remove();
    });
    /*****************state basted city end************************/
    /*****************city basted area start************************/
    $(document).on('change', '.bulk_city_id', function () {

        var index=$(this).closest('tr').index();
        var city_id = $('.bulk_city_id'+index).val();
        if(city_id){
            $(".bulk_area"+index).jCombo("{{ URL::to('jcomboformlogin?table=m_area_t:area_id:area_name') }}&parent=city_id="+city_id+ '&order_by=area_name asc',
            {selected_value:""});
        }
    });
    /*****************city basted area end************************/
    /**************** moblie number validation start ***********/   
    $(document).on('keypress', '.chemist_mobile', function(ev){
        var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
    });

    $(document).on('keyup', '.chemist_mobile', function(ev){
        var count = $('.chemist_mobile').val().replace(/\s+/g, '').length;
        var phno = $('.chemist_mobile').val();
        if(count == 1){                   
            if(phno == 6 || phno == 7 || phno == 8 || phno == 9 ){
            }
            else{                        
                $('.chemist_mobile').val('');
            }                   
        }
        else if(count >=2 ){                        
            var x =  $('.chemist_mobile').val().split('');
            if(x[0] == 6 || x[0] == 7 || x[0] == 8 || x[0] == 9 )
            {
            }
            else{
                $('.chemist_mobile').val('');
            }
        }
        else{
            $('.chemist_mobile').val('');
        }
    });
    /**************** moblie number validation end ***********/


    /**************** phone number validation start ***********/
    $('.chemist_phone').change(function(event) {
        var regExp = /\d{3,5}\-\d{6,8}/; 
        var txtpan = $(this).val();
        if( txtpan.match(regExp) ){
        }else{
            notyMsg("error","Not a valid Phone number");
            $(".chemist_phone").val('');
            event.preventDefault(); 
        }
    });
    
   

    $(".add_row").relCopy(data);
         changeclassfields();
        
    $('.add_row').click(function(){
        changeclassfields();
    });


	$(document).on('click','.saveform',function()
    {

    	 $('#panel_add').trigger('click');

    	var btnval		= $(this).val();

    	var url			="{{ url('chemistsave') }}";

        var red_url		="{{ url('chemist') }}";

        var create_url	="{{ url('chemistcreate') }}/0";
        

        validationrule('chemist');
        change_date();
        var formdata	= $('#chemist').serialize();
        var form = $('#chemist');

	       	form.parsley().validate();
	       	var form = $('#chemist');
	       	form.parsley().validate();
            if (form.parsley().isValid())
            {   
                $('.ajaxLoading').show()  ;

            	$.post(url,formdata,function(data)
                {
                	var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;

                    if(btnval !='save')
                    {
                        notyMsg(status,msg);
						 

                        setTimeout(function(){
                    	
	        				window.location.href=create_url;

                        }, 1500);
                    }
                    else{
                          $('.ajaxLoading').show();
                    	   notyMsg(status,msg);
						 
                        setTimeout(function(){
                    	
	        				window.location.href=red_url;
                        }, 1000);
                    }
                });


            }
        

    });


    $(document).on('click','.remove',function()
    {
        var index = $(this).closest('tr').index();
        var rowCount = $('.address_tbl tbody tr').length;
        if(rowCount > 1)
        {
            $($(this).closest("tr")).remove();
            removeclassfields();
        }
        else
        {
            notyMsg('error',"You Can't Delete Atleast One row should be there");
        }
    }); 

 
});

function changeclassfields(){
    changeClassName('bulk_chemist_detail_id');
    changeClassName('bulk_doctor_address');
    changeClassName('bulk_chemist_address');
    changeClassName('bulk_country_id');
    changeClassName('bulk_state_id');
    changeClassName('bulk_city_id');
    changeClassName('bulk_area');
    changeClassName('bulk_pincode');
}

function removeclassfields()
{
    removeClass('bulk_chemist_detail_id');
    removeClass('bulk_doctor_address');
    removeClass('bulk_chemist_address');
    removeClass('bulk_country_id');
    removeClass('bulk_state_id');
    removeClass('bulk_city_id');
    removeClass('bulk_pincode');
    removeClass('bulk_area');
}

function changeClassName(className){
    $('.' + className).each(function (index)
    {
        $(this).removeClass(className + '0');
        $(this).addClass(className + index);
    });
}

function removeClass(className)
{
    var rowCount = $('.address_tbl tbody tr').length;
    for(var i=0;i<=rowCount;i++)
    {
        $('.product_tbl tbody tr').find('.'+className).removeClass(className+i);
    }
    $('.' + className).each(function (index)
    {
        $(this).addClass(className + index);
    });
}




</script>



@include('layouts.php_js_validation')
@endsection
