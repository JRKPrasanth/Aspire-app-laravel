@extends('layouts.header')
@section('content')
<h3 class="text-danger mb-3">Sample to sale ratio</h3>
@include('layouts.breadcrumb')
<style>
  .custom-card {
    transition: transform 0.3s, box-shadow 0.3s;
    border: 1px solid #dee2e6;
    border-radius: 1rem;
    padding: 1.5rem 1rem;
    text-align: center;
    height: 100%;
    background-color: #fff;
  }

  .custom-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem #000;
  }

  .custom-card-icon {
    width: 60px;
    height: 60px;
    line-height: 60px;
    margin: 0 auto 1rem auto;
    font-size: 1.75rem;
    border-radius: 50%;
    color: #fff;
  }

  .custom-card-title {
    font-weight: 600;
    font-size: 1rem;
    color: #333;
  }

  .custom-card a {
    text-decoration: none;
    color: inherit;
  }
</style>

<div class="row g-3">
  <!-- Card 1 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysaleratio">
      <div class="custom-card border border-primary">
        <div class="custom-card-icon bg-primary">
          <i class="bi bi-calendar-range"></i>
        </div>
        <div class="custom-card-title fw-bold">Month And Zone Wise</div>
      </div>
    </a>
  </div>

  <!-- Card 2 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysaleratioregion">
      <div class="custom-card border border-success">
        <div class="custom-card-icon bg-success">
          <i class="bi bi-geo"></i>
        </div>
        <div class="custom-card-title fw-bold">Region Wise</div>
      </div>
    </a>
  </div>

  <!-- Card 3 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysaleratiostate">
      <div class="custom-card border border-warning">
        <div class="custom-card-icon bg-warning">
          <i class="bi bi-map"></i>
        </div>
        <div class="custom-card-title fw-bold">State Wise</div>
      </div>
    </a>
  </div>

  <!-- Card 4 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysaleratiopro">
      <div class="custom-card border border-info">
        <div class="custom-card-icon bg-info">
          <i class="bi bi-box"></i>
        </div>
        <div class="custom-card-title fw-bold">Product Wise</div>
      </div>
    </a>
  </div>

  <!-- Card 5 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalezoneratio">
      <div class="custom-card border border-dark">
        <div class="custom-card-icon bg-dark">
          <i class="bi bi-diagram-3"></i>
        </div>
        <div class="custom-card-title fw-bold">Zone Wise Ratio</div>
      </div>
    </a>
  </div>

  <!-- Card 6 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysaleregratio">
      <div class="custom-card border border-secondary">
        <div class="custom-card-icon bg-secondary">
          <i class="bi bi-diagram-3-fill"></i>
        </div>
        <div class="custom-card-title fw-bold">Region Wise Ratio</div>
      </div>
    </a>
  </div>

  <!-- Card 7 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalestaratio">
      <div class="custom-card border border-primary">
        <div class="custom-card-icon bg-primary">
          <i class="bi bi-map-fill"></i>
        </div>
        <div class="custom-card-title fw-bold">State Wise Ratio</div>
      </div>
    </a>
  </div>

  <!-- Card 8 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarymonthsample">
      <div class="custom-card border border-success">
        <div class="custom-card-icon bg-success">
          <i class="bi bi-calendar"></i>
        </div>
        <div class="custom-card-title fw-bold">Month Wise Sample</div>
      </div>
    </a>
  </div>

  <!-- Card 9 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primaryzonesample">
      <div class="custom-card border border-warning">
        <div class="custom-card-icon bg-warning">
          <i class="bi bi-compass"></i>
        </div>
        <div class="custom-card-title fw-bold">Zone Wise Sample</div>
      </div>
    </a>
  </div>

  <!-- Card 10 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primaryregionsample">
      <div class="custom-card border border-info">
        <div class="custom-card-icon bg-info">
          <i class="bi bi-globe2"></i>
        </div>
        <div class="custom-card-title fw-bold">Region Wise Sample</div>
      </div>
    </a>
  </div>

  <!-- Card 11 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarystatesample">
      <div class="custom-card border border-danger">
        <div class="custom-card-icon bg-danger">
          <i class="bi bi-geo-alt-fill"></i>
        </div>
        <div class="custom-card-title fw-bold">State Wise Sample</div>
      </div>
    </a>
  </div>

  <!-- Card 12 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarypersonsample">
      <div class="custom-card border border-dark">
        <div class="custom-card-icon bg-dark">
          <i class="bi bi-person"></i>
        </div>
        <div class="custom-card-title fw-bold">Person Wise Sample</div>
      </div>
    </a>
  </div>
</div>
@endsection