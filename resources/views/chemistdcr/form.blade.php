@extends('layouts.header')
@section('content')
<div class="ajaxLoading"></div>
<h2 class="heads">Chemist DCR  
  <span class="ui_close_btn"><a href="../chemistdcr" class="collapse-close pull-right btn-danger" ></a></span>
</h2>


<div class="card">

<div class="card-body card-block">
<form method="post" action="{{URL::to('chemistdcrsave')}}" id="chemistdcr" class="chemistdcr"  enctype="multipart/form-data">
	 {{ csrf_field() }}

<div class="row">
<div class="col-md-12">


    <!--************************ Body content start here **********************-->
      
    <div class="col-md-4">
          
        <div class="form-group row">
            <label for="tp_date" class="form-control-label col-md-5"><span style="color: red;" >*</span> Tour Plan Date</label>
            <div class="col-md-6">
            <input class="form-control chemist_dcr_id" id="chemist_dcr_id" name="chemist_dcr_id" size="16" type="hidden" value="{{ $row->chemist_dcr_id }}" readonly>
            
            <div class="input-group date tp_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input class="form-control tp_date form_date" id="tp_date" name="tp_date" size="16" type="text" value="{{$row->tp_date}}" required  >
            </div>

            </div>
        </div>

        <div class="form-group row">
            <label for="from_area" class="form-control-label col-md-5"><span style="color: red;" >*</span>Area</label>
            <div class="col-md-6">
              <select name='from_area' rows='5' class='form-control select2 from_area' id="from_area" required >
                {!! $from_area !!}
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
            <label for="inputIsValid" class="form-control-label col-md-5"><span style="color: red;" >*</span>Order No</label>
            <div class="col-md-6">
                <input type="text" name="order_no" class="order_no form-control " id="order_no" value="{{$row->order_no}}" required>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5">Value</label>
            <div class="col-md-6">
                <input type="text" name="value" class="value form-control " id="value" value="{{$row->value}}">
            </div>
        </div>

       

    </div>

<div class="col-md-4">
    <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5"><span style="color: red;" >*</span>Chemist Name</label>
            <div class="col-md-6">
                <select name='chemist_name' rows='5' class='form-control select2 chemist_name' id="chemist_name" required >
                    {!! $chemist_name !!}
                </select>
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
    

    <!-- Order Product start-->

    <div class="col-md-12">

        <fieldset><u>Pre Order Booking</u><br><br>
        <a href="javascript:void(0);" class="add_row additem" rel=".rcopy3"><i class="fa fa-plus"></i> ADD</a>
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
                            <input type="hidden" name="bulk_chemist_odr_detail_id[]" class="form-control input-sm bulk_chemist_odr_detail_id" value="{{ $v->chemist_odr_detail_id }}">
                        </td>
                        <td>
                            <select class="bulk_product_order select2 form-control" id="bulk_product_order" name="bulk_product_order[]" required >
                               {!! $v->product_order !!} 
                            </select>
                        </td>
                        <td>
                            <input type="text" name="bulk_pob_qty[]" class="form-control bulk_pob_qty" id="bulk_pob_qty" value="{{ $v->pob_qty }}" required >
                        </td>
                        <td><a class="removepob removepob0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                        <td style="display: none;" ><input type="hidden" name="pob_counter[]"></td>
                    </tr>
                @endforeach
            <?php } if (count($linepob) < 1) { ?>
                <tr class="clone rcopy3">
                    <td>
                        <input type="hidden" name="bulk_chemist_odr_detail_id[]" class="form-control input-sm bulk_chemist_odr_detail_id" >
                    </td>
                    <td>
                        <select class="bulk_product_order select2 form-control" id="bulk_product_order" name="bulk_product_order[]" required >
                            {!! $linepob->product_order !!}
                        </select>
                    </td>
                    <td>
                        <input type="text" name="bulk_pob_qty[]" class="form-control bulk_pob_qty" id="bulk_pob_qty" required >
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
    				<a class='btn cancel' onclick="location.href = '{{url::to('chemistdcr')}}'">Cancel</a>
    			
    			</div>
    		</div>
    	</div>

</form>

</div>
</div>
<style type="text/css">
@media screen and (min-width: 1500px) {

     .bulk_product_order{width: 550px !important;}
    .bulk_pob_qty{width: 550px !important;} 
}

.bulk_product_order{width: 300px;}
.bulk_pob_qty{width: 300px;}
</style>
<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.css')}}">

<script>


$(document).ready(function(){

    $(document).on('change','#tp_date',function(){
        var date = $('#tp_date').val();
        var id = $('#chemist_dcr_id').val();
        if(id == ''){
            id=0;
        }
        var url = "{{ URL::to('chemistareas')}}/"+date+"/"+id;
        $.get(url,function(data){
            $(".from_area").html(data['from_area']);
        });
    });

    $(document).on('change','.from_area',function(){
        var id = $(".from_area option:selected").val();
        var url="{{URL::to('chemistdetail')}}/"+id;
        $.get(url,function(data){
            $(".chemist_name").html(data);
        });
    });

    $(document).on('keypress', '.order_no,.value,.bulk_pob_qty', function(ev){
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
            return true;
        }
        ev.preventDefault();
        return false;
    });


    var date = $('#tp_date').val();
    var id = $('#chemist_dcr_id').val();
    if(id == ''){
        id=0;
    }
    var url = "{{ URL::to('chemistareas')}}/"+date+"/"+id;
    $.get(url,function(data){
        $(".from_area").html(data['from_area']);
    });
    
    var data ="{{\Session::get('j_date_format')}}";
    $(".add_row").relCopy(data);
    changeclassfields();

    $('.add_row').click(function(){
        changeclassfields();
    });


	$(document).on('click','.saveform',function()
    {

    	 $('#panel_add').trigger('click');

    	var btnval		= $(this).val();

    	var url			="{{ url('chemistdcrsave') }}";

        var red_url		="{{ url('chemistdcr') }}";

        var create_url	="{{ url('chemistdcrcreate') }}/0";
        

        validationrule('chemistdcr');
        change_date();
        var formdata	= $('#chemistdcr').serialize();
        var form = $('#chemistdcr');

	       	form.parsley().validate();
	       	var form = $('#chemistdcr');
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

    $(document).on('click', '.removepob', function ()
    {
        var index = $(this).closest('tr').index();
        var rowCount = $('.pob_qty tbody tr').length;
        if(rowCount > 1 ){
            $(this).closest("tr").remove();
            removeClass('bulk_product_order');
            removeClass('bulk_pob_qty');
            removeClass('bulk_chemist_odr_detail_id');
        }else{
            notyMsg('error',"You can't Delete Atleast one row should be there");
        }
    });
    




  $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: true,
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
        autoclose: true,
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
        autoclose: true,
        todayHighlight: 1,
        startView: 1,
        format: 'hh:ii',
        minView: 0,
        maxView: 1,
        forceParse: 0
    });



});

function changeclassfields(){

    changeClassName('bulk_product_order');
    changeClassName('bulk_pob_qty');
    changeClassName('bulk_chemist_odr_detail_id');
}

function removeClass(className)
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

function removeClass(className)
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

function changeClassName(className){
    $('.' + className).each(function (index)
    {
        $(this).removeClass(className + '0');
        $(this).addClass(className + index);
    });
}

</script>
@include('layouts.php_js_validation')
@endsection
