@extends('layouts.header')
@section('content')
<h3 class="text-danger">Sales Return Check</h3>
@include('layouts.breadcrumb')
<style>
.select2-container--open { z-index: 200000 !important; }
</style>	

<div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
        <table id="SalesTbl" class="table table-bordered table-striped w-100">
            <thead>
                <tr class="table-warning">
                    <th></th>
                    <th>Return Ref Number</th>
                    <th>Return Date</th>
                    <th>Return Status</th>
                    <th>Customer Name</th>
                    <th>Product Name</th>
                    <th>Return Qty</th>
                    <th>Actions</th>
                </tr>
                <tr class="table-info">
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="subinventoryModal" tabindex="-1" aria-labelledby="subinventoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg rounded-3">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="subinventoryModalLabel">
                    <i class="bi bi-box-seam"></i> Subinventory & Locator
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <input type="hidden" class="soindex" value="">
            </div>

            <!-- Modal Body -->
            <form id="move_status">
                <input type="hidden" name="stat" class="stat" id="stat" value="">

                <div class="modal-body">
                    <div class="row g-4">

                        <!-- Subinventory -->
                        <div class="col-md-6">
                            <label for="subinventory_id" class="form-label fw-semibold">
                                Subinventory <span class="text-danger">*</span>
                            </label>
                            <select name="subinventory_id" id="subinventory_id"
                                class="form-select select2 subinventory_id" required>
                                {!! $subinventory_id !!}
                            </select>
                        </div>

                        <!-- Sublocator -->
                        <div class="col-md-6">
                            <label for="sublocator_id" class="form-label fw-semibold">
                                Sublocator <span class="text-danger">*</span>
                            </label>
                            <select name="sublocator_id" id="sublocator_id" class="form-select select2 sublocator_id"
                                required>
                                {!! $sublocator_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Dispatch Data -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="dispatchdata border rounded p-3 bg-light"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" id="subinv_ok" class="btn btn-success px-4">
                        <i class="bi bi-check-circle"></i> OK
                    </button>
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@push('scripts')


<script>

    // data table funcrion	
    $(document).ready(function () {
        var table = $('#SalesTbl').DataTable({
            processing: true,
            serverSide: true,
            order: [[2, 'desc']],
            ajax: "getSalesreturncheckData",
            columns: [
                { data: 'so_rma_line_id', name: 'so_rma_line_id' },
                { data: 'rma_ref_no', name: 'rma_ref_no' },
                { data: 'return_date', name: 'return_date' },
                { data: 'return_status', name: 'return_status' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'concatenated_product', name: 'concatenated_product' },
                { data: 'return_qty', name: 'return_qty' },
                {
                    data: 'so_rma_hdr_id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function (data, type, row) {
                        let buttons = '';
                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'scrap')) {
                            buttons += `
                            <button class="btn btn-sm btn-primary scrap-btn returncheck" 
                                data-id="${row.so_rma_line_id}" 
                                data-hdrid="${row.so_rma_hdr_id}" 
                                data-proid="${row.productid}" 
                                data-qty="${row.return_qty}" 
                                data-source="SCRAP" 
                                title="Scrap">
                                <i class="bi bi-arrows-expand"></i>
                            </button>`;
                        }
                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'rework')) {
                            buttons += `
                            <button class="btn btn-sm btn-warning rework-btn returncheck" 
                                data-id="${row.so_rma_line_id}" 
                                data-hdrid="${row.so_rma_hdr_id}" 
                                data-proid="${row.productid}" 
                                data-qty="${row.return_qty}" 
                                data-source="REWORK" 
                                title="Rework">
                                <i class="bi bi-highlighter"></i>
                            </button>`;
                        }
                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'move')) {
                            buttons += `
                            <button class="btn btn-sm btn-success move-btn returncheck" 
                                data-id="${row.so_rma_line_id}" 
                                data-hdrid="${row.so_rma_hdr_id}" 
                                data-proid="${row.productid}" 
                                data-qty="${row.return_qty}" 
                                data-source="MOVETOINVENTORY" 
                                title="Move To Inventory">
                                <i class="bi bi-arrows-move"></i>
                            </button>`;
                        }
                        return buttons;
                    }
                }
            ]
        });

        // Column search
        $('#SalesTbl thead').on('keyup change', ".column-search", function () {
            var colIndex = $(this).parent().index();
            table.column(colIndex).search(this.value).draw();
        });

        // Click handling for returncheck buttons
        $(document).on("click", ".returncheck", function () {
            var source = $(this).data("source");
            var rowData = table.row($(this).closest('tr')).data(); // get current row data

            var lid = rowData.so_rma_line_id;
            var hdrid = rowData.so_rma_hdr_id;
            var prdid = rowData.productid;
            var qty = rowData.return_qty;

            $("#subinv_ok").attr('data-val', source);
            $("#stat").val(source);

            // If you allow multiple selection via DataTables checkboxes:
     //       var selectedIds = $.map(table.rows({ selected: true }).data(), function (item) {
         //       return item.so_rma_line_id;
     //       });

         //   if (selectedIds.length === 0) {
                // fallback to clicked row
        //        selectedIds.push(lid);
        //    }

            // open modal
            $('#subinventoryModal').modal('show');

            var url = "{{ URL::to('dispatchreturndata')}}/" + lid;
            $.get(url, function (data) {
                $('.dispatchdata').html(data);
            });
        });
    });


    /*deepika purpose:qty validation*/
    $(document).on('keypress', '.returnedqty', function (ev) {
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
            return true;
        }
        ev.preventDefault();
        return false;
    });

    $(document).on('keyup', '.returnedqty', function (ev) {
        var rtnqty = $('.returnqty').val();
        var returnqty = $(this).val();
        if (rtnqty < returnqty) {
            showCustomAlert("Qty Should not be greater than Return Qty", "error");
            $(this).val("");
        }
    });


    $('#subinv_ok').click(function () {
        var id = $('.subinventory_id').val();
        var source = $(this).attr('data-val');

        var url = "{{URL::to('rtnmovetoinventory')}}";
        var red_url = "{{URL::to('salesreturncheck')}}";
        var formdata = $("#move_status").serialize();
        $.post(url, formdata, function (data) {
            showCustomAlert("Qty Saved Successfully", "success");
            window.location.href = red_url;
        });

    });





    $(document).on('click', '.move-btn', function () {


        const id = $(this).data('id');
        const prdid = $(this).data('proid');
        const qty = $(this).data('qty');


        $("#subinv_ok").attr('data-val', 'MOVETOINVENTORY');

        $('.subinventory_id').select2('val', ['']);
        $('.sublocator_id').select2('val', ['']);
        $('#subinventoryModal').modal('show');



    });


    $(document).on('click', '.rework-btn', function () {


        const id = $(this).data('id');
        const prdid = $(this).data('proid');
        const qty = $(this).data('qty');

        $("#subinv_ok").attr('data-val', 'REWORK');

        var url = "{{ URL::to('movetoinventory')}}/" + id + "/" + prdid + "/" + qty + "?source='REWORK'";
        $.get(url, function (data) {
            var status = data.status;
            var msg = "Reworked Successfully";
            showCustomAlert(msg, status);
            setTimeout(function () {
                location.reload();
            }, 1500);

        });

    });


