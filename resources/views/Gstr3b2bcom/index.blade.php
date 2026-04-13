@extends('layouts.header')
@section('content')

<h3 class="text-danger"> GSTR 3B2B Report </h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-header bg-success bg-gradient text-white fw-semibold">
        <i class="bi bi-funnel me-2"></i>Filter Options
    </div>
    <div class="card-body">
        <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
				<div class="col-md-2"></div>
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
            </div>

            <div class="d-flex justify-content-center mt-4">
                <button type="button" class="btn bg-success bg-gradient text-white px-4 report_search" id="report_search">
                    <i class="bi bi-search-heart me-1"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>


    <div class='row'>
<div class='col-3'>
<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3"></div>
    <h5 class="fw-bold text-primary">PUR_REG_ASPIRE</h5>
    <div class="table-responsive">
      <table id="ReportTbl1" class="table table-striped table-bordered">
      </table>
    </div>
  </div>
</div>    
	</div>
	
	<div class='col-3'>
		<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3"></div>
    <h5 class="fw-bold text-primary">GST_3B</h5>
    <div class="table-responsive">
      <table id="ReportTbl2" class="table table-striped table-bordered">

      </table>
    </div>
  </div>
</div>    
	</div>
	
	<div class='col-3'>
		<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3"></div>
    <h5 class="fw-bold text-primary">GST_2B</h5>
    <div class="table-responsive">
      <table id="ReportTbl3" class="table table-striped table-bordered">
      </table>
    </div>
  </div>
</div>    
	</div>
    <div class='col-3'>
		<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3"></div>
    <h5 class="fw-bold text-primary">GST_3B_VS_GST_2B</h5>
    <div class="table-responsive">
      <table id="ReportTbl4" class="table table-striped table-bordered">
      </table>
    </div>
  </div>
</div>    
	</div>
</div>



@endsection
@push('scripts')

<script>

//table 1
    $(document).ready(function () {

    var table = $('#ReportTbl1').DataTable({
      processing: true,
      serverSide: false,
      pageLength:5,
      ajax: {
        url: "{{ url('getpurchasereg') }}",
        type: "GET",
        data: function(d) {
          d.start_date = $('#start_date').val();
		  d.end_date = $('#end_date').val();
        }
      },
      columns: [
    { data: "invoice_date", title: "Month", width: "18%" },
    { data: "igst", title: "IGST", width: "15%" },
    { data: "cgst", title: "CGST", width: "15%" },
    { data: "sgst", title: "SGST", width: "15%" }
      ],
      dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
      buttons: [
        {
          extend: 'excelHtml5',
          title: 'PUR_REG_ASPIRE Report',
          exportOptions: {
            columns: ':visible'
          }
        }
      ]
    });
    });	
	
// table 2

    $(document).ready(function () {

    var table = $('#ReportTbl2').DataTable({
      processing: true,
      serverSide: false,
      pageLength:5,
      ajax: {
        url: "{{ url('getgst3report') }}",
        type: "GET",
        data: function(d) {
          d.start_date = $('#start_date').val();
		  d.end_date = $('#end_date').val();
        }
      },
      columns: [
    { data: "invoice_date", title: "Month", width: "19%" },
    { data: "igst", title: "IGST", width: "18%" },
    { data: "cgst", title: "CGST", width: "18%" },
    { data: "sgst", title: "SGST", width: "18%" }
      ],
      dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
      buttons: [
        {
          extend: 'excelHtml5',
          title: 'GST_3B Report',
          exportOptions: {
            columns: ':visible'
          }
        }
      ]
    });
    });	
	
// table 3

    $(document).ready(function () {

    var table = $('#ReportTbl3').DataTable({
      processing: true,
      serverSide: false,
      pageLength:5,
      ajax: {
        url: "{{ url('gstupldreport') }}",
        type: "GET",
        data: function(d) {
          d.start_date = $('#start_date').val();
		  d.end_date = $('#end_date').val();
        }
      },
      columns: [
    { data: "invoice_date", title: "Month", width: "19%" },
    { data: "igst", title: "IGST", width: "18%" },
    { data: "cgst", title: "CGST", width: "18%" },
    { data: "sgst", title: "SGST", width: "18%" }
      ],
      dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
      buttons: [
        {
          extend: 'excelHtml5',
          title: 'GST_2B Report',
          exportOptions: {
            columns: ':visible'
          }
        }
      ]
    });
    });	
	
// table 4	
	
 $(document).ready(function () {

    var table = $('#ReportTbl4').DataTable({
      processing: true,
      serverSide: false,
      pageLength:5,
      ajax: {
        url: "{{ url('gstcomprreport') }}",
        type: "GET",
        data: function(d) {
          d.start_date = $('#start_date').val();
		  d.end_date = $('#end_date').val();
        }
      },
      columns: [
    { data: "invoice_date", title: "Month", width: "19%" },
    { data: "igst", title: "IGST", width: "18%" },
    { data: "cgst", title: "CGST", width: "18%" },
    { data: "sgst", title: "SGST", width: "18%" }
      ],
      dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
      buttons: [
        {
          extend: 'excelHtml5',
          title: 'GST_3B_VS_GST_2B Report',
          exportOptions: {
            columns: ':visible'
          }
        }
      ]
    });
    });	
	


// Trigger search
$('.report_search').on('click', function () {
	
    $('#ReportTbl1').DataTable().ajax.reload();
	$('#ReportTbl2').DataTable().ajax.reload();
	$('#ReportTbl3').DataTable().ajax.reload();
	$('#ReportTbl4').DataTable().ajax.reload();
	
	
    });


</script>
@endpush