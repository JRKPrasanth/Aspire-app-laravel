@extends('layouts.header')
@section('content')
<h3 class="text-danger">Batch Conversion</h3>
@include('layouts.breadcrumb')
<?php error_reporting(0);?>       

<form method="post" action="" id="conversion_form" class="conversion_form needs-validation" novalidate>
    <input type="hidden" value="" name="status" id="savestatus" />
    {{ csrf_field() }}

   <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white fw-semibold">
            Conversion Form
        </div>

        <div class="card-body">
            <!-- Row 1 -->
            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label">Conversion No</label>
                    <input type="text" id="conversion_number" name="conversion_number" 
                           class="form-control conversion_number" value="{{$conversion_number}}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <input type="text" id="conversion_date" name="conversion_date" 
                           class="form-control conversion_date" value="{{$conversion_date}}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <select id="type" name="type" class="form-select select2 type" required>
                        <option value="">-- please select --</option>
                        <option value="batchchange" {{ $type == 'batchchange' ? 'selected' : '' }}>Batch Change</option>
                        <option value="packchange" {{ $type == 'packchange' ? 'selected' : '' }}>Pack Change</option>
                        <option value="numtokgs" {{ $type == 'numtokgs' ? 'selected' : '' }}>Num To Kgs</option>
                    </select>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row g-4 mt-2">
                <div class="col-md-4" id="productField">
                    <label class="form-label">Product</label>
                    <select id="product_id" name="product_id" class="form-select select2 product_id">
                        {!! $product_id !!}
                    </select>
                </div>

                <div class="col-md-4" id="fromProductField">
                    <label class="form-label">From Product</label>
                    <select id="from_product" name="from_product" class="form-select select2 from_product">
                        {!! $from_product !!}
                    </select>
                </div>

                <div class="col-md-4" id="toProductField">
                    <label class="form-label">To Product</label>
                    <select id="to_product" name="to_product" class="form-select select2 to_product">
                        {!! $to_product !!}
                    </select>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <label class="form-label">From Batch</label>
                    @if($pageMethod=="approveconversion")
                        <select id="batch_number" name="batch_number" class="form-select select2 batch_number" required>
                            {!! $batch_number !!}
                        </select>
                    @else
                        <select id="batch_number" name="batch_number" class="form-select select2 batch_number" required></select>
                    @endif
                </div>

                <div class="col-md-4">
                    <label class="form-label">From Inventory</label>
                    <select id="from_inv" name="from_inv" class="form-select select2 from_inv" readonly required>
                        {!! $subinventory_id !!}
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">From Locator</label>
                    <select id="from_loc" name="from_loc" class="form-select select2 from_loc" readonly required>
                        {!! $sublocator_id !!}
                    </select>
                </div>
            </div>

            <!-- Row 4 -->
            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <label class="form-label">To Inventory</label>
                    <select id="to_inv" name="to_inv" class="form-select select2 to_inv" required>
                        {!! $subinventory_id !!}
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">To Locator</label>
                    <select id="to_loc" name="to_loc" class="form-select select2 to_loc" required>
                        {!! $sublocator_id !!}
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">QOH</label>
                    <input type="text" name="from_qoh" class="form-control from_qoh" value="{{ $qoh }}">
                </div>
            </div>

            <!-- Row 5 -->
            <div class="row g-4 mt-2">
                <div class="col-md-4" id="qtyField">
                    <label class="form-label">Qty</label>
                    <input type="text" name="from_qty" class="form-control from_qty" value="{{ $qty }}">
                </div>

                <div class="col-md-4" id="fromqtyField">
                    <label class="form-label">From Qty</label>
                    <input type="text" name="frompack_qty" class="form-control frompack_qty" value="{{ $from_qty }}">
                </div>

                <div class="col-md-4" id="toqtyField">
                    <label class="form-label">To Qty</label>
                    <input type="text" name="to_qty" class="form-control to_qty" value="{{ $to_qty }}">
                </div>
            </div>

            <!-- Row 6 -->
            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <label class="form-label">To Batch</label>
                    @if($pageMethod=="approveconversion")
                        <select id="to_batch" name="to_batch" class="form-select select2 to_batch" required>
                            {!! $to_batch_number !!}
                        </select>
                        <input type="hidden" name="con_id" value="{{ $con_id }}">
                    @else
                        <input type="text" name="to_batch" class="form-control" value="">
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer text-center bg-light mt-2">
            @if($pageMethod=="approveconversion")
                <button type="button" class="btn btn-success px-4 me-2 approved">Approve</button>
                <a href="{{ URL::to('batchconversionapproval') }}" class="btn btn-secondary px-4">Cancel</a>
            @else
                <button type="submit" class="btn btn-success px-4 me-2 saveform">Submit</button>
                <a href="{{ URL::to('home') }}" class="btn btn-secondary px-4">Cancel</a>
            @endif
        </div>
    </div>
