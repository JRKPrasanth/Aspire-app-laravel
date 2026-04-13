@extends('layouts.header')
@section('content')


<style type="text/css">
    
    @media only screen and (min-width:1500px) {

        .bulk_doctor_address {width: 400px !important;}
        .bulk_area {width: 300px !important;}
        .bulk_country_id {width: 300px !important;}
        .bulk_state_id {width: 300px !important;}
        .bulk_city_id {width: 300px !important;}
    }

.sample_time{
    width: 150px;
}
    .bulk_doctor_address {width: 500px;}
    .bulk_area {width: 300px;}  
    .bulk_country_id {width: 300px !important;}
    .bulk_state_id {width: 300px !important;}
    .bulk_city_id {width: 300px !important;}
    .select2-container--default .select2-selection--multiple{
  border: none;
}
.select2-container--default.select2-container--focus .select2-selection--multiple{
  border: none;
}
.select2-container{
    box-sizing: border-box;
    display: inline-block;
    font-size: 11px;
    margin: 0;
    background-color: #fff;
    border: 1px solid #375a80;
    border-radius: 5px;
    box-shadow: none;
    color: #000;
    text-align: center;
    max-width: 100%;
    transition: all 300ms linear 0s;
    position: relative;
    vertical-align: middle;
    height: auto;
}

.datetimepicker tfoot th{
    display: none !important;
}
textarea.form-control {
    height: 34px;
    /* border: 1px solid #112e7a; */
}
</style>
<div class="ajaxLoading"></div>
<h2 class="heads">Doctor   
  <span class="ui_close_btn"><a href="../doctor" class="collapse-close pull-right btn-danger" ></a></span>
</h2>


<div class="card">

<div class="card-body card-block">
<form method="post" action="{{URL::to('doctorsave')}}" id="doctor" class="doctor"  enctype="multipart/form-data">
	 {{ csrf_field() }}

