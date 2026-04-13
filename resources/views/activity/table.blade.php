 @extends('layouts.header')
@section('content')
<h2 class="heads">Activity</h2>
<div class="card">
<div class="card-body card-block">
<form id="save" >
          {{ csrf_field() }}
<div class="col-md-4">
<div class=" form-group row">
                <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red;">*</span>Activity Name</label>
                <div class="col-md-7">
                <input class="form-control edit_id" id="edit_id" name="activity_id" size="16" type="hidden" value="" readonly>
                <input type="text" id="activity_name" name="activity_name" class="form-control activity_name" value="" required style="width:100%;" tabindex="1">
						<span class="btn btn-danger dup_name" style="display:none;"></span>
			</div>
         </div>
    </div>

    <div class="col-md-4">
            <div class="form-group row">
                <label for="fob_barriers" class="form-control-label col-md-5">Description</label>
                <div class="col-md-7">
                <input type="text" id="description" name="description" class="form-control description" value="" style="width:100%;" tabindex="3">
                </div>
                </div>
    </div>
    <div class="col-md-4">
    <div class="form-group row">
      <label for="active" class="form-control-label col-md-5">Active</label>
      <div class="col-md-7">
    <select name='active' id='active' rows='5' class=' select2 active'  >
    <option value="Yes" >Yes</option>
    <option value="No" >No</option>
    </select>
       </div>
       </div>
       </div>
      <div class="row text-center">
      <div class="col-md-12">
      <button type="submit" class="btn save">save</button>
     <?php include('toolbar.php'); ?>
    </div>
  </div>
</form>
<div class="row">
<div class="col-md-12">
<table id="grid1"></table>
</div>
</div>
</div>
</div>
<script src="<?php echo asset('js/plugins/parsley/dist/parsley.js')?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
<script>

