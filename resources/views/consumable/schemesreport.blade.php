@extends('layouts.header')
@section('content')

  <h3 class="text-danger mb-4">Schemes Detail Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-header bg-primary bg-gradient text-white fw-semibold">
      <i class="bi bi-funnel me-2"></i>Filter Options
    </div>
    <div class="card-body">
      <form method="post" action="" id="report" class="needs-validation" novalidate enctype="multipart/form-data">
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
          <button type="button" class="btn btn-primary bg-gradient px-4 report_search" id="report_search">
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

              <th class="freeze">Scheme Name</th>
              <th>Valid From</th>
              <th>Valid Till</th>
              <th>Scheme Type</th>
              <th>Active</th>
              <th>Level 1 Approve Status</th>
              <th>Level 2 Approve Status</th>
              <th>Emp ID</th>
              <th>Created By</th>
              <th>Level 1 Approver</th>
              <th>Level 2 Approver</th>
              <th>Product Name</th>
              <th>Free Product</th>
              <th>JBased On</th>
              <th>Based Type</th>
              <th>Minimum Qty</th>
              <th>Free Qty</th>
              <th>Remarks</th>

            </tr>
            <tr class="table-danger">

              <th class="freeze"><input type="text" class="column-search" placeholder="Search" /><span
                  style="display:none;">Scheme Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Valid
                  From</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Valid
                  Till</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Scheme
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display:none;">Active</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Level 1
                  Approve Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Level 2
                  Approve Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Emp
                  ID</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Created
                  By</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Level 1
                  Approver</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Level 2
                  Approver</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Free
                  Product</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">JBased
                  On</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Based
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Minimum
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display:none;">Free
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display:none;">Remarks</span></th>

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

    $(document).ready(function () {

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getschemesreportdata') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();

          }
        },
        columns: [
          { class: 'freeze', data: 'scheme_name', name: 'scheme_name' },
          { data: 'valid_from', name: 'valid_from' },
          { data: 'valid_till', name: 'valid_till' },
          { data: 'scheme_type', name: 'scheme_type' },
          { data: 'status', name: 'status' },
          { data: 'savestatus', name: 'savestatus' },
          { data: 'approvestatus', name: 'approvestatus' },
          { data: 'emp_id', name: 'emp_id' },
          { data: 'creater_name', name: 'creater_name' },
          { data: 'level1_approver_name', name: 'level1_approver_name' },
          { data: 'level2_approver_name', name: 'level2_approver_name' },
          { data: 'primary_product', name: 'primary_product' },
          { data: 'free_product', name: 'free_product' },
          { data: 'base', name: 'base' },
          { data: 'type', name: 'type' },
          { data: 'min_qty', name: 'min_qty' },
          { data: 'free_qty', name: 'free_qty' },
          { data: 'remarks', name: 'remarks' }

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


      // Column search
      $('#ReportTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

  </script>
@endpush