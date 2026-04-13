@extends('layouts.header')
@section('content')
<h3 class="text-danger">Decimal point settings</h3>
@include('layouts.breadcrumb')

<form method="post" action="{{ url('fpointsave') }}" id="form-ui">
    {{ csrf_field() }}

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-success bg-gradient text-white fw-semibold"></div>
        <div class="card-body mt-2">
            <?php error_reporting(0); ?>
            <div class="row g-3">
                <!-- Hidden ID -->
                <div class="col-md-3 d-none">
                    <label for="settings_tbl_id" class="form-label">Settings Table ID</label>
                    <input type="text" class="form-control settings_tbl_id" name="settings_tbl_id" value="{{ $settings_tbl_id }}">
                </div>
                <div class="col-md-2"></div>
                <!-- Decimal Points -->
                <div class="col-md-3">
                    <label for="decimal_points" class="form-label">
                        <span class="text-danger">*</span>Decimal Points
                    </label>
                    <input type="text" class="form-control decimal_points" name="decimal_points" value="{{ $decimal }}" required>
                </div>

                <!-- Date Format -->
                <div class="col-md-3">
                    <label for="date_format" class="form-label">
                        <span class="text-danger">*</span>Date Format
                    </label>
                    <select name="date_format" id="date_format" class="form-select date_format select2" required>
                        {!! $date_format !!}
                    </select>
                </div>

                <!-- Time Format -->
                <div class="col-md-3">
                    <label for="time_format" class="form-label">
                        <span class="text-danger">*</span>Time Format
                    </label>
                    <select name="time_format" id="time_format" class="form-select time_format select2" required>
                        {!! $time_format !!}
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="row mt-4">
                <div class="col text-center">
                    <input type="hidden" name="submit_type" class="submit_type">
                    <button type="submit" class="btn btn-success px-4 me-2">Submit</button>
                  
                </div>
            </div>
        </div>
    </div>
</form>


@endsection
@push('scripts')

<script>
	$(document).ready(function(){
$(".decimal_points").keypress(function (e) {
    if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
});
		
		    $('.select2').select2({
      width: '100%'
    });
});
</script>
@endpush