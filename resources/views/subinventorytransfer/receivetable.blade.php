@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> Subinventory Transfer Receive </h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="TransTbl" class="table table-bordered table-striped" style="width: 120% !important;">
        <thead>
          <tr class="table-warning">
            <th>Actions</th>
            <th>Subinventorytransfer Number</th>
            <th>Transaction Date</th>
            <th>Product Group Name</th>
            <th>Product Name</th>
            <th>Batch Number</th>
            <th>Manufacture Date</th>
            <th>Product Expire Date</th>
            <th>From Loc</th>
            <th>To Loc</th>
            <th>Remarks</th>
            <th>QOH</th>
            <th>Transfer Qty</th>
          </tr>
          <tr class="table-info">
            <th></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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
      var table = $('#TransTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,

        ajax: "{{ route('getreceiveData') }}",
        columns: [
          {
            data: 'subinventory_transfer_line_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'receive')) {
                buttons += `
        <button class="btn btn-sm btn-success receive-btn" data-id="${row.subinventory_transfer_line_id}">
          Receive
        </button>`;
              }
              return buttons;
            }
          },

          { data: 'subtransfer_no', name: 'subtransfer_no' },
          { data: 'trx_date', name: 'trx_date' },
          { data: 'group_name', name: 'group_name' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'batch_no', name: 'batch_no' },
          { data: 'manufacture_date', name: 'manufacture_date' },
          { data: 'product_expire_date', name: 'product_expire_date' },
          { data: 'frm_subinv_id', name: 'frm_subinv_id' },
          { data: 'to_subinv_id', name: 'to_subinv_id' },
          { data: 'remarks', name: 'remarks' },
          { data: 'qoh', name: 'qoh' },
          { data: 'from_transfer_qty', name: 'from_transfer_qty' },

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

    // receive

    $(document).on('click', '.receive-btn', function () {

      const id = $(this).data('id');
      const url = "{{ url('subinventorytransfercreate') }}/" + id;
      window.location.href = url;

    });


  </script>

@endpush