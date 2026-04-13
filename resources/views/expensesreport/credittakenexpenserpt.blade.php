@extends('layouts.header')
@section('content')
  <h3 class="text-danger mb-4"> Credit Taken Report - Expense </h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg border-0 rounded-4 mb-4">

    <div class="card-body">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
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

        <div class="d-flex justify-content-center mt-4">
          <button type="button" class="btn bg-success bg-gradient text-white px-4 report_search" id="report_search">
            <i class="bi bi-search-heart me-1"></i> Search
          </button>
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
              <th class="freeze">Expense No</th>
              <th>Expense Date</th>
              <th>Created Date</th>
              <th>Updated Date</th>
              <th>Expense Type</th>
              <th>Employee Code</th>
              <th>Employee Name</th>
              <th>Employee Type</th>
              <th>Supplier Code</th>
              <th>Supplier Name</th>
              <th>Supplier GST NO</th>
              <th>Customer Code</th>
              <th>Customer Name</th>
              <th>Customer GST No</th>
              <th>Reference No</th>
              <th>Bill Date</th>
              <th>Reverse Charge</th>
              <th>Tds Applicable</th>
              <th>Tds Account</th>
              <th>Tds Percentage</th>
              <th>Tds Amount</th>
              <th>Round Off</th>
              <th>Expense Account Name</th>
              <th>Expense Account Structure</th>
              <th>Expense Amount</th>
              <th>HSN Code</th>
              <th>Tax Group</th>
              <th>SGST No</th>
              <th>CGST No</th>
              <th>IGST</th>
              <th>Tax Amount</th>
              <th>Expense Total</th>
              <th>Remarks</th>
              <th>Credit Taken</th>
              <th>Credit Taken Month</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Expense No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Expense
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Created
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Updated
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Expense
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Supplier
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Supplier
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Supplier GST
                  NO</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer GST
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Bill
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reverse
                  Charge</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tds
                  Applicable</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tds
                  Account</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tds
                  Percentage</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tds
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Round
                  Off</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Expense
                  Account Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Expense
                  Account Structure</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Expense
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">HSN
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SGST No</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">CGST No</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">IGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Expense
                  Total</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Remarks</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit
                  Taken</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit Taken
                  Month</span></th>


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
          url: "{{ url('getcredittakenexpense') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "expense_no" },
          { data: "expense_date" },
          { data: "created_at" },
          { data: "updated_at" },
          { data: "expense_type" },
          { data: "employee_number" },
          { data: "first_name" },
          { data: "emp_type" },
          { data: "supplier_number" },
          { data: "supplier_name" },
          { data: "gst_number" },
          { data: "customer_number" },
          { data: "customer_name" },
          { data: "gst_no" },
          { data: "invoice" },
          { data: "bill_date" },
          { data: "reverse_charge" },
          { data: "tds_applicable" },
          { data: "concatenated_segments" },
          { data: "tds_prcnt" },
          { data: "tdsamount" },
          { data: "round_off" },
          { data: "accountname" },
          { data: "accountstructure" },
          { data: "expense_line_amount" },
          { data: "classification_code" },
          { data: "tax_group_name" },
          { data: "sgst" },
          { data: "cgst" },
          { data: "igst" },
          { data: "taxamount" },
          { data: "expense_amount" },
          { data: "remarks" },
          { data: "credit_taken" },
          { data: "credit_date" }
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

    });

  </script>
@endpush