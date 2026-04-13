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
</style>
<span class="ui_close_btn"></span>




<?php include('tools_menu.php'); ?><h2 class="heads"> OT <span class="ui_close_btn"></span></h2>



<div class="card">


<div class="card-body card-block">
<div class="row">
   <div class="col-md-12">

<form method="post" action="" id="ot_form"  data-parsley-validate enctype="multipart/form-data">
   		<?php $data=\Session::get('data'); if(isset($data[$pageMethod]['save'])) { ?>

{{ csrf_field() }}
     <!------------------------------------- Body content start here---------------------------->
     
   
   
     <div class="row">
         <div class="col-md-4">
      <div class="form-group row">
           <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Employee Type</label>
           <div class="col-md-7">
            <input type="hidden" id="edit_id" name="ot_id" class="form-control ot_id" value="" >
               <select name='employee_type' id="employee_type" class='form-control employee_type select2' required>
       </select>
               <span class="btn btn-danger dup_name" style="display:none;"></span>
               </div>
          
              <div class="col-md-1 showinline">
 <span class="showspan"><i class="fa fa-refresh jcr_employee_type"></i></span>
              </div></div></div>
           <div class="col-md-4">
<div class="form-group row">
           <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Employee Position</label>
           <div class="col-md-7">
               <select name='position_id' id="position_id" class='form-control position_id select2' required>
       </select>
            
               </div>
          
              <div class="col-md-1 showinline">
 <span class="showspan"><i class="fa fa-refresh jcr_position_id"></i></span>
 </div></div>
            </div>
           <div class="col-md-4">
<div class="form-group row">
           <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Employee Grade</label>
           <div class="col-md-7">
               <select name='grade_id' id="grade_id" class='form-control grade_id select2' required>
       </select>
              
               </div>
          
              <div class="col-md-1 showinline">
 <span class="showspan"><i class="fa fa-refresh jcr_grade_id"></i></span>
 </div></div>
            </div>
           <div class="col-md-4">
       <div class="form-group row">
           <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Minimium OT</label>
           <div class="col-md-7">
               <input class="form-control ot_id" id="ot_id" name="ot_id" size="16" type="hidden" >
               <input type="text" id="min_ot" name="min_ot" class="form-control min_ot"  required>
                
           </div>
          
       </div>
       </div>
           <div class="col-md-4">
                          <div class="form-group row">
           <label for="inputIsValid" class="form-control-label col-md-4">Evening shift Start</label>
           <div class="col-md-7">
               
               
               <input type="time" id="even_start" name="even_start" class="form-control start_time" value="" >
           </div>
          
       </div>
           </div>		   
       
			 
		
          
          <div class="col-md-4">
       <div class="form-group row">
           <label for="inputIsValid" class="form-control-label col-md-4">Evening shift End</label>
           <div class="col-md-7">
             
               <input type="time" id="even_end" name="even_end" class="form-control even_end" value="" >
           </div>
          </div>
       </div>
        
           
	     <div class="col-md-4">	  
		   <div class="form-group row">
           <label for="inputIsValid" class="form-control-label col-md-4">Evening shift Rate</label>
           <div class="col-md-7">

               <input type="text" id="even_rate" name="even_rate" class="form-control even_rate" >
           </div>
           
       </div>
       </div>
             <div class="col-md-4">
       <div class="form-group row">
           <label for="inputIsValid" class="form-control-label col-md-4">Night shift Start</label>
           <div class="col-md-7">
             
               <input type="time" id="night_start" name="night_start" class="form-control night_start" value="" >
           </div>
          </div>
       </div> 
           <div class="col-md-4">
                          <div class="form-group row">
           <label for="inputIsValid" class="form-control-label col-md-4">Night shift rate</label>
           <div class="col-md-7">
               
               
               <input type="text" id="night_rate" name="night_rate" class="form-control night_rate" value="" >
           </div>
          
       </div>
           </div>
          <div class="col-md-4">	  
		   <div class="form-group row">
           <label for="inputIsValid" class="form-control-label col-md-4">Holiday</label>
           <div class="col-md-7">

               <select type="text" id="holiday_ot" name="holiday_ot" class="select2 holiday_ot" >
                   <option value=''>--Please select--</option>
                   <option value="Yes">Yes</option>
                   <option value="No">No</option>
               </select>
           </div>
           
       </div>
       </div>
           <div class="col-md-4">	  
		   <div class="form-group row">
           <label for="inputIsValid" class="form-control-label col-md-4">Holiday Rate</label>
           <div class="col-md-7">

               <input type="text" id="rate_holiday" name="rate_holiday" class="form-control rate_holiday" >
           </div>
           
       </div>
       </div>
          <div class="col-md-4">	  
		   <div class="form-group row">
           <label for="inputIsValid" class="form-control-label col-md-4">Active</label>
           <div class="col-md-7">
     <select type="text" id="active" name="active" class="select2 active" >
               <option value=''>--Please select--</option>
                   <option value="Yes">Yes</option>
                   <option value="No">No</option>
           </select>
           </div>
           
       </div>
       </div>
       </div>  
         
   
   <div class="row">
     <div class="col-lg-12 col-md-12">
         <div class="form-group text-center">             
             <!--   <button type="button"  class="btn btn-cancel cancel reset" >Clear</button> -->
               <button type="button" class="btn save saveform" value="SAVE">Save</button>
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
     </div>

  


 </div>
     <!------------------------------------------------------------------------------------------>

   </div>



    
 <div class="row">
   <div class="col-md-12">

   <table id="grid1"></table>
 </div>
 </div>
   
 </div>


</div>

<script src="<?php echo asset('js/plugins/parsley/dist/parsley.js')?>"></script>
<script>

/***** Deduction employee name Validation Start ***/
         var dup_chk = true;
            function duplicate_validate()
            {
                var employee_type = $("#employee_type").select2('val');

               
                var edit_id = $("#ot_id").val();

                $.ajax({
                    cache: false,
                    url: 'otcategorycheckid', //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async : false,
                    data: {employee_type: employee_type,edit_id : edit_id},
                    success: function(response)
                    {
                        
                        console.log(response);
                        if(response == 1)
                        {
                            $('.dup_name').html('Employee Type:'+response[1]+' Already Exists');
                            $('.dup_name').show();
                            $("#dup_name").val('').select();
                            dup_chk = false;

                        }
                        else if(response == 0)
                        {
                            var html ="";
                            $('.dup_name').hide();
                            dup_chk = true;

                        }

                    },
                    error: function(xhr, resp, text)
                    {
                        console.log(xhr, resp, text);
                    }
                });
            }
            /***** Deduction Component name Validation End ***/







$(document).ready(function()
{


 $(document).on('click',".jcr_employee_type",function() {

         $("#employee_type").jCombo("{{ URL::to('jcomboformlogin?table=a_lookuplines_t:lookuplines_id:lookup_code') }}&order_by=lookup_code asc"+'&parent= lookup_type="EMPLOYEE_TYPE" &order_by=lookup_code asc' ,
  {selected_value:""});
         
});
$(".employee_type").jCombo("{{ URL::to('jcomboformlogin?table=a_lookuplines_t:lookuplines_id:lookup_code') }}&order_by=lookup_code asc"+'&parent= lookup_type="EMPLOYEE_TYPE" &order_by=lookup_code asc' ,
  {selected_value:""});
 $(document).on('click',".jcr_grade_id",function() {

         $("#grade_id").jCombo("{{ URL::to('jcomboform?table=m_position:position_id:position') }}" ,
  {selected_value:""});
         
});
$(".grade_id").jCombo("{{ URL::to('jcomboform?table=m_position:position_id:position') }}" ,
  {selected_value:""});
   $(document).on('click',".jcr_position_id",function() {

         $("#position_id").jCombo("{{ URL::to('jcomboform?table=m_job_title:job_title_id:job_title_name') }}" ,
  {selected_value:""});
         
});
$(".position_id").jCombo("{{ URL::to('jcomboform?table=m_job_title:job_title_id:job_title_name') }}" ,
  {selected_value:""});


$(document).on('click','.saveform',function() {

        
          var form=$("#ot_form");
             form.parsley();
             $('input[name=_token]').val("{{csrf_token()}}");
             form.parsley().validate();
              var data = form.serialize();
             
       
var url="{{ URL::to('otsave') }}";
//duplicate_validate();

if (form.parsley().isValid())
        {
         
$.post(url, data, function(data1)
{
 var status = data1.status;
 var msg    = data1.message;
 notyMsg(status,"<i class='' style='font-size:16px'></i>"+msg);	
  $("#grid1")[0].triggerToolbar();
  $(".clear").trigger('click');
    });

}

      });

 $('.clear').click(function(){

      $(':input','#ot_form')
  .not(':button, :submit, :reset')
  .val('')
  .prop('checked', false);
    $('.employee_type').val("").change();
    $('.position_id').val("").change();
    $('.grade_id').val("").change();
         $('.holiday_ot').val("").change();
         $('.active').val("").change();
	 
         $('.area').val("").change();
         $('.city_id').val("").change();
            });

$("#grid1").jqGrid({


     url:"otData/",
mtype:'GET',
datatype:'json',

colModel: [
{ name: "ot_id", label: "id", width: 100,hidden:true },
{ name: "lookuplines_id", label: "id", width: 100,hidden:true },
{ name: "position_id", label: "id", width: 100,hidden:true },
{ name: "grade_id", label: "id", width: 100,hidden:true },
{ name: "lookup_code", label: "Employee Type", editable:true},
{ name: "job_title_name", label: "Employee Position", editable:true},
{ name: "position", label: "Employee Grade", editable:true},
{ name: "min_ot", label: "Minimium Ot", width: 250,editable:true},
{ name: "even_start", label: "Evening Shift start",editable:true},
{ name: "even_end", label: "Evening Shift End",editable:true},
{ name: "even_rate", label: "Evening Shift Rate",editable:true},
{ name: "night_start", label: "Night Shift Start", width: 250,editable:true},
{ name: "night_rate", label: "Night Shift  Rate", width: 250,editable:true},
{ name: "holiday_ot", label: "Holiday", width: 250,editable:true},
{ name: "rate_holiday", label: "Holiday Rate", width: 250,editable:true},
{ name: "active", label: "Active", width: 250,editable:true},

],
iconSet: "fontAwesome",
   rowNum: 10,
    rowList: [10,20,50,100,250,500,1000],
    sortorder: "asc",
    viewrecords: true,
    gridview: true,
    rownumbers:true,    
      pager: "#grid1",
      autowidth: true,
viewrecords: true,
searching: {
defaultSearch: "cn"
}
});


	
        showcolumn('grid1');


    $("#exportpdf").on("click", function(){
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
            fileName : "OT.pdf",
            mimetype : "application/pdf",
           customSettings:null
        });
    });

	$("#exportexcel").on("click", function(){
		$("#grid1").jqGrid("exportToExcel",{
			includeLabels : true,
			includeGroupHeader : true,
			includeFooter: true,
			fileName : "OT.xlsx",
			maxlength : 40 
		});
                                
                               
    });



/*purpose:clear search the jqgrid*/
  $(".clearsearch").click(function()
  {
    var form=$("#location_form");
   form.parsley().destroy();
    var grid = $("#grid1");
    grid.jqGrid('setGridParam',{search:false});
    var postData = grid.jqGrid('getGridParam','postData');
    $.extend(postData,{filters:""});
    grid.trigger("reloadGrid",[{page:1}]);
    $('input[id*="gs_"]').val("");
    
  });
  /*end*/
	
	


jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$("#grid1").jqGrid("setLabel", "rn", "S.No");

    $("#editdata").click(function()
            {
            var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
            var id = jQuery("#grid1").jqGrid ('getCell', gr, 'ot_id');
            var lookuplines_id = jQuery("#grid1").jqGrid ('getCell', gr, 'lookuplines_id');
            var even_start = jQuery("#grid1").jqGrid ('getCell', gr, 'even_start');
            var even_end = jQuery("#grid1").jqGrid ('getCell', gr, 'even_end');
            var even_rate = jQuery("#grid1").jqGrid ('getCell', gr, 'even_rate');
            var night_start = jQuery("#grid1").jqGrid ('getCell', gr, 'night_start');
            var night_rate = jQuery("#grid1").jqGrid ('getCell', gr, 'night_rate');
            var min_ot = jQuery("#grid1").jqGrid ('getCell', gr, 'min_ot');
            var holiday_ot = jQuery("#grid1").jqGrid ('getCell', gr, 'holiday_ot');
            var rate_holiday = jQuery("#grid1").jqGrid ('getCell', gr, 'rate_holiday');
            var active = jQuery("#grid1").jqGrid ('getCell', gr, 'active');
            var position_id = jQuery("#grid1").jqGrid ('getCell', gr, 'position_id');
            var grade_id = jQuery("#grid1").jqGrid ('getCell', gr, 'grade_id');
          
            if(gr)
            {

         $('#ot_id').val(id);
         $('#position_id').select2('val',[position_id]);
         $('#employee_type').select2('val',[lookuplines_id]);
         $('#grade_id').select2('val',[grade_id]);
         $('#active').select2('val',[active]);
         $('#holiday_ot').select2('val',[holiday_ot]);
         $('#even_start').val(even_start);
         $('#even_end').val(even_end);
         $('#even_rate').val(even_rate);
         $('#night_start').val(night_start);
         $('#night_rate').val(night_rate);
         $('#min_ot').val(min_ot);
         $('#rate_holiday').val(rate_holiday);
        
    
     }
            else
            {
            notyMsg("info","Please Select a Row");
            }
            });
});

