@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Product</h3>
    @include('layouts.breadcrumb')
    <div id="toolbar-container" class="create mb-3 mt-1"></div>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4 table-responsive">
            <table id="Producttbl" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th>Actions</th>
                        <th>Product Code</th>
                        <th>Product Group</th>
                        <th>Product Category</th>
                        <th>Sub Category</th>
                        <th>Product Classification</th>
                        <th>Product Name</th>
                        <th>Uom Code</th>
                        <th>Quality Check</th>
                        <th>Subinventory</th>
                        <th>Locator Code</th>
                        <th>Hsn Code</th>
                        <th>Min Stock Level</th>
                        <th>Max Stock Level</th>
                        <th>Tax Credit</th>
                        <th>Status</th>
                        <th>Created/Approved By</th>
                        <th></th>
                        <th></th>

                    </tr>
                    <tr class="table-info">
                        <th></th>
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

    <!-- popups -->

    <!-- ROL Details Update Modal -->
    <div class="modal fade" id="rolModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">ROL Details Update</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <p><strong>Product Name:</strong> <span class="prd_name text-muted"></span></p>
                        <p><strong>Product Group:</strong> <span class="prd_group text-muted"></span></p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Re Order Level</label>
                            <input type="text" class="form-control min_order_qty" id="min_order_qty" name="min_order_qty">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Max Order Qty</label>
                            <input type="text" class="form-control re_order_level" id="re_order_level"
                                name="re_order_level">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Min Stock Level1</label>
                            <input type="text" class="form-control max_order_qty" id="max_order_qty" name="max_order_qty">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Min Stock Level2</label>
                            <input type="text" class="form-control min_stock_level2" id="min_stock_level2"
                                name="min_stock_level2">
                            <input type="hidden" class="product_id">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Min Stock Level3</label>
                            <input type="text" class="form-control min_stock_level3" id="min_stock_level3"
                                name="min_stock_level3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">MPQ Qty</label>
                            <input type="text" class="form-control mpq_qty" id="mpq_qty" name="mpq_qty">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Gross Weight</label>
                            <input type="text" class="form-control gross_weight" id="gross_weight" name="gross_weight">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Net Weight</label>
                            <input type="text" class="form-control net_weight" id="net_weight" name="net_weight">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary rol_save" id="updateClose">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Search Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Price Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table id="pricegrid" class="table table-bordered table-striped w-100"></table>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Details Modal -->
    <div class="modal fade" id="myModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Company Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body companybody"></div>
                <div class="modal-footer">
                    <button type="button" id="company_assign" class="btn btn-success">Save</button>
                    <button type="button" id="cancel" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Update Modal -->
    <div class="modal fade" id="myModal1" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Update Batch No</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Batch No</label>
                        <div class="input-group">
                            <input type="text" id="batch_no1" name="batch_no1" class="form-control">
                            <button type="button" class="btn btn-success index" data-val="modal">Go</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('scripts')

    <script>

        // Add create button purpose
        $(document).ready(function () {
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
                $('#toolbar-container').append(`
                      <button class="btn btn-primary text-white px-4 create me-2">Create
                        <i class="bi bi-plus-circle"></i> 
                      </button>
                    `);
            }
        });

        // data table funcrion	
        $(document).ready(function () {
            var table = $('#Producttbl').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                scrollY: "50vh",
                order: [[0, 'desc']], 
                ajax: "ProductgridData?pagemethod={{ $pageMethod }}",
                columns: [
                    {
                        data: 'product_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',

                        render: function (data, type, row) {

                         if (type === 'sort' || type === 'type') {
                            return data; // IMPORTANT: return numeric id for sorting
                            }

                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                                buttons += `
                <button class="btn btn-sm btn-warning view-btn" data-id="${row.product_id}">
                  <i class="bi bi-eye"></i>
                </button>

                <button class="btn btn-sm btn-info roll-btn px-2" data-id="${row.product_id}"
                        data-name="${row.concatenated_product}"
                data-group="${row.group_name}"
                data-status="${row.product_status}"
                data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                title="ROL Update">
                <i class="bi bi-cloud-upload"></i>
                </button>`;

                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
                <button class="btn btn-sm btn-primary edit-btn" 
                data-id="${row.product_id}">
                  <i class="bi bi-pencil"></i>
                </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `
                <button class="btn btn-sm btn-danger delete-btn" data-id="${row.product_id}">
                  <i class="bi bi-trash"></i>
                </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                                buttons += `
                <button class="btn btn-sm btn-success approve-btn" data-id="${row.product_id}">
              <i class="bi bi-check-circle me-2"></i> Approve
                </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'image_his')) {
                                buttons += `
                <button class="btn btn-sm btn-success img-btn" data-id="${row.product_id}
                                data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Img History"">
             <i class="bi bi-card-image"></i>
                </button>`;
                            }


                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'batch_no')) {
                                buttons += `
                <button class="btn btn-sm btn-secondary batch-btn" data-id="${row.product_id}"
                data-group="${row.group_name}"
                                data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Batch No">
                <i class="bi bi-copy"></i>
                </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr?.id === 'productspec')) {
                                buttons += `
                    <button class="btn btn-sm btn-danger spec-btn" 
                        data-id="${row.product_id}"
                        data-specid="${row.quality_product_specs_hdr_id}" 
                        data-active="${row.active}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Specification">
                        <i class="bi bi-arrows-fullscreen"></i>
                    </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr?.id === 'price')) {
                                buttons += `
                    <button class="btn btn-sm btn-primary price-btn" 
                        data-id="${row.product_id}"
                        data-specid="${row.quality_product_specs_hdr_id}" 
                        data-active="${row.active}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Assign Price">
                        <i class="bi bi-currency-rupee"></i>
                    </button>`;
                            }

                            return buttons;
                        }
                    },

                        { data: 'product_code', name: 'product_code' },
                        { data: 'group_name', name: 'group_name' },
                        { data: 'category_name', name: 'category_name' },
                        { data: 'subcategory_name', name: 'subcategory_name' },
                        { data: 'product_classification', name: 'product_classification' },
                        { data: 'concatenated_product', name: 'concatenated_product' },
                        { data: 'uom_code', name: 'uom_code' },
                        { data: 'qc_check', name: 'qc_check' },
                        { data: 'subinventory_name', name: 'subinventory_name' },
                        { data: 'locator_code', name: 'locator_code' },
                        { data: 'hsn_code', name: 'hsn_code' },
                        { data: 'min_order_qty', name: 'min_order_qty' },
                        { data: 'max_order_qty', name: 'max_order_qty' },
                        { data: 'tax_credit', name: 'tax_credit' },
                        { data: 'product_status', name: 'product_status' },
                        { data: 'first_name', name: 'first_name' },
                        { data: 'quality_product_specs_hdr_id', name: 'quality_product_specs_hdr_id', visible: false },
                        { data: 'active', name: 'active', visible: false },


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

        });


        // create function
        $(".create").click(function () {
            var url = "{{ URL::to('productcreate')}}";
            window.location.replace(url);
        });
        //edit function
        $(document).on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            const url = "{{ url('productedit') }}/" + id;
            window.location.href = url;
        });

        //view function
        $(document).on('click', '.view-btn', function () {
            const id = $(this).data('id');
            const url = "{{ url('productview') }}/" + id;
            window.location.href = url;
        });


        // delete function
        let deleteId = null;

        $(document).on('click', '.delete-btn', function () {
            deleteId = $(this).data('id');
            $('#globalDeleteModal').modal('show');
        });

        $('#globalConfirmDeleteBtn').on('click', function () {
            if (deleteId) {
                $.ajax({
                    url: "{{ url('productdelete') }}/" + deleteId,
                    type: "GET",
                    success: function (data) {
                        if (data == '0') {
                            $('#globalDeleteModal').modal('hide');
                            showCustomAlert('Deleted successfully!', 'success');
                            $('#Producttbl').DataTable().ajax.reload();
                        }
                        if (data == '1') {
                            $('#globalDeleteModal').modal('hide');
                            showCustomAlert("You Can't delete , Product Used in SomeWhere.", 'error');
                            $('#Producttbl').DataTable().ajax.reload();
                        }
                    },
                    error: function (xhr) {
                        $('#globalDeleteModal').modal('hide');
                        const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
                        showCustomAlert(errorMsg, 'error');
                    }
                });
            }
        });



        // approve
        $(document).on('click', '.approve-btn', function () {
            const id = $(this).data('id');
            const url = "{{ url('productapproved') }}/" + id;
            window.location.href = url;
        });

        // rol update
        $(document).on('click', '.roll-btn', function () {

            const product_id = $(this).data('id');
            const product_name = $(this).data('name');
            const group_name = $(this).data('group');
            const product_status = $(this).data('status');


            if (product_status == 'APPROVED') {

                var groupname = "{{\Session::get('groupname')}}";
                if (groupname == '7' && (group_name == 'RAW MATERIALS' || group_name == 'PACKING MATERIALS')) {

                    $(".prd_name").html(product_name);
                    $(".prd_group").html(group_name);
                    $(".product_id").val(product_id);

                    $("#rolModal").modal('show');
                    var url = "{{URL::to('prdroledit')}}?id=" + product_id;
                    $.get(url, function (data) {

                        $('.min_order_qty').val(data.min_order_qty);
                        $('.min_stock_level2').val(data.min_stock_level2);
                        $('.min_stock_level3').val(data.min_stock_level3);
                        $('.max_order_qty').val(data.max_order_qty);
                        $('.re_order_level').val(data.re_order_level);
                        $('.mpq_qty').val(data.mpq_qty);
                        $('.gross_weight').val(data.gross_weight);
                        $('.net_weight').val(data.net_weight);
                    });
                }
                else if (groupname == '11' && group_name == 'FINISHED GOODS') {
                    $(".prd_name").html(product_name);
                    $(".prd_group").html(group_name);
                    $(".product_id").val(product_id);

                    $("#rolModal").modal('show');
                    var url = "{{URL::to('prdroledit')}}?id=" + product_id;
                    $.get(url, function (data) {

                        $('.min_order_qty').val(data.min_order_qty);
                        $('.min_stock_level2').val(data.min_stock_level2);
                        $('.min_stock_level3').val(data.min_stock_level3);
                        $('.max_order_qty').val(data.max_order_qty);
                        $('.re_order_level').val(data.re_order_level);
                        $('.mpq_qty').val(data.mpq_qty);
                        $('.gross_weight').val(data.gross_weight);
                        $('.net_weight').val(data.net_weight);
                    });

                } else if (groupname == '8' && (group_name == 'SEMI FINISHED GOODS' || group_name == 'FINISHED GOODS')) {
                    $(".prd_name").html(product_name);
                    $(".prd_group").html(group_name);
                    $(".product_id").val(product_id);
                    $("#rolModal").modal('show');
                    var url = "{{URL::to('prdroledit')}}?id=" + product_id;
                    $.get(url, function (data) {

                        $('.min_order_qty').val(data.min_order_qty);
                        $('.min_stock_level2').val(data.min_stock_level2);
                        $('.min_stock_level3').val(data.min_stock_level3);
                        $('.max_order_qty').val(data.max_order_qty);
                        $('.re_order_level').val(data.re_order_level);
                        $('.mpq_qty').val(data.mpq_qty);
                        $('.gross_weight').val(data.gross_weight);
                        $('.net_weight').val(data.net_weight);
                    });
                } else if (groupname == '1' || groupname == '4') {
                    $(".prd_name").html(product_name);
                    $(".prd_group").html(group_name);
                    $(".product_id").val(product_id);
                    $("#rolModal").modal('show');
                    var url = "{{URL::to('prdroledit')}}?id=" + product_id;
                    $.get(url, function (data) {

                        $('.min_order_qty').val(data.min_order_qty);
                        $('.min_stock_level2').val(data.min_stock_level2);
                        $('.min_stock_level3').val(data.min_stock_level3);
                        $('.max_order_qty').val(data.max_order_qty);
                        $('.re_order_level').val(data.re_order_level);
                        $('.mpq_qty').val(data.mpq_qty);
                        $('.gross_weight').val(data.gross_weight);
                        $('.net_weight').val(data.net_weight);
                    });
                } else {
                    showCustomAlert("Please Select Your Respective product group for ROL Update", "warning");
                }
            }
            else {
                showCustomAlert("Please Select Approved Product", "info");
            }

        });


        $(document).on('click', '.rol_save', function () {

            var id = $(".product_id").val();
            var min_order_qty = $(".min_order_qty").val();
            var min_stock_level2 = $(".min_stock_level2").val();
            var min_stock_level3 = $(".min_stock_level3").val();
            var max_order_qty = $(".max_order_qty").val();
            var re_order_level = $(".re_order_level").val();
            var mpq_qty = $(".mpq_qty").val();
            var gross_weight = $(".gross_weight").val();
            var net_weight = $(".net_weight").val();

            $.get("rolupdate?id=" + id + "&min_order_qty=" + min_order_qty + "&min_stock_level2=" + min_stock_level2 + "&min_stock_level3=" + min_stock_level3 + "&max_order_qty=" + max_order_qty + "&re_order_level=" + re_order_level + "&mpq_qty=" + mpq_qty + "&gross_weight=" + gross_weight + "&net_weight=" + net_weight, function (data) {

                if ($.trim(data) == '1') {
                    showCustomAlert("ROL Details Updated Successfully", "success");
                    $("#grid1")[0].triggerToolbar();
                } else {
                    showCustomAlert('Please Try Again', 'error');
                    $("#grid1")[0].triggerToolbar();
                }
            });
            $("#rolModal").modal('hide');

        });


        // image history

        $(document).on('click', '.img-btn', function () {


            const id = $(this).data('id');
            const url = "{{ url('producthistoryview') }}/" + id;
            window.location.href = url;
        });


        // batch no

        $(document).on('click', '.batch-btn', function () {


            const id = $(this).data('id');
            const group = $(this).data('group');

            if (group == 'SEMI FINISHED GOODS') {
                $('#myModal1').modal('show');
                $('.modal-dialog').width('40%');
                $("#myModal1").modal({ backdrop: "static" });
            }
            else {
                $('#myModal1').modal('hide');
                showCustomAlert("Please Choose Semi Finished Goods Product", "warning");
            }

        });


        // specfication	
        $(document).on('click', '.spec-btn', function () {


            const id = $(this).data('id');
            let productspecid = $(this).data('specid');
            const active = $(this).data('active');

            if (productspecid == null) {
                productspecid = 0;
            }

            if (active == "Yes") {
                window.location.replace('productspec/' + id + '/' + productspecid);
            } else {
                showCustomAlert("PRoduct is not Active", "info");
            }

        });










        function resizeDatagridWidth() {

            var $grid = $(this);
            var columns = $grid.jqGrid('getGridParam', 'colModel');
            var colsTotalWidth = 0;
            for (var i = 0; columns[i]; i++) {
                colsTotalWidth += columns[i].width;
                $grid.setColProp(columns[i].name, { width: columns[i].width, widthOrg: columns[i].width });
            }

            colsTotalWidth += 50;

            $(this).jqGrid('setGridWidth', colsTotalWidth, true);

        }


        $("#assign").click(function () {

            var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
                cellValues = [];
            for (i = 0, n = selIds.length; i < n; i++) {
                cellValues.push($grid.jqGrid("getCell", selIds[i], "product_id"));
            }

            if (cellValues) {
                var html = "";
                $.get('product/companydetails', function (data) {

                    html += '<table class="table"> \n\
                                        <thead>\n\
                                            <tr><th></th>\n\
                                                <th></th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tbody>';
                    $.each(data, function (key, value) {
                        html += '<tr class="mytable"><td><input type="checkbox" name="company_id" class="company_id" value="' + value.company_id + '"></td><td>&nbsp;</td><td>' + value.company_name + '</td></tr>';
                    });
                    html += '</tbody></table>';
                    $('.companybody').html(html);
                    $('.open').trigger('click');
                });
            }

            else {

                notyMsg('info', "Please select a row");
            }

        });



        $('#myModal1').on('shown.bs.modal', function () {
            $('.btn-success').click(function () {
                var batchno = $('#batch_no1').val();


                const id = $(this).data('id');

                var editurl = "{{URL::to('batchnoedit')}}/" + id + "?batchno=" + batchno;


                $.get(editurl, function (data) {

                    var red_url = "{{ URL::to('product') }}";

                    if (data == '1') {
                        showCustomAlert('Btach Number Updated Successfully', 'success');
                        setTimeout(function () {
                            window.location.href = red_url;
                        }, 1500);
                    }


                });
            });
        });



        $(document).on('click', '.price-btn', function () {
            var table = $('#Producttbl').DataTable();

            // Get all selected rows
            var selectedRows = table.rows({ selected: true }).data();
            var myarray = [];
            var che = '';
            var group = '';

            if (selectedRows.length > 0) {
                // Collect group_name values
                for (var i = 0; i < selectedRows.length; i++) {
                    var check_group = selectedRows[i].group_name;
                    if (check_group) {
                        myarray.push(check_group);
                    }
                }

                // Validation logic
                if (
                    ((myarray.includes("FINISHED GOODS") && myarray.includes("RAW MATERIALS")) ||
                        (myarray.includes("FINISHED GOODS") && myarray.includes("PACKING MATERIALS")) ||
                        (myarray.includes("FINISHED GOODS") && myarray.includes("PACKING MATERIALS") && myarray.includes("RAW MATERIALS")))
                ) {
                    che = '2';
                }

                if (myarray.includes("SEMI FINISHED GOODS")) {
                    che = '1';
                }

                if (che === '1') {
                    showCustomAlert("No Pricelist for these product", "info");
                    table.ajax.reload();
                    return;
                }

                if (che === '2') {
                    showCustomAlert("Select Only sales product or Only Purchase product", "info");
                    table.ajax.reload();
                    return;
                }

                if (che === '') {
                    // Use the first selected row as reference
                    var firstRow = selectedRows[0];
                    var check_group = firstRow.group_name;

                    if (
                        (check_group === "RAW MATERIALS" || check_group === "PACKING MATERIALS") &&
                        (check_group !== "FINISHED GOODS" &&
                            check_group !== "GIFT PRODUCTS" &&
                            check_group !== "SAMPLE PRODUCTS" &&
                            check_group !== "POSTER PRODUCT")
                    ) {
                        group = "Purchase";
                    } else if (check_group === "FINISHED GOODS") {
                        group = "Sales";
                    }

                    if (group) {
                        $('#customerModal').modal('show');
                        $('#customerModal').width("100%");

                        // Reload customer grid with price_list_type
                        $(mygrid).jqGrid('setGridParam', {
                            postData: { "price_list_type": group }
                        }).trigger('reloadGrid');
                    } else {
                        showCustomAlert("Select Only sales product or Only Purchase product", "info");
                        table.ajax.reload();
                    }
                }
            } else {
                showCustomAlert("Please select a row", "info");
            }
        });


            /*	var mygrid = $("#pricegrid"),
                pagerSelector = "#pager",
                myAddButton = function(options) {
                    mygrid.jqGrid('navButtonAdd',pagerSelector,options);
                    mygrid.jqGrid('navButtonAdd','#'+mygrid[0].id+"_toppager",options);
                };

                mygrid.jqGrid({
                    url: "{{ URL::to('getPurchasepricelistData/null?pagemethod=""') }}",
        datatype: "json",
            mtype: "GET",
                height: 320,
                    width: 1000,
                        colModel: [
                            { name: "pricelist_hdr_id", label: "id", hidden: true, width: 55 },
                            { name: "pricelist_name", label: "Pricelist Name", width: 55 },
                            { name: "price_list_type", label: "Pricelist Type", width: 55 },
                            { name: "start_date", label: "Statr Date", width: 55 },
                            { name: "end_date", label: "End Date", width: 55 },

                        ],

                            iconSet: "fontAwesome",
                                rowNum: 10,
                                    rowList: [10, 20, 100, 1000],
                                        sortorder: "asc",
                                            viewrecords: true,
                                                gridview: true,
                                                    rownumbers: true,

                                                        pager: pagerSelector,
                                                            toppager: true,
                                                                searching: {
            defaultSearch: "cn"
        }
                });

        jQuery(mygrid).jqGrid('filterToolbar', { stringResult: true, searchOnEnter: false });
        mygrid.jqGrid('navGrid', pagerSelector,
            { cloneToTop: true, edit: false, add: false, del: false, search: true });
        myAddButton({
            caption: "Select Price",
            title: "Price",
            buttonicon: 'ui-icon-plus',
            onClickButton: function () {

                var gr = jQuery(mygrid).jqGrid('getGridParam', 'selrow');
                var price = jQuery(mygrid).jqGrid('getCell', gr, 'pricelist_hdr_id');
                var type = jQuery(mygrid).jqGrid('getCell', gr, 'price_list_type');
                if (type == "Sales") {
                    var url = "{{URL::to('salespricelistedit')}}";
                }
                else {
                    var url = "{{URL::to('purchasepricelistedit')}}";
                }
                var gr = jQuery("#grid1").jqGrid('getGridParam', 'selrow');
                var cellValue = jQuery("#grid1").jqGrid('getCell', gr, 'product_id');
                var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
                    cellvalues = [];
                var check = '';
                var j = 0;
                for (i = 0, n = selIds.length; i < n; i++) {
                    var v = $grid.jqGrid("getCell", selIds[i], "product_id");
                    if (v != false)
                        cellvalues.push(v);
                }

                if (price) {
                    var a = 1;
                    var editUrl = url + '/' + price + '?products=' + cellvalues + '&sr=' + a;
                    window.location.replace(editUrl);
                    $('#customerModal').modal('hide');
                }
                else {
                    notyMsg('info', "Please select a row");
                }
            }
        });  */



        /*Create Pricelist Funcion*/
        $("#pricecreate").click(function () {

            var gr = jQuery("#grid1").jqGrid('getGridParam', 'selrow');
            var cellValue = jQuery("#grid1").jqGrid('getCell', gr, 'product_id');
            var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n, groupname = [];
            var myarray = [];
            var check = '';
            var group = '';
            var che = '';
            var j = 0;
            for (i = 0, n = selIds.length; i < n; i++) {
                var check_group = $grid.jqGrid("getCell", selIds[i], "group_name");
                if (check_group) {
                    myarray.push(check_group);

                }
            }
            if (((jQuery.inArray("FINISHED GOODS", myarray)) != '-1' && (jQuery.inArray("RAW MATERIALS", myarray)) != '-1') || ((jQuery.inArray("FINISHED GOODS", myarray)) != '-1' && (jQuery.inArray("PACKING MATERIALS", myarray)) != '-1') || ((jQuery.inArray("FINISHED GOODS", myarray)) != '-1' && (jQuery.inArray("PACKING MATERIALS", myarray)) != '-1') && (jQuery.inArray("RAW MATERIALS", myarray)) != '-1') {

                che = '2';
            }
            if ((jQuery.inArray("SEMI FINISHED GOODS", myarray)) != '-1') {
                che = '1';
            }
            if (che == '1') {
                notyMsg('info', "No Pricelist for these product");
                $("#grid1")[0].triggerToolbar();
            }
            if (che == '2') {
                notyMsg('info', "Select Only sales product or Only Purchase product ");
                $("#grid1")[0].triggerToolbar();
            }


            if (che == '') {

                if ((check_group == "RAW MATERIALS") || (check_group == "PACKING MATERIALS") && (check_group != "FINISHED GOODS" && check_group != "GIFT PRODUCTS" && check_group != "SAMPLE PRODUCTS" && check_group != "POSTER PRODUCT"))
                    group = "purchasepricelistcreate";
                else if (check_group == "FINISHED GOODS")
                    group = "salespricelistcreate";

                var gr = jQuery("#grid1").jqGrid('getGridParam', 'selrow');
                var cellValue = jQuery("#grid1").jqGrid('getCell', gr, 'product_id');
                var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
                    cellvalues = [];
                var check = '';
                var j = 0;
                for (i = 0, n = selIds.length; i < n; i++) {
                    var v = $grid.jqGrid("getCell", selIds[i], "product_id");
                    if (v != false)
                        cellvalues.push(v);
                }
                if (gr) {
                    if (group) {
                        var a = 1;
                        window.location.replace(check_group + '?products=' + cellvalues + '&sr=' + a);
                    } else {
                        notyMsg('info', "Select Only sales product or Only Purchase product ");
                    }
                }
                else {
                    notyMsg('info', "Please select a row");
                }
            }


        });








        $("#company_assign").click(function () {

            var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
                cellValues = [];
            for (i = 0, n = selIds.length; i < n; i++) {
                cellValues.push($grid.jqGrid("getCell", selIds[i], "product_id"));
            }

            var company = [];
            $.each($("input[name='company_id']:checked"), function () {
                company.push($(this).val());
            });

            $.get('product/companyassign/' + cellValues + '/' + company, function (data) {
                if (data == 1) {

                    $('#cancel').trigger('click');
                    notyMsg("success", "Company assigned Successfully");

                } else {
                    notyMsg("info", "Company not assigned");
                }

            });
        });


        function formatImage(cellValue, options, rowObject) {

            cellValue = cellValue.replace("[", '');
            cellValue = cellValue.replace("]", '');
            cellValue = cellValue.replace('"', '');
            cellValue = cellValue.replace('"', '');
            cellValue = cellValue.split(',', '');
            console.log(cellValue);
            var url = "{{URL::to('/uploads/product_image')}}/" + cellValue;
            var imageHtml = "<img width='110' height='70' src=" + url + " originalValue='" + cellValue + "' />";
            console.log(url);
            return imageHtml;

        }

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#myImg').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#choosefile").change(function () {
            readURL(this);
        });






    </script>

@endpush