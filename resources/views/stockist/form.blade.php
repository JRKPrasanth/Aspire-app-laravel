@extends('layouts.header')
@section('content')


<style type="text/css">
    .select2-container{
  height: auto !important ;
}

.select2-selection__rendered {
 
  font-size: 11px !important;
}




.select2-container--default .select2-selection--multiple{
  border:none !important ;
}
.select2-container .select2-selection--multiple {
    box-sizing: border-box !important ;
    cursor: pointer !important ;
    display: block !important ;
    min-height: 27px !important ;
    user-select: none !important;
    -webkit-user-select: none !important ;
}
.select2-container--default.select2-container--focus .select2-selection--multiple{
   border: none !important;

}

/*.select2-selection--multiple{
    overflow-y:auto !important;
    height: 35px !important;
}*/
</style>
<div class="ajaxLoading"></div>
<h2 class="heads">Stockist   
  <span class="ui_close_btn"><a href="../stockist" class="collapse-close pull-right btn-danger" ></a></span>
</h2>


<div class="card">

<div class="card-body card-block">
<form method="post" action="{{URL::to('stockistsave')}}" id="stockist" class="stockist"  enctype="multipart/form-data">
	 {{ csrf_field() }}

<div class="row">
<div class="col-md-12">


    <!--************************ Body content start here **********************-->
      
    <div class="col-md-4">
          
       
        <div class="form-group row">
            <label for="employee_id" class="form-control-label col-md-5">Stockist Name<span style="color: red;" > * </span></label>
            <div class="col-md-6">
                <input class="form-control stockist_id" id="stockist_id" name="stockist_id" size="16" type="hidden" value="{{ $row->stockist_id }}" readonly>
                <input class="form-control stockist_name " id="stockist_name" required name="stockist_name" type="text" value="{{$row->stockist_name}}"  >
                
            </div>
        </div>
        
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Contact Person</label>
            <div class="col-md-6">
                <input class="form-control contact_person " id="contact_person" name="contact_person" type="text" value="{{$row->contact_person}}"  >
            </div>
        </div>

        

    </div>
    <div class="col-md-4">
    <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Phone Number<span style="color: red;" > * </span></label>
            <div class="col-md-6">
                <input type="text" name="stockist_phone" class="stockist_phone form-control " required id="stockist_phone" value="{{$row->stockist_phone}}" maxlength="10">
            </div>
        </div>


        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Chemist Name</label>
            <div class="col-md-6">
                <select name='chemist_id[]' class='form-control select2 chemist_id' multiple id="chemist_id" >
                    {!! $chemist_id !!}
                </select>
            </div>
        </div>

        
        
        
        </div>
    <div class="col-md-4">

    
         <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Distributor<span style="color: red;" > * </span></label>
            <div class="col-md-6">
                <select name='super_stockist_id' class='form-control select2 super_stockist_id' required id="super_stockist_id" >
                    {!! $super_stockist_id !!}
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="active" class="form-control-label col-md-5">Active</label>
            <div class="col-md-6" >
                <select name='active' rows='5' class='select2 active' id="active" >
                    <option value="Yes" <?php if($row->active == "Yes") echo "selected"; ?> >Yes</option>
                    <option value="No" <?php if($row->active == "No") echo "selected"; ?> >No</option>
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
        
</div>