<div class="row">
<div class="col-md-12">


    <!--************************ Body content start here **********************-->
      
    <div class="col-md-4">
          
        <div class="form-group row">
       <?php
        $edit_id = $row->doctor_id; 
        if ($edit_id == '' )
           $doc_name = "Dr.";
        else
           $doc_name = $row->doctor_name;
         ?>
            <label for="doctor_name" class="form-control-label col-md-4 ">First Name<span style="color: red;" > * </span></label>
            <div class="col-md-6">
                <input class="form-control doctor_id" id="doctor_id" name="doctor_id" size="16" type="hidden" value="{{ $row->doctor_id }}" readonly>
            
                <input class="form-control doctor_name " id="doctor_name" name="doctor_name" type="text" required value="{{$doc_name}}"  >
            </div>
        </div>

        <div class="form-group row">
            <label for="last_name" class="form-control-label col-md-4">Last Name</label>
            <div class="col-md-6">
                <input type="text" name='last_name' class='form-control last_name' id="last_name" value="{{$row->last_name}}" >
            </div>
        </div>

        <div class="form-group row">
            <label for="doctor_type" class="form-control-label col-md-4">Doctor Type <span style="color: red;" > * </span></label>
            <div class="col-md-6">
                <select name='doctor_type' class='form-control select2 doctor_type' id="doctor_type" required >
                    <option value="" >--Please Select--</option>
                    <option value="Purchasing" <?php if($row->doctor_type == "Purchasing") echo "selected"; ?> >Purchasing</option>
                    <option value="Prescriber" <?php if($row->doctor_type == "Prescriber") echo "selected"; ?> >Prescriber</option>
                </select> 
            </div>
        </div>
        <div class="form-group row">
            <label for="doctor_phone_number" class="form-control-label col-md-4">Phone No</label>
            <div class="col-md-6">
                <input type="text" name='doctor_phone_number' class='form-control doctor_phone_number' id="doctor_phone_number" value="{{$row->doctor_phone_number}}" maxlength="10" >
                <span class="btn btn-danger dup_name" style="display:none;"></span>
            </div>
        </div>

        
        <div class="form-group row">
            <label for="doctor_email_id" class="form-control-label col-md-4">Email Id</label>
            <div class="col-md-6">
                <input type="text" name='doctor_email_id' rows='5' class='form-control doctor_email_id '  id="doctor_email_id" value="{{$row->doctor_email_id}}">
                <span class="btn btn-danger dup_name2" style="display:none;"></span>
                <span class="btn btn-danger email_vali" id="" style="display:none;"> Email format is example123@gmail.com</span>
            </div>
        </div>

        <div class="form-group row" >
            <label for="gender" class="form-control-label col-md-4" >Gender<span style="color: red;" > * </span></label>
            <div class="col-md-6" style="display: flex;">


                <div class="c-radio">
                <input type="radio" name="gender" class="gender" id="gender" required value="Male" <?php if($row->gender == "Male") echo "checked"; ?> >
                <span class="check_mark"></span> <label class="loginlink"> Male</label>
                </div>
                <div class="c-radio">
                <input type="radio" name="gender" class="gender" id="gender" value="Female" <?php if($row->gender == "Female") echo "checked"; ?>>
                <span class="check_mark"></span> <label class="loginlink"> Female</label>
                </div>


                
                
            </div>
        </div>

        <div class="form-group row" >
            <label for="marital_status" class="form-control-label col-md-4" >Marital Status</label>
            <div class="col-md-6" style="display: flex;">



                <div class="c-radio">
                <input type="radio" name="marital_status" class="marital_status" id="marital_status" value="Single" <?php if($row->marital_status == "Unmarried") echo "checked"; ?> >
                <span class="check_mark"></span> <label class="loginlink"> Unmarried</label>
                </div>
                <div class="c-radio">
                 <input type="radio" name="marital_status" class="marital_status" id="marital_status" value="Married" <?php if($row->marital_status == "Married") echo "checked"; ?> >
                <span class="check_mark"></span> <label class="loginlink"> Married</label>
                </div>


                
               
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group row" id="chemist">
            <label for="chemist" class="form-control-label col-md-4">Chemist</label>
            <div class="col-md-6">
            <select name='chemist_id[]' multiple rows='5' class='form-control chemist_id select2'  id="chemist_id" style="width: 283px;" >
                {!! $row->chemist_id !!}
            </select>
              
            </div>
        </div>
        <div class="form-group row" id="stockist">
            <label for="chemist" class="form-control-label col-md-4">Stockist</label>
            <div class="col-md-6">
            <select name='stockist_id[]' multiple rows='5' class='form-control stockist_id select2'  id="stockist_id" style="width: 283px;" >
                {!! $row->stockist_id !!}
            </select>
              
            </div>
        </div>
        <div class="form-group row">
            <label for="grade" class="form-control-label col-md-4">Category</label>
            <div class="col-md-6">
            <select name='grade' rows='5' class='form-control grade select2'  id="grade" >
                {!! $row->grade !!}
            </select>
              
            </div>
        </div>

        <div class="form-group row">
            <label for="degree" class="form-control-label col-md-4">Degree</label>
            <div class="col-md-6">
            <select name='degree[]' multiple rows='5' class='form-control degree select2'  id="degree" >
                {!! $row->degree !!}
            </select>  
            </div>
        </div>

        <div class="form-group row">
            <label for="specialization" class="form-control-label col-md-4">Specialization<span style="color: red;" > * </span></label>
            <div class="col-md-6">
            <select name='specialization[]' multiple class='form-control specialization select2' required id="specialization" >
                {!! $row->specialization !!}
              </select>
                
            </div>
        </div>

     
        
        <div class="form-group row">
            <label for="doa" class="form-control-label col-md-4">DOA</label>
            <div class="col-md-6">
            
            <div class="input-group date doa col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input class="form-control doa form_date " id="doa" name="doa" size="16" type="text" value="{{$row->doa}}"  >
            </div>

            </div>
        </div>

        <div class="form-group row">
            <label for="dob" class="form-control-label col-md-4">Date Of Birth </label>
            <div class="col-md-6">
            
            <div class="input-group date dob col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input class="form-control dob birth" id="dob" name="dob" size="16" type="text" value="{{$row->dob}}"  >
            </div>

            </div>
        </div>

    </div>

    <div class="col-md-4">
      
       

        <div class="form-group row">
            <label for="from_time" class="form-control-label col-md-4">From Time </label>
            <div class="col-md-6">
            
            <div class="input-group date from_time col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input class="form-control from_time form_time" id="from_time" name="from_time" size="16" type="text" value="{{$row->from_time}}" />
            </div>

            </div>
        </div>

        <div class="form-group row">
            <label for="to_time" class="form-control-label col-md-4">To Time </label>
            <div class="col-md-6">
            
            <div class="input-group date to_time col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input class="form-control to_time form_time" id="to_time" name="to_time" size="16" type="text" value="{{$row->to_time}}"  />
            </div>

            </div>
        </div>

        <div class="form-group row">
            <label for="days" class="form-control-label col-md-4">Days </label>
            <div class="col-md-6">
            <select multiple class="form-control days select2" id="days" name="days[]"  >

                {!! $row->days !!}
            </select>
               
            </div>
        </div>

        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Remarks</label>
            <div class="col-md-6">
              <textarea id="remarks" name="remarks" class="form-control remarks" value="{{$row->remarks}}" row="5"  >{!! $row->remarks !!}</textarea>
            </div>
        </div>

        <!-- <div class="form-group row" >
            <label for="active" class="form-control-label col-md-4">Created By</label>
            <div class="col-md-6" style="pointer-events:none;">
                <select name='created_by' rows='5' class='select2 created_by' id="created_by" >
              {!! $row->created_by !!}
                </select>
            </div>
        </div> -->
      

        <div class="form-group row">
            <label for="last_name" class="form-control-label col-md-4">Association</label>
            <div class="col-md-6">
                <input type="text" name='association' class='form-control association' id="association" value="{{$row->association}}" >
            </div>
        </div>

        <div class="form-group row">
            <label for="active" class="form-control-label col-md-4">Active<span style="color: red;" > * </span></label>
            <div class="col-md-6">
                <select name='active' rows='5' class='select2 active' id="active" required >
                    <option value="Yes" <?php if($row->active == "Yes") echo "selected" ?> >Yes</option>
                    <option value="No" <?php if($row->active == "No") echo "selected" ?> >No</option>
                </select>
            </div>
        </div>

        
    </div>

