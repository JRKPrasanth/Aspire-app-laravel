@extends('layouts.header')
@section('content')
<h3 class="text-danger">Purchase Enquiry Details</h3>
@include('layouts.breadcrumb')


	<form>
    <div class="card">
        <div class="card-header">
            <!--<h2> Purchase Enquiry</h2>-->
            <span class="ui_close_btn"><a  class="collapse-close pull-right btn-danger closeurl"></a></span>
        </div>
        <div class="card-body card-block">

            <div class="row">
                <div class="col-md-12">

                    <div class="invoice-box" id="section-to-print">

                        <table cellpadding="0" cellspacing="0">
                            <tbody>

                                <h2 class="heads1"></h2>

                                <tr class="information">
                                    <td colspan="6">

                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td style="width:37%">
                                                        <p><b>Enquiry No:</b> {!! $enquiry_number !!}</p>
                                                        <br>
                                                           <p><b>Enquiry Date:</b> {!! $enquiry_date !!}</p>
                                                         <br>
                                                         <p><b>Source:</b> {!! $source !!}</p>
                                                        <br>
                                                    </td>
                                                    <td style="width:37%">
                                                        <p><b>Created By:</b> {!! $username !!}</p>
                                                        <br>
                                                         <p><b>Enquiry Status:</b> {!! $enquiry_status !!}</p>
                                                        <br>
                                                        <p><b>Enquiry Type:</b> {!! $enquiry_type_id !!}</p>
                                                        <br>
                                                        
                                                    </td>
                                                     <td style="width:37%">
                                                        <p><b>Supplier Name:</b> {!! $supplier_name !!}</p>
                                                        <br>
                                                         <p><b>Supplier Site Name:</b> {!! $supplier_site_name !!}</p>
                                                        <br>
                                                    </td>
                                                </tr>
                                                 
                                                <tr>
                                                    <td colspan="6" class="ref">
                                                        <h4 class="head-style-1">Additional Details</h4></td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <p><b>Project Name:</b> {!! $project_name !!}</p>
                                                        <br>
                                                         <p><b>Remarks:</b> {!! $remarks !!}</p>
                                                    </td>
						    <td>
                                                         <p><b>Other Info:</b> {!! $other_info !!}</p><br>
                                                         
                                                     </td>
                                                   <td>
                                                         
                                                         <p><b>Enquiry Type:</b> {!! $enquiry_type_id !!}</p>
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
                                                <th>Part Number</th>
                                                
                                                @if($enquiry_type_id != 'STANDARD')
                                                <th>Product Description</th>
                                                @endif
                                                <th>Uom Code</th>
                                                <th>Qty</th>
                                                <th>Promised Date</th>
                                                <th>Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php //dd($vlinesdata); ?>
                                                @foreach ($vlinesdata as $key=>$value)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $value->product_code.'-'.$value->concatenated_product}}</td>
                                                    <td>{{ $value->part_no}}</td>
                                                    @if($enquiry_type_id != 'STANDARD')
                                                    <td>{{ $value->product_description}}</td>
                                                    @endif
                                                    <td>{{ $value->uom_code}}</td>
                                                    <td>{{ $value->qty}}</td>
                                                   <td><?php   if($value->promised_date!="0000-00-00") echo date(\Session::get('p_date_format'), strtotime($value->promised_date));?></td>
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