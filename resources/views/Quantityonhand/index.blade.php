@extends('layouts.header')
@section('content')


<?php if($productgroup == "FINISHED GOODS" || $productgroup =="SEMI FINISHED GOODS" ) {?>
<h2 class="heads">FINISHED GOODS and semi finished goods Quantity On Hand</h2>
<?php } else if($productgroup=="RAW MATERIALS") { ?>
<h2 class="heads">RAW MATERAILS and packing materials Quantity On Hand</h2>
<?php } else {  ?>
<h2 class="heads">CONSUMABLES,PROMOTIONAL ITEMS and ACCESSORIES Quantity On Hand</h2>
<?php } ?>


<div class="card">

<div class="card-body card-block">
<div class="row">
  <div class="col-lg-12 col-md-12">

 <button type='button' id="clearsearch" class='btn search clearsearch btn-search'> clearsearch </button>
    <button type="button" class="btn download  exportexcel">EXPORT AS EXCEL</button> 
    <button type="button" class="btn download  exportpdf">EXPORT AS PDF</button> 
</div>
<div class="col-lg-12 col-md-12">
<table id="qohgrid"></table>
</div>

</div>
</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {

	var prdgrpopt="{{$prdgrpopt}}";
	var prdcatopt="{{$prdcatopt}}";
	var prdnameopt="{{$prdnameopt}}";
	var prdgrpname="{{$productgroup}}";
	var prdgrpname1="{{$productgroup1}}";
  var url = "getGridqohData?prdgrpname="+prdgrpname+'&prdgrpname1='+prdgrpname1;
  <?php if(isset($productgroup2)) {?>
    var prdgrpname2="{{$productgroup2}}";
    var url = "getGridqohData?prdgrpname="+prdgrpname+'&prdgrpname1='+prdgrpname1+'&prdgrpname2='+prdgrpname2;
  <?php } ?>
    $('#qohgrid').jqGrid({
        url: url,
            datatype: "json",
            mtype: "GET",
        colModel: [
            { name: "product_id",label: "Product Id",width:100,hidden:true},
            { name: "group_name",width:100, label: "Product Group"},
            { name: "category_name", width:100,label: "Product Category"},
			{ name: "product_code", width:170,label: "Product Code"},
            { name: "concatenated_product", width:170,label: "Product Name"},
	        { name: "qoh",label: "Actual Qoh",width:90,search:false},
            { name: "res_qty",label: "Reserve Qoh",width:90,search:false},
            { name: "ava_qty",label: "Available Qoh",width:90,search:false},
            { name: "orderres_qty",label: "Order to be Reserve",width:90,search:false},
            { name: "receiveqty",label: "Work In Progress",width:90,search:false},
            { name: "wipqoh",label: "WIP Qoh",width:90,search:false}

 	    ],


            iconSet: "fontAwesome",
            rowNum: 10,
            rowList: [10,20,50,100,250,500,1000],
            sortorder: "asc",
    	      viewrecords: true,
            gridview: true,
            rownumbers:true,
		        rownumWidth: 50,
            data:data,
            pager: '#qohgrid',
            autowidth: true,
            viewrecords: true,
		        searching: {
                defaultSearch: "cn"
            }


    });
    jQuery("#qohgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
    if(prdgrpname == "RAW MATERIALS" || prdgrpname == "PACKING MATERIALS" ){
        jQuery("#qohgrid").jqGrid('hideCol',["res_qty"]);
        jQuery("#qohgrid").jqGrid('hideCol',["ava_qty"]);
        jQuery("#qohgrid").jqGrid('hideCol',["orderres_qty"]);
        jQuery("#qohgrid").jqGrid('hideCol',["wipqoh"]);

    }

    var colSum = $('#qohgrid').jqGrid('getCol', 'ava', false, 'sum');
    //console.log(colSum);
	jQuery("#gs_qohgrid_product_category_id,#gs_qohgrid_product_group_id").select2();

  
  jQuery("#qohgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		$("#qohgrid").jqGrid("setLabel", "rn", "S.No");


    var url_name = "<?php echo $pageMethod; ?>";
  // alert(url_name);
    var f_name=xl='';
    if(url_name == "rawquantityonhand"){
  f_name="RM_And_PM(QOH).pdf";
  xl="RM_And_PM(QOH).xlsx";
 }else if(url_name =="fgquantityonhand"){
  f_name="SFG_And_FG(QOH).pdf";
  xl="SFG_And_FG(QOH).xlsx";
 }

 $(document).on('click',".exportexcel",function() {

    $("#qohgrid").jqGrid("exportToExcel",{
          includeLabels : true,
              includeGroupHeader : true,
              includeFooter: true,
              fileName : xl
              
        })     
   

 });

 $(document).on('click',".exportpdf",function() {
   	$("#qohgrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : f_name,
  mimetype : "application/pdf"  
}); 
	
});
	
	

		
	
  
  

	$("#clearsearch").click(function() {
	    
        var grid = $("#qohgrid");
        grid.jqGrid('setGridParam',{search:false});

        var postData = grid.jqGrid('getGridParam','postData');
        $.extend(postData,{filters:""});
        grid.trigger("reloadGrid",[{page:1}]);
        $('input[id*="gs_"]').val("");
        $('select[id*="gs_"]').select2('val',['']);
    });


});

</script>
@endsection
