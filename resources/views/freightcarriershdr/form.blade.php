@extends('layouts.header')
@section('content')
<h3 class="text-danger">Freight Carriers</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-header bg-primary text-white"></div>

  <div class="card-body">
    <form method="post" action="{{ URL::to('freightcarrierssave') }}" id="freightcarrierhdr" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="save_status" id="save_status">
      <input type="hidden" class="form-control ar_frieghtcarriers_hdr_id" id="ar_frieghtcarriers_hdr_id" name="ar_frieghtcarriers_hdr_id" value="{{ $row->ar_frieghtcarriers_hdr_id }}" readonly>

      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label ">Carrier Name <span class="text-danger">*</span></label>
          <input type="text" name="carrier_name" class="form-control carrier_name" value="{{ $row->carrier_name }}" required tabindex="1">
          <span class="badge bg-danger dup_name d-none"></span>
        </div>

        <div class="col-md-4" style="pointer-events:none;">
          <label class="form-label ">Created By</label>
          <select name="created_by" class="form-select select2 created_by" id="created_by">
            {!! $created_by !!}
          </select>
        </div>

        <div class="col-md-4" style="pointer-events:none;">
          <label class="form-label ">Source Type</label>
          <select name="source_type_id" class="form-select select2" required>
            <option value="0">--Please Select--</option>
            <option value="Purchase" {{ $source_type_id == 'Purchase' ? 'selected' : '' }}>Purchase</option>
            <option value="Sales" {{ $source_type_id == 'Sales' ? 'selected' : '' }}>Sales</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label ">Remarks</label>
          <input type="text" name="remarks" class="form-control remarks" value="{{ $row->remarks }}" tabindex="2">
        </div>

        <div class="col-md-4 d-none">
          <label class="form-label ">Start Date</label>
          <input type="text" class="form-control start_date" name="start_date" value="{{ $row->start_date }}">
        </div>

        <div class="col-md-4 d-none">
          <label class="form-label ">End Date</label>
          <input type="text" class="form-control end_date" name="end_date" value="{{ $row->end_date }}">
        </div>

        <div class="col-md-4">
          <label class="form-label ">Active</label>
          <select name="active" class="form-select select2 active">
            <option value="YES" {{ $row->active == 'YES' ? 'selected' : '' }}>YES</option>
            <option value="NO" {{ $row->active == 'NO' ? 'selected' : '' }}>NO</option>
          </select>
        </div>
      </div>

      <hr class="my-4">

      <h5 class="mb-3 text-primary">Additional Details</h5>
      <div class="row g-3">

        @php $i=0; $j=0; @endphp
        @foreach($enabled_columns as $index => $val)
          @php 
            $required = ($val->action == '1') ? 'required' : '';
            if ($i != $j) $j = $i;
          @endphp

          @if($val->column_name == 'location_id' && $val->active == 1) @php $i++; @endphp
            <div class="col-md-4">
              <label class="form-label ">
                @if($required) <span class="text-danger">*</span> @endif
                Location
              </label>
              <div class="input-group">
                <select name="location_id" class="form-select select2 location_id" {{ $required }} tabindex="3">
                  {!! $location_id !!}
                </select>
              </div>
            </div>
          @endif

          @if($val->column_name == 'default_currency' && $val->active == 1) @php $i++; @endphp
            <div class="col-md-4">
              <label class="form-label ">
                @if($required) <span class="text-danger">*</span> @endif
                Default Currency
              </label>
              <div class="input-group">
                <select name="default_currency" class="form-select select2 default_currency" {{ $required }} tabindex="6">
                  {!! $default_currency !!}
                </select>
              </div>
            </div>
          @endif

          @if($val->column_name == 'shipping_method' && $val->active == 1) @php $i++; @endphp
            <div class="col-md-4">
              <label class="form-label ">
                @if($required) <span class="text-danger">*</span> @endif
                Shipping Method
              </label>
              <select name="shipping_method" class="form-select select2 shipping_method" {{ $required }} tabindex="9">
                <option value="">--Please Select--</option>
                <option value="AIR" {{ $row->shipping_method == 'AIR' ? 'selected' : '' }}>AIR</option>
                <option value="TRAIN" {{ $row->shipping_method == 'TRAIN' ? 'selected' : '' }}>TRAIN</option>
                <option value="TRUCK" {{ $row->shipping_method == 'TRUCK' ? 'selected' : '' }}>TRUCK</option>
              </select>
            </div>
          @endif

          @if($val->column_name == 'charging_uom' && $val->active == 1) @php $i++; @endphp
            <div class="col-md-4">
              <label class="form-label ">
                @if($required) <span class="text-danger">*</span> @endif
                Charging UOM
              </label>
              <select name="charging_uom" class="form-select select2 charging_uom" {{ $required }} tabindex="4">
                <option value="">--Please Select--</option>
                @foreach(['DIMENSION','DISTANCE','TIME','VOLUME','WEIGHT'] as $uom)
                  <option value="{{ $uom }}" {{ $row->charging_uom == $uom ? 'selected' : '' }}>{{ $uom }}</option>
                @endforeach
              </select>
            </div>
          @endif

          @if($val->column_name == 'charging_rating' && $val->active == 1) @php $i++; @endphp
            <div class="col-md-4">
              <label class="form-label ">
                @if($required) <span class="text-danger">*</span> @endif
                Charging Rating
              </label>
              <select name="charging_rating" class="form-select select2 charging_rating" {{ $required }} tabindex="7">
                <option value="">--Please Select--</option>
                @foreach(['BOX','CUBIC','HOUR','KG','KM','METER'] as $rate)
                  <option value="{{ $rate }}" {{ $row->charging_rating == $rate ? 'selected' : '' }}>{{ $rate }}</option>
                @endforeach
              </select>
            </div>
          @endif

          @if($val->column_name == 'charging_rating_value' && $val->active == 1) @php $i++; @endphp
            <div class="col-md-4">
              <label class="form-label ">
                @if($required) <span class="text-danger">*</span> @endif
                Charging Rating Value
              </label>
              <input type="text" class="form-control charging_rating_value" name="charging_rating_value" value="{{ $row->charging_rating_value }}" {{ $required }} tabindex="10">
            </div>
          @endif

          @if($val->column_name == 'minimum_time' && $val->active == 1) @php $i++; @endphp
            <div class="col-md-4">
              <label class="form-label ">
                @if($required) <span class="text-danger">*</span> @endif
                Minimum Time
              </label>
              <input type="text" class="form-control start_date_time" name="minimum_time" value="{{ $row->minimum_time }}" {{ $required }} readonly tabindex="5">
            </div>
          @endif

          @if($val->column_name == 'minimum_distance' && $val->active == 1) @php $i++; @endphp
            <div class="col-md-4">
              <label class="form-label ">
                @if($required) <span class="text-danger">*</span> @endif
                Minimum Distance (in Km)
              </label>
              <input type="text" class="form-control minimum_distance" name="minimum_distance" value="{{ $row->minimum_distance }}" {{ $required }} tabindex="8">
            </div>
          @endif

          @if($val->column_name == 'reliablity_percentage' && $val->active == 1) @php $i++; @endphp
            <div class="col-md-4">
              <label class="form-label ">
                @if($required) <span class="text-danger">*</span> @endif
                Reliability Percentage
              </label>
              <input type="text" class="form-control reliablity_percentage" name="reliablity_percentage" value="{{ $row->reliablity_percentage }}" {{ $required }} tabindex="11">
            </div>
          @endif

        @endforeach
      </div>

