@extends('layouts.header')
@section('content')

<body>
<span class="ui_close_btn"></span>
<div class="container" style="height:1000px;">
<div class="row">

    <br>
<div class="col-md-offset-3 col-lg-6">
<div class="card">
<div class="card-header">
<strong>DEPARTMENT</strong>
</div>
            <form  action=""  id="save" >
                <div class="card-body card-block">
                    <input type="hidden" name="edit_id" value="" id="edit_id" />
                {{ csrf_field()}}
                <div class="col-md-offset-2 col-md-8">

                    <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-5">Department Name</label>
                            <div class="col-md-7">
                                <input type="text" id="department_name" name="department_name" class="form-control department_name" value="" required>
                                <span class="btn btn-danger dup_name" style="display:none;"></span>
                            </div>
                    </div>
                    <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-5">Description</label>
                            <div class="col-md-7">
                                <input type="text" id="description" name="description" class="form-control 	description" value="" required>
                            </div>
                    </div>
                </div>
        </div>

        <div class="section col-md-offset-4">
            <button type="button" id="save" class="btn btn-success save">Save</button> &nbsp;&nbsp;&nbsp;
            <button type="button"  class="btn btn-cancel reset" >Cancel</button>
        </div>

</form>

</div>
</div>
	
</div>
    <br>
    <div class="card">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-12">
                <div class="panel panel-visible" id="spy1">
                    <div class="panel-heading">
                        <div class="panel-title hidden-xs">
                        <span class="glyphicon glyphicon-tasks"></span>

                        <a id="editdata"  class="btn btn-sm btn-primary"><i class="fa fa-edit"> Edit </i></a>
                        <a id="viewdata"  class="btn btn-sm btn-info"><i class="fa fa-eye"> View </i></a>
                        <button type='button'  class='btn btn-sm btn-danger delete'><i class="fa fa-trash"> Delete </i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
    <div class="col-md-12">
    <table id="grid1"></table>
    </div>
    </div>

    </div>

<!--        <div class="col-xs-6">
            <button class="btn btn-default waves-effect waves-light" id="sa-warning">Click me</button>
        </div>-->
        <br>
    </div>
@extends('layouts.footer')
</div>
</body>

	<script>
	$(document).ready(function(){



            var data="{{ $datas }}";
            var result = jQuery.parseJSON(data.replace(/&quot;/g, '"' ));
            $("#grid1").jqGrid({

                colModel: [
                    { name: "department_id", label: "id", width: 100 },
                    { name: "department_name", label: "Department", width: 250,editable:true, editrules:{date:true}},
                    { name: "description", label: "Description", width: 250,editable:true, editrules:{date:true}},
                    {name:"active",label:"active",width: 250,editable:true, editrules:{date:true}},
                ],
                data:result,
                iconSet: "fontAwesome",
                rownumbers: true,
                sortname: "department_id",
                sortorder: "asc",
                threeStateSort: true,
                sortIconsBeforeText: true,
                headertitles: true,
                pager: true,
                rowNum: 10,
                viewrecords: true,
                searching: {
                    defaultSearch: "cn"
                }
            });
        jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});


            $("#editdata").click(function()
            {
            var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
            var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'department_id');

            if( cellValue != false )
            {
                $.get('employeedepartment/department_id='+cellValue,function(data)
                {
                    console.log(data);
                    $('#department_name').val(data['department_name']);
                    $('#description').val(data['description']);
                    $('#edit_id').val(data['department_id']);

                });
            }
            else
            {
            alert("Please Select Row");
            }
            });

            $('#viewdata').click(function(){
              //alert('hhhh');
              var gr=$('#grid1').jqGrid('getGridParam','selrow');
              var cellValue = $("#grid1").jqGrid ('getCell', gr, 'department_id');  //alert(cellValue);

              if(cellValue != false)
              {
                 var url="employeedepartment";
                 var viewurl = url+'/'+cellValue+'/view';
                 window.location.replace('employeedepartment/' +cellValue);
              }
              else
              {
                 alert("Please Select Row");
              }
            });

            $('.reset').click(function(){
                $('#department_name').val('');
                $('#description').val('');
                $('#edit_id').val('');
                $('.dup_name').hide();
            });


            var dup_chk = true;
            function duplicate_validate()
            {
                var department_name = $(".department_name").val();
                var edit_id = $("#edit_id").val();

                $.ajax({
                    cache: false,
                    url: 'employeedepartment/checkname', //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async : false,
                    data: {department_name : department_name,edit_id : edit_id},
                    success: function(response)
                    {
                        console.log(response);
                        if(response == 1)
                        {
                            $('.dup_name').html('Department Name:'+department_name+' Already Exists');
                            $('.dup_name').show();
                            $(".department_name").val('');
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



            $(document).on('click','.save',function(e){

                e.preventDefault();
                var data;
                data = $("#save").serialize();
                duplicate_validate();
                if(dup_chk == true)
                {
                $.post('employeedepartment/save', data, function(data)
                {
                    if(data == 1){
                        alert('saved successfully');
                        //location.reload();
                    }
                    else if(data == 2)
                    {
                        alert('updated  successfully');
                        //location.reload();
                    }
                });
                }

            });




            $(document).on('click','.delete',function(e){
                e.preventDefault();
                var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
                var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'department_id');
                if(cellValue != false)
                {
                    $.get('employeedepartment/delete?del_id='+cellValue, function(data,status)
                    {
                        if(data == 1)
                        {
                            alert('Deletion Error Already Used In Somewhere');
                            location.reload();
                        }
                        else if(data == 2)
                        {
                            alert('Deleted Successfully');
                            location.reload();
                        }
                    });
                }
                else
                {
                 alert("Please Select Row");
                }


            });

	});
	</script>

@endsection
