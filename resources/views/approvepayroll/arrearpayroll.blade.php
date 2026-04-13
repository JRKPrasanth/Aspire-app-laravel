@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Arrear Payroll</h3>
    @include('layouts.breadcrumb')


    <button type='button' class='btn btn-success mt-4 approved_selected'><i class="bi bi-check2-circle"></i> Arrear Payroll
        Generate</button>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
            </div>
            <div class="table-responsive">
                <table id="ArrearPayTbl" class="table table-bordered table-striped w-100" style="width:160% !important;">
                    <thead>
                        <tr class="table-warning">
                            <th><input type="checkbox" id="select_all"></th>
                            <th>Employee Name</th>
                            <th>Payroll Type</th>
                            <th>BASIC</th>
                            <th>HRA</th>
                            <th>DA</th>
                            <th>Annual Allowance</th>
                            <th>Gratuity</th>
                            <th>Ctc</th>
                            <th>Gross Pay</th>
                            <th>Net Amount</th>
                            <th>Esi</th>
                            <th>PF</th>
                            <th>Volunter PF</th>
                            <th>ESI Amount</th>
                            <th>PF Amount</th>
                            <th>PT Amount</th>
                            <th>Effective Date</th>

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
            var table = $('#ArrearPayTbl').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                scrollY: "50vh",
                ajax: "{{ route('arrearpayrollgriddata') }}",
                columns: [
                    {   // Checkbox column
                        data: 'id',
                        render: function (data, type, row) {
                            return '<input type="checkbox" class="row_checkbox" value="' + data + '">';
                        },
                        orderable: false,
                        searchable: false
                    },
                    { data: 'first_name', name: 'first_name' },
                    { data: 'lookup_code', name: 'lookup_code' },
                    { data: 'basic_pay', name: 'basic_pay' },
                    { data: 'hra', name: 'hra' },
                    { data: 'da', name: 'da' },
                    { data: 'annual_allowance', name: 'annual_allowance' },
                    { data: 'gratuity', name: 'gratuity' },
                    { data: 'ctc_pay', name: 'ctc_pay' },
                    { data: 'gross_pay', name: 'gross_pay' },
                    { data: 'net_pay', name: 'net_pay' },
                    { data: 'esi_status', name: 'esi_status' },
                    { data: 'pf_status', name: 'pf_status' },
                    { data: 'volunter_pf', name: 'volunter_pf' },
                    { data: 'esi_amount', name: 'esi_amount' },
                    { data: 'pf_amount', name: 'pf_amount' },
                    { data: 'pt_amount', name: 'pt_amount' },
                    { data: 'effective_date', name: 'effective_date' },
                ],

           initComplete: function () {
          var api = this.api();

          // get the real visible header inside the scroll container
          var $scrollHead = $(api.table().container())
            .find('.dataTables_scrollHead thead');

          // second header row (index 1) has the inputs
          $scrollHead.find('tr:eq(1) th').each(function (colIndex) {
            var th = this;
            $('input.column-search', th).on('keyup change', function () {
              if (api.column(colIndex).search() !== this.value) {
                api.column(colIndex).search(this.value).draw();
              }
            });
          });
        }

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
                    var url = "{{ URL('arrearpayrollgenerate') }}/?row_id=" + selectedIds.join(',');
                    $.get(url, function (data) {
                        if (data == 1) {
                            showCustomAlert('Arrear Payroll Generated Successfully','success');
                            window.location.reload();
                        }
                    });
                } else {
                    showCustomAlert('Please select a row','error');
                }
            });

            // Clear filter logic (like jqGrid clear)
            $(".clear").click(function () {
                $('#ArrearPayTbl').DataTable().search('').draw();
            });

            $('#ArrearPayTbl thead').on('keyup change', ".column-search", function () {
                var index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });

        });


    </script>

@endpush