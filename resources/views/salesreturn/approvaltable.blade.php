@extends('layouts.header')
@section('content')
<h3 class="text-danger">Sales Return</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3 mt-2"></div>

<div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
        <table id="SalesTbl" class="table table-bordered table-striped w-100">
            <thead>
                <tr class="table-warning">
                    <th>Rma Reference Number</th>
                    <th>Return Date</th>
                    <th>Return Status</th>
                    <th>Reference No</th>
                    <th>Customer Name</th>
                    <th>Return Source</th>
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

    // Add create button purpose
    $(document).ready(function () {
        if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
            $('#toolbar-container').append(`
              <button class="btn btn-info text-white px-4 create me-2">Create
                <i class="bi bi-plus-circle"></i> 
              </button>
            `);
        }
    });

    // data table funcrion	
    $(document).ready(function () {

        var table = $('#SalesTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: "returndataApproval",
            columns: [

                { data: 'rma_ref_no', name: 'rma_ref_no' },
                { data: 'return_date', name: 'return_date' },
                { data: 'return_status', name: 'return_status' },
                { data: 'reference_no', name: 'reference_no' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'return_source', name: 'return_source' },
                {
                    data: 'so_rma_hdr_id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',

                    render: function (data, type, row) {
                        let buttons = '';
                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'approval')) {
                            buttons += `
                <button class="btn btn-sm btn-success approve-btn" data-id="${row.so_rma_hdr_id}" data-status="${row.return_status}">
                 Approve
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


    // create	

    $(document).on('click', '.create', function () {
        window.location.replace('salesreturncreate');
    });


    // Approval
			$(document).on('click', '.approve-btn', function () {
	
				 const id = $(this).data('id');
				
                  window.location.replace('salesreturnapprovalcreate/' +id);

            });


</script>

@endpush