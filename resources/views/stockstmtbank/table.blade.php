@extends('layouts.header')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{asset('css/dashboard.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script src='https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js'></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


<style>
    body {
        background: #fff;
    }

    .table {
        width: 100%;
        margin: auto;
        text-align: left;
        border-collapse: collapse;
        font-size: 15px !important;
    }

    .table th,
    .table td {
        border: 1px solid black;
        padding: 8px;
    }

    .table th {
        text-align: center;
    }

    .table td.right {
        text-align: right;
    }

    .note,
    .certify {
        font-size: 12px;
    }

    .certify {
        text-align: justify;
    }

    .value-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .value-label {
        font-weight: bold;
    }

    .value-amount {
        font-weight: bold;
    }

    .custom_datatable1 {
        max-height: 350px;
        overflow-y: auto;
    }

    .sticky-col {
        position: sticky;
        bottom: 0;
        z-index: 1;
        background-color: #b3d1ff !important;
    }

    .align {
        text-align: center;
        background: #f6f7ff;
    }


    @media print {

        body * {
            visibility: hidden;
        }

        .printable-table,
        .printable-table * {
            visibility: visible;
        }

        .printable-table {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .printable-table .table {
            width: 100%;
            border-collapse: collapse;
            line-height: 1.2;
        }

        .printable-table .table th,
        .printable-table .table td {
            border: 1px solid black;
            padding: 4px;
            font-size: 9px;
            line-height: 1.2;
        }

        .printable-table h4,
        .printable-table b {
            font-size: 12px;
        }

        .printable-table .value-container {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            margin-bottom: 5px;
        }

        .printable-table .note,
        .printable-table .certify {
            font-size: 5px;
            line-height: 1.0;
        }

        .printable-table .no-page-break {
            page-break-inside: avoid;
        }

        #print-button {
            display: none;
        }


    }

    #print-button {
        margin: 10px;
        padding: 4px 30px;
        font-size: 14px;
        cursor: pointer;
        background: #7ae6d9;
        border-radius: 8px;
    }

    .container {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
    }

    .table-container {
        width: 70%;
    }

    .summary-container {
        width: 30%;
        padding-left: 20px;
    }

    .value-container {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-bottom: 5px;
    }
</style>
</head>

<body>
    <div class="col-md-12" style="text-align: end;">

        <button id="print-button" onclick="printPage()"><i class="fas fa-print"></i> Print</button>

    </div>
    <div class="printable-table">
        <div class="container">
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table no-page-break">
                        <tbody>
                            <tr>
                                <td style="border: solid 1px black;" rowspan="1" colspan="8">
                                    <h4><b>Statement of Stocks under Hypothecation to Indian Overseas Bank <span
                                                style="text-decoration: underline;">Kunrathur</span> <span
                                                style="word-spacing: 20px;">Branch As</span> at the close of business
                                            on</b></h4>
                                </td>
                                <td style="border: solid 1px black;" colspan="3">{{ date('Y-m-d', strtotime($date)) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="9"><b>Borrower's Name</b><span style="text-decoration: underline;"> Dr.
                                        JRK's Research & Pharmaceuticals Pvt </span><span
                                        style="word-spacing: 56px;">Ltd <b>Address</span> of Godown</b><span
                                        style="text-decoration: underline;"> 18 & 19, Perumal Koil Street, Kunrathur,
                                        Chennai -69 </span></td>
                            </tr>
                            <tr>
                                <th>Particulars of Stock</th>
                                <th>Balance as per last statement</th>
                                <th>Deposits for the week</th>
                                <th>Total</th>
                                <th>Withdrawals for the week</th>
                                <th>Stock on Date</th>
                                <th>Unit of weight</th>
                                <th>Market Rate/ Purchase Rate whichever is less</th>
                                <th>Total Value</th>
                            </tr>
                            <?php foreach ($final_value as $value) { ?>
                            <tr>
                                <td>{{ $value->particulars}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ $value->stock_on_date}}</td>
                                <td>{{ $value->unit_weight}}</td>
                                <td></td>
                                <td>{{ $value->total_value}}</td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <?php foreach ($grand_totals as $value) { ?>
                                <td colspan="6" class="right"><b>{{$value->total_stock}}</b></td>
                                <td colspan="2" class="right"><b>Total Value</b></td>
                                <td class="right"><b>{{$value->grand_total}}</b></td>
                                <?php } ?>
                            </tr>
                            <tr class="no-page-break">
                                <td colspan="4" class="note">Note: In case of the Raw materials, purchase rate or market
                                    rate whichever is lower, and in the case of finished goods, cost price or market
                                    rate whichever is lower should be taken for purpose of arriving drawing power. I/We
                                    hereby certify that the above statement of stocks, which are fully insured is a true
                                    and correct statement under minimum market values, held by me/us Under Hypothecation
                                    to the Indian Overseas Bank and that all stocks are at any and all times under sole
                                    lien to the Indian Overseas Bank for any or all advances outstanding</td>
                                <td colspan="5" class="certify">I/We also certify that all stocks in the godown are
                                    my/our own bonafide property. I/We am/are aware that on the strength of this
                                    declaration the advance is made by the bank. Should the bank wish at any time to
                                    carry out a detailed weightment and/or valuation of the goods, it is at liberty to
                                    do so at our expenses and I/We agree to accept the result of such weighment and/or
                                    valuation.</td>
                            </tr>
                            <tr>
                                <td colspan="9">Date:</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="summary-container" style="margin-top: 104px;">
                <?php foreach ($grand_totals as $value) { ?>
                <div class="value-container">
                    <span class="value-label">Total Value</span>
                    <span class="value-amount">{{$value->grand_total}}</span>
                </div>
                <div class="value-container">
                    <span class="value-label">Less: Trade Creditors</span>
                    <span class="value-amount">{{$value->trade_creditors}}</span>
                </div>
                <div class="value-container">
                    <span class="value-label">Net Value</span>
                    <span class="value-amount">{{$value->net_value}}</span>
                </div>
                <div class="value-container">
                    <span class="value-label">Less: Margin as applicable</span>
                    <span class="value-amount">{{$value->sum_margin}}</span>
                </div>
                <div class="value-container">
                    <span class="value-label">Total Value A</span>
                    <span class="value-amount">{{$value->total_a}}</span>
                </div>
                <div class="value-container">
                    <span class="value-label"><b>&LCBR</b></span>
                </div>
                <br><br>
                <b>Guarantee</b><br>
                <div class="value-container">
                    <span class="value-label">Sundry Debtor</span>
                    <span class="value-amount">{{$value->sundry_debtor}}</span>
                </div>
                <div class="value-container">
                    <span class="value-label">Less: Margin as applicable</span>
                    <span class="value-amount">{{$value->margin_two}}</span>
                </div>
                <div class="value-container">
                    <span class="value-label">Total Value B</span>
                    <span class="value-amount">{{$value->total_b}}</span>
                </div>
                <div class="value-container">
                    <span class="value-label">Drawing Power</span>
                    <span class="value-amount">{{$value->drawing_power}}</span>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-md-12" style="margin-bottom:80px;">

        <div class="col-md-6">
            <table id="Table1" class="display" style="width:100%;">
                <thead>
                    <tr>
                        <th colspan='12' class="align" style="border: 1px solid #000;">Dr. JRKs Research and
                            Pharmaceuticals Pvt Ltd</th>
                    </tr>
                    <tr>
                        <th colspan='12' class="align" style="border: 1px solid #000;">Sundry Debtors As on {{ $date}}
                        </th>
                    </tr>
                    <tr>
                        <th class="align sticky-col" style="border: 1px solid #000;">Customer Name</th>
                        <th class="align sticky-col" style="border: 1px solid #000;">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                $totalBalance = 0;
                foreach ($sundry_debtor as $value) {
                    $totalBalance += $value->cus_balance;
                ?>

                    <tr>
                        <td style="text-align:center;border: 1px solid #000;">{{ $value->customer_name }}</td>
                        <td style="border: 1px solid #000;text-align: center;">{{ $value->cus_balance }}</td>

                    </tr>
                    <?php } ?>

                    <tr class="sticky-col">
                        <td><b>Grand Total</b></td>
                        <td style="text-align:end;"><b>{{ $totalBalance }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <table id="Table2" class="display" style="width:100%;">
                <thead>
                    <tr>
                        <th colspan='12' class="align" style="border: 1px solid #000;">Dr. JRKs Research and
                            Pharmaceuticals Pvt Ltd</th>
                    </tr>
                    <tr>
                        <th colspan='12' class="align" style="border: 1px solid #000;">Sundry Creditors As on {{ $date}}
                        </th>
                    </tr>
                    <tr>
                        <th class="align sticky-col" style="border: 1px solid #000;">Supplier Name</th>
                        <th class="align sticky-col" style="border: 1px solid #000;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                $totalBalance = 0;
                foreach ($sundry_creditor as $value) {
                    $totalBalance += $value->balance;
                ?>

                    <tr>
                        <td style="text-align:center;border: 1px solid #000;">{{ $value->supplier_name }}</td>
                        <td style="border: 1px solid #000;text-align: center;">{{ $value->balance }}</td>

                    </tr>
                    <?php } ?>

                    <tr class="sticky-col">
                        <td><b>Grand Total</b></td>
                        <td style="text-align:end;"><b>{{ $totalBalance }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Include jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#Table1').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: [],
                pageLength: 1000,
                scrollY: '470px',
                scrollX: true,
                scrollCollapse: true,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'Sundry_Debtor_Balance',
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'Sundry_Debtor_Balance',
                    }
                ]
            });

            $('#Table2').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: [],
                pageLength: 1000,
                scrollY: '470px',
                scrollX: true,
                scrollCollapse: true,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'Sundry_Creditor_Amount',
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'Sundry_Creditor_Amount',
                    }
                ]
            });
        });
    </script>
    <script>
        function printPage() {
            window.print();
        }
    </script>

    @endsection