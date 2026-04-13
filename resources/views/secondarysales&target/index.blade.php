@extends('layouts.header')
@section('content')
<h3 class="text-danger mb-4">Secondary Sales And Target</h3>
@include('layouts.breadcrumb')

<style>
  .pill-card {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    border-radius: 2rem;
	background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    transition: 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
  }

  .pill-card:hover {
    transform: scale(1.03);
    background: linear-gradient(90deg, #d0eaff, #fff);
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
  }

  .pill-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background-color: #0d6efd;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 18px;
    margin-right: 1rem;
  }

  .pill-text {
    font-weight: 600;
    font-size: 1rem;
    color: #333;
  }

  .link-wrapper {
    text-decoration: none;
    display: block;
  }
</style>

<div class="row g-3">

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargetmonth" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon"><i class="bi bi-calendar-event"></i></div>
        <div class="pill-text">Month Wise</div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargetzone" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon bg-success"><i class="bi bi-geo-alt"></i></div>
        <div class="pill-text">Zone Wise</div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargetregion" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon bg-danger"><i class="bi bi-globe-asia-australia"></i></div>
        <div class="pill-text">Region Wise</div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargetstate" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon bg-info"><i class="bi bi-map"></i></div>
        <div class="pill-text">Region & State Wise</div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargetdistributor" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon bg-warning"><i class="bi bi-truck"></i></div>
        <div class="pill-text">Distributor Wise</div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargethq" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon bg-primary"><i class="bi bi-building"></i></div>
        <div class="pill-text">HQ Wise</div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargetperson" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon bg-secondary"><i class="bi bi-person-lines-fill"></i></div>
        <div class="pill-text">Person & Month Productivity</div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargetproduct" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon bg-dark"><i class="bi bi-box"></i></div>
        <div class="pill-text">Product Wise</div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargetteam" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon bg-success"><i class="bi bi-people-fill"></i></div>
        <div class="pill-text">Team - Target VS Achivement </div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargetteammonth" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon bg-danger"><i class="bi bi-calendar-check"></i></div>
        <div class="pill-text">Target VS Achivement - Month</div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargetmtstrend" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon bg-info"><i class="bi bi-graph-up-arrow"></i></div>
        <div class="pill-text">Sales & Purchase Trend</div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargetclobal" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon bg-warning"><i class="bi bi-diagram-3-fill"></i></div>
        <div class="pill-text">Distributor Wise Closing Balance</div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargetperclobal" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon bg-primary"><i class="bi bi-person-vcard-fill"></i></div>
        <div class="pill-text">Person Wise Closing Balance</div>
      </div>
    </a>
  </div>

  <div class="col-md-6 col-lg-4">
    <a href="salesandtargetpayout" class="link-wrapper">
      <div class="pill-card">
        <div class="pill-icon bg-dark"><i class="bi bi-cash-coin"></i></div>
        <div class="pill-text">Payment Outstanding</div>
      </div>
    </a>
  </div>

</div>




@endsection
