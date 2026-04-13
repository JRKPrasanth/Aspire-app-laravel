@extends('layouts.header')
@section('content')

<style type="text/css">
    
        ul.tabs{
            margin: 0px;
            padding: 0px;
            list-style: none;
        }
        ul.tabs li{
            background: #ccc;
            color: #222;
            display: inline-block;
            padding: 10px 15px;
            cursor: pointer;
        }

        ul.tabs li.current{
            background: #ededed;
            color: #222;
        }

        .tab-content{
            display: none;
            background: #ededed;
            padding: 15px;
        }

        .tab-content.current{
            display: inherit;
        }


      .bulk_product_sample,.bulk_gift_sample,.bulk_product_order{
        width: 400px;
      }

     .bulk_prd_qty,.bulk_gift_qty,.bulk_pob_qty{
          width: 200px;
     }


.select2-container--default .select2-selection--multiple{
  border: none;
}
.select2-container--default.select2-container--focus .select2-selection--multiple{
  border: none;
}
.select2-container{
    box-sizing: border-box;
    display: inline-block;
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
<h2 class="heads">Doctor DCR  
  <span class="ui_close_btn"><a href="../doctordcr" class="collapse-close pull-right btn-danger" ></a></span>
</h2>


<div class="card">

<div class="card-body card-block">
<form method="post" action="{{URL::to('doctordcrsave')}}" id="doctordcr" class="doctordcr"  enctype="multipart/form-data">
     {{ csrf_field() }}

<div class="row">
<div class="col-md-12">


    <!--************************ Body content start here **********************-->
      
    <div class="col-md-4">
          
        <div class="form-group row">
            <label for="tp_date" class="form-control-label col-md-5">Tour Plan Date</label>
            <div class="col-md-6">
            <input class="form-control doctor_dcr_id" id="doctor_dcr_id" name="doctor_dcr_id" size="16" type="hidden" value="{{ $row->doctor_dcr_id }}" readonly>
            
            <div class="input-group date tp_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input class="form-control tp_date form_date" id="tp_date" name="tp_date" size="16" required type="text" value="{{$row->tp_date}}"  >
            </div>

            </div>
        </div>

        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5"><span style="color: red;" >*</span>Divert Details</label>
            <div class="col-md-6">
                <input type="radio" name="divert_detail" class="divert_detail form-control" id="divert_detail" required <?php if($row->divert_detail == "Yes") echo "checked"; ?> value="Yes">Yes
                <input type="radio" name="divert_detail" class="divert_detail form-control" id="divert_detail" <?php if($row->divert_detail == "No") echo "checked"; ?> value="No">No           
            
            </div>
        </div>

        <div class="form-group row">
            <label for="from_area" class="form-control-label col-md-5"><span style="color: red;" >*</span> Area</label>
            <div class="col-md-6">
              <select name='from_area' rows='5' class='form-control select2 from_area' id="from_area" required >
                {!! $from_area !!}
              </select>
            </div>
        </div>
        

           <div class="form-group row">
            <label for="focus_product" class="form-control-label col-md-5">Focus Product</label>
            <div class="col-md-6">
            <select name='focus_product[]' multiple rows='5' class='form-control focus_product select2'  id="focus_product" >
              {!! $focus_product !!}
            </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="active" class="form-control-label col-md-5">Created By</label>
            <div class="col-md-6" style="pointer-events:none;">
                <select name='created_by' rows='5' class='select2 created_by' id="created_by" >
              {!! $created_by !!}
                </select>
            </div>
        </div>





        

    </div>
    <div class="col-md-4">
          <div class="form-group row">
            <label for="doctor_id" class="form-control-label col-md-5"><span style="color: red;" >*</span> Doctor</label>
            <div class="col-md-6">

              <select name='doctor_id' rows='5' class='form-control select2 doctor_id' id="doctor_id" required >
                {!! $doctor_id !!}

              </select>
            </div>
        </div>
        

       

        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Timing</label>
            <div class="col-md-6">
              <input type="text" id="timing" name="timing" class="form-control form_time timing" value="{{$row->timing}}" >
            </div>
        </div>

         <div class="form-group row">
            <label for="activity_id" class="form-control-label col-md-5">Activity</label>
            <div class="col-md-6">
            <select name='activity_id' class='form-control activity_id select2'  id="activity_id" >
              {!! $activity_id !!}
            </select>
            </div>
        </div>


        <div class="form-group row">
            <label for="visit_with" class="form-control-label col-md-5">Visit With</label>
            <div class="col-md-6">
            <select name='visit_with[]' rows='5' class='form-control visit_with select2' multiple id="visit_with" >
              {!! $visit_with !!}
            </select>
            </div>
        </div>

        

    </div>


    <div class="col-md-4">
          
        <div class="form-group row">
            <label for="outcome_id" class="form-control-label col-md-5">Outcome</label>
            <div class="col-md-6">
            <select name='outcome_id' class='form-control outcome_id select2'  id="outcome_id" >
              {!! $outcome_id !!}
            </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Doctor Reminder Reason</label>
            <div class="col-md-6">
              <input type="text" id="dcr_reminder_reason" name="dcr_reminder_reason" class="form-control dcr_reminder_reason" value="{{$row->dcr_reminder_reason}}" >
            </div>
        </div>
        
        <div class="form-group row">
            <label for="dcr_reminder_date" class="form-control-label col-md-5">Doctor Reminder Date</label>
            <div class="col-md-6">
                <input class="form-control dcr_reminder_date form_date" id="dcr_reminder_date" name="dcr_reminder_date" type="text" value="{{$row->dcr_reminder_date}}"  >                
            </div>
        </div>

        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Remarks</label>
            <div class="col-md-6">
              <textarea id="remarks" name="remarks" class="form-control remarks" value="{{$row->remarks}}" row="5"  >{!! $row->remarks !!}</textarea>
            </div>
        </div>
    </div>

</div>
</div>
    



    <ul class="tabs">
        <li class="tab-link current" data-tab="tab-1"> Product Samples</li>
        <li class="tab-link" data-tab="tab-2">Gift Samples</li>
        <li class="tab-link" data-tab="tab-3">Pre Order Booking</li>
    </ul>


    <!-- Product Sample start-->

    <div id="tab-1" class="tab-content current">
        <fieldset><u>Product Samples</u><br><br>
        <a href="javascript:void(0);" class="add_row1 additem" rel=".rcopy1"><i class="fa fa-plus"></i> ADD</a>
        <div id="preview-area" class="chandru">
        <table class="overflow-y preview prd_smpl">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="prd_smpl_body" >
            <?php if (count($lineprdsmpl) >= 1) { ?>
                @foreach($lineprdsmpl as $key=>$value)
                    <tr class="clone rcopy1">
                        <td>
                            <input type="hidden" name="bulk_product_sample_id[]" class="form-control input-sm bulk_product_sample_id" value="{{ $value->product_sample_id }}">
                        </td>
                        <td>
                            <select class="bulk_product_sample select2 form-control" id="bulk_product_sample" name="bulk_product_sample[]" >
                               {!! $value->product_sample !!} 
                            </select>
                        </td>
                        <td>
                            <input type="text" name="bulk_prd_qty[]" class="form-control bulk_prd_qty" id="bulk_prd_qty" value="{{ $value->prd_qty }}" >
                        </td>
                        <td><a class="removepd removepd0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                        <td style="display: none;"><input type="hidden" name="prd_counter[]"></td>
                    </tr>
                @endforeach
            <?php } if (count($lineprdsmpl) < 1) { ?>
                <tr class="clone rcopy1">
                    <td>
                        <input type="hidden" name="bulk_product_sample_id[]" class="form-control input-sm bulk_product_sample_id" >
                    </td>
                    <td>
                        <select class="bulk_product_sample select2 form-control" id="bulk_product_sample" name="bulk_product_sample[]" >
                            {!! $lineprdsmpl->product_sample !!}
                        </select>
                    </td>
                    <td>
                        <input type="text" name="bulk_prd_qty[]" class="form-control bulk_prd_qty" id="bulk_prd_qty">
                    </td>
                    <td><a class="removepd removepd0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                    <td style="display: none;"><input type="hidden" name="prd_counter[]"></td>
                </tr>
            <?php } ?>                
            </tbody>
        </table>
        </div>
        </fieldset>
    </div>

    <!-- Product Sample End -->

    <!-- Gift Sample start-->

    <div id="tab-2" class="tab-content">
        <fieldset><u>Gift Samples</u><br><br>
        <a href="javascript:void(0);" class="add_row2 additem" rel=".rcopy2"><i class="fa fa-plus"></i> ADD</a>
        <div id="preview-area" class="chandru">
        <table class="overflow-y preview gift_smpl">
            <thead>
                <tr>
                    <th>Gift Product</th>
                    <th>Quantity</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="gift_smpl_body" >
            <?php if (count($linegiftsmpl) >= 1) { ?>
                @foreach($linegiftsmpl as $k => $v)
                    <tr class="clone rcopy2">
                        <td>
                            <input type="hidden" name="bulk_gift_sample_id[]" class="form-control input-sm bulk_gift_sample_id" value="{{ $v->gift_sample_id }}">
                        </td>
                        <td>
                            <select class="bulk_gift_sample select2 form-control" id="bulk_gift_sample" name="bulk_gift_sample[]" >
                               {!! $v->gift_sample !!} 
                            </select>
                        </td>
                        <td>
                            <input type="text" name="bulk_gift_qty[]" class="form-control bulk_gift_qty" id="bulk_gift_qty" value="{{ $v->gift_qty }}" >
                        </td>
                        <td><a class="removegf removegf0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                        <td style="display: none;" ><input type="hidden" name="gift_counter[]"></td>
                    </tr>
                @endforeach
            <?php } if (count($linegiftsmpl) < 1) { ?>
                <tr class="clone rcopy2">
                    <td>
                        <input type="hidden" name="bulk_gift_sample_id[]" class="form-control input-sm bulk_gift_sample_id" >
                    </td>
                    <td>
                        <select class="bulk_gift_sample select2 form-control" id="bulk_gift_sample" name="bulk_gift_sample[]" >
                            {!! $linegiftsmpl->gift_sample !!}
                        </select>
                    </td>
                    <td>
                        <input type="text" name="bulk_gift_qty[]" class="form-control bulk_gift_qty" id="bulk_gift_qty">
                    </td>
                    <td><a class="removegf removegf0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                    <td style="display: none;" ><input type="hidden" name="gift_counter[]"></td>
                </tr>
            <?php } ?>                
            </tbody>
        </table>
        </div>
        </fieldset>
    </div>

    <!-- Gift Sample End -->

    <!-- Order Product start-->

    <div id="tab-3" class="tab-content">

        <fieldset><u>Pre Order Booking</u><br><br>
        <a href="javascript:void(0);" class="add_row3 additem" rel=".rcopy3"><i class="fa fa-plus"></i> ADD</a>
        <div id="preview-area" class="chandru">
        <table class="overflow-y preview pob_qty">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>POB Quantity</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="gift_smpl_body" >
            <?php if (count($linepob) >= 1) { ?>
                @foreach($linepob as $k => $v)
                    <tr class="clone rcopy3">
                        <td>
                            <input type="hidden" name="bulk_pob_detail_id[]" class="form-control input-sm bulk_pob_detail_id" value="{{ $v->pob_detail_id }}">
                        </td>
                        <td>
                            <select class="bulk_product_order select2 form-control" id="bulk_product_order" name="bulk_product_order[]" >
                               {!! $v->product_order !!} 
                            </select>
                        </td>
                        <td>
                            <input type="text" name="bulk_pob_qty[]" class="form-control bulk_pob_qty" id="bulk_pob_qty" value="{{ $v->pob_qty }}" >
                        </td>
                        <td><a class="removepob removepob0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                        <td style="display: none;" ><input type="hidden" name="pob_counter[]"></td>
                    </tr>
                @endforeach
            <?php } if (count($linepob) < 1) { ?>
                <tr class="clone rcopy3">
                    <td>
                        <input type="hidden" name="bulk_pob_detail_id[]" class="form-control input-sm bulk_pob_detail_id" >
                    </td>
                    <td>
                        <select class="bulk_product_order select2 form-control" id="bulk_product_order" name="bulk_product_order[]" >
                            {!! $linepob->product_order !!}
                        </select>
                    </td>
                    <td>
                        <input type="text" name="bulk_pob_qty[]" class="form-control bulk_pob_qty" id="bulk_pob_qty">
                    </td>
                    <td><a class="removepob removepob0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                    <td style="display: none;" ><input type="hidden" name="pob_counter[]"></td>
                </tr>
            <?php } ?>                
            </tbody>
        </table>
        </div>
        </fieldset>
    </div>

    <!-- Order Product End -->

      <!--*******************-->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="form-group text-center">
                    
                    <button type="button" class="btn save saveform" value ="save" >Save</button>
                    <button type="button" class="btn save saveform"   value ="savenew" >Save and New</button>
                    <a class='btn cancel' onclick="location.href = '{{url::to('doctordcr')}}'">Cancel</a>
                
                </div>
            </div>
        </div>

</form>

</div>
</div>
<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.css')}}">


<script>


$(document).ready(function(){

    $('.from_area').css('pointer-event','none');
    
    $(document).on('change','#tp_date',function(){
        var date = $('#tp_date').val();
        var id = $('#doctor_dcr_id').val();
        if(id =='')
            id='0';
        var url = "{{ URL::to('tourapprove')}}/"+date+"/"+id;
        $.get(url,function(data){
            var dat = $.trim(data);
            console.log(dat);
            if(dat == "INITIATED"){
                notyMsg('error','Tour Plan not Approved');
            }else if(dat == 'empty' ){
                notyMsg('error','Tour Plan not Created');
            }else if(dat == 'REJECTED'){
                notyMsg('error','Tour Plan Rejected');
            }
        });
    });

 
   

    $(document).on('change','.from_area' ,function(){
        var date = $('#tp_date').val();
        area = $('.from_area option:selected').val();
        var url = "{{ URL::to('areawisedoctor') }}/"+area+"/"+date;
        $.get(url,function(data){
            $(".doctor_id").html(data);
        });
    });
    
    $(document).on('click','.divert_detail',function(){
        var divert = $("input[name='divert_detail']:checked").val();
        if(divert == "Yes"){
            $(".from_area").jCombo("{{ URL::to('jcomboformlogin?table=m_cities_t:city_id:city_name') }}&order_by=city_name asc",
                {selected_value:""});
            
        }else if(divert == "No"){
            var date = $('#tp_date').val();
            var id = $('#doctor_dcr_id').val();
            if(id =='')
                id='0';
            var url = "{{ URL::to('areas')}}/"+date+"/"+id;
            $.get(url,function(data){
                $(".from_area").html(data['from_area']);
            });
        }
    });


    var data ="{{\Session::get('j_date_format')}}";
    $(".add_row1").relCopy(data);
    changeclassfields1();

    $('.add_row1').click(function(){
        changeclassfields1();
    });


    var data ="{{\Session::get('j_date_format')}}";
    $(".add_row2").relCopy(data);
    changeclassfields2();

    $('.add_row2').click(function(){
        changeclassfields2();
    });

    var data ="{{\Session::get('j_date_format')}}";
    $(".add_row3").relCopy(data);
    changeclassfields3();

    $('.add_row3').click(function(){
        changeclassfields3();
    });


    $(document).on('click','.saveform',function()
    {

         $('#panel_add').trigger('click');

        var btnval      = $(this).val();

        var url         ="{{ url('doctordcrsave') }}";

        var red_url     ="{{ url('doctordcr') }}";

        var create_url  ="{{ url('doctordcrcreate') }}/0";
        

        validationrule('doctordcr');
        
        var formdata    = $('#doctordcr').serialize();
        var form = $('#doctordcr');

            form.parsley().validate();
            var form = $('#doctordcr');
            form.parsley().validate();
            if (form.parsley().isValid())
            {   


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



    $(document).on('click', '.removepd', function ()
    {
        var index = $(this).closest('tr').index();
        var rowCount = $('.prd_smpl tbody tr').length;
        if(rowCount > 1 ){
            $(this).closest("tr").remove();
            removeClass1('bulk_product_sample');
            removeClass1('bulk_prd_qty');
            removeClass1('bulk_product_sample_id');
        }else{
            notyMsg('error',"You can't Delete Atleast one row should be there");
        }
    });

    $(document).on('click', '.removegf', function ()
    {
        var index = $(this).closest('tr').index();
        var rowCount = $('.gift_smpl tbody tr').length;
        if(rowCount > 1 ){
            $(this).closest("tr").remove();
            removeClass2('bulk_gift_sample');
            removeClass2('bulk_gift_qty');
            removeClass2('bulk_gift_sample_id');
        }else{
            notyMsg('error',"You can't Delete Atleast one row should be there");
        }
    });

    $(document).on('click', '.removepob', function ()
    {
        var index = $(this).closest('tr').index();
        var rowCount = $('.pob_qty tbody tr').length;
        if(rowCount > 1 ){
            $(this).closest("tr").remove();
            removeClass3('bulk_product_order');
            removeClass3('bulk_pob_qty');
            removeClass3('bulk_pob_detail_id');
        }else{
            notyMsg('error',"You can't Delete Atleast one row should be there");
        }
    });

    $('ul.tabs li').click(function(){
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#"+tab_id).addClass('current');
    })

});

function changeclassfields1(){

    changeClassName('bulk_product_sample');
    changeClassName('bulk_prd_qty');
    changeClassName('bulk_product_sample_id');
}

function changeclassfields2(){

    changeClassName('bulk_gift_sample');
    changeClassName('bulk_gift_qty');
    changeClassName('bulk_gift_sample_id');
}

function changeclassfields3(){

    changeClassName('bulk_product_order');
    changeClassName('bulk_pob_qty');
    changeClassName('bulk_pob_detail_id');
}

function changeClassName(className){
    $('.' + className).each(function (index)
    {
        $(this).removeClass(className + '0');
        $(this).addClass(className + index);
    });
}

function removeClass1(className)
{   
    var rowCount = $('.prd_smpl tbody tr').length;
    for (var i = 0; i <= rowCount; i++)
    {
        $('.prd_smpl tbody tr').find('.' + className).removeClass(className + i);
    }

    $('.' + className).each(function (index){
        $(this).addClass(className + index);
    });
}


function removeClass2(className)
{   
    var rowCount = $('.gift_smpl tbody tr').length;
    for (var i = 0; i <= rowCount; i++)
    {
        $('.gift_smpl tbody tr').find('.' + className).removeClass(className + i);
    }

    $('.' + className).each(function (index){
        $(this).addClass(className + index);
    });
}

function removeClass3(className)
{   
    var rowCount = $('.pob_qty tbody tr').length;
    for (var i = 0; i <= rowCount; i++)
    {
        $('.pob_qty tbody tr').find('.' + className).removeClass(className + i);
    }

    $('.' + className).each(function (index){
        $(this).addClass(className + index);
    });
}


  $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        format: 'dd-mm-yyyy hh:ii',
        forceParse: 0,
        showMeridian: 1
    });
    $('.form_date').datetimepicker({
       // language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        format: 'dd-mm-yyyy',
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    $('.form_time').datetimepicker({
       // language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        format: 'hh:ii',
        minView: 0,
        maxView: 1,
        forceParse: 0
    });

$(document).ready(function(){
    $("#timing").focusin(function(){
        $(".datetimepicker thead tr:first-child th ").css("display", "none ");
        $(".datetimepicker tfoot th ").css("display", "none");
    });

    // $(".form_time").focusout(function(){
    //     $(".datetimepicker thead tr:first-child th ").css("display", "");
    //     $(".datetimepicker tfoot th ").css("display", "");
    // });
    $("#doctor_dcr_id").focusin(function(){
        $(".datetimepicker thead tr:first-child th ").css("display", "");
        $(".datetimepicker tfoot th ").css("display", "");
    });
    $("#dcr_reminder_date").focusin(function(){
        $(".datetimepicker thead tr:first-child th ").css("display", "");
        $(".datetimepicker tfoot th ").css("display", "");
    });

});


</script>


@include('layouts.php_js_validation')
@endsection
