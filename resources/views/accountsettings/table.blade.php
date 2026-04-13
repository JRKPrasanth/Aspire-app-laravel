@extends('layouts.header')
@section('content')
<h2 class="text-danger">Account Setting</h2>
@include('layouts.breadcrumb')
<?php error_reporting(0);?>

<style type="text/css">
   .select2-container--default .select2-selection--multiple {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    padding: 0.25rem;
    min-height: 38px;
    background-color: #fff;
  }
  .select2-selection__rendered {
    font-size: 13px;
  }
	.config {
	background:#ffccfd;	
	}
</style>

<form  action=""  id="acc_form" >

{{ csrf_field() }}
  <input type="hidden" value="" name="savestatus" id="savestatus" />

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="row g-4">

        @php
          $modules = [
            'hrms' => 'HRMS',
            'grn' => 'GRN',
            'srn' => 'SRN',
            'reversechargeaccount' => 'REVERSE CHARGE ACCOUNT',
            'purchaseinvoice' => 'PURCHASE INVOICE',
            'salesinvoice' => 'SALES INVOICE',
            'production' => 'PRODUCTION CONTROL',
            'salesaccount' => 'SALES ACCOUNT',
            'cashaccount' => 'CASH ACCOUNT',
            'roundoff' => 'ROUND OFF ACCOUNT',
          ];
        @endphp

        @foreach($modules as $key => $label)
          <div class="col-md-4">
            <div class="border rounded p-3 h-100 bg-white shadow-sm position-relative">
              <h6 class="mb-0 text-uppercase text-primary fw-bold" style="font-size: 14px;">
                {{ $label }}
                <a href="javascript:void(0)" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2 config" data-value="{{ $key }}">
                  <i class="fa fa-cogs"></i>
                </a>
              </h6>
            </div>
          </div>
        @endforeach

      </div>
    </div>
  </div>