$('.subinventory_id').on('change', function () {

    var subinv = $(this).val();
    var $sublocator = $('.sublocator_id');

    // Disable by default
    $sublocator.prop('disabled', true);
    $sublocator.empty().append('<option value="">-- Select Sublocator --</option>');

    if (subinv !== '') {
        $sublocator.prop('disabled', false);

        $.ajax({
            url: "{{ URL::to('jcomboform') }}",
            type: "GET",
            dataType: "json",
            data: {
                table: "m_sublocators_t:sublocator_id:locator_code",
                parent: "subinventory_id=" + subinv,
                order_by: "locator_code asc"
            },
            success: function (response) {

                $.each(response, function (i, row) {
                    $sublocator.append(
                        '<option value="' + row.val + '">' + row.option_name + '</option>'
                    );
                });

                // If using select2 or similar
                // $sublocator.trigger('change');
            },
            error: function () {
                alert('Failed to load sublocators');
            }
        });
    }
});


    $(document).on('click', '.scrap-btn', function () {

        const id = $(this).data('id');
        const prdid = $(this).data('proid');
        const qty = $(this).data('qty');

        $("#subinv_ok").attr('data-val', 'SCRAP');

        var url = "{{ URL::to('movetoinventory')}}/" + id + "/" + prdid + "/" + qty + "?source='SCRAP'";
        $.get(url, function (data) {
            var status = data.status;
            var msg = "Scraped Successfully";
            showCustomAlert(msg, status);
            setTimeout(function () {
                location.reload();
            }, 1500);

        });

    });

</script>

@endpush