@extends('layouts.header')
@section('content')
<style>
    input[type="file"] {
    display: none;
}
.custom-file-upload 
{
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
.btnic 
{
    text-align: center;
    background-color: DodgerBlue;
    border: none;
    color: white;
    padding: 12px 30px;
    cursor: pointer;
    font-size: 12px;
}
</style>


<span class="ui_close_btn"></span>


<h2 class="heads">Release Payroll</h2>

            <div class="card">
                    

                            <div class="card-body card-block">
                              <div class="row">
                                
                               <div class=" col-md-6">
                                <div class="col-md-12">


                            <form action="{{ url('employeeuploadsave')}}" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="edit_id" value="" id="edit_id" />
                                {{ csrf_field()}}
									<div class="form-group col-md-12">
									  
									 </div>
                                
                            </form>
                            </div>
                          </div>

                            <div class="col-md-12" style="margin-bottom: 20px;margin-top: 20px;">
                <div class="col-md-12"> 
                   <div class="col-md-2">
							Company:
							<select id="company" class="form-control select2">
							</select>
						</div>
                    <div class="col-md-2">
                        Source:
						<select id="source" class=" select2">
						</select>
                    </div>
                    <div class="col-md-2">
                        Month:
						<select id="month" class=" select2">
						</select>
                    </div>
                    <div class="col-md-2">
                        Year:
						<select id="year" class=" select2">
						</select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn  btn-primary approve releasesource">Release Payroll</button>
                    </div>
                </div> 
               	 <!--	<div class="col-md-4"> 
						<div class="col-md-6">
							Source:
							<select id="source1" class="select2" >
							</select>
						</div>
						 <div class="col-md-2">
							<button type="button" class="btn btn-primary search generate">Search</button>
						</div>
					</div>
					-->			
					<div class="row">
					
			
									<button type='button'  class='btn released_selected approve'>Release Selected</button>
							
						
					</div>
                    
                    
                </div> 
             
                       
            
            </div>
                                   
                                
			<div class="row">
				<div class="col-md-12 " style="padding: 15px;" >
                 <div class="img_location">
       <img src="{{asset('/images/Clear.png')}}" class="clear" height="30px;" width="30px" >
        <img src="{{asset('/images/excel.png')}}" class="exportexcel" height="30px;" width="30px" >
        <img src="{{asset('/images/pdf.png')}}" class="exportpdf" height="30px;" width="30px">
    </div>
				<table id="grid1"></table>
				</div>
			</div>
                      
                    </div>
                    </div>
    
  
	<script>
	$(document).ready(function(){
            // companyload 
            		$("#company").jCombo("{{ URL::to('jcomboformlogin?table=m_company_t:company_id:company_code') }}",{selected_value:'<?php echo \Session::get('companyid'); ?>'});
	// year dropdown
        var min = 1900,
					max = new Date().getFullYear(),
					select = document.getElementById('year');

				for (var i = max; i>=min; i--){
					var opt = document.createElement('option');
					opt.value = i;
					opt.innerHTML = i;
					select.appendChild(opt);
					
				}
		
		// jcombo for payroll type
		var condition ='lookup_code !="MONTHLY ENTRY" and lookup_type="payroll_type"';
		$("#source,#source1").jCombo("{{ URL::to('jcomboformlogin?table=a_lookuplines_t:lookuplines_id:lookup_code') }}&order_by=lookuplines_id asc"+'&parent='+condition,
		{selected_value:""});
		
		// grid for apporve payroll
		$("#grid1").jqGrid(
            {
                url: "employeepayrollreleasegrid",
                datatype: "json",
                mtype: "GET",
                colModel: [
                    { name: "id", label: "payproposal_id", width: 250, hidden: true },
                    { name: "employee_id", label: "employee_id", width: 250, hidden: true},
					{ name: "employee_number", label: "Employee Number", width: 250},
                    { name: "first_name", label: "Employee Name", width: 250},
                    { name: "approved_status", label: "Status", width: 250},
					{ name: "sub_department_name", label: "Department Name", width: 250},
					{ name: "attendance_days", label: "Working Days", width: 250},
					{ name: "month", label: "Month", width: 250},
					{ name: "year", label: "Year", width: 250},
                    { name: "basic_salary", label: "Basic", width: 250},
                    { name: "da", label: "DA", width: 250},
                    { name: "hra", label: "HRA", width: 250},
                    { name: "annual_allowance", label: "Annual Allowance", width: 250},
                    { name: "volunter_pf", label: "Voluter PF", width: 250},
                    { name: "esi_val", label: "ESI Value", width: 250},
                    { name: "pf_val", label: "PF Value", width: 250},
                    { name: "pt_val", label: "Professional tax", width: 250},
                    { name: "lookup_code", label: "Payroll Type", width: 250},
                    { name: "esi1", label: "Esi", width: 250},
                    { name: "pf1", label: "PF", width: 250},
                    { name: "pt1", label: "Professional tax", width: 250},
                    { name: "gross_salary", label: "Gross Pay", width: 250},
                    { name: "net_salary", label: "Net Amount ", width: 250 },
					{ name: "ctc_pay", label: "CTC Pay", width: 250 },
                  
                ],

                iconSet: "fontAwesome",
                rowNum: 10,
                rowList: [10,20,100,1000,2000],
                sortorder: "desc",
                viewrecords: true,
                gridview: true,
                rownumbers:true,
                pager: '#grid1',
                multiselect:true,
                multipageselection:true,
                searching: {
                defaultSearch: "cn",
                },
            });
		
		
		
	 	jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	 var condition1 ='1=1';
         // month dropdown
		$("#month").jCombo("{{ URL::to('jcomboformlogin?table=month:id:description') }}&order_by=id asc"+'&parent='+condition1,
                {selected_value:'<?php echo date("m"); ?>'});	
		// sleected in grid data to approve
		$(document).on('click','.released_selected',function()
                {
			var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
                        cellValues = [];
			for (i = 0, n = selIds.length; i < n; i++) 
			{
                            cellValues.push($grid.jqGrid("getCell", selIds[i], "id"));
			}
                       
                        if(cellValues.length !=0){
			var url = "{{URL('releasedpayroll')}}/?row_id="+cellValues;
			$.get(url,function(data)
                        {
                            if(data == 1)
                            {
                                notyMsg('success','Payroll Approved Successfully');
                                location.reload();
                            }
			});
                    }
                    else{
                          notyMsg('info','Please select a row'); 
                    }
		});


        $(".clear").click(function()
    {   
        var grid = $("#grid1");
        grid.jqGrid('setGridParam',{search:false});

        var postData = grid.jqGrid('getGridParam','postData');
        $.extend(postData,{filters:""});
       location.reload();
        $('input[id*="gs_"]').val("");
       
    });
                // approve from source
		$(document).on('click','.releasesource',function()
		{	
			var company =  $('#company').select2('val');
			var source 		 =  $('#source').select2('val');
			var month 		 =  $('#month').select2('val');
			var year         =  $('#year').select2('val');
			
                        if(company != '' && source != '' && month != '' && year!='' ){
			var url = "{{URL::to('releasepayrollsource')}}?company="+company+"&source="+source+"&month="+month+"&year="+year;
			$.get(url,function(data)
                        {
                         
                            notyMsgs("success","Approved Successfully");

                            setTimeout(function(){
                            location.reload();
                            }, 1000);
                		
                        });
                        }
                        else{
                            notyMsg('Info',"Please Choose All the Fields");
                        }
		});	
		
	  /***** export to PDF Start  ****/

     $(document).on('click',".exportpdf",function() 
                {
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
                      fileName : "Approve Payroll Report.pdf",
                      mimetype : "application/pdf"  
                    });
                });
		  /***** export to PDF END  ****/
		  /***** export to Excel Start  ****/
		     $(document).on('click',".exportexcel",function() 
            {
                $("#grid1").jqGrid("exportToExcel",{
                        includeLabels : true,
                        includeGroupHeader : true,
                        includeFooter: true,
                        fileName : "Approve Payroll Report.xlsx"

                })	
            });
		
		
         /***** export to Excel End  ****/	
		
	});
	</script>

@endsection