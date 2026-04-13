@extends('layouts.header')
@section('content')



<form>
{{ csrf_field() }}



<div class="card">
<div class="card-header">

<span class="ui_close_btn"><a href="../manufacturerpartno" class="collapse-close pull-right btn-danger" onclick="../manufacturerpartno"></a></span>
</div>



<div class="card-body card-block normalform">
	<div class="row">
		<div class="col-md-12">

<div class="invoice-box" id="section-to-print">
      
        <table cellpadding="0" cellspacing="0">
            <tbody>





            <h2 class="heads1">Manufacturer Part Details</h2>
            
            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>
                        
                        <tr>
                            <td>
                                <p><b>Product Group Name:</b>  {!! $group_name !!}</p> <br>
                            </td>
							<td>
                                <p><b>Product Name:</b> {!! $productid !!}</p><br>
                            </td>
                            <td class="text-right">
                                <p><b>Remarks:</b> {!! $remarks !!}</p><br>
                                                     
                            </td>
                        </tr>


                      
						
                    </tbody>
                </table>
                </td>
            </tr>  
            <tr class="heading">
              <table  class="table table-bordered table-striped ">
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
	<?php  foreach ($data as $key => $value): ?>
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
            </tr>
                       

            
     
        </tbody></table>
    </div>




</div>

</div>

</div>
</div>
</form>




@endsection
