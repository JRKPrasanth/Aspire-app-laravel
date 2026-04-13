@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Breakdown Type</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <form action="" id="breaksave">
                @csrf
                <input type="hidden" name="edit_id" id="edit_id" value="">

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="breakdown_name" class="col-md-5 col-form-label">
                                <span class="text-danger">*</span> Breakdown Name
                            </label>
                            <div class="col-md-7">
                                <input type="hidden" class="form-control" id="breakdowntype_id" name="breakdowntype_id"
                                    value="" readonly>
                                <input type="text" id="breakdown_name" name="breakdown_name"
                                    class="form-control breakdown_name" required tabindex="1">
                                <span class="btn btn-danger dup_name mt-2 d-none"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="description" class="col-md-5 col-form-label">Description</label>
                            <div class="col-md-7">
                                <input type="text" id="description" name="description" class="form-control" tabindex="2">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-success saveform px-4" tabindex="6">Save</button>
                    </div>
                </div>
            </form>


        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
            </div>
            <div class="table-responsive">
                <table id="BreakTbl" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr class="table-warning">
                            <th>Breakdown Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                        <tr class="table-info">
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>


@endsection
@push('scripts')

    <script>

        $(document).ready(function () {

            var table = $('#BreakTbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('getbreakdowntypegridData') }}",
                columns: [
                    { data: 'breakdown_name', name: 'breakdown_name' },
                    { data: 'description', name: 'description' },

                    {
                        data: 'breakdowntype_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '140px',
                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
                        <button  class="btn btn-sm btn-info me-1 edit-btn" data-id="${data}"><i class="bi bi-pencil"></i></button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${data}" data-url="{{ url('companydelete') }}"><i class="bi bi-trash"></i>  </button>`;
                            }

                            return buttons;
                        }

                    }
                ]
            });

            // Individual column search
            $('#BreakTbl thead').on('keyup change', ".column-search", function () {
                var colIndex = $(this).parent().index();
                table.column(colIndex).search(this.value).draw();
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
                    url: "{{ url('bkdwntypedelete') }}/" + deleteId,
                    type: "GET",
                    success: function (response) {
                        $('#globalDeleteModal').modal('hide');
                        showCustomAlert('Deleted successfully!', 'success');
                        $('#BreakTbl').DataTable().ajax.reload();

                    },
                    error: function (xhr) {
                        $('#globalDeleteModal').modal('hide');
                        const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
                        showCustomAlert(errorMsg, 'error');
                    }
                });
            }
        });



        /* Purpose for Duplicate Function for Payment term Name*/

        function duplicate_validate() {

            var breakdown_name = $(".breakdown_name").val();
            var edit_id = $("#breakdowntype_id").val();
            var result = true;
            $.ajax({
                cache: false,
                url: 'breakdowntypechkname',
                type: 'GET',
                dataType: 'json',
                async: false,
                data: { breakdown_name: breakdown_name, edit_id: edit_id },
                success: function (response) {
                    if (response == 1) {
                        $('.dup_name').html('Breakdown Name:' + breakdown_name + ' Already Exists');
                        $('.dup_name').show();
                        $(".agency_name").val('');
                        result = false;

                    }
                    else if (response == 0) {
                        var html = "";
                        $('.dup_name').hide();
                        result = true;

                    }
                },
                error: function (xhr, status, error) {
                    console.log("Duplicate check error:", error);
                    result = false;
                }
            });

            return result;
        }
        /*end*/

        /* Purpose For Upper Case*/
        $('.breakdown_name').on('keyup', function () {
            this.value = this.value.toUpperCase();
            $('.dup_name').hide();
        });


        // Save Form
        $(document).on('click', '.saveform', function () {
            const form = $("#breaksave"); // change ID if needed
            form.parsley().validate();

            if (form.parsley().isValid()) {
                const isUnique = duplicate_validate();

                if (!isUnique) {
                    showCustomAlert("Already Exists", 'warning');
                    return;
                }

                const formData = form.serialize();
                var $btn = $(this);
                $btn.prop('disabled', true);

                $.ajax({
                    url: "{{ url('breakdowntype/save') }}",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.status === "success") {
                            showCustomAlert("Saved successfully!", "success");
                            form[0].reset();
                            $('#BreakTbl').DataTable().ajax.reload();
                        } else {
                            showCustomAlert("Save failed. " + (response.message || ""), "error");
                        }
                    },
                    
                    error: function () {
                        showCustomAlert("Unexpected error occurred.", "error");
                    }
                    window.location.reload();
                });
            } else {
                showCustomAlert("Please fill all required fields.", "warning");
            }
            
        });


        // edit function
        $(document).on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            const code = $(this).data('code');
            const name = $(this).data('name');
            const type = $(this).data('lookuplines_id'); // This should now be the ID
            const active = $(this).data('active');

            // Fill form fields
            $('input[name="edit_id"]').val(id);
            $('input[name="organization_code"]').val(code);
            $('input[name="organization_name"]').val(name);

            // For select2 fields, use .val().trigger('change')

            $('.organization_type').val(btn.data('type')).trigger('change');
            $('select[name="active"]').val(active).trigger('change');
        });


    </script>
@endpush