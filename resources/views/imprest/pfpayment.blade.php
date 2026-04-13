@extends('layouts.header')
@section('content')
  <h3 class="text-danger">PF Payment</h3>
  @include('layouts.breadcrumb')
  <button type="button" id="directpay" class="create btn btn-primary mt-2">Direct Payment</button>



  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Actions</th>
            <th>Month</th>
            <th>Year</th>
            <th>PF Amount</th>
            <th>Company Contribute</th>
            <th>Company Contribute1</th>
            <th>Employee Contribute</th>
            <th>Voluter PF</th>
            <th>EDLI Charges</th>
            <th>Admin Charges</th>
            <th>Date</th>

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
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "paymentpfData",
        columns: [

          {
            data: 'employee_conribute_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';

              buttons += `
            <button class="btn btn-sm btn-warning pay-btn" data-id="${row.employee_conribute_id}"
        data-bs-toggle="tooltip" 
        data-bs-placement="top" 
        title="Payment">
              <i class="bi bi-plus"></i>
            </button>`;

              return buttons;
            }
          },
          { data: 'month' },
          { data: 'year' },
          { data: 'pf_amount' },
          { data: 'amount_company' },
          { data: 'amount_company1' },
          { data: 'amount_employee' },
          { data: 'v_pf' },
          { data: 'edli_charge' },
          { data: 'admin_charge' },
          { data: 'date' }

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

    });

    // direct payment	

    $(".create").click(function () {
      var pay_id = "0";
      var url = "{{ url('pfpaymentcreate') }}?pay_id=" + pay_id;
      window.location.replace(url);
    });


    // payment	
    $(document).on('click', '.pay-btn', function () {
      const id = $(this).data('id');

      var url = "{{URL::to('pfpaymentcreate')}}?pay_id=" + id;

      window.location.href = url;

    });



  </script>

@endpush