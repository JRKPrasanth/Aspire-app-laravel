@extends('layouts.header')
@section('content')


	<div class="container-fluid py-4">
    <form>
        <input type="hidden" class="adjustment_id" name="adjustment_id" value="{!! $adjustment_id !!}">
        <input type="hidden" class="adjustment_date" name="adjustment_date" value="{!! $adjustment_date !!}">
        <input type="hidden" class="account_code_id" name="account_code_id" value="{!! $account_code_id !!}">
        <input type="hidden" class="adjustment_amount" name="adjustment_amount" value="{!! $adjustment_amount !!}">
        <input type="hidden" class="account_type" name="account_type" value="{!! $account_type !!}">
        <input type="hidden" class="reason_code" name="reason_code" value="{!! $reason_code !!}">

        <div class="card shadow-lg rounded-3 border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-check2-circle me-2"></i>Adjustments Approval</h4>
                <a href="{{ url('adjustmentapproval') }}" class="btn btn-danger btn-sm"><i class="bi bi-x-lg"></i> Close</a>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <p><strong>Adjustments Date:</strong> {!! $adjustment_date !!}</p>
                        <p><strong>Account Type:</strong> {!! $account_type !!}</p>
                        <p><strong>Adjustments Amount:</strong> {!! $adjustment_amount !!}</p>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6 text-md-end">
                        <p><strong>Account Code:</strong> {!! $concatenated_segments !!}</p>
                        <p><strong>Description:</strong> {!! $description !!}</p>
                        <p><strong>Reason Code:</strong> {!! $reason_code !!}</p>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="button" id="approve" class="btn btn-success" data-value="APPROVED">
                        <i class="bi bi-check-circle me-2"></i> APPROVE
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
@push('scripts')

<script type="text/javascript">
	
$(document).ready(function() {
    $("#approve").click(function(){
        var id = $(".adjustment_id").val();
        var date = $(".adjustment_date").val();
        var account = $(".account_code_id").val();
        var amount = $(".adjustment_amount").val();
        var account_type = $(".account_type").val();
        var reason = $(".reason_code").val();
        var status = $(this).data('value');

        if(id) {
            var url = "{{ URL::to('adjustmentsapproval') }}/"+id+"/"+status+"/"+date+"?account="+account+"&amount="+amount+"&account_type="+account_type+"&reason="+reason;
            $.get(url, function(data){
                var status = data.status;
                var msg = data.message;
                showCustomAlert(msg,status);
                setTimeout(function() {
                    window.location.href = "{{ url('adjustmentapproval') }}";
                }, 1500);
            });
        } else {
            showCustomAlert("info", "Please Select Row");
        }
    });
});
	
</script>

@endpush
