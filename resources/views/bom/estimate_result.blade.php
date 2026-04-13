<style>
.table-estimate {
    border-collapse: collapse;
    width: 100%;
}

.table-estimate th,
.table-estimate td {
    border: 1px solid #000;
    padding: 6px;
}

.table-estimate th {
    background: #f2f2f2;
    text-align: center;
}

.text-right {
    text-align: right;
}
</style>


<div class="card mt-3">

<div class="card-header bg-primary text-white">
Production Cost Estimate
</div>

<div class="card-body">

<div class="row mb-3">

<div class="col-md-6">
<b>Product :</b> {{ $data[0]->assembly_product ?? '' }}
</div>

<div class="col-md-3">
<b>Production Qty :</b> {{ $qty }}
</div>

<div class="col-md-3 text-left">
<b>Total Cost :</b> ₹ {{ number_format(collect($data)->sum('component_cost'),2) }}
</div>

<div class="col-md-3 text-left">
<b>Per Qty Cost :</b> ₹ {{ number_format(collect($data)->sum('component_cost') / $qty),2 }}
</div>

</div>


<table class="table-estimate">

<thead>
<tr>
<th width="5%">#</th>
<th width="40%">Component</th>
<th width="15%">BOM Qty</th>
<th width="15%">Required Qty</th>
<th width="10%">Unit Cost</th>
<th width="15%">Component Cost</th>
</tr>
</thead>

<tbody>

@php
$total = 0;
@endphp

@foreach($data as $key => $row)

<tr>

<td align="center">{{ $key+1 }}</td>

<td>
{{ $row->component_product }}
</td>

<td class="text-right">
{{ number_format($row->bom_qty,4) }}
</td>

<td class="text-right">
{{ number_format($row->required_qty,4) }}
</td>

<td class="text-right">
{{ number_format($row->unit_cost,2) }}
</td>

<td class="text-right">
{{ number_format($row->component_cost,2) }}
</td>

</tr>

@php
$total += $row->component_cost;
@endphp

@endforeach

</tbody>

<tfoot>

<tr>

<th colspan="5" class="text-right">
Total Estimated Cost
</th>

<th class="text-right">
₹ {{ number_format($total,2) }}
</th>

</tr>

</tfoot>

</table>

</div>

</div>