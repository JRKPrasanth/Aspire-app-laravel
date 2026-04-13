@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    Journal Report
  </h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">

    <div class="card-body p-4">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

        <!-- First Row: Date Inputs -->
        <div class="row g-4 mb-3">
          <div class="col-md-2"></div>
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
        </div>

        <!-- Second Row: Centered Search Button -->
        <div class="row">
          <div class="col-md-12 text-center">
            <button type="button" class="btn btn-success text-white px-4 report_search" id="report_search">
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
              <th class="freeze">Name</th>
              <th>Journal type</th>
              <th>Journal Ref Name</th>
              <th>Date</th>
              <th>Month Year</th>
              <th>Ref</th>
              <th>Ref Name</th>
              <th>Batch Number</th>
              <th>Product Qty</th>
              <th>Rate</th>
              <th>Account</th>
              <th>Debit</th>
              <th>Credit</th>
            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Journal
                  type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Journal Ref
                  Name</span></th>
              <th><input type="text" class="column-search start_date" placeholder="Search"><span
                  style="display:none;">Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Month
                  Year</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Ref</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Ref
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Rate</span></th>    
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Account</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Debit</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit</span>
              </th>

            </tr>
          </thead>
          <tfoot>
            <tr class="table-info fw-bold">
              <th class="freeze">PAGE TOTAL</th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
            <tr class="table-success fw-bold">
              <th class="freeze">GRAND TOTAL</th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </tfoot>

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
    $(document).ready(function () {

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollY: "50vh",
        order: [[3, 'asc']],
        ajax: {
          url: "{{ url('journalreport') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "journal_name" },
          { data: "journal_type" },
          { data: "journal_ref_name" },
          { data: "journal_date" },
          { data: "monyr" },
          { data: "paymonyr" },
          { data: "reference_source" },
          { data: "ref_name" },
          { data: "batch_number" },
          { data: "product_qty" },
          { data: "rate_per" },
          { data: "concatenated_segments" },
          { data: "debit" },
          { data: "credit" }

        ],
        buttons: [
          {
            extend: 'colvis',
            text: '<i class="bi bi-layout-three-columns"></i> Columns',
            className: 'btn bg-primary btn-sm',
            postfixButtons: ['colvisRestore'] 
          },
          {
            text: 'Excel',
            action: function () {

              var start_date = $('#start_date').val();
              var end_date = $('#end_date').val();

              window.location.href =
                "{{ url('journalreport/export') }}" +
                "?start_date=" + start_date +
                "&end_date=" + end_date;
            }
          }
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
        },

        footerCallback: function () {

          let api = this.api();

          let num = function (i) {
            return typeof i === 'string'
              ? i.replace(/,/g, '') * 1
              : typeof i === 'number'
                ? i
                : 0;
          };

          // -------------------------
          // PAGE TOTAL (visible rows)
          // -------------------------

          let pageCredit = api.column(12, { page: 'current' }).data()
            .reduce((a, b) => num(a) + num(b), 0);

          let pageBalance = api.column(13, { page: 'current' }).data()
            .reduce((a, b) => num(a) + num(b), 0);

          // -------------------------
          // GRAND TOTAL (ALL rows)
          // -------------------------

          let grandCredit = api.column(12).data()
            .reduce((a, b) => num(a) + num(b), 0);

          let grandBalance = api.column(13).data()
            .reduce((a, b) => num(a) + num(b), 0);

          // PAGE TOTAL row (1st footer row)

          $(api.column(12).footer()).closest('tfoot').find('tr:eq(0) th:eq(12)')
            .html(pageCredit.toFixed(2));
          $(api.column(13).footer()).closest('tfoot').find('tr:eq(0) th:eq(13)')
            .html(pageBalance.toFixed(2));

          // GRAND TOTAL row (2nd footer row)

          $(api.column(12).footer()).closest('tfoot').find('tr:eq(1) th:eq(12)')
            .html(grandCredit.toFixed(2));
          $(api.column(13).footer()).closest('tfoot').find('tr:eq(1) th:eq(13)')
            .html(grandBalance.toFixed(2));
        }


      });

      // Trigger search
      $('.report_search').on('click', function () {
        $('#ReportTbl').DataTable().ajax.reload();
      });

    });
  </script>
@endpush