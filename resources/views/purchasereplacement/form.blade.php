@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Purchase Replacement</h3>
    @include('layouts.breadcrumb')



    <form method="post" id="qcform" data-parsley-validate>
        {{ csrf_field() }}

        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-header bg-primary text-white fw-semibold"></div>
            <div class="card-body">

                <div class="row g-4">
                    <!-- Column 1 -->
                    <div class="col-md-4">
                        <input type="hidden" name="replacement_hdr_id" value="{{ $row[0]->replacement_hdr_id }}">

                        <div class="mb-3">
                            <label for="replacement_no" class="form-label">Replacement No</label>
                            <input type="text" id="replacement_no" name="replacement_no" class="form-control replacement_no"
                                value="{{ $row[0]->replacement_no }}">
                        </div>

                        <div class="mb-3">
                            <label for="replacement_date" class="form-label">
                                <span class="text-danger">*</span> Replacement Date
                            </label>
                            <input type="text" id="replacement_date" name="replacement_date"
                                class="form-control replacement_date datepicker" value="{{ $row[0]->replacement_date }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="replacement_status" class="form-label">Replacement Status</label>
                            <input type="text" id="replacement_status" name="replacement_status"
                                class="form-control replacement_status" value="{{ $row[0]->replacement_status }}" required>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-md-4">
                        <input type="hidden" id="return_header_id" name="return_header_id"
                            value="{{ $row[0]->replacement_hdr_id }}">
                        <input type="hidden" id="po_hdr_id" name="po_number" value="{{ $row[0]->po_hdr_id }}">

                        <div class="mb-3">
                            <label for="po_number" class="form-label">PO Number</label>
                            <input type="text" id="po_number" class="form-control po_number"
                                value="{{ $row[0]->po_number }}">
                        </div>

                        <div class="mb-3">
                            <label for="grn_number" class="form-label">GRN Number</label>
                            <input type="hidden" id="grn_number" name="grn_number" value="{{ $row[0]->grn_id }}">
                            <input type="text" class="form-control grn_number" value="{{ $row[0]->grn_number }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="po_date" class="form-label">PO Date</label>
                            <input type="text" id="po_date" name="po_date" class="form-control po_date"
                                value="{{ $row[0]->po_date }}" readonly>
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier Name</label>
                            <select name="supplier_id" class="form-select supplier_id select2" data-live-search="true">
                                {!! $supplier_id !!}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="subcontract_supplier_id" class="form-label">SubContractor Name</label>
                            <select name="subcontract_supplier_id" class="form-select subcontract_supplier_id select2"
                                data-live-search="true">
                                {!! $subcontract_supplier_id !!}
                            </select>
                        </div>
                    </div>
                </div>

                <!-------------------------Linedata -------------------------------->

                <div class="row mt-4">
                    <div class="col-12 linetable">
                        <div id="preview-area" class="table-responsive">
                            <table class="table table-bordered clone_table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 80px;">Line No</th>
                                        <th class="pdtdiv">Product </th>
                                        <th>Uom Code</th>
                                        <th>Stock Update</th>
                                        <th>Batch Number</th>
                                        <th>Qty</th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </thead>
                                <tbody class="clone_lines_body">
                                    @if(count($linedata) > 0)
                                        @foreach($linedata as $key => $value)
                                                                <tr class="line-row">
                                                                    <td>
                                                                        <input type="hidden" name="bulk_replacement_line_id[]"
                                                                            class="form-control input-sm bulk_replacement_line_id"
                                                                            value="{{ $value->replacement_line_id }}">
                                                                        <input type="text" name="bulk_line_no[]"
                                                                            class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}"
                                                                            readonly="readonly">
                                                                    </td>
                                                                    <td class="pdtdiv">
                                                                        <select name="bulk_product_id[]"
                                                                            class="form-control bulk_product_id  parsley-validated select2"
                                                                            required="required">{!! $value->product_id !!}</select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id"
                                                                            class="form-control bulk_uom_code_id select2">
                                                                            {!! $value->uom_code_id !!}
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="bulk_stock_update[]" id="bulk_stock_update"
                                                                            class="form-control bulk_stock_update select2">
                                                                            <option value="">-- Please Select --</option>
                                                                            <option <?php        if ($value->stock_update == "YES")
                                            echo "selected"; ?>
                                                                                value="YES">YES</option>
                                                                            <option <?php        if ($value->stock_update == "NO")
                                            echo "selected"; ?>
                                                                                value="NO">NO</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="bulk_batch_number[]" id="bulk_batch_number"
                                                                            class="form-control bulk_batch_number select2">
                                                                            {!! $value->batch_number!!}
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty"
                                                                            value="{{ $value->qty }}" required="required">
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" name="bulk_comments[]"
                                                                            class="form-control input-sm bulk_comments" value="{{ $value->comments }}">
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                                                            <i class="fas fa-minus-circle"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                        @endforeach
                                    @endif
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>

                <!-------------------------Linedata End-------------------------------->

                <div class="row mt-4 mb-3">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group text-center">
                            <?php if ($aprvidenty == "") { ?>
                            <button type="button" class="btn btn-secondary saveform px-4 me-2"
                                value="APPLYCHANGES">Draft</button>
                            <button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
                            <a href="{{ url('purchasereplacement') }}" class='btn btn-outline-danger px-4 me-2'>Cancel</a>
                            <?php } else { ?>
                            <button type="button" class="btn btn-success px-4 me-2 saveform"
                                value="APPROVED">Approve</button>
                            <button type="button" class="btn btn-danger px-4 me-2 saveform" value="REJECTED">Reject</button>

                            <a href="{{ url('purchasereturnapproval') }}"
                                class='btn btn-outline-danger px-4 me-2'>Cancel</a>
                            <?php } ?>


                        </div>
                    </div>
                </div>


            </div>
        </div>
    </form>


