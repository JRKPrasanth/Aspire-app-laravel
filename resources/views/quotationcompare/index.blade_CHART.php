@extends('layouts.header')
@section('content')

  
  
  <div class="panel panel-visible" id="spy1">
 
<div>
<fieldset style="box-shadow:0 0 10px #000;margin-top:2%">


<div class="col-md-12">
<div class="col-md-6">
      
        <div class="form-group  " >
            <label for="Enquiry Number" class=" control-label col-md-4 text-left"><span >*</span>Enquiry Number</label>
                    <div class="col-md-7">
                        <select name='enquiry_hdr_id' rows='5' id='enquiry_hdr_id' class='form-control  enquiry_hdr_id quote_input' required></select>
                    </div>
        </div>
          
        </div>
    	<div class="col-md-6">
       
        <div class="form-group">
        <label for="Product Name" class=" control-label col-md-4 text-left"> Product </label>
        <div class="col-md-7">
            <select name='product_id' rows='5' id='product_id' class='form-control  product_id quote_input' ></select>
        </div>
        </div>
          
        </div>

	
</div>
<div class="col-md-12">
<!--    <div class="col-md-6">
            <fieldset>
		<div class="form-group " >
                    <label for="Supplier Name" class=" control-label col-md-5 text-left"> Supplier</label>
                    <div class="col-md-7">
                        <select name='supplier_id' rows='5' id='supplier_id' class=' form-control supplier_id   quote_input' ></select> 
                    </div>
                </div>
            </fieldset>
        </div>-->
	<!--        <div class="col-md-6">
        <fieldset>
        <div class="form-group  " >
        <label for="Project Name" class=" control-label col-md-5 text-left"> Projects  <span style="color:red"> * </span></label>
        <div class="col-md-7">
            <select name='project_id' rows='5' id='project_id' class=' select2   project_id quote_input' ></select>
        </div>
        </div>
        </fieldset>
        </div>-->

</div>

<div class="col-md-offset-5 col-md-10">
		<div class="col-md-8">
            <fieldset>
                <div class="form-group">
                    <div class="col-md-7">
                        <button  id="view" type="button" class=" btn btn-success btn-info tips quote_compare">Quote Compare</button>
                    </div>
                </div>
            </fieldset>
        </div>
</div>
</fieldset>
<div class="ajaxLoading"></div>
<div  align="right" style="margin-top:2%">

</div>
<div class="dow" style="display:none">
    <a style="float:right" href="" id="download" download="QuoteCompare.jpg">
    <input type="button" class="btn btn-xs btn-info" value="Download JPEG"><img style="display:none" src="" id="output_image"></a>
</div>
<div id="myChart" style="margin-top: 3%;"></div>
</div>
  
  </div>
  
  

  
  
<style type="text/css">
      .demo { position: relative; float:right; }
      .demo i {
        position: absolute; bottom: 10px; right: 24px; top: auto; cursor: pointer;
      }
	  #myChart
	  {
		  height:500px;
		  width:90%;
		  min-height:150px;
		  margin:auto;
		  margin-top:1%;
	  }
	  .quote_input
	  {
		 border: 1px solid rgba(153, 153, 153, 0.75);
		 border-radius:5px;
	  }
	.wrapper1, .wrapper2 {
  width: 100%;
  overflow-x: scroll;
  overflow-y:hidden;
}

.wrapper1 {height: 40px; }
/*.wrapper2 {height: 400px; }*/

.div1 {
  width:3000px;
  height: 20px;
}

.div2 {
  width:3000px;
  /*height: 200px;
  background-color: #88FF88;*/
  overflow: auto;
}
div.dow
	{
	margin-top: 1%;
    margin-right: 5%;	
	}
      </style>
<!--<script src= "https://cdn.zingchart.com/zingchart.min.js"></script>
<script> zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9","ee6b7db5b51705a13dc2339db3edaf6d"];</script>	
<script src='//code.jquery.com/jquery-2.1.4.min.js'></script>-->
                
<script src= "https://cdn.zingchart.com/zingchart.min.js"></script>
<script> zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9","ee6b7db5b51705a13dc2339db3edaf6d"];</script>
<link rel="stylesheet" type="text/css" href="{{ URL::to('/DataTables/media/css/jquery.dataTables.min.css')}}">
<script src="https://files.codepedia.info/files/uploads/iScripts/html2canvas.js"></script>

<script>