$(document).on('click','.delete',function(e){
        e.preventDefault();
        var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
        var ot_id = jQuery("#grid1").jqGrid ('getCell', gr, 'ot_id');
        if(ot_id )
        {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: !0,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }, function(e) {
                 if(e == true)
                 {
                    $.get('otdelete/'+ot_id, function(data,status)
                    {
                        var data = $.trim(data);
                        if(data =='0')
                        {
                            notyMsg('success','Deleted Successfully');
                            setTimeout(function(){
                            $("#grid1")[0].triggerToolbar();
                            }, 1500);
                        }
                        if(data =='2')
                        {
                            notyMsg('error',"You Cant't delete  Used in SomeWhere");
                            setTimeout(function(){
                            $("#grid1")[0].triggerToolbar();
                            }, 1500);
                        }
                    });
                }
                else{
                  $('.apply').css('display','none');
                  swal("Cancelled");
                }
            })
             $('.apply').css('display','none');
        }
        else
        {
           notyMsg("info","Please Select a Row");
        }
   });
	

	
	
	
	
$(window).on('load',function(){
       //preloader
       var preLoder = $("#preloader");
       preLoder.fadeOut(500);
       var backtoTop = $('.back-to-top')
       backtoTop.fadeOut(100);
   });


$(".editdata,.clear").click(function(){
var form=$("#ot_form");
   form.parsley().destroy();

 });



</script>


@endsection
