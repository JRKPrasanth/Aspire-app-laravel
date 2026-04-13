@extends('layouts.header')
@section('content')
    <h2 class="text-danger"> Machine Log</h2>
    @include('layouts.breadcrumb')

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <form method="post" action="" id="gin_form" class="gin_form" data-parsley-validate>
                {{ csrf_field() }}
                <input type="hidden" name="id" id="id" value="{{ $row['id'] }}" />

                <div class="row g-4">
                    <!-- Column 1 -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="machine_id" class="form-label"><span class="text-danger">*</span>Machine
                                Name</label>
                            <select name="machine_id" id="machine_id" class="form-select select2" required>
                                {!! $machine_id !!}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="product_id" class="form-label"><span class="text-danger">*</span>Product
                                Name</label>
                            <select name="product_id" id="product_id" class="form-select select2" required>
                                {!! $product_id !!}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="batch_number" class="form-label"><span class="text-danger">*</span>Batch
                                Number</label>
                            <input type="text" name="batch_number" id="batch_number" class="form-control"
                                value="{{ $row['batch_number'] }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="process_dept" class="form-label">Department Name</label>
                            <select name="process_dept" id="process_dept" class="form-select select2">
                                <option value="">--Please Select--</option>
                                <option value="OPERATION" {{ $row['process_dept'] == 'OPERATION' ? 'selected' : '' }}>
                                    OPERATION</option>
                                <option value="PRODUCTION" {{ $row['process_dept'] == 'PRODUCTION' ? 'selected' : '' }}>
                                    PRODUCTION</option>
                            </select>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <input type="text" name="remarks" id="remarks" class="form-control"
                                value="{{ $row['remarks'] }}">
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Activity Date</label>
                            <input type="text" name="date" id="date" class="form-control start_date"
                                value="{{ $row['date'] }}" placeholder="YYYY-MM-DD">
                        </div>

                        <div class="mb-3">
                            <label for="created_by" class="form-label">Created By</label>
                            <select name="created_by" id="created_by" class="form-select select2" readonly>
                                {!! $created_by !!}
                            </select>
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="damages" class="form-label"><span class="text-danger">*</span>Damages</label>
                            <input type="text" name="damages" id="damages" class="form-control"
                                value="{{ $row['damages'] }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="running_hours" class="form-label"><span class="text-danger">*</span>Running
                                Hours</label>
                            <input type="text" name="running_hours" id="running_hours" class="form-control"
                                value="{{ $row['running_hours'] }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label"><span class="text-danger">*</span>Quantity</label>
                            <input type="text" name="quantity" id="quantity" class="form-control"
                                value="{{ $row['quantity'] }}" required>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col text-center">
                        <button type="button" class="btn btn-success saveform px-4 me-2">Save</button>
                        <a href="{{ url('machinelog') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--Karthigaa Purpose For Supplier Search-->
    <div class="modal fade" id="supplierModal">
        <div class="modal-dialog" style="width:80%;">
            <div class="modal-content">
                <!--Moda Header-->
                <div class="modal-header">
                    <h4 class="modal-title"> Supplier Details </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <table id="suppliergrid"></table>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                </div>

            </div>
        </div>
    </div>
    <!--Karthigaa Purpose For Subcontract Supplier Search-->
    <div class="modal fade" id="subcontractModal">
        <div class="modal-dialog" style="width:80%;">
            <div class="modal-content">
                <!--Moda Header-->
                <div class="modal-header">
                    <h4 class="modal-title"> Subcontract Supplier Details </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <table id="subcontractgrid"></table>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                </div>

            </div>
        </div>
    </div>

@endsection
@push('scripts')

    <script>

        $(document).on('click', '.saveform', function () {
            const form = $("#gin_form");
            let dup_chk = true; // Declare duplicate check variable

            form.parsley().validate(); // Validate form using Parsley

            if (form.parsley().isValid() && dup_chk === true) {

                var $btn = $(this);
                $btn.prop('disabled', true);
                const formData = form.serialize(); // Serialize form data

                $.ajax({
                    url: "{{ url('machinelogsave') }}",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.status === "success") {
                            showCustomAlert('Saved successfully!', 'success');
                            setTimeout(() => {
                                window.location.href = "{{ url('machinelog') }}";
                            }, 1500);
                        } else {
                            showCustomAlert(response.message || 'Save failed. Please check your input.', 'error');
                        }
                    },
                    error: function (xhr) {
                        let errorMsg = 'Unexpected error occurred.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        showCustomAlert(errorMsg, 'error');
                    }
                });
            } else {
                showCustomAlert("Please fill out all required fields correctly.", 'warning');
            }
        });


    </script>

@endpush