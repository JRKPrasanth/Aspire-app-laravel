@extends('layouts.header')
@section('content')



<h2 class="heads">UOM CODE CONVERSION</h2>



<div class="card">


  
 <div class="card-body card-block">

<form method="post" action="" id="uomcodeconsave" data-parsley-validate>
    <input type="hidden" value="" name="savestatus" id="savestatus" />

    <input type="hidden" name="edit_id" value="{{$row->uom_conversion_id}}" id="edit_id" /> {{ csrf_field()}}


<div class="row">
    
    	<div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;">*</span>Product</label>
            <div class="col-md-8 sel2">
                <select class="form-control product_id select2" name="product_id" id="product_id" value="" tabindex="1" required="" autofocus="">
                    {!! $product_id !!}
                </select>
                
            </div>
            <span class="btn btn-danger dup_name" style="display:none;"></span>
        </div>
    </div>
    
   
    <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="font-style:20px;color:red;">*</span>Primary Uom</label>
            <div class="col-md-8">
                <select class="form-control primary_uom select2" name="primary_uom" id="primary_uom" value="" tabindex="2" required="" autofocus="">
                    {!! $primary_uom !!}
                </select>
               
            </div>
        </div>
       </div>
    <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="font-style:20px;color:red;">*</span>Uom Value</label>
            <div class="col-md-8">
                <input style="width:90%" type="text" class="form-control uom_value" name="uom_value" tabindex="3" value="{{$row->uom_value}}" required="" autofocus="">
            </div>
			 
        </div>
    </div>
	<div class="col-md-4">
		
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4" >Active</label>
                    <div class="col-md-8">
                        <select name="active" class="select2 active" tabindex="4">
                            <option value="Yes" <?php if($row->active=="Yes"){ echo "selected"; }?>>Yes</option>
                            <option value="No" <?php if($row->active=="No"){ echo "selected"; }?>>No</option>
                        </select>
                    </div>
                </div>
    </div>
    
  

    <div class="col-md-4">
		
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="font-style:20px;color:red;">*</span>Trx Uom</label>
            <div class="col-md-8">
                <select class="form-control trx_uom select2" name="trx_uom" value="" id="trx_uom" tabindex="5" required="" autofocus="">
                    {!! $trx_uom !!}
                </select>
            </div>
        </div>
	</div>
	  <div class="col-md-4">
		
     <div class="form-group row">
            <label for="active" class="form-control-label col-md-4">Created By</label>
            <div class="col-md-8" style="pointer-events:none;">
                <select name='created_by' rows='5' class='select2 created_by' id="created_by"  >
              {!! $created_by !!}
                </select>
            </div>
        </div>
    </div>
</div>
     <div class="row text-center">
                 
            	 <button type="submit" id="save" class="btn save saveform" value="SAVE">Save</button> 
         <?php include('toolbar.php'); ?>
            	
            </div>
   
</form>



	
           
                    
            
    <div class="row">
    <div class="col-md-12">
    <table id="uomcodecongrid"></table>
    </div>
    </div>

  </div>
</div>


