@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Product Category</h3>
    @include('layouts.breadcrumb')

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white fw-semibold"> </div>

        <div class="card-body">
            <form id="prdcatsave" method="post" action="" data-parsley-validate>
                <?php $data = \Session::get('data');
    if (isset($data[$pageMethod]['save'])) { ?>
                {{ csrf_field() }}
                <input type="hidden" value="" name="savestatus" id="savestatus" />
                <input type="hidden" name="edit_id" value="{{ $row->product_category_id }}" id="edit_id" />

                <div class="row g-4">
                    <!-- Left Side -->
                    <div class="col-md-6">

                        <!-- Product Group Name -->
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

                        <!-- Product Category Name -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-5 col-form-label fw-semibold">
                                <span class="text-danger">*</span> Product Category Name
                            </label>
                            <div class="col-md-7">
                                <input type="text" name="category_name" id="category_name" value="{{ $row->category_name }}"
                                    class="form-control category_name" tabindex="3" required>
                                <span class="badge bg-danger mt-2 dup_name d-none"></span>
                            </div>
                        </div>

                        <!-- Created By -->
                        <div class="row mb-3 align-items-center none">
                            <label class="col-md-5 col-form-label fw-semibold">Created By</label>
                            <div class="col-md-7">
                                <select name="created_by" class="form-select select2 created_by" id="created_by">
                                    {!! $created_by !!}
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="col-md-6">

                        <!-- Description -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-5 col-form-label fw-semibold">Description</label>
                            <div class="col-md-7">
                                <input type="text" id="description" name="description" class="form-control description"
                                    value="{{ $row->description }}" tabindex="2">
                            </div>
                        </div>

                        <!-- Active -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-5 col-form-label fw-semibold">Active</label>
                            <div class="col-md-7">
                                <select name="active" class="form-select select2 active" tabindex="4">
                                    <option value="Yes" <?php    if ($row->active == 'Yes') {
            echo "selected";
        } ?>>Yes</option>
                                    <option value="No" <?php    if ($row->active == 'No') {
            echo "selected";
        } ?>>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="row mt-4">
                    <div class="col text-center">
                        <button type="button" id="save" class="btn btn-success px-4  saveform">
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
                        <th>Group Name</th>
                        <th>Category Name</th>
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
                ajax: "{{ route('getProductcategory') }}",
                columns: [
                    { data: 'group_id', name: 'group_id', visible: false },
                    { data: 'group_name', name: 'group_name' },
                    { data: 'category_name', name: 'category_name' },
                    { data: 'description', name: 'description' },
                    { data: 'active', name: 'active' },
                    { data: 'first_name', name: 'first_name' },

                    {
                        data: 'product_category_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',

                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `<button class="btn btn-sm btn-info me-1 edit-btn" 
                  data-id="${row.product_category_id}" 
                  data-grp="${row.group_id}" 
                  data-name="${row.group_name}" 
                  data-code="${row.description}" 
                  data-fname="${row.first_name}" 
                  data-cname="${row.category_name}" 
                  data-active="${row.active}">
                  <i class="bi bi-pencil"></i>
                </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `
                <button class="btn btn-sm btn-danger delete-btn" data-id="${row.product_category_id}">
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



        // purpose:To check Duplicate entry

        var dup_chk = true;
        function duplicate_validate() {
            var category_name = $(".category_name").val();
            var product_group_id = $('.product_group_id').val();

            var edit_id = $("#edit_id").val();

            $.ajax({
                cache: false,
                url: "{{ URL::to('productcategorycheckname') }}", /*this is your uri*/
                type: 'GET',
                dataType: 'json',
                async: false,
                data: { category_name: category_name, product_group_id: product_group_id, edit_id: edit_id },
                success: function (response) {
                    console.log(response);
                    if (response == 1) {
                        $('.dup_name').html('Category Name:' + category_name + ' Already Exists for this Group');
                        $('.dup_name').show();
                        $(".category_name").val('');
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



        $(document).on('click', '.saveform', function () {
            var form = $("#prdcatsave");
            form.parsley();
            duplicate_validate();
            $('input[name="_token"]').val("{{csrf_token()}}");
            var data = form.serialize();

            form.parsley().validate();
            if (form.parsley().isValid()) {
                var $btn = $(this);
                $btn.prop('disabled', true);
                var url = "{{ URL::to('productcategorysave')}}";
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


        $('.category_name').on('keyup', function () {
            this.value = this.value.toUpperCase();
            $('.dup_name').hide();
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
                    url: "{{ url('productcategorydelete') }}/" + deleteId,
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
            const cname = $(this).data('cname');
            const active = $(this).data('active');
            const grp = $(this).data('grp');

            var url = "{{URL::to('productcategoryeditchk')}}/?id=" + id;

            $.get(url, function (data) {

                var data = $.trim(data);

                if (data == 1) {

                    showCustomAlert("You Can't be Edit this Category Name, Already used.", 'warning');

                } else {


                    // Fill form fields
                    $('input[name="edit_id"]').val(id);
                    $('input[name="description"]').val(code);
                    $('input[name="category_name"]').val(cname);
                    $('input[name="group_name"]').val(name);

                    // For select2 fields, use .val().trigger('change')
                    $('select[name="active"]').val(active).trigger('change');
                    $('select[name="product_group_id"]').val(grp).trigger('change');

                }

            });
        });


    </script>

@endpush