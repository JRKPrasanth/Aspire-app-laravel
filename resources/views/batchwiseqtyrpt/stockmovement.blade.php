@extends('layouts.header')

@section('content')

<h3 class="text-danger mb-4">Stock Movement Report</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-body">
        <form id="stockMovementForm" class="needs-validation" novalidate>
            @csrf

            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label fw-semibold">From Date</label>
                    <input type="text" class="form-control start_date" autocomplete="off" required>
                    <div class="invalid-feedback">Please select start date</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">To Date</label>
                    <input type="text" class="form-control end_date" autocomplete="off" required>
                    <div class="invalid-feedback">Please select end date</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Group</label>
                    <select class="form-control group_id" required>
                        <option value="">-- Select Group --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Sub Inventory</label>
                    <select class="form-control subinventory_id" required>
                        <option value="">-- Select Sub Inventory --</option>
                        @foreach($subs as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->subinventory_name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="text-center mt-4">
                <button type="button" class="btn btn-success px-4 report_search">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>

        </form>
    </div>
</div>

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Product Classification</th>
              <th>Product Variant</th>
              <th>Opening Stock</th>
              <th>PO Status</th>
              <th>PO Amount</th>
              <th>GRN Number</th>
              <th>GRN Date</th>
              <th>GRN Status</th>
              <th>Invoice Number</th>
              <th>Invoice Date</th>
              <th>Invoice Amount</th>
              <th>Payment Number</th>
              <th>Payment Date</th>
              <th>Payment Amount</th>
              <th>Balance Amount</th>
              <th>Payment BRS Status</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">SUpplier Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PO
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PO Date</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PO
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PO
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">GRN
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">GRN
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">GRN
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Balance
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment BRS
                  Status</span></th>



            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection

@push('scripts')

{{-- PQGRID --}}
<link rel="stylesheet" href="{{ asset('css/pqgrid.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/pqgrid.ui.min.css') }}">
<script src="{{ asset('js/pqgrid.min.js') }}"></script>
<script src="{{ asset('js/pq-localize-en.js') }}"></script>

{{-- XLSX --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="{{ asset('js/filesaver.js') }}"></script>

<script>
$(function () {

    /* ---------------- DATE PICKER ---------------- */
    let dateFormat = "{{ session('j_date_format') }}";
    let minDate = "{{ session('js_griddate') }}";
    let maxDate = "{{ session('js_gridenddate') }}";

    $('.start_date, .end_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: dateFormat,
        minDate: minDate,
        maxDate: maxDate
    });

    /* ---------------- SEARCH ---------------- */
    $(document).on('click', '.report_search', function () {

        let params = {
            start_date: $('.start_date').val(),
            end_date: $('.end_date').val(),
            group_id: $('.group_id').val(),
            subinventory_id: $('.subinventory_id').val()
        };

        if (Object.values(params).some(v => !v)) {
            notyMsg('info', 'Please choose all fields');
            return;
        }

        let url = "{{ route('getstockmovement') }}?" + $.param(params);

        $("#batchwiseqytrptgrid")
            .pqGrid("option", "dataModel.url", url)
            .pqGrid("refreshDataAndView");
    });

    /* ---------------- BREAKUP ---------------- */
    $(document).on('click', '.show-breakup', function (e) {
        e.preventDefault();

        $.get("{{ route('getstockbreakup') }}", {
            classification: $(this).data('class'),
            metric_type: $(this).data('type'),
            start_date: $('.start_date').val(),
            end_date: $('.end_date').val(),
            group_id: $('.group_id').val(),
            subinventory_id: $('.subinventory_id').val()
        }, function (rows) {

            let total = 0;
            let html = `
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Source</th>
                            <th>Product</th>
                            <th class="text-end">Qty</th>
                        </tr>
                    </thead><tbody>`;

            rows.forEach(r => {
                total += parseFloat(r.qoh_trx_qty);
                html += `
                    <tr>
                        <td>${r.created_at}</td>
                        <td>${r.qoh_source}</td>
                        <td>${r.concatenated_product}</td>
                        <td class="text-end">${parseFloat(r.qoh_trx_qty).toFixed(2)}</td>
                    </tr>`;
            });

            html += `
                </tbody>
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <td colspan="3" class="text-end">Total</td>
                        <td class="text-end">${total.toFixed(2)}</td>
                    </tr>
                </tfoot>
                </table>`;

            $('#breakupContent').html(html);
            $('#breakupModal').modal('show');
        });
    });

    /* ---------------- PQGRID ---------------- */
    let colModel = [
        { title: "Classification", dataIndx: "product_classification", width: 180 },
        { title: "Variant", dataIndx: "product_variant_name", width: 180 },

        ["opening_stock", "Opening Stock", "opening"],
        ["purchase_qty", "Inward Qty", "purchase"],
        ["sales_qty", "Outward Qty", "sales"],
        ["closing_stock", "Closing Stock", "closing"]
    ].map(c => ({
        dataIndx: c[0],
        title: c[1],
        align: "right",
        render: ui => `
            <a href="#" class="show-breakup"
               data-type="${c[2]}"
               data-class="${ui.rowData.product_classification}">
               ${parseFloat(ui.cellData || 0).toFixed(2)}
            </a>`
    }));

    $("#batchwiseqytrptgrid").pqGrid({
        width: "100%",
        height: 500,
        colModel: colModel,
        dataModel: {
            location: "remote",
            dataType: "JSON",
            method: "GET",
            getData: res => ({
                data: res.data,
                totalRecords: res.totalRecords
            })
        },
        pageModel: { type: "remote", rPP: 1000 },
        resizable: true,
        wrap: false,
        numberCell: { show: false },
        title: "Stock Movement Report",
        toolbar: {
            items: [{
                type: 'button',
                label: 'Export Excel',
                icon: 'ui-icon-arrowthickstop-1-s',
                listener: function () {
                    let blob = this.exportData({ format: 'xlsx', render: true });
                    saveAs(blob, "Stock_Movement_Report.xlsx");
                }
            }]
        }
    });

});
</script>
@endpush

@section('content')

{{-- BREAKUP MODAL --}}
<div class="modal fade" id="breakupModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Stock Movement Breakup</h5>
                <button class="btn btn-success btn-sm" id="exportBreakupExcel">Export</button>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="max-height:500px;overflow:auto">
                <div id="breakupContent"></div>
            </div>
        </div>
    </div>
</div>

@endsection