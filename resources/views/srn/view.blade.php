@extends('layouts.header') @section('content')

<form>

    <div class="card">
        <div class="card-header">
            <!--<h2> Goods Receipt Note</h2>-->
            <span class="ui_close_btn"><a href="{{URL::to('grn')}}" class="collapse-close pull-right btn-danger"></a></span>
        </div>

        <div class="card-body card-block ">
            <div class="row">
                <div class="col-md-12">



<div class="invoice-box" id="section-to-print">

    <table cellpadding="0" cellspacing="0">
        <tbody>

            <h2 class="heads1">Goods Receipt Note Details</h2>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tbody>


                            <tr>
                                <td>
                                    <p><b>GRN Number:</b> {!! $row->grn_number !!}</p>
                                    <br>
                                    <p><b>GRN Description:</b> {!! $row->grn_description !!}</p><br>
                                   <p><b>DC Number:</b> {!! $row->dc_number !!}</p>
                                    <br>
                                    <p><b>DC Date:</b> {!! $row->dc_date !!}</p>
                                    <br>
                                    <p><b>GRN Status:</b> {!! $grn_status !!}</p><br>
                                    <p><b>Supplier Name:</b> {!! $supplier_name !!}</p>
                                    <br>
                                      <p><b>Subcontractor Name:</b> {!! $subcontract_supplier_name !!}</p>
                                    <br>
                                   
                                </td>
                                <td class="text-right">
                                    
                                    <p><b>Reference Number:</b> {!! $row->reference_number!!}</p>
                                    <br>
                                    <p><b>Source:</b> {!! $row->source!!}</p>
                                    <br>
                                    <p><b>PO No:</b> {!! $po_number_name!!}</p>
                                    <br>
                                    <?php if($row->source=='PO'){?>
                                    <p><b>PO Date:</b> {!! $row->po_date !!}</p>
                                    <br>
                                    <?php }?>
                                     <p><b>Total Product Packs:</b> {!! $row->total_packs !!}</p>
                                    <br>
                                    
                                    
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
                                <th>Description</th>
                                <th>Uom Code</th>
                                <th>Ordered Qty</th>
                                <th>Pending Qty</th>
                                <th>Box Qty</th>
                                <th>Total Product Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php //dd($vlinesdata); ?>
                                @foreach ($linedata as $key=>$value)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{$value->product_code}} - {{ $value->product_id}}</td>
                                    <td>{{ $value->packed_discription}}</td>
                                    <td>{{ $value->uom_code_id}}</td>
                                    <td>{{ $value->qty}}</td>
                                    <td>{{ $value->pending_qty}}</td>
                                    <td>{{ $value->box_qty}}</td>
                                    <td>{{ $value->receive_qty}}</td>

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