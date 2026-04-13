  @extends('layouts.header')
@section('content')

<style type="text/css">
/*.select2.loc_id {
  border: none !important;
}*/
/*.loc_id .select2-container{
  border: none !important;
}*/
#sel2 .loc_id .select2-container{
   border: none !important;
  
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
.form-control{
  padding: 3px 12px;
}

@media only screen and (min-width: 1500px) {
  .bulk_line_no{width: 50px !important;}
.bulk_spares_id{width: 120px !important;}
.bulk_inventory_stock{width: 120px !important;}
.bulk_qty{width: 120px !important;}

}




.bulk_line_no{width: 50px;}
.bulk_spares_id{width: 120px;}
.bulk_inventory_stock{width: 120px;}
.bulk_qty{width: 120px;}

</style>

<?php //include('tools_menu.php');
?> 
<span class="ui_close_btn"></span>

<h2 class="heads">  Create Ticket Request
  <span class="ui_close_btn">
  <a class="collapse-close pull-right btn-danger close" onclick='location.href="{{ url($pageMethod) }}"'></a>
</span>
</h2>
<form autocomplete="off" action=" " id="user_form" class="user_form" data-parsley-validate  autocomplete="off" >
    {{ csrf_field() }}
    <input type="hidden" value="" name="request_status" id="request_status" />
   

<div class="card">

<div class="card-body card-block">


  <div class="row">

    <div class="col-md-4">

            <div class="form-group row ticket pagemethod">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Ticket Number</label>
            <div class="col-md-8">
         <input type="text" name="ticket_number" id="causes" class='form-control ticket_number'  value="{{ $row->ticket_number }}" readonly tabindex="4" >

            </div>
            
        </div>

            <div class="form-group row department_id pagemethod">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Department</label>
            <div class="col-md-8">
               
                 <input type="hidden"  name="id" id="id" value="<?php echo $row->id; ?>" />
                  <select name='department_id' rows='5' class='form-control select2 department_id' id="department_id" required>{!! $row->department_id !!}
                </select>
            </div>
            
        </div>
             <div class="form-group row  machine_div">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Machine</label>
            <div class="col-md-7 pagemethod">
                  <select name='machine_id' class='form-control select2 machine_id' required id="machine_id">
                </select>
            </div>
            <div class="col-md-1 showinline bdmachine_div">
                                <i class="fa fa-plus" aria-hidden="true" data-toggle="modal" data-target="#bdmachine" style="color: #142e78;
                font-size: 13px;
                padding: 5px;
                border: 1px solid;
                cursor: pointer;"></i>   
                            </div> 
        </div>


          <div class="form-group row pagemethod">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Breakdown Type</label>
            <div class="col-md-8">
              <select name='break_type_id' rows='5' class='form-control select2 break_type_id'  required>{!! $row->break_type_id !!}
                </select>
            </div>
           
        </div>
                  <?php if($pageMethod=="requestraise" or $pageMethod=="approverequest" ) { if($pageMethod=="approverequest"){$request_remark_re="readonly";} else{
                      
                  $request_remark_re='';}
?>
     <div class="form-group row requestraise" >
    <label class="form-control-label col-md-4" for="request_remark"><span style="color:red;">*</span>Request Remarks</label>
  <div class="col-md-8">
  <div class="input-group col-md-12 ">
      <input class="form-control request_remark" id="request_remark" name="request_remark" size="16" type="text" <?php echo $request_remark_re; ?> value="{{ $row->request_remark }}" required>
    
  </div>
  </div>
    <div class="col-md-2"></div>
</div>
<?php } ?>
      
      <div class="form-group row closerequest">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Critical Spares Used</label>
              <div class="col-md-8 sel2">
       <select name='critical_spare' rows='5' class='form-control select2 critical_spare'  >
           <option {{$row->critical_spare == "" ? "selected" : "" }} value="">--Please Select--</option>
           <option {{$row->critical_spare == "Yes" ? "selected" : "" }} value="Yes">Yes</option>
           <option {{$row->critical_spare == "No" ? "selected" : "" }} value="No">No</option>
           <option {{$row->critical_spare == "Other" ? "selected" : "" }} value="Other">Other</option>
         {!!$row->critical_spare !!}
       </select>
           </div>
            
        </div>
