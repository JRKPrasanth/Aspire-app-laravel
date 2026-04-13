
@extends('layouts.header')
@section('content')




<?php //dd($values);?>


    
    <form>
            {{ csrf_field() }}
            
                <div class="card">
                    <div class="card-header">
                    <h2> Invoice Details</h2>
                    <span class="ui_close_btn"><a href="../qasubmitstage" class="collapse-close pull-right btn-danger" onclick="../qasubmitstage"></a></span>
                    </div>


            <div class="card-block">
                         <div class="row">
                            <div class="col-md-12">
                            <table  class="table table-bordered ">
                                    <tbody>



                                        <tr>
                                            <th>Job No:</th>
                                            <td>{{ $header[0]->job_no }}</td>
                                            <th>Batch No:</th>
                                            <td>{{$header[0]->batch_no}}</td>
                                        </tr>
                                        <tr>
                                            <th>product Name:</th>
                                            <td>{!! $product_id !!}</td>
                                            <th>uom code:</th>
                                            <td>{!! $uom_code_id !!}</td>
                                        </tr>
                                        <tr>
                                            <th>verifiers:</th>
                                            <td>{{$header[0]->verifier}}</td>
                                            <th>Jobcard Qty:</th>
                                            <td>{{$header[0]->jobcard_qty}}</td>
                                        </tr>
                                        <tr>
                                            <th>Required Qty:</th>
                                            <td>{{$header[0]->required_qty}}</td>
                                            <th>organization Name:</th>
                                            <td>{!! $organization_id !!}</td>
                                        </tr>
                                        <tr>
                                            <th>Job Date:</th>
                                            <td>{{$header[0]->job_date}}</td>
                                            <th>Remarks:</th>
                                            <td>{{$header[0]->remarks}}</td>
                                        </tr>


                                    </tbody>
                         </table>
                       
                </div>
         <div class="col-md-12">
                                        
        <h4>Invoice Lines Details</h4>
                    <table  class="table">
                        <tr>
                            <th>Line No</th>
                            <th>Product</th>
                            <th>UOM Code</th>
                            <th>Required Qty</th>
                            <th>Need By Date</th>
                            <th>Comments</th>
                        </tr>
                        @foreach($line as $key=>$value)
                        <tr>
                            <td>{!! $key+1!!}</td>
                            <td>{!! $product !!}</td>
                            <td>{!! $uom_code !!}</td>
                            <td><?php echo $value->required_qty?></td>
                            <td><?php echo $value->need_by_date?></td>
                            <td><?php echo $value->comments?></td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                </div>
              </div> 
              </div> 
@endsection
<style>
.ui_close_btn .btn-danger{
    margin-top: -12px;
    background: url("<?php echo URL::asset('images/closebutton.png') ?>") center center no-repeat;
    height: 24px;
    width: 24px;
    background-size: 24px;
    border: none;
}

.table-striped {
     background-color: #9baff1;
}
/*td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}*/



</style>
