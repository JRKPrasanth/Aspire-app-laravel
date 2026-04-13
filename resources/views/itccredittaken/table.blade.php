@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Goods and Services Tax - GSTR-2B Upload</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white">
        </div>
        <div class="card-body">
            <form method="post" action="" id="empinsupload" enctype="multipart/form-data">
                {{ @csrf_field() }}

                <!-- Upload Section -->
                <div class="row g-3 align-items-center mb-3">
                    <input name="batch_name" type="hidden" class="batch_name" />

                    <div class="col-md-2">

                        <a href="{{ url('uploads/GST- GSTR-2B.csv') }}" class="btn btn-primary w-100" download><i class="fa fa-download me-1"></i> Template</a>

                    </div>

                    <div class="col-md-3">
                        <input id="choosefiles" name="choosefile" type="file" class="form-control choosefile" required />
                    </div>

                    <div class="col-md-2">
                        <button type="button" id="upload" class="btn btn-success w-100 uploaded">
                            <i class="fa fa-upload me-1"></i> Upload
                        </button>
                    </div>

                    <div class="col-md-3">
                        <select name="batchnumber" id="batchnumber" class="form-select select2 batchnumber"></select>
                    </div>
                    <div class="col-md-2">
                        <a class="btn btn-secondary searchfile_cls px-4 me-2">
                            <i class="fa fa-search me-1"></i> Search
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3"></div>
            <div class="table-responsive">
                <table id="ReportTbl" class="table table-striped table-bordered">
                    <thead>
                        <tr class="table-warning">
                            <th>Supplier GSTIN</th>
                            <th class="freeze">Legal Name</th>
                            <th>Invoice Number</th>
                            <th>Invoice Type</th>
                            <th>Supply Type</th>
                            <th>Invoice Date</th>
                            <th>Invoice Value</th>
                            <th>Place</th>
                            <th>Reverce Charge</th>
                            <th>Taxable Value</th>
                            <th>Central Tax</th>
                            <th>State Tax</th>
                            <th>Filling Date</th>
                            <th>ITC</th>
                            <th>IRN</th>
                            <th>IRN Date</th>
                        </tr>

                        <tr class="table-danger">
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th class="freeze"><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                        </tr>


                    </thead>
                    <tbody>
                        <!-- Your dynamic row data goes here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <!-- Upload Modal -->
    <div class="modal fade" id="myModal1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Batch Name</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 batch_data_div">
                        <div class="col-md-5">
                            <input type="text" name="batch_name1" id="batch_name1" class="form-control" readonly
                                value="BATCH-<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-1 text-center">-</div>
                        <div class="col-md-6">
                            <input type="text" name="batch_name2" id="batch_name2" class="form-control">
                        </div>
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-success index" data-val="modal">
                                <i class="fa fa-arrow-right me-1"></i> Go
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





@endsection
@push('scripts')

    <script>

        $(document).ready(function () {

            var table = $('#ReportTbl').DataTable({
                processing: true,
                serverSide: false,
                scrollX: true,
                scrollY: "50vh",
                orderCellsTop: true,
                ajax: {
                    url: "{{ url('getgstdata') }}",
                    type: "GET",
                    data: function (d) {
                        d.batchname = $(".batchnumber").val(); // send batchname filter
                    }
                },
                columns: [
                    { data: 'supplier_gstin', name: 'supplier_gstin' },
                    { class: 'freeze', data: 'legal_name', name: 'legal_name' },
                    { data: 'invoice_number', name: 'invoice_number' },
                    { data: 'invoice_type', name: 'invoice_type' },
                    { data: 'supply_type', name: 'supply_type' },
                    { data: 'invoice_date', name: 'invoice_date' },
                    { data: 'invoice_value', name: 'invoice_value' },
                    { data: 'place_of_supply', name: 'place_of_supply' },
                    { data: 'reverse_charge', name: 'reverse_charge' },
                    { data: 'taxable_value', name: 'taxable_value' },
                    { data: 'central_tax', name: 'central_tax' },
                    { data: 'state_tax', name: 'state_tax' },
                    { data: 'filing_date', name: 'filing_date' },
                    { data: 'itc_availability', name: 'itc_availability' },
                    { data: 'irn', name: 'irn' },
                    { data: 'irn_date', name: 'irn_date' }

                ],

                initComplete: function () {
                    var api = this.api();

                    // get the real visible header inside the scroll container
                    var $scrollHead = $(api.table().container())
                        .find('.dataTables_scrollHead thead');

                    // second header row (index 1) has the inputs
                    $scrollHead.find('tr:eq(1) th').each(function (colIndex) {
                        var th = this;
                        $('input.column-search', th).on('keyup change', function () {
                            if (api.column(colIndex).search() !== this.value) {
                                api.column(colIndex).search(this.value).draw();
                            }
                        });
                    });
                }
            });

            // Trigger search
            $('.searchfile_cls').click(function () {

                table.ajax.reload(); // reload table with selected batchname
            });


            // Column search
            $('#ReportTbl thead').on('keyup change', ".column-search", function () {
                var index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });
        });


        var condition1 = 'group by batch_name';

        $.ajax({
            url: "{{ URL::to('jcomboform1') }}",
            type: "GET",
            data: {
                table: "a_gstb2b_t:batch_name:batch_name",
                parent: condition1,
                order_by: "batch_name asc"
            },
            success: function (data) {
                // Parse JSON string if needed
                if (typeof data === "string") {
                    try {
                        data = JSON.parse(data);
                    } catch (e) {
                        console.error("Invalid JSON response:", data);
                        return;
                    }
                }

                $('.batchnumber').html('<option value="">-- Select Batch--</option>');

                $.each(data, function (i, item) {
                    let selected = item.val == "{{ $row->batchnumber ?? '' }}" ? 'selected' : '';
                    $('.batchnumber').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                });

                $('.batchnumber').trigger('change.select2');
            },
            error: function (xhr) {
                console.log("Error fetching batch numbers:", xhr.responseText);
            }
        });

        // popup
        $('.uploaded').click(function () {
            var flname = $(".choosefile").val();
            if (flname != "") {
                $('#myModal1').modal('show');
                $('.modal-dialog').width('40%');
                $("#myModal1").modal({ backdrop: "static" });
            }
            else {
                showCustomAlert("Please choose file", 'warning');
            }
        });


        $('#myModal1').on('shown.bs.modal', function () {
            $('.index').click(function () {
                var tmp1 = $('#batch_name1').val();
                var tmp2 = $('#batch_name2').val();
                if (tmp2 != "") {
                    var temp = tmp1 + tmp2;
                    $('.batch_name').val(temp);

                    var $btn = $(this);
                    $btn.prop('disabled', true);

                    var form_data = new FormData(document.getElementById('empinsupload'));
                    $.ajax({
                        url: "{{URL::to('gstrtwobdataupload')}}",
                        data: form_data,
                        type: 'POST',
                        enctype: 'multipart/form-data',
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            showCustomAlert(data['message'], 'success');
                            $('.close').trigger('click');
                            setTimeout(function () {
                                location.reload();
                            }, 2000);

                        },
                        error: function (xhr, status, error) {
                        }
                    });
                } else {
                    showCustomAlert('Please select batch name', 'error');
                }
            });
        });


    </script>

@endpush