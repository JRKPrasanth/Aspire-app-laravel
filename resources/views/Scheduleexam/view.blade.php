@extends('layouts.header')
@section('content')
<style type="text/css"> 
    .invoice-box table td {
   padding: 10px;
   
}
</style>
    <form>
	{{ csrf_field() }}
	
	<div class="card">
		<div class="card-header">
				<span class="ui_close_btn"><a href="../scheduleexam" class="collapse-close pull-right btn-danger" ></a></span>
			
		</div>
	<div class="card-body card-block">
	   <div class="row">
                <div class="col-md-12">

                    <div class="invoice-box" id="section-to-print">

                        <table cellpadding="0" cellspacing="0">
                            <tbody>

                                <h2 class="heads1">Exam Schedule Details</h2>

                                <tr class="information">
                                    <td colspan="6">

                                       <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p><b>Topic:</b> {{$values->topic_name }}</p>
                                                        <br>
                                                        <p><b>Schedule Date:</b> {{$schedule_date }}</p>
                                                        <br>
                                                        
                                                        
                                                    </td>
                                                    
                                                    <td >
                                                        <p><b>Remarks:</b> {{$values->remarks }}</p>
                                                    </td>
													<td >
                                                        <p><b>Created By:</b> {{$created_by }}</p>
                                                        <br>
                                                       
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="ref">
                                                        <h4 class="head-style-1">Additional Details</h4></td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <p><b>Start Time:</b>{{$start_time}}</p>
                                                        
                                                    </td>
                                                    <td>
                                                        <p><b>End Time:</b>{{$end_time}}</p>
                                                    </td>
                                                    <td>
                                                         <p><b>Reference :</b> {{$values->reference_id }}</p>
                                                        <br>
                                                        
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
                                                <th>Sno</th>
												<th>Employee Name</th>
												<th>Active</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php //dd($vlinesdata); ?>
                                                @foreach($vlinesdata as $key=>$value)
                                                <tr>
                                                   <td><?php echo $key+1; ?></td>
													<td>{!! $value->first_name; !!}</td>
													 
                                                        <td>{!! $active !!}</td>
                                                  
                                                </tr>
                                                @endforeach
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
