@extends('layouts.header')
@section('content')

<?php include('tools_menu.php'); ?><h2 class="heads">Attendance Setting</h2>
  
  <div class="panel panel-visible" id="spy1">

    <div class="row">
    <div class="col-md-12">
<div class="panel-title ">

<!--<a> <button type="button" class="btn add create" >Create</button></a>-->
<a > <button type="button" class="btn create">Create</button></a>
<a> <button type="button" class="btn add Edit"  id="editdata">Edit</button></a>
<a id="view"><button type="button" class="btn add View" >View</button></a>
<a> <button type="button" class="btn add Delete  del" >Delete</button></a>

<a id="clearsearch"><button type="button" class="btn search">Clear Search</button></a>
</div>

  </div>
</div>
<div class="row">

<div class="col-md-12">


  <table id="grid1"></table>

</div>
</div>
</div>
  




<script type="text/javascript">

$( document ).ready(function() {
  /** Jqgrid Load data Start **/
      $("#grid1").jqGrid({
      url: "getattenancesettingdata",
      datatype: "json",
      mtype: "GET",

      colModel: [
    	{ name: "attendance_id", label: "id" ,hidden:true},
		{ name: "employee_type", label: "Employee Type" ,editable:true,},
     name: "formula_type", label: "Formula Type" ,editable:true,},
		{ name: "department_name", label: "Department Name" ,editable:true,},
 	],
 		iconSet: "fontAwesome",
	    rowNum: 10,
	    rowList: [10,20,50,100],
	    sortorder: "asc",
	    viewrecords: true,
	    gridview: true,
	    rownumbers:true,
	    caption: "Attendance Setting",
	    pager: true,
	    autowidth: true,
      	viewrecords: true,
	    searching: {
                 defaultSearch: "cn"
             }
           });

$("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false,edit:true,add:true,del:true,search:true,cloneToTop:true,refresh:false});
/** Jqgrid Load data End **/

/** Save data Start **/
 $(".create").click(function()
 {
     window.location.replace('atscreate');
 });
 /** Save data End **/
 
 /** Edit data Start **/
 $("#editdata").click(function()
{
  var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
  var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'company_id');
  if( cellValue != false )
  {

    window.location.replace('companyform/' +cellValue);
  }
  else
  {
   notyMsg('info',"Please Select Row");
  }
});
/** Edit data End **/



});
    </script>
@endsection