</div>
</div>
<div class="col-md-12 ">
        <a href="javascript:void(0);" class="add_row additem" rel=".rcopy"><i class="fa fa-plus"></i> ADD</a>
        <div id="preview-area" class="chandru">
        <table class="overflow-y preview address_tbl">
            <thead>
                <tr>
                    <th>Address</th>
                    <th>Country</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Area</th>
                    <th>PinCode</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="address_tbl_lines_body">
                <?php if (count($linedata) < 1) { ?>
                
                        <tr class="rcopy clone">
                            <td><input type="hidden" name="bulk_stockist_lines_id[]" class="form-control input-sm bulk_stockist_lines_id" ></td>
                            
                            <td>
                             <textarea id="bulk_stockist_address" name="stockist_address[]" class="form-control bulk_stockist_address" row="5"  ></textarea>
                            </td>
                            <td>
                                <select name='country_id[]' rows='5' required class='form-control bulk_country_id select2'  id="bulk_country_id" >
                                    {!! $country_id !!}
                                </select>   
                            </td>
                            <td>
                                <select name='state_id[]' rows='5' required class='form-control bulk_state_id select2'  id="bulk_state_id" >
                                    {!! $state_id !!}
                                </select>   
                            </td>
                            <td>
                                <select name='city_id[]' rows='5' required class='form-control bulk_city_id select2'  id="bulk_city_id" >
                                    {!! $city_id !!}
                                </select>   
                            </td>
                            <td>
                                <select name='area[]' rows='5' required class='form-control bulk_area select2'  id="bulk_area" >
                                    {!! $area !!}
                                </select>   
                                <input type="hidden" name="concat_address[]" id="bulk_concat_address" class="bulk_concat_address form-control">
                            </td>
                            <td><input type="text" name="pincode[]" class="form-control input-sm bulk_pincode" ></td>
                            <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                            <td><input type="hidden" name="counter[]"></td>
                        </tr>
                
                <?php } if(count($linedata) >= 1 ) { ?>
                @foreach($linedata as $key=>$value)
                    <tr class="rcopy clone">
                        <td>
                            <input type="hidden" name="bulk_stockist_lines_id[]" class="form-control input-sm bulk_stockist_lines_id" value="{{$value->stockist_lines_id}}" >
                        </td>
                        <td>
                            <textarea id="bulk_stockist_address" name="stockist_address[]" class="form-control bulk_stockist_address" row="5"   >{{$value->stockist_address}}</textarea>
                        </td>
                        <td>
                            <select name='country_id[]' rows='5' required class='form-control bulk_country_id select2'  id="bulk_country_id" >
                                {!! $value->country_id !!}
                            </select>   
                        </td>
                        <td>
                            <select name='state_id[]' rows='5' required class='form-control bulk_state_id select2'  id="bulk_state_id" >
                                {!! $value->state_id !!}
                            </select>   
                        </td>
                        <td>
                            <select name='city_id[]' rows='5' required class='form-control bulk_city_id select2'  id="bulk_city_id" >
                                {!! $value->city_id !!}
                            </select>   
                        </td>

                        <td>
                            <select name='area[]' rows='5' required class='form-control bulk_area select2'  id="bulk_area" >
                                {!! $value->area !!}
                            </select>   
                            <input style="display:none;" type="hidden" name="concat_address[]" id="bulk_concat_address" class="bulk_concat_address form-control" value="">
                        </td>
                        <td><input type="text" name="pincode[]" class="form-control input-sm bulk_pincode" id="bulk_area" value="{{$value->pincode}}" ></td>    
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
    				<a class='btn cancel' onclick="location.href = '{{url::to('stockist')}}'">Cancel</a>
    			
    			</div>
    		</div>
    	</div>

</form>

</div>
</div>



<script>


$(document).ready(function(){


    // $(document).on('change',".teritory_name",function(){
    //     var area = $(".teritory_name option:selected").val();
        
    //     var url = "{{ URL::to('stockistareadoctor') }}/"+area;
    //     $.get(url,function(data){
    //         $(".chemist_id").html(data);
    //     });

    // });

    
    $(".add_row").relCopy(data);
    changeclassfields();
        
    $('.add_row').click(function(){
        changeclassfields();
    });
    function changeclassfields(){
    changeClassName('bulk_doctors_adr_id');
    changeClassName('bulk_doctor_address');
    changeClassName('bulk_area');
    changeClassName('bulk_country_id');
    changeClassName('bulk_state_id');
    changeClassName('bulk_city_id');
    changeClassName('bulk_concat_address');
    changeClassName('sample_time');
}

 var date=new Date();
    //$('.form_date').datetimepicker('setStartDate',date);

    $(document).on('change', '.bulk_country_id', function ()
     {
        var index=$(this).closest('tr').index();
        var country_id = $('.bulk_country_id'+index).val();
        $(".bulk_state_id"+index).jCombo("{{ URL::to('jcomboformlogin?table=m_states_t:state_id:state_name') }}&parent=country_id="+country_id+ '&order_by=state_name asc',
        {selected_value:"$value->bulk_state_id"});

            $(".bulk_city_id"+index).find('option').not(':first').remove();
            $(".bulk_area"+index).find('option').not(':first').remove();
    });
    /*****************Country basted state end************************/
    /*****************State basted city start************************/
    $(document).on('change', '.bulk_state_id', function ()
    {

        var index=$(this).closest('tr').index();
        var state_id = $('.bulk_state_id'+index).val();
        if(state_id){
            $(".bulk_city_id"+index).jCombo("{{ URL::to('jcomboformlogin?table=m_cities_t:city_id:city_name') }}&parent=state_id="+state_id+ '&order_by=city_name asc',
            {selected_value:"$value->bulk_city_id"});
        }
        $(".bulk_area_id"+index).find('option').not(':first').remove();
    });
    /*****************state basted city end************************/
    /*****************city basted area start************************/
    $(document).on('change', '.bulk_city_id', function () {

        var index=$(this).closest('tr').index();
        var city_id = $('.bulk_city_id'+index).val();
        if(city_id){
            $(".bulk_area"+index).jCombo("{{ URL::to('jcomboformlogin?table=m_area_t:area_id:area_name') }}&parent=city_id="+city_id+ '&order_by=area_name asc',
            {selected_value:"$value->bulk_area_id"});
        }
    });
    /*****************city basted area end************************/
     var index = $(this).closest('tr').index();
    index =index+1;
    var area = $(".bulk_area"+index +" option:selected").select2().text();
    var adr = $(".bulk_doctor_address"+index).val();
    var con_adr = adr +"-"+ area;
    $(".bulk_concat_address"+index).val(con_adr);

    $(document).on('change','.bulk_area' , function(){
        var index = $(this).closest('tr').index();
        var area = $(".bulk_area"+index +" option:selected").select2().text();
        var adr = $(".bulk_doctor_address"+index).val();
        if(adr){
            var con_adr = adr +"-"+ area;
            $(".bulk_concat_address"+index).val(con_adr);
        }
    });

    $(document).on('keyup','.bulk_doctor_address' , function(){
        var index = $(this).closest('tr').index();
        var area = $(".bulk_area"+index +" option:selected").select2().text();
        var adr = $(".bulk_doctor_address"+index).val();
        if(area){
            var con_adr = adr +"-"+ area;
            $(".bulk_concat_address"+index).val(con_adr);
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
            notyMsg('info',"You Can't Delete Atleast One row should be there");
        }
    }); 

        var currentTime = new Date()
        var year = currentTime.getFullYear()
        var maxDate = new Date((year-20)+"-12-31");
        $('.birth').datetimepicker({
            endDate:maxDate,
            weekStart: 1,
            todayBtn:  1,
            autoclose: true,
            format: 'dd-mm-yyyy',            
            startView: 2,
            minView: 2,
            forceParse: 0
        });




  // $('.form_datetime').datetimepicker({
  //       //language:  'fr',
  //       weekStart: 1,
  //       todayBtn:  1,
  //       autoclose: true,
  //       todayHighlight: 1,
  //        autoclose: true,
  //       startView: 2,
  //       format: 'dd-mm-yyyy hh:ii',
  //       forceParse: 0,
  //       showMeridian: 1
  //   });
    $('.form_date').datetimepicker({
        Default: false,
       format: 'dd-mm-yyyy hh:ii',
useCurrent: false,
autoclose: true,
keepOpen: false,
    });
    $('.form_time').datetimepicker({
       // language:  'fr',
      
        weekStart: 1,
        todayBtn:  1,
        autoclose: true,
        todayHighlight: 1,
        startView: 1,
        
        format:'h:ii',
        sideBySide: true,
         autoclose: true,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });



});

function changeclassfields(){
    changeClassName('bulk_doctors_adr_id');
    changeClassName('bulk_stockist_address');
    changeClassName('bulk_area');
    changeClassName('bulk_country_id');
    changeClassName('bulk_state_id');
    changeClassName('bulk_city_id');
    changeClassName('bulk_concat_address');
    changeClassName('sample_time');
}

function removeclassfields()
{
    removeClass('bulk_doctors_adr_id');
    removeClass('bulk_stockist_address');
    removeClass('bulk_area');
    removeClass('bulk_country_id');
    removeClass('bulk_state_id');
    removeClass('bulk_city_id');
    removeClass('bulk_concat_address');
    removeClass('sample_time');
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


    /**************** moblie number validation start ***********/   
    $(document).on('keypress', '.stockist_phone', function(ev){
        var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
    });

    $(document).on('keyup', '.stockist_phone', function(ev){
        var count = $('.stockist_phone').val().replace(/\s+/g, '').length;
        var phno = $('.stockist_phone').val();
        if(count == 1){                   
            if(phno == 6 || phno == 7 || phno == 8 || phno == 9 ){
            }
            else{                        
                $('.stockist_phone').val('');
            }                   
        }
        else if(count >=2 ){                        
            var x =  $('.stockist_phone').val().split('');
            if(x[0] == 6 || x[0] == 7 || x[0] == 8 || x[0] == 9 )
            {
            }
            else{
                $('.stockist_phone').val('');
            }
        }
        else{
            $('.stockist_phone').val('');
        }
    });
    /**************** moblie number validation end ***********/

	$(document).on('click','.saveform',function()
    {

    	 $('#panel_add').trigger('click');

    	var btnval		= $(this).val();

    	var url			="{{ url('stockistsave') }}";

        var red_url		="{{ url('stockist') }}";

        var create_url	="{{ url('stockistcreate') }}/0";
        

        validationrule('stockist');
        change_date();
        var formdata	= $('#stockist').serialize();
        var form = $('#stockist');

	       	form.parsley().validate();
	       	var form = $('#stockist');
	       	form.parsley().validate();
            if (form.parsley().isValid())
            { 
            $('.ajaxLoading').show();  
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
                    	   notyMsg(status,msg);
						 
                        setTimeout(function(){
                    	
	        				window.location.href=red_url;
                        }, 1000);
                    }
                });


            }
});



$(document).ready(function(){
    $(".form_time").focusin(function(){
        $(".datetimepicker thead tr:first-child th ").css("display", "none ");
        $(".datetimepicker tfoot th ").css("display", "none");
    });

    // $(".form_time").focusout(function(){
    //     $(".datetimepicker thead tr:first-child th ").css("display", "");
    //     $(".datetimepicker tfoot th ").css("display", "");
    // });
    $(".doa").focusin(function(){
        $(".datetimepicker thead tr:first-child th ").css("display", "");
        $(".datetimepicker tfoot th ").css("display", "");
    });

});

</script>
@include('layouts.php_js_validation')
@endsection
