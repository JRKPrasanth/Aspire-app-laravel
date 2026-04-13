@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Supplier Details Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Supplier Name</th>
              <th>Supplier Number</th>
              <th>Supplier Type</th>
              <th>Payment Term Name</th>
              <th>Address</th>
              <th>City</th>
              <th>State</th>
              <th>Country</th>
              <th>Pincode</th>
              <th>Contact Person</th>
              <th>Contact Number</th>
              <th>Contact Mail</th>
              <th>PAN Number</th>
              <th>GST Number</th>
            </tr>
            <tr class="table-success">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Supplier Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Supplier
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Supplier
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Payment
                  Term Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Address</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">City</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">State</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Country</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Pincode</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Contact
                  Person</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Contact
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Contact
                  Mail</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">PAN
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">GST
                  Number</span></th>
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
          url: "{{ url('getsupplierdetails') }}",
          type: "GET",
        },
        columns: [

          { class: 'freeze', data: "supplier_name" },
          { data: "supplier_number" },
          { data: "suppliertype_name" },
          { data: "payment_term_name" },
          { data: "address" },
          { data: "city_name" },
          { data: "state_name" },
          { data: "country_name" },
          { data: "pincode" },
          { data: "contact_person" },
          { data: "contact_number" },
          { data: "contact_mail" },
          { data: "pan_number" },
          { data: "gst_number" }
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