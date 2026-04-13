@extends('layouts.header')
@section('content')

<h3 class="text-danger">Monthly Sheet Report</h3>
  @include('layouts.breadcrumb')
             
         
  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf
        <div class="row g-4 align-items-end">
          <div class="col-md-4">
            <label for="from" class="form-label fw-semibold">Start Date</label>
            <input type="text" class="form-control from" id="from" name="from" required
              autocomplete="off">
            <div class="invalid-feedback">Please select a start date.</div>
          </div>

          <div class="col-md-4">
            <label for="to" class="form-label fw-semibold">End Date</label>
            <input type="text" class="form-control to" id="to" name="to" required autocomplete="off">
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
              <th class="freeze">Reqd Date</th>
              <th class="freeze">Classification</th>
              <th>Product</th>
              <th>Pack</th>
              <th>Batch No</th>
              <th>Plan Qty</th>
              <th>Bacth Size</th>
              <th>Operation Plan Qty</th>
              <th>Filled Qty</th>
              <th>RM Avbl Date</th>
              <th>PM Avbl Date</th>
              <th>Purchase Remarks</th>
              <th>Prdouction Scheduled Date</th>
              <th>Production Actual Date</th>
              <th>QC Date</th>
              <th>Filling Scheduled Start Date</th>
              <th>Filling Scheduled End Date</th>
              <th>Filling Actual Start Date</th>
              <th>Filling Actual End Date</th>
              <th>Packing Actual Start Date</th>
              <th>Packing Actual End Date</th>
              <th>No of Days QC Pass Filling</th>
              <th>Operation BDR</th>
              <th>Total BDR</th>
              <th>Remarks</th>
            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Reqd Date</span></th>
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Classification</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Pack</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;"> Plan Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Bacth Size</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Operation Plan Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Filled Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">RM Avbl Date</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PM Avbl Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Purchase Remarks</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Prdouction Scheduled Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Production Actual Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">QC Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Filling Scheduled Start Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Filling Scheduled End Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Filling Actual Start Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Filling Actual End Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Packing Scheduled Start Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Packing Scheduled End Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">No of Days QC Pass Filling</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Operation BDR</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Total BDR</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Remarks</span></th>

            </tr>
          </thead>

          <tbody>
            <!-- Your dynamic row data goes here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection
@push('scripts')
<script>
$(function () {

    /* -----------------------------
       DATEPICKER INITIALIZATION
    ------------------------------ */
    $("#from, #to").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true
    });

    // Auto fill End Date = Start Date
    $("#from").on("change", function () {
        $("#to").val($(this).val());
    });


    /* -----------------------------
       DATATABLE INITIALIZATION
    ------------------------------ */
    var table = $('#ReportTbl').DataTable({

        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        fixedHeader: true,

        ajax: {
            url: "{{ url('monthrptdetails') }}",
            type: "GET",
            data: function (d) {
                d.from = $('#from').val();
                d.to   = $('#to').val();
            }
        },

        columns: [
            { class: 'freeze', data: "job_date" },
            { class: 'freeze', data: "product_classification" },
            { data: "concatenated_product" },
            { data: "pack_size" },
            { data: "batch_no" },
            { data: "plnd_qty" },
            { data: "batch_size" },
            { data: "oprn_plnd_qty" },
            { data: "filled_qty" },
            { data: "RM_date" },
            { data: "PM_date" },
            { data: "pur_rem" },
            { data: "prdn_schd_date" },
            { data: "prdn_date" },
            { data: "QC_date" },
            { data: "fllng_schd_strt_date" },
            { data: "fllng_schd_end_date" },
            { data: "fllng_actl_strt_date" },
            { data: "fllng_actl_end_date" },
            { data: "pckg_actl_strt_date" },
            { data: "pckg_actl_end_date" },
            { data: "NoOfDay_qc_fllng" },
            { data: "opn_bdr" },
            { data: "total_bdr" },
            { data: "opr_rem" }
        ],

        initComplete: function () {
            var api = this.api();

            let $scrollHead = $(api.table().container())
                .find('.dataTables_scrollHead thead');

            // Bind search inputs correctly
            $scrollHead.find('tr:eq(1) th').each(function (colIndex) {
                $('input.column-search', this).on('keyup change', function () {
                    if (api.column(colIndex).search() !== this.value) {
                        api.column(colIndex).search(this.value).draw();
                    }
                });
            });
        }
    });


    /* -----------------------------
       SEARCH BUTTON → RELOAD
    ------------------------------ */
    $('#report_search').on('click', function () {
        table.ajax.reload();
    });

});
</script>
@endpush