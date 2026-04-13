@extends('layouts.header') @section('content')


<?php include('tools_menu.php'); ?>
<h2 class="heads"> Sales Person</h2>

        <div class="card">
            
            <div class="card-body card-block">

                <form action="" id="save">
                    {{ csrf_field() }}
                    <input type="hidden" name="edit_id" value="" id="edit_id" />
                    <div class="row">
                        <div class="col-md-6">
                            <div class=" form-group row">
                                <label for="fob_point_name" class="form-control-label col-md-5">SalesPerson Number</label>
                                <div class="col-md-6">
                                    
                                    <input type="text" id="salesperson_number" name="salesperson_number" class="form-control salesperson_number" value=""  style="width: 100%;" readonly >
                                    <span class="btn btn-danger dup_name" style="display:none;"></span>
                              </div>
                              </div>
							
                            <div class=" form-group row">
                                <label for="fob_point_name" class="form-control-label col-md-5"><span style="color:red;">*</span>SalesPerson Name</label>
                                <div class="col-md-6">
                                    <input class="form-control salesperson_site_count" id="salesperson_site_count" name="salesperson_site_count" size="16" type="hidden" value="" readonly>
                                    <input class="form-control salesperson_id" id="salesperson_id" name="salesperson_id" size="16" type="hidden" value="" readonly>
                                    <input type="text" id="salesperson_name" name="salesperson_name" class="form-control salesperson_name" value="" required style="width: 100%;">
                                    <span class="btn btn-danger dup_name" style="display:none;"></span>
                              </div>
                              </div>
                                <div class=" form-group row">
                                <label for="" class="form-control-label col-md-5"><span style="color:red;">*</span>Employee Name</label>
                                <div class="col-md-6">
                                    
                                    <select type="text" id="employee_id" name="employee_id" class="select2 employee_id" required style="width: 100%;">{!! $employee_id !!}</select>
                              <span class="employee_span" style="color:red"> Already Employee IS Assigned </span>
                              </div>
	     <div class="col-md-1 showinline">
        	            		<span class="showspan "> <i class="fa fa-refresh jcr_employee_id"></i></span>
                          </div> 
                              </div>
                           

                       
                    </div>

                    <div class="col-md-6">
                       
                         
                        <div class="form-group row">
                            <label for="active" class="form-control-label col-md-5 ">Active</label>
                            <div class="col-md-6">

                                <select name='active' rows='5' class='select2 active' id="active" data-show-subtext="true" data-live-search="true">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
			<label for="active" class="form-control-label col-md-5">Created By</label>
			<div class="col-md-6" style="pointer-events:none;">
				<select name='created_by' rows='5' class='select2 created_by' id="created_by" >
			{!! $created_by !!}
				</select>
			</div>
		</div>
     

                    </div>

            </div>
            <div class="row text-center">
                <button type="submit" class="btn  save" id="save">save</button> 
                
               <?php include('toolbar.php'); ?>
             </div>

            </form>

            <div class="col-md-12">
                <table id="grid1"></table>
            </div>


    </div>

        </div>

