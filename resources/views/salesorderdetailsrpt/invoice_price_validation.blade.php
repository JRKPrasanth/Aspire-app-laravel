@extends('layouts.header')
@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">

<style>

table.dataTable thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 2;
}

.mismatch{
    background:#ffe6e6;
}

</style>


<div class="card shadow-lg">

<div class="card-body">

<h4 class="text-danger">Invoice Price Validation Report</h4>

<div style="overflow-x:auto">

<table id="invoicePriceReport" class="table table-bordered table-striped nowrap" width="100%">

<thead>
<tr>
    <th>Invoice No</th>
    <th>Customer</th>
    <th>Address</th>
    <th>GST No</th>
    <th>Invoice Date</th>
    <th>Type</th>
    <th>Order No</th>
    <th>Product</th>
    <th>Sale Order Qty</th>
    <th>Batch</th>
    <th>Mfg Date</th>
    <th>Exp Date</th>
    <th>Qty</th>
    <th>Free Qty</th>
    <th>Disc Amount</th>
    <th>Line Tax</th>
    <th>Line Total</th>
    <th>Prod Total Tax</th>
    <th>Invoice Total</th>
    <th>Invoice Price</th>
    <th>Invoice MRP</th>
    <th>Price List Price</th>
    <th>Price List MRP</th>
    <th>Price Batch No</th>
    <th>Price Valid</th>
</tr>

<tr class="filters">

<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>
<th><input type="text" placeholder="Search"/></th>

</tr>
</thead>

<tbody>

@foreach($data as $row)

<tr @if($row->price_valid=='PRICE MISMATCH') style="background:#ffe6e6" @endif>

<td>{{ $row->invoice_number }}</td>
<td>{{ $row->customer_site_name }}</td>
<td>{{ $row->address }}</td>
<td>{{ $row->gst_no }}</td>
<td>{{ $row->invoice_date }}</td>
<td>{{ $row->invoice_type }}</td>
<td>{{ $row->sales_order_no }}</td>
<td>{{ $row->concatenated_product }}</td>
<td>{{ $row->salesorder_qty }}</td>
<td>{{ $row->batch_number }}</td>
<td>{{ $row->mfg_dt }}</td>
<td>{{ $row->exp_dt }}</td>
<td>{{ $row->qty }}</td>
<td>{{ $row->free_qty }}</td>
<td>{{ $row->discount_amount }}</td>
<td>{{ $row->tax_amount }}</td>
<td>{{ $row->line_total }}</td>
<td>{{ $row->hdr_tax }}</td>
<td>{{ $row->total }}</td>
<td>{{ number_format($row->unit_price,2) }}</td>
<td>{{ number_format($row->mrp,2) }}</td>
<td>{{ number_format($row->prc_unt_pri,2) }}</td>
<td>{{ number_format($row->prc_mrp,2) }}</td>
<td>{{ $row->prc_batch }}</td>
<td>{{ $row->price_valid }}</td>

</tr>

@endforeach

</tbody>

</table>

</div>
</div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>

<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>

$(document).ready(function() {

var table = $('#invoicePriceReport').DataTable({

dom:'Bfrtip',

buttons:[
'excelHtml5',
'csvHtml5',
'print',
'colvis'
],

scrollX:true,
scrollY:"500px",

pageLength:50,

fixedHeader:true,

fixedColumns:{
leftColumns:2
}

});


$('#invoicePriceReport thead tr.filters th').each(function(i){

$('input',this).on('keyup change',function(){

if(table.column(i).search()!==this.value){

table.column(i).search(this.value).draw();

}

});

});


});

</script>

@endsection