@extends('layouts.header')
@section('content')
<h3 class="text-danger">Travel Claim Approval</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
        <form action="" id="approveclaim" data-parsley-validate>
            @csrf
            <input type="hidden" name="edit_id" value="{{ $edit_id }}" id="edit_id" />

            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Employee</label>
                        <select name="employee_id" id="employee_id" class="form-select select2" data-live-search="true">
                            {!! $employee_id !!}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Claim Title</label>
                        <input type="text" id="claim_title" name="claim_title" class="form-control" value="{{ $claim_title }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Travel Date</label>
                        <input type="text" id="travel_date" name="travel_date" class="form-control" value="{{ $travel_date }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Travel Purpose</label>
                        <input type="text" id="travel_purpose" name="travel_purpose" class="form-control" value="{{ $travel_purpose }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Travel Mode</label>
                        <select name="travel_mode" id="travel_mode" class="form-select select2" required>
                            {!! $travel_mode !!}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Description</label>
                        <input type="text" id="description" name="description" class="form-control" value="{{ $description }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Bill Copy</label><br>
                        <a href="{{ '../images/claimupload/' . $bill_copy }}" download class="btn btn-outline-primary btn-sm">Download</a>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> From Place</label>
                        <input type="text" id="from_place" name="from_place" class="form-control" value="{{ $from_place }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> To Place</label>
                        <input type="text" id="to_place" name="to_place" class="form-control" value="{{ $to_place }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Distance</label>
                        <input type="text" id="distance" name="distance" class="form-control" value="{{ $distance }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Forwarded To</label>
                        <select name="forwarded_id" id="forwarded_id" class="form-select select2" required>
                            {!! $forwarded_id !!}
                        </select>
                    </div>

                    <div class="row mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Date</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="date1" name="date1" value="{{ $date1 }}">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="date2" name="date2" value="{{ $date2 }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Bill Amount</label>
                        <div class="field_wrapper">
						                            @php 
                           
                               $reason = isset($reason) && !empty($reason) ? json_decode($reason) : array();
                               $count = count($reason);
                              
                            @endphp	
                            @if(count($reason) > 0)
                                @foreach($reason as $value)
                                    <div class="mb-2">
                                        <input type="text" readonly name="reason[]" value="{{ $value[0] }}" class="form-control d-inline-block w-45 me-2" placeholder="Reason">
                                        <input type="text" readonly name="amount[]" value="{{ $value[1] }}" class="form-control d-inline-block w-45" placeholder="Amount">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Total Bill Amount</label>
                        <input type="text" name="bill_amount" id="bill_amount" value="{{ $bill_amount }}" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><span class="text-danger">*</span> Approve Bill Amount</label>
                        <input type="text" name="approve_amount" id="approve_amount" class="form-control" required>
                        <input type="hidden" name="status" id="status">
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="button" class="btn btn-success px-4 me-2 save_form" value="1">
                    Approve
                </button>
                <button type="button" class="btn btn-danger px-4 me-2 save_form" value="2">
                    Reject
                </button>
               <a href="{{url('claimapproval')}}"> <button type="button" class="btn btn-secondary px-4" id="delete">
                 Cancel
				   </button> </a>
            </div>
        </form>
    </div>
</div>


   

@endsection
@push('scripts')

<script>
	
	
	$(document).ready(function()
        {
              
                $(document).on('keypress', '.amount,.approve_amount', function(ev)
                {    
                    var regex = new RegExp("^[0-9.]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) 
                    {
                            return true;
                    }
                    ev.preventDefault();
                    return false;
                });
                
                $('.removecursor').css('pointer-events','none');
               

               
                $(document).on('keyup','.sum',function(ev)
                {
                    var sum=0;
                    var value = $('.sum').val();
                    $('.sum').each(function() 
                    {      
                       sum +=(isNaN(parseInt($(this).val()))) ? 0 : parseInt($(this).val())
                    });
                    $('#bill_amount').val(sum);
                });

               
                $(document).on('keypress', '.emi', function(ev)
                {
                    var regex = new RegExp("^[0-9]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) 
                    {
                        return true;
                    }
                    ev.preventDefault();
                    return false;
                });

        
                $(document).on('keyup','#approve_amount',function()
                {
                  
                    var amount = parseInt($('#approve_amount').val());
                    var bill_amount = $('#bill_amount').val();
                      
                    if(amount>bill_amount)
                    {
                          notyMsgs('Info','Approve Amount Cannot be Exceed Than'+bill_amount);
                          $('#approve_amount').val('');
                    }
                });
              
                $('#bill_amount,#travel_purpose,#travel_date,#claim_title,#description,#from_place,#to_place,#distance,.date1,.date2').prop('readonly',true);

        
		
                $(document).on('click','.save_form',function()
                {
						var btnval = $(this).val();
           			 $("#status").val(btnval);
                    var url	="{{URL::to('travelclaimapprove')}}";
                    var form_data = new FormData(document.getElementById('approveclaim'));
                    var form = $('#approveclaim');
                    form.parsley().validate();
                   
                    if (form.parsley().isValid())
                    {	
                        var $btn = $(this);            
			            $btn.prop('disabled', true);
                         $.ajax({
                          url: "{{ url('travelclaimapprove')}}",
                          type: "POST",
                          data: form_data,
                          enctype: 'multipart/form-data',
                          processData: false,  // tell jQuery not to process the data
                          contentType: false,   // tell jQuery not to set contentType
                          async:true,
                          xhr: function(){
                            var xhr = $.ajaxSettings.xhr();
                            if (xhr.upload) {
                                xhr.upload.addEventListener('progress', function(event) {
                                }, true);
                                }
                                return xhr;
                        }
                        }).done(function(data,status)
                        {
                           
                            if(data == 1)
                            {
                                showCustomAlert('Travel Claim Approved Successfully','success');
                                
                                var url= "{{URL::to('claimapproval')}}";
                                setTimeout(function(){ 
                                    window.location.href=url;
                                }, 1000);
                            }
                            if(data == 2)
                            {
                                showCustomAlert('Travel Claim  Rejected Successfully','success'); 
                               
                                var url= "{{URL::to('claimapproval')}}";
                                setTimeout(function(){ 
                                    window.location.href=url;
                                }, 1000);
                            }
                            
                        }).fail(function(data,status)
                        {

                                $(".alert-success").hide();
                                $(".alert-danger").fadeIn(800);

                        });
                    }
                });
        
            });
	
	</script>

@endpush
