@extends('layouts.header')
@section('content')
<style type="text/css">
 /* .breadcrumb{
    display: none;
  }*/

</style>

<span class="ui_close_btn"></span>




<h2 class="heads">Project Type
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick='location.href="{{ url($pageModule) }}"'></a></span>
</h2>

<div class="card">


<div class="card-body card-block">
  <form method="post" action="" id="projecttype_form" class="projecttype_form" data-parsley-validate enctype="multipart/form-data">
 <?php $data=\Session::get('data'); if(isset($data[$pageMethod]['save'])) { ?>

{{ csrf_field() }}
    <div class="col-md-4">

            <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span class="req">*</span>Project Type Name</label>
            <div class="col-md-8">
            <input class="form-control project_type_id" id="project_type_id" name="project_type_id" size="16" type="hidden" value="{{$row->project_type_id}}" >
                <input type="text" id="project_type_name" name="project_type_name"  class="form-control project_type_name" value="{{$row->project_type_name}}"  required>
            <span class="btn btn-danger dup_name" style="display:none;"></span>
				</div>

        </div>
      

    </div>
    <div class="col-md-4">

   <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Description</label>
            <div class="col-md-8">

                <input type="text"  id="description" name="description"  class="form-control description" value="{{$row->description}}">
            </div>

        </div>
      

                </div>
<div class="col-md-4">
  
              <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Active</label>
            <div class="col-md-8">

                 <select name='active' rows='5' class='form-control active select2'  data-show-subtext="true" data-live-search="true"  >
               
                <option <?php if($row->active =="YES") { echo "selected"; } else { echo ""; } ?> value="YES" selected>YES</option>
                <option <?php if($row->active =="NO") { echo "selected"; } else { echo ""; } ?> value="NO">NO</option>
                    </select>
            </div>

        </div>
            </div>
            <div class="col-lg-12 col-md-12">
                <div class="form-group text-center">
                    <button type="button" class="btn save saveform" value="SAVE">Save</button>
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
            <div class="col-md-12 text-left">

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
        var project_type = $(".project_type_name").val();
        var edit_id = $("#project_type_id").val();
        $.ajax({
            cache: false,
            url: "{{URL::to('getCheckprojecttype')}}",
            type: 'GET',
            dataType: 'json',
            async : false,
            data: {project_type : project_type,edit_id : edit_id},
            success: function(response)
            {

                if(response == 1)
                {
                    $('.dup_name').html('Project Type:'+project_type+' Already Exists');
                    $('.dup_name').show();
                    $(".project_type_name").val('');
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


 $('#clear').click(function(){
$(':input','#projecttype_form')
  .not(':button, :submit, :reset')
  .val('')
  .prop('checked', false);
$('.active').val("YES").change();
            });
  
  $(document).on('click',".exportexcel",function() {
$("#grid1").jqGrid("exportToExcel",{
          includeLabels : true,
              includeGroupHeader : true,
              includeFooter: true,
              fileName : "Project Type.xlsx"
              
        })  
});
  showcolumn('grid1');
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
  fileName : "Project Type.pdf",
  mimetype : "application/pdf"  
});
   });
  


var active="{{ $active }}";

    $("#grid1").jqGrid({
        url:"getprojecttypeData/",
        mtype:'GET',
        datatype:'json',
        colModel: [
            { name: "project_type_id", label: "id", width: 100,hidden:true },
            { name: "project_type_name", label: "Project Type Name", width: 200,editable:true, editrules:{date:true}},
            { name: "description", label: "Description", width: 200,editable:true },
            { name: "active", label: "Active", width: 100,editable:true,editoptions:{value:active}},
        ],
        iconSet: "fontAwesome",
        rowNum: 10,
        rowList: [10,20,50,100],
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        rownumbers:true,
        pager: "grid1",
        autowidth: true,
        viewrecords: true,
        searching: {
            defaultSearch: "cn"
        }
    });

jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});


 $(document).on('click','.saveform',function() {
  var form=$("#projecttype_form");
    form.parsley();
  $('input[name=_token]').val("{{csrf_token()}}");
  var data = form.serialize();
  form.parsley().validate();
var url="{{ URL::to('projecttypesave') }}";
duplicate_validate();
if (form.parsley().isValid())
{
	  if(dup_chk==true)
    {
$.post(url, data, function(data1)
{
 var status = data1.status;
 var msg    = data1.message;
 notyMsg('success',"<i class='' style='font-size:16px'></i>"+msg+"");
  $("#grid1")[0].triggerToolbar();
  $("#clear").trigger('click');
  
    });
}
}

      });

  $(document).on('keypress','.project_type_name',function(){
      this.value = this.value.toUpperCase();
  });
  
  // $("#clear").click(function()
  // { 

  //     var grid = $("#grid1");
   
  //     grid.jqGrid('setGridParam',{search:false});

  //     var postData = grid.jqGrid('getGridParam','postData');
    
  //     $.extend(postData,{filters:""});
   
  //     grid.trigger("reloadGrid",[{page:1}]);
   
  //     $('input[id*="gs_"]').val("");
     
     
  // });


    $("#edit").click(function()
            {

            var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
            var id = jQuery("#grid1").jqGrid ('getCell', gr, 'project_type_id');
            var description = jQuery("#grid1").jqGrid ('getCell', gr, 'description');
            var project_type_name = jQuery("#grid1").jqGrid ('getCell', gr, 'project_type_name');
            var active = jQuery("#grid1").jqGrid ('getCell', gr, 'active');
      
      
            if(gr)
            {
             

         $('#project_type_id').val(id);
         $('#description').val(description);
         $('#project_type_name').val(project_type_name);
         $('.active').val(active).change();
     }
            else
            {
          
            notyMsg("info","Please Select a Row");
            }
            });
  
  
   /***** Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#grid1");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
                 $('input[id*="gs_"]').val("");
                $('select[id*="gs_"]').select2('val',['']);
	});
/*End*/


$(document).on('click','.del',function(e){
  
        e.preventDefault();
        var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
        var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'project_type_id');
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
                    $.get('projecttypedelete/'+cellValue, function(data,status)
                    {
                        var data = $.trim(data);
                        if(data =='0')
                        {
                            notyMsg('success','Deleted Successfully');
                            setTimeout(function(){
                            $("#grid1")[0].triggerToolbar();
                            }, 1500);
                        }
                        if(data =='2')
                        {
                            notyMsg('error',"You Cant't delete  Used in SomeWhere");
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

});


$("#edit,.sec").click(function(){
var form=$("#projecttype_form");
   form.parsley().destroy();

 });


</script>


@endsection
