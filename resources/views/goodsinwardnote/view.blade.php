@extends('layouts.header')
@section('content')
<h3 class="text-danger">Goods Inward Note</h3>
@include('layouts.breadcrumb')



<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <h5 class="mb-0 text-primary">Goods Inward Note Details</h5>
        <a href="{{ URL::to('goodsinwardnote') }}" class="btn btn-sm btn-danger">
            <i class="fa fa-times"></i>
        </a>
    </div>

    <div class="card-body">
        <form>
            <div class="container-fluid">
                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <span class="fw-bold">GIN Number:</span>
                            <span class="text-muted">{{ $ginvdata[0]->gin_number }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="fw-bold">DC Number:</span>
                            <span class="text-muted">{{ $ginvdata[0]->dc_number }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="fw-bold">GIN Status:</span>
                            <span class="badge bg-info text-dark">{{ $ginvdata[0]->gin_status }}</span>
                        </div>
                    </div>

                    <!-- Middle Column -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <span class="fw-bold">GIN Description:</span>
                            <span class="text-muted">{{ $ginvdata[0]->gin_description }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="fw-bold">DC Date:</span>
                            <span class="text-muted">{{ $ginvdata[0]->dc_date }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="fw-bold">Created By:</span>
                            <span class="text-muted">{{ $created_by }}</span>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <span class="fw-bold">Supplier Name:</span>
                            <span class="text-muted">{{ $supplier_name }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="fw-bold">Total Product Packs:</span>
                            <span class="text-muted">{{ $ginvdata[0]->total_packs }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


	

@endsection