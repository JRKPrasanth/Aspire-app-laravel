@extends('layouts.header')
@section('content')


<style type="text/css">
    
   
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

textarea.form-control {
    height: 34px;
    /* border: 1px solid #112e7a; */
}
@media  only screen and (min-width: 1500px) {
    .bulk_tour_date {width: 120px;}
.bulk_town_id{width: 120px;}
.bulk_doctor_ct {width: 100px;}
.bulk_chemist_ct{width: 100px;}
.bulk_from_area{width: 200px;}
.bulk_to_area{width: 200px;}
.bulk_distance{width: 90px;}
.bulk_fare {width: 90px;}
.bulk_daily_allow{width: 120px;}
.bulk_post_tele{width: 120px;}
.bulk_total_exp{width: 90px;}
}
@media  only screen and (min-width: 2000px) {
    .bulk_tour_date {width: 150px;}
.bulk_town_id{width: 150px;}
.bulk_doctor_ct {width: 150px;}
.bulk_chemist_ct{width: 150px;}
.bulk_from_area{width: 250px;}
.bulk_to_area{width: 250px;}
.bulk_distance{width: 140px;}
.bulk_fare {width: 140px;}
.bulk_daily_allow{width: 170px;}
.bulk_post_tele{width: 170px;}
.bulk_total_exp{width: 140px;}
}
.bulk_tour_date {width: 100px;}
.bulk_town_id{width: 100px;}
.bulk_doctor_ct {width: 80px;}
.bulk_chemist_ct{width: 80px;}
.bulk_from_area{width: 180px;}
.bulk_to_area{width: 180px;}
.bulk_distance{width: 70px;}
.bulk_fare {width: 70px;}
.bulk_daily_allow{width: 100px;}
.bulk_post_tele{width: 100px;}
.bulk_total_exp{width: 70px;}

</style>

<h2 class="heads">Expenses   
  <span class="ui_close_btn"><a href="../sfaexpenses" class="collapse-close pull-right btn-danger" ></a></span>
</h2>


<div class="card">

<div class="card-body card-block">
<form method="post" action="{{URL::to('sfaexpensessave')}}" id="sfaexpenses" class="sfaexpenses"  enctype="multipart/form-data">
	 {{ csrf_field() }}

<div class="row">
<div class="col-md-12">


    <!--************************ Body content start here **********************-->
      
    <div class="col-md-4">
        <div class="form-group row">
            <label for="month" class="form-control-label col-md-4">Month </label>
            <div class="col-md-6">
                <select class="form-control month select2" id="month" name="month"  >
                    {!!$month!!}
                </select>
            </div>

        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group row">
            <label for="year" class="form-control-label col-md-4">Year </label>
            <div class="col-md-6">
                <select class="form-control year select2" id="year" name="year"  >
                    {!! $year !!}
                </select>
               
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group row">
            <label for="year" class="form-control-label col-md-4">Total Expeses </label>
            <div class="col-md-6">
                <input type="text" name="total_expenses" id="total_expenses" class="total_expenses form-control" readonly >
            </div>
        </div>
    </div>

    <div class="expdata" >
        
    </div>

