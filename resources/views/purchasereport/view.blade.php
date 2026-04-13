<!DOCTYPE html>
<html lang="en">




<div class="card">


<div class="card-header">
<i class="fa fa-print" aria-hidden="true" style="float: right;
    margin: -0.1% 1%;
    font-size: 26px; padding: 5px; border: 1px solid #000;"></i>
<span class="ui_close_btn"><a href="" class="collapse-close pull-right btn-danger work_bench" ></a></span>
</div>





<div class="col-md-12" id="fa-print">
   <h2 class="heads1">PO WORKBENCH DETAILS (<?php echo $poorder[0]->po_number;   ?>) </h2>
    <table class="table table-striped" style="width:100%;" border="1">
        <thead>

         
						<tr>
							<th>S.No</th>
							<th>Product Name</th>
							<th>Po Qty</th>
							<th>Received Qty</th>
							<th>Balance Qty</th>
							<th>Inspection Qty</th>
							
							<th>Invoice Qty</th>
							<th>ReturnToVendor Qty</th>
						</tr>	
						
						
						</thead>
                        <tbody>
					
							
							<?php   foreach($poorder as $k=>$v) {  ?>

                            <tr class="highlight">
                                <td>{{$k+1}}</td>
                                <td>{{$v->product_name}}</td>
                                <td>{{$v->qty}}</td>
                                <td><?php $x= isset ($data['GRN_QTY'][$v->product_id]) ?  implode('<br> <br>',$data['GRN_QTY'][$v->product_id]) :'';  echo $x;
							
								
			if(!empty($x)){
				
				
$a=array_sum($data['receive_QTY'][$v->product_id]);

$b=$v->qty;
$c=$b-$a;
$d= $c > 0 ? $c :0;

			}else {
$d=$v->qty;

			}		
								
								?></td>
                                <td><?php $y=isset ($data['Pending_QTY'][$v->product_id]) ?  implode('<br> <br> ',$data['Pending_QTY'][$v->product_id]):''; echo $y; ?></td>
                                <td><?php $y=isset ($data['QC_QTY'][$v->product_id]) ?  implode('<br> <br> ',$data['QC_QTY'][$v->product_id]):''; echo $y; ?></td>
                                <td><?php $z=isset ($data['Invoice_QTY'][$v->product_id]) ?  implode('<br> <br>',$data['Invoice_QTY'][$v->product_id]):''; echo $z; ?></td>
                                <td><?php $a=isset ($data['Return_QTY'][$v->product_id]) ?  implode('<br> <br>',$data['Return_QTY'][$v->product_id]):''; echo $a;?></td>
                                
                                
								
                             
                           
                            </tr>
<?php } ?>

                        </tbody>
                    </table>
        
</div>



</html>
  <iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>

<script type="text/javascript">
$( document ).ready(function() {
$( document ).on('click','.fa-print',function(){


     window.frames["print_frame"].document.body.innerHTML = document.getElementById("fa-print").innerHTML;
         window.frames["print_frame"].window.focus();
         window.frames["print_frame"].window.print();


})
});
</script>