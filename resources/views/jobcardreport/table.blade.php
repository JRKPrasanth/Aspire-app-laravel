@extends('layouts.header')
@section('content')

<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                      Journal Entry
                                    </a>
        </h4>
  </div>
</div>




<div class="panel panel-visible" id="spy1">
	<div class="row">
	<div class="col-md-12">
<?php include('toolbar.php'); ?>
<button type='button' href='' class='btn clearsearch'>Clear Search </button>
<button type='button' id="showcolumn" value="1" class='btn showcolumn showcolumns'> Showcolumn </button>
</div>
</div>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
	<div class="col-md-12">
<table id="journalentrygrid"></table>
</div>
</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>


</div>


<script type="text/javascript">
$( document ).ready(function() {
 var status ="{{$status}}";
$("#journalentrygrid").jqGrid({
//url: "getJournalentryData",
url: "getJournalentryData?status="+status,
datatype: "json",
mtype: "GET",
	 colModel: [
	{ name: "journal_entry_id", label: "id",hidden:true },
        { name: "journal_name", label: "Journal Name" },
	{ name: "journal_date", label: "Journal Date" },
        { name: "journal_type", label: "Journal Type"},
//        { name: "journal_reference", label: "Journal Reference"},
        { name: "journal_status", label: "Journal Status"},
	
		],
        iconSet: "fontAwesome",
        rowNum: 10,
        rowList: [10,20,50,100],
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        rownumbers:true,
        caption: "",
	autowidth:true,
        pager: true,
        searching: {
            defaultSearch: "cn"
        }
      });
jQuery("#journalentrygrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
showcolumn('journalentrygrid');
$("#edit").click(function(){
	var gr = jQuery("#journalentrygrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#journalentrygrid").jqGrid ('getCell', gr, 'journal_entry_id');
        var status = jQuery("#journalentrygrid").jqGrid ('getCell', gr, 'journal_status');
        
        
	if( cellValue != false )
	{
              if( status != "POSTED" && status != "APPROVED" && status != "SUBMITTED"){
		var url = "{{ url('journalentrycreate') }}";
                var editUrl = url + '/' + cellValue;
		window.location.replace(editUrl);
                }
            else{
               notyMsg('error',"<i class='fa fa-exclamation-circle' style='font-size:16px'></i> Approved,Submitted,Posted JOURNALS Cannot Be Edit!!!");
            }
        }
	else
	{
	notyMsg("info","Please Select Row");
	}
});
  /*Karthigaa Purpose For cancel*/
      $("#create").click(function(){
	var url="{{  URL::to('journalentrycreate')}}";
	window.location.replace(url);

	});
        
$(document).on('click','#approve',function(){
    
        var index = $("#journalentrygrid").jqGrid('getGridParam','selrow');
    var jid = $("#journalentrygrid").jqGrid ('getCell', index, 'journal_entry_id');
       var journal_status = $("#journalentrygrid").jqGrid ('getCell', index, 'journal_status');
//       alert(journal_status);
       if( jid != false )
       {
         window.location.replace('journalapproval/' +jid+'/'+journal_status);
       }
       else
	     {
	       	notyMsg("info","Please Select Row");
     	}

  });


$("#delete").click(function()
{
	var gr = jQuery("#journalentrygrid").jqGrid('getGridParam','selrow');
	var id = jQuery("#journalentrygrid").jqGrid ('getCell', gr, 'journal_entry_id');
	if(id != false ){
		swal({
                title: "Are you sure?",
                text: "You want to delete!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: !1,
                //timer: 2e3,
                closeOnCancel: !1
            }, function(e) {

			if(e == true)
			{
				var url ="{{ url('accountperiodsdelete') }}/" +id;
				$.get(url,function(data)
				{
					var data = $.trim(data);
					var red_url ="{{ url('accountperiods') }}";
					var data = $.trim(data);
					if(data =='0')
					{
						notyMsg('success','Deleted Successfully!!!',red_url);
						setTimeout(function(){
						window.location.href=red_url;
						}, 1500);
					}
					if(data =='2')
					{
						notyMsg('error',"You Cant't delete , Account Periods Used in SomeWhere!!!",red_url);
					}

				});
			}
			else
			{
                            $('.apply').css('display','none');
                            swal("Cancelled");
			}
            })
		$('.apply').css('display','none');
	}
	else
	{
	notyMsg("info","Please Select Row");
	}
});

$('#view').click(function(){
  var gr=$('#journalentrygrid').jqGrid('getGridParam','selrow');
  var cellValue = $("#journalentrygrid").jqGrid ('getCell', gr, 'journal_entry_id');
  if(cellValue != false){
     window.location.replace('journalentryview/' +cellValue);
  }
  else
  {
  notyMsg("info","Please Select Row");
  }
});

/*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#journalentrygrid");
            grid.jqGrid('setGridParam',{search:false});

            var postData = grid.jqGrid('getGridParam','postData');
            $.extend(postData,{filters:""});
            grid.trigger("reloadGrid",[{page:1}]);
    });
/*end*/
	$(window).scroll(function() {
if ($(this).scrollTop() >150){
    $('.header-sticky').addClass("sticky");
  }
  else{
    $('.header-sticky').removeClass("sticky");
  }
});

});
  </script>
@endsection
