@extends('layouts.header')
@section('content')

  <h3 class="text-danger mb-4"> GST HSN Wise Report </h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg border-0 rounded-4 mb-4">

    <div class="card-body">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
          <div class="col-md-2"></div>
          <div class="col-md-4">
            <label for="start_date" class="form-label fw-semibold">Month</label>
            <select id="month" class=" select2"></select>
          </div>
          <div class="col-md-4">
            <label for="end_date" class="form-label fw-semibold">Year</label>
            <select id="year" class=" select2"></select>
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

              <th class="freeze">HSN Code</th>
              <th>UOM</th>
              <th>Total Qty</th>
              <th>Total Taxable Value</th>
              <th>Tax Group(%)</th>
              <th>Month_year</th>
              <th>Integrated Tax</th>
              <th>Central Tax</th>
              <th>State Tax</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">HSN Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">UOM</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Total
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Total Taxable
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Group(%)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Month_year</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Integrated
                  Tax</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Central
                  Tax</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">State
                  Tax</span></th>
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

      var min = 2020,
        max = new Date().getFullYear(),
        select = document.getElementById('year');

      for (var i = max; i >= min; i--) {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        select.appendChild(opt);

      }
      // month jcombo and select current month
      var condition1 = '1=1';


      var url = "{{ URL::to('jcomboformlogin?table=month:id:description') }}&order_by=id";


      $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
          // Parse JSON string if needed
          if (typeof data === "string") {
            try {
              data = JSON.parse(data);
            } catch (e) {
              console.error("Invalid JSON response:", data);
              return;
            }
          }

          $('#month').html('<option value="">-- Select Month --</option>');

          $.each(data, function (i, item) {
            let selected = item.val == "{{ $row->month ?? '' }}" ? 'selected' : '';
            $('#month').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
          });

          $('#month').trigger('change.select2');
        }

      });
    });


    $(document).ready(function () {

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getgsthsnrpt') }}",
          type: "GET",
          data: function (d) {
            d.month = $('#month').val();
            d.year = $('#year').val();
          }
        },
        columns: [
          { class: 'freeze', data: "classification_code" },
          { data: "uom_code" },
          { data: "qty" },
          { data: "taxable_value" },
          { data: "tax_group_percent" },
          { data: "month_year" },
          { data: "igst" },
          { data: "cgst" },
          { data: "sgst" }
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