<div class="form-group row closerequest">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Corrective Action</label>
            <div class="col-md-8">
         <!-- <input type="text" name="corrective_action" id="corrective_action" class="form-control corrective_action"  value="{{ $row->corrective_action }}" tabindex="4" > -->
         <textarea name="corrective_action" id="corrective_action" class="form-control corrective_action">{{ $row->corrective_action }}</textarea>
            </div>
            
        </div>
        <div class="form-group row sopupload">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Error Code</label>
            <div class="col-md-8">
         <!-- <input type="text" name="corrective_action" id="corrective_action" class="form-control corrective_action"  value="{{ $row->corrective_action }}" tabindex="4" > -->
         <textarea name="error_code" id="error_code" class="form-control error_code">{{ $row->error_code }}</textarea>
            </div>
            
        </div>

         <div class="form-group row others">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Other Spares</label>
            <div class="col-md-8">
               
                  <input type="text" name="others" id="others" class="form-control others"  value="{{ $row->others }}" tabindex="4" >
            </div>
            
        </div>
        
    </div>
    <div class="col-md-4">
<div class="form-group row pagemethod" >
    <label class="form-control-label col-md-4" for="issue_date"><span style="color:red;">*</span>Ticket Date</label>
  <div class="col-md-8">
  <div class="input-group date form_date col-md-12 datehide">
      <input class="form-control datetimepicker1 issue_date" id="issue_date" name="issue_date" size="16" type="text" value="{{ $row->issue_date }}" required>
    
  </div>
  </div>
    <div class="col-md-2"></div>
</div>
      
      
       <div class="form-group row pagemethod">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Causes of Breakdown</label>
            <div class="col-md-8">

         <textarea name="causes" id="causes" cols="50" class="form-control causes"  tabindex="4"> {!!$row->causes !!}</textarea>
            </div>
            
        </div>
     <div class="form-group row pagemethod">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Breakdown Severity</label>
              <div class="col-md-8 sel2">
       <select name='breakdown_severity' rows='5' class='form-control select2 breakdown_severity' required >
        {!!$row->breakdown_sevearity !!}
       </select>
           </div>
            
        </div>
         <div class="form-group row technician_div">
            <label for="inputIsValid" class="form-control-label col-md-4">Allocate Technician</label>
            <div class="col-md-8">
                 <select multiple id="technician" name='technician[]' rows='5' class='select2 technician' data-show-subtext="true" data-live-search="true" >
                   {!!$row->technician !!}
                 </select>
            </div>
           
        </div>
   
<?php if($pageMethod=="approverequest") { ?>
<div class="form-group row approverequest" >
    <label class="form-control-label col-md-4" for="approve_remarks"><span style="color:red;">*</span>Remarks</label>
  <div class="col-md-8">
  <div class="input-group col-md-12 ">
      <input class="form-control approve_remarks" id="approve_remarks" name="approve_remarks" size="16" type="text" value="{{ $row->approve_remarks }}" required>
    
  </div>
  </div>
    <div class="col-md-2"></div>
</div>
   <?php } ?>
  

        

        <div class="form-group row closerequest">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Preventive Action</label>
            <div class="col-md-8">
              <textarea name="preventive_action" id="preventive_action" class="form-control preventive_action"  >{{ $row->preventive_action }}</textarea>
       <!--   <input type="text" name="preventive_action" id="preventive_action" class="form-control preventive_action"  value="{{ $row->preventive_action }}" tabindex="4" > -->

            </div>
            
        </div>
         <div class="form-group row closerequest">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Is Breakdown ?</label>
            <div class="col-md-8 sel2">
       <select name='is_breakdown' rows='5' class='form-control select2 is_breakdown' required >
           <option {{$row->is_breakdown == "Yes" ? "selected" : "" }} value="Yes">Yes</option>
           <option {{$row->is_breakdown == "No" ? "selected" : "" }} value="No">No</option>
       </select>
           </div>
            
           </div>
        <div class="col-md-6 sopupload">
         <div class="form-group row">
           <label for="active" class="form-control-label col-md-4 ">Attach File</label>
           <div class="col-md-8">
             <input type="file" id="choosefile" name="choosefile" class="choosefile" tabindex="6" >
             
          </div>
       </div>
    </div>
             </div>
    
   
                <div class="col-md-4">
                <div class="form-group row pagemethod">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Maintenance Type</label>
            <div class="col-md-8 sel2">
            <select name='maintenance_type' rows='5' class='form-control select2 maintenance_type' required >
           <option {{$row->maintenance_type == "Machine" ? "selected" : "" }} value="Machine">Machine</option>
           <option {{$row->maintenance_type == "Facility" ? "selected" : "" }} value="Facility">Facility</option>
         {!!$row->maintenance_type !!}
       </select>
            </div>
            
        </div>




