@extends('layouts.header')
@section('content')

<form>
    {{ csrf_field() }}
    
    <div class="card">
        <div class="card-header">
            <h2> Employee Area Report</h2>
            <span class="ui_close_btn">  <a href="../tourplan" class="collapse-close pull-right btn btn-xs btn-danger" onclick="tourplan"></a></span>
        </div>

        <div class="card-body">   
        <div class="row">
        <div class="col-md-12">
	       <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Tour Area <span style="color: red;" >*</span> </label>
                        <div class="col-md-7">
                            <input class="form-control tour_id" id="tour_id" name="tourprogram_id" size="16" type="hidden" value="" readonly>
                            <input class="form-control tour_status" id="tour_status" name="status" size="16" type="hidden" value="" readonly>
                            <select name="tour_details" class=" form-control tour_type select2 " id="tour_type"  required style="width: 100%;">
                                {!! $tour_area !!}
                            </select>
                        </div>
                    </div>
                </div>
           </div>
        </div>
        
        </div>
        </div>
    </div>
</form>


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
</style>