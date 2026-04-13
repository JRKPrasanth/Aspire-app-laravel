@extends('layouts.header')
@section('content')
<style type="text/css">
   .invoice-box table td {
  
  width: calc(100%/3);
}
.invoice-box table td {
   padding: 10px;
}
.table tr td:nth-child(2),.table tr td:nth-child(3) {
  width: 300px;
}
.table tr td:nth-child(1){
    width: 120px;
}	
</style>
<form>
{{ csrf_field() }}

<div class="card">
<div class="card-header">             
<span class="ui_close_btn"><a  href="{{URL::to('reportelements')}}" class="collapse-close pull-right btn-danger" onclick='location.href="{{ URL::to('reportelements') }}"'></a></span>
</div>
<div class="card-body card-block normalform">
	<div class="row">
		<div class="col-md-12">


<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Report Elements</h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>
                        <tr>
                                <td>
                                    <p><b>Report Source:</b> {{ $rptdispdata[0]->lookup_code }}</p> <br>
                                    <p><b>Set Name:</b>{{ $rptdispdata[0]->set_name }} </p> <br>
                                    <p><b>Description:</b> {{ $rptdispdata[0]->description }}</p> <br>
                                   
                                </td>
                                <td class="text-right">
                                    <p><b>Start Date:</b>{{ $rptdispdata[0]->start_date }} </p> <br>
									 <p><b>End Date:</b> {{ $rptdispdata[0]->end_date }}</p> <br>
                                </td>
                            </tr>

                            

                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <table class="table table-bordered table-hover ">
	<thead>
			<tr>
			<th>Line No</th>
			<th>Element Name</th>
			<th>Element Content</th>
			</tr>
			</thead>
<tbody> 
	<?php foreach ($rptdisplinedate as $key => $value): ?>
<tr>
     <td>{!! $key+1 !!}</td>
     <td>{{ $value->element_name }}</td>
	 <td>{{ $value->element_content }}</td>
	
	 </tr>
	<?php endforeach; ?>

</tbody>
                </table>
            </tr>

        </tbody>
    </table>
</div>




	
</div>

</div>
</div>	
</div>
</form>	

@endsection