@endsection
@push('scripts')

    <script>


        $('input').attr('readonly', true);
        $('select').attr('readonly', true);
        $('#return_date,.bulk_comments,.bulk_qty').attr('readonly', false);

        $(document).ready(function () {
            /*Karthigaa Purpose For Readonly*/
            $('.bulk_product_id,.bulk_uom_code_id,.bulk_tax_group_id,.supplier_id,.subcontract_supplier_id').css('pointer-events', 'none');
            $('#savestatus').val('');
            $(document).on('change', '.bulk_stock_update', function () {
                var index = $(this).closest('tr').index();
                var stock = $(".bulk_stock_update option:selected").text();

                if (stock == "YES") {
                    $('.bulk_batch_number' + index).attr('readonly', true);
                }
            });

        });
        /* Purpose For Save Function*/

        $(document).on('click', '.saveform', function () {
            var btnval = $(this).val();

            if (btnval == 'APPLYCHANGES') {
                $("#replacement_status").val('APPLYCHANGES');
            }
            else if (btnval == 'DRAFT') {
                $("#replacement_status").val('DRAFT');
            }
            else if (btnval == 'APPROVED') {
                $("#replacement_status").val('APPROVED');
            }
            else if (btnval == 'REJECTED') {
                $("#replacement_status").val('REJECTED');
            }
            else {
                $("#replacement_status").val('INITIATED');
            }

            var url = "{{ url('purchasereplacementsave') }}";
            var red_url = "{{ url('purchasereplacement') }}";
            var create_url = "{{ url('purchasereplacementcreate') }}/0";

            var form = $('#qcform');

            if (btnval != 'APPLYCHANGES') {
                form.parsley().validate();
                var form = $('#qcform');
                form.parsley().validate();

                if (form.parsley().isValid()) {
                    var $btn = $(this);
                    $btn.prop('disabled', true);
                    var formdata = $('#qcform').serialize();
                    $.post(url, formdata, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        var edit_url = "{{ url('purchasereplacementcreate') }}/" + id;
                        if (btnval != 'SAVE' && btnval != 'DRAFT' && btnval != 'APPROVED' && btnval != 'REJECTED') {
                            showCustomAlert(msg, status);
                            setTimeout(function () {
                                window.location.href = create_url;
                            }, 1500);
                        }
                        else {
                            showCustomAlert(msg, status);
                            setTimeout(function () {
                                window.location.href = red_url;
                            }, 1500);
                        }
                    });
                }
            }
            else {

                var formdata = $('#qcform').serialize();
                $.post(url, formdata, function (data) {

                    var status = data.status;
                    var msg = data.message;
                    var id = data.id;

                    var edit_url = "{{ url('purchasereplacementedit') }}/" + id;
                    showCustomAlert(msg, status);
                    setTimeout(function () {
                        window.location.href = edit_url;
                    }, 1500);

                });
            }
        });


        /*Readonly For Approval Page*/

        <?php if ($aprvidenty != "") { ?>
        $('#replacement_date,#po_date,.bulk_qty').css('pointer-events', 'none');
        <?php } else { ?>
        $('#po_date').css('pointer-events', 'none');
        <?php }?>


        // Remove button
        $(document).on('click', '.remove-row', function () {
            const rowCount = $('.clone_lines_body tr').length;
            if (rowCount > 1) {
                $(this).closest('tr').remove();
                updateLineNumbers();
            } else {
                showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
            }
        });

    </script>


@endpush