@extends('layouts.header')
@section('content')

<h3 class="text-danger mb-4">Sales Vs Sample Report</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-header bg-primary text-white fw-semibold">
        <i class="bi bi-funnel me-2"></i>Filter Options
    </div>
    <div class="card-body">
        <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate enctype="multipart/form-data">
            @csrf

        <div class="row g-4 mb-3">
          <div class="col-md-2"></div>
          <div class="col-md-4">
            <label for="start_date" class="form-label fw-semibold">From Date</label>
            <input type="text" class="form-control start_date" id="start_date" name="start_date" required
              autocomplete="off">
            <div class="invalid-feedback">Please select a start date.</div>
          </div>

          <div class="col-md-4">
            <label for="end_date" class="form-label fw-semibold">To Date</label>
            <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off">
            <div class="invalid-feedback">Please select an end date.</div>
          </div>
        </div>

        <!-- Second Row: Centered Search Button -->
        <div class="row">
          <div class="col-md-12 text-center mt-2">
            <button type="button" class="btn btn-primary px-4 report_search" id="report_search">
              <i class="bi bi-search-heart me-1"></i> Search
            </button>
          </div>
        </div>
        </form>
    </div>
</div>

{{-- Report Sections --}}
<div class="card shadow-lg rounded-4 border-0">
	<div class="container mt-4">
<div class="report d-none"></div>
<div class="zone d-none"></div>
<div class="sub_cat d-none"></div>
<div class="product d-none"></div>
</div>
@endsection

@push('scripts')

{{-- DataTables --}}
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css" />

<script>
$(document).ready(function () {
    $('.loader').hide();

    function fetchReport(url, targetClass, tableId) {
        $('.loader').show();
        $(".report, .zone, .sub_cat, .product").addClass('d-none');

        $.get(url, function (data) {
            $(targetClass).removeClass('d-none').html(data);
            $(tableId).DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                ordering: false,
                buttons: ['excel']
            });
            $('.loader').hide();
        });
    }

    // Search Report
    $(document).on('click', '.report_search', function () {
        const start_date = $('#start_date').val() || 0;
        const end_date = $('#end_date').val() || 0;

        if (start_date != 0 && end_date != 0) {
            const url = `{{ URL::to('salesvssamplereportdata') }}?type=month&start_date=${start_date}&end_date=${end_date}`;
            fetchReport(url, '.report', '#month');
        } else {
            showCustomAlert("Please Choose Fields","warning");
        }
    });

    // Zone Report
    $(document).on('click', '.zonedata', function () {
        const { from: start_date, to: end_date } = $(this).data();
        if (start_date && end_date) {
            const url = `{{ URL::to('salesvssamplereportdata') }}?type=zone&start_date=${start_date}&end_date=${end_date}`;
            fetchReport(url, '.zone', '#zone');
        } else {
             showCustomAlert("Please Choose Month","warning");
        }
    });

    // Subcategory Report
    $(document).on('click', '.subcatdata', function () {
        const { from: start_date, to: end_date, zone } = $(this).data();
        if (start_date && end_date) {
            const url = `{{ URL::to('salesvssamplereportdata') }}?type=sub_cat&start_date=${start_date}&end_date=${end_date}&zone=${zone}`;
            fetchReport(url, '.sub_cat', '#sub_cat');
        } else {
               showCustomAlert("Please Choose Month","warning");
        }
    });

    // Product Report
    $(document).on('click', '.productdata', function () {
        const { from: start_date, to: end_date, zone, sub } = $(this).data();
        if (start_date && end_date) {
            const url = `{{ URL::to('salesvssamplereportdata') }}?type=product&start_date=${start_date}&end_date=${end_date}&zone=${zone}&sub_name=${sub}`;
            fetchReport(url, '.product', '#product');
        } else {
              showCustomAlert("Please Choose Month","warning");
        }
    });
});
</script>

@endpush
