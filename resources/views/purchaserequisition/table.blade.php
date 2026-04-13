@extends('layouts.header')
@section('content')
<h3 class="text-danger">Purchase Requisition</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
<div class="container mt-4">
  <table id="PurchaseTbl" class="table table-bordered table-striped w-100">
    <thead>
      <tr class="table-warning">
        <th>Requisition Number</th>
        <th>Requisition Date</th>
        <th>Requestor Name</th>
        <th>Requisition Status</th>
        <th>Requisition Source</th>
        <th>Project Name</th>
        <th>Remarks</th>
        <th>Actions</th>

      </tr>
      <tr class="table-info">
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
         <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
      </tr>
    </thead>
    <tbody>
      {{-- DataTable will populate via AJAX --}}
    </tbody>
  </table>
</div>
</div>



@endsection
@push('scripts')

<script>

	
$(document).ready(function () {

	var status ="{{$status}}";
  var pageMethod ="{{$pageMethod}}";



  var table = $('#PurchaseTbl').DataTable({
    processing: true,
    serverSide: true,
    ajax: "getPurchaserequisitionData?status="+status+"&page_status="+pageMethod,
    columns: [
      { data: 'requisition_no', name: 'requisition_no' },
      { data: 'requisition_date', name: 'requisition_date' },
      { data: 'first_name', name: 'first_name' },
      { data: 'requisition_status', name: 'requisition_status' },
      { data: 'requisition_source', name: 'requisition_source' },
      { data: 'project_name', name: 'project_name' },
      { data: 'remarks', name: 'remarks' },


      
      {
        data: 'requisition_hdr_id',
        name: 'actions',
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
            let buttons = '';
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'convert')) {
                    buttons += `
				<button type="button" class="btn btn-sm btn-primary convert-btn"
				  data-id="${row.requisition_hdr_id}"> Convert
				  
				</button>`;
            }
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                    buttons += `
					<button type="button" class="btn btn-sm btn-warning view-btn"
					  data-id="${row.requisition_hdr_id}">
					  <i class="bi bi-eye"></i>
					</button>`;
            }
            return buttons;
          }
          
      }
    ]
  });


  $('#PurchaseTbl thead').on('keyup change', '.column-search', function () {
    let index = $(this).closest('th').index();
    table.column(index).search(this.value).draw();
  });
});
	
	
	
$( document ).ready(function() {
    
/* Purpose For Edit Function*/
$("#edit").click(function(){
        var index = $("#poreqgrid").jqGrid('getGridParam','selrow');
	var reqid = $("#poreqgrid").jqGrid ('getCell', index, 'requisition_hdr_id');
	var reqstatus = $("#poreqgrid").jqGrid ('getCell', index, 'requisition_status');

  if( index )
  {
    if(reqstatus!="APPROVED" && reqstatus!="INITIATED"  &&  reqstatus!="COMPLETED")
    {
      window.location.replace('purchaserequisitioncreate/' +reqid);
    }
    else
    {
       notyMsg("info","Submitted Or Approved Or Completed Requisition Unable to Edit ");
    }

  }
	else
	{
		notyMsg("info","Please Select a Row");
	}

});


  $(document).on('click','.create',function(){
    var status = $(this).val();
    window.location.replace('purchaserequisitioncreate/0'+'/'+status);
  });



	$('.convert').click(function(){
  var gr=$('#poreqgrid').jqGrid('getGridParam','selrow');
  var id = $("#poreqgrid").jqGrid ('getCell', gr, 'requisition_hdr_id');
  if(gr)
  {
      window.location.replace('purchasequtoetorequestioncreate/' +id+'/0?status=REQUISITION');
  }
  else
  {
 notyMsg("info","Please Select a Row");
  }
});


$('.approval').click(function(){
    var gr=$('#poreqgrid').jqGrid('getGridParam','selrow');
    var id = $("#poreqgrid").jqGrid ('getCell', gr, 'requisition_hdr_id');
  if(gr)
  {
      window.location.replace('purchaserequisitioncreate/' +id+'?approve_status=approved');
  }
  else
  {
    notyMsg("info","Please Select a Row");
  }
});


	$('.convert1').click(function(){
  var gr=$('#poreqgrid').jqGrid('getGridParam','selrow');
  var id = $("#poreqgrid").jqGrid ('getCell', gr, 'requisition_hdr_id');
  if(gr)
  {
      window.location.replace('purchaserequestiontopocreate/' +id+'/0?status=REQUESTION');
  }
  else
  {
    notyMsg("info","Please Select a Row");
  }
});


	$('.convert2').click(function(){
  var gr=$('#poreqgrid').jqGrid('getGridParam','selrow');
  var id = $("#poreqgrid").jqGrid ('getCell', gr, 'requisition_hdr_id');
  if(gr)
  {
      window.location.replace('purchaseenquirytorequestioncreate/' +id+'/0?status=REQUISITION');
  }
  else
  {
     notyMsg("info","Please Select a Row");
  }
});



  $('.copyreq').click(function(){
    var index = $("#poreqgrid").jqGrid('getGridParam','selrow');
	  var reqid = $("#poreqgrid").jqGrid ('getCell', index, 'requisition_hdr_id');
    if(index)
    {

              window.location.replace('purchasecopyrequisition/'+reqid+'/'+'COPYREQUISITION');

     }
      else
	    {
            notyMsg("info","Please Select a Row");
	    }
  });

    /*Karthigaa Purpose For View Function*/
       $("#view").click(function(){
	var index = $("#poreqgrid").jqGrid('getGridParam','selrow');
	var reqid = $("#poreqgrid").jqGrid ('getCell', index, 'requisition_hdr_id');
  var url = '{{$pageMethod}}';
	if( index )
	{
		window.location.replace('purchaserequisitionview/' +reqid+'?return='+url);
	}
	else
	{
		notyMsg("info","Please Select a Row");
	}
});

$("#delete").click(function(){

  var gr = jQuery("#poreqgrid").jqGrid('getGridParam','selrow');
  var cellValue = jQuery("#poreqgrid").jqGrid ('getCell', gr,'requisition_hdr_id');
  if( gr )
  {
    swal({
      title: "Are you sure?",
      text: "You want to delete!",
      type: "warning",
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes",
      cancelButtonText: "No",
      closeOnCancel:!1
    }, function(e) {
    if(e == true)
      {
        var url ="{{ URL::to('purchaserequisitiondelete') }}/" +cellValue;
        $.get(url,function(data)
        {
          var data = $.trim(data);
          var red_url ="{{ URL::to('purchaserequisition') }}";

          if(data =='0')
          {
            notyMsg('success','Deleted Successfully');
            $('.clearsearch').trigger('click');
            setTimeout(function(){
            $("#poenquirygrid")[0].triggerToolbar();
            }, 1500);
          }
          if(data=='2')
          {
            notyMsg('info',"You Cant't delete , Requisition Used in SomeWhere");
            $('.clearsearch').trigger('click');
            setTimeout(function(){
              $("#poreqgrid")[0].triggerToolbar();
            }, 1500);
          }
        });
      }
      else
      {
        $('.apply').css('display','none');
        $('.clearsearch').trigger('click');
        swal("Cancelled");
      }
    });
  $('.apply').css('display','none');
  }
  else
  {
  notyMsg("info","Please Select a Row");
  }
});


});
	
</script>

@endpush