<div class="form-group row active pagemethod">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Active</label>
            <div class="col-md-8 sel2">
       <select name='active' rows='5' class='form-control select2 active' required >
           <option {{$row->active == "Yes" ? "selected" : "" }} value="Yes">Yes</option>
           <option {{$row->active == "No" ? "selected" : "" }} value="No">No</option>
         {!!$row->active !!}
       </select>
           </div>
            
           </div>

           <div class="form-group row pagemethod">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Shift</label>
            <div class="col-md-8 sel2">
       <select name='shift' rows='5' class='form-control select2 active' required >
           <option {{$row->shift == "1" ? "selected" : "" }} value="1">1st Shift</option>
           <option {{$row->shift == "2" ? "selected" : "" }} value="2">2nd Shift</option>
           <option {{$row->shift == "3" ? "selected" : "" }} value="3">3rd Shift</option>
           <option {{$row->shift == "4" ? "selected" : "" }} value="4">4th Shift</option>
         {!!$row->shift !!}
       </select>
           </div>
            
           </div>
                     <div class="form-group row engineer">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red">*</span>Allocate Engineer</label>
            <div class="col-md-8">
              <select name='engineer' rows='5' class='form-control select2 engineer'  >
                {!!$row->engineer !!}
                </select>
            </div>
           
        </div>
  <div class="form-group row closerequest">
            <label for="inputIsValid" class="form-control-label col-md-4">Repair Start Date</label>
            <div class="col-md-8">
    <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
      <input class="form-control start_date datetimepickers" id="start_date" name="start_date" size="16" type="text" value="{{ $row->start_date }}" tabindex="5" readonly>
       <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> 
      </div>
      
            </div>
            
        </div>
         <div class="form-group row closerequest">
            <label for="inputIsValid" class="form-control-label col-md-4">Repair End Date</label>
            <div class="col-md-8">
    <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
      <input class="form-control end_date datetimepickers" id="end_date" name="end_date" size="16" type="text" value="{{ $row->end_date }}" tabindex="5" readonly>
       <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> 
      </div>
            </div>
            
        </div>


        
         
       
                </div>

</div>

<!-- raja Code for lines level data-->
             <div class="row linesdiv">
           <div class="col-md-12">
             <a href="javascript:void(0);"  class="add_row additem"  rel=".rcopy"><i class="fa fa-plus"></i> New Item</a>
      
                 <div id="preview-area" class="chandru">
                              <table class="overflow-y preview product_tbl">
                 
                 <thead >
                <tr>                
                <th>Line No</th>
                <th>Spare Name</th>
                <th>Inventory Stock</th>
                <th>Quantity</th>
                  <th>&nbsp;</th>
                </tr>
                </thead>  <?php //dd($productdata); ?>
                 <tbody class="product_tbl_lines_body">
                   <?php if($parent_id>=1)
                                      { ?>
                   @foreach($productdata as $key=>$value)
                     <tr>
                     
                     </tr>
                   @endforeach 
                                    <?php  }  
                       if($parent_id < 1 ) { 
                      ?>
                   
                 <tr class="rcopy clone">
                  
         <td>
          <input type="hidden" name="bulk_bm_lines_id[]" class="form-control input-sm bulk_bm_lines_id" value=""></td>
         <td>
          <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly"></td>
         <td>
            <select name='bulk_spares_id[]' rows='5' class='form-control bulk_spares_id select2' data-show-subtext="true" data-live-search="true">
                        {!!$row->spares_id !!}
            </select>
         </td>
        <td>
          <input type="text" name="bulk_inventory_stock[]" class="form-control input-sm bulk_inventory_stock" data-value="0" value="" readonly="true" >
       
              </td>                
      <td>
      <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty"  data-value="0" >
     </td>
     
                                                                                                                                    
       <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a><input type="hidden" name="counter[]"></td>

            
             </tr> 
               <?php } ?>
             </tbody>    
           </table>
         </div>
       </div>
       </div>
       <div id="bdmachine" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Breakdowns</h4>
      </div>
      <div class="modal-body">
       
           <table class="table table-bordered">   
 
              <tr style="background:#05234e;color:#fff;font-family: fantasy;
    font-size: 14px;">
                <th>Ticket Number</th>
                <th>Issue Date</th>
                <th>Maintenance Type</th>
                <th>Corrective Action</th>
                <th>Preventive Action</th>
                
              </tr>
        
               <?php  $i=1;
        
               foreach($lastmaintenance as $k=>$v) { ?>  
                    <tr>
                  <td>{{$v->ticket_number}}
         
          </td>
          <td>{{$v->issue_date}}
         
          </td>
          <td>{{$v->maintenance_type}}
         
          </td>
           <td>{{$v->corrective_action}}
         
          </td>
           <td>{{$v->corrective_action}}
         
          </td>
                 
                 
              </tr>
          <?php $i++; } ?>
       
          </table>

      </div>
    
      <div class="modal-footer">
