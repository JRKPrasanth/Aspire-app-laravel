@extends('layouts.header')
@section('content')

<div class="container my-4">
    <div class="card shadow-lg border-0 rounded-3">
        
        <!-- Header -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Product Details</h5>
            <a href="../product" class="btn btn-sm btn-danger">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>

        <!-- Body -->
        <div class="card-body normalform">
            
            <!-- Product Info -->
            <div class="mb-4">
                <h5 class="fw-bold text-secondary border-bottom pb-2">Basic Information</h5>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p class="mb-2"><b>Product Code:</b> {{ $values['product_code'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><b>Product Name:</b> {{ $values['concatenated_product'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Attachments Table -->
            <div class="mt-4">
                <h5 class="fw-bold text-secondary border-bottom pb-2">Attachments</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-warning">
                            <tr>
                                <th scope="col">Line No</th>
                                <th scope="col">Product Image</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($linesdata))
                                <?php foreach ($linesdata as $key => $v): ?>
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a download 
                                               href="{{ URL::to('') }}/uploads/product_image/{{ $product_id }}/{{ $v->choosefile }}" 
                                               class="text-decoration-none">
                                                <i class="bi bi-file-earmark-image me-1 text-primary"></i>
                                                {{ $v->choosefile }}
                                                <img src="{{ URL::to('') }}/images/download.png" 
                                                     height="18" width="18" alt="download">
                                            </a>
                                        </td>
                                        <td>{{ $v->image_date }}</td>
                                    </tr>
                                <?php endforeach; ?>
                            @else
                                <tr class="text-muted">
                                    <td colspan="3">No Files Attached</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>




@endsection