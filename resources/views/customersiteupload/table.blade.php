@extends('layouts.header')
@section('content')
    <h3 class="text-danger">customersite Upload</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white">
        </div>
        <div class="card-body">
            <form method="post" action="" id="pricelistupload" enctype="multipart/form-data">
                {{ @csrf_field() }}

                <!-- Upload Section -->
                <div class="row g-3 align-items-center mb-3">
                    <input name="batch_name" type="hidden" class="batch_name" />

                    <div class="col-md-3">
                        <a  href="{{ url('uploads/CUSTOMERS TEMPLATE.csv') }}" class="btn btn-outline-primary w-100" download>
                            <i class="fa fa-download me-1"></i> Template
                        </a>
                    </div>

                    <div class="col-md-3">
                        <input id="choosefiles" name="choosefile" type="file" class="form-control choosefile" required />
                    </div>

                    <div class="col-md-2">
                        <button type="button" id="upload" class="btn btn-success w-100 uploaded">
                            <i class="fa fa-upload me-1"></i> Upload
                        </button>
                    </div>

                    <div class="col-md-4">
                        <select name="batchnumber" id="batchnumber" class="form-select select2 batchnumber"></select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mb-3 mt-4">
                    <div class="col-md-6 d-flex gap-2">
                        <a class="btn btn-secondary searchfile_cls px-4 me-2">
                            <i class="fa fa-search me-1"></i> Search
                        </a>
                        <a class="btn btn-warning verifyed px-4 me-2">
                            <i class="fa fa-check-circle me-1"></i> Validate
                        </a>
                        <a class="btn btn-info loaded px-4 me-2">
                            <i class="fa fa-database me-1"></i> Load
                        </a>
                    </div>

                </div>

            </form>
        </div>
    </div>


    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4">
            <div class="table-responsive">
                <table id="cusiteTbl" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr class="table-warning">


                            <th>Batch Name</th>
                            <th>Batch Status</th>
                            <th>Batch Date</th>
                            <th>Customer Site Name</th>
                            <th>Customer Name</th>
                            <th>Site Type</th>
                            <th>Actions</th>
                        </tr>

                        <tr class="table-info">

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
                        {{-- DataTable will populate via AJAX --}}
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



    <!--end-->

@endsection
@push('scripts')

    <script>

        // table data

        $(document).ready(function () {

            var table = $('#cusiteTbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "getCustomersiteuploadData",
                    data: function (d) {
                        d.batchname = $(".batchnumber").val(); // send batchname filter
                    }
                },

                columns: [


                    { data: 'batch_name', name: 'batch_name' },
                    { data: 'batch_status', name: 'batch_status' },
                    { data: 'batch_date', name: 'batch_date' },
                    { data: 'customer_site_name', name: 'customer_site_name' },
                    { data: 'customer_name', name: 'customer_name' },
                    { data: 'site_type', name: 'site_type' },

                    {

                        data: 'customersite_upload_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-sm btn-warning me-1 view-btn" data-id="${data}">
                                View
                                </button>`;
                        },

                    }
                ]
            });


            $('#cusiteTbl thead').on('keyup change', '.column-search', function () {
                let index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });

            $('.searchfile_cls').click(function () {

                table.ajax.reload(); // reload table with selected batchname
            });

        });

        // edit
        $(document).on('click', '.edit-btn', function () {

            var id = $(this).data('id');
            var status = $(this).data('status');

            if (status == "ERROR") {
                window.location.replace('customersiteuploadedit/' + id);
            }
            else {
                showCustomAlert("Batch not allow to edit", 'info');
            }


        });

        // edit
        $(document).on('click', '.view-btn', function () {

            var id = $(this).data('id');

            window.location.replace('customersiteuploadview/' + id);

        });


        var condition1 = 'group by batch_name';

        $.ajax({
            url: "{{ URL::to('jcomboform1') }}",
            type: "GET",
            data: {
                table: "s_customersites_upload_t:batch_name:batch_name",
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
                    var form_data = new FormData(document.getElementById('pricelistupload'));
                    var $btn = $(this);            
                    $btn.prop('disabled', true);
                    $.ajax({
                        url: "{{URL::to('customersitedataupload')}}",
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



        /* Purpose:to verify batch number */

        $('.verifyed').click(function () {

            var batchname = $('#batchnumber option:selected').val();

            var parm = '';
            var verify = 'verify';
            if (batchname != '') {
                var parm = "?batchname=" + batchname + '&type=verify';
            }
            else {
                showCustomAlert("Please select batchnumber", 'info');
            }

            var newUrl = refineUrl();//fetch new url
            var url = "{{URL::to('getCustomersitevalidate')}}";

            $.get(url, { 'batchname': encodeURIComponent(batchname), 'type': verify }, function (response) {
                var data = response.status;
                var message = response.message;
                var valid = response.valid;
                if (valid == 1) {
                    $('.message').show();
                    setTimeout(function () {
                        window.location.replace(newUrl);
                    }, 4000);

                } else if (data == 'success') {

                    showCustomAlert(message, 'success');
                    setTimeout(function () {
                        window.location.replace(newUrl);
                    }, 2000);

                } else if (data == 'info') {
                    showCustomAlert(message, 'info');
                    setTimeout(function () {
                        window.location.replace(newUrl);
                    }, 2000);

                } else if (data == 'error') {
                    showCustomAlert(message, 'error');
                    setTimeout(function () {
                        window.location.replace(newUrl);
                    }, 2000);
                }
            });
        });

        $('.close').click(function () {
            var newUrl = refineUrl();
            window.location.replace(newUrl);

        });


        $('.loaded').click(function () {

            var batchname = $('#batchnumber option:selected').val();
            var parm = '';
            var load = 'load';
            if (batchname != '') {
                var parm = "?batchname=" + batchname + '&type=load';
            }
            else {
                showCustomAlert("Please select batchnumber", 'error');
            }
            var newUrl = refineUrl();//fetch new url
            var url = "{{URL::to('getCustomersitevalidate')}}";

            $.get(url, { 'batchname': encodeURIComponent(batchname), 'type': load }, function (response) {
                var data = response.status;
                var message = response.message;
                if (data == 'success') {

                    showCustomAlert(message, 'success');
                    setTimeout(function () {
                        window.location.replace(newUrl);
                    }, 2000);

                }

                if (data == 'info') {
                    showCustomAlert(message, 'info');
                    setTimeout(function () {
                        window.location.replace(newUrl);
                    }, 2000);

                }
                if (data == 'error') {
                    showCustomAlert(message, 'error');
                    setTimeout(function () {
                        window.location.replace(newUrl);
                    }, 2000);
                }
            });

        });


        function refineUrl() {
            var url = window.location.href;
            var value = url.split("?")[0];
            return value;
        }

    </script>

@endpush