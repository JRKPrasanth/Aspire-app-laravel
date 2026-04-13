@extends('layouts.header')
@section('content')
<style type="text/css">
	input {
    outline: none;
    border: 1px solid #024d87;
    border-radius: 5px;
}

</style>
<div class="panel">
	<div class="panel-body">
		<form name="drawing" action="" id="save" enctype="multipart/form-data">
			 <input type="hidden" name="edit_id"  id="edit_id" />
                {{ csrf_field()}}
    <div class="row">
    	<div class=" col-md-6">
		<div class="form-group row">
        <label for="Department Name" class="form-control-label col-md-offset-1 col-md-3"><span style="font-style:20px;color:red;">*</span>Department Name</label>
        <div class="col-md-6">
            <select name="department" id="department" class="department select2" required="true">
               {!! $department !!}
            </select>
        </div>
        <div class="col-md-1 showinline">
            <span class="showspan"><i class="fa fa-refresh jcr_department"></i></span>
        </div>
        <div class="col-md-2"></div>
    </div>
	</div>	
    <div class=" col-md-6">
  	    <div class="form-group  row " > 
					<label for="File" class=" control-label col-md-offset-1 col-md-3 text-left"> 
	                        File Upload
					</label>
					<div class="col-md-6">
                                              <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">

                                            <input type="file" name="file" id="file">
					 </div>
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div> 
  </div>
   <div class="row">
    	<div class=" col-md-6">
		<div class="form-group row">
        <label for="Document Name" class="form-control-label col-md-offset-1 col-md-3"><span style="font-style:20px;color:red;">*</span>Document Name</label>
        <div class="col-md-8">
            <input type="text" name="document" id="document" class="document" required="true">
        </div>
        <div class="col-md-2"></div>
    </div>
	</div>	
  </form>
<div class="col-md-12 text-center">
<div class="form-group  row " > 

	<button type="button" class="btn save saveform">  Save </button>
    <button type="button" class="btn sec delete">  Delete </button>
     <button type="button" class="btn sec edit">  Edit </button>

    <div class="row">
<div class="col-md-12" style="padding: 15px;">
<!-- OUR CONTENT STARTS HERE -->

<table id="grid1"></table>

<!-- OUR CONTENT ENDS HERE -->


</div>
</div>



</div>
</div>
</div>  

	</div>
</div>





