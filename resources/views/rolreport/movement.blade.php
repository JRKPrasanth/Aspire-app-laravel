@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Movement Analysis</h3>
    @include('layouts.breadcrumb')

    <div class="card shadow-lg rounded-4 border-0">
        <form action="{{ url('movementanalysis') }}" method="get" id="searchForm">
            <div class="card-body">
                <div class="row g-4">

                    <!-- Product Select -->
                    <div class="col-md-4">
                        <label for="product_id" class="form-label fw-semibold">Product</label>
                        <select name="product_id" id="product_id" class="form-select select2 product_id" required>

                        </select>
                    </div>

                    <!-- From Date -->
                    <div class="col-md-4">
                        <label for="start_date" class="form-label fw-semibold">From Date</label>
                        <input type="text" class="form-control start_date1" id="start_date" name="start_date" required
                            autocomplete="off">
                    </div>

                    <!-- To Date -->
                    <div class="col-md-4">
                        <label for="end_date" class="form-label fw-semibold">To Date</label>
                        <input type="text" class="form-control end_date1" id="end_date" name="end_date" required
                            autocomplete="off">
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary px-4" id="searchButton">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>


    <div class="row g-4">

        <!-- Open Stock -->
        <div class="col-md-3 col-sm-6">
            <div class="card text-white border-0 shadow rounded-4"
                style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                <div class="card-body text-center">
                    <i class="bi bi-box-arrow-in-down display-4 mb-2"></i>
                    <h6 class="fw-bold mb-1">Open Stock</h6>
                    <h4 class="open">{{ $opng }}</h4>
                </div>
            </div>
        </div>

        <!-- Inward -->
        <div class="col-md-3 col-sm-6">
            <div class="card text-white border-0 shadow rounded-4"
                style="background: linear-gradient(135deg, #11998e, #38ef7d);">
                <div class="card-body text-center">
                    <i class="bi bi-arrow-down-circle-fill display-4 mb-2"></i>
                    <h6 class="fw-bold mb-1">Inward</h6>
                    <h4 class="inward">{{ $inv_ct }}</h4>
                </div>
            </div>
        </div>

        <!-- Outward -->
        <div class="col-md-2 col-sm-6">
            <div class="card text-white border-0 shadow rounded-4"
                style="background: linear-gradient(135deg, #fc4a1a, #f7b733);">
                <div class="card-body text-center">
                    <i class="bi bi-box-arrow-up display-4 mb-2"></i>
                    <h6 class="fw-bold mb-1">Outward</h6>
                    <h4 class="outward">{{ $out_ct }}</h4>
                </div>
            </div>
        </div>

        <!-- Consumables -->
        <div class="col-md-2 col-sm-6">
            <div class="card text-white border-0 shadow rounded-4"
                style="background: linear-gradient(135deg, #cc2b5e, #753a88);">
                <div class="card-body text-center">
                    <i class="bi bi-tools display-4 mb-2"></i>
                    <h6 class="fw-bold mb-1">Consumables</h6>
                    <h4 class="consumb">{{ $cons_ct }}</h4>
                </div>
            </div>
        </div>

        <!-- Available -->
        <div class="col-md-2 col-sm-6">
            <div class="card text-white border-0 shadow rounded-4"
                style="background: linear-gradient(135deg, #00c6ff, #0072ff);">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam display-4 mb-2"></i>
                    <h6 class="fw-bold mb-1">Available</h6>
                    <h4 class="stock">{{ $aval_stck }}</h4>
                </div>
            </div>
        </div>

    </div>



    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th colspan="4" class="align text-white bg-danger text-center">Inward</th>

                            </tr>
                            <tr>
                                <th class="align text-white bg-secondary text-center">SNo</th>
                                <th class="align text-white bg-secondary text-center">Date</th>
                                <th class="align text-white bg-secondary text-center">Supplier Name</th>
                                <th class="align text-white bg-secondary text-center">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $key = 1 ?>
                            <?php if ($inward != '') { ?>
                            <?php    foreach ($inward as $val) { ?>
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{ $val->date }}</td>
                                <td>{{ $val->supplier_name }}</td>
                                <td>{{ $val->qoh_trx_qty }}</td>

                            </tr>
                            <?php        $key++; ?>
                            <?php    } ?>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="Table2" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th colspan="4" class="align text-white bg-danger text-center">Outward</th>

                            </tr>
                            <tr>
                                <th class="align text-white bg-secondary text-center">SNo</th>
                                <th class="align text-white bg-secondary text-center">Date</th>
                                <th class="align text-white bg-secondary text-center">Product Name</th>
                                <th class="align text-white bg-secondary text-center">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $key = 1 ?>
                            <?php if ($out_ward != '') { ?>
                            <?php    foreach ($out_ward as $val) { ?>
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{ $val->idate }}</td>
                                <td>{{ $val->product }}</td>
                                <td>{{ $val->production_qty }}</td>

                            </tr>
                            <?php        $key++; ?>
                            <?php    } ?>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="card shadow-lg rounded-4 border-0 p-4">
            <div class="table-responsive" style="overflow-x: auto;">
                <table id="Table3" class="table table-bordered table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th colspan="4" class="align text-white bg-danger text-center">Consumables</th>

                        </tr>
                        <tr>
                            <th class="align text-white bg-secondary text-center">SNo</th>
                            <th class="align text-white bg-secondary text-center">Date</th>
                            <th class="align text-white bg-secondary text-center">Product Name</th>
                            <th class="align text-white bg-secondary text-center">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $key = 1 ?>
                        <?php if ($consumb != '') { ?>
                        <?php    foreach ($consumb as $val) { ?>
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{ $val->cdate }}</td>
                            <td>{{ $val->product }}</td>
                            <td>{{ $val->cqty }}</td>

                        </tr>
                        <?php        $key++; ?>
                        <?php    } ?>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

