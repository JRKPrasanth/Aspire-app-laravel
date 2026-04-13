@extends('layouts.header')
@section('content')

<span class="ui_close_btn"></span>

<h2 class="heads">Pricelist Upload
            
            <span class="ui_close_btn"><a href="{{ URL::to('pricelistupload') }}" class="collapse-close pull-right btn-danger"></a></span>
        </h2>




    <div class="card">
        
        <?php error_reporting(0); ?>
        <div class="card-body card-block">
            <form  method="post" action="{{ URL::to('pricelistuploadsave') }}" id="pricelistupload" data-parsley-validate>
              <input type="hidden" value="" name="savestatus" id="savestatus" />
                {{ csrf_field()}}

            <div class="col-md-4">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-3">Pricelist Name </label>
                    <div class="col-md-9">
                        <input class="form-control pricelist_upload_id" id="pricelist_upload_id" name="pricelist_upload_id" size="16" type="hidden" value="{{ $pricelistuploaddata['pricelist_upload_id'] }}" >
                        <input type="text" id="pricelist_name" name="pricelist_name" class="form-control pricelist_name" value="{{$pricelistuploaddata['pricelist_name']  }}" >
                    </div>

                 </div>
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-3">Pricelist Type </label>
                    <div class="col-md-9">
                        <select name="pricelist_type" name="pricelist_type" class=" form-control pricelist_type select2" id="pricelist_type" >
                            <option value="">--select--</option>
                            <option value="Purchase" <?php if($pricelistuploaddata['pricelist_type']=='Purchase'){ echo "selected";  }?> >Purchase</option>
                            <option value="Sales" <?php if($pricelistuploaddata['pricelist_type']=='Sales'){ echo "selected";  }?>>Sales</option>
                        </select>
                    </div>

                </div>
               
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-3">Start Date </label>
                    <div class="col-md-9">
                        <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <td><input type="text" name="start_date" class="start_date datepicker form-control" id="start_date" value="{{ $pricelistuploaddata['start_date'] }}" data-link-format="yyyy-mm-dd" ></td>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                  </div>
			    <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-3">End Date</label>
                    <div class="col-md-9">
                        <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <td><input type="text" name="end_date" class="end_date datepicker form-control" id="end_date" value="{{ $pricelistuploaddata['end_date'] }}" ></td>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <input type="hidden" id="end_date" value="{{ $row->end_date }}" />
                    </div>
                  </div>

            </div>

            <div class="col-md-4">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-3">Product Name</label>
                    <div class="col-md-9">
                        <select name="product_name" name="product_name" class=" form-control product_name select2" id="product_name"   >
                            {!! $product_name !!}
                        </select>
                    </div>

                </div>
			<?php if($pricelistuploaddata['pricelist_type']=='Sales'){ ?>	
				<div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-3">Batch Number</label>
                    <div class="col-md-9">
                        <select name="batch_number" name="batch_number" class=" form-control batch_number select2" id="batch_number"   >
                            {!! $batch_number !!}
                        </select>
                    </div>

                </div>
				<?php } ?>
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-3">Unit Price </label>
                    <div class="col-md-9">
                        <input type="text" id="unit_price" name="unit_price" class="form-control unit_price" value="{{ $pricelistuploaddata['unit_price'] }}" >
                    </div>

                </div>
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-3">Standard Price </label>
                    <div class="col-md-9">
                        <input type="text" id="std_price" name="std_price" class="form-control std_price" value="{{ $pricelistuploaddata['std_price'] }}" >
                    </div>

                </div>
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-3">Active</label>
                    <div class="col-md-9">
                        <select name="active" name="active" class=" form-control active select2" id="active" >
                            <option value="Yes" <?php if($pricelistuploaddata['active'] == "Yes") echo "selected"; ?> >Yes</option>
                            <option value="No" <?php if($pricelistuploaddata['active'] == "No") echo "selected"; ?> >No</option>
                        </select>
                    </div>

                </div>
            </div>
            <div class="col-md-4" >
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-3">Batch Name</label>
                    <div class="col-md-9">
                        <input type="text" name="batch_name" id="batch_name" value="{{ $pricelistuploaddata['batch_name'] }}" class="form-control batch_name" readonly>
                    </div>

                </div>
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-3">Batch Date</label>
                    <div class="col-md-9">
                        <input type="text" name="batch_date" id="batch_date" value="{{ $pricelistuploaddata['batch_date'] }}" class="form-control batch_date" readonly>
                    </div>

                </div>
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-3">Batch Status</label>
                    <div class="col-md-9">
                        <input type="text" name="batch_status" id="batch_status" value="{{ $pricelistuploaddata['batch_status'] }}" class="form-control batch_status" readonly>
                    </div>

                </div>

                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-3">Batch Comments</label>
                    <div class="col-md-9">
                        <input type="text" name="batch_comments" id="batch_comments" value="{{ $pricelistuploaddata['batch_comments'] }}" class="form-control batch_comments" readonly>
                    </div>

                </div>
            </div>


            <!--******************************-->


            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group text-center">
                        <!--  <a class='btn applychanges'>Apply Changes</a>-->
                        <button type="button" class="btn save">Submit</button>
                        <a href="../pricelistupload" class='btn cancel'  >Cancel</a>

                    </div>
                </div>
            </div>

            </form>

        </div>
    </div>






<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>

<script>
    $(document).ready(function () {

 /*Change Function for Price list Type*/
             
        $(document).on('change','.pricelist_type',function(){
            var prdgroup=$('.pricelist_type').select2('val');
            var prdgroup_id='';
            if( prdgroup == "Purchase"){
                prdgroup_id = '2,3';
            }else{
                prdgroup_id = '1';
            }

            var condition = 'product_group_id in('+prdgroup_id+')';   
            if(prdgroup != ''){
                $(".product_name").jCombo("{{ URL::to('jcomboform?table=m_products_t:concatenated_product:concatenated_product') }}&order_by=concatenated_product asc"+'&parent='+condition,
                {selected_value:""});
            }
        });
/*End*/
		/*click function for save*/
    $(document).on('click','.save',function()
    {

        var url		= "{{ URL::to('pricelistuploadsave') }}";
        var red_url		="{{ URL::to('pricelistupload') }}";
        change_date();
        var formdata	= $('#pricelistupload').serialize();
        var form = $('#pricelistupload');

        $.post(url,formdata,function(data)
        {
            var status      = data.status;
			var msg			=data.message;
            var id          = data.id;
            notyMsg(status,msg);
            setTimeout(function(){
                window.location.href=red_url;
            }, 1500);

        });



    });
    /*End*/
		
    /*keyup function for pricelist_name*/
        $(".pricelist_name").keyup(function () {
            this.value = this.value.toUpperCase();
        });
/*End*/


    });
</script>

<style>
    .btn-xs {
        display: inline-block;
        min-width: 10px;
        margin: 2px 5px;
        /*padding: 10px 15px 12px;*/
        padding:5px;
        /*font: 700 12px/1 'Open Sans', sans-serif;*/
        border-radius: 3px;
        /*box-shadow: inset 0 -1px 0 1px rgba(0, 0, 0, 0.1), inset 0 -10px 20px rgba(0, 0, 0, 0.1);*/
        cursor: pointer;
    }
u {
    text-decoration: underline;
   width: 265px;
   margin-top: -66px;
   margin-left: 0px;
   font-weight:700;
}

</style>
@include('layouts.php_js_validation')
@endsection
