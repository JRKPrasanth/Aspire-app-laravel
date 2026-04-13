@extends('layouts.header')
@section('content')
<h3 class="text-danger mb-4">Profit And Loss Compare Report</h3>
@include('layouts.breadcrumb')

<style type="text/css">
    .report {
        font-size: 14px;
        background-color: #e9ecef;
        padding: 6px 12px;
        margin-top: 10px;
        display: inline-block;
        border-radius: 6px;
        font-weight: 500;
    }

    .linkeid {
        cursor: pointer !important;
        text-decoration: none !important;
        color: blue !important;
    }

    .divhide .card {
        padding: 5px;
        border: 1px solid #ccc;
    }

    .divhide .table {
        width: 100%;
    }

    .trail-balance {

        background-color: #fff;
        margin: auto;
        padding: 30px;
        border: 1px solid #ccc;
        box-shadow: 10px 10px 10px #ccc;
        font-size: 16px;
        line-height: 24px;
        color: #555;
    }

    .trail-balance table tr.heading td {
        background: #e8e8e8;
        border-bottom: 1px solid #dcdcdc;
        color: #000;
    }

    .print_logo,
    .print_name {
        display: none;
    }

    .tab_disp {
        padding-bottom: 30px;
        margin-top: 30px;
    }

    .trail-balance {
        padding: 0;
        border: none;
    }

    .table>caption+thead>tr:first-child>td,
    .table>caption+thead>tr:first-child>th,
    .table>colgroup+thead>tr:first-child>td,
    .table>colgroup+thead>tr:first-child>th,
    .table>thead:first-child>tr:first-child>td,
    .table>thead:first-child>tr:first-child>th {
        border-top: 0;
        background: #0d0d0d;
        font-family: FiraSans-Book;
        font-weight: bold;
        color: #fff;
        padding: 10px;
        font-size: 14px;
    }

    .trail-balance table tr.heading td {
        background-color: #e6e9ed;

    }

    .trail-balance {
        box-shadow: none;
    }

    .table tr td b {
        padding-left: 5px;
    }

    .table {
        border: 1px solid #ddd;
        box-shadow: 5px 5px 5px rgba(0, 0, 0, .1);
    }

    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        font-size: 14px;
        padding: 10px;
    }

    th:nth-child(2) {
        text-align: right !important;
    }

    th:nth-child(3) {
        text-align: right !important;
    }

    .prt {
        position: absolute;
        top: -7px;
    }

    .print_logo figure img {
        display: block;
    }

    .print_name h3 {
        font-size: 20px;
    }

    .print_logo {
        float: left;
        width: 15%:;
    }

    .print_name {
        float: left;
        width: 75%;
    }

    @page {
        size: A4;
    }

    @media print {

        .print_logo,
        .print_name {
            display: block;
        }

        .card-block,
        .prt,
        .heads,
        .footer {
            display: none;
        }

        .trail-balance,
        .tab_show,
        .table {
            max-width: 100% !important;
        }

        .print_logo {
            float: left !important;
            width: 15% !important;
        }

        .print_name {
            float: left !important;
            width: 85% !important;
            padding: 15px 30px;
            font-family: FiraSans-Book;
        }

        .print_name h3 {
            font-family: FiraSans-Book;
            font-size: 16px !important;
        }

        .report {
            width: 100%;
            left: 0;
            position: none;
            display: inline-block;
            margin-bottom: 15px;
        }

        .prt_main {
            width: 100% !important;
            border-bottom: 1px solid #ccc;
            padding-bottom: 15px !important;
        }

        .container {
            max-width: 100% !important;
        }

        .table {
            max-width: 100% !important;
        }
    }

    .show {
        color: #fff !important;
        border: none !important;
        padding: 8px 12px !important;
        border-radius: 8px !important;
        font-size: 16px !important;
        font-weight: 500 !important;
        transition: background-color 0.3s ease, transform 0.2s ease !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
    }

    .glyphicon {
        font-size: 15px;
        line-height: 0;
        position: relative;
        top: 10px;
        float: right;
        color: #fff;
        left: 6px;
    }
</style>


<div class="card-body p-4">
    <div class="row text-center text-md-start align-items-center gy-3">

        <!-- Last SHIP Journal Executed -->
        <div class="col-md-7">
            <h6 class="mb-1 text-muted">Last SHIP Journal Executed On</h6>
            <span class="badge bg-primary bg-gradient fs-6 px-3 py-2">
                {{ $ship_date[0]->created_at }}
            </span>
        </div>

        <!-- SHIP Journal Till Date -->
        <div class="col-md-5">
            <h6 class="mb-1 text-muted">SHIP Journal Till Date</h6>
            <span class="badge bg-primary bg-gradient fs-6 px-3 py-2">
                {{ $ship_date[0]->journal_date }}
            </span>
        </div>

    </div>
