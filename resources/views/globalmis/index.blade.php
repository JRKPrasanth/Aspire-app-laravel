@extends('layouts.header')
@section('content')
<h3 class="text-danger mb-3">Global MIS</h3>
@include('layouts.breadcrumb')
<style>
  .mis-card {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--bs-box-shadow-lg) !important;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
    background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 1.5rem 1rem;
  }

  .mis-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
background-image: linear-gradient(to top, #d299c2 0%, #fef9d7 100%);
  }

  .mis-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 1rem;
    background: linear-gradient(135deg, #1c2c78, #3949ab);
    color: #fff;
    font-size: 1.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
  }

  .mis-title {
    font-weight: 600;
    font-size: 1rem;
    color: #333;
    line-height: 1.3;
    min-height: 50px;
  }

  .mis-link {
    text-decoration: none;
    color: inherit;
  }
</style>


  <div class="row g-4">

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="globalmisreport" class="mis-link">
        <div class="mis-card">
          <div class="mis-icon"><i class="bi bi-calendar-week-fill"></i></div>
          <div class="mis-title">Month Wise</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="globalmisreportzone" class="mis-link">
        <div class="mis-card">
          <div class="mis-icon"><i class="bi bi-geo-alt-fill"></i></div>
          <div class="mis-title">Zone Wise</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="globalmisreportregion" class="mis-link">
        <div class="mis-card">
          <div class="mis-icon"><i class="bi bi-globe-central-south-asia"></i></div>
          <div class="mis-title">Region Wise</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="globalmisreportdistributor" class="mis-link">
        <div class="mis-card">
          <div class="mis-icon"><i class="bi bi-shop"></i></div>
          <div class="mis-title">Distributor Wise</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="globalmisreportsaletrend" class="mis-link">
        <div class="mis-card">
          <div class="mis-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
          <div class="mis-title">Month Sale Trend</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="globalmisdistrend" class="mis-link">
        <div class="mis-card">
          <div class="mis-icon"><i class="bi bi-graph-up"></i></div>
          <div class="mis-title">Distributor Wise Trend</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="globalmisproduct" class="mis-link">
        <div class="mis-card">
          <div class="mis-icon"><i class="bi bi-box-seam"></i></div>
          <div class="mis-title">Product Wise</div>
        </div>
      </a>
    </div>

  </div>






@endsection