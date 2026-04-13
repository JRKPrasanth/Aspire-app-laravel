@extends('layouts.header')
@section('content')
<h3 class="text-danger">Purchase Register Report</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
        <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate enctype="multipart/form-data">
            @csrf
            <div class="row g-4 align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="form-label fw-semibold">From Date</label>
                    <input type="text" class="form-control start_date" id="start_date" name="start_date" required autocomplete="off">
                    <div class="invalid-feedback">Please select a start date.</div>
                </div>

                <div class="col-md-4">
                    <label for="end_date" class="form-label fw-semibold">To Date</label>
                    <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off">
                    <div class="invalid-feedback">Please select an end date.</div>
                </div>

                <div class="col-md-2 d-grid">
                    <button type="button" class="btn btn-primary report_search" id="report_search">
                        <i class="bi bi-search-heart me-1"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-lg rounded-4 border-0"> 
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3"></div>
    <div class="table-responsive">
      <table id="ReportTbl" class="table table-striped table-bordered">
      <thead>
  <tr class="table-warning">
    <th class="freeze">Po Number</th>
    <th>Po Date</th>
    <th>RCM Status</th>
    <th>Invoice Number</th>
    <th>Invoice Date</th>
    <th>GRN Number</th>
    <th>GRN Date</th>
    <th>Supplier Name</th>
    <th>Supplier GST NO</th>
    <th>Product Name</th>
    <th>Product Group</th>
    <th>Product Category</th>
    <th>Product Subcategory</th>
    <th>Product Qty</th>
    <th>UOM Code</th>
    <th>TAX Credit</th>
    <th>Product Rate</th>
    <th>Due Date</th>
    <th>HSN Code</th>
    <th>Taxable Amount</th>
    <th>Tax Group</th>
    <th>Other Taxable Amount</th>
    <th>Other Tax Amount</th>
    <th>Other Tax Group</th>
    <th>Transport Taxable Amount</th>
    <th>Transport Tax Amount</th>
    <th>Transport Tax Group</th>
    <th>CGST</th>
    <th>SGST</th>
    <th>IGST</th>
    <th>Invoice Tax Total</th>
    <th>Invoice Grand Total</th>
    <th>GRN Month</th>
    <th>GRN Year</th>
    <th>Finance Year</th>
    <th>Created By</th>
    <th>lead day</th>
    <th>Lead Time</th>
  </tr>
  <tr class="table-success">
<th class="freeze"><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Po Number</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Po Date</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">RCM Status</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Invoice Number</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Invoice Date</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">GRN Number</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">GRN Date</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Supplier Name</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Supplier GST NO</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Product Name</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Product Group</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Product Category</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Product Subcategory</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Product Qty</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">UOM Code</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">TAX Credit</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Product Rate</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Due Date</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">HSN Code</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Taxable Amount</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Tax Group</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Other Taxable Amount</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Other Tax Amount</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Other Tax Group</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Transport Taxable Amount</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Transport Tax Amount</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Transport Tax Group</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">CGST</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">SGST</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">IGST</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Invoice Tax Total</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Invoice Grand Total</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">GRN Month</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">GRN Year</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Finance Year</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Created By</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">lead day</span></th>
<th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Lead Time</span></th>
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
        url: "{{ url('getpurchaseregister') }}",
        type: "GET",
        data: function(d) {
          d.start_date = $('#start_date').val();
		  d.end_date = $('#end_date').val();

        }
      },
      columns: [
        
    { class:'freeze',data: 'ponumber' },
    { data: 'po_date' },
    { data: 'rcm' },
    { data: 'bill_number' },
    { data: 'invoice_date' },
    { data: 'grnno' },
    { data: 'grn_date' },
    { data: 'supplier_name' },
    { data: 'gst_number' },
    { data: 'concatenated_product' },
    { data: 'group_name' },
    { data: 'category_name' },
    { data: 'subcategory_name' },
    { data: 'p_qty' },
    { data: 'uom_code' },
    { data: 'tax_credit' },
    { data: 'rate' },
    { data: 'promised_date' },
    { data: 'classification_code' },
    { data: 'sub_total' },
    { data: 'tax_group_name' },
    { data: 'other_tax_amt' },
    { data: 'other_tax_value' },
    { data: 'other_tax_group_name' },
    { data: 'transport_tax_amt' },
    { data: 'transport_tax_value' },
    { data: 'transport_tax_group_name' },
    { data: 'cgst' },
    { data: 'sgst' },
    { data: 'igst' },
    { data: 'tax_amount' },
    { data: 'invoice_grand_total' },
    { data: 'grnmonth' },
    { data: 'monyr' },
    { data: 'finance_yr' },
    { data: 'first_name' },
    { data: 'LeadDay' },
    { data: 'LeadTime' }
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
      
      });
 </script>
@endpush