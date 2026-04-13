@extends('layouts.header')
@section('content')
<style type="text/css"> 
    .invoice-box table td {
   padding: 10px;
   
}
.rate_span{color:#07234e;}
</style>
    <form>
	{{ csrf_field() }}
	
	<div class="card">
		<div class="card-header">
				<span class="ui_close_btn"><a href="../scheduletraining" class="collapse-close pull-right btn-danger" ></a></span>
			
		</div>
	<div class="card-body card-block">
	   <div class="row">
                <div class="col-md-12">

                    <div class="invoice-box" id="section-to-print">

                        <table cellpadding="0" cellspacing="0">
                            <tbody>

                                <h2 class="heads1">Training Schedule Details</h2>

                                <tr class="information">
                                    <td colspan="6">

                                       <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p><b>Name:</b> {{$name }}</p>
                                                        <br>
                                                        <p><b>Schedule Date:</b> {{$date }}</p>
                                                        <br>
                                                        
                                                        
                                                    </td>
                                                    
                                                    <td >
                                                        <p><b>Time:</b> {{$time}}</p>
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
