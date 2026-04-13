@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Machine Log View</h3>
    @include('layouts.breadcrumb')


   <div class="row">
    <form>
        <div class="col-md-12">
            <div class="invoice-box" id="section-to-print">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr class="information">
                            <td colspan="6">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p><b>GIN Number:</b> {!! $ginvdata[0]->gin_number !!}</p>
                                                <br>
                                                <p><b>DC Number:</b> {!! $ginvdata[0]->dc_number !!}</p>
                                                <br>
                                                <p><b>GIN Status:</b> {!! $ginvdata[0]->gin_status !!}</p>
                                                <br>
                                            </td>

                                            <td>
                                                <p><b>GIN Description:</b> {!! $ginvdata[0]->gin_description !!}</p>
                                                <br>
                                                <p><b>DC Date:</b> {!! $ginvdata[0]->dc_date !!}</p>
                                                <br>
                                                <p><b>Created By:</b> {!! $created_by !!}</p>
                                                <br>
                                            </td>

                                            <td class="text-right">
                                                <p><b>Supplier Name:</b> {!! $supplier_name !!}</p>
                                                <br>
                                                <p><b>Total Product Packs:</b> {!! $ginvdata[0]->total_packs !!}</p>
                                                <br>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>



@endsection