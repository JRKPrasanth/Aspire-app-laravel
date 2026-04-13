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
                                                        <p><b>Topic:</b> {{$values->topic_name }}</p>
                                                        <br>
                                                        <p><b>Schedule Date:</b> {{$values->schedule_date }}</p>
                                                        <br>
                                                        
                                                        
                                                    </td>
                                                    
                                                    <td >
                                                        <p><b>Trainer Name:</b> {{$values->trainer_name }}</p>
                                                        <br>
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
                                                        <p><b>Need Exam:</b> {{$values->need_exam }}</p>
                                                        <br>
                                                        <p><b>Start Time:</b>{{$values->start_time}}</p>
                                                        
                                                    </td>
                                                    <td>
                                                        <p><b>Trainer Type:</b>{{$values->trainer_type }} </p>
                                                        <br>
                                                        <p><b>End Time:</b>{{$values->end_time}}</p>
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
												<th>Ratings</th>
												<th>Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php //dd($vlinesdata); ?>
                                                @foreach($vlinesdata as $key=>$value)
                                                <tr>
                                                   <td><?php echo $key+1; ?></td>
													<td>{!! $value->first_name; !!}</td>
													 
                                                        <td>
                                                            <?php
                                                                $ft = json_decode($value->feedback_title);
                                                                $fb = json_decode($value->feedback);
                                                                foreach($ft as $fk=>$fv){
                                                                    echo "<h4>".$fv.": "."<span class='rate_span'>".$fb[$fk]." Star<span></h4>";
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>{{$value->comment}}</td>
                                                  
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
