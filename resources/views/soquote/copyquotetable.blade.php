@extends('layouts.header')
@section('content')
<div class="container-fluid tray tray-center">

<div class="row">
<div class="col-md-12">
<div class="panel panel-visible" id="spy1">
<div class="panel-heading">

<div class="panel-title hidden-xs">
<span class="glyphicon glyphicon-tasks"></span>

<?php if($route=="soquote") { ?>
<a> <button type="button" class="btn add create" value="STANDARD">Create Standard</button></a>
<a> <button type="button" class="btn add create" value="LABOUR">Create Labour</button></a>
<a id="edit"><button type="button" class="btn sec">Edit</button></a>
<a id="view"><button type="button" class="btn vie">View</button></a>
<a id="delete"><button type="button" class="btn del">Delete</button></a>

<a id=""><button type="button" class="btn add convert">Convert to Order</button></a>
<?php }
else {?>
  <a id="edit_approv"><button type="button" class="btn sec">Approve Sales Quote</button></a>
<?php } ?>
<a id="clearsearch"><button type="button" class="btn search">Clear Search</button></a>



</div>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-md-12">
<table id="soquotegrid"></table>
</div>
</div>
@extends('layouts.footer')
</div>

<script type="text/javascript">
$( document ).ready(function() {
  var status="{{ $status }}";
/** Jqgrid Sales Quote Gride Data load Start  **/
$("#soquotegrid").jqGrid({
            url: "{{ URL::to('soquotegriddata') }}?status="+status,
            datatype: "json",
            height:'250',
            mtype: "GET",
            colNames: ["","Quote Name","Quote Date","Quote Number", "Quote Type","Customer Name","Remarks","Status","Quote Status"],
            colModel: [
            { name: "quote_hdr_id",align: "center",hidden:true},
            { name: "quote_name", align: "center" },
            { name: "quote_date", align: "center" },
            { name: "quote_no", align: "center" },
            { name: "quote_type", align: "center" },
            { name: "customer_id", align: "center" },
            { name: "remarks",align: "center" },
            { name: "savestatus",align: "center" },
            { name: "quote_status",align: "center" },
            ],
            //	 data:result,
            iconSet: "fontAwesome",
            rownumbers: true,
            sortorder: "desc",
            threeStateSort: true,
            sortIconsBeforeText: true,
            headertitles: true,
            pager: true,
            rowNum: 10,
            viewrecords: true,
            caption: "Sales Quote" ,
            delOptions: { url: '/SoquoteController/delete' },
            searching: {
            defaultSearch: "cn"
            }
});
/** Jqgrid Sales Quote Gride Data load End  **/

jQuery("#soquotegrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
//For Default Width
$(window).bind('resize', function()
{
$("#soquotegrid").setGridWidth($(window).width()*0.99);
}).trigger('resize');

/** Sales Quote Create Data Start  **/
$(document).on('click','.create',function()
{
var quotetype = $(this).val();
var url="{{ url('soquotecreate') }}/0/"+quotetype;
var red_url="{{ url('soquote') }}";
window.location.replace(url);
});
/** Sales Quote Create Data End  **/

            /*Karthigaa Purpose For Edit Function*/
            $("#edit").click(function()
            {

                    var index = $("#soquotegrid").jqGrid('getGridParam','selrow');
                    var quoteid = $("#soquotegrid").jqGrid ('getCell', index, 'quote_hdr_id');
                    var quotetype = $(this).val();
                    if( quoteid != false )
                    {
                        var type = "statustype";
                        var url ="{{ url('soquotestatus') }}/"+quoteid+"/"+type;
                        $.get(url,function(data)
                        {
                            var data = $.trim(data);
                            console.log(data);
                            if(data !="APPROVED")
                            {

                                window.location.replace('soquotecreate/' +quoteid+'/'+quotetype);
                            }
                            else
                            {
                                notyMsg('error',"<i class='fa fa-exclamation-circle' style='font-size:16px'></i> Approved Quote Cannot Be Edit!!!");
                            }

                        });
                            //window.location.replace('soquotecreate/' +quoteid+'/'+quotetype);
                    }
                    else
                    {
                            alert("Please Select Row");
                    }
            });

            /*Karthigaa Purpose For Edit Function*/


            $("#edit_approv").click(function(){
                    var index = $("#soquotegrid").jqGrid('getGridParam','selrow');
            	var quotehdrid = $("#soquotegrid").jqGrid ('getCell', index, 'quote_hdr_id');
                    var quotetype = $("#soquotegrid").jqGrid ('getCell', index, 'quote_type');
                    var status = $("#soquotegrid").jqGrid ('getCell', index, 'status');


                   if( quotehdrid != false ) {
            if(status!="Approved" && status !="Rejected" ){

            		window.location.replace('salesquoteapprovalview/' +quotehdrid+'/'+quotetype);
            }else{
            alert("Already"+status);
            }
            	}

            	else
            	{
            		alert("Please Select Row");
            	}

            });







                /*Karthigaa Purpose For View Function*/
                $("#view").click(function()
                {
                    //alert('asdsad');
                var index = $("#soquotegrid").jqGrid('getGridParam','selrow');
                var quoteid = $("#soquotegrid").jqGrid ('getCell', index, 'quote_hdr_id');
                if( quoteid != false )
                {
                window.location.replace('soquoteview/' +quoteid);
                }
                else
                {
                alert("Please Select Row");
                }
                });

            $("#clearsearch").click(function()
            {
                        var grid = $("#soquotegrid");
                        grid.jqGrid('setGridParam',{search:false});
                        var postData = grid.jqGrid('getGridParam','postData');
                        $.extend(postData,{filters:""});
                        grid.trigger("reloadGrid",[{page:1}]);
            });

            /*Maruthu Purpose For Delete Function*/
            $("#delete").click(function()
            {
            var index = $("#soquotegrid").jqGrid('getGridParam','selrow');
            var quoteid = $("#soquotegrid").jqGrid ('getCell', index, 'quote_hdr_id');
            if( quoteid != false )
            {
                swal({
                title: "Are you sure?",
                text: "You want to delete!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: !1,
                closeOnCancel: !1
            },function(e) {

            if(e == true)
            {

                var url ="{{ url('soquotedelete')}}/"+quoteid;
                var red_url="{{ url('soquote') }}";
                $.get(url,function(data)
                {
                    var data = $.trim(data);
                    console.log(data);
                    if(data == "1")
                    {
                        notyMsg('error',"You Can't delete , Sales  Quote Used in SomeWhere!!!",red_url);
                        $('.cancel').trigger('click');

                    }
                    else
                    {
                        notyMsg('success','Deleted Successfully!!!',red_url);
                        setTimeout(function(){
                        window.location.href=red_url;
                        }, 1500);
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
            else{
                alert("Please Select Row");
            }
            });



            /** Sales Quote CONVERT  Data Start  **/
            $(".convert").click(function()
            {
                    var index = $("#soquotegrid").jqGrid('getGridParam','selrow');
                    var quoteid = $("#soquotegrid").jqGrid ('getCell', index, 'quote_hdr_id');

                    if( quoteid != false )
                    {
                        var type = "savestatus";
                        var url ="{{ url('soquotestatus')}}/"+quoteid+"/"+type;
                        $.get(url,function(data)
                        {

                                var data = $.trim(data);
                                if(data =="SAVE")
                                    window.location.replace('salesorderfromqo/' +quoteid);
                                else
                                    notyMsg('warning',"<i class='fa fa-exclamation-circle' style='font-size:16px'></i> Please Save Enquiry First. Which is in "+data+" Status!!!");
                        });

                    }
                    else
                    {
                            alert("Please Select Row");
                    }
            });
            /** Sales Quote CONVERT  Data End  **/

    /*Karthigaa Purpose For View Function*/
       $("#view").click(function(){
        alert('asd');
	var index = $("#soquotegrid").jqGrid('getGridParam','selrow');
	var quoteid = $("#soquotegrid").jqGrid ('getCell', index, 'quote_hdr_id');
	if( quoteid != false )
	{
            window.location.replace('soquoteview/' +quoteid);
	}
	else
	{
            alert("Please Select Row");
	}
});

/***** Delete Row ********/
/***** Karthigaa Purpose For CLEAR search ********/
$("#clearsearch").click(function()
{
var grid = $("#soquotegrid");
grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
	});
});
/*End*/


</script>
@endsection
