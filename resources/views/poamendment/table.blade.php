@extends('layouts.header')
@section('content')
<style type="text/css">
  .datepicker{
    
    z-index:1052 !important;}
</style>

<?php //dd($pageMethod); ?>
<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                       
                                        Purchase Order
                                    </a>
                                </h4>
                            </div>

</div>



  
  <div class="panel panel-visible" id="spy1">

<div class="panel-title">
  <div class="row">
    <div class="col-md-12" >
            <?php include('toolbar.php'); ?>
	
        <!--<button type="button" class="btn add alternate"> Change Promised Date</button>-->
  <!--<a id="print"><button type="button" class="btn print vie">Print</button></a>-->
		 <!--<a id="email"><button type="button" class="btn email vie">E-mail</button></a>-->
                
	
  
</div>
</div>
<div class="row">
    <div class="col-md-12">
         <hr class="xlg">
    </div>
</div>
</div>
  <div class="row">
    <div class="col-md-12" >
   <table id="pogrid"></table>
  </div>
  </div>
  <div class="row">
    <div class="col-md-12">
         <hr class="xlg">
    </div>
</div>
  </div>
  
  
<form method="post" action="" id="saveprodateform" class="saveprodateform" data-parsley-validate>
    <!-- Trigger the modal with a button -->
  <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Promised Alternate Date</h4>
        </div>
        <div class="modal-body">
          <table width=80%>
		  <tr>
		  <th>Po Number</th>
		  <th>Po Date</th>
		  </tr>
		  <tbody class="poaltdatetbl">
		  </tbody>
		  </table>
		  <br>
		   <table class="date"  width=80%>
		  <tr>
		  <th>Product Name</th>
		  <th>Promised Date</th>
		  <th class="date1">Promised Alternate Date</th>
		  </tr>
		  <tbody class="poaltdatetblbody">
		  </tbody>
		  </table>
        </div>
        <div class="modal-footer">
		  <button type="submit" class="btn btn-default prodatesave" data-dismiss="modal">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
 </form>

 
  <div id="contactModal" class="modal fade" role="dialog">
<div class="modal-dialog" style="width:845px;margin: auto;">
<!-- Modal content-->
<div class="modal-content" style="height: 550px;overflow-y: auto;">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">Contact</h4>
</div>

<form method="POST" action="" accept-charset="UTF-8" class="form-horizontal" id="enquirymail" data-parsley-validate="" enctype="multipart/form-data">

<div class="modal-body ">

<div class="table-responsive">

<table class="table">
<tr><td>
<label>Supplier Name</label>
<input type="text" name="supplier_name" class="form-control supplier_name" readonly />
</td></tr>
</table>
</div><br/>

<div class="table-responsive">
<table class="table">
<thead>
<th></th>
<th>Contact Person</th>
<th>Contact NUmber</th>
<th>Contact Value</th>

<th></th>
</thead>
<tbody class="mcontent2"></tbody>
</table>
</div>
<div class="col-md-12">
    <div class="form-group  " > 
          <label for="cc" class=" control-label col-md-1 text-left"> 
          CC  
          </label>
          <div class="col-md-6">
            
            <input type="text" name="cc[]" class="form-control cc"  />
           </div> 
           <div class="col-md-2">
            
           </div>
          </div> 
    
</div> 
<div>

<div class="row">
  <div class="col-lg-12 col-md-12">
  <label>Message:</label> <br/>

<textarea name="msg" class="msg tinymce" style="width:100%;">
    
    
Thanks and Regards,
Purchase Department, 
JRKS,
Kundrathur.
</textarea>


<input type="hidden" name="hdr_id" class="msg hdr_id" rows="4" >

  </div>

  <div class="col-md-12">
<div class="col-md-12 attach_div">Add Attachments<input type="file" name="email_attachment[]"  class="email_attachment" multiple="multiple"></div>
<div class="col-md-12 attach_div">Attach pdf <input type="checkbox" name="attchment"  class='attchment' value='0' ></div>
<div class='.preview'>

</div>
</div>

<div class="col-lg-12 col-md-12">
  <iframe id="iframepdf"  width=100% height="800" style="display:none"></iframe>
  <button  type='button' class="btn btn-success sendmail" name="sendmail" id="sentmail_id">Send</button> 

</div>

</div>
</div>
</div>

</div> 




</form>
</div>
</div>
  </div>
    

<script type="text/javascript">

