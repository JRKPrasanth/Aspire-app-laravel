@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Contra Entry</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body">
      <form id="Accform" action="">
        <?php $data = \Session::get('data');
  if (isset($data[$pageMethod]['save'])) { ?>
        {{ csrf_field() }}

        <div class="row g-4">

          <!-- Left Column -->
          <div class="col-md-4">
            <div class="mb-3 none">
              <label class="form-label fw-semibold">Contra Entry Number</label>
              <input type="hidden" id="contraentry_id" name="contraentry_id">
              <input type="text" id="contraentry_no" name="contraentry_no" class="form-control bg-light contraentry_no">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-danger">* Date</label>
              <input type="text" id="contra_date" name="contra_date" class="form-control datepicker contra_date"
                value="{{$contra_date}}" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-danger">* Payment Type</label>
              <select name="payment_type_id" class="form-select select2 payment_type_id" required>
                <option value="">--Please Select--</option>
                <option value="CHEQUE">CHEQUE</option>
                <option value="NEFT">NEFT</option>
                <option value="CASH">CASH</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Reference Number</label>
              <input type="text" id="reference_no" name="reference_no" class="form-control reference_no" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Remarks</label>
              <input type="text" id="remarks" name="remarks" class="form-control remarks">
            </div>
          </div>

          <!-- Middle Column -->
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label fw-semibold text-danger">* From Bank Name</label>
              <select name="from_bank_id" id="from_bank_id" class="form-select select2 from_bank_id" required>
                {!! $from_bank_id !!}
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">From Account Number</label>
              <select name="from_account_no" id="from_account_no" class="form-select select2 from_account_no"></select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-danger">* From Account</label>
              <select name="from_account_id" id="from_account_id" class="form-select select2 from_account_id" required>
                {!! $from_account_id !!}
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-danger">* Amount</label>
              <input type="text" id="amount" name="amount" class="form-control amount" required>
            </div>

            <div class="mb-3 cheqno">
              <label class="form-label fw-semibold text-danger">* Cheque No</label>
              <input type="text" id="cheque_no" name="cheque_no" class="form-control cheque_no">
            </div>

            <div class="mb-3 cheqno">
              <label class="form-label fw-semibold text-danger">* Favouring Name</label>
              <input type="text" id="favouring_name" name="favouring_name" class="form-control favouring_name">
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label fw-semibold text-danger">* To Bank Name</label>
              <select name="to_bank_id" id="to_bank_id" class="form-select select2 to_bank_id" required>
                {!! $to_bank_id !!}
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">To Account Number</label>
              <select name="to_account_no" id="to_account_no" class="form-select select2 to_account_no"></select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-danger">* To Account</label>
              <select name="to_account_id" id="to_account_id" class="form-select select2 to_account_id" required>
                {!! $to_account_id !!}
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Created By</label>
              <select name="created_by" id="created_by" class="form-select select2 created_by"
                style="pointer-events:none;" readonly>
                {!! $created_by !!}
              </select>
            </div>

            <div class="mb-3 cheqno">
              <label class="form-label fw-semibold">Cheque Date</label>
              <input type="text" name="cheque_date" id="cheque_date" class="form-control datepicker cheque_date">
            </div>
          </div>

        </div>

        <div class="text-center mt-4">
          <button type="button" class="btn btn-success px-4 me-2 saveform">
            Save
          </button>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>



  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped">
        <thead>
          <tr class="table-warning">

            <th>Contra Entry Number</th>
            <th>Date</th>
            <th>Payment Type</th>
            <th>Cheque No</th>
            <th>Cheque Date</th>
            <th>Favouring Name</th>
            <th>Reference Number</th>
            <th>From Account</th>
            <th>To Account</th>
            <th>Amount</th>
            <th>Remarks</th>
            <th>Created By</th>

          </tr>
          <tr class="table-danger">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>

          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>



@endsection
@push('scripts')

  <script>

    // data table funcrion	
    $(document).ready(function () {
      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        order: [[2, 'desc']],
        ajax: "{{ route('contraentrydata') }}",
        columns: [

          { data: 'contraentry_no', name: 'contraentry_no' },
          { data: 'contra_date', name: 'contra_date' },
          { data: 'payment_type_id', name: 'payment_type_id' },
          { data: 'cheque_no', name: 'cheque_no' },
          { data: 'cheque_date', name: 'cheque_date' },
          { data: 'favouring_name', name: 'favouring_name' },
          { data: 'reference_no', name: 'reference_no' },
          { data: 'fromaccount', name: 'fromaccount' },
          { data: 'toaccount', name: 'toaccount' },
          { data: 'amount', name: 'amount' },
          { data: 'remarks', name: 'remarks' },
          { data: 'first_name', name: 'first_name' }


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

      // Individual column search
      $('#AccTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });



    // save form

    $(document).on('click', '.saveform', function () {

      const form = $("#Accform");
      let dup_chk = true;
      form.parsley().validate();

      if (form.parsley().isValid() && dup_chk === true) {

        const data = form.serialize();

        var url = "{{ URL::to('contraentrysave') }}";
        $.post(url, data, function (data1) {
          var msg = data1.message;
          showCustomAlert(msg, 'success');
          window.location.reload(true);

        });

      } else {
        showCustomAlert("Please fill out all required fields correctly.", 'info');
      }

    });


    // on change functons	

    $(document).ready(function () {

      // --- Payment Type Logic ---
      $('.payment_type_id').on('change', function () {
        var pmttype = $.trim($('.payment_type_id option:selected').text());

        if (pmttype === "CHEQUE") {
          $(".cheque_no, .favouring_name, .cheque_date").attr('required', true);
          $(".reference_no").attr('required', false);
          $('.cheqno').show();
        } else {
          $(".cheque_no, .favouring_name, .cheque_date, .reference_no").attr('required', false);
          $('.cheqno').hide();
        }
      });

      // --- From Bank Change ---
      $('#from_bank_id').on('change', function () {
        var frmbank = $('#from_bank_id').val();
        var tobank = $('#to_bank_id').val();

        if (frmbank === tobank) {
          showCustomAlert("From and To bank should not have the same value", "info");
          $(".from_account_no").val('').trigger('change.select2');
          $('#from_bank_id').val('').trigger('change.select2');
          return;
        }

        if (frmbank && frmbank !== '9999') {
          var url = "{{ URL::to('jcomboform1') }}?table=f_bank_account_lines_t:bank_account_line_id:account_number"
            + "&order_by=account_number asc"
            + "&parent=and bank_account_hdr_id=" + frmbank;

          $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
              if (typeof data === "string") {
                try { data = JSON.parse(data); } catch (e) { console.error("Invalid JSON:", data); return; }
              }

              $(".from_account_no").html('<option value="">-- Select Account No --</option>');
              $.each(data, function (i, item) {
                $(".from_account_no").append(`<option value="${item.val}">${item.option_name}</option>`);
              });
              $(".from_account_no").trigger('change.select2');
            },
            error: function (xhr, status, error) {
              console.error("AJAX Error (from_bank_id):", error);
            }
          });
        }
      });

      // --- To Bank Change ---
      $('#to_bank_id').on('change', function () {
        var frmbank = $('#from_bank_id').val();
        var tobank = $('#to_bank_id').val();

        if (frmbank === tobank) {
          showCustomAlert("From and To bank should not have the same value", "info");
          $(".to_account_no").val('').trigger('change.select2');
          $('#to_bank_id').val('').trigger('change.select2');
          return;
        }

        if (tobank && tobank !== '9999') {
          var url = "{{ URL::to('jcomboform1') }}?table=f_bank_account_lines_t:bank_account_line_id:account_number"
            + "&order_by=account_number asc"
            + "&parent=and bank_account_hdr_id=" + tobank;

          $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
              if (typeof data === "string") {
                try { data = JSON.parse(data); } catch (e) { console.error("Invalid JSON:", data); return; }
              }

              $(".to_account_no").html('<option value="">-- Select Account No --</option>');
              $.each(data, function (i, item) {
                $(".to_account_no").append(`<option value="${item.val}">${item.option_name}</option>`);
              });
              $(".to_account_no").trigger('change.select2');
            },
            error: function (xhr, status, error) {
              console.error("AJAX Error (to_bank_id):", error);
            }
          });
        }
      });

      // --- From Account Change ---
      $(document).on('change', '.from_account_no', function () {
        var bankId = $('#from_bank_id').val();

        if (bankId && bankId !== '9999') {
          var url = "{{ URL::to('getaccountdetails') }}/" + bankId;

          $.get(url, function (data) {
            if (data && data[0]) {
              $('.from_account_id').val(data[0].account_code_id).trigger('change');
            }
          }).fail(function () {
            console.error("Error fetching account details (from_account_no)");
          });
        } else {
          $('.from_account_id').val('64').trigger('change');
        }
      });

      // --- To Account Change ---
      $(document).on('change', '.to_account_no', function () {
        var bankId = $('#to_bank_id').val();

        if (bankId && bankId !== '9999') {
          var url = "{{ URL::to('getaccountdetails') }}/" + bankId;

          $.get(url, function (data) {
            if (data && data[0]) {
              $('.to_account_id').val(data[0].account_code_id).trigger('change');
            }
          }).fail(function () {
            console.error("Error fetching account details (to_account_no)");
          });
        } else {
          $('.to_account_id').val('64').trigger('change');
        }
      });
    });


  </script>

@endpush