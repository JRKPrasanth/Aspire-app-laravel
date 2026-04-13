@extends('layouts.header')
@section('content')
<h3 class="text-danger">Product Account Setting</h3>
@include('layouts.breadcrumb')

		
<form>
<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Product Account Setting</h5>
      <a href="../productaccountsettings" class="btn btn-sm btn-danger">
        <i class="bi bi-x-lg"></i>
      </a>
    </div>

    <div class="card-body">
      <div class="row g-4">
        <!-- Left Column -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label fw-bold">Product Group Name:</label>
            <div class="text-muted">{!! $group_name !!}</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Product Category Name:</label>
            <div class="text-muted">{!! $category_name !!}</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Product Subcategory Name:</label>
            <div class="text-muted">{!! $subcategory_name !!}</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Active:</label>
            <div>
              @if($active == 'Yes')
                <span class="badge bg-success">Active</span>
              @else
                <span class="badge bg-secondary">Inactive</span>
              @endif
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label fw-bold">Product Account Code:</label>
            <div class="text-muted">{!! $sub1 !!}</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Discount Account Code:</label>
            <div class="text-muted">{!! $sub2 !!}</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Control Account Code:</label>
            <div class="text-muted">{!! $sub3 !!}</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Created By:</label>
            <div class="text-muted">{!! $first_name !!}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
			

		
		
	

@endsection