$( document ).ready(function() {
        initDateEdit = function (elem) {
				$(elem).datepicker({
					dateFormat: "yy-mm-dd",
//dateFormat: "yy-mm-dd",
					autoSize: true,
					changeYear: true,
					changeMonth: true,
					showButtonPanel: true,
					showWeek: true
				});
			},
                        initDateSearch = function (elem) {
				setTimeout(function () {
					initDateEdit(elem);
				}, 100);
			};

  var status ="{{$status}}";   
$("#pogrid").jqGrid({  
      url: "getPurchaseorderData?status="+status,
      datatype: "json",
      mtype: "GET",
	 colNames: ["","","PO Number","PO Date","PO Type","Supplier Name", "Grand Total","PO Status","Reference No","Quality Status","Remarks"],
        colModel: [
            { name: "po_hdr_id",align: "center",hidden:true},
			{ name: "sub_id",align: "center",hidden:true},
            { name: "po_number", align: "center" },
            { name: "po_date", align: "center" },
            { name: "po_type", align: "center" },
            { name: "supplier_id",  align: "center",label: "Supplier Name"},
            { name: "po_grand_total", align: "center" },
            { name: "po_status", align: "center" },
            { name: "reference_number", align: "center" },
            { name: "quality_status", align: "center" },
            { name: "remarks",align: "center" }, 
             ],
	iconSet: "fontAwesome",
	rownumbers: true,
	sortorder: "desc",
        threeStateSort: true,
	sortIconsBeforeText: true,
	headertitles: true,
	pager: "pogrid",
	rowNum: 10,
	viewrecords: true,
         rowList: [10, 20, 50, 100,500,1000],
         delOptions: { url: '/PurchaseorderController/delete' },
	searching: {
	defaultSearch: "cn"
	}
});
jQuery("#pogrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#gs_pogrid_supplier_id").select2();
	
	$("#pogrid").jqGrid("setLabel", "rn", "S.No");
 $(document).on('click',".export",function() {
   	$("#pogrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "PO Amendment.pdf",
  mimetype : "application/pdf"  
});
			
$("#pogrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "PO Amendment.xlsx"
    					
				})		 
	});

/*Karthigaa Purpose for Show Coloumn*/
showcolumn('pogrid');
$("#pogrid").jqGrid('hideCol',["remarks"]);
$("#showcolumn").click(function() {
  var btn = $(".showcolumn").val();
  if(btn =="1" ){
    $("#pogrid").jqGrid('showCol',["remarks"]);
    $(".showcolumn").val('2');
  }else{
    $("#pogrid").jqGrid('hideCol',["remarks"]);
    $(".showcolumn").val('1');
  }
});


/*Karthigaa Purpose For CREATE Function*/
$(document).on('click','.create',function(){
var potype = $(this).val();
var url="{{ url('purchaseordercreate') }}/0/"+potype+'/2';
var red_url="{{ url('purchaseorder') }}";
window.location.replace(url);
});


