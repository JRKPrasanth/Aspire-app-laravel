@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Goods Inward Note</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <form method="post" action="" id="gin_form" class="gin_form needs-validation" novalidate>
            {{ csrf_field() }}
            <div class="card-header bg-primary text-white">
            </div>
            <div class="card-body">
                <div class="row g-4">
             
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="gin_number" class="form-label">GIN Number</label>
                            <input type="hidden" name="p_gin_hdr_id" id="p_gin_hdr_id" value="{{$row['p_gin_hdr_id']}}">
                            <input type="text" class="form-control gin_number" id="gin_number" name="gin_number"
                                value="{{$row['gin_number']}}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="dc_number" class="form-label"><span class="text-danger">*</span> DC Number</label>
                            <input type="text" class="form-control dc_number" id="dc_number" name="dc_number"
                                value="{{$row['dc_number']}}" required>
                        </div>

                        <div class="mb-3" style="pointer-events:none;">
                            <label for="gin_status" class="form-label">GIN Status</label>
                            <select class="form-select gin_status select2" name="gin_status" id="gin_status">
                                <option value="">--Please Select--</option>
                                <option value="DRAFT" {{ $row['gin_status'] == "DRAFT" ? 'selected' : '' }}>DRAFT</option>
                                <option value="INITIATED" {{ $row['gin_status'] == "INITIATED" ? 'selected' : '' }}>INITIATED
                                </option>
                            </select>
                            <input type="hidden" name="save_status" id="save_status" value="{{$row['save_status']}}">
                        </div>
                    </div>

                    <!-- Middle Column -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="gin_description" class="form-label">GIN Description</label>
                            <input type="text" class="form-control gin_description" id="gin_description"
                                name="gin_description" value="{{$row['gin_description']}}">
                        </div>

                        <div class="mb-3 mb-3">
                            <label for="dc_date" class="form-label">DC Date</label>
                            <input type="text" class="form-control start_date dc_date" id="dc_date" name="dc_date"
                                value="{{ $row['dc_date'] }}">
                        </div>

                        <div class="mb-3" style="pointer-events:none;">
                            <label for="created_by" class="form-label">Created By</label>
                            <select class="form-select created_by select2" name="created_by">
                                {!! $created_by !!}
                            </select>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="supplier_type" class="form-label"><span class="text-danger">*</span> Supplier
                                Type</label>
                            <select class="form-select supplier_type select2" id="supplier_type" name="supplier_type"
                                required>
                                <option value="">--Please Select--</option>
                                <option value="SUPPLIER" {{ $row['supplier_type'] == "SUPPLIER" ? 'selected' : '' }}>SUPPLIER
                                </option>
                                <option value="SUBCONTRACT" {{ $row['supplier_type'] == "SUBCONTRACT" ? 'selected' : '' }}>
                                    SUBCONTRACT</option>
                            </select>
                        </div>

                        <div class="mb-3 showsupplier">
                            <label for="supplier_id" class="form-label"><span class="text-danger">*</span> Supplier
                                Name</label>
                            <div class="input-group">
                                <select class="form-select supplier_id select2" name="supplier_id" required>
                                    {!! $supplier_id !!}
                                </select>
                                <button class="btn btn-outline-secondary mt-2 suppliersearch" type="button"><i
                                        class="fa fa-search"></i></button>
                            </div>
                        </div>

                        <div class="mb-3 showcontract">
                            <label for="subcontract_supplier_id" class="form-label"><span class="text-danger">*</span>
                                Subcontract Name</label>
                            <div class="input-group">
                                <select class="form-select subcontract_supplier_id select2" name="subcontract_supplier_id"
                                    required>
                                    {!! $subcontract_supplier_id !!}
                                </select>
                                <button class="btn btn-outline-secondary mt-2 subcontractsearch" type="button"><i
                                        class="fa fa-search"></i></button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="total_packs" class="form-label"><span class="text-danger">*</span> Total Product
                                Packs</label>
                            <input type="number" class="form-control total_packs" name="total_packs"
                                value="{{ $row['total_packs'] }}" required>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="text-center mt-4">
                    @if($row['gin_status'] != "INITIATED")
                        <!--<button type="button" class="btn btn-warning">Draft</button>-->
                    @endif
                    <button type="button" class="btn btn-success px-4 me-2 saveform intiated" value="SAVE">Save</button>
                    <a href="{{ url('goodsinwardnote') }}" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </div>
    </div>
    </form>
    </div>


    <!-- Supplier Search Modal -->
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content shadow-lg border-0">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="supplierModalLabel">
                        <i class="bi bi-people-fill me-2"></i>Supplier Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="supplierTable" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Supplier Number</th>
                                    <th>Supplier Name</th>
                                    <th>Supplier Type</th>
                                    <th>Supplier Site Name</th>
                                    <th>Address</th>
                                    <th>Country</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Close
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Subcontract Supplier Search Modal -->
    <div class="modal fade" id="subcontractModal" tabindex="-1" aria-labelledby="subcontractModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content shadow-lg border-0">

                <!-- Modal Header -->
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="subcontractModalLabel">
                        <i class="bi bi-building-check me-2"></i>Subcontract Supplier Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="subcontractTable" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Site ID</th>
                                    <th>Subcontractor Number</th>
                                    <th>Subcontractor Name</th>
                                    <th>Subcontractor Type</th>
                                    <th>Subcontract Site Name</th>
                                    <th>Address</th>
                                    <th>Country</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Close
                    </button>
                </div>

            </div>
        </div>
    </div>



