@extends('layouts.header')
@section('content')
<h3 class="text-danger">Primary Sales And Target</h3>
@include('layouts.breadcrumb')

  <div class="row g-4">

  <!-- Card 1 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesandtarget" class="text-decoration-none">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-body d-flex align-items-center btn btn-outline-primary">
          <i class="bi bi-graph-up fs-3 me-3 text-primary"></i>
          <span class="fw-semibold">Month and Zone Wise</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 2 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesandtargetregion" class="text-decoration-none">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-body d-flex align-items-center btn btn-outline-success">
          <i class="bi bi-geo-alt fs-3 me-3 text-success"></i>
          <span class="fw-semibold">Region Wise</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 3 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesandtargetstate" class="text-decoration-none">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-body d-flex align-items-center btn btn-outline-warning">
          <i class="bi bi-map fs-3 me-3 text-warning"></i>
          <span class="fw-semibold">Region and State Wise</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 4 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesandtargetarea" class="text-decoration-none">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-body d-flex align-items-center btn btn-outline-info">
          <i class="bi bi-map-fill fs-3 me-3 text-info"></i>
          <span class="fw-semibold">State and Area Wise</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 5 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesdistributor" class="text-decoration-none">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-body d-flex align-items-center btn btn-outline-dark">
          <i class="bi bi-person-lines-fill fs-3 me-3 text-dark"></i>
          <span class="fw-semibold">Distributor Wise</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 6 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesandtargetproduct" class="text-decoration-none">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-body d-flex align-items-center btn btn-outline-secondary">
          <i class="bi bi-box-seam fs-3 me-3 text-secondary"></i>
          <span class="fw-semibold">Product Wise</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 7 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesdistributorpropack" class="text-decoration-none">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-body d-flex align-items-center btn btn-outline-primary">
          <i class="bi bi-boxes fs-3 me-3 text-primary"></i>
          <span class="fw-semibold">Product Pack Wise</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 8 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesplanandshort" class="text-decoration-none">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-body d-flex align-items-center btn btn-outline-success">
          <i class="bi bi-exclamation-circle fs-3 me-3 text-success"></i>
          <span class="fw-semibold">Plan with Shortage</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 9 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesmonachive" class="text-decoration-none">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-body d-flex align-items-center btn btn-outline-danger">
          <i class="bi bi-calendar-check fs-3 me-3 text-danger"></i>
          <span class="fw-semibold">Month Achievement</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 10 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesstockdts" class="text-decoration-none">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-body d-flex align-items-center btn btn-outline-info">
          <i class="bi bi-building fs-3 me-3 text-info"></i>
          <span class="fw-semibold">State Wise Sale With Mon</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 11 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesdistwise" class="text-decoration-none">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-body d-flex align-items-center btn btn-outline-warning">
          <i class="bi bi-diagram-2-fill fs-3 me-3 text-warning"></i>
          <span class="fw-semibold">Dist Wise Sale With Mon</span>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 12 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysaleszonepro" class="text-decoration-none">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-body d-flex align-items-center btn btn-outline-danger">
          <i class="bi bi-diagram-3-fill fs-3 me-3 text-danger"></i>
          <span class="fw-semibold">Zone & Pro Trg & Sale</span>
        </div>
      </div>
    </a>
  </div>
</div>


@endsection