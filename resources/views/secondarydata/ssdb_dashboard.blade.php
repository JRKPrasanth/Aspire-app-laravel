@extends('layouts.header')
@section('content')
<h2 class="text-danger">Secondary Sales Dashboard</h2>



	<div class="row">

<div class="col-md-6">
    <div class="card shadow-lg rounded-4 border-0 p-5">
        <div class="row g-3">
            <?php error_reporting(0); foreach($das_access as $val) { ?>
                <?php if($val == "FinishedGoods") { ?>
                    <!-- Card 1 -->
                    <div class="col-md-6">
                        <a href="product" class="text-decoration-none">
                            <div class="card border-start border-4 border-success shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-success mb-2">FINISHED GOODS</h6>
                                    <h5 class="fw-bold text-dark">{{$fg_product_count[0]->count}}</h5>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-md-6">
                        <a href="product" class="text-decoration-none">
                            <div class="card border-start border-4 border-danger shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-danger mb-2">FINISHED GOODS</h6>
                                    <h5 class="fw-bold text-dark">{{$fg_product_count[0]->count}}</h5>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-md-6">
                        <a href="product" class="text-decoration-none">
                            <div class="card border-start border-4 border-success shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-success mb-2">FINISHED GOODS</h6>
                                    <h5 class="fw-bold text-dark">{{$fg_product_count[0]->count}}</h5>
                                </div>
                            </div>
                        </a>
                    </div>

                <?php } elseif($val == "PromotionalItems") { ?>
                    <!-- Card 4 -->
                    <div class="col-md-6">
                        <a href="product" class="text-decoration-none">
                            <div class="card border-start border-4 border-info shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-info mb-2">PROMOTIONAL ITEMS</h6>
                                    <h5 class="fw-bold text-dark">{{$pi_product_count[0]->count}}</h5>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card 5 -->
                    <div class="col-md-6">
                        <a href="product" class="text-decoration-none">
                            <div class="card border-start border-4 border-primary shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-primary mb-2">PROMOTIONAL ITEMS</h6>
                                    <h5 class="fw-bold text-dark">{{$pi_product_count[0]->count}}</h5>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card 6 -->
                    <div class="col-md-6">
                        <a href="product" class="text-decoration-none">
                            <div class="card border-start border-4 border-danger shadow-sm h-100">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-danger mb-2">PROMOTIONAL ITEMS</h6>
                                    <h5 class="fw-bold text-dark">{{$pi_product_count[0]->count}}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>


    <div class="col-md-6">
    <div class="card shadow-lg rounded-4 border-0 p-4">

    @foreach($das_access as $val)
        @if($val == 'PurchaseOrderPiechart')
          <div class="dash_charrrt mb-4">
            <div id='po_chart4'></div>
          </div>
        @endif
      @endforeach

    </div>
    </div>

 </div>


	<div class="row">
    @foreach($das_access as $val)
      @if($val == 'PurchaseBySupplier')
        <div class="col-md-6 mb-4">
		<div class="card shadow-lg rounded-4 border-0 p-4">
          <div class="mb-3">
            <label class="form-label">Supplier</label>
            <select name='supplier_id' class='form-select select2'>
              {!! $supplier !!}
            </select>
          </div>
          <div class="dash_charrt">
            <div id='po_chart1'></div>
          </div>
        </div>
			 </div>
	
      @elseif($val == 'PurchaseByProducts')
        <div class="col-md-6 mb-4">
				<div class="card shadow-lg rounded-4 border-0 p-4">
          <div class="mb-3">
            <label class="form-label">Product</label>
            <select name='raw_product' class='form-select select2'>
              {!! $raw_product !!}
            </select>
          </div>
          <div class="dash_charrt">
            <div id='po_chart2'></div>
          </div>
        </div>
			  </div>
      @endif
    @endforeach
  </div>

  <div class="row">
    @foreach($das_access as $val)
      @if($val == 'GRNOverdue')
			<div class="card shadow-lg rounded-4 border-0 p-4">
            <h4 class="text-danger">GRN Overdue</h4>
           <table id="Table1" class="table table-bordered table-striped table-hover w-100">
              <thead>
                <tr class="table-primary">
                  <th>PO Number</th>
                  <th>Supplier</th>
                  <th>Product</th>
                  <th>Qty</th>
                  <th>Promise date</th>
                  <th>Overdue</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pending_qty as $value)
                  <tr>
                    <td>{{ $value->po_number }}</td>
                    <td>{{ $value->supplier_name }}</td>
                    <td>{{ $value->product_name }}</td>
                    <td>{{ round($value->pending_qty, 2) }}</td>
                    <td>{{ $value->promised_date }}</td>
                    <td>{{ $value->due }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endif
    @endforeach






@endsection
@push('scripts')

<script>
	   $(document).ready(function() {
        $('#Table1').DataTable({
            dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
            order: [],
            scrollCollapse: true,
            buttons: [
                {
                    extend: 'excelHtml5',
                    filename: 'GRN Overdue Report',
                },
                {
                    extend: 'pdfHtml5',
                    filename: 'GRN Overdue Report',
                }
            ]
        });
	 });
$(document).ready(function() {
    
    var column="{{$column}}";
    
 $(".fg_product").change(function(){
    
  var id=$(this).val();
  
  if(id!='')
  {
      $.get("{{URL::to('dashboard_secondarydata')}}?type=fg_product&product_id="+id,function(data){
          
           $("#so_chart2").highcharts().update({
                      series:{
                        type: 'column',
                        colorByPoint: true,
                        data:data,
                        showInLegend: false
                      }
                     });
      });
  }
    
});  
   
 
  $(".raw_product").change(function(){
    
  var id=$(this).val();
  
  if(id!='')
  {
      $.get("{{URL::to('dashboard_secondarydata')}}?type=raw_product&product_id="+id,function(data){
          
           $("#po_chart2").highcharts().update({
                      series:{
                        type: 'column',
                        colorByPoint: true,
                        data:data,
                        showInLegend: false
                      }
                     });
      });
  }
    
});

 
     $(".supplier_id").change(function(){
    
  var id=$(this).val();
  
  if(id!='')
  {
      $.get("{{URL::to('dashboard_secondarydata')}}?type=supplier&supplier_id="+id,function(data){
          
           $("#po_chart1").highcharts().update({
                      series:{
                        type: 'column',
                        colorByPoint: true,
                        data:data,
                        showInLegend: false
                      }
                     });
      });
  }
    
});
    
    $('#example').DataTable({
        "bLengthChange": false,
        "order": [[ 4, "desc" ]]
    });
    
    $('#example1').DataTable({
        "bLengthChange": false,
        "order": [[ 4, "desc" ]]
    });
    
        $('#operation_data').DataTable({
        "bLengthChange": false,
        "order": [[ 1, "desc" ]]
    });
    
      $('#production_data').DataTable({
        "bLengthChange": false,
        "order": [[ 1, "desc" ]]
    });
    
     $('#rol_fg_data').DataTable({
        "bLengthChange": false
    });
     $('#rol_rm_data').DataTable({
        "bLengthChange": false
    }); $('#rol_pm_data').DataTable({
        "bLengthChange": false
    });
    
    
    
    var inv_data="{{$overall_invoice_data}}";
    var pro_data="{{$overall_product_data}}";
  if(pro_data!='')
  {
 var chart = Highcharts.chart('po_chart2', {
    title: {
        text: 'Purchase By Product'
    },

    subtitle: {
        text: ''
    },
     plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.2f}'
            }
        }},
         yAxis: {
        
        title: {
            text: 'Values (in Lakhs)'
        }},

    xAxis: {
        categories: JSON.parse(column.replace(/&quot;/g,'"'))
    },

    series: [{
        type: 'column',
        colorByPoint: true,
        data: JSON.parse(pro_data.replace(/&quot;/g,'"')),
        showInLegend: false
    }]

});   
  }  
  
  
  
  if(inv_data!='')
  {
 var chart = Highcharts.chart('po_chart1', {

    title: {
        text: 'Purchase By Supplier'
    },

    subtitle: {
        text: ''
    },
     plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.2f}'
            }
        }},

 yAxis: {
        
        title: {
            text: 'Values (in Lakhs)'
        }},
    xAxis: {
        categories:JSON.parse(column.replace(/&quot;/g,'"'))
    },

    series: [{
        type: 'column',
        colorByPoint: true,
        data: JSON.parse(inv_data.replace(/&quot;/g,'"')),
        showInLegend: false
    }]

});  

  }
   

    
    
    var inv_data="{{$so_overall_invoice_data}}";
 
 
var po_status_data="{{$po_status}}";

if(po_status_data!='')
{
   
Highcharts.chart('po_chart4', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Purchase Order For '+"{{date('M-y')}}"
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.y}'
            }
        }
    },
    series: [{
        name: 'Status',
        colorByPoint: true,
        data: JSON.parse(po_status_data.replace(/&quot;/g,'"'))
    }]
}); 
} 
  
});
</script>
@endpush