@endsection
@push('scripts')

    <script>

        $(document).ready(function () {

            $('.created_by,.gin_status').css("pointer-events", "none");

            $(document).on('click', '.intiated', function () {
                $("#save_status").val('INITIATED');
                $("#gin_status").val('INITIATED');
            });

            /* Purpose For Supplier Type Based Show Supplier and SubContractor*/

            $(".supplier_type").change(function () {
                var supplier_type = $(".supplier_type option:selected").val();
                if (supplier_type == "SUPPLIER") {
                    $(".showsupplier").show();
                    $(".showcontract").hide();
                    $('.subcontract_supplier_id').attr("required", false);
                } else {
                    $(".showcontract").show();
                    $(".showsupplier").hide();
                    $('.supplier_id').attr("required", false);
                }
            });

            /* Purpose For Draft Status Save*/

            $(document).on('click', '.draft', function () {
                $('.dc_number,.supplier_id,.total_packs').attr("required", false);
                $("#save_status").val('DRAFT');
                $("#gin_status").val('DRAFT');
            });


            /* Purpose For Validation For Total PAck*/

            $(document).on('keypress', '.total_packs', function (ev) {
                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });



            $('.total_packs').bind("cut copy paste", function (e) {

                e.preventDefault();
            });


            /* Purpose For Save Function*/

            $(document).on('click', '.saveform', function () {
                var btnval = $(this).val();
                var url = "{{ URL::to('ginsave') }}";
                var red_url = "{{ URL::to('goodsinwardnote') }}";
                var form = $('#gin_form');
                form.parsley().validate();
                if (btnval == 'SAVE') {
                    if (form.parsley().isValid()) {
                        var $btn = $(this);
                        $btn.prop('disabled', true);
                        var formdata = $('#gin_form').serialize();
                        $.post(url, formdata, function (data) {
                            var status = data.status;
                            var msg = data.message;
                            var id = data.id;
                            var auto_no = data.auto_no;
                            if (btnval == 'SAVE') {
                                showCustomAlert(msg, status);
                                setTimeout(function () {
                                    window.location.href = red_url;
                                }, 1500);
                            }
                        });
                    }
                }
                else {

                    var formdata = $('#gin_form').serialize();
                    $.post(url, formdata, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        var auto_no = data.auto_no;
                        if (btnval == 'SAVE') {
                            showCustomAlert(msg, status);
                            setTimeout(function () {
                                window.location.href = red_url;
                            }, 1500);
                        }
                    });
                }
            });


            $('.subcontractsearch').click(function () {
                $('#subcontractModal').modal('show');
                $('#subcontractModal').width("100%");
            });


            $('.suppliersearch').click(function () {
                $('#supplierModal').modal('show');
                $('#supplierModal').width("100%");
            });


            $(document).ready(function () {
                var subcontractTable = $('#subcontractTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('getSubcontractgridData') }}",
                    columns: [
                        { data: 'subcontract_supplierid', visible: false },
                        { data: 'subcontract_site_id', visible: false },
                        { data: 'subcontract_number', name: 'subcontract_number' },
                        { data: 'subcontract_name', name: 'subcontract_name' },
                        { data: 'suppliertype_name', name: 'suppliertype_name' },
                        { data: 'subcontract_site_name', name: 'subcontract_site_name' },
                        { data: 'address', name: 'address' },
                        { data: 'country_name', name: 'country_name' },
                        { data: 'state_name', name: 'state_name' },
                        { data: 'city_name', name: 'city_name' },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row) {
                                return `<button type="button" class="btn btn-primary btn-sm selectSubcontract" 
                            data-id="${row.subcontract_supplierid}" 
                            data-address="${row.address}">
                            Select
                        </button>`;
                            }
                        }
                    ],
                    pageLength: 10,
                    lengthMenu: [[10, 50, 100, 250, 500, 1000], [10, 50, 100, 250, 500, 1000]],
                    order: [[2, 'asc']]
                });

                // Handle select button click
                $('#subcontractTable').on('click', '.selectSubcontract', function () {
                    var id = $(this).data('id');
                    var address = $(this).data('address');

                    if (id) {
                        $('.subcontract_supplier_id').val(id).trigger('change');
                        $('#subcontractModal').modal('hide');
                    } else {
                        alert('Please select a row');
                    }
                });
            });



            $(document).ready(function () {
                var supplierTable = $('#supplierTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('getSuppliergridData') }}",
                    columns: [
                        { data: 'supplierid', visible: false },
                        { data: 'supplier_number', name: 'supplier_number' },
                        { data: 'supplier_name', name: 'supplier_name' },
                        { data: 'suppliertype_name', name: 'suppliertype_name' },
                        { data: 'supplier_site_name', name: 'supplier_site_name' },
                        { data: 'address', name: 'address' },
                        { data: 'country_name', name: 'country_name' },
                        { data: 'state_name', name: 'state_name' },
                        { data: 'city_name', name: 'city_name' },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row) {
                                return `<button type="button" 
                            class="btn btn-primary btn-sm selectSupplier" 
                            data-id="${row.supplierid}" 
                            data-address="${row.address}">
                            Select
                        </button>`;
                            }
                        }
                    ],
                    pageLength: 10,
                    lengthMenu: [[10, 50, 100, 250, 500, 1000], [10, 50, 100, 250, 500, 1000]],
                    order: [[2, 'asc']]
                });

                // Handle supplier select
                $('#supplierTable').on('click', '.selectSupplier', function () {
                    var id = $(this).data('id');
                    var address = $(this).data('address');

                    if (id) {
                        $('.supplier_id').val(id).trigger('change');
                        $('#supplierModal').modal('hide');
                    } else {
                        alert('Please select a row');
                    }
                });
            });



        });

    </script>


@endpush