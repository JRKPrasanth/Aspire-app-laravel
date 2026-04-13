@extends('layouts.header')
@section('content')

	<h3 class="heads"> SubInventory Transfer </h3>

      <form id="subinventorytransferform" class="subinventorytransferform" method="POST" action="">
		  <div class="col-md-12">

			  <div class="col-md-6 divalgin">
				  <div class="form-group">
                    <label for="trxdate" class=" form-control-label">Trx Date</label>
                    <input id="trx_date" class="form-control trx_date" placeholder="" name="trx_date" type="text" value="" readonly>
                 </div>

				  <div class="form-group divalgin">
                    <label for="from_organization" class=" form-control-label">From Organization</label>
                    <select name='frm_org' rows='3' id='frm_org_id' class='selectpicker frm_org_id' data-show-subtext="true" data-live-search="true"required ></select>
                 </div>

				   <div class="form-group divalgin">
                    <label for="from_subinventory" class=" form-control-label">From SubInventory</label>
              <select name='frm_subinv' rows='3' id='frm_subinv_id' class='selectpicker frm_subinv_id' data-show-subtext="true" data-live-search="true"required  ></select>
                 </div>

				  <div class="form-group divalgin">
                    <label for="from_locator" class=" form-control-label">From Locator</label>
                    <select name='frm_loc' rows='3' id='frm_loc_id' class='selectpicker frm_loc_id' data-show-subtext="true" data-live-search="true" required ></select>
                 </div>



			  </div>

			  <div class="col-md-6">
				  <br><br><br><br>

				  <div class="form-group divalgin">
                    <label for="from_organization" class=" form-control-label">To Organization</label>
                    <select name='to_org' rows='3' id='to_org_id' class='selectpicker  to_org_id' data-show-subtext="true" data-live-search="true"required ></select>
                 </div>

				   <div class="form-group divalgin">
                    <label for="to" class=" form-control-label">To SubInventory</label>
              <select name='to_subinv' rows='3' id='to_subinv_id' class='selectpicker to_subinv_id' data-show-subtext="true" data-live-search="true"required  ></select>
                 </div>

				  <div class="form-group divalgin">
                    <label for="to" class=" form-control-label">To Locator</label>
              <select name='to_subinv' rows='3' id='to_loc_id' class='selectpicker to_loc_id' data-show-subtext="true" data-live-search="true"required  ></select>
                 </div>



			  </div>

			    <div class=" col-md-4" > </div>
		  </div>
		      <div class="col-md-offset-5 col-md-7">
				<div class="col-md-8">
				 <fieldset>
					<div class="form-group">
						<div class="col-md-7">
							<a href="javascript://ajax" class=" tips btn btn-sm btn-white trading"  accesskey="n"><i class="fa fa-plus"></i>Trading</a>
						</div>
					</div>
				 </fieldset>
			   </div>
            </div>
		   		<input type="hidden" id="productid" class="productid" name="productid" value=""/>
		 		<input type="hidden" id=searchdata class="searchdata" name="searchdata" value=""/>
	  </form>


<div class="col-md-12">

<table id="grid1"></table>
</div>

<script src="{{ asset('js/jquery.jCombo.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {

		$("#frm_org_id").jCombo("{{ URL::to('jcomboform?table=m_organizations_t:organization_id:organization_name') }}&order_by=organization_name asc",
{selected_value:""});

		$("#to_org_id").jCombo("{{ URL::to('jcomboform?table=m_organizations_t:organization_id:organization_name') }}&order_by=organization_name asc",
{selected_value:""});

		$("#frm_subinv_id").jCombo("{{ URL::to('jcomboform?table=m_subinventory_t:subinventory_id:subinventory_name') }}&order_by=subinventory_name asc",
{selected_value:""});

		$("#to_subinv_id").jCombo("{{ URL::to('jcomboform?table=m_subinventory_t:subinventory_id:subinventory_name') }}&order_by=subinventory_name asc",
{selected_value:""});

		$("#frm_loc_id").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocators_id:locator_name') }}&order_by=locator_name asc",
{selected_value:""});

		$("#to_loc_id").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocators_id:locator_name') }}&order_by=locator_name asc",
{selected_value:""});


var data="{{$datas}}";
		var id;
var result = jQuery.parseJSON(data.replace(/&quot;/g, '"' ));
$("#grid1").jqGrid({

 url: "getsubinvData",
	datatype: "json",
	mtype: "GET",
	height: 300,
	 colModel: [
	{ name: "product_id", label: "id",hidden:true },
	{ name: "concatenated_product", label: "Product Name" ,editable:true, editrules:{date:true}},
	{ name: "group_name", label: "Product Group",editable:true, editrules:{date:true}},
	{ name: "category_name", label: "Product Category",editable:true, editrules:{date:true}},
	{ name: "qoh_trx_qty", label: "Qoh Trx Qty",editable:true, editrules:{date:true}},
	{ name: "frm_trx_qty", label: "From Trx Qty",editable:true, editrules:{date:true}},
	{ name: "to_trx_qty", label: "To Trx Qty",editable:true, editrules:{date:true}},
	],
    iconSet: "fontAwesome",
            rowNum: 10,
        rowList: [10,20,50,100,250,500,1000],
        sortorder: "asc",
        viewrecords: true,
        gridview: true,
        rownumbers:true,
        caption: "",
          pager: true,
        searching: {
            defaultSearch: "cn"
        }


	});
		jQuery("#grid1").jqGrid('filterToolbar','editRow', id, {keys:  true},{stringResult: true,searchOnEnter : false});


		$(window).bind('resize', function() {
        $("#grid1").setGridWidth($(window).width()*0.95);
    }).trigger('resize');







});
</script>
@endsection
