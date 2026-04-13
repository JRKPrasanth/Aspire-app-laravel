@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Quality Check</h3>
  @include('layouts.breadcrumb')


  <form>
    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <span class="fw-bold">Quality Check View</span>
        <a href="../qualitycheckview" class="btn btn-sm btn-danger">Close</a>
      </div>

      <div class="card-body card-block">
        <div class="row">
          <div class="col-md-12">
            <div class="invoice-box" id="section-to-print">
              <table class="table table-borderless">
                <tbody>
                  <tr class="information">
                    <td colspan="6">
                      <table class="table table-borderless">
                        <tbody>
                          <tr>
                            <td>
                              <p><b>Job No:</b> {{ $headerdata->job_no }}</p>
                              <p><b>Batch No:</b> {{ $headerdata->batch_no }}</p>
                              <p><b>Reference No:</b> {{ $headerdata->reference_no }}</p>
                            </td>
                            <td>
                              <p><b>Production Qty:</b> {{ $headerdata->production_qty }}</p>
                              <p><b>Product Name:</b> {{ $headerdata->concatenated_product }}</p>
                              <p><b>Product Code:</b> {{ $headerdata->product_code }}</p>
                            </td>
                            <td>
                              <p><b>UOM Code:</b> {{ $headerdata->uom_code }}</p>
                              <p><b>Transaction Status:</b> {{ $headerdata->trx_status }}</p>
                              <p><b>Quality Status:</b> {{ $headerdata->status }}</p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>

              <h4 class="text-primary">Product Quality Check Details</h4>
              <table class="table table-bordered table-hover">
                <thead class="table-light">
                  <tr>
                    <th>Line No</th>
                    <th>Parameter</th>
                    <th>Specification Criteria</th>
                    <th>Spec Value From</th>
                    <th>Spec Value To</th>
                    <th>UOM</th>
                    <th>Measurement</th>
                    <th>Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($lineardata as $key => $value)
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>{{ $value->parameter }}</td>
                      <td>{{ $value->lookup_code }}</td>
                      <td>{{ $value->spec_value_from }}</td>
                      <td>{{ $value->spec_value_to }}</td>
                      <td>{{ $value->uom }}</td>
                      <td>{{ $value->old_measurement }}</td>
                      <td>
                        @foreach($nddata as $nd)
                          {{ $nd->remarks }}
                        @endforeach
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>



@endsection