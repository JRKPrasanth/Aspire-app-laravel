@extends('layouts.header')
@section('content')
<h3 class="text-danger">EMI Calculator</h3>
@include('layouts.breadcrumb')

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
            <div class="row gy-4">
                <!-- LEFT SIDE: SLIDERS -->
                <div class="col-lg-7">
                    <!-- Loan Amount -->
                    <div class="mb-4">
                        <h5 class="text-secondary mb-2">Loan Amount</h5>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-primary p-2 fs-6">₹ <span id="la_value">260000</span></span>
                        </div>
                        <input type="text" id="la" data-slider="true" value="260000" data-slider-range="100000,5000000" data-slider-step="10000" data-slider-snap="true" class="form-range w-100">
                    </div>

                    <!-- No. of Months -->
                    <div class="mb-4">
                        <h5 class="text-secondary mb-2">Number of Months</h5>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-danger p-2 fs-6"><span id="nm_value">36</span> Months</span>
                        </div>
                        <input type="text" id="nm" data-slider="true" value="36" data-slider-range="12,360" data-slider-step="1" data-slider-snap="true" class="form-range w-100">
                    </div>

                    <!-- Rate of Interest -->
                    <div class="mb-4">
                        <h5 class="text-secondary mb-2">Rate of Interest (ROI)</h5>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-warning text-dark p-2 fs-6"><span id="roi_value">10.2</span>%</span>
                        </div>
                        <input type="text" id="roi" data-slider="true" value="10.2" data-slider-range="8,16" data-slider-step=".05" data-slider-snap="true" class="form-range w-100">
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-md-6">
                            <div class="alert alert-light border shadow-sm text-center">
                                <strong class="text-primary d-block mb-1">Monthly EMI</strong>
                                <button type="button" class="btn btn-success rounded-pill px-4" id="emi"></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-light border shadow-sm text-center">
                                <strong class="text-warning d-block mb-1">Total Interest</strong>
                                <button type="button" class="btn btn-warning rounded-pill px-4" id="tbl_int"></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-light border shadow-sm text-center">
                                <strong class="text-info d-block mb-1">Total Payable</strong>
                                <button type="button" class="btn btn-info rounded-pill px-4" id="tbl_full"></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-light border shadow-sm text-center">
                                <strong class="text-secondary d-block mb-1">Interest %</strong>
                                <button type="button" class="btn btn-secondary rounded-pill px-4" id="tbl_int_pge"></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDE: CHART -->
                <div class="col-lg-5">
                    <div id="container" class="border rounded-4 shadow-sm p-3 bg-light" style="min-height:350px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/simple-slider.css') }}">
<style>
    .form-range {
        accent-color: #0d6efd;
    }
    .badge {
        font-size: 1rem;
    }
    .btn::before {
        content: "₹ ";
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/simple-slider.js') }}"></script>
<script>
$(document).ready(function(){
    Highcharts.setOptions({
        colors: ['#d63384', '#0dcaf0']
    });

    function calculateEMI(){
        let loanAmount = parseFloat($("#la_value").html());
        let numberOfMonths = parseInt($("#nm_value").html());
        let rateOfInterest = parseFloat($("#roi_value").html());
        let monthlyInterestRatio = (rateOfInterest / 100) / 12;

        let top = Math.pow((1 + monthlyInterestRatio), numberOfMonths);
        let bottom = top - 1;
        let sp = top / bottom;
        let emi = ((loanAmount * monthlyInterestRatio) * sp);
        let full = numberOfMonths * emi;
        let interest = full - loanAmount;
        let int_pge = (interest / full) * 100;

        $("#tbl_int_pge").html(int_pge.toFixed(2) + "%");
        $("#emi").html(emi.toFixed(2).toLocaleString());
        $("#tbl_full").html(full.toFixed(2).toLocaleString());
        $("#tbl_int").html(interest.toFixed(2).toLocaleString());

        Highcharts.chart('container', {
            chart: { type: 'pie' },
            title: { text: 'Loan vs Interest' },
            plotOptions: {
                pie: {
                    innerSize: '50%',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Amount',
                data: [
                    ['Principal', loanAmount],
                    ['Interest', interest]
                ]
            }]
        });
    }

    // Bind sliders
    $("#la, #nm, #roi").on("slider:changed", function(e, data){
        let id = $(this).attr('id');
        $("#" + id + "_value").html(data.value.toFixed(id === "roi" ? 2 : 0));
        calculateEMI();
    });

    calculateEMI();
});
</script>

@endpush
