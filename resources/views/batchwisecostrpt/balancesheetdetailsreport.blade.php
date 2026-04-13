@extends('layouts.header')
@section('content')
<h2 class="text-danger">Balancesheet Details Report</h2>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
        <form method="post" action="" id="job_card_reprot" class="org_form" data-parsley-validate enctype="multipart/form-data">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="row mb-3 align-items-center">
                        <label for="start_date" class="col-sm-4 col-form-label">From Date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control start_date" id="start_date" name="start_date" required autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row mb-3 align-items-center">
                        <label for="end_date" class="col-sm-4 col-form-label">To Date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row mb-3 align-items-center">
                        <label for="account_line_id" class="col-sm-3 col-form-label">Account</label>
                        <div class="col-sm-9">
                            <select id="account_line_id" name="account_line_id" class="form-select select2 account_line_id" required tabindex="1">
                                {!! $account_line_id !!}
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row text-center">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary report_search px-4" id="report_search" value="SAVE"><i class="bi bi-search"></i> Search</button>
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
            <th class="freeze">Journal Name</th>
            <th>Journal Date</th>
            <th>Finance Year</th>
            <th>Reference Source</th>
            <th>Reference Name</th>
            <th>Account Class Name</th>
            <th>Account Structure</th>
            <th>Main Account Code</th>
            <th>Main Account Name</th>
            <th>Sub1 Account Code</th>
            <th>Sub1 Account Name</th>
            <th>Sub2 Account Code</th>
            <th>Sub2 Account Name</th>
            <th>Sub3 Account Code</th>
            <th>Sub3 Account Name</th>
            <th>Sub4 Account Code</th>
            <th>Sub4 Account Name</th>
            <th>Debit Amount</th>
            <th>Credit Amount</th>
            <th>Balancesheet Amount</th>
          </tr>
          <tr class="table-success">
            <th class="freeze"><input type="text" placeholder="Search" /><span style="display: none;">Journal Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Journal Date</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Finance Year</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Reference Source</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Reference Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Account Class Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Account Structure</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Main Account Code</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Main Account Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Sub1 Account Code</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Sub1 Account Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Sub2 Account Code</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Sub2 Account Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Sub3 Account Code</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Sub3 Account Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Sub4 Account Code</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Sub4 Account Name</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Debit Amount</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Credit Amount</span></th>
            <th><input type="text" placeholder="Search" /><span style="display: none;">Balancesheet Amount</span></th>
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
      ajax: {
        url: "{{ url('getbalancesheetrptData') }}",
        type: "GET",
        data: function(d) {
          d.start_date = $('#start_date').val();
          d.end_date = $('#end_date').val();
          d.account_code_line = $('#account_line_id').val();
        }
      },
      columns: [
        { class:'freeze', data: 'journal_name'},
        { data: 'journal_date'},
        { data: 'monyr'},
        { data: 'reference_source'},
        { data: 'reference_name'},
        { data: 'account_class_name'},
        { data: 'concatenated_segments'},
        { data: 'main_account_code'},
        { data: 'account_name'},
        { data: 'r2_account_code'},
        { data: 'r2_account_code_meaning'},
        { data: 'account_code'},
        { data: 'account_code_meaning'},
        { data: 'fr_account_code'},
        { data: 'fr_account_code_meaning'},
        { data: 'r4_account_code'},
        { data: 'r4_account_code_meaning'},
        { data: 'debit_amount'},
        { data: 'credit_amount'},
        { data: 'amount'}
      ],
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
