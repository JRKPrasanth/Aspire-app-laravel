@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Move To Inventory</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="MovTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th style="display:none;"></th>
              <th>Product Name</th>
              <th>GRN Number</th>
              <th>GRN Date</th>
              <th>PO Number</th>
              <th>QC Number</th>
              <th>QC Approved date</th>
              <th>Quality Check</th>
              <th>Supplier Name</th>
              <th>Created By</th>
              <th>Due days</th>


            </tr>
            <tr class="table-info">
              <th data-column="0"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th data-column="1" style="display:none;"></th>
              <th data-column="2"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th data-column="3"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th data-column="4"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th data-column="5"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th data-column="6"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th data-column="7"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th data-column="8"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th data-column="9"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th data-column="10"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th data-column="11"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            </tr>
          </thead>

          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>


  <!-- popup-->
  <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <h3 class="modal-title" id="myModalLabel">Move To Inventory</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body dialogue">
          <!-- Modal content goes here -->
        </div>

      </div>
    </div>
  </div>


@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      var table = $('#MovTbl').DataTable({
        processing: true,
        serverSide: false,
        order: [[3, 'desc']],
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "{{ route('MovinventoryData') }}",
        columns: [
          {
            data: 'qc_line_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'moveinv')) {
                buttons += `
            <button  class="btn btn-sm btn-primary me-1 movetoInv" data-id="${data}"
                data-qc_chk="${row.quality_check}"
              data-grn_id="${row.grn_line_id}"
              data-bs-toggle="tooltip" 
              data-bs-placement="top" 
              title="Move To Inventory"
            >Move</button>`;
              }

              return buttons;
            }

          },
          { data: 'grn_line_id', name: 'grn_line_id', visible: false },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'grn_number', name: 'grn_number' },
          { data: 'grn_date', name: 'grn_date' },
          { data: 'po_number', name: 'po_number' },
          { data: 'qc_number', name: 'qc_number' },
          { data: 'qc_date', name: 'qc_date' },
          { data: 'quality_check', name: 'quality_check' },
          { data: 'supplier_name', name: 'supplier_name' },
          { data: 'first_name', name: 'first_name' },
          { data: 'due_days', name: 'due_days' },
          

        ],

        initComplete: function () {
            let api = this.api();

            let $scrollHead = $(api.table().container())
                .find('.dataTables_scrollHead thead');

            $scrollHead.find('input.column-search').on('keyup change clear', function () {

                let columnIndex = $(this).closest('th').data('column');

                if (api.column(columnIndex).search() !== this.value) {
                    api.column(columnIndex).search(this.value).draw();
                }
            });
        }
      });

    });


    //  Move to Inv

    $(document).on('click', '.movetoInv', function () {

      const qc_line_id = $(this).data('id');
      const grn_line_id = $(this).data('grn_id');
      const qc_chk = $(this).data('qc_chk');

      if (qc_chk == "Yes") {

        window.location.replace('movetoinvform/' + qc_line_id + '/' + 'QC');

      } else {
        window.location.replace('movetoinvform/' + grn_line_id + '/' + 'GRN');
      }

    });


    // voucher
    $(document).on('click', '.voucher', function () {

      const qc_line_id = $(this).data('id');
      const grn_line_id = $(this).data('grn_id');
      const qc_chk = $(this).data('qc_chk');

      if (qc_chk == "Yes") {

        var url = "{{ URL::to('movetoinvformpopup')}}/" + qc_line_id + "/" + "QC";
        $.get(url, function (data) {
          $(".dialogue").html(data);
          $('#myModal').modal('show');
        });

      } else {
        var url = "{{ URL::to('movetoinvformpopup')}}/" + grn_line_id + "/" + "GRN";
        $.get(url, function (data) {
          $(".dialogue").html(data);
          $('#myModal').modal('show');
        });
      }


    });



  </script>

@endpush