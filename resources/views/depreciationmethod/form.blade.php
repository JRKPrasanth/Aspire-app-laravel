@extends ('layouts.header')
@section('content')
<h3 class="text-danger"> Depreciation Method</h3>
@include('layouts.breadcrumb')


<form method="post" action="" id="depreciation_form" data-parsley-validate>
    {{ csrf_field() }}
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white py-3">
        </div>

        <div class="card-body">
            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-md-6">
                    <input type="hidden" id="depreciation_method_id" name="depreciation_method_id" value="{{ $row->depreciation_method_id }}">

                    <div class="mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> Depreciation Method Name
                        </label>
                        <select name="depreciation_method_name" class="form-select select2" required>
                            {!! $depreciation_method_name !!}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Asset Type Name</label>
                        <select name="asset_type_id" class="form-select select2">
                            {!! $asset_type_id !!}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Asset Category Name</label>
                        <select name="asset_category_id" class="form-select select2">
                            {!! $asset_category_id !!}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> PO Number
                        </label>
                        <select name="po_hdr_id" id="po_hdr_id" class="form-select select2 chckclick" required>
                            {!! $po_hdr_id !!}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <select name="product_id" class="form-select select2">
                            {!! $product_id !!}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> Unit Price
                        </label>
                        <input type="text" id="unit_price" name="unit_price" class="form-control" value="{{ $row->unit_price }}" required>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> Salvage
                        </label>
                        <div class="d-flex gap-4 mt-1">
                            <div class="form-check">
                                <input class="form-check-input salvage" type="radio" name="salvage" value="Yes" id="salvage_yes" 
                                    {{ $row->salvage == 'Yes' ? 'checked' : '' }}>
                                <label class="form-check-label" for="salvage_yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input salvage" type="radio" name="salvage" value="No" id="salvage_no"
                                    {{ $row->salvage == 'No' ? 'checked' : '' }}>
                                <label class="form-check-label" for="salvage_no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Salvage Percentage</label>
                        <input type="text" id="salvage_percentage" name="salvage_percentage" class="form-control" value="{{ $row->salvage_percentage }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> Useful Life
                        </label>
                        <input type="text" id="useful_life" name="useful_life" class="form-control" value="{{ $row->useful_life }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Salvage Value</label>
                        <input type="text" id="salvage_value" name="salvage_value" class="form-control" value="{{ $row->salvage_value }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Depreciable Base</label>
                        <input type="text" id="depreciable_base" name="depreciable_base" class="form-control" value="{{ $row->depreciable_base }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <span class="text-danger">*</span> Annual Depreciation Expense
                        </label>
                        <input type="text" id="depreciation_value" name="depreciation_value" class="form-control" value="{{ $row->depreciation_value }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Created By</label>
                        <select name="created_by" class="form-select select2" id="created_by" style="pointer-events:none;">
                            {!! $created_by !!}
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-center bg-light py-3">
            <input type="hidden" name="submit_type" class="submit_type" value="">
            <button type="button" class="btn btn-success px-4 saveform">
                <i class="bi bi-check-circle"></i> Save
            </button>
            <a href="{{ url($pageModule) }}" class="btn btn-secondary px-4 ms-2">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
        </div>
    </div>
</form>



@endsection
@push('scripts')

<script>

    $(document).ready(function(){

        /*Validation*/
        $(document).on('keypress', '.useful_life,.salvage_percentage,.unit_price,.salvage_value,.depreciable_base,.depreciation_value', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });
    /*End*/

    $('.salvage_percentage,.useful_life').bind("cut copy paste", function(e) {
        e.preventDefault();
            });
    /*copy paste validation*/
    $(document).on('keyup change', '.salvage_percentage,.useful_life', function() {
         var $price=$('.unit_price').val();
    var  $life=$('.useful_life').val();
    var $sal_perc=$('.salvage_percentage').val();
    var $sal_rate=$sal_perc/100;

    var   $salvage_val = $price*Math.pow(1-$sal_rate,$life);

    $('.salvage_value').val($salvage_val.toFixed(2));
    var $productprice=($price-$salvage_val)/$life;

    $productprice = $productprice?$productprice:0;

    $('.depreciation_value').val($productprice.toFixed(2));

    var radio=$('input[name=salvage]:checked').val();
    if(radio=="Yes"){
        	 var depreciable_base=$price-$salvage_val;
    depreciable_base = depreciable_base?depreciable_base:0;

    $('.depreciable_base').val(depreciable_base.toFixed(2));
            }
    else{
         var depreciation=$price/$life;
    depreciation = depreciation?depreciation:0;

    $('.depreciable_base').val(depreciation);
        }
       
    });


    $(document).on('change', '.salvage', function() {
        var radio=$('input[name=salvage]:checked').val();
    if(radio=="Yes"){
        $('.deprctn_prct').show();
            }
    else{
        $('.deprctn_prct').hide();
        }
    });

    //rohini purpose to change 
    $(document).on('change','.asset_type_id',function(){
			var asset_type_id=$('.asset_type_id').select2('val');
    var condition = ' asset_type_id='+asset_type_id;
    if(asset_type_id != ''){
        $(".asset_category_id").jCombo("{{ URL::to('jcomboform?table=f_asset_category_t:asset_category_id:asset_category_name') }}&order_by=asset_category_name asc" + '&parent=' + condition,
            { selected_value: "" });
			}
                       
			 
		});

    /* purpose to load po based product*/
    $(document).on('change','.po_hdr_id',function(){
    var poid=$('.po_hdr_id').val();
    var url="{{ URL::to('getpodetails')}}/"+poid;
    $.get(url,function(data){
        $('.product_id').html(data);
        });
});
    /*End*/
    /* purpose to load product based Price*/
    $(document).on('change','.product_id',function(){
     var prdid=$('.product_id').val();
    var url="{{ URL::to('getprice')}}/"+prdid;
    $.get(url,function(data){
         if(data!=''){
        $('.unit_price').val(data[0].unit_price);   
         }
    else{
        $('.unit_price').val('');   
         }
     });
});

    /* Purpose For Save Function*/

    $(document).on('click', '.saveform', function() {
    var btnval = $(this).val();
    $('#savestatus').val(btnval);
    var url = "{{ url('depreciationmethodsave') }}";
    var red_url = "{{ url('depreciationmethod') }}"

    var formdata = $('#depreciation_form').serialize();
    validationrule('depreciation_form');
    var form = $('#depreciation_form');
    form.parsley().validate();
    if (form.parsley().isValid())
    {

            var formdata	= $('#depreciation_form').serialize();

    $.post(url, formdata, function(data)
    {
            var status = data.status;
    var msg =  data.message;
    var id = data.id;
    var edit_url = "{{ url('depreciationmethodcreate') }}/" + id;
    if (btnval != 'SAVE' && btnval != 'DRAFT')
    {
        showCustomAlert(msg, status);
    setTimeout(function(){
        window.location.href = red_url;
            }, 1500);
            }
    else
    {
        showCustomAlert(msg, status);
    setTimeout(function(){
        window.location.href = red_url;
            }, 1500);
            }
            });
            }
    });
    });

</script>

@endpush
