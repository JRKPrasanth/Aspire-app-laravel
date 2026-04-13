@extends('layouts.header')
@section('content')
    <h3 class="text-danger">
        Manufacturing Stock Report
    </h3>
    @include('layouts.breadcrumb')



    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body p-4">
            <form action="{{ url('manufacturingstockdatarpt') }}" method="get" id="searchForm">
                @csrf
                <div class="row g-4 align-items-end">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label fw-semibold">From Date</label>
                        <input type="text" class="form-control start_date1" id="start_date" name="start_date" required
                            autocomplete="off">
                        <div class="invalid-feedback">Please select a start date.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="end_date" class="form-label fw-semibold">To Date</label>
                        <input type="text" class="form-control end_date1" id="end_date" name="end_date" required
                            autocomplete="off">
                        <div class="invalid-feedback">Please select an end date.</div>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary report_search" id="report_search">
                            <i class="bi bi-search-heart me-1"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered  w-100">
                <thead>
                    <tr>
                        <?php if (request('start_date') != ''): ?>
                        <th colspan='4' class="align text-white bg-danger text-center">Particulars of Products Manufactured / Services Rendered</th>    
                        <th colspan='5' class="align text-white bg-danger text-center">{{ request('start_date')}} To
                            {{ request('end_date')}}</th>
                        <?php endif; ?>

                    </tr>
                    <tr>
                        <th class="align sticky-col text-white bg-secondary text-center">Particulars</th>
                        <th class="align text-white bg-secondary text-center">UOM Code</th>
                        <th class="align text-white bg-secondary text-center">Product Capacity in Relevant Terms</th>
                        <th class="align text-white bg-secondary text-center">Product Quantity Manufactured</th>
                        <th class="align text-white bg-secondary text-center">Product Value (in Rs.)</th>

                    </tr>
                </thead>

                <tfoot>
                <tr class="table-success fw-bold">
                    <th class="freeze">GRAND TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </tfoot>    

                <tbody>
                    <?php foreach ($manufact_data as $value) { ?>
                    <tr>
                        <td>{{$value->cat_grp}}</td>
                        <td>{{$value->uom_code}}</td>
                        <td>{{$value->qty_rel}}</td>
                        <td>{{$value->qty}}</td>
                        <td>{{$value->total}}</td>

                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')

<script>

$(document).ready(function () {

    $('#Table1').DataTable({
        paging: false,
        searching: false,
        ordering: false,
        info: false,

        footerCallback: function () {
            let api = this.api();

            let num = function (i) {
                return typeof i === 'string'
                    ? i.replace(/,/g, '') * 1
                    : typeof i === 'number'
                    ? i
                    : 0;
            };

            // Qty total (column index 2)
            let totalQtyrel = api.column(2).data()
                .reduce((a, b) => num(a) + num(b), 0);

            // Value total (column index 3)
            let totalQty = api.column(3).data()
                .reduce((a, b) => num(a) + num(b), 0);

            let totalValue = api.column(4).data()
                .reduce((a, b) => num(a) + num(b), 0);    

            // Update footer
            $(api.column(2).footer()).html(totalQty.toFixed(2));
            $(api.column(3).footer()).html(totalValue.toFixed(2));
            $(api.column(4).footer()).html(totalValue.toFixed(2));
        }
    });

    // Restore dates after search
    $('.start_date1').val("{{ request('start_date') }}");
    $('.end_date1').val("{{ request('end_date') }}");

});

</script>
@endpush