<script src="<?php echo asset('js/plugins/parsley/dist/parsley.js')?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
<script>
/* -- Start Duplicate Function --*/
var dup_chk = true;
function duplicate_validate()
{
var salesperson_name = $(".salesperson_name").val();
var edit_id = $("#edit_id").val();
$.ajax({
cache: false,
url: 'salesperson/checkname', //this is your uri
type: 'GET',
dataType: 'json',
async : false,
data: {salesperson_name : salesperson_name,edit_id : edit_id},
success: function(response)
{
//console.log(response);
if(response == 1)
{
$('.dup_name').html('salesperson name:'+salesperson_name+' Already Exists');
$('.dup_name').show();
$(".salesperson_name").val('');
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
//console.log(xhr, resp, text);
}
});
}
/* -- End Duplicate Function --*/


/* -- Start Save Function --*/
$("#save").click(function()
{
 var form=$("#save");
 form.parsley();
 form.submit(function(){
 $('input[name=_token]').val("{{csrf_token()}}");
var data;
data = form.serialize();
var url="{{ URL::to('salesperson/save') }}";
if(form.parsley().isValid() && dup_name)
  notyMsg("Already Used");
                 {
      if(dup_chk == true)
                 {  
//  console.log(data);
  $.post(url, data, function(data1)
  {
 var status = data1.status;
 var msg    = data1.message;
                       // var id     = data.id;

	 notyMsg('success',"<i class='' style='font-size:16px'></i>"+msg+" !!!");

  $("#grid1")[0].triggerToolbar();
  $(".reset").trigger('click');
  $('#active').select2('val',['Yes']);
  });
}
}
  return false;
    });
});
    /* -- End Save Function --*/


$(document).ready(function()
{
$('.employee_span').hide();    
$(".jcr_employee_id").click(function()
{
$(".employee_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:employee_number|first_name') }}",
		{selected_value:""});
});
var date_format="{{\Session::get('p_date_format')}}";
var opt="{{$datas}}";
/* -- Start Jqgrid Data Function --*/
$("#grid1").jqGrid({
url:"{{URL::to('getSalepersonData')}}",
mtype:'GET',
datatype:'json',
		
colModel: [
{ name: "salesperson_id", label: "id", width: 100,hidden:true },
{ name: "employee_id", label: "id", width: 100,hidden:true },
{ name: "salesperson_site_count", label: "salesperson_site_count", width: 100,hidden:true },
{ name: "salesperson_number", label: "SalesPerson Number", width: 250 ,editable:true, editrules:{date:true}},
{ name: "salesperson_name", label: "SalesPerson Name", width: 250 ,editable:true, editrules:{date:true}},
{ name: "first_name", label: "Employee Name", width: 250 ,editable:true, editrules:{date:true}},
{ name: "active", label: "Active", width: 250,editable:true, editrules:{date:true}},
		{ name: "username", label: "Created By", editable:true, editrules:{date:true}},
      	{ name: "created_id", label: "Created By",hidden:true},
],
rowNum:10,
		viewrecords: true,
		footerrow: true,
		rownumbers: true ,
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rowList: [10, 20, 50, 100,250,500,1000],
		pager: "#grid1",
        sortorder: "desc"
});
/* -- End Function --*/

$("#grid1").jqGrid('hideCol', 'cb');
jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$("#grid1").jqGrid("setLabel", "rn", "S.No");

/* -- Start Jqgrid Export data to PDF Function --*/
$("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false,edit:true,add:true,del:true,search:true,cloneToTop:true,refresh:false});
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
  fileName : "Sales Person.pdf",
  mimetype : "application/pdf"  
});
 });
 /* -- End Jqgrid Export data to PDF Function --*/

 /* -- Start Jqgrid Export data to Excel Function --*/
	 $(document).on('click',".exportexcel",function() {
$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Sales Person.xlsx"
    					
				})	
	
});
/* -- End Jqgrid Export data to PDF Function --*/

/* -- Start Sale Person name to change Upper case Function --*/
$('.salesperson_name').on('keyup',function(){
this.value=this.value.toUpperCase();
})
/* -- End Sale Person name to change Upper case Function --*/

/* -- Start Employee data Function --*/
$('.employee_id').on('change',function(){
	var employee_id=$(this).val();
	var primary_id=$('#edit_id').val();
	if(primary_id==""){
		primary_id=0;
	}
  var url ="{{ url('employeecheck') }}/" +employee_id+'/'+primary_id;
  if(employee_id = '' ){
      $.get(url,function(data)
      {
        
        if($.trim(data) ==1){
          $('.employee_id').select2('val'," ");
          notyMsg("info","Employee is already Assigned");      
        }
      });
  }
});
/* -- End Employee data Function --*/