<!--         <button type="button" class="btn btn-default addadvancepayamt " data-dismiss="modal">Add</button>-->
        <button type="button" class="btn btn-default close1 " data-dismiss="modal">Close</button>
      </div>
    
    </div>

  </div>
</div>
<!--end-->

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="form-group text-center">
          <?php if($pageMethod=="requestraise") {?>
            <button name="submit" type="button" class="btn save saveform" value="REQUESTED">Request</button>
          <?php }else if($pageMethod=="approverequest"){?>
            <button name="submit" type="button" class="btn save saveform" value="APPROVED">Approve</button>
            <button name="submit" type="button" class="btn save saveform" value="REJECTED">REJECT</button>
          <?php }else if($pageMethod=="closerequest"){?>
            <button name="submit" type="button" class="btn save saveform" value="CLOSED">Close</button>
           <?php  }else if($pageMethod=="allocateengineer"){?>
            <button name="submit" type="button" class="btn save saveform" value="INITIATED">ALLOCATE ENGINEER</button>
           <?php } else if($pageMethod=="allocatetechnician"){?>
            <button name="submit" type="button" class="btn save saveform" value="INITIATED">ALLOCATE TECHNICIAN</button>
           <?php }else{ ?>
            <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
            <button name="submit" type="button" class="btn save saveform" value="SAVE">Save</button>
            <button name="submit" type="button" class="btn save saveform" value="SAVENEW">Save and New</button>
          <?php } ?>
            <a class='btn cancel' onclick='location.href="{{ url($pageMethod) }}"'>Cancel</a>
       </div>
    </div>
</div>
</div>
   
</div>

</form>