<!--<script src="<?php //echo asset('js/plugins/parsley/dist/parsley.js')?>"></script>-->

	<script>
		
	$(document).ready(function(){
		
		var form=$("#uomcodeconsave");
		form.parsley();
	
			form.submit(function(){ 
            duplicate_validate();

			 	$('input[name="_token"]').val("{{csrf_token()}}"); 
			 var formdata	= form.serialize(); ;
		     var url="{{ URL::to('uomconversionsave') }}"; 
			if(dup_chk == true)
                 {	
		$.post(url, formdata, function(data1)
		{	
			 var status   = data1.status;
			 var msg      = data1.message;
			     notyMsg(status,msg);	
			
			$("#uomcodecongrid").trigger('reloadGrid' );
			 $(".reset").trigger('click');
			
				});
				 }
				return false;			
					});
         var dup_chk = true;
         function duplicate_validate()
                        {
            var product_id = $(".product_id").val();
			
            var edit_id = $("#edit_id").val();
            var primuom =$(".primary_uom").val(); 
            var trxmuom =$(".trx_uom").val();

            $.ajax({
                cache: false,
                url: "{{URL::to('uomconversioncheckname')}}", //this is your uri
                type: 'GET',
                dataType: 'json',
                async : false,
                data: {product_id : product_id,edit_id : edit_id,primary_uom : primuom,trx_uom : trxmuom},
                success: function(response)
                {
                    console.log(response);
                    if(response == 1)
                    {
                        $('.dup_name').html('Product Already Exists');
                        $('.dup_name').show();
                        $('.product_id').select2('val',['']);
                        $('.primary_uom').select2('val',['']);
                        $('.trx_uom').val('').change();
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
		
		
		$(document).on('change','.product_id',function(){
   
          var product_id = $(this).val();
		
				if(product_id!=' '){		
var url = "{{ URL::to('productprimaryuom') }}/"+product_id;
               
				   $.get(url , function(data)
				   {
					 if(data[0]!=''){
					 $('.primary_uom').select2('val',[data[0]]);
					 }if(data[1]!=''){
					 $('.trx_uom').select2('val',[data[1]]);
					 }
				   });
				}		  
											 
        });
		/* raja code for primary and trx code chk*/
		$(document).on('change','.primary_uom,.trx_uom',function(){
			var primuom =$(".primary_uom").val(); 
			var trxmuom =$(".trx_uom").val(); 
			if(primuom!='' && trxmuom!=''){
						if(primuom==trxmuom) 
			   {   
			 $(this).val('').select2();
			  notyMsg("warning","Primary Uom and Trx Uom must be diffrent ");
			}		
			}
	    });
	
			$(document).on('keypress','.uom_value', function(ev){
	   			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
			});
		
		$(".select2").select2({width:"90%"});
		
	showcolumn('uomcodecongrid');	
	
/*purpose:clear search the jqgrid*/
  $(".clearsearch").click(function()
  {
    
    var grid = $("#uomcodecongrid");
    grid.jqGrid('setGridParam',{search:false});

    var postData = grid.jqGrid('getGridParam','postData');
    $.extend(postData,{filters:""});
    grid.trigger("reloadGrid",[{page:1}]);
    $('input[id*="gs_"]').val("");
    $('select[id*="gs_"]').select2('val',['']);
  });
  /*end*/
	  var pdt="{!!$pdt!!}";
        $("#uomcodecongrid").jqGrid({
			 url: "{{URL::to('getGridUomconData')}}",
		datatype: "json",
		mtype: "GET",				
     colModel:[ {name: "uom_conversion_id", label: "Id", width: 100,hidden:true},
         {name: "product", label: "Id", width: 100,hidden:true},
          {name: "concatenated_product",label:"Product Name",width:200},
				   {name: "uom_code",label:"Primary Uom",width:100},
				   {name: "trx_code",label:"Trx Uom",width:100,search:false},
				   {name: "uom_value",label:"Uom Value",width:100},
				    {name: "active",label:"Active",width:100},
				    { name: "username", label: "Created By", editable:true, editrules:{date:true}},
 			  ],
				 iconSet: "fontAwesome",
            rowNum: 10,
           rowList: [10,50,100,250,500,1000],
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        rownumbers:true,
       
          pager: "#uomcodecongrid",
          
					viewrecords: true,
				});


 jQuery("#uomcodecongrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		$("#uomcodecongrid").jqGrid("setLabel", "rn", "S.No");
 $(document).on('click',".exportpdf",function() {
   	$("#uomcodecongrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "uomconversion.pdf",
  mimetype : "application/pdf"  
});
 });
		 $(document).on('click',".exportexcel",function() {
$("#uomcodecongrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "uomconversion.xlsx"
    					
				})		 
	 
	 
	
});
	
	

	
		
	
		   $("#editdata").click(function()
            {
            var gr = jQuery("#uomcodecongrid").jqGrid('getGridParam','selrow');
            var cellValue = jQuery("#uomcodecongrid").jqGrid ('getCell', gr, 'uom_conversion_id');
            var primary_uom = jQuery("#uomcodecongrid").jqGrid ('getCell', gr, 'primary_uom');
            var product_id = jQuery("#uomcodecongrid").jqGrid ('getCell', gr, 'product');
            
			    var trx_uom = jQuery("#uomcodecongrid").jqGrid ('getCell', gr, 'trx_uom');
			     var uom_value = jQuery("#uomcodecongrid").jqGrid ('getCell', gr, 'uom_value');
			    var primary_uom=$('#primary_uom option').filter(function () { return $(this).html() == primary_uom; }).val();
			     var trx_uom=$('#trx_uom option').filter(function () { return $(this).html() == trx_uom; }).val();
			   var url="{{URL::to('uomconversionedit')}}/";

            if( gr )
            {	
              
                    $('.primary_uom').val(primary_uom).change();
                    $('.trx_uom').val(trx_uom).change();
                    $('.uom_value').val(uom_value);
                    $('#edit_id').val(cellValue);
                    $('.product_id').select2('val',[product_id]);
                    
              
            }
            else
            {
            notyMsg("info","Please Select Row");
            }
            });
		
		  
           
		 $('.reset').click(function(){
               $(':input','#uomcodeconsave')
                .not(':button, :submit, :reset')
                .val('')
                .prop('checked', false);
			    $('.product_id').select2('val',['']);
			    $('.primary_uom').select2('val',['']);
			    $('.trx_uom').val('').change();
			     $('.active').val('Yes').change();
			    $('.created_by').select2('val',["{{\Session::get('id')}}"]);
               $('.uom_value').val('');
            });
           
		
		        $(".delete").click(function(){
	           var gr = jQuery("#uomcodecongrid").jqGrid('getGridParam','selrow');
               var id = jQuery("#uomcodecongrid").jqGrid ('getCell', gr, 'uom_conversion_id');
		         if(gr )
                  {
						swal({
					title: "Are you sure?",
					text: "You want to delete!",
					type: "warning",
					showCancelButton: !0,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Yes",
					cancelButtonText: "No",
					closeOnCancel: !1
					  },function(e) 
                         { 
						   if(e == true)
			              { 
						     var url ="{{ url('uomconversiondelete') }}/" +id;
							  $.get(url,function(data)
							  {
								 var data = $.trim(data);
								 var red_url ="{{ url('uomconversion') }}";
								 var data = $.trim(data);
								if(data =='0')
								  {				
									notyMsg('success','Deleted Successfully!!!');
									setTimeout(function(){ 
									$("#uomcodecongrid").trigger('reloadGrid' );
									}, 1500);
                                      $('#primary_uom').val('').select2();
                                      $('.trx_uom').val('').select2();
                                      $('.uom_value').val('');
                                        $('.clearsearch').trigger('click');
								  }
								  if(data =='1')
								  {
									  notyMsg('error',"You Cant't delete , Enquiry Used in SomeWhere!!!");
									    $('.clearsearch').trigger('click');
								  }
							  });
						  }
						  else
						  {
						     $('.apply').css('display','none');
                             swal("Cancelled");
                               $('.clearsearch').trigger('click');
						  }
			         }); 
					  $('.apply').css('display','none');
				  }
				 else
      		   	  {
	                 notyMsg("info","Please Select Row");
      	          }
            }); 
	    	
                 
		
           
	});
	</script>
@include('layouts.php_js_validation');
@endsection