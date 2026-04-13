@extends('layouts.header')
@section('content')

<h2 class="heads">Groupmenuaccess</h2>


        <div class="card">

           

            <div class="card-body card-block">
               
                   
                     <div class="row"> 
                     <div class="col-md-12">
                         
                          <!--<a> <button type="button" class="btn add create" >Create</button></a>-->
                            <a id="create">
                                <button type="button" class="btn add saveform">Create</button>
                            </a>
                            <a>
                                <button type="button" class="btn add Edit">Edit</button>
                            </a>

                            <a id="clearsearch">
                                <button type="button" class="btn search">Clear Search</button>
                            </a>

                     </div>
                     </div>

                           
                    
                    
                
                <div class="row">
                <div class="col-md-12">
                    
                     <table id="grid1"></table>

                </div>
                   
                </div>


                
            </div>
        </div>

    
  


<script type="text/javascript">
$( document ).ready(function() {

var data="{{$data}}";
            var result = jQuery.parseJSON(data.replace(/&quot;/g, '"' ));
            $("#grid1").jqGrid({
						
                colModel: [
                   { name: "menus", label: "Menus", width: 100 },
                    { name: "group_name", label: "Group Name", width: 250,editable:true, editrules:{date:true}},
                ],
                data:result,
                iconSet: "fontAwesome",
                rownumbers: true,
                sortname: "group_name",
                sortorder: "asc",
                threeStateSort: true,
                sortIconsBeforeText: true,
                headertitles: true,
                  rowList: [10,20,50,100,250,500,1000],
                pager: "grid1",
                rowNum: 10,
                viewrecords: true,
                searching: {
                    defaultSearch: "cn"
                }
            });
        jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
        

/* Edit Function*/
$("#create").click(function(){
	
var url="{{ url('creategroupaccess') }}";

window.location.replace(url);

});





$(".edit").click(function(){

      var index = $("#grid1").jqGrid('getGridParam','selrow');
  var pohdrid = $("#grid1").jqGrid ('getCell', index, 'menus');
       if( pohdrid != false ){
		window.location.replace('groupaccessedit/' +pohdrid);
	}
	else
	{
		 notyMsg("info","Please Select Row");
	}
     
});

    /*View Function*/
   
  

/*****  CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#grid1");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
	});
/*End*/
});
    </script>
@endsection
