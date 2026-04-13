@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Advance Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="RptTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Employee Name</th>
              <th>Reporting Name</th>
              <th>Departments</th>
              <th>Date Of Join</th>
              <th>Advance Date</th>
              <th>Effective Date</th>
              <th>Advance Type</th>
              <th>Amount</th>
              <th>Paid Amount</th>
              <th>Remaining Amount</th>
              <th>Emi</th>
              <th>Approve Status</th>
              <th>Paid Status</th>


            </tr>
            <tr class="table-danger">

              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Employee Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reporting
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Departments</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date Of
                  Join</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Advance
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Effective
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Advance
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Amount</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Paid
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Remaining
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Emi</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Approve
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Paid
                  Status</span></th>


            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>


@endsection
@push('scripts')


  <script>

    $(document).ready(function () {

      var table = $('#RptTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        ajax: {
          url: "{{ url('approverptData') }}",
          type: "GET",
          data: function (d) {
            d.month = $('#month').val();
            d.year = $('#year').val();
          }
        },
        columns: [

          { class: 'freeze', data: "full_name" },
          { data: "report_name" },
          { data: "department" },
          { data: "date_of_joining" },
          { data: "advance_date" },
          { data: "effective_date" },
          { data: "adv_type" },
          { data: "amount" },
          { data: "paid_amount" },
          { data: "remaining_amount" },
          { data: "emi" },
          { data: "app_status" },
          { data: "paidnew_status" }

        ]

      });

      // Trigger search
      $('.report_search').on('click', function () {
        $('#RptTbl').DataTable().ajax.reload();
      });


      // Column search
      $('#RptTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });



  </script>

@endpush