<script type="text/javascript">
	$(document).ready(function(){

$("#grid1").jqGrid(
            {
                url: "filegrid",
                datatype: "json",
                mtype: "GET",
                colModel: [
                    { name: "drawing_id", label: "drawing_id", width: 250, hidden: true },
                    { name: "department", label: "Department Name", width: 250},
                    { name: "document", label: "Document Name", width: 250},
                    { name: "created_at", label: "Upload Date", width: 250},
                    { name: "file",       label: "File", align: "center", formatter: downloadLink },
                ],

  
                iconSet: "fontAwesome",
                rowNum: 10,
                rowList: [10,20,100,1000,2000],
                sortname: "drawing_id",
                sortorder: "desc",
                viewrecords: true,
                gridview: true,
                rownumbers:true,
                pager: "#grid1",
                multiselect:false,
                multipageselection:true,
                searching: {
                defaultSearch: "cn",
                },
            });
 function downloadLink(cellvalue, options, rowObject) {
        var MTJobRoleId = rowObject.MTJobRoleId;

     return "<a href='{{asset('upload/drawing/5ce9303ce0b0c.pdf')}}' download='{{asset('upload/drawing/5ce9303ce0b0c.pdf')}}'>View Certificate</a>";
}
$(document).on('click',".jcr_department",function() {
	alert("hai");
$("#department").jCombo("{{ URL::to('jcomboform?table=ma_department_t:department_id:department_name') }}",
      {selected_value:department});
});
 // $(document).on('change','#department',function()
 //            {
 //                var department_id=$(this).val();
 //                var condition = ' department_id IN('+department_id+')';
                
 //                $("#department").jCombo("{{ URL::to('jcomboformrerule?table=ma_department_t:department_id:department_name') }}&order_by=department_name asc"+'&parent='+condition,
 //                    {selected_value:""});
 //            });
/** JQgrid Description Show column Data Start **/
showcolumn('#grid1');
jQuery("#showcolumn").click(function() {

  var btn = $(".showcolumn").val();

  if(btn =="1" )
  {  $(this).text('Hide column');
    jQuery("#grid1").jqGrid('showCol',["pricelist_id"]);
    $(".showcolumn").val('2');
  }else{
       $(this).text('Show column');
    jQuery("#grid1").jqGrid('hideCol',["pricelist_id"]);
    $(".showcolumn").val('1');
  }

});

 $(document).on('click','.saveform',function()
                {
                	//alert("sdsdsd");
                    var url ="{{url('drawingsave')}}";
                    var data = $('#save').serialize();
                  alert(data); 
                    var form = $('#save');
                     //alert(form);
                    var form_data = new FormData(document.getElementById('save'));
                      //alert(form);
                    form.parsley().validate();
                    var form = $('#save');

                    form.parsley().validate();
                     
                    if (form.parsley().isValid())
                    {
                    //	alert("url");
                        
                        
                           $.ajax({
                          url: "{{ url('drawingsave')}}",
                          type: "POST",
                          data: form_data,
                          enctype: 'multipart/form-data',
                          processData: false,  // tell jQuery not to process the data
                          contentType: false,   // tell jQuery not to set contentType
                          async:true,
                          xhr: function(){
                            var xhr = $.ajaxSettings.xhr();
                            if (xhr.upload) {
                                xhr.upload.addEventListener('progress', function(event) {
                                }, true);
                                }
                                return xhr;
                        }
                        }).done(function(data,status)
                        {
                           
                            if(data == 1)
                            {
                                notyMsgs('info','Drawing Saved Successfully');
                                    $("#grid1")[0].triggerToolbar();
                                $('#save')[0].reset();
                                $('.clear').trigger('click');
                            }
                            if(data == 2)
                            {
                                notyMsgs('info','Drawing Updated Successfully');
                                   $("#grid1")[0].triggerToolbar();
                                $('#save')[0].reset();
                                $('.clear').trigger('click');
                            }
                            else
                            {
                                $(".alert-success").hide();
                                $(".alert-danger").fadeIn(800);

                            }
                        }).fail(function(data,status)
                        {
                          

                                $(".alert-success").hide();
                                $(".alert-danger").fadeIn(800);

                        });
                    
                    }
                   // location.reload();

        });

  $(".edit").click(function()
            {
           //  alert("hai");
                var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
                var drawing_id = jQuery("#grid1").jqGrid ('getCell', gr, 'drawing_id');
               //lert(breakdownseverity_id);
                var department = jQuery("#grid1").jqGrid ('getCell', gr, 'department');
                var document = jQuery("#grid1").jqGrid ('getCell', gr, 'document');
                //var description = jQuery("#grid1").jqGrid ('getCell', gr, 'description');
               if( drawing_id)
                {
                     $('#edit_id').val(drawing_id);
                  //  $('#department_id').val(department_id);
                  //  $('#department').val(department);
                    $('#department').val('').select2('val',[department]);
                    $('#document').val(document);
                    

                }
                else
                {
                    notyMsg('INFO','Please Select a Row');
                }
            });
   $(document).on('click','.delete',function(e){
                e.preventDefault();
                var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
                var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'drawing_id');
             
                if(cellValue )
                {
                    $.get('drawingdelete?del_id='+cellValue, function(data,status)
                    {
                        if(data == 0)
                        {
                            notyMsg('Warning','Deleted Successfully');
                            $("#grid1")[0].triggerToolbar();
                        }
                        else if(data == 2)
                        {
                            notyMsg('SUCCESS','Deleted Successfully');
                            $("#grid1")[0].triggerToolbar();
                        }
                    });
                }
                else
                {
                    notyMsg('Info','Please Select a Row');
                }
 location.reload();

            });
   });
        /** Description Save End **/
                



		

                
	</script>
	@endsection