<div class="row mt-4">
  <div class="col-12 linetable">
<div id="preview-area" class="table-responsive">
  <table class="table table-bordered clone_table" style="width: 140%;">
    <thead class="table-light">
      <tr>
        <th style="width: 80px;">Line No</th>
        <th >Carrier Number</th>
        <th >Carrier Registration</th>
        <th >Permit Number</th>
        <th >Address</th>
        <th >Country</th>
        <th >State</th>
        <th >City</th>
        <th >Active</th>
        <th >Comments</th>
        <th style="width: 60px;"></th>
      </tr>
    </thead>
<tbody class="clone_lines_body">
  @if(count($linedata) > 0)
    @foreach($linedata as $key => $value)
      <tr class="line-row">
 <td>
<input type="hidden" name="bulk_freightcarriers_lines_id[]" class="form-control input-sm bulk_freightcarriers_lines_id" value="{{ $value->freightcarriers_lines_id }}" >


<input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly"></td>
<td><input type="text" name="bulk_carrier_number[]" class="form-control input-sm bulk_carrier_number" value="{{ $value->carrier_number }}" ></td>


<td>
<input type="text" name="bulk_carrier_registration[]" class="form-control input-sm bulk_carrier_registration input_qty_width" value="{{ $value->carrier_registration }}" minlength="1" >
</td>
<td>
<input type="text" name="bulk_permit_number[]" class="form-control input-sm bulk_permit_number" value="{{ $value->permit_number }}" >
</td>
<td>
<input type="text" name="bulk_carrier_address[]" class="form-control input-sm bulk_carrier_address" value="{{ $value->carrier_address }}" required >
</td>

