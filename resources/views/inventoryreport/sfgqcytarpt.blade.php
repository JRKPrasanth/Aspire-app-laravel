@extends('layouts.header')
@section('content')

  <h3 class="text-danger"> SFG Stock QC YTA Report </h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg border-0 rounded-4 mb-4">

    <div class="card-body">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
          <div class="col-md-2"> <label for="start_date" class="form-label fw-semibold">As On Date</label></div>
          <div class="col-md-4">
            <input type="text" class="form-control start_date" id="start_date" name="start_date" required
              autocomplete="off">
          </div>
          <div class="col-md-4">
            <div class="col-md-6 text-md-start text-center">
              <button type="button" id="search" class="btn btn-primary text-white mt-3 mt-md-0">
                <i class="bi bi-search me-1"></i> Search
              </button>
            </div>
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
              <th class="freeze">Product Group</th>
              <th>Product Category</th>
              <th>Product Sub-Category</th>
              <th>Group Classification</th>
              <th>Product Name</th>
              <th>Job Number</th>
              <th>Job Status</th>
              <th>Batch No</th>
              <th>QOH</th>
              <th>Mfg. Date</th>
              <th>Exp. Date</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Product Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Category</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Sub-Category</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Group
                  Classification</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">QOH</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Mfg.
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Exp.
                  Date</span></th>




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
          url: "{{ url('getsfgqcytaqohrpt') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "group_name" },
          { data: "category_name" },
          { data: "subcategory_name" },
          { data: "group_classification" },
          { data: "concatenated_product" },
          { data: "job_no" },
          { data: "job_status" },
          { data: "batch_no" },
          { data: "production_qty" },
          { data: "manufacturer_date" },
          { data: "product_expire_date" }

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