@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Bank Statement Details</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4">
            <div class="table-responsive">
                <table id="AccTbl" class="table table-bordered table-striped">
                    <thead>
                        <tr class="table-warning">
                            <th>Actions</th>
                            <th>Actions</th>
                            <th>Date</th>
                            <th>Value Date</th>
                            <th>Bank Cheque No</th>
                            <th>Narration</th>
                            <th>Bank Name</th>
                            <th>Ref Number</th>
                            <th>Ref Date</th>
                            <th>Ref Type</th>
                            <th>Supplier Name</th>
                            <th>Customer Name</th>
                            <th>Employee Name</th>
                            <th>Bill Ref Number</th>
                            <th>Cheque Number</th>
                            <th>Ref Amount</th>
                            <th>Statement Amount</th>

                        </tr>
                        <tr class="table-danger">
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
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>

                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>




@endsection
    @push('scripts')

        <script>


            // data table funcrion	
            $(document).ready(function () {
                var table = $('#AccTbl').DataTable({
                    processing: true,
                    serverSide: false,
                    scrollX: true,
                    scrollY: "50vh",
                    orderCellsTop: true,
                    ajax: "{{ route('getviewstatementdetails') }}",
                    columns: [

                        {
                            data: 'bankstmt_id',
                            name: 'actions',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',

                            render: function (data, type, row) {
                                let buttons = '';
                                if (window.toolbarButtons?.some(btn => btn.attr.id === 'unmatched')) {
                                    buttons += `
            <button class="btn btn-sm btn-primary unmatched-btn" data-id="${row.bankstmt_id}" data-table_find="${row.table_find}">
              Unmatch
            </button>`;
                                }
                                return buttons;
                            }
                        },
                        { data: "table_find", visible: false },
                        { data: "date" },
                        { data: "value_date" },
                        { data: "chq_no" },
                        { data: "narration" },
                        { data: "bank_name" },
                        { data: "ref_no" },
                        { data: "ref_date" },
                        { data: "payment_type" },
                        { data: "supplier_name" },
                        { data: "customer_name" },
                        { data: "first_name" },
                        { data: "billref" },
                        { data: "cheque_no" },
                        { data: "ref_amount" },
                        { data: "stmt_amount" },


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





            $(document).on('click', '.unmatched-btn', function () {

                const id = $(this).data('id');
                const table_find = $(this).data('table_find');

                var url = "{{ URL::to('unmatchedstatment') }}/" + id + '?table=' + table_find;

                $.getJSON(url, function (data) {

                    if (data != 0) {
                        showCustomAlert("Unmatch Updated Successfully", "success");
                        location.reload();
                    }
                    else {
                        showCustomAlert("Unmatch Not Updated", 'error');
                        location.reload();
                    }

                });

            });


        </script>

    @endpush