<td class="country">

<select  name="bulk_country[]" class="select2 input-sm bulk_country"  required >
{!! $value->country !!}
</select>
</td>
<td class="state">

<select name="bulk_state[]" class="select2 input-sm bulk_state"  required >
{!! $value->state !!}
</select>
</td>

<td class="city">
<select type="bulk_city" name="bulk_city[]" class="select2 bulk_city"  required >
{!! $value->city !!}
</select>
</td>

<td>
<select type="bulk_active" name="bulk_active[]" class="select2 bulk_active"  required >
<option value="">Please select</option>
<option value="1" <?php  if($value->active =='1'){ echo "selected"; } else { echo ""; } ?> >Yes</option>
<option value="2" <?php if($value->active =='2'){ echo "selected";   } else { echo ""; } ?> >No</option>
</select>

</td>

<td>
	<input id="bulk_comments" type="text" name="bulk_comments[]" class="form-control bulk_comments" value="{{$value->comments}}" row="5" />


</td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger remove-row">
            <i class="fas fa-minus-circle"></i>
          </button>
        </td>
      </tr>
    @endforeach
  @else
    <tr class="line-row">
<td>
<input type="hidden" name="bulk_freightcarriers_lines_id[]" class="form-control input-sm bulk_freightcarriers_lines_id" value="">

<input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly"></td>
<td><input type="text" name="bulk_carrier_number[]" class="form-control input-sm bulk_carrier_number" value="" required ></td>


<td>
<input type="text" name="bulk_carrier_registration[]" class="form-control input-sm bulk_carrier_registration input_qty_width" value="" minlength="1" >
</td>
<td>
<input type="text" name="bulk_permit_number[]" class="form-control input-sm bulk_permit_number" value=""  >
</td>

<td>
<input type="text" name="bulk_carrier_address[]" class="form-control input-sm bulk_carrier_address" value="" required >
</td>

<td class="country">
<select type="bulk_country" name="bulk_country[]" class="select2 input-sm bulk_country"  required>
{!! $country !!}
</select>
</td>
<td class="state">
<select name="bulk_state[]" class="select2 input-sm bulk_state" required >
{!! $state !!}
</select>
</td>

<td class="city">
<select type="bulk_city" name="bulk_city[]" class="select2 input-sm bulk_city"  required >
{!! $city !!}
</select>
</td>


<td>
<select type="bulk_active" name="bulk_active[]" class="select2 bulk_active" >
<option value="">--please select--</option>
<option value="1" selected="selected">Yes</option>
<option value="2" >No</option>
</select>
</td>

<td>
<input type="text" id="bulk_comments" name="bulk_comments[]" class="form-control bulk_comments" value="" row="5"  />
</td>
      <td class="text-center">
        <button type="button" class="btn btn-sm btn-danger remove-row">
          <i class="fas fa-minus-circle"></i>
        </button>
      </td>
    </tr>
  @endif
</tbody>

  </table>

  <div class="text-end">
    <button type="button" class="btn btn-success btn-sm add-row">
      <i class="fas fa-plus-circle"></i> Add Row
    </button>
  </div>
</div>

  </div>
</div>
<!-- END -->

  <div class="text-center mt-4">
    <button type="button" class="btn btn-success saveform px-4 me-2">Save</button>
    <a class="btn btn-secondary canceld me-2">Cancel</a>
  </div>