</div>
</div>
    
      <!--*******************-->

    <!-- Address lines start -->

    <div class="col-md-12 ">
        <a href="javascript:void(0);" class="add_row additem" rel=".rcopy"><i class="fa fa-plus"></i> ADD</a>
        <div id="preview-area" class="chandru">
        <table class="overflow-y preview address_tbl">
            <thead>
                <tr>
                    <th>Clinic OR Hospital Name</th>
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
                            <td><input type="hidden" name="doctors_adr_id[]" class="form-control input-sm bulk_doctors_adr_id" ></td>
                            
                            <td>
                            <input type="text" name="clinic_name[]" class="form-control input-sm bulk_clinic_name" >
                            </td>
                            
                            
                            <td>
                                <textarea id="bulk_doctor_address" name="doctor_address[]" class="form-control bulk_doctor_address" row="5" ></textarea>
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
                            <td><input type="text" name="pin_code[]" class="form-control input-sm bulk_pin_code" ></td>
                            <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                            <td><input type="hidden" name="counter[]"></td>
                        </tr>
                
                <?php } if(count($linedata) >= 1 ) { ?>
                @foreach($linedata as $key=>$value)
                    <tr class="rcopy clone">
                        <td>
                            <input type="hidden" name="doctors_adr_id[]" class="form-control input-sm bulk_doctors_adr_id" value="{{$value->doctors_adr_id}}" >
                        </td>
                        <td>
                            <input type="text" name="clinic_name[]" class="form-control bulk_clinic_name" value="{{$value->clinic_name}}" >
                            </td>
                            
                        <td>
                            <textarea id="bulk_doctor_address" name="doctor_address[]" class="form-control bulk_doctor_address" row="5"  >{!! $value->doctor_address !!}</textarea>
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
                            <input type="hidden" name="concat_address[]" id="bulk_concat_address" class="bulk_concat_address form-control" value="{{$value->concat_address}}">
                        </td>
                        <td>
                            <input type="text" name="pin_code[]" class="form-control bulk_pin_code" value="{{$value->pin_code}}" >
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

    	<div class="row">
    		<div class="col-lg-12 col-md-12">
    			<div class="form-group text-center">
    				
    				<button type="button" class="btn save saveform" value ="save" >Save</button>
    				<button type="button" class="btn save saveform"   value ="savenew" >Save and New</button>
    				<a class='btn cancel' onclick="location.href = '{{url::to('doctor')}}'">Cancel</a>
    			
    			</div>
    		</div>
    	</div>

</form>

</div>
</div>

<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.css')}}">
<script>


    var dup_chk = true;
    function duplicate_validate()
    {
        var phone_no = $(".doctor_phone_number").val();
        var email_id = $(".doctor_email_id").val();
        var edit_id = $("#doctor_id").val();
        var url = "{{URL::to('drphonechk')}}";
        if(email_id != "" && phone_no != ""){
            $.ajax({
                cache: false,
                url: url,
                type: 'GET',
                dataType: 'json',
                async : false,
                data: {doctor_phone_number : phone_no,doctor_email_id:email_id,edit_id : edit_id},
                success: function(response)
                {
                    $.each(response,function(index,value){
                        if(value == 1 ){
                            $('.dup_name').html('Doctor Phone Number:'+phone_no+' Already Exists');
                            $('.dup_name').show();
                            $(".doctor_phone_number").val('');
                            dup_chk = false;        
                        }else if(value == 2){
                            $('.dup_name2').html('Doctor Email Id:'+email_id+' Already Exists');
                            $('.dup_name2').show();
                            $(".doctor_email_id").val('');
                            dup_chk = false;  
                        }else if(response == 0){
                            var html ="";
                            $('.dup_name').hide();
                            $('.dup_name2').hide();
                            dup_chk  = true;
                        }
                    });
                        
                },
                error: function(xhr, resp, text)
                {
                    alert("error");
                    console.log(xhr, resp, text);
                }
            });
        }
    }


$(document).ready(function(){
$('#chemist').hide();
$('#stockist').hide();
$(document).on('change', '.doctor_type', function ()
     { 
        var doctor_type = $('.doctor_type').val();
        if(doctor_type == "Purchasing")
        {
            $('#stockist').show();
            $('#chemist').hide();
        }
        else if(doctor_type == "Prescriber")
        {
            $('#chemist').show();
            $('#stockist').hide();
        }
        else
        {
            $('#chemist').hide();
            $('#stockist').hide();
        }
     });

    /*****************Country basted state start************************/
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

    /**************** moblie number validation start ***********/
    $(document).on('keypress', '.doctor_phone_number', function(ev){
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
            return true;
        }
        ev.preventDefault();
        return false;
    });

    $(document).on('keyup', '.doctor_phone_number', function(ev){
        var count = $('.doctor_phone_number').val().replace(/\s+/g, '').length;
        var phno = $('.doctor_phone_number').val();
        if(count == 1){                   
            if(phno == 6 || phno == 7 || phno == 8 || phno == 9 ){
            }
            else{                        
                $('.doctor_phone_number').val('');
            }                   
        }
        else if(count >=2 ){                        
            var x =  $('.doctor_phone_number').val().split('');
            if(x[0] == 6 || x[0] == 7 || x[0] == 8 || x[0] == 9 )
            {
            }
            else{
                $('.doctor_phone_number').val('');
            }
        }
        else{
                
            $('.doctor_phone_number').val('');
        }
    });
    /**************** moblie number validation end ***********/


    /**************** email validation start ***********/
    function ValidateEmail(email) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
    };
    
    $(document).on('change','.doctor_email_id',function()
    {
        var mail = $('#doctor_email_id').val();
            if(mail !=''){
            var mailvalid = ValidateEmail(mail);
            if ( mailvalid == true) {
                $('.email_vali').hide();
            }
            else {
                $('.email_vali').show();
            }
        }
    });
    
    $(document).on('keyup', '.doctor_email_id', function(){
        $('.email_vali').hide();
        $(".dup_name2").hide();
    });
    /**************** email validation end ***********/


    $(".add_row").relCopy(data);
    changeclassfields();
        
    $('.add_row').click(function(){
        changeclassfields();
    });
    
    $(".doctor_phone_number").keyup(function(){
        $(".dup_name").hide();
    });


	$(document).on('click','.saveform',function()
    {

    	var btnval		= $(this).val();
    	var url			="{{ url('doctorsave') }}";
        var red_url		="{{ url('doctor') }}";
        var create_url	="{{ url('doctorcreate') }}/0";        

        validationrule('doctor');
        var formdata	= $('#doctor').serialize();
        var form = $('#doctor');

       	form.parsley().validate();
       	var form = $('#doctor');
       	form.parsley().validate();
        duplicate_validate();
        var doctorname = $('.doctor_name').val();

        if( form.parsley().isValid() && doctorname != "Dr.")
        {
            if(dup_chk ){
                $('.ajaxLoading').show();
                $.post(url,formdata,function(data)
                {
                    var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;

                    if(btnval !='save')
                    {
                        notyMsg(status,msg);
                        window.location.href=create_url;                        
                    }
                    else
                    {
                        notyMsg(status,msg);
                        window.location.href=red_url;
                    }
                }); 
            }
        }else{
            if(doctorname == "Dr." ){
                notyMsg('info',"please fill doctor name");
            }
        }
        return false;

    });

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
        //var area = $(".bulk_area"+index +" option:selected").select2().text();
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
    changeClassName('bulk_doctor_address');
    changeClassName('bulk_area');
    changeClassName('bulk_country_id');
    changeClassName('bulk_state_id');
    changeClassName('bulk_city_id');
    changeClassName('bulk_concat_address');
    changeClassName('sample_time');
    changeClassName('bulk_clinic_name');
    changeClassName('bulk_pin_code');
}

function removeclassfields()
{
    removeClass('bulk_doctors_adr_id');
    removeClass('bulk_doctor_address');
    removeClass('bulk_area');
    removeClass('bulk_country_id');
    removeClass('bulk_state_id');
    removeClass('bulk_city_id');
    removeClass('bulk_concat_address');
    removeClass('sample_time');
    removeClass('bulk_clinic_name');
    removeClass('bulk_pin_code');
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