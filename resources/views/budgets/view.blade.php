@extends('layouts.header')
@section('content')


			<form>

    <div class="card">
        <div class="card-header">
            <!--<h2>Budgets Review</h2>-->
            <span class="ui_close_btn"><a href="{{URL::to('budgetreview')}}" class="collapse-close pull-right btn-danger"></a></span>
        </div>
        <div class="card-body card-block">

            <div class="row">
                <div class="col-md-12">

                    <div class="invoice-box" id="section-to-print">

                        <table cellpadding="0" cellspacing="0">
                            <tbody>

                                <h2 class="heads1">Budget Details</h2>

                                <tr class="information">
                                    <td colspan="6">

                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p><b>Budget Name:</b> {!! $budget_name !!}</p>
                                                        <br>
                                                        <p><b>Budget Date :</b> {!! $budget_date !!}</p>
                                                        <br>
                                                         <p><b>Original Budget Name:</b> {!! $original_budget_id !!}</p>
                                                        <br>
                                                        <p><b>Current Budget Level:</b> {!! $current_budget_level !!}</p>
                                                        <br>
                                                        <p><b>Active:</b> {!! $active !!}</p>
                                                        <br>
                                                    </td>
                                                    <td class="text-right">
                                                        
                                                        <p><b>Budget Check Level:</b> {!! $budget_check_level !!}</p>
                                                        <br>
                                                        <p><b>Parent Budget Name:</b> {!! $parent_budget_id !!}</p>
                                                        <br>
                                                         <p><b>Budget Status:</b> {!! $budget_status !!}</p>
                                                        <br>
                                                        <p><b>Company Name:</b> {!! $company_name !!}</p>
                                                        <br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="ref">
                                                        <h4 class="head-style-1">Reference</h4></td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <p><b>Budget Year:</b> {!! $budget_year !!}</p><br>
                                                        <p><b>Budget From Period:</b> {!! $budget_from_period_id !!}</p><br>
                                                        <p><b>Budget To Period:</b> {!! $budget_to_period_id !!}</p><br>
                                                        <br>
                                                    </td>

                                                    <td class="text-right">
                                                        
                                                        <p><b>Budget From Date:</b> {!! $budget_from_date !!}</p><br>
                                                        <p><b>Budget To Date:</b> {!! $budget_to_date !!}</p><br>
                                                        <p><b>Budget Currency:</b> {!! $budget_currency_id !!}</p><br>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr class="heading">
                                    <table class="table table-bordered table-hover ">
                                        <thead>
                                            <tr>
                                                <th>Line No</th>
                                    <th>Company</th>
                                    <th>Location</th>
                                    <th>Department</th>
                                    <th>Account Code</th>
                                    <th>Budget Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          
                                                @foreach ($vlinesdata as $key=>$value)
                                    <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->company_name}}</td>
                                    <td>{{ $value->location_name}}</td>
                                    <td>{{ $value->department_name}}</td>
                                    <td>{{ $value->concatenated_segments}}</td>
                                    <td>{{ $value->budget_amount}}</td>
                                    </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</form>



@endsection
