@extends('layouts.header')
@section('content')

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
        
    </div>

    <div class="col-md-4">
        <div class="form-group row">
            <label for="active" class="form-control-label col-md-5">Created By</label>
            <div class="col-md-6" style="pointer-events:none;">
                <input type="hidden" name="dcr_remove_id" value="" class="dcr_remove_id" >
                <select name='created_by' rows='5' class='select2 created_by' id="created_by" >
                    {!! $created_by !!}
                </select>
            </div>
        </div>
    </div>


    <div class="col-md-4">
    </div>

</div>
</div>
    <?php if($hide_newrow == 0) { ?>
        <a href="javascript:void(0);" class="add_row1 additem" rel=".rcopy1"><i class="fa fa-plus"></i> ADD</a>
    <?php } ?>
    <div id="preview-area" class="chandru">
        <table class="overflow-y preview line_data">
            <thead>
                <tr>
                    <th>Tourplan Date</th>
                    <th>Divert Details</th>
                    <th>Area</th>
                    <th>Doctor</th>
                    <th>Focus Product</th>
                    <th>Timing</th>
                    <th>Activity</th>
                    <th>Visit With</th>
                    <th>Outcome</th>
                    <th>Doctor Reminder Reason</th>
                    <th>Doctor Reminder Date</th>
                    <th>Remarks</th>
                    <th>POB Details</th>
                    <th>Gift Products</th>
                    <th>Sample Products</th>
                    <th>Poster Products</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="line_data_body" >
                <?php if (count($linedata) >= 1) { ?>
                    @foreach($linedata as $key=>$value)
                        <tr class="clone rcopy1">
                            <td>
                                <input type="hidden" name="bulk_doctor_dcr_id[]" id="bulk_doctor_dcr_id" class="form-control input-sm bulk_doctor_dcr_id" value="{{ $value->doctor_dcr_id }}">
                            </td>
                            <td>
                                <input class="form-control input-sm bulk_tp_date datepicker" id="bulk_tp_date" name="bulk_tp_date[]" type="text" value="{{ $value->tp_date }}" >
                            </td>
                            <td class="divert_point">
                                <select class="bulk_divert_detail select2" id="bulk_divert_detail" name="bulk_divert_detail[]" >
                                    <option value="">--Please Select--</option>
                                    <option value="Yes" <?php if($value->divert_detail == "Yes") echo "selected"; ?> >Yes</option>
                                    <option value="No" <?php if($value->divert_detail == "No") echo "selected"; ?> >No</option>
                                </select>
                            </td>
                            <td class="area_point">
                                <select name='bulk_from_area[]' class='form-control select2 bulk_from_area' id="bulk_from_area" >
                                    {!! $value->from_area !!}
                                </select>
                            </td>
                            <td class="doctor_point">
                                <select name='bulk_doctor_id[]' rows='5' class='form-control select2 bulk_doctor_id' id="bulk_doctor_id"  >
                                    {!! $value->doctor_id !!}
                                </select>
                            </td>
                            <td>
                                <select name='bulk_focus_product[]' multiple class='form-control bulk_focus_product select2'  id="bulk_focus_product" >
                                    {!! $value->focus_product !!}
                                </select>
                            </td>
                            <td>
                                <input type="text" name="bulk_timing[]" class="form-control timepicker bulk_timing" value="{{$value->timing}}" >
                            </td>
                            <td>
                                <select name='bulk_activity_id[]' class='form-control bulk_activity_id select2'  id="bulk_activity_id" >
                                    {!! $value->activity_id !!}
                                </select>
                            </td>
                            <td>
                                <select name='bulk_visit_with[]' class='form-control bulk_visit_with select2' multiple id="bulk_visit_with" >
                                    {!! $value->visit_with !!}
                                </select>
                            </td>
                            <td>
                                <select name='bulk_outcome_id[]' class='form-control bulk_outcome_id select2'  id="bulk_outcome_id" >
                                    {!! $value->outcome_id !!}
                                </select>
                            </td>
                            <td>
                                <input type="text" id="bulk_dcr_reminder_reason" name="bulk_dcr_reminder_reason[]" class="form-control bulk_dcr_reminder_reason" value="{{$value->dcr_reminder_reason}}" >
                            </td>
                            <td>
                                <input class="form-control bulk_dcr_reminder_date datepicker" id="bulk_dcr_reminder_date" name="bulk_dcr_reminder_date[]" type="text" value="{{$value->dcr_reminder_date}}"  > 
                            </td>
                            <td>
                                <textarea id="bulk_remarks" name="bulk_remarks[]" class="form-control bulk_remarks" row="5"  >{!! $value->remarks !!}</textarea>
                            </td>
                            <td>
                                <a href="#" class="bulk_pob_detail" title="Add Pob Details"> <i class="fa fa-plus"></i></a>
                                <input type="hidden" name="bulk_pob_id[]" class="form-control bulk_pob_id " value="{{$value->pob_id}}">
                                <input type="hidden" name="bulk_pob_product[]" class="form-control bulk_pob_product " value="{{$value->pob_product}}">
                                <input type="hidden" name="bulk_pob_qty[]" class="form-control bulk_pob_qty " value="{{$value->pob_qty}}">
                            </td> 
                            <td>
                                <a href="#" class="bulk_gift_detail" title="Add Gift Details"> <i class="fa fa-plus"></i></a>
                                <input type="hidden" name="bulk_gift_id[]" class="form-control bulk_gift_id " value="{{$value->gift_id}}">
                                <input type="hidden" name="bulk_gift_product[]" class="form-control bulk_gift_product " value="{{$value->gift_product}}">
                                <input type="hidden" name="bulk_gift_qty[]" class="form-control bulk_gift_qty " value="{{$value->gift_qty}}">
                            </td> 
                            <td>
                                <a href="#" class="bulk_sample_detail" title="Add Sample Details"> <i class="fa fa-plus"></i></a>
                                <input type="hidden" name="bulk_sample_id[]" class="form-control bulk_sample_id " value="{{$value->sample_id}}">
                                <input type="hidden" name="bulk_sample_product[]" class="form-control bulk_sample_product " value="{{$value->sample_product}}">
                                <input type="hidden" name="bulk_sample_qty[]" class="form-control bulk_sample_qty " value="{{$value->sample_qty}}">
                            </td> 
                            <td>
                                <a href="#" class="bulk_poster_detail" title="Add Poster Details"> <i class="fa fa-plus"></i></a>
                                <input type="hidden" name="bulk_poster_id[]" class="form-control bulk_poster_id " value="{{$value->poster_id}}">
                                <input type="hidden" name="bulk_poster_product[]" class="form-control bulk_poster_product " value="{{$value->poster_product}}">
                                <input type="hidden" name="bulk_poster_qty[]" class="form-control bulk_poster_qty " value="{{$value->poster_qty}}">
                            </td>
                            <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                        <td style="display: none;"><input type="hidden" name="lin_counter[]"></td>
                        </tr>
                    @endforeach
                <?php } else { ?>
                    <tr class="clone rcopy1">
                        <td>
                            <input type="hidden" name="bulk_doctor_dcr_id[]" class="form-control input-sm bulk_doctor_dcr_id" id="bulk_doctor_dcr_id" value="">
                        </td>
                        <td>
                            <input class="form-control bulk_tp_date input-sm datepicker" id="bulk_tp_date" name="bulk_tp_date[]" type="text" value="" >
                        </td>
                        <td class="divert_point">
                            <select class="bulk_divert_detail select2" id="bulk_divert_detail" name="bulk_divert_detail[]" >
                                <option value="">--Please Select--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </td>
                        <td class="area_point">
                            <select name='bulk_from_area[]' class='form-control select2 bulk_from_area' id="bulk_from_area" >
                                {!! $from_area !!}
                            </select>
                        </td>
                        <td class="doctor_point">
                            <select name='bulk_doctor_id[]' rows='5' class='form-control select2 bulk_doctor_id' id="bulk_doctor_id"  >
                                {!! $doctor_id !!}
                            </select>
                        </td>
                        <td>
                            <select name='bulk_focus_product[]' multiple class='form-control bulk_focus_product select2'  id="bulk_focus_product" >
                                {!! $focus_product !!}
                            </select>
                        </td>
                        <td>
                            <input type="text" name="bulk_timing[]" class="form-control timepicker bulk_timing" value="" >
                        </td>
                        <td>
                            <select name='bulk_activity_id[]' class='form-control bulk_activity_id select2'  id="bulk_activity_id" >
                                {!! $activity_id !!}
                            </select>
                        </td>
                        <td>
                            <select name='bulk_visit_with[]' class='form-control bulk_visit_with select2' multiple id="bulk_visit_with" >
                                {!! $visit_with !!}
                            </select>
                        </td>
                        <td>
                            <select name='bulk_outcome_id[]' class='form-control bulk_outcome_id select2'  id="bulk_outcome_id" >
                                {!! $outcome_id !!}
                            </select>
                        </td>
                        <td>
                            <input type="text" id="bulk_dcr_reminder_reason" name="bulk_dcr_reminder_reason[]" class="form-control bulk_dcr_reminder_reason" value="" >
                        </td>
                        <td>
                            <input class="form-control bulk_dcr_reminder_date datepicker" id="bulk_dcr_reminder_date" name="bulk_dcr_reminder_date[]" type="text" value=""  > 
                        </td>
                        <td>
                            <textarea id="bulk_remarks" name="bulk_remarks[]" class="form-control bulk_remarks" row="5" ></textarea>
                        </td>
                        <td>
                            <a href="#" class="bulk_pob_detail" title="Add Pob Details"> <i class="fa fa-plus"></i></a>
                            <input type="hidden" name="bulk_pob_id[]" class="form-control bulk_pob_id " value="">
                            <input type="hidden" name="bulk_pob_product[]" class="form-control bulk_pob_product " value="">
                            <input type="hidden" name="bulk_pob_qty[]" class="form-control bulk_pob_qty " value="">
                        </td> 
                        <td>
                            <a href="#" class="bulk_gift_detail" title="Add Gift Details"> <i class="fa fa-plus"></i></a>
                            <input type="hidden" name="bulk_gift_id[]" class="form-control bulk_gift_id " value="">
                            <input type="hidden" name="bulk_gift_product[]" class="form-control bulk_gift_product " value="">
                            <input type="hidden" name="bulk_gift_qty[]" class="form-control bulk_gift_qty " value="">
                        </td> 
                        <td>
                            <a href="#" class="bulk_sample_detail" title="Add Sample Details"> <i class="fa fa-plus"></i></a>
                            <input type="hidden" name="bulk_sample_id[]" class="form-control bulk_sample_id " value="">
                            <input type="hidden" name="bulk_sample_product[]" class="form-control bulk_sample_product " value="">
                            <input type="hidden" name="bulk_sample_qty[]" class="form-control bulk_sample_qty " value="">
                        </td> 
                        <td>
                            <a href="#" class="bulk_poster_detail" title="Add Poster Details"> <i class="fa fa-plus"></i></a>
                            <input type="hidden" name="bulk_poster_id[]" class="form-control bulk_poster_id " value="">
                            <input type="hidden" name="bulk_poster_product[]" class="form-control bulk_poster_product " value="">
                            <input type="hidden" name="bulk_poster_qty[]" class="form-control bulk_poster_qty " value="">
                        </td>
                        <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
                        <td style="display: none;"><input type="hidden" name="lin_counter[]"></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

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

        <!-- POB details modal start-->
        <div class="modal fade" id="pobModal">
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <!--Modal Header-->
                    <div class="modal-header">
                        <h4 class="modal-title"> POB Details </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <input type="hidden" class="pobindex" value="">
                    </div>
                    <!-- Modal Body -->
                    <button type="button" class="btn add addpobbox" id="addpobbox"> Add Pob Details</button> 
                    <div class="modal-body ">
                        <div id="preview-area" class="chandru">
                            <table class="overflow-y preview pob_table">
                                <thead>
                                    <tr>
                                        <th>POB Product Name</th>
                                        <th>POB Quanity</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="pob_body">
                                    <tr class="rcopy2 clone2">
                                        <td><input type="hidden" class="pob_product_id form-control"></td>
                                        <td>
                                            <select name="" class="pob_product_name select2 form-control" >
                                                {!!$pob_product!!}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="" class="pob_product_qty form-control">
                                        </td>
                                        <td><a class="removepob removepob"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                            <input type="hidden" name="pob_counter[]" value="" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="javascript:void(0);" class="add_row2 additem" rel=".rcopy2">
                            <i class="fa fa-plus"></i> New Item</a>
                        </div>
                    </div>
                    <!-- Modal footer -->
                </div>
            </div>
        </div>
        <!-- POB details modal end -->

        <!-- Gift details modal start-->
        <div class="modal fade" id="giftModal">
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <!--Modal Header-->
                    <div class="modal-header">
                        <h4 class="modal-title"> Gift Details </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <input type="hidden" class="giftindex" value="">
                    </div>
                    <!-- Modal Body -->
                    <button type="button" class="btn add addgiftbox" id="addgiftbox"> Add Gift Details</button> 
                    <div class="modal-body ">
                        <div id="preview-area" class="chandru">
                            <table class="overflow-y preview gift_table">
                                <thead>
                                    <tr>
                                        <th>Gift Product Name</th>
                                        <th>Gift Quanity</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="gift_body">
                                    <tr class="rcopy3 clone3">
                                        <td><input type="hidden" class="gift_product_id form-control"></td>
                                        <td>
                                            <select name="" class="gift_product_name select2 form-control" >
                                                {!!$gift_product!!}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="" class="gift_product_qty form-control">
                                        </td>
                                        <td><a class="removegift removegift"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                            <input type="hidden" name="gift_counter[]" value="" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="javascript:void(0);" class="add_row3 additem" rel=".rcopy3">
                            <i class="fa fa-plus"></i> New Item</a>
                        </div>
                    </div>
                    <!-- Modal footer -->
                </div>
            </div>
        </div>
        <!-- Gift details modal end -->

        <!-- Sample details modal start-->
        <div class="modal fade" id="sampleModal">
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <!--Modal Header-->
                    <div class="modal-header">
                        <h4 class="modal-title"> Sample Details </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <input type="hidden" class="sampleindex" value="">
                    </div>
                    <!-- Modal Body -->
                    <button type="button" class="btn add addsamplebox" id="addsamplebox"> Add Sample Details</button> 
                    <div class="modal-body ">
                        <div id="preview-area" class="chandru">
                            <table class="overflow-y preview sample_table">
                                <thead>
                                    <tr>
                                        <th>Sample Product Name</th>
                                        <th>Sample Quanity</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="sample_body">
                                    <tr class="rcopy4 clone4">
                                        <td><input type="hidden" class="sample_product_id form-control"></td>
                                        <td>
                                            <select name="" class="sample_product_name select2 form-control" >
                                                {!!$sample_product!!}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="" class="sample_product_qty form-control">
                                        </td>
                                        <td><a class="removesam removesam"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                            <input type="hidden" name="sam_counter[]" value="" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="javascript:void(0);" class="add_row4 additem" rel=".rcopy4">
                            <i class="fa fa-plus"></i> New Item</a>
                        </div>
                    </div>
                    <!-- Modal footer -->
                </div>
            </div>
        </div>
        <!-- Sample details modal end -->

        <!-- Poster details modal start-->
        <div class="modal fade" id="posterModal">
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <!--Modal Header-->
                    <div class="modal-header">
                        <h4 class="modal-title"> Poster Details </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <input type="hidden" class="posterindex" value="">
                    </div>
                    <!-- Modal Body -->
                    <button type="button" class="btn add addposterbox" id="addposterbox"> Add Poster Details</button> 
                    <div class="modal-body ">
                        <div id="preview-area" class="chandru">
                            <table class="overflow-y preview poster_table">
                                <thead>
                                    <tr>
                                        <th>Poster Product Name</th>
                                        <th>Poster Quanity</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="poster_body">
                                    <tr class="rcopy5 clone5">
                                        <td><input type="hidden" class="poster_product_id form-control"></td>
                                        <td>
                                            <select name="" class="poster_product_name select2 form-control" >
                                                {!!$poster_product!!}
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="" class="poster_product_qty form-control">
                                        </td>
                                        <td><a class="removepos removepos"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                            <input type="hidden" name="pos_counter[]" value="" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="javascript:void(0);" class="add_row5 additem" rel=".rcopy5">
                            <i class="fa fa-plus"></i> New Item</a>
                        </div>
                    </div>
                    <!-- Modal footer -->
                </div>
            </div>
        </div>
        <!-- Poster details modal end -->

</form>

</div>
</div>


<script>


$(document).ready(function(){

    // POB details modal start
    $('.bulk_pob_detail').click(function(){
        $('#pobModal').modal('show');
        $('#pobModal').width("70%").css('margin','auto');
        var index=$(this).closest('tr').index();
        $('.pobindex').val(index);
    });

    $('#pobModal').on('hidden.bs.modal', function () {
        $('.pob_product_id').val('');
        $('.pob_product_name').val('').change();
        $('.pob_product_qty').val('');
        $(".pob_body").find("tr:gt(0)").remove();
    });

    $('#pobModal').on('shown.bs.modal', function () {   
        var index = $(this).closest('tr').index();
        var index=$('.pobindex').val();
        var pob_id = $('.bulk_pob_id'+index).val();
        var pob_prd = $('.bulk_pob_product'+index).val();
        var pob_qty = $('.bulk_pob_qty'+index).val();

        if(pob_id !=""){
            var p_i = pob_id.split(',');
            var p_id = p_i.length;
            var index = $(this).closest('tr').index();
            var rowCount = $('.pob_table tbody tr').length;
            if (rowCount > 1)
            {
                $(".pob_table tbody").find("tr:gt(0)").remove();
            }
            for(var j=1; j<p_id;j++ ){
                $('.add_row2').trigger('click');
            }
            $('.pob_product_id').each(function(d){
                if(p_i[d] != 0 ){
                    $(this).val(p_i[d]);
                }else{
                    $(this).val('');
                }
            });
        }
        if(pob_prd !=""){
            var p_l = pob_prd.split(',');
            var prd_n = p_l.length;
            $('.pob_product_name').each(function(a){
                if(p_l[a] != 0 ){
                    $(this).val(p_l[a]).change();
                }else{
                    $(this).val('');
                }
            });   
        }
        if(pob_qty !=""){
            var p_qt = pob_qty.split(',');
            var p_qtt = p_qt.length;
            $('.pob_product_qty').each(function(d){
                if(p_qt[d] != 0 ){
                    $(this).val(p_qt[d]);
                }else{
                    $(this).val('');
                }
            });
        }
    });

    $('#addpobbox').click(function(){
        var pobprd_name=[];
        var pobqty=[];
        var pobid=[];
        var index=$('.pobindex').val();
        $('.pob_product_id').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var name = $(this).val();
            }else{
               var name = 0;
            }
            pobid[k]=name;
        });
        $('.pob_product_name').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var name = $(this).val();
            }else{
               var name = 0;
            }
            pobprd_name[k]=name;
        });
        $('.pob_product_qty').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var qty = $(this).val();
            }else{
               var qty = 0;
            }
            pobqty[k]=qty;
        });

        $('.bulk_pob_id'+index).val(pobid);
        $('.bulk_pob_product'+index).val(pobprd_name);
        $('.bulk_pob_qty'+index).val(pobqty);
        $('.pob_product_name').val('').change();
        $('.pob_product_qty').val('');
        $('.pob_product_id').val('');
        $(".pob_body").find("tr:gt(0)").remove();
        $('#pobModal').modal('hide');
    });
    // POB details modal end

    // gift details modal start
    $('.bulk_gift_detail').click(function(){
        $('#giftModal').modal('show');
        $('#giftModal').width("70%").css('margin','auto');
        var index=$(this).closest('tr').index();
        $('.giftindex').val(index);
    });

    $('#giftModal').on('hidden.bs.modal', function () {
        $('.gift_product_name').val('').change();
        $('.gift_product_id').val('');
        $('.gift_product_qty').val('');
        $(".gift_body").find("tr:gt(0)").remove();
    });

    $('#giftModal').on('shown.bs.modal', function () {   
        var index = $(this).closest('tr').index();
        var index=$('.giftindex').val();
        var gift_id = $('.bulk_gift_id'+index).val();
        var gift_prd = $('.bulk_gift_product'+index).val();
        var gift_qty = $('.bulk_gift_qty'+index).val();
        if(gift_id !=""){
            var g_i = gift_id.split(',');
            var g_n = g_i.length;
            var index = $(this).closest('tr').index();
            var rowCount = $('.gift_table tbody tr').length;
            if (rowCount > 1)
            {
                $(".gift_table tbody").find("tr:gt(0)").remove();
            }
            for(var j=1; j<g_n;j++ ){
                $('.add_row3').trigger('click');
            }
            $('.gift_product_id').each(function(a){
                if(g_i[a] != 0 ){
                    $(this).val(g_i[a]).change();
                }else{
                    $(this).val('');
                }
            });   
        }
        if(gift_qty !=""){
            var p_qt = gift_qty.split(',');
            var p_qtt = p_qt.length;
            $('.gift_product_qty').each(function(d){
                if(p_qt[d] != 0 ){
                    $(this).val(p_qt[d]);
                }else{
                    $(this).val('');
                }
            });
        }
        if(gift_prd !=""){
            var g_l = gift_prd.split(',');
            var prd_n = g_l.length;
            $('.gift_product_name').each(function(a){
                if(g_l[a] != 0 ){
                    $(this).val(g_l[a]).change();
                }else{
                    $(this).val('');
                }
            }); 
        }
    });

    $('#addgiftbox').click(function(){
        var giftprd_name=[];
        var giftid=[];
        var giftqty=[];
        var index=$('.giftindex').val();
        $('.gift_product_name').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var name = $(this).val();
            }else{
               var name = 0;
            }
            giftprd_name[k]=name;
        });
        $('.gift_product_qty').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var qty = $(this).val();
            }else{
               var qty = 0;
            }
            giftqty[k]=qty;
        });
        $('.gift_product_id').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var id = $(this).val();
            }else{
               var id = 0;
            }
            giftid[k]=id;
        });

        $('.bulk_gift_id'+index).val(giftid);
        $('.bulk_gift_product'+index).val(giftprd_name);
        $('.bulk_gift_qty'+index).val(giftqty);
        $('.gift_product_name').val('').change();
        $('.gift_product_id').val('');
        $('.gift_product_qty').val('');
        $(".gift_body").find("tr:gt(0)").remove();
        $('#giftModal').modal('hide');
    });
    // gift details modal end

    // sample details modal start
    $('.bulk_sample_detail').click(function(){
        $('#sampleModal').modal('show');
        $('#sampleModal').width("70%").css('margin','auto');
        var index=$(this).closest('tr').index();
        $('.sampleindex').val(index);
    });

    $('#sampleModal').on('hidden.bs.modal', function () {
        $('.sample_product_name').val('').change();
        $('.sample_product_id').val('');
        $('.sample_product_qty').val('');
        $(".sample_body").find("tr:gt(0)").remove();
    });

    $('#sampleModal').on('shown.bs.modal', function () {   
        var index = $(this).closest('tr').index();
        var index=$('.sampleindex').val();
        var sample_id = $('.bulk_sample_id'+index).val();
        var sample_prd = $('.bulk_sample_product'+index).val();
        var sample_qty = $('.bulk_sample_qty'+index).val();
        if(sample_id !=""){
            var s_l = sample_id.split(',');
            var s_n = s_l.length;
            var index = $(this).closest('tr').index();
            var rowCount = $('.sample_table tbody tr').length;
            if (rowCount > 1)
            {
                $(".sample_table tbody").find("tr:gt(0)").remove();
            }
            for(var j=1; j<s_n;j++ ){
                $('.add_row4').trigger('click');
            }
            $('.sample_product_id').each(function(a){
                if(s_l[a] != 0 ){
                    $(this).val(s_l[a]).change();
                }else{
                    $(this).val('');
                }
            });
        }
        if(sample_prd !=""){
            var g_l = sample_prd.split(',');
            var prd_n = g_l.length;
            $('.sample_product_name').each(function(a){
                if(g_l[a] != 0 ){
                    $(this).val(g_l[a]).change();
                }else{
                    $(this).val('');
                }
            });   
        }
        if(sample_qty !=""){
            var p_qt = sample_qty.split(',');
            var p_qtt = p_qt.length;
            $('.sample_product_qty').each(function(d){
                if(p_qt[d] != 0 ){
                    $(this).val(p_qt[d]);
                }else{
                    $(this).val('');
                }
            });
        }
    });

    $('#addsamplebox').click(function(){
        var add=0;
        var samprd_name=[];
        var samqty = []; var samid=[];
        var index=$('.sampleindex').val();
        $('.sample_product_id').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var name = $(this).val();
            }else{
               var name = 0;
            }
            samid[k]=name;
        });
        $('.sample_product_name').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var name = $(this).val();
            }else{
               var name = 0;
            }
            samprd_name[k]=name;
        });
        $('.sample_product_qty').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var qty = $(this).val();
            }else{
               var qty = 0;
            }
            samqty[k]=qty;
        });

        $('.bulk_sample_id'+index).val(samid);
        $('.bulk_sample_product'+index).val(samprd_name);
        $('.bulk_sample_qty'+index).val(samqty);
        $('.sample_product_name').val('').change();
        $('.sample_product_qty').val('');
        $('.sample_product_id').val('');
        $(".sample_body").find("tr:gt(0)").remove();
        $('#sampleModal').modal('hide');
    });
    // sample details modal end

     // poster details modal start
    $('.bulk_poster_detail').click(function(){
        $('#posterModal').modal('show');
        $('#posterModal').width("70%").css('margin','auto');
        var index=$(this).closest('tr').index();
        $('.posterindex').val(index);
    });

    $('#posterModal').on('hidden.bs.modal', function () {
        $('.poster_product_name').val('').change();
        $('.poster_product_qty').val('');
        $('.poster_product_id').val('');
        $(".poster_body").find("tr:gt(0)").remove();
    });

    $('#posterModal').on('shown.bs.modal', function () {   
        var index = $(this).closest('tr').index();
        var index=$('.posterindex').val();
        var poster_id = $('.bulk_poster_id'+index).val();
        var poster_prd = $('.bulk_poster_product'+index).val();
        var poster_qty = $('.bulk_poster_qty'+index).val();
        if(poster_id !=""){
            var p_l = poster_id.split(',');
            var po_n = p_l.length;
            var index = $(this).closest('tr').index();
            var rowCount = $('.poster_table tbody tr').length;
            if (rowCount > 1)
            {
                $(".poster_table tbody").find("tr:gt(0)").remove();
            }
            for(var j=1; j<po_n;j++ ){
                $('.add_row5').trigger('click');
            }
            $('.poster_product_id').each(function(a){
                if(p_l[a] != 0 ){
                    $(this).val(p_l[a]).change();
                }else{
                    $(this).val('');
                }
            });   
        }
        if(poster_prd !=""){
            var g_l = poster_prd.split(',');
            var prd_n = g_l.length;
            
            $('.poster_product_name').each(function(a){
                if(g_l[a] != 0 ){
                    $(this).val(g_l[a]).change();
                }else{
                    $(this).val('');
                }
            });   
        }
        if(poster_qty !=""){
            var p_qt = poster_qty.split(',');
            var p_qtt = p_qt.length;
            $('.poster_product_qty').each(function(d){
                if(p_qt[d] != 0 ){
                    $(this).val(p_qt[d]);
                }else{
                    $(this).val('');
                }
            });
        }
    });

    $('#addposterbox').click(function(){
        var add=0;
        var posprd_name=[];
        var posqty = [];
        var posid= [];
        var index=$('.posterindex').val();
        $('.poster_product_id').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var name = $(this).val();
            }else{
               var name = 0;
            }
            posid[k]=name;
        });

        $('.poster_product_name').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var name = $(this).val();
            }else{
               var name = 0;
            }
            posprd_name[k]=name;
        });
        $('.poster_product_qty').each(function(k,v){
            var val=$(this).val();
            if(($(this).val()) !=''){
                var qty = $(this).val();
            }else{
               var qty = 0;
            }
            posqty[k]=qty;
        });

        $('.bulk_poster_id'+index).val(posid);
        $('.bulk_poster_product'+index).val(posprd_name);
        $('.bulk_poster_qty'+index).val(posqty);
        $('.poster_product_name').val('').change();
        $('.poster_product_qty').val('');
        $('.poster_product_id').val('');
        $(".poster_body").find("tr:gt(0)").remove();
        $('#posterModal').modal('hide');
    });
    // poster details modal end


    $('.divert_point,.area_point,.doctor_point').css('pointer-events','none');
    
    $(document).on('change','.bulk_tp_date',function(){
        var date = $(this).val();
        var index = $(this).closest('tr').index();
        var id = $('#doctor_dcr_id').val();
        if(date){
            var url = "{{ URL::to('tourapprove')}}/"+date;
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
            $('.divert_point').css('pointer-events','');
        }else{
            $('.divert_point').css('pointer-events','none');
        }
    });


    $(document).on('change','.bulk_divert_detail',function(){
        var index = $(this).closest('tr').index();
        var divert = $(this).val();
        if(divert == "Yes"){
            $(".from_area").jCombo("{{ URL::to('jcomboformlogin?table=m_cities_t:city_id:city_name') }}&order_by=city_name asc",
                {selected_value:""});
            $('.area_point').css('pointer-events','');
            
        }else if(divert == "No"){
            var date = $('.bulk_tp_date'+index).val();
            var id = $('.bulk_doctor_dcr_id'+index).val();
            if(id =='')
                id='0';
            var url = "{{ URL::to('areas')}}/"+date+"/"+id;
            $.get(url,function(data){
                $(".bulk_from_area"+index).html(data['from_area']);
            });
            $('.area_point').css('pointer-events','');
        }else if(divert == ''){
            $('.area_point').css('pointer-events','none');
        }
    });


    $(document).on('change','.bulk_from_area' ,function(){
        var index = $(this).closest('tr').index();
        var date = $('.bulk_tp_date'+index).val();
        var area = $(this).val();
        if(area){
            var url = "{{ URL::to('areawisedoctor') }}/"+area+"/"+date;
            $.get(url,function(data){
                $(".bulk_doctor_id"+index).html(data);
            });
            $('.doctor_point').css('pointer-events','');
        }else{
            $('.doctor_point').css('pointer-events','none');
        }
        
    });

    $(document).on('keypress', '.pob_product_qty,.gift_product_qty,.sample_product_qty,.poster_product_qty', function(ev){
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
            return true;
        }
        ev.preventDefault();
        return false;
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

    var data ="{{\Session::get('j_date_format')}}";
    $(".add_row4").relCopy(data);
    changeclassfields4();

    $('.add_row4').click(function(){
        changeclassfields4();
    });

    var data ="{{\Session::get('j_date_format')}}";
    $(".add_row5").relCopy(data);
    changeclassfields5();

    $('.add_row5').click(function(){
        changeclassfields5();
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



    $(document).on('click', '.remove', function ()
    {
        var index = $(this).closest('tr').index();
        var rowCount = $('.line_data tbody tr').length;
        if(rowCount > 1 ){
            
            var remove_id = $('.bulk_doctor_dcr_id'+index).val();
            var remove = $('.dcr_remove_id').val();
            
            if(remove == ''){
                $('.dcr_remove_id').val(remove_id);
            }
            else{
                var rem = remove+","+remove_id;
                $('.dcr_remove_id').val(rem);
            }

            $(this).closest("tr").remove();
            
            removeClass1('bulk_doctor_dcr_id');
            removeClass1('bulk_tp_date');
            removeClass1('bulk_divert_detail');
            removeClass1('bulk_from_area');
            removeClass1('bulk_doctor_id');
            removeClass1('bulk_focus_product');
            removeClass1('bulk_timing');
            removeClass1('bulk_activity_id');
            removeClass1('bulk_visit_with');
            removeClass1('bulk_outcome_id');
            removeClass1('bulk_dcr_reminder_reason');
            removeClass1('bulk_dcr_reminder_date');
            removeClass1('bulk_remarks');
            removeClass1('bulk_pob_id');
            removeClass1('bulk_pob_product');
            removeClass1('bulk_pob_qty');
            removeClass1('bulk_gift_id');
            removeClass1('bulk_gift_product');
            removeClass1('bulk_gift_qty');
            removeClass1('bulk_sample_id');
            removeClass1('bulk_sample_product');
            removeClass1('bulk_sample_qty');
            removeClass1('bulk_poster_id');
            removeClass1('bulk_poster_product');
            removeClass1('bulk_poster_qty');
        }else{
            notyMsg('info',"You can't Delete Atleast one row should be there");
        }
    });

    $(document).on('click', '.removepob', function ()
    {
        var index = $(this).closest('tr').index();
        var rowCount = $('.pob_table tbody tr').length;
        if(rowCount > 1 ){
            $(this).closest("tr").remove();
            removeClass2('pob_product_name');
            removeClass2('pob_product_qty');
        }else{
            notyMsg('info',"You can't Delete Atleast one row should be there");
        }
    });

    $(document).on('click', '.removegift', function ()
    {
        var index = $(this).closest('tr').index();
        var rowCount = $('.gift_table tbody tr').length;
        if(rowCount > 1 ){
            $(this).closest("tr").remove();
            removeClass3('gift_product_name');
            removeClass3('gift_product_qty');
        }else{
            notyMsg('info',"You can't Delete Atleast one row should be there");
        }
    });

    $(document).on('click', '.removesam', function ()
    {
        var index = $(this).closest('tr').index();
        var rowCount = $('.sample_table tbody tr').length;
        if(rowCount > 1 ){
            $(this).closest("tr").remove();
            removeClass4('sample_product_name');
            removeClass4('sample_product_qty');
        }else{
            notyMsg('info',"You can't Delete Atleast one row should be there");
        }
    });

    $(document).on('click', '.removepos', function ()
    {
        var index = $(this).closest('tr').index();
        var rowCount = $('.poster_table tbody tr').length;
        if(rowCount > 1 ){
            $(this).closest("tr").remove();
            removeClass5('poster_product_name');
            removeClass5('poster_product_qty');
        }else{
            notyMsg('info',"You can't Delete Atleast one row should be there");
        }
    });


});

function changeclassfields1(){

    changeClassName('bulk_doctor_dcr_id');
    changeClassName('bulk_tp_date');
    changeClassName('bulk_divert_detail');
    changeClassName('bulk_from_area');
    changeClassName('bulk_doctor_id');
    changeClassName('bulk_focus_product');
    changeClassName('bulk_timing');
    changeClassName('bulk_activity_id');
    changeClassName('bulk_visit_with');
    changeClassName('bulk_outcome_id');
    changeClassName('bulk_dcr_reminder_reason');
    changeClassName('bulk_dcr_reminder_date');
    changeClassName('bulk_remarks');
    changeClassName('bulk_pob_id');
    changeClassName('bulk_pob_product');
    changeClassName('bulk_pob_qty');
    changeClassName('bulk_gift_id');
    changeClassName('bulk_gift_product');
    changeClassName('bulk_gift_qty');
    changeClassName('bulk_sample_id');
    changeClassName('bulk_sample_product');
    changeClassName('bulk_sample_qty');
    changeClassName('bulk_poster_id');
    changeClassName('bulk_poster_product');
    changeClassName('bulk_poster_qty');
}

function changeclassfields2(){
    changeClassName('pob_product_id');
    changeClassName('pob_product_name');
    changeClassName('pob_product_qty');
}

function changeclassfields3(){
    
    changeClassName('gift_product_id');
    changeClassName('gift_product_name');
    changeClassName('gift_product_qty');
}

function changeclassfields4(){
    
    changeClassName('sample_product_id');
    changeClassName('sample_product_name');
    changeClassName('sample_product_qty');
}

function changeclassfields5(){
    
    changeClassName('poster_product_id');
    changeClassName('poster_product_name');
    changeClassName('poster_product_qty');
}

function changeClassName(className){
    var i=0;
    $('.' + className).each(function (index)
    {
        $(this).removeClass(className + '0');
        $(this).addClass(className + index);

        if(className == "bulk_focus_product"){
            $(this).removeAttr('name');
            $(this).attr('name','bulk_focus_product'+index+'[]');    
        }
        if(className == "bulk_visit_with"){
            $(this).removeAttr('name');
            $(this).attr('name','bulk_visit_with'+index+'[]');
        }

        i++;
    });

}

function removeClass1(className)
{   
    var rowCount = $('.line_data tbody tr').length;
    for (var i = 0; i <= rowCount; i++)
    {
        $('.line_data tbody tr').find('.' + className).removeClass(className + i);
    }

    $('.' + className).each(function (index){
        $(this).addClass(className + index);
    });
}


function removeClass2(className)
{   
    var rowCount = $('.pob_table tbody tr').length;
    for (var i = 0; i <= rowCount; i++)
    {
        $('.pob_table tbody tr').find('.' + className).removeClass(className + i);
    }

    $('.' + className).each(function (index){
        $(this).addClass(className + index);
    });
}

function removeClass3(className)
{   
    var rowCount = $('.gift_table tbody tr').length;
    for (var i = 0; i <= rowCount; i++)
    {
        $('.gift_table tbody tr').find('.' + className).removeClass(className + i);
    }

    $('.' + className).each(function (index){
        $(this).addClass(className + index);
    });
}

function removeClass4(className)
{   
    var rowCount = $('.sample_table tbody tr').length;
    for (var i = 0; i <= rowCount; i++)
    {
        $('.sample_table tbody tr').find('.' + className).removeClass(className + i);
    }

    $('.' + className).each(function (index){
        $(this).addClass(className + index);
    });
}

function removeClass5(className)
{   
    var rowCount = $('.poster_table tbody tr').length;
    for (var i = 0; i <= rowCount; i++)
    {
        $('.poster_table tbody tr').find('.' + className).removeClass(className + i);
    }

    $('.' + className).each(function (index){
        $(this).addClass(className + index);
    });
}


</script>


@include('layouts.php_js_validation')
@endsection