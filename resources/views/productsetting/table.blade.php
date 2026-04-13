@extends('layouts.header')
@section('content')
<h2 class="text-danger">Product Setting</h2>
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

<form  action=""  id="pro_form" >

{{ csrf_field() }}
  <input type="hidden" value="" name="savestatus" id="savestatus" />

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="row g-4">

        @php
          $modules = [
            'purchaseenquiry' => 'PURCHASE ENQUIRY',
            'purchaserequisition' => 'PURCHASE REQUISITION',
            'purchasequotation' => 'PURCHASE QUOTATION',
            'purchasecommitment' => 'PURCHASE COMMITMENT',
            'purchaseorder' => 'PURCHASE ORDER',
            'goodsinwardnote' => 'GIN',
            'grn' => 'GRN',
            'purchaseqc' => 'QUALITY CHECK',
            'purchaseinvoice' => 'PURCHASE INVOICE',
            'purchasereturn' => 'PURCHASE RETURN',
            'salesinquiry' => 'SALES ENQUIRY',
            'soquote' => 'SALES QUOTE',
            'soorder' => 'SALES ORDER',
            'salesinvoice' => 'SALES INVOICE',
            'pickorder' => 'PICKORDER',
            'dispatch' => 'DISPATCH',
            'bomequipments' => 'BOM EQUIPMENTS',
            'materialbom' => 'MATERIAL BOM',
            'workorder' => 'WORKORDER',
            'qasubmitstage' => 'QA SUBMIT STAGE',
            'purchasepricelist' => 'PURCHASE PRICELIST',
            'salespricelist' => 'SALES PRICELIST',
            'qualityindent' => 'QUALITY INDENT',
            'jobworkoutorder' => 'JOBWORKOUT ORDER',
            'closerequest' => 'CLOSE TICKET',
            'depreciationmethod' => 'DEPRECIATION METHOD',
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

        <!--Config Modal -->
<div class="modal fade" id="config_modal" tabindex="-1" aria-labelledby="config_modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="config_modalLabel">Configuration</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <div id="form_body">

          <div class="row mb-3">
            <label class="col-md-5 col-form-label" for="product_group_id">
              <span class="text-danger">*</span> Product Group Name
            </label>
            <div class="col-md-7">
              <select multiple name="product_group_id[]" id="product_group_id" class="form-select select2 product_group_id" readonly>
                {!! $product_group_id !!}
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-md-5 col-form-label" for="select_option">
              <span class="text-danger">*</span> Select Option
            </label>
            <div class="col-md-7">
              <select multiple name="select_option[]" id="select_option" class="form-select select2 select_option">
                <option value="">--Please Select--</option>
                <option value="product_code" {{ $row->select_option == 'product_code' ? 'selected' : '' }}>PRODUCT CODE</option>
                <option value="concatenated_product" {{ $row->select_option == 'concatenated_product' ? 'selected' : '' }}>PRODUCT NAME</option>
              </select>
            </div>
          </div>

          <input type="hidden" name="type" class="type" value="">

        </div>
      </div>

      <div class="modal-footer justify-content-center">
        <button name="submit" type="button" class="btn btn-primary saveform me-2 " value="SAVE">OK</button>
      </div>

    </div>
  </div>
</div>

 <!--Config Modal -->
</form>

@endsection
@push('scripts')

<script>
$( document ).ready(function() {
     $('#savestatus').val('');
    $('.config').click(function(e) {
        var type=$(this).data('value');
         $('.type').val(type);
        $.get('getproductsetting?type='+type,function(data){
            console.log(data);
           if (data != 0) {
                var grouparray=data[0].product_group_id.split(",");
                var grouparray1=data[0].select_option.split(",");
		$('.product_group_id').val(grouparray);
                $('.select_option').val(grouparray1);
		$('.product_group_id').trigger('change.select2');
                $('.select_option').trigger('change.select2');
            }
        });
         $('#config_modal').modal('show');
    });

});
	
	    // Save Form
$(document).on('click', '.saveform', function () {
    const form = $("#pro_form");
    let dup_chk = true; // Declare duplicate check variable

    form.parsley().validate(); // Validate form using Parsley

    if (form.parsley().isValid() && dup_chk === true) {
        const formData = form.serialize(); 

        $.ajax({
            url: "{{ url('productsettingsave') }}",
            type: "POST",
            data: formData,
            success: function (response) {
                if (response.status === "success") {
                    showCustomAlert(response.message || 'Saved successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = "{{ url('productsetting') }}";
                    }, 1500);
                } else {
                    showCustomAlert(response.message || 'Save failed. Please check your input.', 'error');
                }
            },
            error: function (xhr) {
                let errorMsg = 'Unexpected error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showCustomAlert(errorMsg, 'error');
            }
        });
    } else {
        showCustomAlert("Please fill out all required fields correctly.", 'warning');
    }
});
</script>

@endpush
