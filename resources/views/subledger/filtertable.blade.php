@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> Sub Ledger </h3>
  @include('layouts.breadcrumb')
  <button class="btn btn-primary px-4" id="ledgerposting"> Ledger Posting </button>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form method="post" action="" id="job_card_reprot" class="org_form" data-parsley-validate
        enctype="multipart/form-data">
        @csrf

        <div class="row mb-3">
          <div class="col-md-4">
            <div class="row mb-3 align-items-center">
              <label for="start_date" class="col-sm-4 col-form-label">From Date</label>
              <div class="col-sm-8">
                <input type="text" class="form-control start_date1" id="start_date" name="start_date" required
                  autocomplete="off" required>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="row mb-3 align-items-center">
              <label for="end_date" class="col-sm-4 col-form-label">To Date</label>
              <div class="col-sm-8">
                <input type="text" class="form-control end_date1" id="end_date" name="end_date" required autocomplete="off"
                  required>
              </div>
            </div>
          </div>
       <div class="col-md-4">
            <button type="button" class="btn btn-primary report_search px-4" id="report_search" value="SAVE">Search</button>
        </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th><input type="checkbox" id="select-all" /></th>
            <th>Journal Name</th>
            <th>Journal Date</th>
            <th>Account Name</th>
            <th>Debit Amount</th>
            <th>Credit Amount</th>
            <th>Actions</th>
          </tr>
          <tr class="table-danger">
            <th></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>


@endsection
@push('scripts')

  <script>

    $(document).ready(function () {
      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ url('getsubledgerfilterData') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          {
            data: 'f_journal_entry_line_id',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data) {
              return `<input type="checkbox" class="row-checkbox" value="${data}">`;
            }
          },
          { data: 'journal_name', name: 'journal_name' },
          { data: 'journal_date', name: 'journal_date' },
          { data: 'account_id', name: 'account_id' },
          { data: 'debit_amount', name: 'debit_amount' },
          { data: 'credit_amount', name: 'credit_amount' },
          {
            data: 'f_journal_entry_line_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                <button class="btn btn-sm btn-primary view-btn" data-id="${row.f_journal_entry_line_id}">
                  View
                </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Trigger search
      $('.report_search').on('click', function () {
        $('#AccTbl').DataTable().ajax.reload();
      });

      // Column-specific search
      $('#AccTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });

      // Select All checkbox
      $('#select-all').on('click', function () {
        var rows = table.rows({ search: 'applied' }).nodes();
        $('input.row-checkbox', rows).prop('checked', this.checked);
      });

      // Uncheck "select all" if any checkbox is unchecked
      $('#AccTbl tbody').on('change', 'input.row-checkbox', function () {
        if (!this.checked) {
          var el = $('#select-all').get(0);
          if (el && el.checked && ('indeterminate' in el)) {
            el.indeterminate = true;
          }
        }
      });

      // Ledger posting button click
      $("#ledgerposting").click(function () {
        var selectedIds = [];
        $('#AccTbl tbody input.row-checkbox:checked').each(function () {
          selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
          showCustomAlert("Please select at least one row", 'info');
          return;
        }

        var url = "{{ URL::to('getledgerpost') }}/" + selectedIds.join(',');
        $.get(url, function (data) {
          showCustomAlert(data.message, data.status);
          setTimeout(function () {
            location.reload();
          }, 1500);
        });
      });

      //view function
      $(document).on('click', '.view-btn', function () {
        const id = $(this).data('id');
        const url = "{{ url('subledgerview') }}/" + id;
        window.location.href = url;
      });

    });

  </script>

@endpush