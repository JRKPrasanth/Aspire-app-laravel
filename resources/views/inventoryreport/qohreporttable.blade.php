@extends('layouts.header')
@section('content')

  <h3 class="text-danger">
    <?php  if ($pageTitle == 1) {?>
    RM Product Stock Report
    <?php } else if ($pageTitle == 2) {?>
    Verdura RM Product Stock Report
    <?php  } else if ($pageTitle == 3) {?>
    FG Product Stock Report
    <?php  } else if ($pageTitle == 4) {?>
    Sample Product Stock Report
    <?php  } else if ($pageTitle == 5) {?>
    WIP Product Stock Report
    <?php  } else if ($pageTitle == 6) {?>
    SFG Product Stock Report
    <?php  } else if ($pageTitle == 7) {?>
    PG Product Stock Report
    <?php  } else if ($pageTitle == 8) {?>
    WIP PG Product Stock Report
    <?php  } else if ($pageTitle == 9) {?>
    Control Samples Product Stock Report
    <?php  } else if ($pageTitle == 10) {?>
    PM Product Stock Report
    <?php  } else if ($pageTitle == 11) {?>
    Verdura PM Product Stock Report
    <?php  } else if ($pageTitle == 12) {?>
    CONSUMABLES Product Stock Report
    <?php  } else if ($pageTitle == 13) {?>
    PROMOTIONAL ITEMS Product Stock Report
    <?php  } else if ($pageTitle == 14) {?>
    ACCESSORIES Product Stock Report
    <?php  } else if ($pageTitle == 15) {?>
    LAB-RM Product Stock Report
    <?php  } else if ($pageTitle == 16) {?>
    WIP PM Product Stock Report
    <?php  } else if ($pageTitle == 17) {?>
    LAB-PM Product Stock Report
    <?php  } ?>
  </h3>
  @include('layouts.breadcrumb')



  <div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-body">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
          <div class="col-md-2"></div>
          <div class="col-md-4 me-4">
            <label for="start_date" class="form-label fw-semibold">Stock as on Date</label>
            <input type="text" class="form-control start_date" id="start_date" name="start_date" required
              autocomplete="off">
            <div class="invalid-feedback">Please select a start date.</div>
          </div>
          <div class="col-md-4">
          <button type="button" class="btn bg-primary bg-gradient text-white px-4 report_search mt-4" id="report_search">
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
              <th class="freeze">Product Group</th>
              <th>Product Classification</th>
              <th>Category</th>
              <th>Sub Category</th>
              <th>Product Name</th>
              <th>Description</th>
              <th>Batch No</th>
              <th>Mfg Date</th>
              <th>Exp Date</th>
              <th>Locator Name</th>
              <th>Locator Code</th>
              <th>QOH</th>
              <th>Cost</th>
              <th>Value</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Product Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Classification</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Category</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sub
                  Category</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Description</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Mfg
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Exp
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Locator
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Locator
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Qoh</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cost</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Value</span>
              </th>
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

      var group = "{{$pageMethod}}";
      var loc = "{{$locator}}";
      var ctgy = "{{$subcategory}}";
      var lcod = "{{$loccode}}";
      var wipenable = "{{$wipenable}}";

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getrmqohreport') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
           // d.end_date = $('#end_date').val();
            d.group = group,
              d.locator = loc,
              d.subcategory = ctgy,
              d.loccode = lcod,
              d.wipenable = wipenable

          }
        },
        columns: [
          { class: 'freeze', data: "product_group_id" },
          { data: "product_classification" },
          { data: "product_category_id" },
          { data: "subcategory_name" },
          { data: "concatenated_product" },
          { data: "product_alternate_name" },
          { data: "batch_number" },
          { data: "manufacturer_date" },
          { data: "product_expire_date" },
          { data: "locator_name" },
          { data: "locator_code" },
          { data: "qoh" },
          { data: "cost" },
          { data: "total_cost" }




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