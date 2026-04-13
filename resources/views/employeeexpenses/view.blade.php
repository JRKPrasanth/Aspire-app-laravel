@extends('layouts.header')
@section('content')
<h3 class="text-danger">Employee Expenses Details</h3>
@include('layouts.breadcrumb')

			
<form>
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0"><i class="bi bi-receipt"></i> Employee Expenses </h4>
      <a href="../empexpenses" class="btn btn-danger btn-sm rounded-circle" title="Close">
        <i class="bi bi-x-lg"></i>
      </a>
    </div>

        <div class="card-body card-block normalform">
            <div class="row">
                <div class="col-md-12">
                    <div class="invoice-box" id="section-to-print" style="max-width: 100% !important;">

                        <table class="table table-borderless mb-4">
                            <tbody>
                                <tr>
                                    <td style="width: 50%;">
                                        <p><b>Expense No:</b> {!! $expense_no !!}</p>
                                        <p><b>Expense Date:</b> {!! $expense_date !!}</p>
                                        <p><b>Expense Amount:</b> {!! $expense_amount !!}</p>
                                    </td>
                                    <td>
                                        <p><b>Created By:</b> {!! $username !!}</p>
                                        <p><b>Remarks:</b> {!! $remarks !!}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div id="preview-area" class="chandru" style="max-height:400px; overflow-y:auto;">
                            <table class="table table-hover table-bordered overflow-y preview" >
                                <thead class="table-primary">
                                    <tr class="topfreeze">
                                        <th >Employee Name</th>
                                        <th>Bill No</th>
                                        <th>Bill Date</th>
                                        <th>Expense Account</th>
                                        <th>Expense Amount</th>
                                        <th>TDS Applicable</th>
                                        <th class="tds_hide">TDS Percentage</th>
                                        <th class="tds_hide">TDS Amount</th>
                                        <th class="tds_hide">Total Expense</th>
                                        <th class="tds_hide">TDS Account</th>
                                        <th>Files</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($vlinesdata as $value)
                                        <tr>
                                            <td></td>
                                            <td>{{ $value->employee_number.'-'.$value->first_name }}</td>
                                            <td>{{ $value->bill_no }}</td>
                                            <td>{{ $value->bill_date }}</td>
                                            <td>{{ $value->expense_account }}</td>
                                            <td>{{ $value->expense_line_amount }}</td>
                                            <td>{{ $value->tds_applicable }}</td>
                                            <td>{{ $value->tds_percentage }}</td>
                                            <td>{{ $value->tds_amount }}</td>
                                            <td>{{ $value->emp_exp_total }}</td>
                                            <td>{{ $value->tds_account }}</td>
                                            <td>
                                                @php $files = json_decode($value->choosefile); @endphp
                                                @if(!empty($files))
                                                    @foreach($files as $file)
                                                        <p>
                                                            <a download href="{{ URL::to('Uploads/empexpense/'.$value->expense_line_id.'/'.$file) }}">
                                                                {{ $file }}
                                                                <img src="{{ URL::to('images/download.png') }}" height="20" width="20">
                                                            </a>
                                                        </p>
                                                    @endforeach
                                                @else
                                                    <p>No Files</p>
                                                @endif
                                            </td>
                                            <td>{{ $value->remarks }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>




@endsection
                  