$( document ).ready(function() {

$(".supplier_id").jCombo("{{ URL::to('jcomboform?table=m_supplier_t:supplier_id:supplier_name') }}&order_by=supplier_name asc",
{selected_value:""});
$(".project_id").jCombo("{{ URL::to('jcomboform?table=m_projects_t:project_id:project_name') }}&order_by=project_name asc",
{selected_value:""});
$(".enquiry_hdr_id").jCombo("{{ URL::to('jcomboform?table=p_enquiry_hdr_t:enquiry_hdr_id:enquiry_number') }}&order_by=enquiry_number asc",
{selected_value:""});
$(".product_id").jCombo("{{ URL::to('jcomboform?table=m_products_t:product_id:concatenated_product') }}&order_by=concatenated_product asc",
{selected_value:""});


/***Purpose to filter Supplier based on Enquiry *************/
// $(document).on('change','.enquiry_hdr_id',function(){
//     	$('.supplier_id').trigger('click');
// 	var enquiry_id=$('.enquiry_hdr_id option:selected').val();
//	$.get("{{ URL::to('enquirysupplier') }}/"+enquiry_id,function(data)
//	{
//		  $('.supplier_id').find('option').remove().end().append(data).val('');
//	  });
//     
//});

/***Purpose to filter Product based on Enquiry *************/
 $(document).on('change','.enquiry_hdr_id',function(){
     	$('.product_id').trigger('click');
 	var enquiry_id=$('.enquiry_hdr_id option:selected').val();
     
	$.get("{{ URL::to('enquiryproduct') }}/"+enquiry_id,function(data)
	{
		  $('.product_id').find('option').remove().end().append(data).val('');
	  });
     
});

$(document).on('click','.quote_compare',function(){
// var supplierid= $('.supplier_id option:selected').val();
 var prdid= $('.product_id option:selected').val()?$('.product_id option:selected').val():0;
 
 var enqid= $('.enquiry_hdr_id option:selected').val();
    if(enqid!=''){
  $.get("{{ URL::to('quotecomparechart')}}/"+enqid+'/'+prdid,function(data){
     
	 $('.dow').css("display","block");
	  var myConfig = {
    "graphset": [{
           "type": "bar",
	   "plot": {
            "bars-space-left": "25%",
            "bars-space-right": "25%",
           "tooltip": { //HTML Tooltips
            "text": "<table border='1' width='150' height='100' bgcolor='#FFF'><tr><td colspan=2><strong>%data-sup</strong></td></tr><tr><td>%data-all</td></tr></table>",
            "html-mode": true
          },
            "value-box": {
         "text": "%data-disp1"
       }
         },
            "background-color": "#FFF",
            "title": {
                "text": "Quote Comparison Chart ",
                "font-color": "#FFF",
                "backgroundColor": "#1a7e88",
                "font-size": "22px",
                "alpha": 1,
                "adjust-layout":true,
            },
            "plotarea": {
                "margin": "dynamic"
            },
			
            "legend": {
                "layout": "x3",
                "overflow": "page",
                "alpha": 0.05,
                "shadow": false,
                "align":"center",
                "adjust-layout":true,
                "marker": {
                    "type": "circle",
                    "border-color": "none",
                    "size": "10px"
                },
                "border-width": 0,
                "maxItems": 3,
                "toggle-action": "hide",
                "pageOn": {
                    "backgroundColor": "#000",
                    "size": "10px",
                    "alpha": 0.65
                },
                "pageOff": {
                    "backgroundColor": "#7E7E7E",
                    "size": "10px",
                    "alpha": 0.65
                },
                "pageStatus": {
                    "color": "black"
                }
            },
			"scale-y": {
//                
                "line-color": "#7E7E7E",
                "item": {
                    "font-color": "#7e7e7e"
                },
                "label": {
                  "text": "Unit Price (Rs)",
                  "font-family": "arial",
                  "bold": true,
                  "font-size": "14px",
                  "font-color": "#7E7E7E",
                   "text":data.quote_product,
                },
             },
		"scale-x":{
                    "values":data.chart_supplier ,
                    "items-overlap":true,
                    "item":{
                        "font-angle":-45,
                        "offset-x":"7px"
                    }
                },

            "crosshair-x":{
                "line-width":"100%",
                "alpha":0.18,
                "plot-label":{
                  "header-text":"%kv"
                }
            },
			
            "series": [
		
            {"values": data.price , "borderRadiusTopLeft": 7,"alpha": 0.95,"background-color": "#00aff0",
			"text": "Price", "data-all": data.chart_display_all, "data-price": data.price, "data-disp1": data.chart_disp1,
			"data-sup": data.chart_supplier},

            ]
        }
    ]
};

	zingchart.render({
		id : 'myChart',
		data : myConfig,
		height: '100%',
		width: '100%'
		
	});
	  
  zingchart.exec('myChart', 'getimagedata', {
       filetype : 'jpg',
       callback : function(imagedata) {
         
           document.getElementById('output_image').src = imagedata;
		    document.getElementById('download').href = imagedata;
       }
   }); 	
	  
	  
     
  });
  }else{
          notyMsg('error','Please Select Enquiry Number');
      }
     
});

});


</script>
@endsection
