@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Machine Capacity</h3>
    @include('layouts.breadcrumb')



    <form method="post" action="" id="materialequipments" data-parsley-validate>
        <input type="hidden" value="" name="savestatus" id="savestatus" />
        <input type="hidden" value="{{ $row->machine_equipments_hdr_id }}" name="machine_equipments_hdr_id"
            id="machine_equipments_hdr_id" />
        {{ csrf_field() }}


        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-body container">
                <!------------------------------------- Body content start here ---------------------------->
                <div class="row g-3">

                    <!-- Machine Name -->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">
                                <span class="text-danger">*</span> Machine Name
                            </label>
                            <div class="col-sm-7">
                                <select name="machine_id" class="form-control select2 machine_id" style="width: 100%;"
                                    required tabindex="2">
                                    {!! $machine_id !!}
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Created By -->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Created By</label>
                            <div class="col-sm-7" style="pointer-events:none;">
                                <select name="created_by" class="form-control select2 created_by" id="created_by"
                                    tabindex="6">
                                    {!! $created_by !!}
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Remarks</label>
                            <div class="col-sm-7">
                                <input type="text" name="remarks" id="remarks" class="form-control remarks"
                                    value="{{ $row->remarks }}" tabindex="3" />
                            </div>
                        </div>

                        <!-- Hidden Organization -->
                        <div class="form-group row d-none">
                            <label class="col-sm-5 col-form-label">Organization</label>
                            <div class="col-sm-7">
                                <select name="organization_id" class="form-control organization_id" id="organization_id"
                                    style="width: 100%;">
                                    {!! $organization_id !!}
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <!------------------------------------------------------------------------------------------>

                <div class="row mt-4">

                    <div class="col-12 linetable">
                        <div id="preview-area" class="table-responsive">
                            <table class="table table-bordered clone_table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Line No</th>
                                        <th class="pdtdiv" style="width: 30% !important;">Product</th>
                                        <th>From Value </th>
                                        <th>To Value</th>
                                        <th>Hours</th>
                                        <th>Comments</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="clone_lines_body">
                                    @if(count($linedata) >= 1)
                                        @foreach($linedata as $key => $value)
                                            <tr class="rcopy clone">
                                                <td>
                                                    <input type="hidden" name="bulk_machine_equipments_lines_id[]"
                                                        class="form-control input-sm bulk_machine_equipments_lines_id"
                                                        value="{{ $value->machine_equipments_lines_id }}">

                                                    <input type="text" name="bulk_line_no[]"
                                                        class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}"
                                                        readonly="readonly">
                                                </td>

                                                <td class="pdtdiv">
                                                    <select name="product_id[]" id="bulk_product_id"
                                                        class="select2 bulk_product_id pdtdiv  parsley-validated" value=""
                                                        required="required">{!! $value->product_id!!}</select>

                                                </td>

                                                <td>
                                                    <input type="text" name="bulk_range_from[]"
                                                        class="form-control input-sm bulk_range_from input_qty_width"
                                                        value="{{ $value->range_from }}" required="required">
                                                </td>
                                                <td>
                                                    <input type="text" name="bulk_range_to[]"
                                                        class="form-control input-sm bulk_range_to input_qty_width"
                                                        value="{{ $value->range_to }}" required="required">
                                                </td>
                                                <td>
                                                    <input type="text" name="bulk_hours[]" id="bulk_hours"
                                                        class="form-control bulk_hours " value="{{ $value->hours }}"
                                                        required="required">
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
                                    @else
                                        <tr class="rcopy clone">
                                            <td>
                                                <input type="hidden" name="bulk_machine_equipments_lines_id[]"
                                                    class="form-control input-sm bulk_machine_equipments_lines_id" value="">

                                                <input type="text" name="bulk_line_no[]"
                                                    class="form-control input-sm bulk_line_no" value="1" readonly="readonly">
                                            </td>
                                            <td class="pdtdiv">
                                                <select name="product_id[]" id="bulk_product_id"
                                                    class="select2 bulk_product_id pdtdiv  parsley-validated" value=""
                                                    required="required"></select>

                                            </td>

                                            <td>
                                                <input type="text" name="bulk_range_from[]"
                                                    class="form-control input-sm bulk_range_from input_qty_width" value=""
                                                    required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_range_to[]"
                                                    class="form-control input-sm bulk_range_to input_qty_width" value=""
                                                    required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_hours[]" id="bulk_hours"
                                                    class="form-control bulk_hours" value="" required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_comments[]"
                                                    class="form-control input-sm bulk_comments" row="5" value="">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger remove-row">
                                                    <i class="fas fa-minus-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <div class="text-end">
                                <button type="button" class="btn btn-success btn-sm add-row">
                                    <i class="fas fa-plus-circle"></i> Add Row
                                </button>
                            </div>

                        </div>
                    </div>
                </div>



                <div class="row mt-4 mb-3">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group text-center">
                            <button type="button" class="btn btn-success saveform px-4 me-2" value="SAVE">Save</button>
                            <a href="{{ URL::to('materialequipments') }}" class='btn btn-danger px-4'>Cancel</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

