@extends('layouts.header')
@section('content')


<form>
{{ csrf_field() }}


<div class="card">


<div class="card-header">

<span class="ui_close_btn"><a href="../jobactivity" class="collapse-close pull-right btn-danger" onclick="../jobactivity"></a></span>
</div>



<div class="card-body card-block normalform">


<div class="row">
<div class="col-md-12">

<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Job Activity Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>

                            <tr>
                                <td>
                                    <p><b>Employee Name:</b> {!! $headerdata->employee_number !!}</p>
                                </td>
								 <td>
                                    <p><b>Remarks:</b> {!! $headerdata->remarks !!}</p>  
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6" class="ref">
                                    <h4 class="head-style-1">Additional Details</h4></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <table class="table table-bordered table-hover ">
		<thead>
			<tr>
			<th >Line No</th>
<th >Activity Name</th>
<th >Start DateTime</th>
<th >End DateTime</th>
<th >Duration</th>
			</tr>
			</thead>
<tbody>
	<?php  foreach ($linesdata as $key => $value) {  ?>
<tr>
     <td>{!! $key+1 !!}</td>
     <td>{!! $value->activity_name !!}</td>
     <td>{!! $value->start_datetime !!}</td>
     <td>{!! $value->end_datetime !!}</td>
     <td>{!! $value->duration !!}</td>
	 </tr>
	<?php } ?>

</tbody>
                </table>
            </tr>

        </tbody>
    </table>
</div>
</div>	
</div>
</div>
</form>	
@endsection