<!-- popups -->	
<!-- Sales Config Modal -->
<div class="modal fade" id="configsales_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4">
      
      <!-- Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Sales Configuration</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="row g-3">

          <div class="col-md-12">
            <label for="sample_account_id" class="form-label">
              <span class="text-danger">*</span> Sample Account
            </label>
            <select name="sample_account_id[]" id="sample_account_id" class="form-select select2">
              {!! $sample_account_id !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="domestic_account_id" class="form-label">
              <span class="text-danger">*</span> Trade Account
            </label>
            <select name="domestic_account_id[]" id="domestic_account_id" class="form-select select2">
              {!! $sample_account_id !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="export_account_id" class="form-label">
              <span class="text-danger">*</span> Export Account
            </label>
            <select name="export_account_id[]" id="export_account_id" class="form-select select2" required>
              {!! $export_account_id !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="cogs_account_id" class="form-label">
              <span class="text-danger">*</span> COGS Account
            </label>
            <select name="cogs_account_id[]" id="cogs_account_id" class="form-select select2" required>
              {!! $cogs_account_id !!}
            </select>
          </div>

        </div>
        <input type="hidden" name="type" class="type" value="">
      </div>

      <!-- Footer -->
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-success px-4 saveform">Save</button>
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>


<!-- Round-off Config Modal -->
<div class="modal fade" id="configround_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4">
      
      <!-- Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Round-off Configuration</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <div class="mb-3">
          <label for="roundoff_account_id" class="form-label">
            <span class="text-danger">*</span> Cash Account
          </label>
          <select name="roundoff_account_id[]" id="roundoff_account_id" class="form-select select2" required>
            {!! $roundoff_account_id !!}
          </select>
        </div>
        <input type="hidden" name="type" class="type" value="">
      </div>

      <!-- Footer -->
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-success px-4 saveform">Save</button>
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>
	
	
<!-- Production Config Modal -->
<div class="modal fade" id="config_production" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Production Configuration</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">

          <div class="col-md-12">
            <label for="production" class="form-label"><span class="text-danger">*</span> Production Control Account</label>
            <select name="production[]" id="production" class="form-select select2" required>
              {!! $production !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="wip" class="form-label"><span class="text-danger">*</span> WIP Control Account</label>
            <select name="wip[]" id="wip" class="form-select select2" required>
              {!! $wip !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="scrab" class="form-label"><span class="text-danger">*</span> Scrap Control Account</label>
            <select name="scrab[]" id="scrab" class="form-select select2" required>
              {!! $scrab !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="rework" class="form-label"><span class="text-danger">*</span> Rework Control Account</label>
            <select name="rework[]" id="rework" class="form-select select2" required>
              {!! $rework !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="labour" class="form-label"><span class="text-danger">*</span> Production Labour Account</label>
            <select name="labour[]" id="labour" class="form-select select2" required>
              {!! $labour !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="eletricity" class="form-label"><span class="text-danger">*</span> Production Electricity Account</label>
            <select name="eletricity[]" id="eletricity" class="form-select select2" required>
              {!! $eletricity !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="packingcontrol_accid" class="form-label"><span class="text-danger">*</span> Packing Control Account</label>
            <select name="packingcontrol_accid[]" id="packingcontrol_accid" class="form-select select2" required>
              {!! $packingcontrol_accid !!}
            </select>
          </div>

        </div>
      </div>

      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-success px-4 saveform">Save</button>
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>


<!-- General Config Modal -->
<div class="modal fade" id="config_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">General Configuration</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">

          <div class="col-md-12">
            <label for="otherfreight_acccode_id" class="form-label"><span class="text-danger">*</span> Other Freight Amount Account</label>
            <select name="otherfreight_acccode_id[]" id="otherfreight_acccode_id" class="form-select select2" required>
              {!! $otherfreight_acccode_id !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="othertax_acccode_id" class="form-label"><span class="text-danger">*</span> Other Tax Amount Account</label>
            <select name="othertax_acccode_id[]" id="othertax_acccode_id" class="form-select select2" required>
              {!! $othertax_acccode_id !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="transport_acccode_id" class="form-label"><span class="text-danger">*</span> Transport Charges Account</label>
            <select name="transport_acccode_id[]" id="transport_acccode_id" class="form-select select2" required>
              {!! $transport_acccode_id !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="insurance_acccode_id" class="form-label"><span class="text-danger">*</span> Insurance Charges Account</label>
            <select name="insurance_acccode_id[]" id="insurance_acccode_id" class="form-select select2" required>
              {!! $insurance_acccode_id !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="packaging_acccode_id" class="form-label"><span class="text-danger">*</span> Packaging Charges Account</label>
            <select name="packaging_acccode_id[]" id="packaging_acccode_id" class="form-select select2" required>
              {!! $packaging_acccode_id !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="unloading_acccode_id" class="form-label"><span class="text-danger">*</span> Unloading Charges Account</label>
            <select name="unloading_acccode_id[]" id="unloading_acccode_id" class="form-select select2" required>
              {!! $unloading_acccode_id !!}
            </select>
          </div>

        </div>
      </div>

      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-success px-4 saveform">Save</button>
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>


<!-- HRMS Config Modal -->
<div class="modal fade" id="confighrms_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">HRMS Configuration</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">

          <div class="col-md-12">
            <label for="travelclaim_account_id" class="form-label"><span class="text-danger">*</span> Travel Claim Account</label>
            <select name="travelclaim_account_id[]" id="travelclaim_account_id" class="form-select select2">
              {!! $travelclaim_account_id !!}
            </select>
          </div>

          <div class="col-md-12">
            <label for="imprest_account_id" class="form-label"><span class="text-danger">*</span> Imprest Account</label>
            <select name="imprest_account_id[]" id="imprest_account_id" class="form-select select2">
              {!! $imprest_account_id !!}
            </select>
          </div>

        </div>
      </div>

      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-success px-4 saveform">Save</button>
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>
	
	
<!-- Inventory Modal -->
<div class="modal fade" id="configgrn_modal" tabindex="-1" aria-labelledby="configgrnLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="configgrnLabel">Configuration</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 row">
          <label for="inventory_account_id" class="col-sm-5 col-form-label">
            <span class="text-danger">*</span> Inventory Account
          </label>
          <div class="col-sm-7">
            <select name="inventory_account_id[]" id="inventory_account_id" class="form-select select2" disabled>
              {!! $inventory_account_id !!}
            </select>
          </div>
        </div>
        <input type="hidden" name="type" class="type" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary saveform">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Service Modal -->
<div class="modal fade" id="configsrn_modal" tabindex="-1" aria-labelledby="configsrnLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="configsrnLabel">Configuration</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 row">
          <label for="service_account_id" class="col-sm-5 col-form-label">
            <span class="text-danger">*</span> Service Account
          </label>
          <div class="col-sm-7">
            <select name="service_account_id[]" id="service_account_id" class="form-select select2" disabled>
              {!! $service_account_id !!}
            </select>
          </div>
        </div>
        <input type="hidden" name="type" class="type" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary saveform">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Cash Modal -->
<div class="modal fade" id="configcash_modal" tabindex="-1" aria-labelledby="configcashLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="configcashLabel">Configuration</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 row">
          <label for="cash_account_id" class="col-sm-5 col-form-label">
            <span class="text-danger">*</span> Round Off Account
          </label>
          <div class="col-sm-7">
            <select name="cash_account_id[]" id="cash_account_id" class="form-select select2" required>
              {!! $cash_account_id !!}
            </select>
          </div>
        </div>
        <input type="hidden" name="type" class="type" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary saveform">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Reverse Modal -->
<div class="modal fade" id="configreverse_modal" tabindex="-1" aria-labelledby="configreverseLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="configreverseLabel">Configuration</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 row">
          <label for="revcharge_acccode_id" class="col-sm-5 col-form-label">
            <span class="text-danger">*</span> Reverse Account
          </label>
          <div class="col-sm-7">
            <select name="revcharge_acccode_id[]" id="revcharge_acccode_id" class="form-select select2" required>
              {!! $revcharge_acccode_id !!}
            </select>
          </div>
        </div>
        <input type="hidden" name="type" class="type" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary saveform">OK</button>
      </div>
    </div>
  </div>
</div>
	


 
</form>


@endsection
@push('scripts')


<script type="text/javascript">
$( document ).ready(function() {

     $('#savestatus').val('');
     
    $('.config').click(function(e) {
        var type=$(this).data('value');
         $('.type').val(type);
        $.get('getaccountsetting?type='+type,function(data){
           
           if (data != 0) {
                $('.otherfreight_acccode_id').val(data[0].otherfreight_acccode_id).change();
                $('.othertax_acccode_id').val(data[0].othertax_acccode_id).change();
                $('.transport_acccode_id').val(data[0].transport_acccode_id).change();
                $('.insurance_acccode_id').val(data[0].insurance_acccode_id).change();
                $('.packaging_acccode_id').val(data[0].packaging_acccode_id).change();                
                $('.unloading_acccode_id').val(data[0].unloading_acccode_id).change();                

            }
        });
         $('#config_modal').modal('show');
    });



 $('.configproduction').click(function(e) {
     var type=$(this).data('value');
         $('.type').val(type);

     $.get('getaccountsetting?type='+type,function(data){
           
           if (data != 0) {
                $('.production').val(data[0].production).change();
                $('.wip').val(data[0].wip).change();
                $('.scrab').val(data[0].scrab).change();
                $('.rework').val(data[0].rework).change();
                $('.packingcontrol_accid').val(data[0].packingcontrol_accid).change();
				$('.labour').val(data[0].labour).change();
				$('.eletricity').val(data[0].eletricity).change();
                          

            }
        });

         $('#config_production').modal('show');
    });



  $('.configsales').click(function(e) {
        var type=$(this).data('value');
         $('.type').val(type);
        $.get('getaccountsetting?type='+type,function(data){
           if (data != 0) {
                $('.export_account_id').val(data[0].export_account_id).change();
                $('.sample_account_id').val(data[0].sample_account_id).change();
                $('.domestic_account_id').val(data[0].domestic_account_id).change();
                $('.cogs_account_id').val(data[0].cogs_account_id).change();
                
            }
        });
         $('#configsales_modal').modal('show');
    });
    
    $('.configgrn').click(function(e) {
        var type=$(this).data('value');
         $('.type').val(type);
        $.get('getaccountsetting?type='+type,function(data){
             console.log(data);
           if (data != 0) {
                $('.inventory_account_id').val(data[0].inventory_account_id).change();
            }
        });
         $('#configgrn_modal').modal('show');
    });
    
 $('.configsrn').click(function(e) {
        var type=$(this).data('value');
         $('.type').val(type);
        $.get('getaccountsetting?type='+type,function(data){
             console.log(data);
           if (data != 0) {
                $('.service_account_id').val(data[0].service_account_id).change();
            }
        });
         $('#configsrn_modal').modal('show');
    });   
     $('.confighrms').click(function(e) {
        var type=$(this).data('value');
         $('.type').val(type);
        $.get('getaccountsetting?type='+type,function(data){
           if (data != 0) {
                $('.travelclaim_account_id').val(data[0].travelclaim_account_id).change();
                $('.imprest_account_id').val(data[0].imprest_account_id).change();
            }
        });
         $('#confighrms_modal').modal('show');
    });
   $('.configcash_modal').click(function(e) {
        var type=$(this).data('value');
        $('.type').val(type);
        $.get('getaccountsetting?type='+type,function(data){
        if (data != 0) {
                $('.cash_account_id').val(data[0].cash_account_id).change();
            }
        });
         $('#configcash_modal').modal('show');
    });  
    
    $('.configround_modal').click(function(e) {
        var type=$(this).data('value');
        $('.type').val(type);
        $.get('getaccountsetting?type='+type,function(data){
        if (data != 0) {
                $('.roundoff_account_id').val(data[0].roundoff_account_id).change();
            }
        });
         $('#configround_modal').modal('show');
    });   
    
       $('.configreverse_modal').click(function(e) {
        var type=$(this).data('value');
        $('.type').val(type);
        $.get('getaccountsetting?type='+type,function(data){
        if (data != 0) {
                $('.revcharge_acccode_id').val(data[0].revcharge_acccode_id).change();
            }
        });
         $('#configreverse_modal').modal('show');
    });  
    
    $('.config').click(function(e) {
         var type=$(this).data('value');
         if(type=="salesinvoice"){
             $('.unloading').hide();
         }else{
             $('.unloading').show();
         }
    });
    
		  $(document).on('click','.saveform',function(e){
                e.preventDefault();
                var data;
                data = $("#save").serialize();
                $.post('accountsettingsave', data, function(data)
                {

                 showCustomAlert("Account Settings Saved Successfully !!","success");

                });
               $('#config_modal').modal('hide');
               $('#configgrn_modal').modal('hide');
               $('#configcash_modal').modal('hide');
               $('#configreverse_modal').modal('hide');
               $('#confighrms_modal').modal('hide');
               $('#config_production').modal('hide');
                $('#configsales_modal').modal('hide');
                 $('#configsrn_modal').modal('hide');
                 $('#configround_modal').modal('hide');

            });


});
    </script>

@endpush
