@extends('layouts.header')
@section('content')
    <h3 class="text-danger">EL Encashment</h3>
    @include('layouts.breadcrumb')

    <button type='button' class='btn btn-success mt-4 request'>EL Encashment Request</button>


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
            </div>
            <div class="table-responsive">
                <table id="elencashgrid" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr class="table-warning">
                            <th><input type="checkbox" id="select_all"></th> <!-- Checkbox for select all -->
                            <th>Employee Number</th>
                            <th>Employee Name</th>
                            <th>Department</th>
                            <th>Active</th>
                            <th>Date of Joining</th>
                            <th>Total EL</th>
                            <th>Eligible for Encashment</th>
                            <th>Total Amount</th>
                        </tr>
                        <tr class="table-info">
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
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
            var table = $('#elencashgrid').DataTable({
                processing: true,
                serverSide: true,

                ajax: "{{ route('getencashmentlist') }}",
                columns: [
                    {   // Checkbox column
                        data: 'id',
                        render: function (data, type, row) {
                            return '<input type="checkbox" class="row_checkbox" value="' + data + '">';
                        },
                        orderable: false,
                        searchable: false
                    },
                    { data: 'emp_number', name: 'emp_number' },
                    { data: 'emp_name', name: 'emp_name' },
                    { data: 'sub_department_name', name: 'sub_department_name' },
                    { data: 'active', name: 'active' },
                    { data: 'doj', name: 'doj' },
                    { data: 'total_el', name: 'total_el' },
                    { data: 'extra_el', name: 'extra_el' },
                    { data: 'total_amt', name: 'total_amt' },
                ],
                order: [[1, 'desc']]
            });

            // Select all checkboxes
            $('#select_all').on('click', function () {
                $('.row_checkbox').prop('checked', this.checked);
            });

            // Request button click
            $(document).on('click', '.request', function () {
                var selectedIds = [];
                $('.row_checkbox:checked').each(function () {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    var url = "{{ URL('requestelencashment') }}/?row_id=" + selectedIds.join(',');
                    $.get(url, function (data) {
                        if (data == 1) {
                            showCustomAlert('El Encashment Requested Successfully', 'success');
                            table.ajax.reload();
                        }
                    });
                } else {
                    showCustomAlert('Please select a row', 'warning');
                }
            });

            // Clear filter logic (like jqGrid clear)
            $(".clear").click(function () {
                $('#elencashgrid').DataTable().search('').draw();
            });

            $('#elencashgrid thead').on('keyup change', ".column-search", function () {
                var index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });

        });


    </script>

@endpush