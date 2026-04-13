@extends('layouts.header')
@section('content')

<style>
    .red{
        color:red;
    }
	.img_location{
    float:right;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqgrid/4.6.0/js/jquery.jqGrid.min.js"></script>
<h2 class="heads">EMPLOYEE PAY  REPORT</h2>


<div class="card">

    <div class="card-body card-block">

      
<div class="row">
<div class="col-md-12" style="padding: 15px;">
<!-- OUR CONTENT STARTS HERE -->
 <div class="img_location">
        <img src="{{asset('/images/Clear.png')}}" class="clear" height="30px;" width="30px" >
        <img src="{{asset('/images/excel.png')}}" class="exportexcel" height="30px;" width="30px" >
        <img src="{{asset('/images/pdf.png')}}" class="exportpdf" height="30px;" width="30px">
    </div>
<table id="grid1"></table>

<!-- OUR CONTENT ENDS HERE -->


</div>
</div>
</div>
</div>

<script>
	$(document).ready(function()
    {
        /***** employee pay grid  Start   ****/
          var date_format="{{\Session::get('p_date_format')}}";
        var data="{{$result}}";
        var datacolumn="{{$datacolumn}}";
	var data=JSON.parse(data.replace(/&quot;/g,'"'));
	datacolumn=JSON.parse(datacolumn.replace(/&quot;/g,'"'));
		$("#grid1").jqGrid(
            {
               datatype: "local",
                colModel:datacolumn,

                iconSet: "fontAwesome",
                rowNum: 10,
                rowList: [10,20,100,1000,2000],
                sortorder: "desc",
                viewrecords: true,
                gridview: true,
                rownumbers:true,
                pager: "#grid1", data:data,
                multiselect:false,
                multipageselection:true,
                searching: {
                defaultSearch: "cn",
                },
            });
		
        jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
        $("#gs_start_date").attr("placeholder","Eg:2018-10-31");
        $("#grid1").jqGrid("setLabel", "rn", "S.No");
	 /***** employee pay grid end   ****/	
         	 /***** export to pdf start   ****/	
	 

            /***** Grid Search Clear Start  ****/

        $(".clear").click(function()
	{	
	    var grid = $("#grid1");
	    grid.jqGrid('setGridParam',{search:false});

	    var postData = grid.jqGrid('getGridParam','postData');
	    $.extend(postData,{filters:""});
	   location.reload();
	    $('input[id*="gs_"]').val("");
	   
	});
        /***** Grid Search Clear End  ****/

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
                      fileName : "Employee Pay Report.pdf",
                      mimetype : "application/pdf"  
                    });
                });
		
        /***** export to pdf END  ****/
		
        /***** export to Excel Start  ****/
		     $(document).on('click',".exportexcel",function() 
            {
                $("#grid1").jqGrid("exportToExcel",{
                        includeLabels : true,
                        includeGroupHeader : true,
                        includeFooter: true,
                        fileName : "Employee Pay Report.xlsx"

                })	
            });
		
		
         /***** export to Excel End  ****/

                
   
                });
	
	</script>
@include('layouts.php_js_validation')
@endsection
