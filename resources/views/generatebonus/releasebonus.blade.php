@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Release Bonus</h3>
    @include('layouts.breadcrumb')

    <button type='button' class='btn btn-success bg-gradient mt-4 approved_selected'><i class="bi bi-check2-circle"></i>
        Release Selected</button>


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
            </div>
            <div class="table-responsive">
                <table id="BonusTbl" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr class="table-warning">
                            <th><input type="checkbox" id="select_all"></th>
                            <th>Employee Number</th>
                            <th>Employee Name</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Zone</th>
                            <th>Date of joining</th>
                            <th>Last Salary Revision</th>
                            <th>Bonus 1</th>
                            <th>Bonus 2</th>
                            <th>Bonus 3</th>
                            <th>Bonus 4</th>
                            <th>From Bonus 1</th>
                            <th>To Bonus 1</th>
                            <th>Payable 1</th>
                            <th>From Bonus 2</th>
                            <th>To Bonus 2</th>
                            <th>Payable 2</th>
                            <th>From Bonus 3</th>
                            <th>To Bonus 3</th>
                            <th>Payable 3</th>
                            <th>From Bonus 4</th>
                            <th>To Bonus 4</th>
                            <th>Payable 4</th>
                            <th>From Last Period</th>
                            <th>To Last Period</th>
                            <th>Last Period Payable</th>
                            <th>Arrear Bonus</th>
                            <th>Total Bonus Payable</th>

                        </tr>
                        <tr class="table-info">
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
                            <th><input type="text" class="column-search" placeholder="Search" /><span
                                    style="display: none;"></span></th>
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
            var table = $('#BonusTbl').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                scrollY: "50vh",
                ajax: "{{ route('employeebonusreleasegrid') }}",
                columns: [
                    {   // Checkbox column
                        data: 'id',
                        render: function (data, type, row) {
                            return '<input type="checkbox" class="row_checkbox" value="' + data + '">';
                        },
                        orderable: false,
                        searchable: false
                    },
                    { data: 'employee_number', name: 'employee_number' },
                    { data: 'employee_name', name: 'employee_name' },
                    { data: 'department', name: 'department' },
                    { data: 'status', name: 'status' },
                    { data: 'zone', name: 'zone' },
                    { data: 'doj', name: 'doj' },
                    { data: 'last_revision_date', name: 'last_revision_date' },
                    { data: 'bonus1', name: 'bonus1' },
                    { data: 'bonus2', name: 'bonus2' },
                    { data: 'bonus3', name: 'bonus3' },
                    { data: 'bonus4', name: 'bonus4' },
                    { data: 'from1', name: 'from1' },
                    { data: 'to1', name: 'to1' },
                    { data: 'payable1', name: 'payable1' },
                    { data: 'from2', name: 'from2' },
                    { data: 'to2', name: 'to2' },
                    { data: 'payable2', name: 'payable2' },
                    { data: 'from3', name: 'from3' },
                    { data: 'to3', name: 'to3' },
                    { data: 'payable3', name: 'payable3' },
                    { data: 'from4', name: 'from4' },
                    { data: 'to4', name: 'to4' },
                    { data: 'payable4', name: 'payable4' },
                    { data: 'last_period', name: 'last_period' },
                    { data: 'end_date', name: 'end_date' },
                    { data: 'final_payable', name: 'final_payable' },
                    { data: 'arrear_bonus', name: 'arrear_bonus' },
                    { data: 'total_bonus', name: 'total_bonus' },
                ],
                order: [[1, 'desc']]
            });

            // Select all checkboxes
            $('#select_all').on('click', function () {
                $('.row_checkbox').prop('checked', this.checked);
            });

            // Request button click
            $(document).on('click', '.approved_selected', function () {
                var selectedIds = [];
                $('.row_checkbox:checked').each(function () {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    var url = "{{ URL('releasedbonus') }}/?row_id=" + selectedIds.join(',');
                    $.get(url, function (data) {
                        if (data == 1) {
                            showCustomAlert('Bonus Released Successfully', 'success');
                            table.ajax.reload();
                        }
                    });
                } else {
                    showCustomAlert('Please select a row', 'warning');
                }
            });


            $('#BonusTbl thead').on('keyup change', ".column-search", function () {
                var index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });

        });
    </script>

@endpush