@endsection
@push('scripts')

    <script>





        $(document).ready(function () {
            var url = "{{ URL::to('jcomboformallchecknew') }}?table=m_products_t:product_id:product_code|concatenated_product&condition=yes";
            var requestedProduct = "{{ request('product_id') }}";

            // Initialize Select2 first
            var $prod = $('#product_id');
            $prod.select2({
                placeholder: '-- Select Product --',
                allowClear: true,
                width: '100%'
            });

            // Load options via AJAX
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    // parse if string
                    if (typeof data === "string") {
                        try {
                            data = JSON.parse(data);
                        } catch (e) {
                            console.error("Invalid JSON response:", data);
                            return;
                        }
                    }

                    $prod.empty();
                    $prod.append('<option value=""></option>');
                    // Append returned options
                    $.each(data, function (i, item) {
                        // make sure item.val and item.option_name exist
                        var val = String(item.val);
                        var text = item.option_name || item.text || val;
                        $prod.append(`<option value="${val}">${text}</option>`);
                    });

                    if (requestedProduct) {
                        // Debug
                        console.log('Selecting product from request:', requestedProduct, 'options count:', $prod.find('option').length);
                        $prod.val(requestedProduct).trigger('change'); // Select2 will reflect the change
                    }

                },
                error: function (xhr, status, err) {
                    console.error('Failed to load products:', status, err);
                }
            });

            $prod.on('select2:open', function () {
                console.log('select2 opened, current value:', $prod.val());
            });
        });


        $(document).ready(function () {
            $('#Table1').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                autoWidth: false, // Disable automatic column width calculation
                order: [],
                pageLength: 4,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'Inward moment analysis',
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'Inward moment analysis',
                    }
                ]
            });

            $('#Table2').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: [],
                pageLength: 4,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'Outward moment analysis',
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'Outward moment analysis',
                    }
                ]
            });

            $('#Table3').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: [],
                pageLength: 4,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'Consumble moment analysis',
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'Consumble moment analysis',
                    }
                ]
            });
        });


        $(document).ready(function () {
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('.start_date1').val(startDate);
            $('.end_date1').val(endDate);

        });
    </script>
@endpush