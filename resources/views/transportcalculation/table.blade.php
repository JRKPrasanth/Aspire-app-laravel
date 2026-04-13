@extends('layouts.header')
@section('content')
<h3 class="text-danger">Transport Calculation</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary bg-gradient text-white fw-semibold">
</div>
<div class="card-body p-4">
    <form action="{{ url('transportcalculation') }}" method="get" id="searchForm">
        {{ csrf_field() }}

        <div class="row g-4 align-items-end">

            <!-- From Date -->
            <div class="col-md-4">
                <label for="start_date" class="form-label fw-semibold">From Date</label>
                <input type="text" class="form-control start_date1" id="start_date" name="start_date" required>
                <div class="invalid-feedback">Please select a start date.</div>
            </div>

            <!-- To Date -->
            <div class="col-md-4">
                <label for="end_date" class="form-label fw-semibold">To Date</label>
                <input type="text" class="form-control end_date1" id="end_date" name="end_date" required>
                <div class="invalid-feedback">Please select an end date.</div>
            </div>

            <!-- Type -->
            <div class="col-md-4">
                <label for="type" class="form-label fw-semibold">Freight Carrier</label>
                <select id="freight_id" name='freight_id' rows='5'  class='form-control freight_id select2' tabindex="1" data-show-subtext="true" data-live-search="true" required>
                    {!! $frieght !!}
            </select>
            </div>

        </div>

        <!-- Search Button -->
        <div class="row mt-4">
            <div class="col text-center">
                <button type="submit" class="btn btn-primary bg-gradient px-4 report_search">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </div>

    </form>
</div>
</div>



<!-- product based -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive" style="overflow-x: auto;">
      <table id="Table1" class="table table-bordered table-striped table-hover w-100">
            <thead>

            <tr style="background:#c32323 !important;">
                <th class="align text-white bg-danger text-center" >S.No</th>
                <th class="align text-white bg-danger text-center">Invoice No</th>
                <th class="align text-white bg-danger text-center">Invoice Date</th>
                <th class="align text-white bg-danger text-center">Booking Loc</th>
                <th class="align text-white bg-danger text-center">Delivery Loc</th>
                 <th class="align text-white bg-danger text-center">Docket Num</th>
                <th class="align text-white bg-danger text-center">Auctual Wgt</th>
                <th class="align text-white bg-danger text-center">Rate Per(kg)</th>
                <th class="align text-white bg-danger text-center">Num of package</th>
                <th class="align text-white bg-danger text-center">Invoice Value</th>
                <th class="align text-white bg-danger text-center">ROV Charge</th>
                 <th class="align text-white bg-danger text-center">Docket Charge</th>
                <th class="align text-white bg-danger text-center">ESS Amount</th>
                <th class="align text-white bg-danger text-center">Non Metro Amt</th>
                <th class="align text-white bg-danger text-center">Freight Amount</th>
                <th class="align text-white bg-danger text-center">Green Tax Amt</th>

            </tr>
        </thead>
        <tbody>
             <?php $no = 1; ?>
             <?php  foreach($transport_detail as $detail) { ?>
                <tr>
                   
                        <td>{{$no}}</td>
                        <td>{{$detail->invoice_number }}</td>
                        <td>{{$detail->invoice_date }}</td>
                        <td>{{$detail->booking_from }}</td>
                        <td>{{$detail->state_name }}</td>
                        <td>{{$detail->lr_no }}</td>
                        <td>{{$detail->pack_weight }}</td>
                        <td>{{$detail->rate }}</td>
                        <td>{{$detail->packaging_qty }}</td>
                        <td>{{$detail->invoice_grand_total }}</td>
                        <td>{{$detail->rov_charge }}</td>
                         <td>{{$detail->docket_charge }}</td>
                        <td>{{$detail->ess_value}}</td>
                        <td>{{$detail->metro_city }}</td>
                        <td>{{$detail->amount }}</td>   
                        <td>{{$detail->green_tax }}</td>
                        
                     <?php    $no ++; ?>
                </tr>
           
     <?php } ?>
        </tbody>
        <tfoot>
          
        </tfoot>

    </table>
</div>
</div>

@endsection
@push('scripts')

<script>


    $(document).ready(function () {
        $('#Table1').DataTable({
            
            scrollX: true,
            scrollY: "50vh",
        });
    });

    $(document).ready(function () {
        var freight = "{{ request('freight_id') }}";
        var startDate = "{{ request('start_date') }}";
        var endDate = "{{ request('end_date') }}";

        $('.freight_id').select2();
        $('#freight_id').val(freight).trigger('change');

        $('#start_date').val(startDate);
        $('#end_date').val(endDate);

    });

</script>


@endpush