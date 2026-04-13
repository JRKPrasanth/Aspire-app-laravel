@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Customer Details Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Customer Name</th>
              <th>Customer Number</th>
              <th>Customer Type</th>
              <th>Site Name</th>
              <th>Site Type</th>
              <th>Address</th>
              <th>City</th>
              <th>State</th>
              <th>Country</th>
              <th>Pincode</th>
              <th>Contact Number</th>
              <th>Contact Person Name</th>
              <th>Email</th>
              <th>GST Number</th>
              <th>PAN Number</th>
              <th>TCS Applicable</th>
              <th>Active</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Customer Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Site
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Site
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Address</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">City</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">State</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Country</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Pincode</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Contact
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Contact Person
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Email</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">GST
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PAN
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">TCS
                  Applicable</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Active</span>
              </th>
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

      $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getcustomerdetails') }}",
          type: "GET",
        },
        columns: [
          { class: 'freeze', data: "customer_name" },
          { data: "customer_number" },
          { data: "customer_type" },
          { data: "customer_site_name" },
          { data: "site_type" },
          { data: "address" },
          { data: "city_name" },
          { data: "state_name" },
          { data: "country_name" },
          { data: "pincode" },
          { data: "contact_number" },
          { data: "contact_person" },
          { data: "contact_mail" },
          { data: "gst_no" },
          { data: "pan_no" },
          { data: "tcs_applicable" },
          { data: "active" },


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

    });

  </script>
@endpush