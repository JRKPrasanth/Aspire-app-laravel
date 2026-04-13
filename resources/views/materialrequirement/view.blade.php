@extends('layouts.header')
@section('content')


<form>

    <div class="card">
        <div class="card-header">
            
            <span class="ui_close_btn">
                <a href="{{URL::to('materialrequirement')}}" class="collapse-close pull-right btn-danger"></a>
            </span>
        </div>
        <div class="card-body card-block">
            <div class="row">
                <div class="col-md-12">
                    <div class="invoice-box" id="section-to-print">
                        <table cellpadding="0" cellspacing="0">
                            <tbody>
                                <h2 class="heads1">Material Requirement Details</h2>
                                <tr class="information">
                                    <td colspan="6">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p><b>Batch No:</b> {!! $batch_no !!}</p><br>
                                                        <p><b>Remarks:</b> {!! $remarks !!}</p>
                                                        <br>
                                                        <p><b>Created By:</b> {!! $created_by !!}</p>
                                                        <br>
                                                    </td>
                                                    <td>
                                                        <p><b>Start Date:</b> {!! $start_date !!}</p><br>
                                                         <p><b>End Date:</b> {!! $end_date !!}</p>
                                                        <br>
                                                    </td>
                                                    <td>
                                                        <p><b>Employee Name:</b> {!! $emloyee_name !!}</p>
                                                        <br>
                                                        <p><b>Active:</b> {!! $active !!}</p>
                                                       
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
                                                <th>Product Name</th>
                                                <th>Uom Code</th>
                                                <th>Issue Qty</th>
                                                <th>Component Qoh</th>
                                                <th>Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php //dd($tablelines); ?>
                                                @foreach ($tablelines as $key=>$value)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $value->concatenated_product}}</td>
                                                    <td>{{ $value->uom_code}}</td>
                                                    <td>{{ $value->issue_qty}}</td>
                                                    <td>{{ $value->component_qoh}}</td>
                                                    <td>{{ $value->comments}}</td>
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
