@extends('layouts.header')
@section('content')
        <h3 class="text-danger"> Product Asset Details Report</h3>
        @include('layouts.breadcrumb')

        <div class="card shadow-lg rounded-4 border-0">
                <div class="card-body">
                        <div class="d-flex justify-content-between mb-3"></div>
                        <div class="table-responsive">
                                <table id="ReportTbl" class="table table-striped table-bordered">
                                        <thead>

                                                <tr class="table-warning">

                                                        <th>Location</th>
                                                        <th>Area Name</th>
                                                        <th>Group Name</th>
                                                        <th>Category Name</th>
                                                        <th>Subcategory Name</th>
                                                        <th>Product Code</th>
                                                        <th>Product Name</th>
                                                        <th>Product Alternate Name</th>
                                                        <th>Locator Control</th>
                                                        <th>Subinventory Name</th>
                                                        <th>Locator Code</th>
                                                        <th>Tax Credit</th>
                                                        <th>Default HSN Code</th>
                                                        <th>Active</th>
                                                        <th>Product Status</th>
                                                        <th>Asset Code</th>
                                                        <th>Serial No.</th>
                                                        <th>Brand Name</th>
                                                        <th>UOM Code</th>
                                                        <th>Life Period (in months)</th>
                                                        <th>Warranty From</th>
                                                        <th>Warranty (in months)</th>
                                                        <th>Purchase Date</th>
                                                        <th>PO Number</th>
                                                        <th>PO Invoice Number</th>
                                                        <th>Supplier Name</th>
                                                        <th>Department Name</th>
                                                        <th>Installed ON</th>
                                                        <th>Assigned to</th>
                                                        <th>Asset Type Name</th>
                                                        <th>Asset Category Name</th>
                                                        <th>Asset Status</th>
                                                        <th>Workgroup</th>
                                                        <th>System Name</th>
                                                        <th>Operating System</th>
                                                        <th>Windows Key</th>
                                                        <th>Processor Name</th>
                                                        <th>HDD Size</th>
                                                        <th>RAM Size</th>
                                                        <th>Printer Name</th>
                                                        <th>Monitor</th>
                                                        <th>Anti-Virus</th>
                                                        <th>IP Address</th>
                                                        <th>MS Office</th>
                                                        <th>MS Office Key</th>
                                                        <th>Additional Softwares</th>
                                                        <th>Mouse</th>
                                                        <th>Keyboard</th>
                                                        <th>Network Type</th>
                                                        <th>CD/DVD Drive</th>
                                                        <th>Remarks</th>


                                                </tr>

                                                <tr class="table-danger">

                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Location</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">Area
                                                                        Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">Group
                                                                        Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Category Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Subcategory Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Product Code</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Product Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Product Alternate Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Locator Control</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Subinventory Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Locator Code</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">Tax
                                                                        Credit</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Default HSN Code</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Active</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Product Status</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">Asset
                                                                        Code</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Serial No.</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">Brand
                                                                        Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">UOM
                                                                        Code</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">Life
                                                                        Period (in months)</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Warranty From</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Warranty (in months)</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Purchase Date</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">PO
                                                                        Number</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">PO
                                                                        Invoice Number</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Supplier Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Department Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Installed ON</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Assigned to</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">Asset
                                                                        Type Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">Asset
                                                                        Category Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">Asset
                                                                        Status</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Workgroup</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">System Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Operating System</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Windows Key</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Processor Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">HDD
                                                                        Size</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">RAM
                                                                        Size</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Printer Name</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Monitor</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Anti-Virus</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">IP
                                                                        Address</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">MS
                                                                        Office</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span style="display:none;">MS
                                                                        Office Key</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Additional Softwares</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Mouse</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Keyboard</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Network Type</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">CD/DVD Drive</span></th>
                                                        <th><input type="text" class="column-search"
                                                                        placeholder="Search" /><span
                                                                        style="display:none;">Remarks</span></th>

                                                </tr>

                                        </thead>
                                        <tbody>
                                                <!-- Your dynamic row data goes here -->
                                        </tbody>
                                </table>
                        </div>
                </div>
        </div>



