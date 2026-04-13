@extends('layouts.header')
@section('content')
<h3 class="text-danger">Receipt For Invoice</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="receiptcreate mb-3 mt-1"></div>


<div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
        <table id="ReciptTbl" class="table table-bordered table-striped w-100">
            <thead>
                <tr class="table-warning">
                    <th><input type="checkbox" id="select-all"></th>
                    <th>Invoice Number</th>
                    <th>Invoice Date</th>
                    <th>Invoice Type</th>
                    <th>Invoice Amount</th>
                    <th> Balance Amount</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Actions</th>
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
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'receiptinv')) {
        $('#toolbar-container').append(`
                <button class="btn btn-success text-white px-4 me-2">Create Receipt
                </button>
              `);
      }
    });


    // data table funcrion	
    $(document).ready(function () {

        var table = $('#ReciptTbl').DataTable({
            processing: true,
            serverSide: true,
            order: [[1, 'desc']],
            ajax: "getsfiData",
            columns: [
                {
                    data: 'invoice_hdr_id',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function (data) {
                      return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                    }
                },
                { data: 'invoice_number', name: 'invoice_number' },
                { data: 'invoice_date', name: 'invoice_date' },
                { data: 'invoice_type', name: 'invoice_type' },
                { data: 'invoice_grand_total', name: 'invoice_grand_total' },
                { data: 'balance_amount', name: 'balance_amount' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'invoice_status', name: 'invoice_status' },
                {
                    data: 'invoice_hdr_id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',

                    render: function (data, type, row) {
                        let buttons = '';

                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                            buttons += `
        <button class="btn btn-sm btn-warning view-btn me-1" data-id="${row.invoice_hdr_id}">
          <i class="bi bi-eye"></i>
        </button>`;
                        }

                       /* if (window.toolbarButtons?.some(btn => btn.attr.id === 'receiptinv')) {
                            buttons += `
        <button class="btn btn-sm btn-success create-btn" data-id="${row.invoice_hdr_id}"
                data-bs-toggle="tooltip" 
        data-bs-placement="top" 
        title="Create Receipt">
          <i class="bi bi-plus"></i>
        </button>`;
                        }*/
                        return buttons;
                    }
                }
            ]
        });

        // Individual column search
        $('#ReciptTbl thead').on('keyup change', ".column-search", function () {
            var colIndex = $(this).parent().index();
            table.column(colIndex).search(this.value).draw();
        });

        $('#select-all').on('click', function () {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"].row-checkbox', rows).prop('checked', this.checked);
      });

    $('#ReciptTbl tbody').on('change', '.row-checkbox', function () {
        var checked = $(this).is(':checked');
        var currentRow = table.row($(this).closest('tr')).data();
        var currentCustomer = currentRow.customer_name;

        if (checked) {
          // Get all currently checked Customers
          var checkedCustomers = [];
          $('#ReciptTbl tbody input.row-checkbox:checked').each(function () {
            var rowData = table.row($(this).closest('tr')).data();
            if (rowData && rowData.customer_name) {
              checkedCustomers.push(rowData.customer_name);
            }
          });

          // Check if all Customers are the same
          var uniqueCustomers = [...new Set(checkedCustomers)];
          if (uniqueCustomers.length > 1) {
            // Different Customers selected - prevent checking
            showCustomAlert("Can't select rows from different Customers!", "error");
            $(this).prop('checked', false);
            return;
          }
        } else {
          // If unchecked, update select-all indeterminate
          var el = $('#select-all').get(0);
          if (el && el.checked && ('indeterminate' in el)) {
            el.indeterminate = true;
          }
        }
      });
    });
	
	
	
	
    /* Purpose For Create*/
    $(document).on('click', '.create-btn', function () {

        const id = $(this).data('id');

       window.location.replace('receiptforinvoicecrt/'+id+'/'+'0'+'/'+'0');


    });

    $(".receiptcreate").click(function () {
      var table = $("#ReciptTbl").DataTable();
      var ids = [];
      var poIds = [];

      // Loop through all checked checkboxes
      $("#ReciptTbl tbody input.row-checkbox:checked").each(function () {
        var rowData = table.row($(this).closest("tr")).data();

        if (rowData) {
          if (rowData.invoice_hdr_id) ids.push(rowData.invoice_hdr_id);
          /*if (rowData.po_id) poIds.push(rowData.po_id);*/
        }
      });

      // If at least one row is selected
      if (ids.length > 0) {
        window.location.replace(
          "receiptforinvoicecrt/" + ids.join(",") + "/0/0"
        );
      } else {
        showCustomAlert("Please Select a Row", "info");
      }
    });

    /* Purpose For View Function*/

    $(document).on('click', '.view-btn', function () {

        const id = $(this).data('id');
        var return1 = "{{$pageMethod}}";

        window.location.replace('salesinvoiceview/' +id+'?return='+return1);

    });

</script>

@endpush