</form>
		


@endsection
@push('scripts')

<script>

   $(document).on('change', '.product_id', function() {
    	
	var val= $('.product_id').select2('val');

	if(val!='')
	{
		$.get('productbatchno?product_id='+val,function(data){
			$(".batch_number").html(data);
		});
	}
    });

   $(document).on('change', '.from_product', function() {
    	
	var val= $('.from_product').select2('val');
    var batchtype =$('#type').select2('val');


	if(val!='')
	{
		$.get('productbatchno?product_id='+val,function(data){
			$(".batch_number").html(data);
		});
		if(batchtype=='packchange'){
		$.get('toproduct?product_id='+val,function(data){
			$(".to_product").html(data);
		});
		}else{
		    $.get('toproductkgs?product_id='+val,function(data){
			$(".to_product").html(data);
		});
		}
	
	}
    });
    
    $(".batch_number").change(function(){
        
    	var val = $('.batch_number').select2('val');
    	var product1 =  $('.product_id').select2('val');
    	var product2 =  $('.from_product').select2('val');
    	
    	if(product1 !=''){
    	var product =  $('.product_id').select2('val');
    	} 
    	if(product2 !=''){
    	    var product =  $('.from_product').select2('val');
    	}
    	
    	var url = "{{URL::to('getinventlocator')}}?batch="+val+"&product="+product;
    	
    	$.get(url,function(data)
    	{
    		$('.from_inv').select2('val',[data.subinventory_id]);
    		$('.from_loc').select2('val',[data.sublocator_id]);
    		$('.to_inv').select2('val',[data.subinventory_id]);
    		$('.to_loc').select2('val',[data.sublocator_id]);
    		$('.from_qoh').val(data.qoh);
    	})
    });


    $(".from_qty").change(function(){
        
    	var val = $('.from_qty').val();
    	var product = $(".from_qoh").val();
    	var product = parseFloat(product.replace(/,/g, ''))

    	if(parseInt(val)>parseInt(product))
    	{
    		showCustomAlert("From Quantity Should not be greater than From Qoh","warning");
    		$(".from_qty").val('0');
    
    	}
    });
    
	
    $(".to_qty").change(function(){
            
        var val = $('.to_qty').val();
        var product = $(".from_qty").val();
        var product = parseFloat(product.replace(/,/g, ''))
        console.log(product);
        if(parseInt(val)>parseInt(product))
        {
            showCustomAlert("To Quantity Should not be greater than From Qty","warning");
                        $(".to_qty").val('0');
    
        }

    });


   // save function
   
	$(document).ready(function () {
    $(document).on('click', '.saveform', function (e) {
        e.preventDefault();

        var btnval = $(this).val();
        $('#savestatus').val(btnval);

        var form = $('#conversion_form');
        form.parsley().validate();

        if (form.parsley().isValid()) {
            var $btn = $(this);            
			$btn.prop('disabled', true);
            var saveurl = "{{ url('conversionsave') }}";
            var red_url = "{{ url('batchconversion') }}";

            var formData = form.serialize();

            $.post(saveurl, formData, function (data) {
                var status = data.status;
                var msg = data.message;
                showCustomAlert(msg,status);

                if (status === 'success') {
                    setTimeout(function () {
                        window.location.href = red_url;
                    }, 1500);
                }
            });
        }
    });
 });

// approved save

	$(document).ready(function () {
    $(document).on('click', '.approved', function (e) {
        e.preventDefault();

        var btnval = $(this).val();
        $('#savestatus').val(btnval);

        var form = $('#conversion_form');
        form.parsley().validate();

        if (form.parsley().isValid()) {
            var saveurl = "{{ url('approveconversionsave') }}";
            var red_url = "{{ url('batchconversionapproval') }}";

            var formData = form.serialize();

            $.post(saveurl, formData, function (data) {
                var status = data.status;
                var msg = data.message;
                showCustomAlert(msg,status);

                if (status === 'success') {
                    setTimeout(function () {
                        window.location.href = red_url;
                    }, 1500);
                }
            });
        }
    });
});

$(document).on('change','.type',function(){
    var batchtype =$('#type').select2('val');
    console.log("hi");
    if(batchtype == "batchchange"){

        $('#productField').show();   
        $('#qtyField').show(); 
        $('#fromProductField').hide();   
        $('#toProductField').hide(); 
        $('#fromqtyField').hide();   
        $('#toqtyField').hide(); 

    }else{
        $('#productField').hide();   
        $('#qtyField').hide(); 
        $('#fromProductField').show();   
        $('#toProductField').show(); 
        $('#fromqtyField').show();   
        $('#toqtyField').show(); 
    }
});
	
</script>


@endpush