</div>
</div>

    <div class="col-md-12 ">
        <div id="preview-area" class="chandru">
            <a href="javascript:void(0);" class="add_row additem" rel=".rcopy" style="display: none;" ><i class="fa fa-plus"></i> ADD</a>
            <table class="overflow-y preview expenses_tbl">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Town</th>
                        <th>Doctor Visits</th>
                        <th>Chemist Visits</th>
                        <th>Travelled From</th>
                        <th>Travelled To</th>
                        <th>Distance</th>
                        <th>Fare</th>
                        <th>Daily Allowance</th>
                        <th>Postage Telegrams</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody class="expenses_tbl_body">
                    <?php if(count($linedata) >= 1 ) { ?>
                    @foreach($linedata as $key=>$value)
                        <tr class="rcopy clone">
                            <td><input type="hidden" name="expenses_id[]" class="form-control input-sm bulk_expenses_id" id="bulk_expenses_id" value="" ></td>
                            <td >
                                <input type="text" name="tour_date[]" class="form-control input-sm bulk_tour_date  " id="bulk_tour_date" value="{{$value->tour_date}}" readonly >
                            </td>
                            <td style="pointer-events: none;">
                                <select name='town_id[]' rows='5' required class='form-control bulk_town_id select2'  id="bulk_town_id" >
                                    {!! $value->town !!}
                                </select>
                            </td>
                            <td>
                                <input type="text" name="doctor_ct[]" class="form-control input-sm bulk_doctor_ct  " id="bulk_doctor_ct" value="{{$value->doctor_ct}}" readonly >
                            </td>
                            <td>
                                <input type="text" name="chemist_ct[]" class="form-control input-sm bulk_chemist_ct  " id="bulk_chemist_ct" value="{{$value->chemist_ct}}" readonly >
                            </td>
                            <td>
                                <select name='from_area[]' rows='5' required class='form-control bulk_from_area select2'  id="bulk_from_area" >
                                    {!! $value->from_area !!}
                                </select>   
                            </td>
                            <td>
                                <select name='to_area[]' rows='5' required class='form-control bulk_to_area select2'  id="bulk_to_area" >
                                    {!! $value->to_area !!}
                                </select>   
                            </td>
                            <td>
                                <input type="text" name="distance[]" class="form-control input-sm bulk_distance  " id="bulk_distance" value="{{$value->distance}}"  readonly >
                            </td>
                            <td>
                                <input type="text" name="fare[]" class="form-control input-sm bulk_fare  " id="bulk_fare" value="{{$value->fare}}" readonly >
                            </td>
                            <td>
                                <input type="text" name="daily_allow[]" class="form-control input-sm bulk_daily_allow  " id="bulk_daily_allow" value="" >
                            </td>
                            <td>
                                <input type="text" name="post_tele[]" class="form-control input-sm bulk_post_tele  " id="bulk_post_tele" value="" >
                            </td>
                            <td>
                                <input type="text" name="total_exp[]" class="form-control input-sm bulk_total_exp  " id="bulk_total_exp" value="" readonly >
                            </td>
                        </tr>
                    @endforeach
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="form-group text-center">
                    
                    <button type="button" class="btn save saveform" value ="save" >Save</button>
                    <!-- <button type="button" class="btn save saveform"   value ="savenew" >Save and New</button> -->
                    <a class='btn cancel' onclick="location.href = '{{url::to('sfaexpenses')}}'">Cancel</a>
                
                </div>
            </div>
        </div>


</form>
</div>
</div>
      <!--*******************-->


<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.css')}}">

<script type="text/javascript" src=""></script>
<script>


$(document).ready(function(){

    /************For multi select  Dont delete **********/
    // $(".jcr_location_id").click(function(){
    //     var url="{{ url('locationget') }}";
    //     $.get(url,function(data){
    //         var data= $.trim(data);
    //         if(data!=0){
    //             var condition="and location_id in("+data+")";
    //         $(".location_id").jCombo("{{ URL::to('jcomboform1?table=m_location_t:location_id:location_name')}}&parent="+condition,
    //     {selected_value:""});
    //         }else{
    //             $(".location_id").jCombo("{{ URL::to('jcomboform?table=m_location_t:location_id:location_name') }}",
    //     {selected_value:""});
    //         }
    //     });
        

    // });


    /**************** moblie number validation start ***********/
    $(document).on('keypress', '.bulk_daily_allow,.bulk_post_tele', function(ev){
        var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
    });

    $(document).on('change','.month,.year',function(){
        var mth = $('.month option:selected').val();
        var year = $('.year option:selected').text();
        if(year != '' && mth != ''){
            var url = "{{URL::to('sfaexpensesdata')}}/"+mth+"/"+year;
            $.get(url,function(data){
                console.log(data);
                arrlen = data.length;
                if(arrlen > 0){
                    $.each(data,function(index){
                        // console.log(data[index]['town']);
                        $('.bulk_tour_date'+index).val(data[index]['tour_date']);
                        // $('.bulk_town_id'+index).select2('val',[data[index]['town']]);
                        $('.bulk_doctor_ct'+index).val(data[index]['doctor_ct']);
                        $('.bulk_chemist_ct'+index).val(data[index]['chemist_ct']);
                        $('.bulk_distance'+index).val(data[index]['distance']);
                        $('.bulk_fare'+index).val(data[index]['fare']);
                        $('.bulk_daily_allow'+index).val(data[index]['daily_allow']);
                        $('.bulk_post_tele'+index).val(data[index]['post_tele']);
                        $('.bulk_total_exp'+index).val(data[index]['total_exp']);
                        $('.bulk_total_expenses'+index).val(data[index]['total_expenses']);

                        if((arrlen-1) > index){
                            $('.add_row').trigger('click');
                        }
                    });

                    $('.saveform').prop('disabled',false);
                }else{
                    notyMsg('info','No Tourplan data for this month');
                    $(".expenses_tbl  > tbody").find("tr:gt(0)").remove();
                    $('.bulk_tour_date').val('');
                    $('.bulk_town_id').select2('val',['']);
                    $('.bulk_doctor_ct').val('');
                    $('.bulk_chemist_ct').val('');
                    $('.bulk_from_area').select2('val',['']);
                    $('.bulk_to_area').select2('val',['']);
                    $('.bulk_distance').val('');
                    $('.bulk_fare').val('');
                    $('.bulk_daily_allow').val('');
                    $('.bulk_post_tele').val('');
                    $('.bulk_total_exp').val('');
                    $('.bulk_total_expenses').val('');

                    $('.saveform').prop('disabled',true);
                }
            });
        }else if(year == '' && mth != ''){
            notyMsg('info','Please choose year');
        }else if(year != '' && mth == ''){
            notyMsg('info','Please choose month');
        }
    });


    $(document).on('keyup','.bulk_daily_allow,.bulk_post_tele',function(){
        var index = $(this).closest('tr').index();
        
        var daily = $('.bulk_daily_allow'+index).val();
        var post = $('.bulk_post_tele'+index).val();
        var fare = $('.bulk_fare'+index).val();
        var sum =0;
        if(daily == '')
            daily = 0;
        if(post == '')
            post = 0;
        if(fare == '')
            fare = 0;
        var total = parseFloat(fare) + parseFloat(post) + parseFloat(daily);
        $('.bulk_total_exp'+index).val(total.toFixed(3));

        $('.bulk_total_exp').each(function()
        {
            console.log($(this).val());
            sum += ($(this).val()) ? parseFloat($(this).val()) : 0;
        });

        $('.total_expenses').val(sum.toFixed(3));
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

    	var url			="{{ url('sfaexpensessave') }}";
        var red_url		="{{ url('sfaexpenses') }}";
        var create_url	="{{ url('sfaexpensescreate') }}/0";        

        validationrule('sfaexpenses');
        
        var formdata	= $('#sfaexpenses').serialize();
        var form = $('#sfaexpenses');

       	form.parsley().validate();
       	var form = $('#sfaexpenses');
       	form.parsley().validate();
       
        if(form.parsley().validate())
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
                else
                {
                    notyMsg(status,msg);
		            setTimeout(function(){
        	           window.location.href=red_url;
                    }, 1000);
                }
            }); 
        }
        return false;

    });


           
    $(document).on('click','.remove',function()
    {
        var index = $(this).closest('tr').index();
        var rowCount = $('.expenses_tbl tbody tr').length;
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
    changeClassName('bulk_tour_date');
    changeClassName('bulk_town_id');
    changeClassName('bulk_doctor_ct');
    changeClassName('bulk_chemist_ct');
    changeClassName('bulk_from_area');
    changeClassName('bulk_to_area');
    changeClassName('bulk_distance');
    changeClassName('bulk_fare');
    changeClassName('bulk_daily_allow');
    changeClassName('bulk_post_tele');
    changeClassName('bulk_total_exp');
    changeClassName('bulk_total_expenses');
}

function removeclassfields()
{
    removeClass('bulk_tour_date');
    removeClass('bulk_town_id');
    removeClass('bulk_doctor_ct');
    removeClass('bulk_chemist_ct');
    removeClass('bulk_from_area');
    removeClass('bulk_to_area');
    removeClass('bulk_distance');
    removeClass('bulk_fare');
    removeClass('bulk_daily_allow');
    removeClass('bulk_post_tele');
    removeClass('bulk_total_exp');
    removeClass('bulk_total_expenses');
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
    var rowCount = $('.expenses_tbl tbody tr').length;
    for(var i=0;i<=rowCount;i++)
    {
        $('.expenses_tbl tbody tr').find('.'+className).removeClass(className+i);
    }
    $('.' + className).each(function (index)
    {
        $(this).addClass(className + index);
    });
}

</script>
@include('layouts.php_js_validation')
@endsection