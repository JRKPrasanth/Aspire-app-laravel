@extends('layouts.header')
@section('content')
<h3 class="text-danger">Hq wise - Secondary sales and target</h3>
@include('layouts.breadcrumb')

  <style>

    .report-tile {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      transition: 0.3s;
      text-align: center;
      background-color: #fff;
      height: 100%;
    }

    .report-tile:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
    }

    .report-header {
      background: #1c2c78;
      color: white;
      padding: 1.2rem;
      font-size: 1.5rem;
    }

    .report-body {
      padding: 1rem;
      font-weight: 600;
      color: #000;
      background: #dfdfdf;
      font-size: 1rem;
      min-height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .report-link {
      text-decoration: none;
      color: inherit;
    }
    
  </style>

  <div class="row g-4 mt-4 mb-4">

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="misreport" class="report-link">
        <div class="report-tile">
          <div class="report-header bg-primary">
            <i class="bi bi-person-check-fill"></i>
          </div>
          <div class="report-body">Person wise Trt & Ach</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="misreporthq" class="report-link">
        <div class="report-tile">
          <div class="report-header bg-success">
            <i class="bi bi-building"></i>
          </div>
          <div class="report-body">HQ Wise</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="misreportsaleandpurc" class="report-link">
        <div class="report-tile">
          <div class="report-header bg-danger">
            <i class="bi bi-graph-up-arrow"></i>
          </div>
          <div class="report-body">Distributor & Stockist Trend</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="misreportcolbal" class="report-link">
        <div class="report-tile">
          <div class="report-header bg-warning">
            <i class="bi bi-wallet2"></i>
          </div>
          <div class="report-body">HQ & Dist CB</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="prosearchrpt" class="report-link">
        <div class="report-tile">
          <div class="report-header bg-info">
            <i class="bi bi-search"></i>
          </div>
          <div class="report-body">Product Search</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="misreporthqpro" class="report-link">
        <div class="report-tile">
          <div class="report-header bg-secondary">
            <i class="bi bi-boxes"></i>
          </div>
          <div class="report-body">HQ Wise Product</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="misreporthqclobal" class="report-link">
        <div class="report-tile">
          <div class="report-header bg-dark">
            <i class="bi bi-clipboard2-data-fill"></i>
          </div>
          <div class="report-body">HQ Wise Clo.Bal</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="misreportprohqsale" class="report-link">
        <div class="report-tile">
          <div class="report-header bg-primary">
            <i class="bi bi-bag-check"></i>
          </div>
          <div class="report-body">Product HQ Wise Sale</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="misreportpayout" class="report-link">
        <div class="report-tile">
          <div class="report-header bg-success">
            <i class="bi bi-cash-coin"></i>
          </div>
          <div class="report-body">Payment Outstanding</div>
        </div>
      </a>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
      <a href="asmpersonproduction" class="report-link">
        <div class="report-tile">
          <div class="report-header bg-danger">
            <i class="bi bi-bar-chart-steps"></i>
          </div>
          <div class="report-body">Person Productivity</div>
        </div>
      </a>
    </div>

  </div>



@endsection