@extends('layouts.header')
@section('content')


<!-- Card -->
<div class="card shadow-lg rounded-4 border-0">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Production Indent Details</h5>
    <div class="d-flex gap-2">
      <a href="../productionindent" class="btn btn-danger btn-sm">
       Close
      </a>
    </div>
  </div>

  <div class="card-body">

    <!-- Top Summary -->
    <div class="row g-4">
      <div class="col-md-6">
        <div class="p-3 rounded-3 border bg-light">
          <div class="mb-2">
            <span class="text-muted">Indent No</span>
            <div class="fw-semibold">{!! $indent_no !!}</div>
          </div>
          <div class="mb-2">
            <span class="text-muted">Indent Date</span>
            <div class="fw-semibold">{!! $indent_date !!}</div>
          </div>
          <div class="mb-0">
            <span class="text-muted">Indent Status</span>
            <div>
              <span class="badge
                @if(($indent_status ?? '') === 'Approved') bg-success
                @elseif(($indent_status ?? '') === 'Pending') bg-warning text-dark
                @elseif(($indent_status ?? '') === 'Rejected') bg-danger
                @else bg-secondary @endif">
                {!! $indent_status !!}
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="p-3 rounded-3 border bg-light">
          <div class="mb-2">
            <span class="text-muted">Requestor</span>
            <div class="fw-semibold">{!! $requestor_id !!}</div>
          </div>
          <div class="mb-2">
            <span class="text-muted">Indent Source</span>
            <div><span class="badge bg-info text-dark">{!! $indent_source !!}</span></div>
          </div>
          <div class="mb-0">
            <span class="text-muted">Remarks</span>
            <div class="fw-semibold">{!! $remarks !!}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Additional Details -->
    <div class="mt-4">
      <h6 class="text-uppercase text-muted mb-2">Additional Details</h6>
      <div class="p-3 rounded-3 border">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-0">
              <span class="text-muted">Project Name</span>
              <div class="fw-semibold">{!! $project_name !!}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Lines Table -->
    <div class="mt-4" id="section-to-print">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Line Items</h6>
        <small class="text-muted">Total lines: {{ count($vlinesdata ?? []) }}</small>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th style="width: 80px;">Line No</th>
              <th>Product Name</th>
              <th>Product Description</th>
              <th style="width: 120px;">UOM</th>
              <th style="width: 120px;" class="text-end">Qty</th>
              <th style="width: 160px;">Need By Date</th>
              <th>Comments</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($vlinesdata as $key => $value)
              <tr>
                <td>{{ $key + 1 }}</td>
                <td class="fw-semibold">{{ $value->concatenated_product }}</td>
                <td>{{ $value->product_description }}</td>
                <td><span class="badge bg-secondary">{{ $value->uom_code }}</span></td>
                <td class="text-end">{{ number_format((float)$value->qty, 2) }}</td>
                <td>
                  @php
                    $nbd = $value->need_by_date ?? '';
                  @endphp
                  {{ $nbd ? \Carbon\Carbon::parse($nbd)->format('Y-m-d') : '—' }}
                </td>
                <td>{{ $value->comments }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted">No line items available.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

@endsection
