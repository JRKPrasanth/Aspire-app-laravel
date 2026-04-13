@extends('layouts.header')
@section('content')
<style type="text/css">
    .dup_name{
        font-size: 11.5px;
        font-weight: bolder;
        margin: 15px -2px;
        padding: 3px;
        line-height: unset;
        height:unset;
    }
    .loader {
  border: 10px solid #f3f3f3;
  border-radius: 50%;
  border-top: 10px solid #3498db;
  width: 70px;
  height: 70px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<span class="ui_close_btn"></span>
<?php include('tools_menu.php'); ?>

  <h2 class="heads">Miss Punch Overall Request</h2>
<div class="card">

            <form  action=""  id="save" >
			<?php  $data=\Session::get('data');
     // dd($data);
                if($pageMethod=="punchoverallrequest") { ?>
                <div class="card-body card-block">
                    <input type="hidden" name="edit_id" value="" id="edit_id" />
                {{ csrf_field()}}
                <div class="row">
                	 <div class="form-group col-md-4">
                    <label for="start_date" class="form-control-label col-md-5"><span class="req">*</span>Employee Name</label>
                    <div class="col-md-6 " >
                      <select name='employee_id' rows='5'  id="employee_id" class='select2' required>
                      </select>
                    </div>
                     <div class="col-md-1 showinline">
                        <span class="showspan">
                            <i class="fa fa-refresh jcr_emp"></i>
                        </span>
                    </div>
                </div>
                
                      <div class="form-group col-md-4 ">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span class="req">*</span>Attendance Date</label>
                            <div class="col-md-6">
                               <!--  <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd"> -->
                                    <input class="form-control date datepicker" id="date" name="date"  required type="text" value="" readonly required>
                                   
                               <!--  </div> -->
                            </div>
                    </div>
                      <div class="form-group col-md-4">
                            <label for="inputIsValid" class="form-control-label col-md-5"><span class="req">*</span>In Time</label>
                            <div class="col-md-6">
                                <input type="text" id="in_time" name="in_time" class="form-control datetimepicker1 in_time" value="" required>
                            </div>
                    </div> 
                    <div class="form-group col-md-4">
                            <label for="inputIsValid" class="form-control-label col-md-5"><span class="req">*</span>Out Time</label>
                            <div class="col-md-6">
                                <input type="text" id="out_time" name="out_time" class="form-control datetimepicker1 out_time" value="" required>
                            </div>
                    </div>

                    
                    <div class="form-group col-md-4 ">
                            <label for="inputIsValid" class="form-control-label col-md-5"><span class="req">*</span>Reason</label>
                            <div class="col-md-6">
                                <input type="text" id="reason" name="reason" class="form-control reason" value="" required>
                            </div>
                    </div>
                    
                   
                    <div class="form-group col-md-4" style="pointer-events: none">
                        <label for="organization_id" class="form-control-label col-md-5"><span class="req">*</span>Misspunch Status</label>
                        <div class="col-md-6 pointer">
                            <select name='status' rows='5'  id="status" class='select2' required>				
                            <option  value="INITIATED">INITIATED</option>			
                            <option  value="APPROVED">APPROVED</option>
                            <option  value="REJECTED">REJECTED</option>
                            </select>
                        </div>
                        </div>
                      <div class="form-group col-md-4">
                    <label for="active" class="form-control-label col-md-5 "><span class="req">*</span>Forwarded To</label>
                    <div class="col-md-6 ">
                        <select id="forwarded" class="select2 forwarded_id" required name="forwarded_id" multiple>
                            {{!!$reporting!!}}
                        </select>
                    </div>
                    <div class="col-md-1 showinline">
                        <span class="showspan">
                            <i class="fa fa-refresh jcr_reporting_id"></i>
                        </span>
                    </div>
                </div>
                </div>
                
                
                <div class="row">
                <div class="col-md-12 text-center ">
                     <button type="button" id="save" class="btn save">Save</button> &nbsp;&nbsp;&nbsp;
      
                  <?php include('toolbar.php'); ?>
                                  </div>
              </div>
              
        </div>
		<?php } else { ?>
	   <div class="row text-center">
      <?php  include('toolbar.php'); ?>
   
    </div>
	<?php } ?>
</form>

<div class="row">
              <div class="col-md-12">
              <table id="grid1"></table>
              </div>
              </div>
        </div>

	<script>
   
     
 $('.pointer').css('pointer-events','none');
        /***** Reporting manager Start  ****/
        var forwarded_id = '{{$forwarded_id}}';
        var logged_id = '{{$logged_id}}';
	var condition="  employee_id!="+logged_id;
	$("#employee_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:employee_number|first_name') }}",
        {selected_value:logged_id});
        $(document).on('click','.jcr_reporting_id',function()
        {
           // $('.pointer').css('pointer-events','unset');
            $("#forwarded_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:employee_number|first_name') }}&parent="+condition+'&order_by=employee_id asc',{selected_value:logged_id});
        });
       
	$('.datetimepicker1').datetimepicker();
		
		$(document).on('click',".jcr_component",function() {
         $("#misspunch").jCombo("{{ URL::to('jcomboformlogin?table=a_lookuplines_t:lookuplines_id:lookup_code') }}&order_by=lookup_code asc"+'&parent= lookup_type="misspunch" &order_by=lookup_code asc' ,
  {selected_value:""});
         
});
	//	$("#forwarded_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:employee_number|first_name') }}&parent="+condition+'&order_by=employee_id asc',{selected_value:forwarded_id});

	

	 var condition1=' and lookup_type="misspunch"';
        $("#misspunch").jCombo("{{ URL::to('jcomboform1?table=a_lookuplines_t:lookuplines_id:lookup_code') }}&parent="+condition1+'&order_by=lookuplines_id asc',{selected_value:''});


         /*Validation*/
    // $(document).on('keypress','.in_time,.limitto,.time,.company_contribute', function(ev){
    //        var regex = new RegExp("^[0-9.]+$");
    //                 var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
    //                 if (regex.test(str)) {
    //                     return true;
    //                 }
    //                 ev.preventDefault();
    //                 return false;
    //     });
       
       
function reset(){
  $('#date,#reason,#time,#in_time,#out_time').val('');
  $('#misspunch').select2('val',['']);
}
//	$(document).ready(function(){
        /***** Jqgrid Deduction load data Start ***/

// var date_format="{{\Session::get('j_date_format')}}";
// var data="{{$result}}";
//     //alert(data);
//     var data=JSON.parse(data.replace(/&quot;/g,'"'));

//         $("#grid1").jqGrid(
//         {
//             url: "missdata",
//             datatype: "json",
//             mtype: "GET",
//             colModel: [
//                 { name: "miss_id", label: "id", width: 250, hidden: true },
//                 { name: "employee_id", label: "Employee Id", width: 250, hidden: true },
//                 { name: "forwarded_id", label: "Forwarded Id", width: 250, hidden: true },                    
//                 { name: "first_name", label: "Employee Name", width: 250},
//                 { name: "reporting_name", label: "Reporting Name", width: 250},
//                 { name: "lookup_meaning", label: "Missed Punch Name", width: 250},
//                 { name: "misspunch", label: "Miss Punch  Id", width: 250,hidden: true },
//                 { name: "employee_type", label: "Components  Id", width: 250,hidden: true },
//                 { name: "date", label: "Date", width: 250 ,editable:true, formatter: 'date', formatoptions: {srcformat:"Y-m-d",  newformat: date_format}},
//                 { name: "time", label: "Time", width: 250},
//                 { name: "in_time", label: "In Time", width: 250},
//                 { name: "out_time", label: "Out Time", width: 250},
//                 { name: "reason", label: "Reason", width: 250},
//                 { name: "status", label: "Status", width: 250},
//             ],

//             iconSet: "fontAwesome",
//             rowNum: 10,
//             rowList: [10,20,100,1000,2000],
//             sortname: "miss_id",
//             sortorder: "desc",
//             viewrecords: true,
//             gridview: true,
//             rownumbers:true,
//             pager: "#grid1",
//             multiselect:false,
//             multipageselection:true,
//             searching: {
//             defaultSearch: "cn",
//             },
//         });
// showcolumn('grid1');
//         jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
//         /***** Jqgrid Deduction load data End ***/


    /***** Jqgrid Deduction Export to Pdf data Start ***/
    	 $(document).on('click',".exportpdf",function() {
       	$("#grid1").jqGrid('exportToPdf', {
      title: null,
      orientation: 'portrait',
      pageSize: 'A4',
      description: null,
      onBeforeExport: null,
      download: 'download',
      includeLabels : true,
      includeGroupHeader : true,
      includeFooter: true,
      fileName : "Deductions.pdf",
      mimetype : "application/pdf"  
    });
    	 });
/***** Jqgrid Deduction Export to Pdf data End ***/
/**  tto greater than from **/
 $(document).on('change',".limitto,.in_time",function() {
     var limitto=$('.limitto').val();
     var in_time=$('.in_time').val();
    if(in_time!='' && limitto!='' ){
     if(limitto<in_time){
        $(this).val(''); 
          notyMsg('info','To Limit  is  greater than From limit');
     }
    }
 });
/***** Jqgrid Deduction Export to Excel data Start ***/
	 $(document).on('click',".exportexcel",function() {
$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Deductions.xlsx"
    					
				})	
});
/***** Jqgrid Deduction Export to Excel data End ***/