<script>
$(document).ready(function()
{
  $('.datetimepicker1').datetimepicker({ weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
    format: "dd-mm-yyyy hh:ii:ss",
    });
  var condition2='';

  $('.bdmachine_div').css('pointer-events','auto');
  $('.machine_div').css('pointer-events','none');
    var page ='<?php echo $pageMethod; ?>';

    if(page == "createissue"){
       
        $('.pagemethod,.technician_div,.engineer').attr('readonly',false);
        $('.pagemethod').css('pointer-events','auto');
      //  $('.department_id').css('pointer-events','none');
        $('.closerequest,.technician_div,.engineer,.bdmachine_div,.sopupload,.ticket,.active').hide();
   

    }else if(page=="allocateengineer"){
        $('.pagemethod').attr('readonly',true);
        $('.pagemethod').css('pointer-events','none');
        $('.bdmachine_div').css('pointer-events','auto');
       // $('#technician').prop('required',true);
        $('.engineer').prop('required',true);
        $('.closerequest,.sopupload,.active').hide();

    }
    else if(page=="allocatetechnician"){
       
        $('.pagemethod').attr('readonly',true);
        $('.pagemethod').css('pointer-events','none');
        $('.bdmachine_div').css('pointer-events','auto');
       // $('#technician').attr('required',true);
        $('.closerequest,.sopupload,.active').hide();

    }else if(page=="sopupload"){
  $('.pagemethod').attr('readonly',true);
      $('.bdmachine_div').css('pointer-events','auto');
      $('.pagemethod,.engineer,.technician_div,.closerequest').css('pointer-events','none');
      $('.closerequest').show();
      $('.active').hide();
      $('.critical_spare,.preventive_action,.corrective_action').attr('required',true);
    }else if(page=="closerequest"){
      $('.pagemethod').attr('readonly',true);
      $('.bdmachine_div').css('pointer-events','auto');
      $('.pagemethod,.engineer,.technician_div').css('pointer-events','none');
      $('.closerequest').show();
      $('.active').hide();
      $('.critical_spare,.preventive_action,.corrective_action').attr('required',true);

$('.sopupload').hide();
// <?php //if(page=="closerequest"){?>
// $(document).on('change','.bulk_spares_id',function(){
//     var index=($(this).closest('tr').index());
//     var product_id=$(this).val();
//     var bom_hdr_id=$(".reference_id").val();
//     var pdtcount = pdtcheck(product_id,index);
//     if(pdtcount <= 0){
//     var url="{{URL::to('getbomqty')}}/"+bom_hdr_id+"/"+product_id;
//     $.get(url,function(data){
//         $(".bulk_qty"+index).val(data[0].component_qty_value);
//     });
//     }
//     else
//         {
//         var msg    = $(".bulk_product_id" + index + ' option:selected').text();
//         var message  = '<span style="color:#fdff65">'+msg+'</span>'+' Product Already Selected';
//         notyMsgs('info',message);
//         rowdataEmpty(index);
//         }
// });
// <?php //} ?>


/*Lines Same Spare name duplicate validation and Image show function */
 $(document).on('change','.bulk_spares_id',function(){
    var index=($(this).closest('tr').index());
    var spares_id=$(this).val();
    var pdtcount = pdtcheck(spares_id,index);
    var url1="{{URL::to('')}}/upload/spares/"; 
    if(pdtcount <= 0){
    var url="{{URL::to('getspareqty')}}/"+spares_id;
    $.get(url,function(data){
       // alert(data[0].qty);
      if((data[0].qty)>0 && data[0].qty!="NULL"){
        qty=data[0].qty
      }else{
        qty = 0;
      }
      $(".bulk_inventory_stock"+index).val(qty);
//
//        url1= url1+data[0].upload_image;
//        if(data[0].upload_image){
//        $(".bulk_upload_image"+index).attr("src",url1);
//      }
    });
    }
    else
        {
        var msg    = $(".bulk_spares_id" + index + ' option:selected').text();
        var message  = '<span style="color:#fdff65">'+msg+'</span>'+' Spare Already Selected';
        notyMsgs('info',message);
        rowdataEmpty(index);
          $(".bulk_spares_id" + index).select2('val',['']);
        }
});

 /*End*/

    }else{
      $('.pagemethod').attr('readonly',true);
        $('.pagemethod,.engineer,.technician_div').css('pointer-events','none');
        $('.closerequest').hide();
        $('.sopupload').hide();
    }
$('.others').hide();
$('.linesdiv').hide();
$('.critical_spare').change(function()
{
  var val = $(this).val();
  if(val=="Other")
  {
 $('.others').show();
 $('.linesdiv').hide();
 $('.others').prop('required',true);
  }else if(val=="Yes"){
    $('.others').hide();
    $('.linesdiv').show();
     $('.others').prop('required',false);

  }else{
     $('.others').hide();
 $('.linesdiv').hide();
 $('.others').prop('required',false);

  }
})
$(".add_row").relCopy(data);
     changeclassfields();
    
     $('.add_row').click(function(){
             changeclassfields();
         });
//$(".machine_div").hide();

/* Ajith Number Validation */
 $(document).on('keypress', '.bulk_qty', function(ev){
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            
    });
 /*End*/

/* Ajith Quantity not Greater than Inventory Stock function*/
$(document).on('change','.bulk_qty',function()
   {
     var index = $(this).closest('tr').index();
    var qty =$(".bulk_qty"+index).val();
 
     var stock =$.trim($(".bulk_inventory_stock"+index).val());
      if(qty > stock) {
                     notyMsg('info',"Quantity should not be Greater than Inventory Stock");
                   $(".bulk_qty"+index).val('');  
        }
         
          });  
/*End*/

 $(document).on('change','.department_id',function()
   {
         $('.machine_div').css('pointer-events','auto');
       $(".machine_div").show();
        var id=$('#department_id').select2('val');
            var condition2='and department_id="'+id+'"';
            $("#machine_id").jCombo("{{ URL::to('jcomboform1?table=w_machine_hdr_t:machine_hdr_id:machine_code|machine_name') }}&parent="+condition2+'&order_by=machine_hdr_id asc',
                {selected_value:'{{$row->machine_id}}'});
       
    });



  $(document).on('click','.remove',function()
   {
     var index = $(this).closest('tr').index();
     var rowCount = $('.product_tbl tbody tr').length;
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
    
  
    $(document).on('click','.addbtn',function(){
       var index = $(this).closest('tr').index();
        //alert(index);
      
    });

/**** To Empty the Rowdata when product Empty ********/
    function rowdataEmpty(index){
    $(".bulk_spares_id" + index).val('').change();
    $(".bulk_inventory_stock" + index).val('');
    $(".bulk_qty" + index).val('');
    $(".bulk_upload_image" + index).val('');
    }

function changeclassfields(){
changeClassName('bulk_bm_lines_id');
changeClassName('bulk_line_no');
changeClassName('bulk_spares_id');
changeClassName('bulk_inventory_stock');
changeClassName('bulk_qty');
changeClassName('bulk_upload_image');
}
function removeclassfields()
{
removeClass('bulk_bm_lines_id');
removeClass('bulk_line_no');
removeClass('bulk_spares_id');
removeClass('bulk_inventory_stock');
removeClass('bulk_qty');
removeClass('bulk_upload_image');
}

  function changeClassName(className){
$('.' + className).each(function (index)
{
if (className == "bulk_line_no")
{
$(this).val(index + 1).attr("readonly", 1);
}

$(this).removeClass(className + '0');
$(this).addClass(className + index);
});
} 
    
function removeClass(className)
{
  var rowCount = $('.product_tbl tbody tr').length;
  for(var i=0;i<=rowCount;i++)
  {
  $('.product_tbl tbody tr').find('.'+className).removeClass(className+i);
  }
  $('.' + className).each(function (index)
  {
    if (className == "bulk_line_no")
    {
    $(this).val(index + 1).attr("readonly", 1);
    }
    $(this).addClass(className + index);
  });
}

  $('.read').css('pointer-events','none');
 $('.company').css('pointer-events','none');
  

      $('#request_status').val('');
    $(document).on('click','.saveform',function() {

  var btnval    = $(this).val();
  $('#request_status').val(btnval);
  validationrule('user_form');
      
    var form = $('#user_form');
    form.parsley().validate();


        if (form.parsley().isValid())
        {            
            var form_data = new FormData(document.getElementById('user_form'));              
            $.ajax({
                  url: "{{ URL::to('issuesave') }}",
                  type: "POST",
                  data: form_data,
                  enctype: 'multipart/form-data',
                  processData: false,  /*tell jQuery not to process the data*/
                  contentType: false,   /*tell jQuery not to set contentType*/
                  async:true,
                  xhr: function(){
                      var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                        percent = Math.ceil(position / total * 100);
                                }
                                }, true);
                        }
          return xhr;

                }
                }).done(function(data,status)
                {
                  console.log(data);
                   var msg    = data.message;
                   notyMsg('success',"<i class='' style='font-size:16px'></i>"+msg+"");
                   setTimeout(function(){
                     $('.close').trigger('click');
                   },300);

                }).fail(function(data,status)
                {
                  $(".alert-success").hide();
                  $(".alert-danger").fadeIn(800);

                });
    }
      
      
});
var id="{{$row->id}}";
if(id!=''){
$('.department_id').trigger('change');
}
});
$(window).on('load',function(){
       var preLoder = $("#preloader");
       preLoder.fadeOut(500);
       var backtoTop = $('.back-to-top')
       backtoTop.fadeOut(100);
   });
</script>

<script>
var dateToday = new Date();
  var data ="{{\Session::get('j_date_format')}}";
  var startt_date = "{{date('Y-m-d',strtotime($row->issue_date))}}";
  var date = new Date("'"+startt_date+"'");
  console.log(date);
    $( "#start_date" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,
      minDate: date,
			maxDate: +7,
      //maxDate: null,
      onClose: function () {
        $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');
    
    $( "#end_date" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,
      minDate: dateToday,
			maxDate: 0,
      //maxDate: null,
      onClose: function () {
        $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');
    
var startt_date = "{{date('Y-m-d',strtotime($row->issue_date))}}";
var date = new Date("'"+startt_date+"'");
    console.log(date);
   $('#start_date').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        format: "dd-mm-yyyy hh:ii:ss",
        minView: 0,
        maxView: 1,
        //forceParse: 0,
        startDate : date
        
  }).on('change',function(){
    $(this).parsley().destroy();
  });
  

   $('#end_date').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        format: "dd-mm-yyyy hh:ii:ss",
        minView: 0,
        maxView: 1,
        forceParse: 0,
        endDate: new Date(),
        
  }).on('change',function(){
    $(this).parsley().destroy();
  });
</script>

@include('layouts.php_js_validation')
@endsection
