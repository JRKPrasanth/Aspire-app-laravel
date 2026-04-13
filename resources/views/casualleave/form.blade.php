@extends('layouts.header')
@section('content')

<style>
.red
{
    color:red;
}
</style>
<span class="ui_close_btn"></span>
<?php include('tools_menu.php')?><h2 class="heads">Casual Leave</h2>
<div class="card">


                <div class="card-body card-block">
                    <form  action=""  id="save" >
					 <?php $data=\Session::get('data'); if(isset($data[$pageMethod]['save'])) { ?>

                        <input type="hidden" name="edit_id" value="" id="edit_id" />
                                    {{ csrf_field()}}
						 <div class="row">
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span class="red">*</span>Company</label>
                                <div class="col-md-6">
                                    <select  id="company_id" name="company_id" class="select2 company_id" value="" required>
                                    </select>
                                </div>
                               

                                <div  class="col-md-1 showinline">
 <span class="showspan"><i class="fa fa-refresh jcr_company_id"></i></span>
 </div>
 <div class="col-md-offset-3 col-md-6">
     <span class=" btn btn-danger dup_name" style="display:none;    font-size: 12px;
    font-weight: bold;"></span>
 </div>
  
                            </div>
						
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span class="red" >*</span>Year</label>
                                    <div class="col-md-6">
                                      <select  id="year" name="year" class="form-control years select2" value="" required>
                                          
                                      </select>
                                    </div>
								 
								  
                            </div>
				  <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span class="red" >*</span>Employee Type</label>
                                    <div class="col-md-6">
                                      <select class="select2 form-control employee_type" name="employee_type" id="employee_type" required>
                            </select>
                                    </div>
								  <div class="showinline col-md-1">
 <span class="showspan"><i class="fa fa-refresh jcr_employee_type"></i></span>
 </div>
								  
                            </div>		
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span class="red">*</span>Casual Leave</label>

                                                            <div class="form_date col-md-6" data-link-format="yyyy-mm-dd">
                                              <input class="form-control casual_leave " id="casual_leave" name="casual_leave" type="text" maxlength="2" value="" required>
                                </div>

                            </div>
						
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span class="red"> *</span>Sick Leave</label>
									
                                   <div class="form_date col-md-6" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control sick_leave" id="sick_leave" name="sick_leave" type="text" maxlength="2"  value="" required >
                                  </div>
                              </div>
						
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span class="red">*</span>Earn Leave</label>				
                                  <div class="form_date col-md-6" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control earn_leave" id="earn_leave" name="earn_leave" type="text" maxlength="2"  value="" required>
                                  </div>
                            </div>
						</div>
                            <div class="row">
                                <div class="col-md-12 text-center">
								<button type="button"  class="btn save save_form">SAVE</button> 
                                   <?php include('toolbar.php'); ?>
                                </div>
                            </div>
                         			      
<?php } else { ?>
	   <div class="row text-center">
	   
        <?php  include('toolbar.php'); ?>
   
    </div>
	<?php } ?>      
                               
                    </form>
					
            </div>
	
                <div class="row">
                    <div class="col-md-12">
                    <table id="grid1"></table>
                    </div>
                </div>





