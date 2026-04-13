@extends('layouts.header')
@section('content')

  <form>
    {{ csrf_field() }}
    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Product BOM Details</h5>
        <a href="{{ URL::to('materialbom') }}" class="btn btn-sm btn-danger">
          <i class="fa fa-times"></i> Close
        </a>
      </div>

      <div class="card-body">
        <!-- Header Info -->
        <div class="row mb-4">
          <div class="col-md-4">
            <p><strong>Production Product:</strong> {!! $assembly_product_id !!}</p>
            <p><strong>Remarks:</strong> {!! $remarks !!}</p>
          </div>
          <div class="col-md-4">
            <p><strong>UOM Code:</strong> {!! $uom_code_id !!}</p>
            @if($process == 1)
              <p><strong>Process:</strong> Yes</p>
            @endif
          </div>
          <div class="col-md-4">
            <p><strong>Active:</strong> {!! $active !!}</p>
            <p><strong>Created By:</strong> {!! $created_by !!}</p>
          </div>
        </div>

        <!-- Additional Details -->
        <div class="mb-3">
          <h6 class="text-primary border-bottom pb-1">Additional Details</h6>
          <p><strong>Project:</strong> {!! $project_id !!}</p>
        </div>

        <!-- Line Items Table -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Line No</th>
                <th>Component Product</th>
                <th>Component UOM Code</th>
                <th>Component Qty</th>
                @if($process == 1)
                  <th>Process Level</th>
                  <th>Process Name</th>
                  <th>Machine</th>
                @endif
                <th>Comments</th>
              </tr>
            </thead>
            <tbody>
              @foreach($linesdata as $key => $value)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $value->product_code }} - {{ $value->concatenated_product }}</td>
                  <td>{{ $value->uom_code }}</td>
                  <td>{{ $value->component_qty }}</td>
                  @if($process == 1)
                    <td>{{ $value->process_level }}</td>
                    <td>{{ $value->process_name }}</td>
                    <td>{{ idname("machine_name", "w_machine_hdr_t", "machine_hdr_id", $value->machine_name) }}</td>
                  @endif
                  <td>{{ $value->comments }}</td>
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