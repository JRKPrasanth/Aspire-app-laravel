@extends('layouts.header')
@section('content')

<div class="container">

<h3 class="text-danger">BOM Cost Estimate</h3>

<form method="post">
@csrf

<div class="row mb-3">

<div class="col-md-4">
<label>Product</label>
<select name="product_id" class="form-control select2" required>

<option value="">Select Product</option>

@foreach($products as $p)
<option value="{{$p->product_id}}">
{{$p->concatenated_product}}
</option>
@endforeach

</select>
</div>

<div class="col-md-3">
<label>Production Qty</label>
<input type="number" name="production_qty"
class="form-control"
value="{{$qty}}">
</div>

<div class="col-md-2 mt-4">
<button class="btn btn-primary">
Estimate Cost
</button>
</div>

</div>

</form>

@if(count($data)>0)

<table class="table table-bordered table-striped" id="bomTable">

<thead class="table-dark">
<tr>
<th>#</th>
<th>Component</th>
<th class="text-end">Required Qty</th>
<th class="text-end">Unit Cost</th>
<th class="text-end">Component Cost</th>
<th class="text-end">PerPrdn Qty Cost</th>
<th class="text-end">Parent Cost</th>
</tr>
</thead>

<tbody>

@php $i=1; @endphp

@foreach($data as $index => $row)

@php
$nextLevel = isset($data[$index+1]) ? $data[$index+1]->level : 0;
$hasChild = $nextLevel > $row->level;
@endphp

<tr class="level-{{$row->level}}">

<td>{{$i++}}</td>

<td>

<span style="padding-left: {{($row->level-1)*25}}px">

@if($hasChild)
<span class="toggle-btn" style="cursor:pointer;font-weight:bold">▶</span>
@else
<span style="display:inline-block;width:15px"></span>
@endif

{{$row->concatenated_product}}

</span>

</td>

<td class="text-end">
{{number_format($row->required_qty,2)}}
</td>

<td class="text-end">
{{number_format($row->unit_cost,2)}}
</td>

<td class="text-end">
{{number_format($row->component_cost,2)}}
</td>

<td class="text-end">
{{ number_format($row->per_qty_cost ?? 0,2) }}
</td>

<td class="text-end">
{{ number_format($row->parent_cost ?? 0,2) }}
</td>

</tr>

@endforeach

<tr class="table-success">

<td colspan="4" class="text-end">
<strong>Total Estimated Cost</strong>
</td>

<td class="text-end">
<strong>₹ {{$qty}}</strong>
</td>

<td class="text-end">
<strong>₹ {{number_format($per_unit_cost,2)}}</strong>
</td>

<td class="text-end">
<strong>₹ {{number_format($total_cost,2)}}</strong>
</td>

</tr>

</tbody>

</table>

@endif

</div>

@endsection

@push('scripts')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$(document).ready(function(){

/* Hide all child rows */

$("#bomTable tbody tr").each(function(){

let level = parseInt($(this).attr("class").replace("level-",""));

if(level > 1){
$(this).hide();
}

});


/* Toggle rows */

$(".toggle-btn").click(function(){

let icon = $(this);
let row = icon.closest("tr");
let level = parseInt(row.attr("class").replace("level-",""));

let next = row.next();

while(next.length){

let nextLevel = parseInt(next.attr("class").replace("level-",""));

if(nextLevel <= level){
break;
}

next.toggle();

next = next.next();

}

icon.text(icon.text()=="▶" ? "▼" : "▶");

});

});

</script>

<script>
$(document).ready(function(){

    $('.select2').select2({
        placeholder: "Search Product",
        allowClear: true,
        width: '100%'
    });

});


</script>