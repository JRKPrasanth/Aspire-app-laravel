@extends('layouts.header')
@section('content')
<h3 class="text-danger">ESI Payment</h3>
@include('layouts.breadcrumb')
<button type="button" id="directpay" class="create btn btn-primary mt-2">Direct Payment</button>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Month</th>
            <th>Year</th>
            <th>ESI Amount</th>
            <th>ESI Employee Amount</th>
            <th>ESI Company Amount</th>
            <th>Date</th>
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
        ajax: "paymentesiData",
        columns: [
        { data: 'month' },
        { data: 'year' },
        { data: 'esi_amount' },
        { data: 'amount_employee' },
        { data: 'company_amount' },
        { data: 'date' },

          {
            data: 'company_conribute_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
 
                buttons += `
        <button class="btn btn-sm btn-warning pay-btn" data-id="${row.company_conribute_id}"
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
    var pay_id="0";
    var url="{{ url('esipaymentcreate') }}?pay_id="+pay_id;
    window.location.replace(url);
});	
	
	
// payment	
    $(document).on('click', '.pay-btn', function () {
      const id = $(this).data('id');

                var url = "{{URL::to('esipaymentcreate')}}?pay_id="+id;
           
                window.location.href=url;

    });	
		

</script>

@endpush