/* Start Duplicate validate */
	             var dup_chk = true;
	        function duplicate_validate()
	        {
              var activity_name = $(".activity_name").val();
              var edit_id = $("#edit_id").val();
              if(!activity_name=='')
              {
	            $.ajax({
	                cache: false,
	                url: 'activity/checkname', //this is your uri
	                type: 'GET',
	                dataType: 'json',
	                async : false,
	                data: {activity_name : activity_name,edit_id : edit_id},
	                success: function(response)
	                {
	                    console.log(response);
	                    if(response == 1)
	                    {
	                        $('.dup_name').html('activity name :'+activity_name+' Already Exists');
	                        $('.dup_name').show();
	                        $(".activity_name").val('');
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
              else
              {
                notyMsg('info','Please Enter the Activity Name');
              }
	        }
/* End Duplicate validate */
$(document).ready(function(){
		/* Start upper Case  */
		  $('.activity_name').on('keyup',function(){
            $('.dup_name').hide();
          this.value=this.value.toUpperCase();
        });
/* End upper Case  */
    var form=$("#save");
    form.parsley();
  form.submit(function(){
  $('input[name=_token]').val("{{csrf_token()}}");
var data = form.serialize();
var url="{{ URL::to('activity/save') }}";
	  duplicate_validate();
    if(form.parsley().isValid())
                 {
	  if(dup_chk==true){
$.post(url, data, function(data1)
{
	 var status = data1.status;
   var msg    = data1.message;
   // var id     = data.id;
  notyMsg('success',"<i class='' style='font-size:16px'></i>"+msg+" !!!");
  $("#grid1")[0].triggerToolbar();
  $('.activity_name').val('');
  $('.description').val('');
  $('.active').val('');
  $(".reset").trigger('click');
    });
  }
}
    return false;
      });
       var data="{{ $datas }}";
/* Start Jqgrid data */
        $("#grid1").jqGrid({
        url: "activitydata",
         mtype:'GET',
         datatype:'json',
      colModel: [
     { name: "activity_id", label: "id", width: 100,hidden:true },
     { name: "activity_name", label: "Activity Name", width: 250,editable:true, editrules:{date:true}},
     { name: "description", label: "Description", width: 250,editable:true, editrules:{date:true}},
    { name: "active", label: "Active", editable:true, editrules:{date:true}},

 ],
     rowNum:10,
		viewrecords: true,
		footerrow: true,
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rowList: [10, 20, 50, 100,250,500,1000],
		pager: "#grid1",
        sortorder: "desc",
			 rownumbers: true ,
});
jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$("#grid1").jqGrid("setLabel", "rn", "S.No");
/* End Jqgrid data */
/* Start Save function */
   $('.reset').click(function(){
   	var form=$("#save");
            //form.parsley().destroy();
               $(':input','#save')
                .not(':button, :submit, :reset')
                .val('')
                .prop('checked', false);
         $('.activity_id').change();
	   var created=<?php echo(\Session::get('id')); ?>;

	           $('.active').select2('val',['Yes']);
            });
/* End Save function */
/* Start Show Column function */
 showcolumn("grid1");
/* Start Export data to pdf function */
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
  fileName : "Activity.pdf",
  mimetype : "application/pdf"  
});
 });
/* End Export data to pdf function */ 

/* Start Export data to Excel function */
		$(document).on('click',".exportexcel",function() {
$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Activity.xlsx"    					
				})	
});
/* End Export data to Excel function */

/*Start purpose:clear search the jqgrid*/
 $(".clearsearch").click(function(){
    var grid = $("#grid1");
    grid.jqGrid('setGridParam',{search:false});
    var postData = grid.jqGrid('getGridParam','postData');
    $.extend(postData,{filters:""});
    grid.trigger("reloadGrid",[{page:1}]);
    $('input[id*="gs_"]').val("");
  });
 /*End purpose:clear search the jqgrid*/

      /* Start edit data Jqgrid function */
      $("#editdata").click(function()
        {
        	var form=$("#save");
            form.parsley().destroy();
            var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
            var id = jQuery("#grid1").jqGrid ('getCell', gr, 'activity_id');
            var activity_name = jQuery("#grid1").jqGrid ('getCell', gr, 'activity_name');
            var description = jQuery("#grid1").jqGrid ('getCell', gr, 'description');
            var active = jQuery("#grid1").jqGrid ('getCell', gr, 'active');
            var url="activityedit/"+id;
            if(gr)
            {
              $.get(url,function(data){
                   var data = $.trim(data);
                   if(data == 0)
                  {
                  $('#edit_id').val(id);
                  $('#activity_name').val(activity_name);
                  $('#description').val(description);
					  	    $('#active').select2('val',[active]);
                  }else{
                        notyMsg('info',"You Can't be Edit this Activity , Already used!!!");
                  }
              });
            }
            else
            {
            notyMsg("info","Please Select Row");
            }
            });
        $("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false,edit:true,add:true,del:true,search:true,cloneToTop:true,refresh:false});
/* End edit data Jqgrid function */

/* Start Delete data Jqgrid function */
$(document).on('click','.delete',function(e){
e.preventDefault();
var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'activity_id');
if(gr)
{

    swal({
      title: "Are you sure?",
      text: "You want to delete!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes",
      cancelButtonText: "No",
      closeOnCancel:!1
}, function(e){

    if (e == true) {
var url ="{{ url('activity/delete/') }}/" +cellValue;
$.get(url,function(data)
{
    //alert(nh);
var data = $.trim(data);
var red_url ="{{ url('activity') }}";
var data = $.trim(data);
if(data =='0')
{
  notyMsg('success','Deleted Successfully!!!');

  setTimeout(function(){
$(".clearsearch").trigger('click');
  }, 1500);
}
if(data =='1')
{
  notyMsg('info',"You Can't delete Used in SomeWhere!!!");
  setTimeout(function() {
     $(".clearsearch").trigger('click');
  },1500);

}

});

                }
                else
                {
          			$('.apply').css('display','none');
          			swal("Cancelled");
					  setTimeout(function() {
     $(".clearsearch").trigger('click');
  },1500);
          			}
		
  })

$('.apply').css('display','none');

}
else
{
notyMsg("info","Please Select Row");
}

            });

/* End Delete data Jqgrid function */
	});
	</script>

@endsection
