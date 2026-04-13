@extends('layouts.header')
@section('content')
<h3 class="text-danger">Sales Quote</h3>
@include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="SalesTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Quote Number</th>
			 <th>Quote Name</th>
            <th>Quote Type</th>
             <th>Quote Date</th>
            <th>Customer Name</th>
            <th>Actions</th>
          </tr>
          <tr class="table-info">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>


@endsection
@push('scripts')


<script>
  
	
// data table funcrion	
    $(document).ready(function () {

	  var status="{{ $status }}";
	  var customer="{{$customer}}";
	  var pageMethod='<?php echo $pageMethod; ?>';
	  var quotetype = "copyquote";

      var table = $('#SalesTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ URL::to('soquotegriddata') }}?status="+status+"&quotetype="+quotetype+"&pageMethod="+pageMethod,
        columns: [

          { data: 'quote_no', name: 'quote_no' },
		  { data: 'quote_name', name: 'quote_name' },
          { data: 'quote_type', name: 'quote_type' },
		  { data: 'quote_date', name: 'quote_date' },
          { data: 'customer_name', name: 'customer_name' },

          {
            data: 'quote_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
          <button class="btn btn-sm btn-warning view-btn" data-id="${row.quote_hdr_id}">
            <i class="bi bi-eye"></i>
          </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'convert')) {
                buttons += `
          <button class="btn btn-sm btn-success convert-btn" data-id="${row.quote_hdr_id}">
          Convert
          </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#SalesTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
$( document ).ready(function() { 
	 var status="{{ $status }}";
  var customer="{{$customer}}";
var pageMethod='<?php echo $pageMethod; ?>';
 var quotetype = "copyquote";
		var date_format="{{\Session::get('p_date_format')}}";
$("#soquotegrid").jqGrid({
      url: "{{ URL::to('soquotegriddata') }}?status="+status+"&quotetype="+quotetype+"&pageMethod="+pageMethod,
      datatype: "json",
      mtype: "GET",
	 colNames: ["","Quote Number","Quote Name","Quote Date", "Quote Type","Customer Name"],
        colModel: [
            { name: "quote_hdr_id",align: "center",hidden:true},
			   { name: "quote_no", align: "center" },
            { name: "quote_name", align: "center" },
            { name: "quote_date", align: "center" ,formatter: 'date', formatoptions: { srcformat: 'Y-m-d', newformat: 'Y-m-d'}},
            { name: "quote_type", align: "center" },
            { name: "customer_name"},
           
             ],
 rowNum:10,
		viewrecords: true,
		footerrow: true,
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rowList: [10, 20, 50, 100,250,500,1000],
		pager: "#soquotegrid",
	 rownumbers: true ,
        sortorder: "desc",
});
	 	jQuery("#soquotegrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	$("#soquotegrid").jqGrid("setLabel", "rn", "S.No");
	$("#gs_quote_date").attr("placeholder","Eg:2018-10-31");	
		 $(document).on('click',".exportpdf",function() {
   	$("#soquotegrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Sales Quote.pdf",
  mimetype : "application/pdf"  
});
		 });
     // u
     $("#view").click(function()
 {
          //alert('adasd');
            var index = $("#soquotegrid").jqGrid('getGridParam','selrow');
            var sales_hdr_id = $("#soquotegrid").jqGrid ('getCell', index, 'quote_hdr_id');
      var selRows= $('#soquotegrid tbody .ui-state-highlight').length;
            if(index)
            {
        if(selRows > 1)
        {
          notyMsgs('info','Please Select One Row.....');
        }
        else
        {
                window.location.replace('soorderfromqo/' +sales_hdr_id+'?return=soorderfromqo');
        }
            }
            else
            {
               notyMsg('info',"Please Select Row");
            }
        });
	 $(document).on('click',".exportexcel",function() {
$("#soquotegrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Sales Quote.xlsx"
    					
				})	
});

$(document).on('click','.create',function()
{
var quotetype = $(this).val();
var url="{{ url('soquotecreate') }}/"+quotetype;
var red_url="{{ url('soquote') }}";
	window.location.replace(url);

});

jQuery("#soquotegrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
  jQuery("#gs_soquotegrid_customerid").select2();
	showcolumn('soquotegrid');
//For Default Width
  $(window).bind('resize', function() {
        $("#soquotegrid").setGridWidth($(window).width()*0.99);
    }).trigger('resize');

    /*Karthigaa Purpose For Edit Function*/
$(".convert").click(function()
{
	var index = $("#soquotegrid").jqGrid('getGridParam','selrow');
	var quoteid = $("#soquotegrid").jqGrid ('getCell', index, 'quote_hdr_id');
	if(index)
	{
		window.location.replace('salesorderfromqo/' +quoteid);
	}
	else
	{
		notyMsg('info',"Please Select Row");
	}
});
$("#clearsearch").click(function()
{
  console.log(cl);
var grid = $("#soquotegrid");
grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
 $('input[id*="gs_"]').val("");
	});



});
	
</script>

@endpush
