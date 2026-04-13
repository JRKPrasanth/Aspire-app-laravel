@extends('layouts.header')
@section('content')
<body>
<div class="container main_container">	
<!------------------------- breadcrumbs start here --------------------------->
<div class="row">
<div class="col-lg-12 col-md-12">		
</div>
</div>
<!---------------------------------------------------------------------------->	
<div class="row">
<div class="col-lg-1">
</div>
<form>
<div class="col-lg-12">
<div class="card">
<div class="card-header">
<strong> Manufacturerpartno Details</strong> 
<span class="ui_close_btn"><a href="../manufacturerpartno" class="collapse-close pull-right btn-danger" onclick="../manufacturerpartno"></a></span>
</div>
<div class="card-body card-block normalform">
<table  class="table table-bordered table-hover ">
			<tbody>
				<tr><td>Product Group Name:</td><td>{!! $group_name !!}</td></tr>
				<tr><td>Product Name:</td><td>{!! $productid !!}</td></tr>
				<tr><td>Remarks:</td><td>{!! $remarks !!}</td></tr>
			</tbody>
		</table>		
</div>	
</br></br>
<h4> Manufacturer Part Details</h4>
<table  class="table table-bordered table-hover ">
			<thead>
			<tr>
			<th>Line No</th>
			<th>Manufacturer Source</th>
			<th>Manufacturer Source Value</th>
			<th>Part No</th>
			<th>Part No Description</th>
			</tr>
			</thead>
<tbody>
	<?php foreach ($data as $key => $value): ?>
<tr>
     <td>{!! $key+1 !!}</td>
	   <td>{!! $value->manufacturer_source !!}</td>
	<?php if($value->manufacturer_source=="CUSTOMER"){ ?>
	   <td>{!! $value->manufacturer_source_value_c !!}</td>
<?php	}else{ ?>
	 <td>{!! $value->manufacturer_source_value_s !!}</td>
	<?php } ?>
	   <td>{!! $value->part_no !!}</td>
		 <td>{!! $value->part_no_description !!}</td>
	 </tr>
	<?php endforeach; ?>

</tbody>
</table>
</form>	
</div>
</div>
@extends('layouts.footer')
@endsection