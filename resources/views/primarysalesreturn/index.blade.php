@extends('layouts.header')
@section('content')
<h3 class="text-danger">Sales Return</h3>
@include('layouts.breadcrumb')

<style>
  .fancy-card {
    border: none;
    border-radius: 1rem;
    transition: all 0.3s ease;
    text-align: center;
    background: linear-gradient(145deg, #f4f4f4, #ffffff);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  }
  .fancy-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
  }
  .fancy-icon {
    width: 70px;
    height: 70px;
    line-height: 70px;
    margin: 1rem auto;
    border-radius: 50%;
    font-size: 28px;
    color: white;
  }
  .fancy-title {
    font-weight: 600;
    font-size: 1rem;
  }
</style>

<div class="row g-4">

  <!-- 1 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesreturn" class="text-decoration-none text-dark">
      <div class="card shadow-lg fancy-card">
        <div class="fancy-icon bg-primary">
          <i class="bi bi-bar-chart-line-fill"></i>
        </div>
        <div class="card-body btn btn-outline-primary">
          <div class="fancy-title">Month & Zone Wise</div>
        </div>
      </div>
    </a>
  </div>

  <!-- 2 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesreturnregion" class="text-decoration-none text-dark">
      <div class="card shadow-lg fancy-card">
        <div class="fancy-icon bg-success">
          <i class="bi bi-map-fill"></i>
        </div>
        <div class="card-body btn btn-outline-success">
          <div class="fancy-title">Region Wise</div>
        
        </div>
      </div>
    </a>
  </div>

  <!-- 3 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesreturnstate" class="text-decoration-none text-dark">
      <div class="card shadow-lg fancy-card">
        <div class="fancy-icon bg-warning">
          <i class="bi bi-geo-alt-fill"></i>
        </div>
        <div class="card-body btn btn-outline-warning">
          <div class="fancy-title">State Wise</div>
         
        </div>
      </div>
    </a>
  </div>

  <!-- 4 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesreturnproduct" class="text-decoration-none text-dark">
      <div class="card shadow-lg fancy-card">
        <div class="fancy-icon bg-info">
          <i class="bi bi-box-seam"></i>
        </div>
        <div class="card-body btn btn-outline-info">
          <div class="fancy-title">Product Wise</div>
        
        </div>
      </div>
    </a>
  </div>

  <!-- 5 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesreturnzoneratio" class="text-decoration-none text-dark">
      <div class="card shadow-lg fancy-card">
        <div class="fancy-icon bg-secondary">
          <i class="bi bi-pie-chart-fill"></i>
        </div>
        <div class="card-body btn btn-outline-secondary">
          <div class="fancy-title">Zone Wise Ratio</div>
   
        </div>
      </div>
    </a>
  </div>

  <!-- 6 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesreturnregionratio" class="text-decoration-none text-dark">
      <div class="card shadow-lg fancy-card">
        <div class="fancy-icon bg-danger">
          <i class="bi bi-pie-chart"></i>
        </div>
        <div class="card-body btn btn-outline-danger">
          <div class="fancy-title">Region Wise Ratio</div>
     
        </div>
      </div>
    </a>
  </div>

  <!-- 7 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesreturnstateratio" class="text-decoration-none text-dark">
      <div class="card shadow-lg fancy-card">
        <div class="fancy-icon bg-dark">
          <i class="bi bi-percent"></i>
        </div>
        <div class="card-body btn btn-outline-dark">
          <div class="fancy-title">State Wise Ratio</div>
    
        </div>
      </div>
    </a>
  </div>

  <!-- 8 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesreturnpermonth" class="text-decoration-none text-dark">
      <div class="card shadow-lg fancy-card">
        <div class="fancy-icon bg-primary">
          <i class="bi bi-calendar3"></i>
        </div>
        <div class="card-body btn btn-outline-primary">
          <div class="fancy-title">Month Wise Return</div>
          
        </div>
      </div>
    </a>
  </div>

  <!-- 9 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesreturperzone" class="text-decoration-none text-dark">
      <div class="card shadow-lg fancy-card">
        <div class="fancy-icon bg-success">
          <i class="bi bi-ui-checks-grid"></i>
        </div>
        <div class="card-body btn btn-outline-success">
          <div class="fancy-title">Zone Wise Return</div>
         
        </div>
      </div>
    </a>
  </div>

  <!-- 10 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesreturnsperregion" class="text-decoration-none text-dark">
      <div class="card shadow-lg fancy-card">
        <div class="fancy-icon bg-warning">
          <i class="bi bi-grid-fill"></i>
        </div>
        <div class="card-body btn btn-outline-warning">
          <div class="fancy-title">Region Wise Return</div>
        
        </div>
      </div>
    </a>
  </div>

  <!-- 11 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesreturperstate" class="text-decoration-none text-dark">
      <div class="card shadow-lg fancy-card">
        <div class="fancy-icon bg-info">
          <i class="bi bi-layers"></i>
        </div>
        <div class="card-body btn btn-outline-info">
          <div class="fancy-title">State Wise Return</div>
      
        </div>
      </div>
    </a>
  </div>

  <!-- 12 -->
  <div class="col-lg-3 col-md-4 col-sm-6">
    <a href="primarysalesreturnsperperson" class="text-decoration-none text-dark">
      <div class="card shadow-lg fancy-card">
        <div class="fancy-icon bg-danger">
          <i class="bi bi-person-badge"></i>
        </div>
        <div class="card-body btn btn-outline-danger">
          <div class="fancy-title">Person Wise Return</div>
       
        </div>
      </div>
    </a>
  </div>

</div>




@endsection