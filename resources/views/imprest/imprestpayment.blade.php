@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Imprest Payment</h3>
  @include('layouts.breadcrumb')
  <button type="button" id="directpay" class="create btn btn-primary mt-2">Direct Payment</button>

  

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Imprest Number</th>
            <th>Employee Number</th>
            <th>Reporting Employee Number</th>
            <th>Imprest Date</th>
            <th>Reason</th>
            <th>Amount</th>
            <th>Active</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
          <tr class="table-danger">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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
      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "paymentimprestData",
        columns: [
        { data: 'imprest_number' },
        { data: 'employee_number' },
        { data: 'report_number' },
        { data: 'imprest_date' },
        { data: 'reason' },
        { data: 'amount' },
        { data: 'active' },
        { data: 'status' },

          {
            data: 'imprest_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
 
                buttons += `
        <button class="btn btn-sm btn-warning pay-btn" data-id="${row.imprest_id}"
		data-bs-toggle="tooltip" 
		data-bs-placement="top" 
		title="Payment">
          <i class="bi bi-plus"></i>
        </button>`;
             
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#AccTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });	
	
// direct payment	
	
$(".create").click(function(){
    var imprest_id="0";
    var url="{{ url('createdirectpayment') }}?imp_id="+imprest_id;
    window.location.replace(url);
});	
	
	
// payment	
    $(document).on('click', '.pay-btn', function () {
      const id = $(this).data('id');

    var url = "{{URL::to('imprestpaymentcreate')}}?imp_id="+id;
    window.location.href=url;

    });	
	
	
	
</script>

@endpush