@endsection
@push('scripts')


        <script>

                $(document).ready(function () {

                        var table = $('#ReportTbl').DataTable({
                                processing: true,
                                serverSide: false,
                                scrollX: true,
                                scrollY: "50vh",
                                orderCellsTop: true,
                                ajax: {
                                        url: "{{ url('getproductassetdetails') }}",
                                        type: "GET",
                                        data: function (d) {
                                                d.start_date = $('#start_date').val();
                                        }
                                },
                                columns: [
                                        { data: 'location', name: 'location' },
                                        { data: 'area', name: 'area' },
                                        { data: 'group_name', name: 'group_name' },
                                        { data: 'category_name', name: 'category_name' },
                                        { data: 'subcategory_name', name: 'subcategory_name' },
                                        { data: 'product_code', name: 'product_code' },
                                        { data: 'concatenated_product', name: 'concatenated_product' },
                                        { data: 'product_alternate_name', name: 'product_alternate_name' },
                                        { data: 'locator_control', name: 'locator_control' },
                                        { data: 'subinventory_name', name: 'subinventory_name' },
                                        { data: 'locator_code', name: 'locator_code' },
                                        { data: 'tax_credit', name: 'tax_credit' },
                                        { data: 'classification_code', name: 'classification_code' },
                                        { data: 'active', name: 'active' },
                                        { data: 'product_status', name: 'product_status' },
                                        { data: 'asset_number', name: 'asset_number' },
                                        { data: 'serial_number', name: 'serial_number' },
                                        { data: 'brand_name', name: 'brand_name' },
                                        { data: 'uom_code', name: 'uom_code' },
                                        { data: 'life_period', name: 'life_period' },
                                        { data: 'warrenty_from', name: 'warrenty_from' },
                                        { data: 'warrenty', name: 'warrenty' },
                                        { data: 'purchase_date', name: 'purchase_date' },
                                        { data: 'po_number', name: 'po_number' },
                                        { data: 'po_invoice_number', name: 'po_invoice_number' },
                                        { data: 'supplier_name', name: 'supplier_name' },
                                        { data: 'sub_department_name', name: 'sub_department_name' },
                                        { data: 'replace_date', name: 'replace_date' },
                                        { data: 'assigned_to', name: 'assigned_to' },
                                        { data: 'asset_type_name', name: 'asset_type_name' },
                                        { data: 'asset_category_name', name: 'asset_category_name' },
                                        { data: 'asset_status', name: 'asset_status' },
                                        { data: 'work_group', name: 'work_group' },
                                        { data: 'system_name', name: 'system_name' },
                                        { data: 'operating_system', name: 'operating_system' },
                                        { data: 'windows_key', name: 'windows_key' },
                                        { data: 'processor_name', name: 'processor_name' },
                                        { data: 'hdd_size', name: 'hdd_size' },
                                        { data: 'ram_size', name: 'ram_size' },
                                        { data: 'printer_name', name: 'printer_name' },
                                        { data: 'monitor', name: 'monitor' },
                                        { data: 'anti_virus', name: 'anti_virus' },
                                        { data: 'ip_address', name: 'ip_address' },
                                        { data: 'ms_office', name: 'ms_office' },
                                        { data: 'ms_office_key', name: 'ms_office_key' },
                                        { data: 'add_software', name: 'add_software' },
                                        { data: 'mouse', name: 'mouse' },
                                        { data: 'keyboard', name: 'keyboard' },
                                        { data: 'network_type', name: 'network_type' },
                                        { data: 'cd_dvd_drive', name: 'cd_dvd_drive' },
                                        { data: 'remarks', name: 'remarks' }
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

                        // Trigger search
                        $('.report_search').on('click', function () {
                                $('#ReportTbl').DataTable().ajax.reload();
                        });


                        // Column search
                        $('#ReportTbl thead').on('keyup change', ".column-search", function () {
                                var index = $(this).closest('th').index();
                                table.column(index).search(this.value).draw();
                        });
                });

        </script>

@endpush