/* -- Start Edit data Function --*/
$("#editdata").click(function()
{
   var form=$("#save");
   form.parsley().destroy();
  var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');

  var id = jQuery("#grid1").jqGrid ('getCell', gr, 'salesperson_id');
  var salesperson_name = jQuery("#grid1").jqGrid ('getCell', gr, 'salesperson_name');
  var salesperson_site_count = jQuery("#grid1").jqGrid ('getCell', gr, 'salesperson_site_count');
  var salesperson_number = jQuery("#grid1").jqGrid ('getCell', gr, 'salesperson_number');
  var employee_id = jQuery("#grid1").jqGrid ('getCell', gr, 'employee_id');
  var active = jQuery("#grid1").jqGrid ('getCell', gr, 'active');

  var url ="{{ url('salespersonedit2') }}/" +id;
  if(gr){
  $.get(url,function(data)
  {
    var data=$.trim(data);
    if(data==0)
    {
      if( id != false )
      {

		  
        $('#edit_id').val(id);
        $('#salesperson_id').val(id);
        $('#salesperson_name').val(salesperson_name);
        $('#salesperson_site_count').val(salesperson_site_count);
        $('#salesperson_number').val(salesperson_number);
        $('#active').select2('val',[active]);
        $('#employee_id').select2('val',[employee_id]);
      }
      else
      {
      notyMsg("info","Please Select Row");
      }
    }else
    {
      $('#edit_id').val();
      $('#salesperson_id').val();
      $('#salesperson_name').val();
      $('#salesperson_number').val();
      $('#salesperson_site_count').val();
      $('#active').select2('val',['Yes']);
      $('#employee_id').select2('val',['']);
      notyMsg('info',"You Can't be Edit this salesperson name, Already used!!!");
    }
  });
  }else{
    notyMsg("info","Please Select Row");
  }
});
/* -- End Edit data Function --*/

/* -- Start View data Function --*/
$('#viewdata').click(function(){
//alert('hhhh');
var gr=$('#grid1').jqGrid('getGridParam','selrow');
var cellValue = $("#grid1").jqGrid ('getCell', gr, 'salesperson_id');  //alert(cellValue);

if(gr)
{
var url="salesperson";
var viewurl = url+'/'+cellValue+'/view';
window.location.replace('salesperson/' +cellValue);
}
else
{
notyMsgs("info","Please Select Row");
}
});
/* -- End View data Function --*/

//show column
showcolumn('grid1');

/* -- Start Clear Search Function --*/
$("#clearsearch").click(function() {
    var grid = $("#grid1");
    grid.jqGrid('setGridParam',{search:false});

    var postData = grid.jqGrid('getGridParam','postData');
    $.extend(postData,{filters:""});
    grid.trigger("reloadGrid",[{page:1}]);
	$('input[id*="gs_"]').val("");
  });
/* -- End Clear Search Function --*/

/* -- Start Save,reset button Function --*/
$('.reset').click(function(){
  var form=$("#save");
   form.parsley().destroy();

  $(':input','#save')
    .not(':button, :submit, :reset')
    .val('')
    .prop('checked', false);
    var empid ='<?php echo \Session::get('id') ?>';
    $('.employee_id').select2('val',[empid]);
    $('#active').select2('val',["Yes"]);
    $('#salesperson_id').change();

});
/* -- End Save,reset button Function --*/

/* -- Start Delete data Function --*/
	$(".delete").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var id = jQuery("#grid1").jqGrid ('getCell', gr,'salesperson_id');
	if(gr){
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
        $(document).on('click','.confirm',function(e){
				var url ="{{ url('salespersondelete') }}/" +id;
				$.get(url,function(data)
				{
					var data = $.trim(data);
					var red_url ="{{ url('salesperson') }}";
					if(data =='0')
					{
						notyMsg('success','Deleted Successfully!!!');
						setTimeout(function(){
						$("#grid1")[0].triggerToolbar();
						}, 1500);
					}
					if(data =='1')
					{
						notyMsg('info',"You Can't delete  Used in SomeWhere!!!");
						setTimeout(function(){
						$("#grid1")[0].triggerToolbar();
						}, 1500);
					}

        });
				});
			}
			else
			{
			$('.apply').css('display','none');
			swal("Cancelled");
			}
            })
		$('.apply').css('display','none');

	}
	else
	{
	notyMsg("info","Please Select Row");
	}
	});
/* -- End delete data Function --*/
});
</script>

@endsection
