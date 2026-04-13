@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Pending Sales Invoice</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="reportTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th class="freeze">Invoice Number</th>
              <th>Payment Status</th>
              <th>Invoice Date</th>
              <th>Customer Name</th>
              <th>Invoice Amount</th>
              <th>Paid Amount</th>
              <th>Balance Amount</th>

            </tr>
            <tr class="table-danger">
              <th></th>
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Invoice Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment
                  Status</span></th>
              <th><input type="text" class="column-search start_date" placeholder="Search"><span
                  style="display:none;">Invoice Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Paid
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Balance
                  Amount</span></th>

            </tr>
          </thead>

          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>


@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      var table = $('#reportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "{{ route('getpendingreceiptData') }}",
        columns: [
          {
            data: 'invoice_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '150px',
            render: function (data, type, row) {
              return `
                    <button class="btn btn-sm btn-primary me-1 edit-btn" data-id="${data}">
                        <i class="bi bi-plus-circle"></i>
                    </button>`;
            }
          },

          { class: 'freeze', data: 'invoice_number', name: 'invoice_number' },
          { class: 'fw-bold text-danger', data: 'paid_status', name: 'paid_status' },
          { data: 'invoice_date', name: 'invoice_date' },
          { data: 'customer_name', name: 'customer_name' },
          { data: 'invoice_grand_total', name: 'invoice_grand_total' },
          { data: 'paid_amount', name: 'paid_amount' },
          { data: 'balance_amount', name: 'balance_amount' }


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




    // create payment	
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('receiptforinvoicecreate') }}/" + id + '/' + '0' + '/' + '0';
      window.location.href = url;
    });	
  </script>

@endpush