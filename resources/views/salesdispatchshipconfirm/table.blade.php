@extends('layouts.header')
@section('content')
<h3 class="text-danger">Ship Confirm</h3>
@include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="SalesTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Actions</th>
            <th>Invoice No</th>
            <th>Dispatch No</th>
            <th>Prepare Date</th>
            <th>Dispatch Date</th>
            <th>Customer Name</th>
            <th>Employee Name</th>
            <th>Dispatch Status</th>
            <th>Freight Carrier</th>
            
          </tr>
          <tr class="table-info">
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


      var table = $('#SalesTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "getshipdata",
        columns: [

                  {
            data: 'so_dispatch_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                <button class="btn btn-sm btn-warning view-btn" data-id="${row.so_dispatch_hdr_id}">
                  <i class="bi bi-eye"></i>
                </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'confirm')) {
                buttons += `
                <button class="btn btn-sm btn-success ship-btn" data-id="${row.so_dispatch_hdr_id}"
				data-number="${row.dispatch_number}" data-status="${row.dispatch_status}">
                  Ship
                </button>`;
              }

              return buttons;
            }
          },
          { data: 'invoice_number', name: 'invoice_number' },
          { data: 'dispatch_number', name: 'dispatch_number' },
          { data: 'prepare_date', name: 'prepare_date' },
          { data: 'dispatch_date', name: 'dispatch_date' },
          { data: 'customername', name: 'customername' },
          { data: 'empname', name: 'empname' },
          { data: 'dispatch_status', name: 'dispatch_status' },
          { data: 'carrier_name', name: 'carrier_name' },

        ]
      });

      // Individual column search
      $('#SalesTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });
	
	
// sip confirm


	 $(document).on('click', '.ship-btn', function () {

        const id =     $(this).data('id');
        const number  =     $(this).data('number');
        const status  =     $(this).data('status');
		
       	var url = "{{ URL::to('shipmentstatus')}}/"+id;

			if(status=="SHIPPED")
			{
				showCustomAlert("Already Shipped","warning");
			}
			else
			{
			      $.get(url, function(data){
        		  showCustomAlert(data['message'],data['status']);
					$('#SalesTbl').DataTable().ajax.reload();
                  });
			}

    });
	
	
// view	
	
                $(document).on('click', '.view-btn', function () {

                    const id =     $(this).data('id');
                     var return1 = "{{$pageMethod}}";
       
                    var url = "dispatchview";
                    var editUrl = url + '/' + id + '/show';
                    window.location.replace('dispatchview/' +id+'?return='+return1);

            }); 	


</script>

@endpush
