@extends('layouts.header')
@section('content')
<style type="text/css">
.divhide .card{
    padding: 5px;
    border: 1px solid #ccc;
}
.divhide .table{
    width: 100%;
}
.card-header{
    border-bottom:1px solid #ccc;
}
.curs{
    cursor: pointer;   
}
.freeze-wrap {
    max-height: 400px;   /* adjust as needed */
    overflow-y: auto;
}
.freeze-header thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f8f9fa;
}
/* Level 1: Summary expanded */
tr.shown td,
.summary-child td {
    font-weight: bold;
    background-color: #e8f4ff !important;  /* light blue */
}
.summary-child tr.fw-bold td {
    background-color: #cfe2ff !important;  /* darker blue */
    font-weight: bold;
}
/* Level 2: Ledger expanded */
tr.shown-ledger td,
.ledger-child td {
    font-weight: bold;
    background-color: #fff3cd !important;
}
/* Level 2 Total row */
.ledger-child tr.fw-bold td {
    background-color: #ffe58f !important;  /* darker green */
    font-weight: bold;
}
</style>

<h2 class="heads">Trial Balance JRK Report</h2>
<div class="card">
    <div class="card-body card-block">
        <form method="GET" action="{{ url('trialbalancesrptjrk') }}">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date"
                           class="form-control"
                           value="{{ request('start_date') ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date"
                           class="form-control"
                           value="{{ request('end_date') ?? '' }}">
                </div>
        
                <!-- New filter mode -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Filter Mode</label>
                        <select name="filter_mode" id="filter_mode" class="form-control">
                            <option value="all">All</option>
                            <option value="exclude">Exclude</option>
                        </select>
                    </div>
                
                    <div class="col-md-3" id="ledger_select_box" style="display:none;">
                        <label>Exclude Ledgers</label>
                        <select name="exclude_ledgers[]" id="exclude_ledgers" class="form-control" multiple>
                            <option value="all">-- Select All / Deselect All --</option>
                            @foreach($ledgers as $ledger)
                                <option value="{{ $ledger->account_id }}">{{ $ledger->account_description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end mt-2">
                    <button type="button" id="filter" class="btn btn-primary w-100">
                        Search
                    </button>
                </div>
            </div>
        </form>

        <!--<div class="table-responsive freeze-wrap">-->
        <table id="trialBalanceTable" class="table table-bordered table-striped ">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Account Name</th>
                    <th>FS</th>
                    <th>Opening</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>
            </thead>
    <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th style="text-align:right">Grand Total:</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
        </table>
        <!--</div>-->
    </div>
</div>

<!-- jQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


<!-- DataTables Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<!-- SheetJS for Excel Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx-style/0.8.13/xlsxstyle.min.js"></script>
<script src="https://unpkg.com/xlsx-style@0.8.13/dist/xlsxstyle.min.js"></script>-->

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    function loadTable(start_date, end_date, exclude_ledgers = [], filter_mode = "all") {
    var table = $('#trialBalanceTable').DataTable({
        destroy: true,
        processing: true,
        serverSide: false,
        lengthMenu: [[-1, 10, 25, 50], ["All", 10, 25, 50]],
        pageLength: -1,
        ajax: {
            url: "{{ url('gettrialbalancejrk') }}",
            type: "GET",
            data: function(d) {
                return {
                    start_date: start_date || '',
                    end_date: end_date || '',
                    filter_mode: filter_mode || 'all',
                    exclude_ledgers: Array.isArray(exclude_ledgers) ? exclude_ledgers : []
                };
            }
        /*        error: function (xhr, error, code) {
        console.error("Ajax error response:", xhr.responseText);
        alert("Server error â†’ check console / laravel.log");
            }*/
        },
        columns: [
            { className: 'curs details-summary', orderable: false, data: null, defaultContent: '+' },
            { data: 'account_id', visible: false },
            { data: 'account_description' },
            { data: 'FS' },
            { data: 'opening_balance' },
            { data: 'debit' },
            { data: 'credit' },
            { data: 'balance' }
        ],
            dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
            buttons: [
                {
                    text: 'Export Excel (Summary)',
                    action: function (e, dt) {
                        window.exportsummary(dt);   // use dt from DataTables
                    }
                }
            ],
            footerCallback: function (row, data, start, end, display) {
                var api = this.api();

                var intVal = function (i) {
                    return typeof i === 'string' ?
                        parseFloat(i.replace(/,/g, '')) :
                        typeof i === 'number' ? i : 0;
                };

                var totalOpening = api.column(4).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                var totalDebit   = api.column(5).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                var totalCredit  = api.column(6).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                var totalBalance = api.column(7).data().reduce((a, b) => intVal(a) + intVal(b), 0);

                $(api.column(4).footer()).html(totalOpening.toFixed(2));
                $(api.column(5).footer()).html(totalDebit.toFixed(2));
                $(api.column(6).footer()).html(totalCredit.toFixed(2));
                $(api.column(7).footer()).html(totalBalance.toFixed(2));
            }
        });

        // ===================== 1st level expand: SUMMARY =====================
        $('#trialBalanceTable tbody').off('click', 'td.details-summary').on('click', 'td.details-summary', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

    // Close any other open rows
    $('#trialBalanceTable tr.shown').not(tr).each(function () {
        table.row(this).child.hide();
        $(this).removeClass('shown');
        $(this).find('td.details-summary').text('+');
    });

    if (row.child.isShown()) {
        row.child.hide();
        tr.removeClass('shown');
        $(this).text('+');
    } else {
        var summaryRow = row.data();
        var account_description = summaryRow.account_description;

        // ðŸ”¹ Always take values from filter inputs
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var filter_mode = $('#filter_mode').val();
        var exclude_ledgers = $('#exclude_ledgers').val() || [];

        $.get("{{ url('getTrialBalanceDetailsjrk') }}/" + encodeURIComponent(account_description), {
            start_date: start_date,
            end_date: end_date,
            filter_mode: filter_mode || 'all',
            exclude_ledgers: exclude_ledgers
        }, function (res) {

            var html = '<div class="table-responsive freeze-wrap">' +
                       '<table class="table table-sm table-bordered freeze-header">';
            html += '<tr><th></th><th>Account Code</th><th>Account Name</th><th>Ledger Name</th>' +
                    '<th>Opening</th><th>Debit</th><th>Credit</th><th>Balance</th></tr><tbody>';

            var totalOpening = 0, totalDebit = 0, totalCredit = 0, totalBalance = 0;

            $.each(res.data, function (i, d) {
                totalOpening += parseFloat(d.opening_balance) || 0;
                totalDebit   += parseFloat(d.debit) || 0;
                totalCredit  += parseFloat(d.credit) || 0;
                totalBalance += parseFloat(d.balance) || 0;

                html += '<tr>' +
                        '<td class="curs details-ledger" data-id="'+ d.account_id +'">+</td>' +
                        '<td>' + d.account_id + '</td>' +
                        '<td>' + d.account_description + '</td>' +
                        '<td>' + d.concatenated_segments + '</td>' +
                        '<td class="text-end">' + d.opening_balance + '</td>' +
                        '<td class="text-end">' + d.debit + '</td>' +
                        '<td class="text-end">' + d.credit + '</td>' +
                        '<td class="text-end">' + d.balance + '</td>' +
                        '</tr>';
            });

            html += '<tr class="fw-bold bg-light">' +
                    '<td colspan="4" class="text-end"><b>Total</b></td>' +
                    '<td class="text-end"><b>' + totalOpening.toFixed(2) + '</b></td>' +
                    '<td class="text-end"><b>' + totalDebit.toFixed(2) + '</b></td>' +
                    '<td class="text-end"><b>' + totalCredit.toFixed(2) + '</b></td>' +
                    '<td class="text-end"><b>' + totalBalance.toFixed(2) + '</b></td>' +
                    '</tr>';

            html += '</tbody></table></div>';

            row.child('<div class="summary-child">' + html + '</div>').show();
            tr.addClass('shown');
            $(tr).find('td.details-summary').text('-');
        });
    }
});


        // ===================== 2nd level expand: LEDGER =====================
        
 

$('#trialBalanceTable').on('click', 'td.details-ledger', function () {
        

    var tr2 = $(this).closest('tr');
    
     $('#trialBalanceTable tr.shown-ledger').not(tr2).each(function () {
        $(this).next('tr.ledger-child').remove();
        $(this).removeClass('shown-ledger');
        $(this).find('td.details-ledger').text('+');
    });

    if (tr2.hasClass('shown-ledger')) {
        tr2.next('tr.ledger-child').remove();
        tr2.removeClass('shown-ledger');
        $(this).text('+');
    } else {
        
                var account_id = $(this).data('id');   // âœ… read directly
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                var filter_mode = $('#filter_mode').val();
                var exclude_ledgers = $('#exclude_ledgers').val() || [];
                
                $.get("{{ url('getledgerbalancejrk') }}",{
                    account_id: account_id,
                    start_date: start_date,
                    end_date: end_date,
                    filter_mode: filter_mode || 'all',
                    exclude_ledgers: exclude_ledgers
                }, function (res) {
                var html = '<div class="table-responsive freeze-wrap">' + '<table class="table table-sm table-bordered freeze-header">';
                html += '<tr><th>Date</th><th>Type</th><th>Name</th><th>Reference Source</th><th>Reference Name</th><th>Segments</th><th class="text-end">Debit</th><th class="text-end">Credit</th><th class="text-end">Balance</th></tr><tbody>';

                var totalDebit = 0, totalCredit = 0, totalBalance = 0;

                $.each(res, function (i, d) {
                    var debit  = parseFloat(d.debit_amounts) || 0;
                    var credit = parseFloat(d.credit_amounts) || 0;
                    var bal    = parseFloat(d.balance) || 0;

                    totalDebit   += debit;
                    totalCredit  += credit;
                    totalBalance += bal;

                    html += '<tr>' +
                        '<td>' + (d.journal_date ?? '') + '</td>' +
                        '<td>' + (d.journal_type ?? '') + '</td>' +
                        '<td>' + (d.journal_name ?? '') + '</td>' +
                        '<td>' + (d.reference_source ?? '') + '</td>' +
                        '<td>' + (d.reference_name ?? '') + '</td>' +
                        '<td>' + (d.concatenated_segments ?? '') + '</td>' +
                        '<td class="text-end">' + debit.toFixed(2) + '</td>' +
                        '<td class="text-end">' + credit.toFixed(2) + '</td>' +
                        '<td class="text-end">' + bal.toFixed(2) + '</td>' +
                    '</tr>';
                });

                html += '<tr class="fw-bold bg-light">' +
                    '<td colspan="6" class="text-end"><b>Total</b></td>' +
                    '<td class="text-end"><b>' + totalDebit.toFixed(2) + '</b></td>' +
                    '<td class="text-end"><b>' + totalCredit.toFixed(2) + '</b></td>' +
                    '<td class="text-end"><b>' + (totalDebit.toFixed(2)-totalCredit.toFixed(2)).toFixed(2) + '</b></td>' +
                    //'<td class="text-end"><b>' + totalBalance.toFixed(2) + '</b></td>' +
                '</tr>';

                html += '</tbody></table>';

                // âœ… use colspan="9"
                tr2.after('<tr class="ledger-child"><td colspan="9">' + html + '</td></tr>');
                tr2.addClass('shown-ledger');
                tr2.find('td.details-ledger').text('-');
                /* tr2.after('<tr class="ledger-child"><td colspan="9">' + html + '</td></tr>');
                tr2.addClass('shown-ledger');
                $(this).text('-');    */
                
            }
        );
    }
});


    }

    //Initial Load
    /* loadTable($('#start_date').val(), $('#end_date').val());*/
    
    // Filter
        $('#filter').click(function () {
            let start_date      = $('#start_date').val() || '';
            let end_date        = $('#end_date').val() || '';
            let filter_mode     = $('#filter_mode').val() || 'all';
            let exclude_ledgers = $('#exclude_ledgers').val() || [];
        
            console.log("Sending params:", { start_date, end_date, filter_mode, exclude_ledgers });
        
            loadTable(start_date, end_date, exclude_ledgers, filter_mode);
        });
    
    //multiple select2
    
    $('#exclude_ledgers').select2({
        placeholder: "Select ledgers to exclude",
        allowClear: true,
        width: '100%'
    });

    // Show/hide ledger box based on filter_mode
    $('#filter_mode').on('change', function () {
        if ($(this).val() === 'exclude') {
            $('#ledger_select_box').show();
        } else {
            $('#ledger_select_box').hide();
            $('#exclude_ledgers').val(null).trigger('change'); // clear selection
        }
    });

    // Handle "Select All / Deselect All"
    $('#exclude_ledgers').on('select2:select', function (e) {
        if (e.params.data.id === 'all') {
            let allOptions = $('#exclude_ledgers option:not([value="all"])').map(function () {
                return $(this).val();
            }).get();

            // If not all selected â†’ select all
            if ($('#exclude_ledgers').val().length - 1 < allOptions.length) {
                $('#exclude_ledgers').val(allOptions).trigger('change');
            } else {
                // If all selected â†’ deselect all
                $('#exclude_ledgers').val(null).trigger('change');
            }
        }
    });
    
    window.sanitize = function(value) {
    if (!value) return "";
    return value
        .toString()
        .replace(/[\x00-\x1F\x7F]/g, "")   // remove control chars
        .replace(/['"]/g, " ")             // replace quotes with space
        .replace(/\//g, "-")               // replace / with -
        .trim();
}

function setHeaderStyle(ws, range) {
    for (let C = range.s.c; C <= range.e.c; ++C) {
        let cell_address = XLSX.utils.encode_cell({r:0, c:C});
        if(!ws[cell_address]) continue;
        ws[cell_address].s = {
            font: { name: "Calibri", sz: 10, bold: true },
            alignment: { horizontal: "center", vertical: "center" },
            fill: { fgColor: { rgb: "D9E1F2" } } // light blue background
        };
    }
}

function setTotalRowStyle(ws, row, colStart, colEnd) {
    for (let C = colStart; C <= colEnd; ++C) {
        let cell_address = XLSX.utils.encode_cell({r: row, c:C});
        if(!ws[cell_address]) continue;
        ws[cell_address].s = {
            font: { name: "Calibri", sz: 10, bold: true },
            alignment: { horizontal: (C >= colEnd-2 ? "right" : "left") }
        };
    }
}

function applyDefaultStyle(ws) {
    let range = XLSX.utils.decode_range(ws['!ref']);
    for (let R = 0; R <= range.e.r; ++R) {
        for (let C = 0; C <= range.e.c; ++C) {
            let cell_address = XLSX.utils.encode_cell({r:R, c:C});
            if(!ws[cell_address]) continue;
            if(!ws[cell_address].s) ws[cell_address].s = {};
            ws[cell_address].s.font = { name: "Calibri", sz: 10 };
        }
    }
    // Freeze header row
    ws['!freeze'] = { xSplit: "0", ySplit: "1", topLeftCell: "A2", activePane: "bottomLeft", state: "frozen" };
}

window.exportsummary = async function (dt) {
    function sanitize(v){ return (v ?? "").toString().replace(/[\x00-\x1F\x7F]/g,"").replace(/['"]/g," ").replace(/\//g,"-").trim(); }

    const wb = XLSX.utils.book_new();
    const rows = [["Account Name","FS","Opening","Debit","Credit","Balance"]];

    let tOpen=0, tDr=0, tCr=0, tBal=0;

    dt.rows({ search: 'applied' }).every(function () {
        const d = this.data();
        const open = parseFloat(d.opening_balance) || 0;
        const dr   = parseFloat(d.debit) || 0;
        const cr   = parseFloat(d.credit) || 0;
        const bal  = parseFloat(d.balance) || 0;

        rows.push([ sanitize(d.account_description), d.FS, open, dr, cr, bal ]);

        tOpen += open; tDr += dr; tCr += cr; tBal += bal;
    });

    rows.push(["Grand Total","", tOpen, tDr, tCr, tBal]);

    const ws = XLSX.utils.aoa_to_sheet(rows);
    XLSX.utils.book_append_sheet(wb, ws, "Summary");
    XLSX.writeFile(wb, "JRK_TrialBalanceSummary.xlsx");
}
    
});
</script>
@endsection