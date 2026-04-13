@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Goods Receipt Note</h3>
  @include('layouts.breadcrumb')

  <div class="col-md-12">

    <a class='btn btn-danger mt-2' onclick="location.href = '{{url('purchaseqc')}}'">Cancel</a>

  </div>



  <?php if ($pageMethod == 'qualitymr') { ?>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="QcTbl1" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Batch No</th>
              <th>S Date</th>
              <th>E Date</th>
              <th>Employee</th>
              <th>Product Name</th>
              <th>Actions</th>
            </tr>
            <tr class="table-info">
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th></th>
            </tr>
          </thead>

          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <?php } else { ?>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="QcTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th>GRN Number</th>
              <th>Product Code</th>
              <th>Product Name</th>
              <th>QC Type</th>
              <th>GRN Status</th>
              <th>PO Number</th>
              <th>Supplier Name</th>
              <th>Subcontract Name</th>
              <th>DC Number</th>
              <th>DC Date</th>
              <th></th>
              <th></th>

            </tr>
            <tr class="table-info">
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
              <th></th>
              <th></th>
            </tr>
          </thead>

          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <?php } ?>


@endsection
@push('scripts')


  <script>

    $(document).ready(function () {

      var table = $('#QcTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getGrntabledata') }}",
        columns: [
          {
            data: 'grn_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data, type, row) {
              return `
                    <button class="btn btn-sm btn-success me-1 qc-check" 
            data-id="${data}"
            data-po_id ="${row.po_id}"
            data-pro_id ="${row.product_id}"
            data-qc_type ="${row.qc_type}"
            data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            title="Quality Check">
                        <i class="bi bi-check2-circle"></i>
                    </button>`;
            }
          },
          { data: 'grn_number', name: 'grn_number' },
          { data: 'product_code', name: 'product_code' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'qc_type', name: 'qc_type' },
          { data: 'grn_status', name: 'grn_status' },
          { data: 'po_number', name: 'po_number' },
          { data: 'supplier_name', name: 'supplier_name' },
          { data: 'subcontract_name', name: 'subcontract_name' },
          { data: 'dc_number', name: 'dc_number' },
          { data: 'dc_date', name: 'dc_date' },
          { data: 'po_id', name: 'po_id', visible: false },
          { data: 'product_id', name: 'product_id', visible: false },

        ]
      });

      // Individual column search
      $('#QcTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });

    // QC check
    $(document).on('click', '.qc-check', function () {

      const grn_id = $(this).data('id');
      const product_id = $(this).data('pro_id');
      const po_id = $(this).data('po_id');
      const qc_type = $(this).data('qc_type');


      if (qc_type != "") {
        var url = "{{ URL::to('qualitychecking') }}/" + grn_id + "/" + product_id + "/" + po_id + "/" + qc_type;
        window.location.replace(url);
      } else {
        showCustomAlert("Please Assign QC Type For This Product", "warning");
      }
    });



  </script>
@endpush