/***** Deduction Edit Start ***/
            $("#edit").click(function()
            {
                var form=$("#save");
                form.parsley().destroy();
                var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
                var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'miss_id');
                var misspunch = jQuery("#grid1").jqGrid ('getCell', gr, 'misspunch');
                var lookup_meaning = jQuery("#grid1").jqGrid ('getCell', gr, 'lookup_meaning');
                var in_time = jQuery("#grid1").jqGrid ('getCell', gr, 'in_time');
                var out_time = jQuery("#grid1").jqGrid ('getCell', gr, 'out_time');
                var date = jQuery("#grid1").jqGrid ('getCell', gr, 'date');
                var time = jQuery("#grid1").jqGrid ('getCell', gr, 'time');
                var reason = jQuery("#grid1").jqGrid ('getCell', gr, 'reason');
                var status = jQuery("#grid1").jqGrid ('getCell', gr, 'status');
                var reason1 = jQuery("#grid1").jqGrid ('getCell', gr, 'reason1');
                var in_out = jQuery("#grid1").jqGrid ('getCell', gr, 'in_out');
                var forwarded_id = jQuery("#grid1").jqGrid ('getCell', gr, 'forwarded_id');
                var employee_id = jQuery("#grid1").jqGrid ('getCell', gr, 'employee_id');
                
            

            if(gr)
            {
                if(status=="INITIATED"){
                $('#misspunch').select2('val',[misspunch]);
                $('#employee_id').select2('val',[employee_id]);
                $('#forwarded_id').select2('val',[forwarded_id]);
                $('.in_time').val();
               
                    var parsedDate = $.datepicker.parseDate("yy-mm-dd", date);
                    $('.date').val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));                
                $('.time').val(time);
                $('.reason').val(reason);
                $('.status').val(status);
                $('.in_time').val(in_time);
                $('.out_time').val(out_time);
                $('.in_out').val(in_out);
                $('#edit_id').val(cellValue);
                 var result = $("#misspunch option:selected").text();
                if(result == "In Punch Missing" || result == "Out Punch Missing")
                {
                    $('.contribute').show();
                    $('.time,.date,.reason').attr('required',true);
                   
                  
                }
                else if(result=="In-Out Punch Missing" || result=="DA" || result=="Work from Home" || result=="Out-door Duty"){
                   $('.contribute1').show();
                
                      $('.contribute').hide();
                    
                     $('.in_time,.out_time,.reason1').removeAttr('required');
                }
                else{
                    $('.contribute').hide();
                    $('.contribute1').hide();
                     $('.employee_type_hide').hide();
                    $('.time,.company_contribute,.company_contribute1').removeAttr('required');
                }
            }else{
                notyMsg('info',status+" Data can't be edit"); 
            }
            }
            else
            {
                notyMsg('info','Please Select a Row');
            }
            });
            /***** Deduction Edit End ***/

            $('.contribute,.contribute1').hide();

            /***** Deduction Change component Start ***/
            $(document).on('change','#misspunch',function()
            {
               $('.dup_name').hide();
                var result = $("#misspunch option:selected").text();
//alert(result);
                
                if(result == "In Punch Missing" || result == "Out Punch Missing")
                {
                    $('.contribute').show();
                    $('.contribute1').hide();
                     $('.time').attr('required',true);
                      $('.in_time,.out_time').removeAttr('required');  
                   // $('.employee_type_hide').hide();
                    //$('.employee_type').removeAttr('required');  
                }
                else if(result=="In-Out Punch Missing"  || result=="Work from Home" || result=="Out-door Duty"){
                   $('.contribute1').show();
                       $('.contribute').hide();
                        $('.time').removeAttr('required');
                      $('.in_time,.out_time').attr('required',true);
                    //$('.contribute1').hide();
//$('.time,.reason,.date').removeAttr('required');
                }
                else
                {
                   // $('.contribute').hide();
                    $('.contribute1').hide();
                    $('.time,.company_contribute,.limitto,.company_contribute1,.employee_type').removeAttr('required');
                }
            });
            /***** Deduction Change component End ***/

            
            /***** Deduction Save Start ***/
            $(document).on('click','.save',function(){

                
                var data;
       
               // duplicate_validate();
                var form = $('#save');
                form.parsley().validate();
                 if (form.parsley().isValid())
            {
               
                    change_date();
                data = $("#save").serialize();
                $.post('misspunchsaveap', data, function(data)
                {
               
                    if(data == 1)
                    {
                        notyMsg('success','Saved Successfully');
                  setTimeout(function(){
                      window.location.reload();
                  },500);
                      
                    }
                    else if(data == 2)
                    {
                        notyMsg('success','Updated Successfully');
                         setTimeout(function(){
                      window.location.reload();
                  },500);
                    }
                    reset();
                });
                
            }

            });
            /***** Deduction Save End ***/

            /***** Deduction Cancel Start ***/
            $(document).on('click','.cancel',function()
            {
                var form=$("#save");
                form.parsley().destroy();
                $('#component').val('').select2();
                $('#employee_type').val('').select2();
                $('.in_time').val('');
             
                var dates="<?php echo date('Y-m-d'); ?>";
                var parsedDate = $.datepicker.parseDate("yy-mm-dd",dates);
                $('.date').val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));   
                $('.time').val('');
                $('.company_contribute').val('');
                $('#edit_id').val('');
                $('.contribute').hide();
                $('.contribute1').hide();
                $("#grid1")[0].triggerToolbar();
                  //  $('.dup_name').hide();

            })
            /***** Deduction Cancel End ***/

