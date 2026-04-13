@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Expenses</h3>
    @include('layouts.breadcrumb')
    <div class="d-flex align-items-center justify-content-between mb-3 mt-1">
        <div id="toolbar-container" class="create"></div>

        <button class="btn btn-primary text-white px-4 ms-2" id="credit_update">
            Credit Update
        </button>
    </div>



    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4 table-responsive">
            <table id="AccTbl" class="table table-bordered table-striped">
                <thead>
                    <tr class="table-warning">
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Actions</th>
                        <th>Expense No</th>
                        <th>Expense Date</th>
                        <th>Expense Type</th>
                        <th>Expense Status</th>
                        <th>Expense Amount</th>
                        <th>Bill No</th>
                        <th>Bill Date</th>
                        <th>Supplier Name</th>
                        <th>Customer Name</th>
                        <th>Employee Name</th>
                        <th>Employee Type</th>
                        <th>Remarks</th>
                        <th>Credit Taken</th>
                        <th>Credit Taken Date</th>
                        <th>Created By</th>
                        <th>Approved/Rejected By</th>
                    </tr>

                    <tr class="table-danger">
                        <th></th> <!-- Empty for checkbox -->
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
                <tbody></tbody>
            </table>
        </div>
    </div>



    <!-- Credit Update Modal -->
    <div class="modal fade" id="creditModal" tabindex="-1" aria-labelledby="creditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="creditModalLabel">Credit Update Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="credit_update_form" data-parsley-validate>
                        <input type="hidden" class="invoice_id" value="">

                        <div class="mb-3">
                            <label class="form-label"><strong>Credit Taken:</strong></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="credit_taken" id="credit_taken_yes"
                                    value="Yes">
                                <label class="form-check-label" for="credit_taken_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="credit_taken" id="credit_taken_no"
                                    value="No">
                                <label class="form-check-label" for="credit_taken_no">No</label>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="credit_date" class="col-sm-4 col-form-label">Date</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control credit_date start_date" id="credit_date"
                                    name="credit_date" placeholder="Select date">
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="text-center">
                            <button type="button" class="btn btn-success" id="credit_update_val">Update</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="expfileattachModal" tabindex="-1" aria-labelledby="expfileattachModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-3">

                <!-- Header -->
                <div class="modal-header bg-primary text-white py-2">
                    <h5 class="modal-title" id="expfileattachModalLabel">Check File Attachment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div><strong>Expense Number:</strong> <span class="intbal_no"></span></div>
                        <div><strong>Expense Date:</strong> <span class="intbal_date"></span></div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-6">
                            <strong>Is Attachment Enabled for this Expense?</strong>
                        </div>
                        <div class="col-md-6">
                            <span class="expensefileattached text-primary fw-bold"></span>
                            <input type="hidden" class="intbal_id">
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                  <button class="btn btn-success text-white px-4 create me-2">Create
                    <i class="bi bi-plus-circle"></i> 
                  </button>
                `);
            }
        });

        $(document).ready(function () {

            var status = "{{$status}}";

            var table = $('#AccTbl').DataTable({
                processing: true,
                serverSide: false,
                scrollX: true,
                scrollY: "50vh",
                orderCellsTop: true,
                ajax: "getExpenseindexData?status=" + status,
                columns: [
                    {
                        data: 'expense_id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function (data) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        }
                    },
                    {
                        data: 'expense_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',

                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                                buttons += `
            <button class="btn btn-sm btn-warning view-btn" data-id="${row.expense_id}" data-status="${row.expense_status}">
              <i class="bi bi-eye"></i>
            </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
            <button class="btn btn-sm btn-primary edit-btn" data-id="${row.expense_id}" data-status="${row.expense_status}">
              <i class="bi bi-pencil"></i>
            </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `
            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.expense_id}" data-status="${row.expense_status}">
              <i class="bi bi-trash"></i>
            </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                                buttons += `
            <button class="btn btn-sm btn-primary approve-btn" data-id="${row.expense_id}" data-status="${row.expense_status}"
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    title="Approve">
              <i class="bi bi-check2-circle"></i>
            </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'expensepay')) {
                                buttons += `
            <button class="btn btn-sm btn-secondary expensepay-btn" data-id="${row.expense_id}" data-status="${row.expense_status}"
                    data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            title="Expense Pay">
              <i class="bi bi-credit-card-fill"></i>
            </button>`;
                            }


                            buttons += `
            <button class="btn btn-sm btn-success attachment-btn" data-id="${row.expense_id}" data-status="${row.expense_status}"
            data-number="${row.expense_no}"
            data-date="${row.expense_date}"
            data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            title="Attachment Check">
             <i class="bi bi-clipboard-check"></i>
            </button>`;

                            return buttons;
                        }
                    },

                    { data: 'expense_no', name: 'expense_no', className: 'text-center' },
                    { data: 'expense_date', name: 'expense_date', className: 'text-center' },
                    { data: 'expense_type', name: 'expense_type', className: 'text-center' },
                    { data: 'expense_status', name: 'expense_status', className: 'text-center' },
                    { data: 'expense_amount', name: 'expense_amount', className: 'text-center' },
                    { data: 'invoice', name: 'invoice', className: 'text-center' },
                    { data: 'bill_date', name: 'bill_date', className: 'text-center' },
                    { data: 'supplier_name', name: 'supplier_name', className: 'text-start' },
                    { data: 'customer_name', name: 'customer_name', className: 'text-start' },
                    { data: 'first_name', name: 'first_name', className: 'text-start' },
                    { data: 'emp_type', name: 'emp_type', className: 'text-center' },
                    { data: 'remarks', name: 'remarks', className: 'text-center' },
                    { data: 'credit_taken', name: 'credit_taken', className: 'text-center' },
                    { data: 'credit_date', name: 'credit_date', className: 'text-center' },
                    { data: 'createdby', name: 'createdby', className: 'text-start' },
                    { data: 'approvedby', name: 'approvedby', className: 'text-start' },

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


            $('#AccTbl thead').on('keyup change', ".column-search", function () {
                var colIndex = $(this).parent().index();
                table.column(colIndex).search(this.value).draw();
            });


            $('#select-all').on('click', function () {
                var rows = table.rows({ 'search': 'applied' }).nodes();
                $('input[type="checkbox"].row-checkbox', rows).prop('checked', this.checked);
            });


            $('#AccTbl tbody').on('change', '.row-checkbox', function () {
                if (!this.checked) {
                    var el = $('#select-all').get(0);
                    if (el && el.checked && ('indeterminate' in el)) {
                        el.indeterminate = true;
                    }
                }
            });
        });


        // Create

        $(".create").click(function () {
            var url = "{{  URL::to('expensescreate')}}";
            window.location.replace(url);
        });

        // expense pay

        $(document).on('click', '.expensepay-btn', function () {

            const id = $(this).data('id');

            window.location.replace('expensespaycreate/' + id);


        });


        /* Edit Function*/
        $(document).on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            const status = $(this).data('status');


            if (status != "APPROVED") {
                window.location.replace('expensescreate/' + id);
            }
            else {
                showCustomAlert("Approved Data Cant Edit", "error");
            }



        });



        /* Purpose For approve Function*/
        $(document).on('click', '.approve-btn', function () {

            const id = $(this).data('id');
            const status = $(this).data('status');

            window.location.replace('expenseapproval/' + id + '/' + status);



        });





        //view function
        $(document).on('click', '.view-btn', function () {
            const id = $(this).data('id');
            const url = "{{ url('expensesview') }}/" + id;
            window.location.href = url;
        });


        //Delete function
        $(document).on('click', '.delete-btn', function () {
            const id = $(this).data('id');
            const url = "{{ url('accountstructuredelete') }}/" + id;
            window.location.href = url;
        });


        // Credit update
        $('#credit_update').on('click', function () {
            var table = $('#AccTbl').DataTable();
            var selectedIds = [];
            var notApproved = false;

            // Loop through checked rows
            $('#AccTbl tbody input.row-checkbox:checked').each(function () {
                var rowData = table.row($(this).closest('tr')).data();
                if (rowData) {
                    selectedIds.push(rowData.expense_id);
                    if (rowData.expense_status !== "APPROVED") {
                        notApproved = true;
                    }
                }
            });

            if (selectedIds.length === 0) {
                showCustomAlert("Please select at least one row", "info");
                return;
            }

            if (notApproved) {
                showCustomAlert("Please select Approved rows only", "info");
                return;
            }

            // Show modal and set selected IDs
            $('#creditModal').modal('show');
            $('.expense_id').val(selectedIds.join(','));
        });


        // Handle Credit Update modal submit
        $(document).on('click', '#credit_update_val', function () {
            var expenseid = $('.expense_id').val();
            var url = "{{ URL::to('expensecreditupdate') }}/" + expenseid;
            var formdata = $('#credit_update_form').serialize();

            $.post(url, formdata, function (data) {
                var status = data.status;
                var msg = data.message;

                showCustomAlert(msg, "success");

                // Reload table or redirect
                var red_url = "{{ URL::to('expenses') }}";
                window.location.href = red_url;
            }).fail(function () {
                showCustomAlert("Error updating expense credit.", "error");
            });
        });

        // Attach file

        $(document).on('click', '.attachment-btn', function () {
            const expense_id = $(this).data('id');
            const expense_status = $(this).data('status');

            // Retrieve row data from DataTable (safer for date/no)
            const table = $('#AccTbl').DataTable();
            const rowData = table.row($(this).closest('tr')).data();

            const expense_no = rowData?.expense_no || '';
            const expense_date = rowData?.expense_date || '';

            // Only allow APPROVED expenses
            if (expense_status === 'INITIATED') {
                $(".intbal_no").text(expense_no);
                $(".intbal_date").text(expense_date);
                $(".intbal_id").val(expense_id);

                // Show modal first
                $("#expfileattachModal").modal('show');

                // Fetch attachment status
                const url = "{{ URL::to('expfileattachvalidate') }}?id=" + expense_id;
                $.get(url, function (data) {
                    $('.expensefileattached').text(data.choosefile || 'No');
                }).fail(function () {
                    $('.expensefileattached').text('Error checking attachment');
                });
            } else {
                showCustomAlert("Please select INITIATED expenses only!", "info");

            }
        });


    </script>

@endpush