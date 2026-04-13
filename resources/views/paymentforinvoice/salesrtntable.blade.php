@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> Payment For Credit Notes </h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Sales Return No</th>
            <th>Sales Return Date</th>
            <th>Type</th>
            <th>Balance Amount</th>
            <th>Customer Name</th>
            <th>Supplier Name</th>
            <th>Actions</th>
          </tr>
          <tr class="table-danger">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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
        serverSide: true,
        ajax: "getsalesrtndetailsData",
        columns: [
          { data: 'rma_ref_no' },
          { data: 'return_date' },
          { data: 'debitcredit_type' },
          { data: "balance_amount" },
          { data: "customer_name" },
          { data: "supplier_name" },

          {
            data: 'so_rma_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';

              buttons += `
          <button class="btn btn-sm btn-primary pay-btn" data-id="${row.so_rma_hdr_id}" data-status="${row.return_source}"
      data-bs-toggle="tooltip" 
      data-bs-placement="top" 
      title="Create Payment">
            <i class="bi bi-plus"></i>
          </button>`;

              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#AccTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    // payment	
    $(document).on('click', '.pay-btn', function () {
      const id = $(this).data('id');
      const status = $(this).data('status');

      window.location.replace('paymentforsalesrtncreate/' + id + '?source=' + status);

    });


  </script>

@endpush