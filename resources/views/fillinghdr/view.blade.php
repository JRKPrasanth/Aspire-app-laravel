
@extends('layouts.header')
@section('content')




    <form>
			{{ csrf_field() }}
			
				<div class="card">


					<div class="card-header">
					<h2> Jobcard Details</h2>
					<span class="ui_close_btn"><a href="{{ URL::to('jobcard') }}" class="collapse-close pull-right btn-danger"></a></span>
					</div>



					<div class="card-body card-block">

                         <div class="row">
    <div class="col-md-12">

        <div class="invoice-box" id="section-to-print">

            <table cellpadding="0" cellspacing="0">
                <tbody>

                    <h2 class="heads1">Jobcard Details</h2>

                    <tr class="information">
                        <td colspan="6">
                            <table>
                                <tbody>

                                    <tr>
                                        <td>
                                            <p><b>Job No:</b> {!! $job_no !!}</p>
                                            <br>
                                            <p><b>Job Date:</b> {!! $job_date !!}</p>
                                            <br>
                                            <p><b>Job Completion Date:</b> {!! $job_completion_date !!}</p>
                                            <br>
                                            <p><b>Remarks:</b> {!! $remarks !!}</p>
                                            <br>
                                            <p><b>Job Created By:</b> {!! $job_created_by !!}</p>
                                            <br>
                                            <p><b>Job Assigned To:</b> {!! $assigned_name !!}</p>
                                            <br>
                                            <p><b>Product:</b> {!! $assembly_product !!}</p>
                                            <br>


                                        </td>
                                        <td class="text-right">
                                            <p><b>Uom Code:</b> {!! $uom_code_id !!}</p>
                                            <br>
                                            <p><b>Job Qty:</b> {!! $job_qty !!}</p>
                                            <br>
                                            <p><b>Job Adjusted Qty:</b> {!! $job_adjusted_qty !!}</p>
                                            <br>

                                            <p><b>Machine:</b> {!! $machine !!}</p>
                                            <br>
                                            <p><b>Machine Capacity:</b> {!! $machine_capacity !!}</p>
                                            <br>
                                            <p><b>Organization:</b> {!! $organization_name !!}</p>
                                            <br>
                                        </td>
                                    </tr>

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
					</div>
</form>



@endsection



