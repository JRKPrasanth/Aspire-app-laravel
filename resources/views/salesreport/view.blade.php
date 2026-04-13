<style type="text/css">
.invoice-box {
    background-color: #fff;
    margin: auto;
    padding: 10px;
    border: 1px solid #ccc;
     max-width: unset; 
    width: 100%;
    box-shadow: 3px 3px 4px #ccc;
}
/*table.table-bordered{
    border:1px solid blue;
    margin-top:20px;
  }
table.table-bordered > tbody > .information > tr>th{
    border:1px solid blue;
}
table.table-bordered > tbody > tr > td{
    border:1px solid blue;
}*/
table{
    
    margin-top:20px;
  }
  .table>tbody>tr>td{
  	border: none;
  }
  th,td{
  	font-size: 14px;
  	border: 1px solid #455986;
  }
  th{
  	background: #455986;
  	color: #fff;
  }
</style>

<form>
{{ csrf_field() }}


<div class="card">


<div class="card-header">

<span class="ui_close_btn"><a href="" class="collapse-close pull-right btn-danger sowork_bench" onclick=""></a></span>
</div>



<div class="card-body card-block normalform">


<div class="row">
<div class="col-md-12">

<div class="invoice-box" id="section-to-print">

    <table class="table  table-hover  " cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">SO WORKBENCH DETAILS</h2>
            
			

            <tr class="information">
                <td colspan="6">
                    <table>
						<tr>
							<th>S.NO</th>
							<th>Product Name</th>
							<th>So Qty</th>
							<th>Dispatch Qty</th>
							<th>Invoice Qty</th>
							<th>Return Qty</th>
						</tr>	
                        <tbody>
							<?php foreach($soorder as $k=>$v) {  ?>
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{$v->product_name}}</td>
                                <td>{{$v->qty}}</td>
                                <td><?php $x= isset ($data['DISPATCH_QTY'][$v->product_id]) ?  implode('<br>',$data['DISPATCH_QTY'][$v->product_id]) :'';  echo $x;?></td>
                             
                                <td><?php $z=isset ($data['Invoice_QTY'][$v->product_id]) ?  implode('<br>',$data['Invoice_QTY'][$v->product_id]):''; echo $z; ?></td>
                                <td><?php $a=isset ($data['Return_QTY'][$v->product_id]) ?  implode('<br>',$data['Return_QTY'][$v->product_id]):''; echo $a;?></td>
                            </tr>
<?php } ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            
        </tbody>
    </table>
</div>	
</div>	
</div>
</div>