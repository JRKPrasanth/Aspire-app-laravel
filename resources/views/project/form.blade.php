@extends('layouts.header')
@section('content')


<?php include("tools_menu.php"); ?>
<span class="ui_close_btn"></span>





<h2 class="heads">Project<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick='location.href="{{ url($pageModule) }}"'></a></span></h2>


<div class="card">


<div class="card-body card-block">
  <form method="post" action="" id="project_form" class="project_form" data-parsley-validate enctype="multipart/form-data">

{{ csrf_field() }}
    <div class="col-md-4">

            <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Project Name</label>
            <div class="col-md-8">
            <input class="form-control project_id" id="project_id" name="project_id" size="16" type="hidden" value="{{$row->project_id}}" >
                <input type="text" id="project_name" name="project_name"  class="form-control project_name" value="{{$row->project_name}}"  required>
               <span class="btn btn-danger dup_name" style="display:none;"></span>
            </div>

        </div>
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Description</label>
            <div class="col-md-8">

                <input type="text" id="description" name="description"  class="form-control description" value="{{$row->description}}">
            </div>

        </div>
     </div>
    
     
 
   
    <div class="col-md-4">

   <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Project Type</label>
            <div class="col-md-6">

                 <select name='project_type_id' rows='5' class='form-control project_type_id select2'  data-show-subtext="true" data-live-search="true"  required>
                    </select>
            </div>
         <div class="col-md-1 showinline">
                            <span class="showspan"> <i class="fa fa-refresh jcr_product_group_id"></i></span>
			                   </div>
        </div>
     
     <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Active</label>
            <div class="col-md-8">

                 <select name='active' rows='5' class='form-control active select2' id="active"  data-show-subtext="true" data-live-search="true"  >
                 <option value="">--Please select--</option>
                <option <?php if($row->active =="YES") { echo "selected"; } else { echo ""; } ?> value="YES" selected>YES</option>
                <option <?php if($row->active =="NO") { echo "selected"; } else { echo ""; } ?> value="NO">NO</option>
                    </select>
            </div>

        </div>
                </div>
<div class="col-md-4">
   <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Customer</label>
            <div class="col-md-8">

                 <select name='customer_id' rows='5' class='form-control customer_id select2' id="customer_id" data-show-subtext="true" data-live-search="true"  required>
                    </select>
            </div>

        </div>
        

            </div>
            <div class="col-lg-12 col-md-12">
                <div class="form-group text-center">

               </div>

            </div>
            </form>
            <div class="row">
              <div class="col-md-4" ></div>
            <div class="col-md-12 text-center">
               <?php include('toolbar.php'); ?>
              </div>
            </div>


            <div class="row">
            <div class="col-md-12">

            <table id="grid1"></table>
            </div>
            </div>
</div>


</div>


  


<script>
  
   var dup_chk = true;
    function duplicate_validate()
    {
      
      
        var project_name = $(".project_name").val();
       
      var edit_id = $("#project_id").val();
        $.ajax({
            cache: false,
            url: 'getCheckprojectname',
            type: 'GET',
            dataType: 'json',
            async : false,
            data: {project_name : project_name,edit_id : edit_id},
            success: function(response)
            {

                if(response == 1)
                {
                    $('.dup_name').html('project Name:'+project_name+' Already Exists');
                    $('.dup_name').show();
                    $(".project_name").val('');
                    dup_chk = false;

                }
                else if(response == 0)
                {
                    var html ="";
                    $('.dup_name').hide();
                    dup_chk  = true;

                }

            },
            error: function(xhr, resp, text)
            {
                console.log(xhr, resp, text);
            }
        });
    }