@endsection
@push('scripts')

    <script>

        // Add Row 
        $(document).on('click', '.add-row', function () {
            const $lastRow = $('.clone_lines_body tr:last');
            const $newRow = $lastRow.clone(false, false);

            // clear inputs
            $newRow.find('input').val('');

            // reset product dropdown with full list
            $newRow.find('select.bulk_product_id').each(function () {
                if (productsOptionsHtml) {
                    $(this).html(productsOptionsHtml);
                } else {
                    $(this).html($('.bulk_product_id').first().html());
                }
                $(this).val('');
            });

            // reset select2
            $newRow.find('select.select2').each(function () {
                if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
                    $(this).select2('destroy');
                }
                $(this).removeAttr('data-select2-id');
                $(this).next('.select2').remove();
            });

            $('.clone_lines_body').append($newRow);
            $newRow.find('select.select2').select2({ width: '100%' });

            updateLineNumbers();
        });




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

        // Renumber Line Nos
        function updateLineNumbers() {
            $('.clone_lines_body tr').each(function (index) {
                $(this).find('.bulk_line_no').val(index + 1);
            });
        }

        $('.bulk_component_uom_code_id').attr("readonly", true);


        $('input').keyup(function (e) {
            if (e.which == 39) { // right arrow
                $(this).closest('td').next().find('input').focus();

            } else if (e.which == 37) { // left arrow
                $(this).closest('td').prev().find('input').focus();

            } else if (e.which == 40) { // down arrow
                $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();

            } else if (e.which == 38) { // up arrow
                $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
            }
        });


        var group = '{{ $group ?? '' }}';
        var cat = '{{ $cate_semi ?? '' }}';

        let productsOptionsHtml = ''; // global

        function loadMachineProducts(machineId) {
            if (!machineId) return;

            var url = "{{ URL::to('prdmachinedetails') }}/" + machineId;

            $.get(url, function (data) {
                data = $.trim(data);
                if (data == 0) {
                    showCustomAlert('Please create Machine Based on this product type', 'info');
                    return;
                }

                var cond1 = "product_id in(" + data + ")";

                $.ajax({
                    url: "{{ URL::to('jcomboform') }}",
                    type: "GET",
                    data: {
                        table: "m_products_t:product_id:product_code|concatenated_product",
                        parent: cond1,
                        order_by: "concatenated_product asc"
                    },
                    success: function (resp) {
                        if (typeof resp === "string") {
                            try { resp = JSON.parse(resp); }
                            catch (e) {
                                console.error("Invalid JSON response:", resp);
                                return;
                            }
                        }

                        let options = '<option value="">-- Please Select --</option>';
                        $.each(resp, function (i, item) {
                            options += `<option value="${item.val}">${item.option_name}</option>`;
                        });

                        productsOptionsHtml = options;

                        // apply to all existing rows and keep current value
                        $('.bulk_product_id').each(function () {
                            const currentVal = $(this).val();
                            $(this).html(productsOptionsHtml);
                            if (currentVal) $(this).val(currentVal);
                        }).trigger('change.select2');
                    }
                });
            });
        }

        // when user changes machine
        $('.machine_id').on('change', function () {
            loadMachineProducts($(this).val());
        });

        // when editing: machine already selected, so load immediately
        $(document).ready(function () {
            const mid = $('.machine_id').val();
            if (mid) {
                loadMachineProducts(mid);   // ⬅ this ensures edit page gets ALL products
            }
        });



        $(".bom_name").keyup(function () {
            $(this).val($(this).val().toUpperCase());
        });

        /*deepika purpose:qty validation*/
        $(document).on('keypress', '.bulk_range_from,.bulk_range_to,.bulk_hours', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });

        /*copy paste validation*/
        $('.bulk_range_from,.bulk_range_to,.bulk_hours').bind("cut copy paste", function (e) {
            e.preventDefault();
        });

        $('#savestatus').val('');
        $(document).on('click', '.saveform', function () {
            var btnval = $(this).val();
            if (btnval == 'APPLYCHANGES')
                var savestatus = 'APPLY CHANGES';
            else if (btnval == 'SAVE' || btnval == 'SAVENEW')
                var savestatus = 'SAVE';

            $('#savestatus').val(savestatus);

            var url = "{{ URL::to('materialequipmentssave') }}";
            var red_url = "{{ URL::to('materialequipments') }}";
            var create_url = "{{ URL::to('materialequipmentscreate') }}";
            var formdata = $('#materialequipments').serialize();
            var form = $('#materialequipments');


            if (btnval != 'APPLYCHANGES') {
                form.parsley().validate();
                var form = $('#materialequipments');
                form.parsley().validate();

                if (form.parsley().isValid()) {
                    var $btn = $(this);
                    $btn.prop('disabled', true);
                    $.post(url, formdata, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        var edit_url = "{{ URL::to('materialbomedit') }}/" + id;
                        if (btnval != 'SAVE' && btnval != 'DRAFT') {

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

                $.post(url, formdata, function (data) {

                    var status = data.status;
                    var msg = data.message;
                    var id = data.id;
                    var edit_url = "{{ URL::to('materialbomedit') }}/" + id;
                    showCustomAlert(msg, status);
                    setTimeout(function () {
                        window.location.href = edit_url;
                    }, 1500);

                });
            }

        });


        /* purpose:load uom based on product*/
        $(document).on('change', '.product_id', function () {
            var product_id = $(this).val();
            var url = "{{ URL::to('productuomdetails') }}/" + product_id;
            $.get(url, function (data) {
                var data = $.trim(data);
                $('.uom_code_id').val(data).trigger('change');
            });
        });





    </script>


@endpush