/***** Deduction Clear Search Start ***/
$(".clearsearch").click(function()
	{	

	    var grid = $("#grid1");
	    grid.jqGrid('setGridParam',{search:false});

	    var postData = grid.jqGrid('getGridParam','postData');
	    $.extend(postData,{filters:""});
	    grid.trigger("reloadGrid",[{page:1}]);
	    $('input[id*="gs_"]').val("");
	                  
	});
    /***** Deduction Clear Search End ***/

    /***** Deduction Delete Start ***/
            $(document).on('click','.delete',function(e){
                e.preventDefault();
                var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
                var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'id');
                if(gr)
                {
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
                    $.get('deduction/delete?del_id='+cellValue, function(data,status)
                    {
                        if(data == 1)
                        {  
                            notyMsg('warning','Deletion Error Already Used In Somewhere');
                            $("#grid1")[0].triggerToolbar();
                        }
                        else if(data == 2)
                        {
                            notyMsg('success','Deleted Successfully');
                            $("#grid1")[0].triggerToolbar();
                        }
                    });
                }
                else
      {
        $('.apply').css('display','none');
        swal("Cancelled");
      }
    });
  $('.apply').css('display','none');
  }
                else
                {
                 notyMsg("info","Please Select a Row");
                }


            });
            /***** Deduction Delete End ***/
            
            
             var g_id="{{\Session::get('groupid')}}";
  
    if(g_id>3){
        
 $('.employee_pointer').css("pointer-events","none");
        
    }else{
        
            $('.employee_pointer').css("pointer-events","auto");
    }
 $(document).on('change','#employee_id',function(){
       var employee_id=$(this).val();
       if(employee_id!='')

        {
             var url="{{ url::to('leaveapprover') }}?employee_id="+employee_id;
               $.get(url,function(data){ 
            var data=jQuery.parseJSON(data);
            //  $(".week_off").val(data.week_off[0].week_off)
              
             $("#forwarded_id").html('');
                  $("#forwarded_id").html(data.reporting);
               });
        }       
       
   });
   	/***** Based on Start date And End date- no of days calculate function start ****/
                $('#date').change(function()
                {
                    var emp_id=$("#employee_id").val();
                    var count = 0;
                    var curDate = new Date($('#date').val());
              

                    // $.get("{{URL::to('misspunchdatecheck')}}?employee_id="+emp_id+"&date="+$('#date').val(),function(data){
                        
                    //     if(data!='0')
                    //     {
                    //      $('#date').val('');   
                    //      notyMsgs('info','Already Leave Has Been Applied');
                    //     }
                        
                    // });

              //      while (curDate <= endDate) 
         //           {
                     //   var dayOfWeek = curDate.getDay();
                   //     var isWeekend = (dayOfWeek == 0); 
                    //    if(!isWeekend)
                     //   count++;
                     //   curDate = curDate.addDays(1);
                    
                   // $('.no_of_days').val(count);
                });
//	});
	</script>
@include('layouts.php_js_validation')
@endsection
