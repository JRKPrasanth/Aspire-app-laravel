@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Bank Statement Upload</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white">
        </div>
        <div class="card-body">
            <form method="post" action="" id="stmtupload" enctype="multipart/form-data">
                {{ @csrf_field() }}

                <div class="row">
                    <div class=" col-md-6">
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-4"><span style="color: red;">*</span>Bank
                                Name </label>
                            <div class="col-md-7">
                                <select name='bank_id' rows='5' required class='form-control bank_id select2'>
                                    {!! $bank_id !!}
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-4"><span style="color: red;">*</span>
                                Account Number </label>
                            <div class="col-md-7">
                                <select name='account_no' rows='5' required class='form-control account_no select2'>
                                    {!! $account_no !!}
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Upload Section -->
                <div class="row g-3 align-items-center mb-3">
                    <input name="batch_name" type="hidden" class="batch_name" />

                    <div class="col-md-2">
                        <a href="{{ url('uploads/BANK STATEMENT.csv') }}" class="btn btn-primary w-100" download>
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
                            <th class="freeze">Batch Name</th>
                            <th>Batch Status</th>
                            <th>Batch Date</th>
                            <th>Bank Name</th>
                            <th>Account Number</th>
                            <th>Date</th>
                            <th>Value Date</th>
                            <th>Chq No</th>
                            <th>Narration</th>
                            <th>Cod</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                        </tr>

                        <tr class="table-danger">
                            <th class="freeze"><input type="text" class="form-control form-control-sm column-search"
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
                    url: "{{ url('getstmtuploaddata') }}",
                    type: "GET",
                    data: function (d) {
                        d.batchname = $(".batchnumber").val(); // send batchname filter
                    }
                },
                columns: [
                    { class: 'freeze', data: 'batch_name', name: 'batch_name' },
                    { data: 'batch_status', name: 'batch_status' },
                    { data: 'batch_date', name: 'batch_date' },
                    { data: 'bank_name', name: 'bank_name' },
                    { data: 'account_number', name: 'account_number' },
                    { data: 'date', name: 'date' },
                    { data: 'value_date', name: 'value_date' },
                    { data: 'chq_no', name: 'chq_no' },
                    { data: 'narration', name: 'narration' },
                    { data: 'cod', name: 'cod' },
                    { data: 'debit', name: 'debit' },
                    { data: 'credit', name: 'credit' },
                    { data: 'balance', name: 'balance' }


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

                table.ajax.reload();
            });


            // Column search
            $('#ReportTbl thead').on('keyup change', ".column-search", function () {
                var index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });
        });


        $(document).ready(function () {

            $('.bank_id').on('change', function () {
                var bank = $(this).val();
                var url = "{{ URL::to('jcomboform1') }}?table=f_bank_account_lines_t:bank_account_line_id:account_number"
                    + "&parent=and bank_account_hdr_id=" + bank
                    + "&order_by=account_number asc";

                $.ajax({
                    url: url,
                    type: 'GET',
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

                        // Reset dropdown
                        $('.account_no').html('<option value="">-- Select Account No --</option>');

                        // Populate options
                        $.each(data, function (i, item) {
                            let selected = item.val == "{{ $row->account_no ?? '' }}" ? 'selected' : '';
                            $('.account_no').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                        });

                        // Trigger select2 refresh if used
                        $('.account_no').trigger('change.select2');
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });
            });



            var bank = "{{$bank}}";
            var account = "{{$account}}";


            var condition1 = 'group by batch_name';

            $.ajax({
                url: "{{ URL::to('jcomboform1') }}",
                type: "GET",
                data: {
                    table: "f_bankstmtupload_t:batch_name:batch_name",
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




            $('.uploaded').click(function () {
                var flname = $("#choosefile").val();
                var bank_name = $(".bank_id").val();
                var account_no = $(".account_no").val();
                if (flname != "") {
                    if (account_no != "" && bank_name != "") {
                        $('#myModal1').modal('show');
                        $('.modal-dialog').width('40%');
                        $("#myModal1").modal({ backdrop: "static" });
                    } else {
                        showCustomAlert("Please choose Bank Name and Account Number", "error");
                    }
                }
                else {
                    showCustomAlert("Please choose file", "error");
                }
            });

            $('#myModal1').on('shown.bs.modal', function () {
                $('.index').click(function () {
                    var tmp1 = $('#batch_name1').val();
                    var tmp2 = $('#batch_name2').val();
                    var temp = tmp1 + tmp2;
                    $('.batch_name').val(temp);
                    var form_data = new FormData(document.getElementById('stmtupload'));
                    $.ajax({
                        url: "{{URL::to('stmtuploaddata')}}",
                        data: form_data,
                        type: 'POST',
                        enctype: 'multipart/form-data',
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            showCustomAlert(data['message'], "success");
                            $('.close').trigger('click');
                            setTimeout(function () {
                                location.reload();
                            }, 2000);

                        },
                        error: function (xhr, status, error) {
                        }
                    });
                });
            });

        });


        function showResponse(data, message) {
            if (data == 'success') {
                notyMsg("success", message);
                var url = "{{ URL::to('stmtupload') }}";
            }
            if (data == 'info') {
                notyMsg("info", message);
                var url = "{{ URL::to('stmtupload') }}";
            }
            if (data == 'error') {
                notyMsg("error", message);
                var url = "{{ URL::to('stmtupload') }}";
            }
        }

    </script>

@endpush