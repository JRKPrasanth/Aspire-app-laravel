@extends('layouts.header')
<style>
.hover-shadow:hover {
  box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.12);
  transform: translateY(-2px);
  transition: all 0.3s ease;
	background: #ffdfdf;
		font-weight: bold;
}

</style>
@section('content')
<h3 class="text-danger">Cost To Sale Ratio</h3>
@include('layouts.breadcrumb')

<div class="row g-4">

  <!-- Card 1: Month Wise -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarycostsaleratio" class="text-decoration-none">
      <div class="card text-center h-100 shadow-lg border-0 rounded-4 hover-shadow">
        <div class="card-body p-4">
          <div class="mx-auto mb-3 bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center" style="width:60px; height:60px;">
            <i class="bi bi-calendar3 text-primary fs-3"></i>
          </div>
          <h6 class="fw-semibold text-dark mb-1">Month Wise</h6>
          <small class="text-muted">Primary cost ratio by month</small>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 2: Zone Wise -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarycostsaleratiozone" class="text-decoration-none">
      <div class="card text-center h-100 shadow-lg border-0 rounded-4 hover-shadow">
        <div class="card-body p-4">
          <div class="mx-auto mb-3 bg-success bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center" style="width:60px; height:60px;">
            <i class="bi bi-diagram-3 text-success fs-3"></i>
          </div>
          <h6 class="fw-semibold text-dark mb-1">Zone Wise</h6>
          <small class="text-muted">Cost ratio across zones</small>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 3: Region Wise -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarycostsaleratioregion" class="text-decoration-none">
      <div class="card text-center h-100 shadow-lg border-0 rounded-4 hover-shadow">
        <div class="card-body p-4">
          <div class="mx-auto mb-3 bg-warning bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center" style="width:60px; height:60px;">
            <i class="bi bi-globe2 text-warning fs-3"></i>
          </div>
          <h6 class="fw-semibold text-dark mb-1">Region Wise</h6>
          <small class="text-muted">Compare cost by region</small>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 4: State Wise -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarycostsaleratiostate" class="text-decoration-none">
      <div class="card text-center h-100 shadow-lg border-0 rounded-4 hover-shadow">
        <div class="card-body p-4">
          <div class="mx-auto mb-3 bg-info bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center" style="width:60px; height:60px;">
            <i class="bi bi-geo-alt text-info fs-3"></i>
          </div>
          <h6 class="fw-semibold text-dark mb-1">State Wise</h6>
          <small class="text-muted">State-wise cost analysis</small>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 5: Person Wise Cost Distribution -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarycostratiodist" class="text-decoration-none">
      <div class="card text-center h-100 shadow-lg border-0 rounded-4 hover-shadow">
        <div class="card-body p-4">
          <div class="mx-auto mb-3 bg-danger bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center" style="width:60px; height:60px;">
            <i class="bi bi-person-lines-fill text-danger fs-3"></i>
          </div>
          <h6 class="fw-semibold text-dark mb-1">Person Wise Cost</h6>
          <small class="text-muted">Cost distribution by employee</small>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 6: Distributor Wise Sale Value -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarycostratiodistsaletrend" class="text-decoration-none">
      <div class="card text-center h-100 shadow-lg border-0 rounded-4 hover-shadow">
        <div class="card-body p-4">
          <div class="mx-auto mb-3 bg-secondary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center" style="width:60px; height:60px;">
            <i class="bi bi-graph-up-arrow text-secondary fs-3"></i>
          </div>
          <h6 class="fw-semibold text-dark mb-1">Distributor Sale Value</h6>
          <small class="text-muted">Sale trends per distributor</small>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 7: Person Wise Manpower -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarycostratiomanpower" class="text-decoration-none">
      <div class="card text-center h-100 shadow-lg border-0 rounded-4 hover-shadow">
        <div class="card-body p-4">
          <div class="mx-auto mb-3 bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center" style="width:60px; height:60px;">
            <i class="bi bi-people-fill text-primary fs-3"></i>
          </div>
          <h6 class="fw-semibold text-dark mb-1">Manpower (Person Wise)</h6>
          <small class="text-muted">Workforce distribution by person</small>
        </div>
      </div>
    </a>
  </div>

  <!-- Card 8: Reg & State Wise Manpower -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarycostratiomanpowerstate" class="text-decoration-none">
      <div class="card text-center h-100 shadow-lg border-0 rounded-4 hover-shadow">
        <div class="card-body p-4">
          <div class="mx-auto mb-3 bg-dark bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center" style="width:60px; height:60px;">
            <i class="bi bi-person-badge text-dark fs-3"></i>
          </div>
          <h6 class="fw-semibold text-dark mb-1">Reg & State Manpower</h6>
          <small class="text-muted">Manpower by region & state</small>
        </div>
      </div>
    </a>
  </div>

</div>


@endsection