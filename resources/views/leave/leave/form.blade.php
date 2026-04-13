@extends('layouts.header')
@section('content')

<style>
    .red{
        color:red;
    }
.balance {
    background: #2e9ce0;
    color: #fff;
    border-radius:5px;
    padding: 5px;
}
</style>

 <?php include('tools_menu.php');  ?>

<h2 class="heads">LEAVE </h2>

<div class="card">

    <div class="card-body card-block">

        <form  action="" id="leave" data-parsley-validate >

        <input type="hidden" name="edit_id" value="" id="edit_id" />
            {{ csrf_field()}}
        <div class="row">
                

            
             <div class="form-group col-md-4">
                    <label for="start_date" class="form-control-label col-md-5"><span class="red">*</span>Employee Type</label>
                    <div class="col-md-6" >
                      <select name='employee_type' rows='5'  id="employee_type" class='select2 employee_type' required>
                          {!! $employee_type !!}
                      </select>
                    </div>
                     <div class="col-md-1 showinline">
                        <span class="showspan">
                            <i class="fa fa-refresh jcr_leave_type"></i>
                        </span>
                    </div>
                </div>
            
            
                <div class="form-group col-md-4">
                    <label for="start_date" class="form-control-label col-md-5"><span class="red">*</span>Leave Type</label>
                    <div class="col-md-6" >
                      <select name='leave_type' rows='5'  id="leave_type" class='select2 leave_type' required>
                      </select>
                    </div>
                     <div class="col-md-1 showinline">
                        <span class="showspan">
                            <i class="fa fa-refresh jcr_leave_type"></i>
                        </span>
                    </div>
                </div>
                  <div class="form-group col-md-4 worked_date">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span class="red">*</span>Worked Date</label>
                            <div class="col-md-6">
                               <!--  <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd"> -->
                                    <input class="form-control leave_combo datepicker" id="leave_combo" name="leave_combo"   type="text" value="" >
                                   
                               <!--  </div> -->
                            </div>
                    </div>
                <div class="form-group col-md-4 leave_mod">
                    <label for="start_date" class="form-control-label col-md-5"><span class="red">*</span>Leave Mode</label>
                    <div class="col-md-6" >
                      <select name='leave_mode' rows='5'  id="leave_mode" class='select2' data-show-subtext="true" data-live-search="true" required>
						<option value="">-- Please Select --</option>
						<option value="134">HALF DAY</option>
						<option value="135">FULL DAY</option>  
						<option value="281">HALF AN HOUR</option>  
						<option value="282">ONE HOUR</option>
						<option value="283">TWO HOUR</option>
                      </select>
                    </div>
                </div>
                
                <div class="half_day">
                    <div class="form-group col-md-4" >
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span class="red">*</span>Start Date</label>
                                    <div class="col-md-6">
                                                    <!-- <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd"> -->
                                                                    <input class="form-control start_date" id="start_date" name="start_date"  required type="text" value="" >
                                                                    
                                                   <!--  </div> -->
                                    </div>
                    </div>

                    <div class="form-group col-md-4">
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span class="red">*</span>End Date</label>
                                            <div class="col-md-6">
                                                    <!-- <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd"> -->
                                                        <input class="form-control end_date" id="end_date" name="end_date"  required type="text" value="" >
                                                        
                                                    <!-- </div> -->
                                            </div>
                    </div>
                </div>
			
                <div class="full_day">
                    <div class="form-group col-md-4">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span class="red">*</span>Start Date</label>
                            <div class="col-md-6">
                                 <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd"> 
                                    <input class="form-control start_date1" id="start_date1" name="start_date1"  required type="text" value="" readonly>
                                   
                                 </div> 
                            </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span class="red">*</span>End Date</label>
                        <div class="col-md-6">
                             <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd"> 
                                    <input class="form-control end_date1" id="end_date1" name="end_date1"  required type="text" value=""readonly>
                                    
                             </div> 
                        </div>
                    </div>
                </div>
                
                <div class=" form-group col-md-4">
                    <label for="end_date" class="form-control-label col-md-5">No of Days</label>
                    <div class="col-md-6" >
                        <input type="text" id="no_of_days" name="no_of_days" class="form-control no_of_days" value="" readonly>
                    </div>
                </div>
				
                <div class=" form-group col-md-4">
                        <label for="fob_point_name" class="form-control-label col-md-5"><span class="red">*</span>Reason</label>
                        <div class="col-md-6">
                            <input type="text" id="reason" class="form-control" name="reason" required>
                        </div>
                </div>
                <div class="form-group col-md-4">
                        <label for="organization_id" class="form-control-label col-md-5"><span class="red">*</span>Leave Status</label>
                        <div class="col-md-6 pointer">
                            <select name='leave_status' rows='5'  id="leave_status" class='select2' required>				     <option  value="INITIATED">INITIATED</option>			
                                <option  value="APPROVED">APPROVED</option>
                                <option  value="REJECTED">REJECTED</option>
                            </select>
                        </div>
                </div>
                
                <!--<div class="form-group col-md-4">-->
                <!--    <label for="active" class="form-control-label col-md-5 "><span class="red">*</span>Approvers</label>-->
                <!--    <div class="col-md-6 pointer">-->
                <!--        <select id="forwarded_id" class="select2 forwarded_id" id="forwarded_id" name="forwarded_id[]" multiple>-->
                <!--            {!!$reporting!!}-->
                <!--        </select>-->
                <!--    </div>-->
                <!--    <div class="col-md-1 showinline">-->
                <!--        <span class="showspan">-->
                <!--            <i class="fa fa-refresh jcr_reporting_id"></i>-->
                <!--        </span>-->
                <!--    </div>-->
                <!--</div>-->
               <div class="row checkin_out">
     <div class="col-md-offest-6 col-md-6 ">
     <div class="alert alert-success nopunch">
    <strong>Not Punch</strong> 
  </div>
       <div class="alert alert-success yespunch">
    <strong>Check In:</strong><span class="checkin"></span> <br>
    <strong>Check Out:</strong> <span class="checkout"></span>
  </div>
     </div>
 </div>    
        </div>
                
                

        <div class="row text-center">
            <button type="button" id="save" class="btn save  save_form">Save</button>
        </div>
					      

   
    </div>
	
    </form>

