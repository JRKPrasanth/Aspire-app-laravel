@extends('layouts.header')
@section('content')

    <h3 class="text-danger mb-4"> General Ledger </h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-success bg-gradient text-white fw-semibold">
            <i class="bi bi-funnel me-2"></i>Filter Options
        </div>
        <div class="card-body p-4">
            <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
                enctype="multipart/form-data">
                @csrf

                <!-- First Row: Date Inputs -->
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
                        <input type="text" class="form-control end_date" id="end_date" name="end_date" required
                            autocomplete="off">
                        <div class="invalid-feedback">Please select an end date.</div>
                    </div>
                </div>

                <!-- Second Row: Centered Search Button -->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn bg-success bg-gradient text-white px-4 report_search"
                            id="report_search">
                            <i class="bi bi-search-heart me-1"></i> Search
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-4 mb-4">
        <div class="divhide">


        </div>
    </div>

@endsection
@push('scripts')

    <script>

        $(document).ready(function () {
            $('.divhide').hide();
            $(document).on('click', '.report_search', function () {
                var start_date = ($('.start_date').val() != '') ? $('.start_date').val() : '';
                var end_date = ($('.end_date').val() != '') ? $('.end_date').val() : '';
                if (start_date != '' && end_date != '') {
                    var url = "{{URL::to('getgeneralledger')}}/?start_date=" + start_date + "&end_date=" + end_date;
                    $.get(url, function (data) {
                        $('.divhide').html(data);
                        $('.divhide').show();
                    });
                }
                else {
                    showCustomAlert("Please Select From & To Period", "info");
                }
            });

        });

        $(document).ready(function () {
            $('#Table1').DataTable({

            });
        });
    </script>

@endpush