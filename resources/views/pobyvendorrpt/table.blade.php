@extends('layouts.header')
@section('content')

<style type="text/css">
.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}
.table-responsive > .table-bordered {
        text-align: center;
    border: 0;
}

.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}

.table.table-bordered thead {
    border: 1px solid #f2f2f2;
    border-bottom: none;
}

.table-responsive tbody tr > td:first-child {
    display: table-cell;
}
.card{
    border-radius: 5px;
    margin-bottom: 40px;
}
.card-body {
    padding: 14px 10px;
}
table tbody tr:nth-child(1) {
    background: none;
}
tbody{
    vertical-align: middle;
    font-size: 13px;
    line-height: 1;
    white-space: nowrap;
}
table-bordered td {
    border: 1px solid #f2f2f2;
}
.table th, .table td {
    padding: 18px 30px;
    
}
.table>thead:first-child>tr:first-child>th {
   color: #000;
    font-weight: bolder;
    text-align: center;
    background: none;
    padding: 10px 0px 10px 0px;
}
.table .progress{
    text-align: center;
    height: 8px;
    border-radius: 3px;
    width: 75%;
}

.table.table-bordered thead tr th {
    border-left: none;
    border-right: none;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #f2f2f2;
}
.table-bordered th, .table-bordered td {
    border: 1px solid #f2f2f2;
}
.table>tbody>tr>td{
    padding: 15px;

    vertical-align: middle;
    border-top: 1px solid #f2f2f2;
}

</style>


<div class="data_div">
	</div>

<h5 class="heads">Purchase Orders by Vendor</h5>
    
        
            <div class="card div_hide">
                
                <div class="card-body">

                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>

                          <th>
                            Vendor Name
                          </th>
               <th>
                            Purchase Order Count
                          </th>
                          <th>
                            Amount
                          </th>

                        </tr>
                      </thead>
                      <tbody>
                       <?php  
              foreach($result as $key=>$value) { ?>
              <tr>
                 <td class="font-weight-medium">
                          <a class="btn-link supplier " data-value="{{$value->supplier_id}}">{{ $value->supplier_name }}</a> 
                          </td>

                <td>
                   <div class="label label-table label-success">{{$value->po_count}}</div>

                </td>

                <td>
                 {{ $value->po_grand_total }}
                </td>

              </tr>
                       <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div>
            
          

      

  


<script type="text/javascript">
$( document ).ready(function() {
	$('.data_div').hide();
	
$(document).on('click','.supplier', function(ev){
			var supplier=$(this).data('value');
		var url="{{ URL::to('podetailsrpt')}}/?supplier_id="+supplier;
		$.get(url,function(data){
			$(".data_div").html(data);
			$('.data_div').show();
			$('.div_hide').hide();
		});
	});
	$(document).on('click','.close_btn', function(ev){
		$('.data_div').hide();
			$('.div_hide').show();
	});

	$(window).scroll(function() {
if ($(this).scrollTop() >150){
    $('.header-sticky').addClass("sticky");
  }
  else{
    $('.header-sticky').removeClass("sticky");
  }
});

});
  </script>
@endsection