<div class="row">
<div class="col-md-12" style="padding: 15px;">
<!-- OUR CONTENT STARTS HERE -->

<table id="grid1"></table>

<!-- OUR CONTENT ENDS HERE -->


</div>
</div>
</div>
</div>
<script>
	$(document).ready(function()
    {
        var g_id="{{\Session::get('groupid')}}";
    if(g_id>3){
        $('.employee_div').css('pointer-events','none');
    }   $('.checkin_out').hide();
        /***** Leave grid Start   ****/
          var date_format="{{\Session::get('p_date_format')}}";
    var data="{{$result}}";
    //alert(data);
	var data=JSON.parse(data.replace(/&quot;/g,'"'));

       
	 /***** Leave grid end   ****/	
         	 /***** export to pdf start   ****/	

          /***** export to pdf end   ****/

        /***** export to Excel Start  ****/


         /***** export to Excel End  ****/

            /***** Grid Search Clear Start  ****/

       
        /***** Grid Search Clear End  ****/
     
 

 $(document).on('click',".jcr_leave_type",function() {
         $("#leave_type").jCombo("{{ URL::to('jcomboformlogin?table=a_lookuplines_t:lookuplines_id:lookup_code') }}&order_by=lookup_code asc"+'&parent= lookup_type="leave_type" &order_by=lookup_code asc' ,
  {selected_value:""});
         
});


		        /***** Reporting manager End  ****/
       
				
        
               
        var condition1=' and lookup_type="leave_type"';
        $("#leave_type").jCombo("{{ URL::to('jcomboform1?table=a_lookuplines_t:lookuplines_id:lookup_code') }}&parent="+condition1+'&order_by=lookuplines_id asc',{selected_value:''});
                
               /***** Set days as half day Function Start  ****/
        $(document).on('change','#end_date',function()
        {
            var leave_mode = $('#leave_mode').select2('val');
            var end_date = $('#end_date').val();
            if(leave_mode == "134" && leave_mode != '' && end_date != '')
            {
                $('#no_of_days').val('0.5');
            }
        });
     /***** Set days as half day Function End  ****/
              
   
               /***** Clear Function End  ****/ 
             /***** Delete Function Start  ****/   
        $(document).on('click','.delete',function(e)
        {
            e.preventDefault();
            var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
            var leave_id = jQuery("#grid1").jqGrid ('getCell', gr, 'leave_id');
             var leave_status = $("#grid1").jqGrid ('getCell', 'leave_status');
            if(gr)
            {
              if(leave_status != 'INITIATED')
                            {
                                notyMsgs('WARNING','Approved Data Cannot Be Delete');
                             location.reload();
                            }
                            else{
                swal({
                  title: "Are you sure?",
                  text: "You want to delete!",
                  type: "warning",
                  showCancelButton: !0,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Yes",
                  cancelButtonText: "No",
                  closeOnCancel:!1
                }, function(e) {
                    if(e == true)
                    {
                        $.get('leave/delete?del_id='+leave_id, function(data,status)
                        {
                           if(data == 2)
                            {
                                notyMsgs('Info','Leave Details Deleted Successfully');
                              location.reload();

                            }
                        });
                    }
                    else
                    {
                     location.reload();
                        $('.apply').css('display','none');
                        swal("Cancelled");
                    }
                });


                $('.apply').css('display','none');
            }
        }
        else
        {
            notyMsgs('Info','Please Select a Row');
          location.reload();
        }
    });
		                    $('.half_day').hide();

		    $(document).on('change','#leave_mode',function()
                    {

                            var leave_mode = $('#leave_mode').select2('val');
                            $('#no_of_days').val('');
                            if(leave_mode == 134 && leave_mode != '')
                            {
                                    $('.start_date1,.end_date1').removeAttr('required');
                                    $('.start_date,.end_date').attr('required','true');
                                    $('.half_day').show();
                                    $('.full_day').hide();
                                    var dateToday = new Date();  
dateToday.setDate(dateToday.getDate() - 1);
var dateToday = new Date(dateToday);

                                    $('.start_date').datepicker({
                                            dateFormat: "yy-mm-dd",
                                            maxDate: dateToday,
                                            onSelect: function(selected) 
                                            {
                                                   $('.end_date').datepicker("option","minDate",$(".start_date").datepicker('getDate') )
                                            },onClose: function () {
        $(this).parsley().validate();
        }

                                    });

                                    $('.end_date').datepicker({
                                            dateFormat: "yy-mm-dd",
                                            minDate: dateToday,
                                            maxDate: dateToday,
                                            onClose: function () {
        $(this).parsley().validate();
        }
                                    }); 
                                    $('.start_date,.end_date').trigger('click');
                            }
                            else
                            {	   
                                $('.start_date1,.end_date1').attr('required','true');
                                $('.start_date,.end_date').removeAttr('required');
                                if(leave_mode != 134 && leave_mode != '')
                                { 

                                        $('.half_day').hide();
                                        $('.full_day').show();
                                        var dateToday = new Date(); 

                                        $('.start_date1').datepicker({
                                                dateFormat: "yy-mm-dd",
                                                maxDate: null,
                                                onSelect: function(selected) 
                                                {
                                                   
                                                       $('.end_date1').datepicker("option","minDate",$(".start_date1").datepicker('getDate') )
                                                },onClose: function () {
        $(this).parsley().validate();
        }
                                        });

                                        $('.end_date1').datepicker({
                                                dateFormat: "yy-mm-dd",
                                                minDate: null,
                                                maxDate: null,
                                                onClose: function () {
        $(this).parsley().validate();
        }
                                         }); 

                                }
                            }
                    });
			
// 		/*** based on leave mode date change start **/
//                     $(document).on('change','#leave_mode',function()
//                     {

//                             var leave_mode = $('#leave_mode').select2('val');
//                             $('#no_of_days').val('');
//                             if(leave_mode == 134 && leave_mode != '')
//                             {
//                                     $('.start_date1,.end_date1').removeAttr('required');
//                                     $('.start_date,.end_date').attr('required','true');
//                                     $('.half_day').show();
//                                     $('.full_day').hide();
//                                     var dateToday = new Date();  
// dateToday.setDate(dateToday.getDate() - 1);
// var dateToday = new Date(dateToday);

//                                     $('.start_date').datepicker({
//                                             dateFormat: "yy-mm-dd",
//                                             maxDate: dateToday,
//                                             onSelect: function(selected) 
//                                             {
//                                                   $('.end_date').datepicker("option","minDate",$(".start_date").datepicker('getDate') )
//                                             },onClose: function () {
//         $(this).parsley().validate();
//         }

//                                     });

//                                     $('.end_date').datepicker({
//                                             dateFormat: "yy-mm-dd",
//                                             minDate: dateToday,
//                                             maxDate: dateToday,
//                                             onClose: function () {
//         $(this).parsley().validate();
//         }
                                                                            


//                       $('.start_date1').datepicker({
//                                                 dateFormat: "yy-mm-dd",
//                                                 maxDate: dateToday,

//                                                 onSelect: function(selected) 
//                                                 {
                                                   
//                                                       $('.end_date1').datepicker("option","minDate",$(".start_date1").datepicker('getDate') )
//                                                 },onClose: function () {
//         $(this).parsley().validate();
//         }
//                                         });

//                                         $('.end_date1').datepicker({
//                                                 dateFormat: "yy-mm-dd",
//                                                 minDate: null,
//                                                 maxDate: null,
//                                                 onClose: function () {
//         $(this).parsley().validate();
//         }
//                                          }); 

//                                 }
//                             }
//                     });
// 			/*** based on leave mode date change end **/
		/***** Delete Function End  ****/
	Date.prototype.addDays = function(days) 
	{
		var date = new Date(this.valueOf())
		date.setDate(date.getDate() + days);
		return date;
	}
   
		
		
		/***** Based on Start date And End date- no of days calculate function start ****/
                $('#start_date1,#end_date1').change(function()
                {
                    var count = 0;
                    var curDate = new Date($('#start_date1').val());
                    var endDate=new Date($('#end_date1').val());

                    while (curDate <= endDate) 
                    {
                        var dayOfWeek = curDate.getDay();
                        var isWeekend = (dayOfWeek == 0); 
                        if(!isWeekend)
                        count++;
                        curDate = curDate.addDays(1);
                    }
                    $('.no_of_days').val(count);
                });
                /***** Based on Start date And End date- no of days calculate function End ****/
                /***** Employee based leave days validation start ****/
                function validate_date()
                {
                    var no_of_days = $('.no_of_days').val();
                     var leave_mode = $('#leave_mode').select2('val');
                     if(leave_mode=="134"){
                    var start_date = $('.start_date').val();
                    var end_date = $('.end_date').val();
                }
                else{
                  var start_date = $('.start_date1').val();
                    var end_date = $('.end_date1').val();
                }
                    var employee_id = $('#employee_id').select2('val');
                    var leave_type = $('#leave_type').select2('val');
                    var result;
                  if(no_of_days!=""&&start_date!=""&&end_date!=""&&employee_id!=""&&leave_type!=""){
                    $.ajax({
                        cache: false,
                        url: 'employeeleavescheck', //this is your uri
                        type: 'GET',
                        dataType: 'json',
                        async : false,
                        data: {no_of_days : no_of_days,start_date:start_date,end_date:end_date,employee_id:employee_id,leave_type:leave_type},
                        success: function(response)
                        {
                            result = response;
                           
                        },
                        error: function(xhr, resp, text)
                        {
                            console.log(xhr, resp, text);
                        }
                    });
                }
                    
                    return result;
                }
		 /***** Employee based leave days validation End ****/
                 
                   /*****  Edit Function start ****/
                $(document).on('click','.edit',function()
                {		
                    var index = $("#grid1").jqGrid('getGridParam','selrow');
                    var leave_id = $("#grid1").jqGrid ('getCell', index, 'leave_id');
                    var employee_id = $("#grid1").jqGrid ('getCell', index, 'employee_id');
                    var leave_type = $("#grid1").jqGrid ('getCell', index, 'leave_type');
                    var leave_mode = $("#grid1").jqGrid ('getCell', index, 'leave_mode');
                    var start_date = $("#grid1").jqGrid ('getCell', index, 'start_date');
                    var end_date = $("#grid1").jqGrid ('getCell', index, 'end_date');
                    var no_of_days = $("#grid1").jqGrid ('getCell', index, 'no_of_days');
                    var leave_reason = $("#grid1").jqGrid ('getCell', index, 'leave_reason');
                    var leave_status = $("#grid1").jqGrid ('getCell', index, 'leave_status');
                    var leave_combo = $("#grid1").jqGrid ('getCell', index, 'leave_combo');
                    var forwarded_id = $("#grid1").jqGrid ('getCell', index, 'forwarded_id');

                    var alloted_days = $("#grid1").jqGrid ('getCell', index, 'alloted_days');
                    var approvel_comments = $("#grid1").jqGrid ('getCell', index, 'approvel_comments');
                    var approval_reason = $("#grid1").jqGrid ('getCell', index, 'approval_reason');
                    var selRows= $('#grid1 tbody .ui-state-highlight').length;   
                    
                    
                    if(leave_id)
                    {
                        if(leave_status == 'INITIATED')
                        {
                            var dateAr = start_date.split('-');
                            var newDate = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];  
                            $('#edit_id').val(leave_id);
                            $('#employee_id').select2('val',[employee_id]);
                            setTimeout(function(){
                                $('#leave_mode').select2('val',[leave_mode]);
                            }, 500);
                            if(leave_combo!=''){
                               var date_com = leave_combo.split('-');
                            var date_com_date = date_com[2] + '-' + date_com[1] + '-' + date_com[0];  
                           $('.leave_combo').val(date_com_date);
                            }else{
                                 $('.leave_combo').val(''); 
                            }
                           $('.leave_combo').trigger('change');
                            if(leave_mode == 135)
                            {
                                $('#start_date1').val(start_date);
                                $('#end_date1').val(end_date);
                            }
                            else
                            { 
                                $('#start_date').val(start_date);
                                $('#end_date').val(end_date);
                            }
                                
                            $('#leave_type').select2('val',[leave_type]);
                          
                       
                            $('#reason').val(leave_reason);
                            $('#leave_status').val(leave_status);
                            
                      
                            $('#alloted_days').val(alloted_days);
                            $('#approvel_comments').val(approvel_comments);
                            $('#approval_reason').val(approval_reason);
                            setTimeout(function()
                            {
                                $('#no_of_days').val(no_of_days);
                            },500);
                            
                        }
                        else
                        {
                            notyMsgs('WARNING','Approved Or Rejected Status Cannot be Edit');
                        }
                    }
                    else
                    {
                        notyMsgs('info','Please Selet A Row');
                    }
                }); 
                /****showcolum***/
                 showcolumn('grid1');
                 /***showcolum***/
                    /*****  Edit Function End ****/
              
              
                
                 /*****  Save Function start ****/
                $(document).on('change','.leave_combo',function(){
                    var leave_combo=$('.leave_combo').val();
                    var employee_id=$('#employee_id').select2('val');
                    if(leave_combo!='' && employee_id!=''){
                      var url	="{{URL::to('getcombodate')}}/"+leave_combo+"/"+employee_id;
                      $.get(url,function(data)
                        {
                             $('.checkin_out').show();
                        if(data['data']==1){
                            $('.nopunch').hide();
                            $('.yespunch').show();
                            $('.checkin').html(data['data_combo']['check_in']);
                            $('.checkout').html(data['data_combo']['check_out']);
                            
                        }else{
                            $('.nopunch').show();
                            $('.yespunch').hide();
                             notyMsg('info','Not Worked on these day'); 
                             $('.leave_combo').val('');
                        }
                        });
                    }else{
                          $('.checkin_out').hide();
                    }
                });
   
   
   $(document).on('change','#employee_id',function(){
       var employee_id=$(this).val();
       if(employee_id!='')

        {
             var url="{{ url::to('leaveapprover') }}?employee_id="+employee_id;
              $.get(url,function(data){ 
             $("#forwarded_id").html('');
                   $("#forwarded_id").html(data);
              });
        }       
       
   });
                
//   $(document).on('change','.leave_type',function(){
//          var leave_type=$('#leave_type').select2('val');
//          var employee_id=$('#employee_id').select2('val');
//         // alert(leave_type);
//         var url="{{ url::to('leavetypebase') }}/"+leave_type+"?employee_id="+employee_id;
//      $.get(url,function(data){
//           $(".balance").html(0);
//          if(data>-1)
//          {
//              $(".balance").html(data);
//          }
                            
//                                 });
         
//       });
              $(document).on('change','.leave_type',function(){
                    var leave_type=$('#leave_type').select2('val');
                    if(leave_type=="273"){
                        $('.worked_date').show();
                        $('.leave_combo').attr('required',true);
                    }else{
                         $('.worked_date').hide();
                         $('.leave_combo').removeAttr('required');
                    }
                });
                $(document).on('click','.save_form',function()
                {
                    var url	="{{URL::to('earnleavesave')}}";
                    var no_of_days = $('.no_of_days').val();
                    var leave_type = $('#leave_type').select2('val');
                   
                    var form = $('#leave');
                    form.parsley().validate();
                  
                    
                //     var result = validate_date();
                //     var check = true;
                   
                //   if(leave_type == 130)
                //   {
                //         if(result['remain_leave']  !=  0)
                //         {
                //             if((no_of_days < result['remain_leave']) && (no_of_days < result['cl']))
                //             {
                //               check = true;
                //             }
                //             else
                //             {
                //                 //notyMsg('info','No Of Days Not Exceed than Remaining Causual Leave  '+result['cl']); 
                //                 check = false;
                //             }
                //         }
                //     }
                //     else if(leave_type == 131 ||  leave_type == 132)
                //     {
                //         var cl= result['cl'];
                //         if(leave_type == 131 && no_of_days > cl)
                //         {
                //           // notyMsg('info','Sick Leave Cannot Be Exceed than '+cl);
                //             check = false;
                //         }
                //         else if(leave_type == 132 && no_of_days > cl)
                //         {
                //           //  notyMsg('info','Earn Leave Cannot Be Exceed than '+cl);
                //             check = false;
                //         }
                //     }
                    var check=true;
                    if (form.parsley().isValid() && check)
                    {	
						        change_date();          
                       var data	= $('#leave').serialize();
                        $.post(url,data,function(data1)
                        {
                            if(data1 == 1)
                            {
                                notyMsg('success','Leave Saved Successfully');
                          location.reload();
                                $('.reset').trigger('click');
                            }
                            else
                            {
                                notyMsg('success','Leave   Updated Successfully'); 
                                location.reload();
                                $('.reset').trigger('click');
                            }
                        });
                    }
                });
                /*****  Save Function End ****/
                });
         
	
	</script>
@include('layouts.php_js_validation')
@endsection