</form>
</div>
</div>  


@endsection
@push('scripts')

<script>

//// Add Row
$(document).on('click', '.add-row', function () {
    const $lastRow = $('.clone_lines_body tr:last');
    const $newRow = $lastRow.clone(false, false); // clone without events or data

    // Clear all input and select values in the cloned row
    $newRow.find('input').val('');
    $newRow.find('select').val('').trigger('change');

    // Remove any Select2 artifacts before reinitializing
    $newRow.find('select.select2').each(function () {
        if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
        $(this).removeAttr('data-select2-id'); 
       $(this).next('.select2').remove(); // remove the select2 container
    });

    // Append the cleaned-up cloned row
    $('.clone_lines_body').append($newRow);

    // Reinitialize select2
    $newRow.find('select.select2').select2({ width: '100%' });

		// Update line numbers
		updateLineNumbers();
	});



  // Remove button
  $(document).on('click', '.remove-row', function () {
    const rowCount = $('.clone_lines_body tr').length;
    if (rowCount > 1) {
      $(this).closest('tr').remove();
      updateLineNumbers();
    } else {
      showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
    }
  });

  // Renumber Line Nos
  function updateLineNumbers() {
    $('.clone_lines_body tr').each(function (index) {
      $(this).find('.bulk_line_no').val(index + 1);
    });
  }

	
	$('.carrier_name').on('keyup',function(){
		this.value= this.value.toUpperCase();
	});	
	
	
	
// on change functions
	
