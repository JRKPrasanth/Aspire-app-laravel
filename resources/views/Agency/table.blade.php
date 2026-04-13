@extends('layouts.header')
@section('content')
<h3 class="text-danger">Agency</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3"></div>

<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="AgencyTbl" class="table table-bordered table-striped w-100">
        <thead>
  <tr class="table-warning">
    <th>Agency Name</th>
    <th>Address</th>
    <th>Email</th>
    <th>Mobile</th>
    <th>Actions</th>
  </tr>
  <tr class="table-info">
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th></th>
  </tr>
</thead>

        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

@endsection
@push('scripts')
<script>
	
$(document).ready(function() {

    var table = $('#AgencyTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('agencyData') }}",
      columns: [
        { data: 'agency_name', name: 'agency_name' },
        { data: 'address', name: 'address' },
        { data: 'email', name: 'email' },
        { data: 'mobile_no', name: 'mobile_no' },
        {
          data: 'agency_id',
          name: 'actions',
          orderable: false,
          searchable: false,
		  className: 'text-center',
          width: '140px', 
          render: function (data, type, row) {
			   let buttons = '';
			  console.log(window.toolbarButtons);
			    if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                    buttons += `
					<button  class="btn btn-sm btn-info me-1 edit-btn" data-id="${data}"><i class="bi bi-pencil"></i></button>`;
           		 }
			  
			     if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                    buttons += `<button class="btn btn-sm btn-warning me-1 view-btn" data-id="${data}"><i class="bi bi-eye"></i></button>`;
					}

			   if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
				buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${data}" data-url="{{ url('companydelete') }}"><i class="bi bi-trash"></i>  </button>`;
            }
			  
	return buttons;
          }
  
        }
      ]
    });
  
    // Individual column search
    $('#AgencyTbl thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });	
	
	
	// Add create button purpose
	      $(document).ready(function () {
        if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
          $('#toolbar-container').append(`
            <button class="btn btn-primary create me-2">Create
              <i class="bi bi-plus-circle"></i> 
            </button>
          `);
        }
      });
	
	// create function
	 $(".create").click(function()
{
  var url="{{ URL::to('agencycreate/0')}}";
    window.location.replace(url);
});
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('agencycreate') }}/" + id;
    window.location.href = url;
    });
	
	//view function
$(document).on('click', '.view-btn', function () {
  const id = $(this).data('id');
  const url = "{{ url('agencyview') }}/" + id;
  window.location.href = url;
});

	// delete function
	let deleteId = null; 

	$(document).on('click', '.delete-btn', function () {
		deleteId = $(this).data('id');
		$('#globalDeleteModal').modal('show'); 
	});

	$('#globalConfirmDeleteBtn').on('click', function () {
		if (deleteId) {
				$.ajax({
					url: "{{ url('agencydelete') }}/" + deleteId,
					type: "GET",
					success: function (response) {
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Deleted successfully!', 'success');
						$('#AgencyTbl').DataTable().ajax.reload();

					},
					error: function (xhr) {
						 $('#globalDeleteModal').modal('hide');
						const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
						showCustomAlert(errorMsg, 'error');
					}
				});
		}
	});
	
	
	
	
	
	
	
	
	
	
	
$( document ).ready(function() {
/*deepika purpose: function to load data using jqgrid*/
$("#grid1").jqGrid({
url: "{{URL::to('agencyData')}}",
datatype: "json",
mtype: "GET",
	 colModel: [

	{ name: "agency_id", label: "id",hidden:true },
	{ name: "agency_name", label: "Agency Name"},
	{ name: "address", label: "Address"},
    { name: "email", label: "Email"},	
    { name: "mobile_no", label: "Mobile"}		

		],iconSet: "fontAwesome",
    rowNum: 10,
    rowList: [10,20,100,1000,2000],
    sortname: "agency_id",
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
jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
/*end*/
	/*deepika purpose: function to set label for sno*/
	$("#grid1").jqGrid("setLabel", "rn", "S.No");/*end*/
	/*deepika purpose: function to show hidden columns ref showcolumn in header blade*/	
showcolumn('grid1');
	/*deepika purpose:export excel & pdf*/
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
  fileName : "Agency.pdf",
  mimetype : "application/pdf"  
});
});
 $(document).on('click',".exportexcel",function() {	
	$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Agency.xlsx"
    					
				});		
				});		
	/*end*/
	/*deepika purpose: function :redirect to  create new record*/
$(document).on('click','.create',function()
{
var url="{{ URL::to('agencycreate') }}";
var red_url="{{ URL::to('agency') }}";
window.location.replace(url);
});
/*end*/
	/*deepika purpose: function :redirect to  update the created record*/
	$(".edit").click(function(){
  var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
  var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'agency_id');
//  alert(cellValue);
  if( gr )
  {
    var url = "{{ url('agencycreate') }}";
                var editUrl = url + '/' + cellValue;
              //  alert(editUrl);
    window.location.replace(editUrl);
  }
  else
  {
  notyMsg("info","Please Select Row");
  }
});
$(document).on('click','.del',function(e){
        e.preventDefault();
        var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
        var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'agency_id');
        if(cellValue)
        {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: !0,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }, function(e) {
               var red_url     ="{{ url('agency') }}";
                 if(e == true)
                 {
                    $.get('agencydelete/'+cellValue, function(data,status)
                    {
                        var data = $.trim(data);
                      //  alert(data);
                        if(data =='0')
                        {
                            notyMsg('success','Deleted Successfully',red_url);
                       
                            setTimeout(function(){
                                 var red_url     ="{{ url('agency') }}";
                            window.location.href=red_url;

                            }, 100);
                        }
                        if(data =='1')
                        {
                            notyMsg('error',"You Cant't delete  Used in SomeWhere");
                             $('.clearsearch').trigger('click');
                            setTimeout(function(){
                            $("#grid1")[0].triggerToolbar();
                            }, 1500);
                        }
                    });
                }
                else{
                  $('.apply').css('display','none');
                   $('.clearsearch').trigger('click');
                  swal("Cancelled");
                }
            })
             $('.apply').css('display','none');
        }
        else
        {
            notyMsg('info',"Please Select a Row");
        }
   });

//Ajith purpose : function  clearsearch in grid  
 $(".clearsearch").click(function(){
            var grid = $("#grid1");
            grid.jqGrid('setGridParam',{search:false});

            var postData = grid.jqGrid('getGridParam','postData');
            $.extend(postData,{filters:""});

            grid.trigger("reloadGrid",[{page:1}]);
              $('input[id*="gs_"]').val("");
              $('select[id*="gs_"]').select2('val',['']);
    });
 /*end*/
	/*Ajith purpose: function :redirect to show the created records*/
$('#viewdata').click(function()
{
  var gr=$('#grid1').jqGrid('getGridParam','selrow');
  var cellValue = $("#grid1").jqGrid ('getCell', gr, 'agency_id');
  if(gr)
  {
     var url="{{URL::to('agencyview')}}/"+cellValue;
     window.location.replace(url);
  }
  else
  {
     notyMsg("info","Please Select Row");
  }
});
 /*end*/



});
  </script>
@endpush