/*Karthigaa Purpose For Edit Function*/
$("#edit").click(function(){
        var index = $("#pogrid").jqGrid('getGridParam','selrow');
	
	var pohdrid = $("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');
        var potype = $("#pogrid").jqGrid ('getCell', index, 'po_type');
        var postatus = $("#pogrid").jqGrid ('getCell', index, 'po_status');
       if( index )
       {
         if(postatus!="APPROVED" && postatus!="INITIATED" && postatus!="CANCELLED")
         {
           window.location.replace('purchaseordercreate/' +pohdrid+'/'+potype+'/2');
         }
         else
         {
           notyMsg("info","Unable to Edit PO");
         }

       }
      else
      {
        notyMsg("info","Please Select Row");
      }

});
/*Karthigaa Purpose For Edit Function*/
$(".amendment").click(function(){
        var index = $("#pogrid").jqGrid('getGridParam','selrow');
	
	var pohdrid = $("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');
        var potype = $("#pogrid").jqGrid ('getCell', index, 'po_type');
        var postatus = $("#pogrid").jqGrid ('getCell', index, 'po_status');
       if( index )
       {
//         if(pohdrid=)
//         {
           window.location.replace('poamendmentcreate/' +pohdrid+'/'+potype+'/5');
//         }
//         else
//         {
//           notyMsg("info","You Cant Edit This PO Bcz Item Received...");
//         }

       }
      else
      {
        notyMsg("info","Please Select Row");
      }

});

/*Maruthu Purpose For Print Function*/
$("#print").click(function(){
        var index = $("#pogrid").jqGrid('getGridParam','selrow');
	     var pohdrid = $("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');
        var potype = $("#pogrid").jqGrid ('getCell', index, 'po_type');
        var postatus = $("#pogrid").jqGrid ('getCell', index, 'po_status');
       if( index )
       {
		   /*
         if(postatus!="APPROVED" && postatus!="INITIATED" && postatus!="CANCELLED")
         {
           window.location.replace('purchaseordercreate/' +pohdrid+'/'+potype+'/2');
         }
         else
         {
           notyMsg("info","Unable to Edit PO");
         }
		 */
		   window.open('poprint/' +pohdrid,'_blank');

       }
      else
      {
        notyMsg("info","Please Select Row");
      }

});


/*Karthigaa Purpose For Copy PO*/
$('.copypo').click(function(){
  var index = $("#pogrid").jqGrid('getGridParam','selrow');
	var quoteid = $("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');
	if( index ){
		window.location.replace('purchasecopypocreate/' +quoteid+'/0?status=COPYPO');
	}
	else
	{
		 notyMsg("info","Please Select Row");
	}
});

$("#create_approve").click(function(){
        var index = $("#pogrid").jqGrid('getGridParam','selrow');
	var pohdrid = $("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');
        var potype = $("#pogrid").jqGrid ('getCell', index, 'po_type');
       if( index ){
		window.location.replace('purchaseordercreate/' +pohdrid+'/'+potype+'/1');
	}
	else
	{
		notyMsg("info","Please Select Row");
	}

});

  /* raja code for po cancellation*/
    $(document).on('click',"#cancelpo",function(){
       var index = $("#pogrid").jqGrid('getGridParam','selrow');
       var pohdrid = $("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');
       var potype = $("#pogrid").jqGrid ('getCell', index, 'po_type');
        if(index)
        {
          window.location.replace('purchaseordercreate/'+pohdrid+'/'+potype+'/3');
        }
       else
       {
      notyMsg("info","Please Select Row");
       }
    });
  /* */

    /*Karthigaa Purpose For View Function*/
       $("#view").click(function(){
	var index = $("#pogrid").jqGrid('getGridParam','selrow');
	var pohdrid = $("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');
	if( index )
	{
		window.location.replace('purchaseorderview/' +pohdrid);
	}
	else
	{
			notyMsg("info","Please Select Row");
	}
   });
    /*Karthigaa Purpose For Delete Function*/
       $("#delete").click(function(){
		var gr = jQuery("#pogrid").jqGrid('getGridParam','selrow');
		var pohdrid = jQuery("#pogrid").jqGrid ('getCell', gr, 'po_hdr_id');
		if( gr ){
		window.location.replace('purchaseorderdelete/' +pohdrid);
    notyMsg("success","Deleted Successfully");
		}
		else{
		notyMsg("info","Please Select Row");
		}
	});
/***** Delete Row ********/
	
	/*raja code for setting alternate date*/
     $('.alternate').on('click',function()
	 {    

         

	      var index = $("#pogrid").jqGrid('getGridParam','selrow');		
          var pohdrid = jQuery("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');		
            if(index)
			{
				 $('#myModal2').modal('show');
  //          $('#myModal2').click(function () {
           
  //            //if ($('input.datepicker').length > 0){
  //           $('input.datepicker').datepicker({dateFormat: 'dd-mm-yy'});
  //           //alert($('input.datepicker').length > 0);
  //         //}
    
  //   return false;
  // });

       
        


				 var id = $("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');
				 var po_number = $("#pogrid").jqGrid ('getCell', index, 'po_number');
				 var po_date = jQuery("#pogrid").jqGrid ('getCell', index, 'po_date');
				
               var datefomdatas="<tr><td>"+po_number+"</td><td>"+po_date+"</td></tr>";
               $('.poaltdatetbl').html(datefomdatas);
			   
			
			 
			 var url="{{URL::to('getpoaltdatedatas')}}/"+id; 
			 $.get(url,function(data){ 
				 var table='';
				$.each(data, function( key, value ) {
					table+="<tr><td>"+value['concatenated_product']+"</td><td>"+value['promised_date']+"</td><td><div class='input-group date form_date col-md-12' data-date='' data-date-format='dd MM yyyy' data-link-field='dtp_input2' data-link-format='yyyy-mm-dd'><input id='dtpicker1' type='text' name='bulk_promised_alternate_date[]' class='form-control datepicker  bulk_promised_alternate_date' required><span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span></div> </td><input type='hidden' name='bulk_po_line_id[]' value="+value['po_line_id']+"><input type='hidden' name='bulk_po_hdr_id[]' value="+value['po_hdr_id']+"></tr>"; 
				  
				});
				 	$('.poaltdatetblbody').html(table);
			  });
			 

			}
            else			
		    {
			  notyMsg("info","Please Select Row");
			}		
		 
	 });

//date picker for popup bootsrap

$('#myModal2').on('mousemove',function () {

//    $('.datepicker').datepicker("destroy");
    var i = 0;
	var data ="{{\Session::get('j_date_format')}}";
	 var dateToday = new Date();
      $('.datepicker').each(function () {
		   $(this).attr("id",'dtpicker'+ i).datepicker({
              dateFormat: data,
              minDate: dateToday,
              maxDate: null,

            });
  
    i++;
});
   });


	 
	 $(document).on('click','.prodatesave',function(){
		 var url ="{{ URL::to('alternatedatesave') }}";
		 validationrule('saveprodateform');
		 
		var form = $('#saveprodateform');
		form.parsley().validate();
		 change_date();
		 var formdata	= $('#saveprodateform').serialize();
		 console.log(formdata);
		 $.post(url,formdata,function(data){
			    var status  = data.status;
				var msg     = '<span style="color:#090065">'+data.status+'</span>  '+data.message;
				notyMsg(status,msg);
		 })
	 });
	 
	 
     $('#email').on('click',function()
					{			
		var index = $("#pogrid").jqGrid('getGridParam','selrow');			
			 var postatus = $("#pogrid").jqGrid ('getCell', index, 'po_status');		
		if(postatus!="INITIATED" && postatus!="CANCELLED" && postatus!="DRAFT")
         {
var index = $("#pogrid").jqGrid('getGridParam','selrow');
    var id = $("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');
    var id = $("#pogrid").jqGrid ('getCell', index, 'supplier_id');
    var suid = $("#pogrid").jqGrid ('getCell', index, 'sub_id');
 
$('.mcontent2').html('');

$('.supplier_name').val(id);

  // var url_print='{{URL::to("suppliermaildetails")}}/'+suid+'?mail="mail"';
   var url_print='{{URL::to("suppliermaildetails")}}/'+suid;
			
  $.get(url_print,function(data)  
{   
 //alert(data);
if(data =="")
{
notyMsg('info',"No Email Contact For Current Supplier...Please Add Email First...");            
}
else
{   
	
$.each(data, function (key) 
{
	//console.log(data[key][0]);
	//var data =jQuery.parseJSON(data);
  //console.log(data);
$('.mcontent2').append('<tr class="cont_row">\n\
<td><input type="radio" name="check_mail[]" class="check_mail mail_name"  value="' + data[key][0] + '" data-id="' + data[key].supplier_site_id + '" data-value="' + data[key].supplier_site_id + '"></td>\n\
<td><input type="text" name="contact_person[]" class="form-control" value="' + data[key][1] + '" readonly/></td>\n\
<td><input type="text" name="contact_number[]" class="form-control" value="' + data[key][2]+ '" readonly/></td>\n\
<td><input type="text" name="email_id[]" class="form-control" value="' + data[key][3]+ '" readonly/></td>\n\
</tr>');

$(".check_mail").click(function () 
{
    
var check=$(this).is(":checked");           
var index=$(this).closest('tr').index();

var att_check= $(".attchment").parent('[class*="icheckbox"]').hasClass("checked");

if(att_check) 
{

}

if(check==true)
{

var id = $(this).data('value');

$('.cont_row input:not(.check_mail)').attr('disabled','disabled');            
$('.cont_row:eq('+index+') input').removeAttr('disabled');

}
else
{
notyMsg('info',"Please Check Any Email Contact First...");  
$('.cont_row input').removeAttr('disabled');  

}

});

});         

}   

});
  $('#contactModal').modal('show');
		 }
			else
         {
           notyMsg("info","Please Select Approved Only");
         } 
			 

          });
	
	
	    
$('#contactModal').on('shown.bs.modal', function() 
{
  
var index = $("#pogrid").jqGrid('getGridParam','selrow');
    var id = $("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');


$('.sendmail').click(function()
{
  var id = $("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');
    var suid = $("#pogrid").jqGrid ('getCell', index, 'sup_id');
var check=$('.mail_name').is(":checked"); 
  var mail=$('.cc').val();
//alert(check);
 // alert(mail);
   var url = "{{ URL::to('poprint') }}/"+id+'?mail='+mail+'&email='+check;
       $.get(url , function(data)
    
  {
    notyMsg('info',"Mail Send Successfully");   
     window.location.reload(); 
     });
}); 

  
  $(document).on('click','.attchment' ,function(){
//alert();
var index = $("#pogrid").jqGrid('getGridParam','selrow');
    var id = $("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');
    var po_number = $("#pogrid").jqGrid ('getCell', index, 'po_number');
    
     var url = "{{ URL::to('poprint') }}/"+id+'?mails=mails';
       $.get(url , function(data)
    
  {

$('#iframepdf').attr('src',"Uploads/purchaseorder/PO_"+po_number+".pdf");
$('#iframepdf').show();
});

});

  

$(".attchment").on("ifUnchecked", function(){


$('#iframepdf').attr('src','');
$('#iframepdf').hide();
$('.highlight').removeClass('highlight');

});

});
  
/***** Karthigaa Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#pogrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
		$('input[id*="gs_"]').val("");
        $('select[id*="gs_"]').select2('val',['']);
	});
/*End*/
});



    </script>
@include('layouts.php_js_validation')
@endsection
