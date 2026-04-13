@extends('layouts.header')
@section('content')

<style>
    
    .tooltip {
  position: relative;
  display: inline-block;
  opacity: 1;
  z-index: unset;
}

.tooltip .tooltiptext {
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}
.tooltip .tooltiptext {
  width: 250px;
  top: 85%;
  left: 50%;
  margin-left: -110px; /* Use half of the width (120/2 = 60), to center the tooltip */
}
</style>

    <h2 class="heads">Payment For EL Encashment</h2>
    <div class="panel panel-visible" id="spy1">


    <div class="panel-title ">
            <div class="row">
                <div class="col-md-3">
                    <button class="btn btn-primary  create_payment add"   data-action="createoffer"  id="">Create Payment</button>
</div>
<div class="col-md-7">
    ALL. TOTAL: <?php if($sum_inv_tot[0]->total > 0){ ?> <a class="btn inv_grand_tot">INR.{{$sum_inv_tot[0]->total}} </a> <?php } else { ?> <a class="btn inv_grand_tot">INR.0.00 </a> <?php } ?>
    Total (based on selection): <div class="tooltip"><a id="inv_search_tot" class="btn inv_search_tot" >INR. 0.00</a><span class="tooltiptext">Click Here to get Selected Total Amount</span></div>
</div>
    </div>
    <div class="row">
 <div class="col-md-12 " style="padding: 15px;" >
                          

    <table id="grid1"></table>

    <!-- OUR CONTENT ENDS HERE -->


    </div>
    </div>
    </div>
    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content modal_data">
          <br>
          <div class="row">
              <div class="col-md-6">
                  <label>Reason</label>
              </div>
              <div class="col-md-6">
                  <input type='text' class='form-control reason' id='reason'>
              </div>
          </div>
          <div class='row'>
              <button type='button'  class='btn hold_release approve'>Submit</button>
              <button type='button'  class='btn cancel'>Close</button>
          </div>
      </div>
      
    </div>
  </div>
   
    @extends('layouts.footer')


    <script type="text/javascript">
    $(document).ready(function()
    {
        // grid data for payslip
        var date_format="{{\Session::get('p_date_format')}}";
		$("#grid1").jqGrid(
		{
                url: "elencashpayment",
                datatype: "json",
                mtype: "GET",
                colModel: [
                { name: "id", label: "id", width: 150, hidden: true },
                { name: "employee_id", label: "employee_id", width: 150, hidden: true},
                { name: "emp_number", label: "Employee Number", width: 150},
                { name: "emp_name", label: "Employee Name", width: 150},
                { name: "sub_department_name", label: "Department", width: 150},
                { name: "active", label: "Active", width: 150},
                 { name: "status", label: "Status", width: 150},
                { name: "doj", label: "Date of joining", width: 150},
                { name: "total_el", label: "Total EL", width: 150},
                { name: "extra_el", label: "Eligible for Encashment", width: 150},
                { name: "salary_day", label: "daysalary", width: 150, hidden: true},
                { name: "total_amt", label: "Total Amount", width: 150},
                ],

                iconSet: "fontAwesome",
                rowNum: 10,
                rowList: [10,20,100,1000,2000],
                sortorder: "desc",
                viewrecords: true,
                gridview: true,
                rownumbers:true,
                pager: "#grid1",
                multiselect:false,
                multipageselection:true,
                searching: 
				{
                defaultSearch: "cn",
                },
                multiselect:true,
                multiPageSelection:true,
              });

          
            jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
              $("#gs_date").attr("placeholder","Eg:2018-10-31");	
	            //jQuery("#grid1").jqGrid("hideCol","cb");
		        $("#grid1").jqGrid("setLabel", "rn", "S.No");
		
                // Create Payment function
            $(document).on('click','.create_payment',function(){
                var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
                var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,cellvalues = [];
                for (i = 0, n = selIds.length; i < n; i++) {
                    var v=	$grid.jqGrid("getCell", selIds[i], "employee_id");
                    if(v!=false)
                    cellvalues.push(v);
                }
       if(gr){
        var url = "{{ URL::to('paymentforelencashcreate')}}/"+cellvalues;
         window.location.replace(url);
          }
	else
	{
	notyMsg("info","Please Select a Row");
	}
            });
			
		$("#inv_search_tot").click(function()
            {
                var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
                //var invoice_grand_total = jQuery("#grid1").jqGrid ('getCell', gr, 'invoice_grand_total');
                var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	 cellvalues=[];
		  var pid = [];
	     var slid = [];
			var check='';

			//alert(cellvalues);
		var j=0;
		//alert(selIds.length);
    for (i = 0, n = selIds.length; i < n; i++) {
	var v=	$grid.jqGrid("getCell", selIds[i],"total_amt");

		if(v!=false){
		cellvalues.push(v);
		var sum_tot = 0;
        for (let f = 0; f < cellvalues.length; f++) {
            sum_tot += parseFloat(cellvalues[f]);
        }
		console.log(sum_tot);
		}
	}
                if(gr)
                {
                    

                    $(".inv_search_tot").html('INR. '+sum_tot);
                
                }else{
                  $(".inv_search_tot").html('INR. 0.00');  
                } 
            });     	
			
			
		$(".search").click(function(){   
			var dept=$(".department_id").val();
			if(dept!="")
			{
             var url="{{URL::to('employeepayrolforpaygrid')}}?dept="+dept;
			  $("#grid1").jqGrid('setGridParam',{ url: url });
			  
			}
			else{
              notyMsg("info","Please Choose Feilds")
			}
			});
			
  $(document).on('click','.select_hold',function(){
        var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
                        cellValues = [];
			for (i = 0, n = selIds.length; i < n; i++) 
			{
                            cellValues.push($grid.jqGrid("getCell", selIds[i], "id"));
			}
                       
                        if(cellValues.length ==1){
                            $('.open_modal').trigger('click');
                    }
                    else if(cellValues.length>1){
                        notyMsg('info','Please select only one row');
                    }
                    else{
                          notyMsg('info','Please select a row'); 
                    }
    })
    
    // Hold Release
     $(document).on('click','.hold_release',function(){
         var reason = $('.reason').val();
         console.log(reason);
            var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
                        cellValues = [];
			for (i = 0, n = selIds.length; i < n; i++) 
			{
                            cellValues.push($grid.jqGrid("getCell", selIds[i], "id"));
			}
                       
                        if(cellValues.length !=0){
			var url = "{{URL('holdpaymentfremp')}}/?row_id="+cellValues+"&reason="+reason;
			$.get(url,function(data)
                        {
                            if(data == 1)
                            {
                                notyMsg('success','Payment Hold Successfully');
                                location.reload();
                            }
                            
			});
                    }
                    else{
                          notyMsg('info','Please select a row'); 
                    }
     });
  
    $(".cancel").click(function(){   
        $('#myModal').hide();
    });
    $(".clear").click(function(){   
        var grid = $("#grid1");
        grid.jqGrid('setGridParam',{search:false});

        var postData = grid.jqGrid('getGridParam','postData');
        $.extend(postData,{filters:""});
       location.reload();
        $('input[id*="gs_"]').val("");
    });

    });
    </script>
@include('layouts.php_js_validation')
@endsection
