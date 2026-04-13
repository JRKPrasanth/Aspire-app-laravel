@extends('layouts.header')
@section('content')
<h3 class="text-danger">Product Subcategory</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold"></div>

    <div class="card-body">
        <form id="prdsubcat" method="post" action="" data-parsley-validate>
            <?php $data = \Session::get('data'); if(isset($data[$pageMethod]['save'])) { ?>
            {{ csrf_field() }}
            <input type="hidden" value="" name="savestatus" id="savestatus" />
            <input type="hidden" name="edit_id" value="" id="edit_id" />

            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-md-6">

                    <!-- Product Group -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-md-5 col-form-label fw-semibold">
                            <span class="text-danger">*</span> Product Group Name
                        </label>
                        <div class="col-md-7">
                            <select name="product_group_id" class="form-select select2 product_group_id"
                                data-show-subtext="true" data-live-search="true" required tabindex="1">
                                {!! $product_group_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Product Category -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-md-5 col-form-label fw-semibold">
                            <span class="text-danger">*</span> Product Category Name
                        </label>
                        <div class="col-md-7">
                            <select name="product_category_id" class="form-select select2 product_category_id"
                                data-show-subtext="true" data-live-search="true" required tabindex="2">
                                {!! $product_category_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- SubCategory Name -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-md-5 col-form-label fw-semibold">
                            <span class="text-danger">*</span> Product SubCategory Name
                        </label>
                        <div class="col-md-7">
                            <input type="text" id="subcategory_name" name="subcategory_name"
                                class="form-control subcategory_name" value="" required tabindex="3">
                            <span class="badge bg-danger mt-2 dup_name d-none"></span>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">

                    <!-- Description -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-md-5 col-form-label fw-semibold">Description</label>
                        <div class="col-md-7">
                            <input type="text" id="description" name="description" class="form-control description"
                                value="" tabindex="4">
                        </div>
                    </div>

                    <!-- Active -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-md-5 col-form-label fw-semibold">Active</label>
                        <div class="col-md-7">
                            <select name="active" tabindex="5" class="form-select select2 active">
                                <option value="Yes" selected>Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Created By -->
                    <div class="row mb-3 align-items-center none">
                        <label class="col-md-5 col-form-label fw-semibold">Created By</label>
                        <div class="col-md-7">
                            <select name="created_by" tabindex="6" class="form-select select2 created_by"
                                id="created_by">
                                {!! $created_by !!}
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="row mt-4">
                <div class="col text-center">
                    <button type="button" class="btn btn-success px-4 saveform">
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
                    <th>Group</th>
                    <th>Group</th>
                    <th>Group Name</th>
                    <th>Category Name</th>
                    <th>Sub Category Name</th>
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
            ajax: "{{ route('getProductsubcategoryData') }}",
            columns: [
                { data: 'prdgrpid', name: 'prdgrpid', visible: false },
                { data: 'prdcat', name: 'prdcat', visible: false },
                { data: 'group_name', name: 'group_name' },
                { data: 'category_name', name: 'category_name' },
                { data: 'subcategory_name', name: 'subcategory_name' },
                { data: 'description', name: 'description' },
                { data: 'active', name: 'active' },
                { data: 'first_name', name: 'first_name' },

                {
                    data: 'product_subcategory_id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',

                    render: function (data, type, row) {
                        let buttons = '';
                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                            buttons += `<button class="btn btn-sm btn-info me-1 edit-btn" 
							  data-id="${row.product_subcategory_id}" 
							  data-grp="${row.prdgrpid}" 
							   data-grpcat="${row.prdcat}" 
							  data-name="${row.group_name}" 
							  data-code="${row.description}" 
							  data-fname="${row.first_name}" 
							  data-cname="${row.category_name}" 
							  data-sname="${row.subcategory_name}" 
							  data-active="${row.active}">
							  <i class="bi bi-pencil"></i>
							</button>`;
                        }
                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                            buttons += `
            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.product_subcategory_id}">
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


    /*Duplicate Validate Check*/
    var dup_chk = true;
    function duplicate_validate() {

        var product_group_id = $(".product_group_id").val();
        var product_category_id = $(".product_category_id").val();
        var subcategory_name = $(".subcategory_name").val();
        var edit_id = $("#edit_id").val();

        $.ajax({
            cache: false,
            url: "{{URL::to('/productsubcategorycheckname/')}}", //this is your uri
            type: 'GET',
            dataType: 'json',
            async: false,
            data: { product_group_id: product_group_id, product_category_id: product_category_id, subcategory_name: subcategory_name, edit_id: edit_id },
            success: function (response) {
                console.log(response);
                if (response == 1) {
                    $('.dup_name').html('Product Subcategory Name:' + subcategory_name + ' Already Exists');
                    $('.dup_name').show();
                    $(".subcategory_name").val('');
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



    $(document).on('change', '.product_group_id', function () {
        var prdgrp = $(this).val();
        var comp = '{{ \Session::get('companyid')}}';

        if (prdgrp != '') {
            var url = "{{ URL::to('jcomboform') }}?table=m_product_category_t:product_category_id:category_name"
                + "&order_by=category_name asc"
                + "&parent=product_group_id=" + prdgrp + " and company_id=" + comp;

            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    // Parse JSON if response is string
                    if (typeof data === "string") {
                        try {
                            data = JSON.parse(data);
                        } catch (e) {
                            console.error("Invalid JSON response:", data);
                            return;
                        }
                    }

                    $('.product_category_id').html('<option value="">-- Select Category --</option>');

                    $.each(data, function (i, item) {
                        let selected = item.val == "" ? 'selected' : '';
                        $('.product_category_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                    });

                    // If using select2
                    $('.product_category_id').trigger('change.select2');
                }
            });
        }
    });



    /*Save Function*/

    $(document).on('click', '.saveform', function () {
        var form = $("#prdsubcat");
        form.parsley();
        duplicate_validate();
        $('input[name="_token"]').val("{{csrf_token()}}");
        var data = form.serialize();

        form.parsley().validate();
        if (form.parsley().isValid()) {
            var $btn = $(this);            
			$btn.prop('disabled', true);
            var url = "{{ URL::to('productsubcategorysave')}}";
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


    $("#subcategory_name").keyup(function () {
        $('.dup_name').hide();
    });

    /*Purpose for Grid Data*/
    $('.subcategory_name').on('keyup', function () {
        this.value = this.value.toUpperCase();
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
                url: "{{ url('productsubcategorydelete') }}/" + deleteId,
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



    // edit function
    $(document).on('click', '.edit-btn', function () {

        const id = $(this).data('id');
        const code = $(this).data('code');
        const name = $(this).data('name');
        const grpcat = $(this).data('grpcat');
        const cname = $(this).data('cname');
        const sname = $(this).data('sname');
        const active = $(this).data('active');
        const grp = $(this).data('grp');

        var url = "{{URL::to('productcategoryeditchk')}}/?id=" + id;

        $.get(url, function (data) {

            var data = $.trim(data);

            if (data == 1) {

                showCustomAlert("Sub Category name already used some where.Unable to Edit.", 'warning');

            } else {


                // Fill form fields
                $('input[name="edit_id"]').val(id);
                $('input[name="description"]').val(code);
                $('input[name="category_name"]').val(cname);
                $('input[name="subcategory_name"]').val(sname);
                $('input[name="group_name"]').val(name);

                // For select2 fields, use .val().trigger('change')
                $('select[name="active"]').val(active).trigger('change');
                $('select[name="product_group_id"]').val(grp).trigger('change');
                $('select[name="product_category_id"]').val(grpcat).trigger('change');

            }

        });
    });

</script>

@endpush