$(document).on('change', '.bulk_country', function () {
  let bulk_country = $(this).val();
  $('.bulk_state').html('<option value="">-- Loading States --</option>');

  if (bulk_country) {
    $.ajax({
      url: "{{ url('jcomboformlogin') }}?table=m_states_t:state_id:state_name&parent=country_id=" + bulk_country + "&order_by=state_name",
      success: function (data) {
        $('.bulk_state').html('<option value="">-- Select State --</option>');
        $.each(data, function (i, item) {
          let selected = item.val == "{{ $row->bulk_state ?? '' }}" ? 'selected' : '';
          $('.bulk_state').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
        });
      }
    });
  }
});

	$(document).on('change', '.bulk_state', function () {
  let bulk_state = $(this).val();
  $('.bulk_city').html('<option value="">-- Loading Cities --</option>');

  if (bulk_state) {
    $.ajax({
      url: "{{ url('jcomboformlogin') }}?table=m_cities_t:city_id:city_name&parent=state_id=" + bulk_state + "&order_by=city_name",
      success: function (data) {
        $('.bulk_city').html('<option value="">-- Select City --</option>');
        $.each(data, function (i, item) {
          let selected = item.val == "{{ $row->bulk_city ?? '' }}" ? 'selected' : '';
          $('.bulk_city').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
        });
      }
    });
  }
});
	
	

	
$(document).ready(function(){
$('.dup_name').hide();

	$('.carrier_name').keyup(function (e) {
	   $('.dup_name').hide();	
	});
		/**********Up/down/left/right arrow navigation start*******/
		$('input').keyup(function (e) {
	        if (e.which == 39) { // right arrow
	          $(this).closest('td').next().find('input').focus();
	 
	        } else if (e.which == 37) { // left arrow
	          $(this).closest('td').prev().find('input').focus();
	 
	        } else if (e.which == 40) { // down arrow
	          $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
	 
	        } else if (e.which == 38) { // up arrow
	          $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
	        }
      	});
        


<?php  if(isset($used_some)) { ?>
	
	$('.carrier_name').attr('readonly',true);
	<?php } ?>


$(document).on('keypress','.charging_rating_value,.minimum_time,.minimum_distance,.reliablity_percentage', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});


     $('.minimum_distance,.charging_rating_value,.reliablity_percentage').bind("cut copy paste", function(e) {
        e.preventDefault();
            });

	$('#save_status').val('');


      var dup_chk = true;
    function duplicate_validate()
    {

        var carrier_name = $(".carrier_name").val();
        var source_type_id = $(".source_type_id").val();
        var ar_frieghtcarriers_hdr_id = $(".ar_frieghtcarriers_hdr_id").val();

        $.ajax({
            cache: false,
            url: "{{ url::to('carrierchck') }}", //this is your uri
            type: 'GET',
            dataType: 'json',
            async : false,
            data: {carrier_name : carrier_name,source_type_id:source_type_id,ar_frieghtcarriers_hdr_id : ar_frieghtcarriers_hdr_id},
            success: function(response)
            {
               
                if (response == 1)
                {
                    $('.dup_name')
                        .html('UOM Code: ' + uom_code + ' already exists')
                        .removeClass('d-none')
                        .addClass('d-block');

                    $(".uom_code").val('');
                    dup_chk = false;
                }
                    else if(response == 0)
                    {
                           var html ="";
                            $('.dup_name').hide();
                            dup_chk = true;

                    }
            },
            error: function(xhr, resp, text)
            {
                console.log(xhr, resp, text);
            }
        });
    }

/* -- Start Save,draft Button function -- */
	$(document).on('click','.saveform',function()
    {
		 $(".source_type_id").select2({
	     disabled:false
        });
    	 $('#panel_add').trigger('click');
    	var btnval		= $(this).val();
		  var status = "Saved";
      if(btnval == "save")
        {
			$('#save_status').val(status);
		}
		if(btnval == "savenew"){
			$('#save_status').val(status);
		}
		if(btnval == "applychanges")
		{
			$('#save_status').val(status1);
		}
    	var url			="{{ URL::to('freightcarrierssave') }}";
        var red_url		="{{ url('freightcarriershdr') }}";

        var red_url_pur	="{{ url('purchasefreightcarriershdr') }}";
        var create_url	="{{ url('freightcarriershdrcreate') }}/0";
        var create_url_pur	="{{ url('purchasefreightcarriershdrcreate') }}/0";
        var type = "<?php echo $source_type_id; ?>";

				duplicate_validate();

        var formdata	= $('#freightcarrierhdr').serialize();
        var form = $('#freightcarrierhdr');


/**********Duplicate check  Start*******/
    if(dup_chk==true)
        {
        if(btnval != 'applychanges')
        {

	       	form.parsley().validate();
	       	var form = $('#freightcarrierhdr');
	       	form.parsley().validate();

            if (form.parsley().isValid())
            {

              var $btn = $(this);            
		         	$btn.prop('disabled', true);
      
            	$.post(url,formdata,function(data)
                {
                	   var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;

                    if(btnval !='save')
                    {
                        showCustomAlert(msg,status);
						 $(".source_type_id").select2({
	                       disabled:true
                         });

                        setTimeout(function(){
                    	if(type == "Purchase")
                    	   	window.location.href=red_url_pur;
	        			else
	        				window.location.href=red_url;

                        }, 1500);
                    }
                    else{
                    	   showCustomAlert(msg,status);
						 $(".source_type_id").select2({
	                       disabled:true
                         });
                        setTimeout(function(){
                    	if(type == "Purchase")

                    	   	window.location.href=red_url_pur;
	        			else
	        				window.location.href=red_url;
                        }, 1000);
                    }
                });


            }
        }
        else
        {  
          
			   var name=$('.carrier_name').val();
			if(name!=''){

        	       $.post(url,formdata,function(data){
        		var status = data.status;
                var msg    = data.message;
                var id     = data.id;
                console.log(data);
                var edit_url	="{{ url('freightcarriershdrcreate') }}/"+id;
    			var edit_url_pur	="{{ url('purchasefreightcarriershdrcreate') }}/"+id;
                notyMsg(status,msg);
                setTimeout(function(){
            	if(type == "Purchase")
            	   	window.location.href=edit_url_pur;
    			else
    				window.location.href=edit_url;
                }, 1500);
        	});
			}
			else{
				showCustomAlert("Carrier name is required",'info');
			}
        }
	}

    });


	
	$(".canceld").click(function(){

	var type = "<?php echo $source_type_id; ?>";
        var ty = "<?php echo $url_type; ?>";
        var create_url ="{{url('freightcarriershdr')}}";
	var red_url ="{{url('purchasefreightcarriershdr')}}";
	if(type == "Purchase" || ty == "purchasefreightcarriershdredit" ){
		window.location.href=red_url;
	}else{
		window.location.href=create_url;
	}
});
	

});


</script>


@endpush
