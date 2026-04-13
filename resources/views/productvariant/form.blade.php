@extends('layouts.header')
@section('content')
<h3 class="text-danger">Product Variant</h3>
@include('layouts.breadcrumb')



<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold">
    </div>

    <div class="card-body">
        <form action="" id="variantForm" method="post" data-parsley-validate>
            <?php $data = \Session::get('data');
            if (isset($data[$pageMethod]['save'])) { ?>
                {{ csrf_field() }}

                <input type="hidden" id="product_variant_id" name="product_variant_id" value="">

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">

                        <!-- Variant Name -->
                        <div class="row mb-3 align-items-center">
                            <label for="product_variant_name" class="col-md-5 col-form-label fw-semibold">
                                <span class="text-danger">*</span> Variant Name
                            </label>
                            <div class="col-md-7">
                                <input type="text" id="product_variant_name" name="product_variant_name"
                                    class="form-control product_variant_name" value="" required>
                                <span class="badge bg-danger mt-2 dup_name d-none"></span>
                            </div>
                        </div>

                        <!-- Active -->
                        <div class="row mb-3 align-items-center">
                            <label for="active" class="col-md-5 col-form-label fw-semibold">Active</label>
                            <div class="col-md-7">
                                <select name="active" id="active" class="form-select select2">
                                    <option value="Yes" <?php if ($row->active == "Yes")
                                        echo "selected"; ?>>Yes</option>
                                    <option value="No" <?php if ($row->active == "No")
                                        echo "selected"; ?>>No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">

                        <!-- Description -->
                        <div class="row mb-3 align-items-center">
                            <label for="variant_description" class="col-md-5 col-form-label fw-semibold">Description</label>
                            <div class="col-md-7">
                                <input type="text" id="variant_description" name="variant_description"
                                    class="form-control variant_description" value="{{ $row->variant_description }}">
                            </div>
                        </div>

                        <!-- Created By -->
                        <div class="row mb-3 align-items-center none">
                            <label class="col-md-5 col-form-label fw-semibold">Created By</label>
                            <div class="col-md-7">
                                <select name="created_by" id="created_by" class="form-select select2 created_by">
                                    {!! $created_by !!}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="row mt-4">
                    <div class="col text-center">
                        <button type="button" id="save" class="btn btn-success px-4 saveform">
                            <i class="bi bi-check-circle me-2"></i> Save
                        </button>
                    </div>
                </div>
            <?php } ?>
        </form>
    </div>
</div>


<div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
        <table id="InvTbl" class="table table-bordered table-striped w-100">
            <thead>
                <tr class="table-warning">
                    <th>Variant Name</th>
                    <th>Description</th>
                    <th>Active</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
                <tr class="table-info">
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>

                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


@endsection
@push('scripts')


<script>

    // data table funcrion	

    $(document).ready(function () {
        var table = $('#InvTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('getproductvariantData') }}",
            columns: [
                { data: 'product_variant_name', name: 'product_variant_name' },
                { data: 'variant_description', name: 'variant_description' },
                { data: 'active', name: 'active' },
                { data: 'first_name', name: 'tb_users.first_name' },

                {
                    data: 'product_variant_id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',

                    render: function (data, type, row) {
                        let buttons = '';
                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                            buttons += `<button class="btn btn-sm btn-info me-1 edit-btn" 
              data-id="${row.product_variant_id}" 
              data-name="${row.product_variant_name}" 
              data-desc="${row.variant_description}" 
              data-active="${row.active}">
              <i class="bi bi-pencil"></i>
            </button>`;
                        }
                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                            buttons += `
            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.product_variant_id}">
              <i class="bi bi-trash"></i>
            </button>`;
                        }
                        return buttons;
                    }
                }
            ]
        });

        // Individual column search
        $('#InvTbl thead').on('keyup change', ".column-search", function () {
            var colIndex = $(this).parent().index();
            table.column(colIndex).search(this.value).draw();
        });
    });

    $(document).ready(function () {


        /*Duplicate Name Check*/
        var dup_chk = true;
        function duplicate_validate() {
            var product_variant_name = $(".product_variant_name").val();
            var product_variant_id = $("#product_variant_id").val();
            var url = "{{ URL::to('productvariantcheckname') }}";
            $.ajax({
                cache: false,
                url: url, /*this is your uri*/
                type: 'GET',
                dataType: 'json',
                async: false,
                data: { product_variant_name: product_variant_name, product_variant_id: product_variant_id },
                success: function (response) {
                    if (response == 1) {
                        $('.dup_name').html('Variant Name:' + product_variant_name + ' Already Exists');
                        $('.dup_name').show();
                        $(".product_variant_name").val('');
                        dup_chk = false;
                    }
                    else if (response == 0) {
                        var html = "";
                        $('.dup_name').hide();
                        dup_chk = true;
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                }
            });
        }
        /*End*/


        $('.product_variant_name').on('keyup', function () {
            this.value = this.value.toUpperCase();
        })

        $("#product_variant_name").keyup(function () {
            $('.dup_name').hide();
        });

        /*Save Function*/
        $(document).on('click', '.saveform', function () {
            var form = $("#variantForm");
            form.parsley();
            duplicate_validate();
            $('input[name="_token"]').val("{{csrf_token()}}");
            var data = form.serialize();
            form.parsley().validate();
            if (form.parsley().isValid()) {
                var $btn = $(this);            
			    $btn.prop('disabled', true);
                var url = "{{ URL::to('productvariantsave')}}";
                if (dup_chk == true) {
                    $.post(url, data, function (data1) {
                        var status = data1.status;
                        var msg = data1.message;
                        showCustomAlert(msg,"success");
                        form[0].reset();
                        // Reload DataTable
                        window.location.reload();

                    });
                }
            }
            return false;

        });


        // edit function
        $(document).on('click', '.edit-btn', function () {

            const id = $(this).data('id');
            const desc = $(this).data('desc');
            const name = $(this).data('name');
            const active = $(this).data('active');

            var url = "{{ url('productvarianteditchk') }}/" + id;

            $.get(url, function (data) {

                var data = $.trim(data);

                if (data == 1) {

                    showCustomAlert("Product Variant already used some where.Unable to Edit", 'warning');


                } else {

                    // Fill form fields
                    $('input[name="product_variant_id"]').val(id);
                    $('input[name="product_variant_name"]').val(name);
                    $('input[name="variant_description"]').val(desc);

                    // For select2 fields, use .val().trigger('change')
                    $('select[name="active"]').val(active).trigger('change');

                }

            });

        });


        // delete function
        let deleteId = null;

        $(document).on('click', '.delete-btn', function () {
            deleteId = $(this).data('id');
            $('#globalDeleteModal').modal('show');
        });

        $('#globalConfirmDeleteBtn').on('click', function () {
            if (deleteId) {
                $.ajax({
                    url: "{{ url('productvariantdelete') }}/" + deleteId,
                    type: "GET",
                    success: function (data) {
                        if (data == '0') {
                            $('#globalDeleteModal').modal('hide');
                            showCustomAlert('Deleted successfully!', 'success');
                            $('#InvTbl').DataTable().ajax.reload();
                        }
                        if (data == '1') {
                            $('#globalDeleteModal').modal('hide');
                            showCustomAlert("You Can't delete , Product Group Used in SomeWhere.", 'error');
                            $('#InvTbl').DataTable().ajax.reload();
                        }
                    },
                    error: function (xhr) {
                        $('#globalDeleteModal').modal('hide');
                        const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
                        showCustomAlert(errorMsg, 'error');
                    }
                });
            }
        });


    });	
</script>

@endpush