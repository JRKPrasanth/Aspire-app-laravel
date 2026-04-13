@extends('layouts.header')
@section('content')
  <h3 class="text-danger">

    <?php  if ($pageModule == "traveljournal") { ?>
    Travel Claim Journal Entry
    <?php  } else if ($pageModule == "paymenttravelclaim") { ?>
    Payment For Travel Claim
    <?php  } else { ?>
    Approve Claim
    <?php  } ?>
  </h3>
  @include('layouts.breadcrumb')

  <?php  if ($pageModule == "paymenttravelclaim") { ?>
  <button class="btn btn-primary px-4 directpayment">Direct Payment</button>
  <?php  } ?>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="PosTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th class="freeze">Employee Name</th>
              <th>Claim Title</th>
              <th>Claim Date</th>
              <th>Description</th>
              <th>Travel Purpose</th>
              <th>Approve by</th>
              <th>Claim Status</th>
            </tr>
            <tr class="table-danger">
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;"></span></th>
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Employee Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Claim
                  Title</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Claim
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Description</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Travel
                  Purpose</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Approve
                  by</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Claim
                  Status</span></th>

            </tr>
          </thead>

          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>


@endsection
@push('scripts')


  <script>

    $(document).ready(function () {

      <?php   if ($pageModule == "traveljournal") { ?>
      var status = "1";
      <?php  } else if ($pageModule == "paymenttravelclaim") {  ?>

      var status = "2";
      <?php  } else { ?>
      var status = "0";
      <?php  } ?>

      var url = "{{ URL::to('travelapproveclaim') }}?status=" + status;

      var table = $('#PosTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: url,
        columns: [


          {
            data: 'travel_claim_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-success edit-btn"
              data-id="${row.travel_claim_id}">
             Approve
            </button>`;
              }

              <?php   if ($pageModule == "traveljournal") { ?>

              buttons += `
            <button type="button" class="btn btn-sm btn-success journalentry me-1" value="1"
              data-id="${row.travel_claim_id}"
                  data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                title="Journal Entry">
             <i class="bi bi-plus"></i>
            </button>`;

              buttons += `
            <button type="button" class="btn btn-sm btn-primary journalentry" value="2"
              data-id="${row.travel_claim_id}"
                  data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                title="Expense Entry">
             <i class="bi bi-plus"></i>
            </button>`;

              <?php   }  ?>

              <?php  if ($pageModule == "paymenttravelclaim") { ?>
              buttons += `
            <button type="button" class="btn btn-sm btn-success payment"
              data-id="${row.travel_claim_id}"
                  data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                title="Payment">
             <i class="bi bi-cash"></i>
            </button>`;
              <?php  } ?>

              return buttons;
            }
          },

          { class: 'freeze', data: "employee_name" },
          { data: "claim_title" },
          { data: "travel_date" },
          { data: "description" },
          { data: "travel_purpose" },
          { data: "reporting_name" },
          { data: "approved_status" },

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


    // approve
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('approvetravelclaim') }}/" + id;
      window.location.href = url;
    });

    // journal entry

    $(document).on('click', '.journalentry', function () {


      const id = $(this).data('id');


      var status = $(this).val();
      if (status == 1) {
        var url = "{{URL::to('traveljournalcreate')}}/" + id;
      } else {
        var url = "{{URL::to('travelexpensecreate')}}/" + id;
      }
      window.location.href = url;

    });



    $(document).on('click', '.payment', function () {

      const id = $(this).data('id');

      var url = "{{URL::to('travelpaymentcreate')}}?claim_id=" + id;

      window.location.href = url;

    });

    $(".directpayment").click(function () {
      var travel_claim_id = "0";
      var url = "{{ url('directtravelclaimpayment') }}?claim_id=" + travel_claim_id;
      window.location.replace(url);
    });


  </script>

@endpush