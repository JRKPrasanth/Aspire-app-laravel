@extends('layouts.header')
@section('content')


<h2 class="heads">Quality Check Approval Settings</h2>

<div class="card">

 <div class="card-body card-block">
 <form action="" id="save"  data-parsley-validate>
  <?php $data=\Session::get('data'); if(isset($data[$pageMethod]['save'])) { ?>

	{{ csrf_field()}}
   <input type="hidden" value="" name="savestatus" id="savestatus" />
 			

          <input type="hidden" name="edit_id" value="{{$qcapproval_id}}" id="edit_id" />
                <div class="col-md-12">
  
<div class="row">
  <div class=" col-md-6">

    <div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-5"> <span style="color:red;">*</span> Product Group Name</label>
        <div class="col-md-7">
            <select name='product_group_id' rows='5' class='select2 product_group_id' id="product_group_id" tabindex="3" required>
              {!! $product_group_id !!}
                </select>
        </div>
        <div class="col-md-2">
        </div>
    </div>
     <div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-5">Product Category</label>
        <div class="col-md-7">
            <select name='product_category_id' class='select2 product_category_id' id="product_category_id" tabindex="3" >
               
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="inputIsValid" class="form-control-label col-md-5">Qc Approver</label>
        <div class="col-md-7">
             <select multiple="multiple" name='qc_approver_id[]' rows='5' class='select2 qc_approver_id' id="qc_approver_id" tabindex="3" >
              {!! $qc_approver_id !!}
                </select>
        </div>
    </div>
</div>
<div class=" col-md-6">
<div class="form-group row">
            <label for="active" class="form-control-label col-md-5">Qc Checker</label>
            <div class="col-md-7">
                <select multiple="multiple" name='qc_checker_id[]' rows='5' class='select2 qc_checker_id' id="qc_checker_id" tabindex="3" >
              {!! $qc_checker_id !!}
                </select>
            </div>
        </div>
 <div class="form-group row">
            <label for="active" class="form-control-label col-md-5">Created By</label>
            <div class="col-md-7" style="pointer-events:none;">
                <select name='created_by' rows='5' class='select2 created_by' id="created_by" tabindex="3" >
              {!! $created_by !!}
                </select>
            </div>
        </div>
    </div>
   
</div>
</div>
</div>

<div class="row">     
<div class="col-md-12 text-center">	 
        
            <button type="button"  class="btn save saveform" value='save'>Save</button> &nbsp;&nbsp;&nbsp;
            <!--<button type="button"  class="btn edit editdata" value='edit'>Edit</button> &nbsp;&nbsp;&nbsp;-->
	<?php include('toolbar.php'); ?>
          
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
    <table id="productgroupgrid"></table>
    </div>
    </div>



<div class="row">
<div class="col-md-12">

<table id="productgroupgrid" style="width:auto !important;"></table>
</div>
</div>

</div>
</div>
	<script>



	$(document).ready(function(){

 $(".saveform").click(function()
            {
            	
	var form=$("#save");
    form.parsley();
$('input[name="_token"]').val("{{csrf_token()}}"); 
      var  data = form.serialize();
        form.parsley().validate();
	 	if(form.parsley().isValid()){
        var url="{{ URL::to('qcapprovalsettingssave')}}";
        $.post(url,data,function(data1)
        {
                  var status = data1.status;
                  var msg    = data1.message;

               notyMsg('success',"<i class='' style='font-size:16px'></i>"+msg+" !!!");
            $("#productgroupgrid")[0].triggerToolbar();
            $(".product_group_id").select2('val',['']);
            $(".product_category_id").select2('val',['']);
            $(".qc_approver_id").select2('val',['']);
            $(".qc_checker_id").select2('val',['']);
            $("#edit_id").val('');
            
        });
		}
        return false;

});

showcolumn('productgroupgrid');  

	$(".select2").select2({width:"100%"});




	$('.group_name').on('keyup',function(){
	this.value= this.value.toUpperCase();
   $('.dup_name').hide();
	});

	 $("#productgroupgrid").jqGrid({
		url:"{{URL::to('getqcapprovaldata')}}",
		mtype:'GET',
		datatype:'json',
     colModel: [
     { name: "qcapproval_id", label: "id", width: 100,hidden:true },
	 { name: "group_name", label: "Product Group", width: 250 ,editable:true, editrules:{date:true}},
     { name: "category_name", label: "Product Category", width: 250,editable:true, editrules:{date:true}},
     {name:"qc_checker",label:"Qc Checker",width: 250,editable:true, editrules:{date:true}},
     {name:"qc_approver",label:"Qc Approver",width: 250,editable:true, editrules:{date:true}},
      { name: "username", label: "Created By", editable:true, editrules:{date:true}},
 ],
       rowNum: 10,
		
        rowList: [10,50,100,250,500,1000],
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        rownumbers:true,
		 rownumWidth:50,
        caption: "",
          pager: "#productgroupgrid",
          autowidth: true,
viewrecords: true,
searching: {
    defaultSearch: "cn"
}
});
jQuery("#productgroupgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		$("#productgroupgrid").jqGrid("setLabel", "rn", "S.No");
 $(document).on('click',".exportpdf",function() {
   	$("#productgroupgrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "ProductGroup.pdf",
  mimetype : "application/pdf"  
});
 });
		 $(document).on('click',".exportexcel",function() {
$("#productgroupgrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "ProductGroup.xlsx"
    					
				})	
	
});
            $(".editdata").click(function()
            {
                var gr = jQuery("#productgroupgrid").jqGrid('getGridParam','selrow');
                var id = jQuery("#productgroupgrid").jqGrid ('getCell', gr, 'qcapproval_id');
				var url ="{{URL::to('qcapprovalsettings')}}?id="+id; 
				
				
        
        if(id){
                $.get(url,function(data){
                  console.log(data);
                  var approver = (data.qc_approver_id).split(',');
                    $('.qc_approver_id').select2('val',[approver]);
                    var qc_checker = (data.qc_checker_id).split(',');
				            $('.qc_checker_id').select2('val',[qc_checker]);
                    $(".product_group_id").select2('val',[data.product_group_id]);
                    $("#edit_id").val(data.qcapproval_id);
                    setTimeout(function(){

                    $(".product_category_id").select2('val',[data.product_category_id]);
                  },800);
                 
				 });
				}else{
            notyMsg("info","Please Select Row");
        }
			});

/*purpose:clear search the jqgrid*/
  $(".clearsearch").click(function()
  {
	  
    
    var grid = $("#productgroupgrid");
    grid.jqGrid('setGridParam',{search:false});

    var postData = grid.jqGrid('getGridParam','postData');
    $.extend(postData,{filters:""});
    grid.trigger("reloadGrid",[{page:1}]);
    $('input[id*="gs_"]').val("");
  });
  /*end*/

 $('.reset').click(function(){
	
$(':input','#save')
  .not(':button, :submit, :reset')
  .val('')
  .prop('checked', false);
				$('.product_group_id').change();
				 $('.active').select2().val('Yes').change();
                var kk= '<?php echo \Session::get("id")?>';
				$('.created_by').select2().val(kk).change();
	              
            });
$('.product_group_id').change(function(){
  var prdgroup=$('.product_group_id').select2('val');
    var condition = ' product_group_id='+prdgroup;   
    if(prdgroup != ''){
      $(".product_category_id").jCombo("{{ URL::to('jcomboformallcheck?table=m_product_category_t:product_category_id:category_name') }}&order_by=category_name asc"+'&parent='+condition,
            {selected_value:""});
    }
  
});

   $(".delete").click(function(){
       var gr = jQuery("#productgroupgrid").jqGrid('getGridParam','selrow');
       var id = jQuery("#productgroupgrid").jqGrid ('getCell', gr, 'product_group_id');
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
                      var url ="{{ url('productgroupdelete') }}/" +id; 
                      $.get(url,function(data)
                      {
                         var data = $.trim(data);
                         var red_url ="{{ url('productgroup') }}";
                         
                        if(data =='0')
                          {
                            notyMsg('success','Deleted Successfully!!!');
                            $('.clearsearch').trigger('click');
                            setTimeout(function(){
                            $("#productgroupgrid")[0].triggerToolbar();
                            }, 1500);
							             $('.group_name,.description').val('');
                          }
                          if(data =='1')
					                  {
						                  notyMsg('info',"You Can't delete , Quality Check Approval Used in SomeWhere!!!");
                              $('.clearsearch').trigger('click');
                              setTimeout(function(){
                              $("#productgroupgrid")[0].triggerToolbar();
                              }, 1500);
					                  }
                      });
                    }
                    else
                    {
                      $('.apply').css('display','none');
                      swal("Cancelled");
                      $('.clearsearch').trigger('click');
                    }
               })
               $('.apply').css('display','none');
        }
        else
      	{
	        notyMsg("info","Please Select Row");
      	}
   });

	});
	</script>
@include('layouts.php_js_validation')
@endsection