</div>

	<script>
	$(document).ready(function(){
            /** company jcombo **/
            $("#company_id").jCombo("{{ URL::to('jcomboform?table=m_company_t:company_id:company_name') }}&order_by=first_name asc",
            {selected_value:"{{\Session::get('companyid')}}"});
             /** employee type jcombo **/
              var condition="lookup_type='EMPLOYEE_TYPE'";
      $(".employee_type").jCombo("{{ URL::to('jcomboform?table=a_lookuplines_t:lookuplines_id:lookup_meaning') }}&parent="+condition, {
                selected_value: ""
            });
            /**** year dropdown  start**/
		  var min = 1900,
					max = new Date().getFullYear(),
					select = document.getElementById('year');

				for (var i = max; i>=min; i--){
					var opt = document.createElement('option');
					opt.value = i;
					opt.innerHTML = i;
					select.appendChild(opt);
					
				}
          /**** year dropdown  end**/

 $(document).on('click',".jcr_company_id",function() {
         $("#company_id").jCombo("{{ URL::to('jcomboform?table=m_company_t:company_id:company_name') }}",
                {selected_value:''});
});



 $(document).on('click',".jcr_employee_type",function() {
         $("#employee_type").jCombo("{{ URL::to('jcomboformlogin?table=a_lookuplines_t:lookuplines_id:lookup_code') }}&order_by=lookup_code asc"+'&parent= lookup_type="EMPLOYEE_TYPE" &order_by=lookup_code asc' ,
  {selected_value:""});
         
});



         /** casual leave jqgrid start **/
            $("#grid1").jqGrid({
                    url: "casualleavegrid",
                    datatype: "json",
                    mtype: "GET",

                    colModel: [
                    { name: "casual_leave_id", label: "casual_leave_id", width: 250,editable:true, editrules:{date:true},hidden:true},
                    { name: "employee_type", label: "employee_type", width: 250,editable:true, editrules:{date:true},hidden:true},
                    { name: "company_id", label: "Company", width: 250,editable:true, editrules:{date:true},hidden:true},
                    { name: "company_name", label: "Company", width: 250,editable:true, editrules:{date:true}},
                    { name: "year", label: "Year", width: 250,editable:true, editrules:{date:true}},
                    { name: "casual_leave", label: "Casual Leave", width: 250,editable:true, editrules:{date:true}},
                    { name: "sick_leave", label: "Sick Leave", width: 250,editable:true, editrules:{date:true}},
                    { name: "earn_leave", label: "Earn Leave", width: 250,editable:true, editrules:{date:true}},
                 

                    ],
                  
       rowNum:10,
		viewrecords: true,
		footerrow: true,
		rownumbers: true ,
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rowList: [10, 20, 50, 100,500,1000],
		pager: "#grid1",
        sortorder: "desc",
                    searching: {
                        defaultSearch: "cn"
                    }
                });

        jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$("#grid1").jqGrid("setLabel", "rn", "S.No");
	showcolumn('grid1');
          /** casual leave jqgrid end **/
          /** export to pdf start **/
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
  fileName : "Casual Leave.pdf",
  mimetype : "application/pdf"  
});
	 });
          /** export to pdf end **/
          /** export to excel start **/
	 $(document).on('click',".exportexcel",function() {
$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Casual Leave.xlsx"
    					
				})	
});
 /** export to excel end **/
            $("#company_id,#year,.employee_type").change(function(){
				  $('.dup_name').hide();
			});
		   $("#casual_leave,#sick_leave,#earn_leave").keyup(function(){
				  $('.dup_name').hide();
			});
                        /** edit function start **/
            $("#editdata").click(function()
            {
                var form=$("#save");
                form.parsley().destroy();
            $('.dup_name').hide();
            var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
            var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'casual_leave_id');
            var company_id = jQuery("#grid1").jqGrid ('getCell', gr, 'company_id');
            var casual_leave = jQuery("#grid1").jqGrid ('getCell', gr, 'casual_leave');
            var sick_leave = jQuery("#grid1").jqGrid ('getCell', gr, 'sick_leave');
            var earn_leave = jQuery("#grid1").jqGrid ('getCell', gr, 'earn_leave');
            var year = jQuery("#grid1").jqGrid ('getCell', gr, 'year');
            var employee_type = jQuery("#grid1").jqGrid ('getCell', gr, 'employee_type');
            
            if(gr)
            {   
                $('#edit_id').val(cellValue);
                $('#company_id').select2('val',[company_id]);
                $('#year').select2('val',[year]);
                $('#employee_type').select2('val',[employee_type]);
                $('#casual_leave').val(casual_leave);
                $('#sick_leave').val(sick_leave);
                $('#earn_leave').val(earn_leave);
            }
            else
            {
                notyMsgs('Info',"Please Select a Row");
            }
            });

            /** edit function end **/
            /** clear form feilds function start **/
            $('.reset').click(function()
            {
                var form=$("#save");
                form.parsley().destroy();
                $('#edit_id').val('');
                var comp="{{\Session::get('companyid')}}";
                $('#company_id').select2('val',[comp]);
                var currentYear = (new Date).getFullYear();
                $('#year').select2('val',[currentYear]);
                $('#employee_type').select2('val',['']);
                $('#casual_leave').val('');
                $('#sick_leave').val('');
                $('#earn_leave').val('');
                $('.dup_name').hide();
            });
                 /** clear form feilds function end **/
                 /*** numbers only validation start **/
            $(document).on('keypress', '.sick_leave,.casual_leave,#earn_leave', function(ev)
            {
                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) 
                {
                    return true;
                }
                ev.preventDefault();
                return false;
            });
      /*** numbers only validation end **/
      /** duplicate check funcation start **/
            var dup_chk = true;
            function duplicate_validate()
            {
                var year = $("#year").select2('val');
                var company_id = $("#company_id").select2('val');
                var employee_type = $("#employee_type").select2('val');
                var edit_id = $("#edit_id").val();
				
                $.ajax({
                    cache: false,
                    url: 'casualleave/checkname', //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async : false,
                    data: {year : year,company_id:company_id,edit_id : edit_id,employee_type : employee_type},
                    success: function(response)
                    {
                     
                        if(response == 1)
                        {
                            
                            $('.dup_name').html('This Combination is Already Exists');
                            $('.dup_name').show();
                            var form=$("#save");
                            form.parsley().destroy();
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
  /** duplicate check funcation end **/
			$('.start_date,.end_date').datepicker({format: 'yyyy-mm-dd', autoClose: true});
		
		    jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
                    /*** save function start ***/
                    
            $(document).on('click','.save',function(e){
				
                e.preventDefault();
                var data;
                data = $("#save").serialize();
                duplicate_validate();
                var form = $('#save');
                form.parsley().validate();
                if(form.parsley().isValid() && dup_chk)
                {
                    $.post('casualleave/save', data, function(data)
                    {
                        if(data == 1)
                        {
                            notyMsg('success','Saved Successfully ');
                            $("#grid1")[0].triggerToolbar();
                            $('.reset').trigger('click');

                        }
                        else if(data == 2)
                        {
                            notyMsg('success','Updated  Successfully ');
                            $("#grid1")[0].triggerToolbar();
                            $('.reset').trigger('click');
                        }
                    });
                }

            });
   /*** save function end ***/

   /*** delete function start ***/

            $(document).on('click','.delete',function(e){
                e.preventDefault();
                var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
                var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'casual_leave_id');
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
                    $.get('casualeave/delete?del_id='+cellValue, function(data,status)
                    {
                        if(data == 0)
                        {
                            notyMsg('SUCCESS','Deleted successfully');
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
                 notyMsgs('Info',"Please Select a Row");
                }


            });
               /*** delete function end ***/
               /*** clear search in grid  start **/
	$(".clearsearch").click(function()
	{	

	    var grid = $("#grid1");
	    grid.jqGrid('setGridParam',{search:false});

	    var postData = grid.jqGrid('getGridParam','postData');
	    $.extend(postData,{filters:""});
	    grid.trigger("reloadGrid",[{page:1}]);
	    $('input[id*="gs_"]').val("");
	   
	});
          /*** clear search in grid  end **/
    });
	</script>
@include('layouts.php_js_validation')
@endsection
