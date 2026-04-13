<style type="text/css">
.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}
.table-responsive > .table-bordered {
        text-align: center;
    border: 0;
}

.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}

.table.table-bordered thead {
    border: 1px solid #f2f2f2;
    border-bottom: none;
}

.table-responsive tbody tr > td:first-child {
    display: table-cell;
}
.card{
    border-radius: 5px;
    margin-bottom: 40px;
}
.card-body {
    padding: 14px 10px;
}
table tbody tr:nth-child(1) {
    background: none;
}
tbody{
    vertical-align: middle;
    font-size: 13px;
    line-height: 1;
    white-space: nowrap;
}
table-bordered td {
    border: 1px solid #f2f2f2;
}
.table th, .table td {
    padding: 18px 30px;

}
.table>thead:first-child>tr:first-child>th {
   color: #000;
    font-weight: bolder;
    text-align: center;
    background: none;
    padding: 10px 0px 10px 0px;
}
.table .progress{
    text-align: center;
    height: 8px;
    border-radius: 3px;
    width: 75%;
}

.table.table-bordered thead tr th {
    border-left: none;
    border-right: none;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #f2f2f2;
}
.table-bordered th, .table-bordered td {
    border: 1px solid #f2f2f2;
}
.table>tbody>tr>td{
    padding: 15px;

    vertical-align: middle;
    border-top: 1px solid #f2f2f2;
}

</style>



<div class="row grid-margin1">
    <div class="col-md-12">
            <div class="col-md-12 grid-margin">
              <div class="card">
                <div class="card-header" align="center">
                  <h5>Purchase Order Details For - {{$result[0]->supplier_id}}<h5>
                  <span class="ui_close_btn"><a class="collapse-close pull-right btn-danger close_btn"></a></span>
              </div>
                <div class="card-body">

                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>
                            Status
                          </th>
                          <th>
                           Po Date
                          </th>

                          <th>
                            Delivery Date
                          </th>
                          <th>
                            PO Number
                          </th>
                          <th>
                            Vendor Name
                          </th>
                          <th>
                            Amount
                          </th>

                        </tr>
                      </thead>
                      <tbody>
                       <?php // dd($result);
						  foreach($result as $key=>$value) { ?>
						  <tr >
								  <?php  if($value->po_status=="DRAFT"){ $style="label-default"; }
							            elseif($value->po_status=="APPLY CHANGES"){ $style="label-info"; }
							            elseif($value->po_status=="INITIATED"){ $style="label-warning"; }
							            elseif($value->po_status=="APPROVED"){ $style="label-success"; }
							            elseif($value->po_status=="REJECTED"){ $style="label-danger"; }
							  ?>

							   <td>
                            <div class="label label-table {{$style}}">{{$value->po_status}}</div>
                          </td>
							  <td>
							  <?php $sec = strtotime($value->po_date);  echo date("d-m-Y", $sec); ?>
							  </td>

							   <td>
							  <?php $sec = strtotime($value->delivery_date);  echo date("d-m-Y", $sec); ?>
							  </td>
							   <td class="font-weight-medium">
                          <a class="btn-link po_number " data-value="{{$value->po_hdr_id}}">{{ $value->po_number }}</a>
                          </td>
							  <td>
								 {{ $value->supplier_id }}
							  </td>

							  <td>
								 {{ $value->po_grand_total }}
							  </td>


						  </tr>
                       <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div>
            </div>
          </div>


      </div>
   <div class="modal fade" id="myModal" role="dialog" style="margin:2%;">
<div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body dialogue">

        </div>

      </div>

    </div>
  </div>


<script type="text/javascript">
$( document ).ready(function() {

	$(document).on('click','.po_number', function(ev){
			var po_number=$(this).data('value');
		var url="{{ URL::to('purchaseorderview')}}/"+po_number+"/?report='report'";
		$.get(url,function(data){
			$(".dialogue").html(data);
			$('#myModal').modal('show');
		});
	});
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
