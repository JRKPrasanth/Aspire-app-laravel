@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Quotation Compare</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white fw-semibold"></div>
        <form action="{{ url('quotationcompare') }}" method="get" id="searchForm" class="p-4 bg-light rounded shadow-sm">
            <div class="row g-3">

                <!-- Product -->
                <div class="col-md-3">
                    <label for="product_id" class="form-label fw-bold">Product</label>
                    <select name="product_id" id="product_id" class="form-select select2 product_id" required>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>

                <!-- From Date -->
                <div class="col-md-3">
                    <label for="start_date" class="form-label fw-bold">From Date</label>
                    <div class="input-group">

                        <input type="text" class="form-control start_date1" id="start_date" name="start_date" required
                            autocomplete="off">
                    </div>
                </div>

                <!-- To Date -->
                <div class="col-md-3">
                    <label for="end_date" class="form-label fw-bold">To Date</label>
                    <div class="input-group">

                        <input type="text" class="form-control end_date1" id="end_date" name="end_date" required
                            autocomplete="off">
                    </div>
                </div>

                <!-- Supplier -->
                <div class="col-md-3">
                    <label for="supplier_id" class="form-label fw-bold">Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-select supplier_id select2" style="width:100%"
                        required>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="col-md-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-bar-chart"></i> Quote Compare
                    </button>
                </div>
            </div>
        </form>
    </div>



    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th class="align text-white bg-danger text-center">Line No</th>
                        <th class="align text-white bg-danger text-center">Quote Date</th>
                        <th class="align text-white bg-danger text-center">Quote Number</th>
                        <th class="align text-white bg-danger text-center">Supplier Name</th>
                        <th class="align text-white bg-danger text-center">Enquiry Number</th>
                        <th class="align text-white bg-danger text-center">Product Name</th>
                        <th class="align text-white bg-danger text-center">Price</th>
                        <th class="align text-white bg-danger text-center">Discount Amount</th>
                        <th class="align text-white bg-danger text-center">Transport Charges </th>
                        <th class="align text-white bg-danger text-center">Insurance Charges </th>
                        <th class="align text-white bg-danger text-center">Packing Charges </th>
                        <th class="align text-white bg-danger text-center">Unloading Charges </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $key = 1 ?>
                    <?php if ($data != '') { ?>
                    <?php    foreach ($data as $val) { ?>
                    <tr>


                        <td>{{$key}}</td>
                        <td>{{ $val->quotation_date }}</td>
                        <td>{{ $val->quotation_no }}</td>
                        <td>{{ $val->supplier_name }}</td>
                        <td>{{ $val->reference_number }}</td>
                        <td>{{ $val->product_name }}</td>
                        <td>{{ $val->unit_price }}</td>
                        <td>{{ $val->discount_amount }}</td>
                        <td>{{ $val->transport_charges }}</td>
                        <td>{{ $val->insurance_charges }}</td>
                        <td>{{ $val->packing_charges }}</td>
                        <td>{{ $val->unloading_charges }}</td>

                    </tr>
                    <?php        $key++; ?>
                    <?php    } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>


@endsection
@push('scripts')


    <script>

        $(document).ready(function () {

            // Generic function to load options via AJAX
            function loadDropdown(selector, url, selectedValue = "") {
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function (data) {
                        var $dropdown = $(selector);
                        $dropdown.empty().append('<option value="">-- Please Select --</option>');

                        // Ensure we have parsed JSON
                        if (typeof data === 'string') {
                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                console.error("Invalid JSON from " + url, data);
                                return;
                            }
                        }

                        $.each(data, function (i, item) {
                            let selected = (item.val == selectedValue) ? 'selected' : '';
                            $dropdown.append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                        });

                        $dropdown.trigger('change.select2');
                    },
                    error: function (xhr, status, error) {
                        console.error("Error loading " + selector + ":", error);
                    }
                });
            }


            // Load supplier_id
            loadDropdown(
                ".supplier_id",
                "{{ URL::to('jcomboformallcheck?table=m_supplier_t:supplier_id:supplier_number|supplier_name') }}"
            );

            // Load product_id
            loadDropdown(
                ".product_id",
                "{{ URL::to('jcomboformallchecknew?table=m_products_t:product_id:product_code|concatenated_product') }}&condition=product_group_id in(2,3,12,13,10,14,15,17)"
            );



        });


        $(document).ready(function () {
            $('#Table1').DataTable({

                scrollX: true,
                scrollY: "60vh"

            });
        });


        $(document).ready(function () {

            var productName = "{{ request('product_id') }}";
            var productGroup = "{{ request('supplier_id') }}";
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('.product_id').select2();
            $('#product_id').val(productName).trigger('change');
            $('.supplier_id').select2();
            $('#supplier_id').val(productGroup).trigger('change');

            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

        });


    </script>

@endpush