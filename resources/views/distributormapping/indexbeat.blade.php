@extends('layouts.header')
@section('content')


<span class="ui_close_btn"></span>

<h2 class="heads">Outlet Mapping</h2>
<button type="button" class="btn add create" id="create" value="">Create</button>

          <div class="row">
                <div class="col-md-12">
                <table id="grid1"></table>
                </div>
                </div>
                        </div>                 
             
        </div>
                      

    <script>
     
    $(document).ready(function(){
/************* distributormapping  grid start ***********/
   $("#grid1").jqGrid({
            url: "distributorbeatmappinggrid",
            datatype: "json",
            mtype: "GET",

      colModel: [
      { name: "beatmappinglines_id", label: "beatmappinglines_ib", width: 100,hidden:true},
     { name: "employee_name", label: "Employee Name", width: 250,editable:true, editrules:{date:true}},
     { name: "employee_code", label: "Employee Code", width: 250,editable:true, editrules:{date:true}},
     { name: "beat_name", label: "Beat", width: 250,editable:true, editrules:{date:true}},
      { name: "created_at", label: "Created At", width: 250,editable:true, editrules:{date:true}},
     { name: "username", label: "Created Login id", width: 250,editable:true, editrules:{date:true}},
     { name: "first_name", label: "Created Name", width: 250,editable:true, editrules:{date:true}},
        ],

        iconSet: "fontAwesome",
        rownumbers: true,
        sortname: "beatmappinglines_id",
        sortorder: "desc",
    rowList: [10, 50, 100,250,500,1000],
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
/************* distributormapping grid end ***********/

      $("#grid1").jqGrid("setLabel", "rn", "S.No");    

  
                              /************* clear grid search end ***********/
     /*****************create**************/

$('.create').click(function(){
    
    
    var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
    var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'beatmappinglines_id');
    if(gr)
    {

        var url = "{{ URL::to('distributormappingdata') }}";
                var editUrl = url + '/' + cellValue;
        window.location.replace(editUrl);
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