$(document).ready(function()
{
  $(".project_type_id").jCombo("{{ URL::to('jcomboform?table=m_project_type_t:project_type_id:project_type_name') }}&order_by=project_type_name asc",
  {selected_value:"{{$row->project_type_id}}"});

$(".customer_id").jCombo("{{ URL::to('jcomboform?table=m_customers_t:customer_id:customer_name') }}&order_by=customer_name asc",
  {selected_value:"{{$row->customer_id}}"});

$(".organization_id").jCombo("{{ URL::to('jcomboform?table=m_organizations_t:organization_id:organization_name') }}&order_by=organization_name asc",
  {selected_value:"{{$row->organization_id}}"});


 



var project_type="{{ $project_type }}";
var active="{{ $active }}";
var date_format="{{\Session::get('p_date_format')}}";
$("#grid1").jqGrid({
     url:"getprojectData/",
mtype:'GET',
datatype:'json',

colModel: [
{ name: "project_id", label: "id", width: 100,hidden:true },
{ name: "customer_id", label: "id", width: 100,hidden:true },
{ name: "organization_id", label: "id", width: 100,hidden:true },
{ name: "description", label: "id", width: 100,hidden:true },
{ name: "project_type_id", label: "id", width: 100,hidden:true },
{ name: "project_name", label: "Project Name", width: 250,editable:true, editrules:{date:true}},
{ name: "project_type_name", label: "Project Type", width: 250,editable:true},
{ name: "customer_name", label: "Customer Name", width: 250,editable:true, editrules:{date:true}},
{ name: "active", label: "Active", width: 250,editable:true},
],
iconSet: "fontAwesome",
   rowNum: 10,
    rowList: [10,20,50,100],
    sortorder: "desc",
    viewrecords: true,
    gridview: true,
    rownumbers:true,
    
      pager: "#grid1",
      autowidth: true,
viewrecords: true,
searching: {
defaultSearch: "cn"
}
});
$("#gs_grid1_project_type_id").select2();
$("#gs_grid1_active").select2();

$(".select2").select2();
$(".select2").css('width','100%');

jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$("#grid1").jqGrid("setLabel", "rn", "S.No");

  
  $(document).on('keyup','.project_name',function(){
      this.value = this.value.toUpperCase();
  });
  
 $(document).on('click','.saveform',function() {

  var form=$("#project_form");
    form.parsley();
  $('input[name=_token]').val("{{csrf_token()}}");

        form.parsley().validate();
var url="{{ URL::to('projectsave') }}";

if (form.parsley().isValid())
        {
          change_date();
        var data = form.serialize();
duplicate_validate();
        
         if(dup_chk==true)
    {
$.post(url, data, function(data1)
{

 var status = data1.status;
 var msg    = data1.message;
 notyMsg('success',"<i class='' style='font-size:16px'></i>"+msg+" Successfully");
  $("#grid1")[0].triggerToolbar();
  $("#clear").trigger('click');
    });
}
        }

      });


   


$(document).on('click','.del',function(e){
        e.preventDefault();
        var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
        var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'project_id');
        if(gr)
        {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this",
                type: 'warning',
                showCancelButton: !0,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }, function(e) {
                 if(e == true)
                 {
                    $.get('projectdelete/'+cellValue, function(data,status)
                    {
                        var data = $.trim(data);
                        if(data =='0')
                        {
                            notyMsg('success','Deleted Successfully');
                            setTimeout(function(){
                            $("#grid1")[0].triggerToolbar();
                            }, 1500);
                        }
                        if(data =='1')
                        {
                            notyMsg('info',"You Cant't delete  Used in SomeWhere");
                            setTimeout(function(){
                            $("#grid1")[0].triggerToolbar();
                            }, 1500);
                        }
                    });
                }
                else{
                  $('.apply').css('display','none');
                  swal("Cancelled");
                }
            })
             $('.apply').css('display','none');
        }
        else
        {
            notyMsg("info","Please Select a Row");
        }
   });
$(document).on('click',".sec",function()
	{

var form=$("#project_form");
   form.parsley().destroy();
    
		var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
		var id = jQuery("#grid1").jqGrid ('getCell', gr, 'project_id');
		var project_name = jQuery("#grid1").jqGrid ('getCell', gr, 'project_name');
		var description = jQuery("#grid1").jqGrid ('getCell', gr, 'description');
		var project_t = jQuery("#grid1").jqGrid ('getCell', gr, 'project_type_id');
		var customer_id = jQuery("#grid1").jqGrid ('getCell', gr, 'customer_id');
		var organization_id = jQuery("#grid1").jqGrid ('getCell', gr, 'organization_id');
		var start_date = jQuery("#grid1").jqGrid ('getCell', gr, 'start_date');
		var end_date = jQuery("#grid1").jqGrid ('getCell', gr, 'end_date');
		var active = jQuery("#grid1").jqGrid ('getCell', gr, 'active');
	if(gr)
	{
		$('#project_id').val(id);
		$('#project_name').val(project_name);
		$('#start_date').val(start_date);
		$('#end_date').val(end_date);
		$('#description').val(description);
		$('.project_type_id').val(project_t).change();
		$('.customer_id').val(customer_id).change();
		$('.organization_id').val(organization_id).change();
		$('.active').val(active).change();
	}
	else
	{
		notyMsg("info","Please Select a Row");
	}
});
	
	/*Refresh Jcombo for Product Group*/
 var comp='{{ \Session::get('companyid')}}';
 $(document).on('click','.jcr_product_group_id',function(){
$(".project_type_id").jCombo("{{ URL::to('jcomboform?table=m_project_type_t:project_type_id:project_type_name') }}&order_by=project_type_name asc",
  {selected_value:"{{$row->project_type_id}}"});
		 
	});
 /*End*/

	
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
  fileName : "Project.pdf",
  mimetype : "application/pdf"  
});
			 
	});
$(document).on('click',".exportexcel",function() {
$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Project.xlsx"
    					
				})		 
	});
	
	
	
	
	$("#clear").click(function() {
    var form=$("#project_form");
   form.parsley().destroy();
		$(':input','#project_form')
  .not(':button, :submit, :reset')
  .val('')
  .prop('checked', false);
        
         $('.customer_id').val('').change();
         $('.organization_id').val('').change();
         $('.project_type_id').val('').change();
		 $('#active').select2('val',['YES']);
		 
	});
	
	showcolumn('grid1');
	$("#clearsearch").click(function() {
		var grid = $("#grid1");
		$("#grid1").jqGrid('setGridParam',{search:false});
		
		var postData = $("#grid1").jqGrid('getGridParam','postData');
		//$.extend(postData,{filters:""});
		$.extend(postData,{filters:"",'datas':'hi'});
		$("#grid1").trigger("reloadGrid",[{page:1}]);
                 $('input[id*="gs_"]').val("");
	});
$(window).on('load',function(){
       //preloader
       var preLoder = $("#preloader");
       preLoder.fadeOut(500);
       var backtoTop = $('.back-to-top')
       backtoTop.fadeOut(100);
   });
});




</script>


@endsection
