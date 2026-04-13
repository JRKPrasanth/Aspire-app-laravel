@extends('layouts.header')
@section('content')
<h3 class="text-danger">Purchase Order Details</h3>
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
    <div class="table-responsive w-100">
      <table id="ReportTbl" class="table table-striped table-bordered">
        <thead>
          <tr class="table-warning">
            <th class="freeze">Action</th>
            <th class="freeze">Supplier Name</th>
            <th>Po Hdr</th>
            <th>PO Status</th>
            <th>PO Date</th>
            <th>PO Number</th>
            <th class="text-end">Grand Total</th>
          </tr>
         <tr class="table-success">
     <th></th>     
    <th class="freeze">
      <input type="text" placeholder="Search" class="form-control form-control-sm column-search" />
      <span style="display: none;">Supplier Name</span>
    </th>
    <th>
      <input type="text" placeholder="Search" class="form-control form-control-sm column-search" />
      <span style="display: none;">PO Header</span>
    </th>
    <th>
      <input type="text" placeholder="Search" class="form-control form-control-sm column-search" />
      <span style="display: none;">PO Status</span>
    </th>
    <th>
      <input type="text" placeholder="Between Dates" class="form-control form-control-sm column-search" />
      <span style="display: none;">PO Date</span>
    </th>
    <th>
      <input type="text" placeholder="Search" class="form-control form-control-sm column-search" />
      <span style="display: none;">PO Number</span>
    </th>
    <th>
      <input type="text" placeholder="Search" class="form-control form-control-sm column-search text-end" />
      <span style="display: none;">Grand Total</span>
    </th>
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
      ajax: {
        url: "{{ url('getpodetailsData') }}",
        type: "GET",
        data: function(d) {
          d.start_date = $('#start_date').val();
		      d.end_date = $('#end_date').val();

        }
      },
      columns: [

        {   data: 'po_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
            let buttons = '';
              
                      buttons += `
                        <button type="button" class="btn btn-sm btn-warning bg-gradient view-btn"
                          data-id="${row.po_hdr_id}">
                          <i class="bi bi-eye"></i>
                        </button>`;
                            
                              return buttons;
                        }
                     },   
        { class: 'freeze', data: 'supplier_name'},
        { data: 'po_hdr_id', visible:true},
        { data: 'po_status'},
        { data: 'po_date'},
        { data: 'po_number'},
        { data: 'po_grand_total'}

      ]
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

   $(document).on('click', '.view-btn', function () {

    var table = $('#ReportTbl').DataTable();   // ✅ correct table id
    var data = table.row($(this).closest('tr')).data();

    if (!data) {
        alert("Row data not found");
        return;
    }

    var po_hdr_id = data.po_hdr_id;   // ✅ get from row data
    var po_status = data.po_status;   // ✅ you forgot this earlier

    //alert(po_hdr_id);

    if (po_hdr_id != "") {

        if (po_status == 'APPROVED' || 
    po_status == 'COMPLETED' || 
    po_status == 'CLOSED') {

    var url = "{{URL::to('purchasereport')}}/" + po_hdr_id;
    window.open(url, '_blank');   // ✅ opens in new tab

    } else {

    var url = "{{ URL::to('purchaseorderview')}}/" + po_hdr_id + "/?report=report";
    window.open(url, '_blank');   // optional: open this also in new tab

           
    }
}

});
      });
 </script>
@endpush