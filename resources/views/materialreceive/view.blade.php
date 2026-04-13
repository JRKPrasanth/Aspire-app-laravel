@extends('layouts.header')
@section('content')
<h3 class="text-danger"></h3>
@include('layouts.breadcrumb')
  

<form>
    {{ csrf_field() }}
    <div class="card shadow-lg border-0 rounded-3">
        
        <!-- Card Header -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Material Acknowledgement Details</h5>
            <a href="{{ URL::to($pageurl) }}" class="btn btn-sm btn-danger">
                <i class="bi bi-x-circle"></i>
            </a>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><b>Product:</b> {!! $product_code !!} - {!! $concatenated_product !!}</p>
                    <p><b>Job No:</b> {!! $job_no !!}</p>
                    <p><b>Batch No:</b> {{ $batch_no }}</p>
                    <p><b>Material Issue Date:</b> {{ $mtl_receive_date }}</p>
                </div>
                <div class="col-md-6">
                    <p><b>UOM Code:</b> {!! $uom_code !!}</p>
                    <p><b>Job Qty:</b> {{ $job_qty }}</p>
                    <p><b>Job Status:</b> {{ $job_status }}</p>
                    <p><b>Remarks:</b> {{ $remarks }}</p>
                    <p><b>Material Received By:</b> {{ $received_by }}</p>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Line No</th>
                            <th>Product</th>
                            <th>Batch No</th>
                            <th>Locator</th>
                            <th>Component UOM</th>
                            <th>Component Qty</th>
                            <th>Needed Qty</th>
                            <th>Issue Qty</th>
                            <th>Receive Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($linesdata as $key=>$value)
                        <tr>
                            <td class="text-center">{!! $key+1 !!}</td>
                            <td>{!! $value->product_code."-".$value->concatenated_product !!}</td>
                            <td>{!! $value->batchnumber !!}</td>
                            <td>{!! $value->locator_code !!}</td>
                            <td class="text-center">{!! $value->uom_code !!}</td>
                            <td class="text-end">{!! $value->qty !!}</td>
                            <td class="text-end">{!! $value->issue_qty !!}</td>
                            <td class="text-end">{!! $value->mtl_issue_qty !!}</td>
                            <td class="text-end fw-bold">{!! $value->receive_qty !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</form>



<?php
	function idname($displayname,$table,$condition,$value){
	$name=\DB::select("select $displayname from $table where $condition = '$value' ");
    if($name)
		   return $name[0]->$displayname;
		else
		   return "";
	}
?>

@endsection