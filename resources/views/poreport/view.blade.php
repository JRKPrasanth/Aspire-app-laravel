
@extends('layouts.header')
@section('content')



	<div class="row">
	<div class="col-lg-12 col-md-12">
	</div>
	</div>

<?php //dd($values);?>

<div class="row">
	<div class="col-lg-1">
	</div>


			<div class="col-lg-12">
				<div class="card">
					<div class="card-header" align="center">
					<h4><b>Workorder Pending List</b></h4>

                                        </div>
					<div class="col-md-2"></div>
<div class="card-body card-block">
	<form>
		{{ csrf_field() }}
			<table  class="table table-bordered table-striped " style="width:100%">
				<tr>
				<th>Sno</th>
				<th>Product Name</th>
                <th>Batch Number</th>
				<!--< th>Workorder Qty</th> -->
				<th>Production Quantity</th>

				<th></th>
				</tr>
				<?php if(!empty($workorder)){ ?>
                              <?php $a=0;
			 foreach($workorder as $key=>$value) {
                          $a+=1;   ?>

				<tr>
				<td>{{$a}}</td>
				<!-- <td>{{$key}}</td> -->
				<td>{{$workorder_name[$key]}}</td>
				<td><?php echo implode(", ", $value); 
					$p_id=implode(",",array_unique($production_hdr_id[$key]));
					 
					?></td>
					<input type="hidden" name="production_hdr_id" id="production_hdr_id" value={{$p_id}}>
				<!-- <td><?php echo implode(", ", $qty[$key]); ?></td> -->
				<td><?php echo array_sum($qty[$key]);  ?></td>
					<td><?php if($status[$key]=="1"){ ?><center><b style="color:black;">JOB CARD IN PROCESS</b></center><?PHP } else {?><button type="button" class="btn add saveform create_jc" data-productid='{{$key}}' data-qty=<?php echo array_sum($qty[$key]);  ?> > Create Job Card </button><?php } ?></td>




				</tr>

<?php } ?>

<?php }else { ?>
<tr><td colspan="5" align="center"><h4>There is No Pending Workorder..!</h4></td></tr>
<?php } ?>
</table>
</form>
</div>

</div>
</div>
<style>

.table-striped {
     background-color: #c2cae6;
}
.card{
	top:5px;
}
.card-header {
    background-color: #ccc;
    margin-bottom: 0;
    font-weight: normal;
    border-bottom: 1px solid #000;
}
.card-body {
    padding: 9px 15px;
}
table tbody tr:nth-child(1) {
    color: #fff;
    background: #07234e !important;
}
.table-bordered>tbody>tr>td,.table-bordered>tbody>tr>th {
    border: 1px solid #07234e;
}
.table>tbody>tr>td{
	font-size: 14px;
}
.table-bordered>tbody>tr>td {
     border: 1px solid #07234e;
}
</style>
<script type="text/javascript">
	$(document).ready(function(){
	$('.create_jc').click(function(){
        var id = $(this).data('productid');
        var qty = $(this).data('qty');
        var production_hdr_id = $('#production_hdr_id').val();
       //alert(id+" "+qty);
            var url = "{{ URL::to('jobcardcreate') }}";
                var editUrl = url + '/0'+'?prod_id='+id+'&qty='+qty+'&production_hdr_id='+production_hdr_id;
		window.location.replace(editUrl);

    });
});
</script>

@endsection
