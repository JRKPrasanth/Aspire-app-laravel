@extends('layouts.header')
@section('content')
<h3 class="text-danger">Sales Return Details</h3>
@include('layouts.breadcrumb')
 
<div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
        <table id="SalesTbl" class="table table-bordered table-striped w-100">
            <thead>
                <tr class="table-warning">
                    <th>Return Ref Number</th>
                    <th>Invoice Number</th>
                    <th>Return Date</th>
                    <th>Customer Name</th>
                    <th>Return Status</th>
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
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@endsection
@push('scripts')

<script type="text/javascript">
    // data table funcrion	
    $(document).ready(function () {

        var table = $('#SalesTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: "salesreturnviewdata",
            columns: [

                { data: 'rma_ref_no', name: 'rma_ref_no' },
                { data: 'invoice_number', name: 'invoice_number' },
                { data: 'return_date', name: 'return_date' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'return_status', name: 'return_status' },
                {
                    data: 'so_rma_hdr_id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',

                    render: function (data, type, row) {
                        let buttons = '';
                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                            buttons += `
                <button class="btn btn-sm btn-primary view-btn" data-id="${row.so_rma_hdr_id}">
                 View
                </button>`;
                        }

                        return buttons;
                    }
                }
            ]
        });

        // Individual column search
        $('#SalesTbl thead').on('keyup change', ".column-search", function () {
            var colIndex = $(this).parent().index();
            table.column(colIndex).search(this.value).draw();
        });
    });




    // View
    $(document).on('click', '.view-btn', function () {

        const id = $(this).data('id');

        window.location.replace('salesreturnviews/' + id);

    });

	
</script>

@endpush