@extends('layouts.header')
@section('content')
<h3 class="text-danger">Product Bom Upload</h3>
@include('layouts.breadcrumb')

<form method="post" action="" id="materialbomupload" data-parsley-validate>

{{ csrf_field() }}
	
<div class="card shadow-lg rounded-4 border-0">
<div class="card-body card-block headerdiv1">


<div class="container-fluid">
  <div class="row g-4">
    
    <!-- Left Column -->
    <div class="col-md-4">
      
      <!-- Production Product -->
      <div class="mb-3">
        <label class="form-label">
          <span class="text-danger">*</span> Production Product
        </label>
        <input type="hidden" name="bom_upload_id" id="bom_upload_id" class="bom_upload_id" value="{{ $row->bom_upload_id }}" readonly>
        <select name="bom_product" id="bom_product" class="form-select select2 bom_product" required>
          {!! $bom_product !!}
        </select>
      </div>

      <!-- UOM Code -->
      <div class="mb-3">
        <label class="form-label">UOM Code</label>
        <select name="bom_uom_code" id="bom_uom_code" class="form-select select2 bom_uom_code">
          {!! $bom_uom_code !!}
        </select>
      </div>

      <!-- Machine -->
      <div class="mb-3">
        <label class="form-label">Machine</label>
        <select name="machine" id="machine" class="form-select select2 machine">
          {!! $machine !!}
        </select>
      </div>

      <!-- Process Level -->
      <div class="mb-3">
        <label class="form-label">Process Level</label>
        <select name="process_level" id="process_level" class="form-select select2 process_level">
          {!! $process_level !!}
        </select>
      </div>

      <!-- Process Name -->
      <div class="mb-3">
        <label class="form-label">Process Name</label>
        <select name="process_name" id="process_name" class="form-select select2 process_name">
          {!! $process_name !!}
        </select>
      </div>

      <!-- Component Product -->
      <div class="mb-3">
        <label class="form-label">
          <span class="text-danger">*</span> Component Product
        </label>
        <select name="component_product" id="component_product" class="form-select select2 component_product" required>
          {!! $component_product !!}
        </select>
      </div>

      <!-- Component UOM Code -->
      <div class="mb-3">
        <label class="form-label">
          <span class="text-danger">*</span> Component UOM Code
        </label>
        <select name="component_uom_code" id="component_uom_code" class="form-select select2 component_uom_code" required>
          {!! $component_uom_code !!}
        </select>
      </div>

      <!-- Remarks -->
      <div class="mb-3">
        <label class="form-label">Remarks</label>
        <input type="text" name="bom_remarks" id="bom_remarks" class="form-control" value="{{ $row->bom_remarks }}">
      </div>
    </div>

    <!-- Middle Column -->
    <div class="col-md-4">
      
      <!-- Component Qty -->
      <div class="mb-3">
        <label class="form-label">Component Qty</label>
        <input type="text" name="component_qty" id="component_qty" class="form-control component_qty" value="{{ $row->component_qty }}">
      </div>

      <!-- Process Checkbox -->
      <div class="mb-3">
        <label class="form-label">Process</label>
        <div class="form-check">
          <input class="form-check-input process" type="checkbox" name="process[]" value="1"
            <?php if($row->process =="1") { echo "checked"; } ?>>
          <label class="form-check-label">Include in Process</label>
        </div>
      </div>

      <!-- Comments -->
      <div class="mb-3">
        <label class="form-label">Comments</label>
        <textarea name="comments" id="comments" class="form-control comments" rows="3">{!! $row->comments !!}</textarea>
      </div>
    </div>

    <!-- Right Column -->
    <div class="col-md-4">
      
      <!-- Batch Name -->
      <div class="mb-3">
        <label class="form-label">Batch Name</label>
        <input type="text" name="batch_name" id="batch_name" class="form-control batch_name" value="{{ $row->batch_name }}" readonly>
      </div>

      <!-- Batch Date -->
      <div class="mb-3">
        <label class="form-label">Batch Date</label>
        <input type="text" name="batch_date" id="batch_date" class="form-control batch_date" value="{{ $row->batch_date }}" readonly>
      </div>

      <!-- Batch Status -->
      <div class="mb-3">
        <label class="form-label">Batch Status</label>
        <input type="text" name="batch_status" id="batch_status" class="form-control batch_status" value="{{ $row->batch_status }}" readonly>
      </div>

      <!-- Batch Comments -->
      <div class="mb-3">
        <label class="form-label">Batch Comments</label>
        <textarea name="batch_comments" id="batch_comments" class="form-control batch_comments" rows="3" readonly>{{ $row->batch_comments }}</textarea>
      </div>
    </div>
    
  </div>
</div>



<div class="row mt-4 mb-3">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">   
			<button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
        	  <a href="{{ url('materialbomupload') }}" class='btn btn-danger px4 me-2'>Cancel</a>
		</div>
	</div>
</div>




</div>
</div>
</form>


@endsection
@push('scripts')


<script>

$(document).ready(function()
{

	
		$(document).on('keypress','.per_based,.qty_based', function(ev){
			var regex = new RegExp("^[0-9.]+$");
			var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
			if (regex.test(str)) {
				return true;
			}
			ev.preventDefault();
			return false;
		});

	$(document).on('change','.bom_product',function(){
		var prd = $('.bom_product').val();
		var url1 = "{{ URL::to('getprdtypeid') }}/"+prd;
		$.get(url1 , function(data){
			var data = $.trim(data);
			$('.bom_uom_code').val(data).trigger('change');;
		});
	});
	
	/* purpose:to get component product*/
	$(document).on('change','.component_product',function(){
		var prd = $('.component_product').val();
		var url1 = "{{ URL::to('getprdtypeid') }}/"+prd;
		$.get(url1 , function(data){
			var data = $.trim(data);
			$('.component_uom_code').val(data).trigger('change');;
		});
	});
	
	/* purpose:to get component qty*/
  $('.component_qty').change(function(){
	var qtybas=$(this).val();
	if(qtybas=='Percentage'){
	   $('.qty_based').attr('readonly',true);
	   $('.per_based').attr('required',true);
	   $('.per_based').attr('readonly',false);
		$('.per').show();
		$('.val').hide();
	   }else{
		 $('.qty_based').attr('readonly',false);
	   $('.per_based').attr('readonly',true);   
	   $('.qty_based').attr('required',true);   
		   $('.val').show();
		   $('.per').hide();
	   }
  });
	
$('.component_qty').trigger('change');
	
    $(document).on('click','.saveform',function()
    {
        var url		= "{{ url('materialbomuploadsave') }}";
        var red_url		="{{ url('materialbomupload') }}";
        validationrule('materialbomupload');
        var formdata	= $('#materialbomupload').serialize();
        var form = $('#materialbomupload');
        form.parsley().validate();
        var form = $('#materialbomupload');
        form.parsley().validate();

        if (form.parsley().isValid())
        {
            $.post(url,formdata,function(data)
            {
                var status      = data.status;
                var msg     =     data.message;
                
                showCustomAlert(msg,status);
                setTimeout(function(){
                    window.location.href=red_url;
                }, 1500);
                
            });
        }
      
    });

     
});


</script>

@endpush
