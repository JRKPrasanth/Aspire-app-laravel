@extends('layouts.header')
@section('content')
<div class="container my-5">
    <h3 class="text-danger">Pricelist Download</h3>
    @include('layouts.breadcrumb')

    @php
        $groupname = \Session::get('groupname'); 
        $emp_id = \Session::get('emp_id');
        $zone = \Session::get('zone');
    @endphp

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body p-4">

            <!-- Nav Pills -->
            <ul class="nav nav-pills nav-justified mb-4" id="priceTabs" role="tablist">
                @if($job_id =='42' || $job_id =='37')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="stockiest-tab" data-bs-toggle="pill" data-bs-target="#stockiest" type="button" role="tab">
                            Stockiest Pricelist
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="chemist-tab" data-bs-toggle="pill" data-bs-target="#chemist" type="button" role="tab">
                            Chemist Pricelist
                        </button>
                    </li>
                @else
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="distributor-tab" data-bs-toggle="pill" data-bs-target="#distributor" type="button" role="tab">
                            Distributor Pricelist
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="stockiest-tab" data-bs-toggle="pill" data-bs-target="#stockiest" type="button" role="tab">
                            Stockiest Pricelist
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="chemist-tab" data-bs-toggle="pill" data-bs-target="#chemist" type="button" role="tab">
                            Chemist Pricelist
                        </button>
                    </li>
                @endif
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="priceTabsContent">

                <!-- Distributor Tab -->
                <div class="tab-pane fade show active" id="distributor" role="tabpanel">
                    <h4 class="fw-bold text-primary mb-3">Distributor Pricelist</h4>
                    @if($groupname == "14" || in_array($emp_id, ["302","152","525","156","151","160","155","474","531","598","240"]))
                        <div class="row g-4">
                            <!-- Pioneer -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold">PRICE LIST WEF SEP 25 GST 2.0_PIONEER</h6>
                                        <a href="../Uploads/pricelist_uploads_v4/distributor/PRICE LIST WEF SEP 25 GST 2.0_PIONEER_DISTRIBUTOR.pdf"
                                           class="btn btn-outline-primary mt-2" download>
                                           <i class="bi bi-download me-2"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Ayurvedic -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold">PRICE LIST WEF May 24_AYURVEDIC</h6>
                                        <a href="{{ ($groupname == '14' && $zone != '16') || in_array($emp_id, ['302','152','156','151','160','53','155','474','531','598','240']) 
                                                ? '../Uploads/pricelist_uploads_v2/distributor/DISTRIBUTOR AYURVEDIC PRICE LIST WEF May 24.pdf' 
                                                : '../Uploads/pricelist_uploads_v2/wb_classical/WB_DISTRIBUTOR AYURVEDIC PRICE LIST WEF May 24.pdf' }}"
                                           class="btn btn-outline-success mt-2" download>
                                           <i class="bi bi-download me-2"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Cosmetics -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold">PRICE LIST WEF SEP 25 GST 2.0_COSMETICS</h6>
                                        <a href="../Uploads/pricelist_uploads_v4/distributor/PRICE LIST WEF SEP 25 GST 2.0_COSMETICS_DISTRIBUTOR.pdf"
                                           class="btn btn-outline-warning mt-2" download>
                                           <i class="bi bi-download me-2"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="../images/error_images/sign.png" width="120" class="mb-3">
                            <h3 class="fw-bold text-danger">ACCESS RESTRICTED!</h3>
                            <p class="text-danger">Sorry, you do not have access to this page.</p>
                        </div>
                    @endif
                </div>

                <!-- Stockiest Tab -->
                <div class="tab-pane fade" id="stockiest" role="tabpanel">
                    <h4 class="fw-bold text-primary mb-3">Stockiest Pricelist</h4>
                    @if($groupname == "14" || in_array($emp_id, ["302","152","525","156","151","160","155","474","531","598","240"]))
                        <div class="row g-4">
                            <!-- Pioneer -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold">PRICE LIST WEF SEP 25 GST 2.0_PIONEER</h6>
                                        <a href="../Uploads/pricelist_uploads_v4/stockiest/PRICE LIST WEF SEP 25 GST 2.0_PIONEER_STOCKIEST.pdf"
                                           class="btn btn-outline-primary mt-2" download>
                                           <i class="bi bi-download me-2"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Ayurvedic -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold">PRICE LIST WEF May 24_AYURVEDIC</h6>
                                        <a href="{{ ($groupname == '14' && $zone != '16') || in_array($emp_id, ['302','152','156','151','160','53','155','474','531','598','240']) 
                                                ? '../Uploads/pricelist_uploads_v2/stockiest/STOCKIEST AYURVEDIC PRICE LIST WEF May 24.pdf' 
                                                : '../Uploads/pricelist_uploads_v2/wb_classical/WB_STOCKIEST AYURVEDIC PRICE LIST WEF May 24.pdf' }}"
                                           class="btn btn-outline-success mt-2" download>
                                           <i class="bi bi-download me-2"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Cosmetics -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold">PRICE LIST WEF SEP 25 GST 2.0_COSMETICS</h6>
                                        <a href="../Uploads/pricelist_uploads_v4/stockiest/PRICE LIST WEF SEP 25 GST 2.0_COSMETICS_STOCKIEST.pdf"
                                           class="btn btn-outline-warning mt-2" download>
                                           <i class="bi bi-download me-2"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="../images/error_images/sign.png" width="120" class="mb-3">
                            <h3 class="fw-bold text-danger">ACCESS RESTRICTED!</h3>
                            <p class="text-danger">Sorry, you do not have access to this page.</p>
                        </div>
                    @endif
                </div>

                <!-- Chemist Tab -->
                <div class="tab-pane fade" id="chemist" role="tabpanel">
                    <h4 class="fw-bold text-primary mb-3">Chemist Pricelist</h4>
                    @if($groupname == "14" || in_array($emp_id, ["302","152","525","156","151","160","155","474","531","598","240"]))
                        <div class="row g-4">
                            <!-- Pioneer -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold">PRICE LIST WEF SEP 25 GST 2.0_PIONEER</h6>
                                        <a href="../Uploads/pricelist_uploads_v4/chemist/PRICE LIST WEF SEP 25 GST 2.0_PIONEER_CHEMIST.pdf"
                                           class="btn btn-outline-primary mt-2" download>
                                           <i class="bi bi-download me-2"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Ayurvedic -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold">PRICE LIST WEF May 24_AYURVEDIC</h6>
                                        <a href="{{ ($groupname == '14' && $zone != '16') || in_array($emp_id, ['302','152','156','151','160','53','155','474','531','598','240']) 
                                                ? '../Uploads/pricelist_uploads_v2/chemist/CHEMIST AYURVEDIC PRICE LIST WEF May 24.pdf' 
                                                : '../Uploads/pricelist_uploads_v2/wb_classical/WB_CHEMIST AYURVEDIC PRICE LIST WEF May 24.pdf' }}"
                                           class="btn btn-outline-success mt-2" download>
                                           <i class="bi bi-download me-2"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Cosmetics -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold">PRICE LIST WEF SEP 25 GST 2.0_COSMETICS</h6>
                                        <a href="../Uploads/pricelist_uploads_v4/chemist/PRICE LIST WEF SEP 25 GST 2.0_COSMETICS_CHEMIST.pdf"
                                           class="btn btn-outline-warning mt-2" download>
                                           <i class="bi bi-download me-2"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="../images/error_images/sign.png" width="120" class="mb-3">
                            <h3 class="fw-bold text-danger">ACCESS RESTRICTED!</h3>
                            <p class="text-danger">Sorry, you do not have access to this page.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
