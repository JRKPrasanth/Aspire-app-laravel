@extends('layouts.header')
@section('content')
  <h3 class="text-danger">ITC Reversal Computation Details</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf
        <div class="row g-4 align-items-end">
          <div class="col-md-4">
            <label for="start_date" class="form-label fw-semibold">From Date</label>
            <input type="text" class="form-control start_date" id="start_date" name="start_date" required
              autocomplete="off">
            <div class="invalid-feedback">Please select a start date.</div>
          </div>

          <div class="col-md-4">
            <label for="end_date" class="form-label fw-semibold">To Date</label>
            <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off">
            <div class="invalid-feedback">Please select an end date.</div>
          </div>

          <div class="col-md-2 d-grid">
            <button type="button" class="btn btn-primary report_search" id="report_search">
              <i class="bi bi-search-heart me-1"></i> Search
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Month</th>
              <th>Tax Applicable</th>
              <th>Sample Product Name</th>
              <th>Batch No</th>
              <th>Sample Sold</th>
              <th>Sample Packed</th>
              <th>JC No</th>
              <th>Comp Product</th>
              <th>Prod UOM</th>
              <th>Comp Product Qty</th>
              <th>Sub Comp Group</th>
              <th>Sub Comp Product</th>
              <th>Sub Comp UOM</th>
              <th>Sub Batch</th>
              <th>Consumed Qty</th>
              <th>Purchase Cost</th>
              <th>Assess Value</th>
              <th>Sample Consumed Qty</th>
              <th>Sample Assess Value</th>
              <th>Tax Group</th>
              <th>Tax %</th>
              <th>Purchase Tax</th>
              <th>SGST</th>
              <th>CGST</th>
              <th>IGST</th>
              <th>Sample Tax</th>
              <th>ITC SGST</th>
              <th>ITC CGST</th>
              <th>ITC IGST</th>
              <th>Credit Status</th>
              <th>Credit Date</th>
              <th>Tax Credit Taken</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Month</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax Applicable</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sample Product Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sample Sold</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sample Packed</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">JC No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Comp Product</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Prod UOM</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Comp Product Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sub Comp Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sub Comp Product</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sub Comp UOM</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sub Batch</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Consumed Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Purchase Cost</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Assess Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sample Consumed Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sample Assess Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax %</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Purchase Tax</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SGST</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">CGST</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">IGST</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sample Tax</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ITC SGST</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ITC CGST</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">ITC IGST</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax Credit Taken</span></th>

            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>


@endsection

@push('scripts')


<script>


    $(document).ready(function () {

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,

        ajax: {
          url: "{{ url('getitccostData') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
        { class: 'freeze', data: "FnYr" },
        { data: "tax_credit" },
        { data: "name" },
        { data: "batch_number" },
        { data: "sls" },
        { data: "oprn" },
        { data: "job_no" },
        { data: "main_comp_prod_name" },
        { data: "prod_uom" },
        { data: "smp_main_comp_qty" },
        { data: "comp_grp_name" },
        { data: "comp_name" },
        { data: "comp_uom" },
        { data: "lot_no" },
        { data: "comp_qty" },
        { data: "comp_rate" },
        { data: "comp_val" },
        { data: "smp_cnsmd_qty" },
        { data: "smp_cnsmd_qty_val" },
        { data: "tax_group_name" },
        { data: "tax_group" },
        { data: "pur_tax_amt" },
        { data: "SGST" },
        { data: "CGST" },
        { data: "IGST" },
        { data: "smp_cnsmd_qty_tax" },
        { data: "ITC_SGST" },
        { data: "ITC_CGST" },
        { data: "ITC_IGST" },
        { data: "credit_taken" },
        { data: "credit_date" },
        { data: "credit_taken_tax" }
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

      // Trigger search
      $('.report_search').on('click', function () {
        $('#ReportTbl').DataTable().ajax.reload();
      });

    })

</script>
  @endpush