</div>


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-info bg-gradient text-white fw-semibold">
        <i class="bi bi-funnel me-2"></i>
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
                    <button type="button" class="btn btn-info bg-gradient px-4 text-white report_search"
                        id="report_search">
                        <i class="bi bi-eye-fill"></i> View
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>



<a href="{{URL::to('profitandlosscompare.xls')}}" class='download_link' download></a>

<div class="container-fluid my-4">

    <div class="card shadow-lg rounded-4 border-0">

        <div class="card-header bg-primary text-white text-center">

            <div class="report text-success report-title2"></div>

        </div>
        <div class="card-body">
            <button type="button" class="btn btn-danger bg-gradient downloads">
                <span class="glyphicon glyphicon-download printMe"></span> Download
            </button>
            <div class="table-responsive mt-2">
                <div id="table"></div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title" id="accountModalLabel">Ledger Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="table-responsive">
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3"></div>
                            <div class="table-responsive">
                                <table id="accountgrid" class="table table-bordered table-striped" style="width: 100%;">
                                    <!-- Header will be populated dynamically -->
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Footer (optional buttons) -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

@endsection
@push('scripts')

<script>



    $('.report_search').click(function () {


        var from_date = $(".start_date").val();
        var to_date = $(".end_date").val();

        if (from_date != "") {

            $(".report-title2").html(from_date + ' To ' + to_date);


            var url_val = "{{URL::to('getprofitandlosscompare')}}?start_date=" + from_date + "&end_date=" + to_date;
            $.get(url_val, function (data) {

                $("#table").html(data);

                $(".child").hide();

                $("table").click(function (event) {
                    event.stopPropagation();
                    var $target = $(event.target);
                    var id = $target.attr('data');
                    var col = $target.attr('col');

                    if (col == '0') {
                        $(".child" + id).show();
                        $target.attr('col', '1');

                    } else {
                        $(".child" + id).hide();
                        $target.attr('col', '0');
                    }
                });

            });

        } else {

            showCustomAlert('Please Select Date', 'warning');
        }
    });

    $('.downloads').click(function () {

        var from_date = $(".start_date").val();
        var to_date = $(".end_date").val();
        if (from_date != "") {

            var url_val = "{{URL::to('getprofitandlosscompare')}}?download=1&start_date=" + from_date + "&end_date=" + to_date;
            $.get(url_val, function (data) {

                $('.download_link')[0].click();

            });
        }
        else {
            notyMessageError("Please select Month");
        }
    });


    function ledgerpop(accountid) {
        var from_date = $('.start_date').val();
        var to_date = $('.end_date').val();

        $('#accountModal').modal('show').width("100%");

        // Destroy if already initialized
        if ($.fn.DataTable.isDataTable('#accountgrid')) {
            $('#accountgrid').DataTable().destroy();
            $('#accountgrid').empty(); // optional, clean up old table if needed
        }

        // Rebuild the table header
        $('#accountgrid').html(`
        <thead>
            <tr class="table-warning">
                <th>Journal Name</th>
                <th>Date</th>
                <th>Reference Source</th>
                <th>Reference Name</th>
                <th>Account</th>
                <th>Debit Amount</th>
                <th>Credit Amount</th>
                <th>Balance</th>
            </tr>
			          <tr class="table-danger">
            <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Journal Name</span></th>
			<th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference Source</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference Name</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Account</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Debit Amount</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit Amount</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Balance</span></th>
          </tr>
        </thead>
    `);

        $('#accountgrid').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            ajax: {
                url: "{{ url('getledgerpandlData') }}",
                data: function (d) {
                    d.start_date = from_date;
                    d.end_date = to_date;
                    d.ledger_id = accountid;
                }
            },
            columns: [
                { data: 'journal_name', name: 'journal_name' },
                { data: 'journal_date', name: 'journal_date' },
                { data: 'reference_source', name: 'reference_source' },
                { data: 'reference_name', name: 'reference_name' },
                { data: 'concatenated_segments', name: 'concatenated_segments' },
                { data: 'debit_amounts', name: 'debit_amounts', className: 'text-right' },
                { data: 'credit_amounts', name: 'credit_amounts', className: 'text-right' },
                { data: 'balance', name: 'balance', className: 'text-right' },
            ],
            dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Ledger Report',
                    exportOptions: { columns: ':visible' }
                }
            ]

        });
        $('#ReportTbl thead').on('keyup change', ".column-search", function () {
            var index = $(this).closest('th').index();
            table.column(index).search(this.value).draw();
        });
    }

</script>
@endpush