@extends('layouts.header')
@section('content')
<h3 class="text-danger">Seperation Approval</h3>
@include('layouts.breadcrumb')



<div class="card shadow-lg rounded-4 border-0">
<div class="container mt-4">
  <table id="sepTable" class="table table-bordered table-striped w-100">
<thead>
  <tr class="table-warning">
    <th>Name</th>
    <th>Employee Number</th>
    <th>Notice Period</th>
    <th>Relive Date</th>
    <th>Relive Reason</th>
    <th>Relive Reason by Reporting</th>
    <th>Actions</th>
  </tr>
  <tr class="table-info">
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th></th>
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
	
	$(document).ready(function () {
var table = $('#sepTable').DataTable({
  processing: true,
  serverSide: true,
  ajax: "{{ route('employeeseperationgrid') }}",
  columns: [
    { data: 'first_name', name: 'first_name' },
    { data: 'employee_number', name: 'employee_number' },
    { data: 'lookup_code', name: 'lookup_code' },
    { data: 'relieve_date', name: 'relieve_date' },
    { data: 'relieve_reason', name: 'relieve_reason' },
    { data: 'relieve_status_approve', name: 'relieve_status_approve' },

    {
      data: 'employee_id',
      name: 'actions',
      orderable: false,
      searchable: false,
      render: function (data, type, row) {
        let buttons = '';
        if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
          buttons += `
            <button type="button" class="btn btn-sm btn-success edit-btn" data-id="${data}">
Approve
            </button>`;
        }
        return buttons;
      }
    }
  ]
});



  $('#sepTable thead').on('keyup change', '.column-search', function () {
    let index = $(this).closest('th').index();
    table.column(index).search(this.value).draw();
  });
});	
	
	
// approve 
	
$(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
	
	 var url = "{{URL::to('approveseperation')}}/"+id;
      window.location.href=url;

});	
	
	
	
	
	
	
	
	
	
	
	
	
$(document).ready(function()
{
     $("#grid1").jqGrid({
			url: "{{URL::to('employeeseperationgrid')}}?status='approve'",
			datatype: "json",
			mtype: "GET",

                colModel: [
                { name: "employee_id", label: "id", width: 100,hidden:true},
                { name: "notice_period", label: "notice_period", width: 100,hidden:true},
                { name: "relieve_status", label: "notice_period", width: 100,hidden:true},
                { name: "relieve_id", label: "id", width: 100,hidden:true},
                { name: "first_name", label: "Name", width: 100},
                { name: "employee_number", label: "Employee Number", width: 250,editable:true, editrules:{date:true}},
                { name: "lookup_code", label: "Notice Period", width: 250,editable:true, editrules:{date:true}},
                { name: "relieve_date", label: "Relive Date", width: 250,editable:true, editrules:{date:true}},
                { name: "relieve_reason", label: "Relive Reason", width: 250,editable:true, editrules:{date:true}},
                { name: "relieve_status_approve", label: "Relive Reason by Reporting", width: 250,editable:true, editrules:{date:true}},
                { name: "active", label: "Active", width: 250,editable:true, editrules:{date:true}}
 		],

		iconSet: "fontAwesome",
		rownumbers: true,
		sortname: "employee_id",
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
                
                
		$("#gs_start_date").attr("placeholder","Eg:2018-10-31");
		$("#gs_end_date").attr("placeholder","Eg:2018-10-31");
		$("#grid1").jqGrid("setLabel", "rn", "S.No");
		/** grid for request **/
	 /***** export to pdf start   ****/	
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
              fileName : "Seperation Approval.pdf",
              mimetype : "application/pdf"  
            });
        });
    showcolumn('grid1');
         
          /***** export to pdf end   ****/

        /***** export to Excel Start  ****/

        $(document).on('click',".exportexcel",function() 
        {
            $("#grid1").jqGrid("exportToExcel",{
                includeLabels : true,
                includeGroupHeader : true,
                includeFooter: true,
                fileName : "Seperation Approval.xlsx"

            })	
        });
         /***** export to Excel End  ****/

            /***** Grid Search Clear Start  ****/

        $(".clearsearch").click(function()
	{	
	    var grid = $("#grid1");
	    grid.jqGrid('setGridParam',{search:false});

	    var postData = grid.jqGrid('getGridParam','postData');
	    $.extend(postData,{filters:""});
	    grid.trigger("reloadGrid",[{page:1}]);
	    $('input[id*="gs_"]').val("");
	   
	});
        /***** Grid Search Clear End  ****/
/** approve page open function **/
         $('.approve').on('click', function (event) 
        {
       
            var index = $("#grid1").jqGrid('getGridParam','selrow');
            var employee_id = $("#grid1").jqGrid ('getCell', index, 'employee_id');
            var active = $("#grid1").jqGrid ('getCell', index, 'active');
     
            if(index)
            {
                var url = "{{URL::to('approveseperation')}}/"+employee_id;
                window.location.href=url;
            }
            
            else{
                notyMsgs('info','Please Select A Row');
            }
        });
       
       
         
        

});
	
</script>

@endpush
