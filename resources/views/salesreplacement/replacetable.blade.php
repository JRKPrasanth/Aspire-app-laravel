@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Sales Replacement</h3>
  @include('layouts.breadcrumb')
  <button class="btn btn-danger px-4 canceled">Cancel</button>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="ReciptTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>
              Actions
            </th>
            <th>
              Invoice Number
            </th>
            <th>
              Invoice Type
            </th>
            <th>
              Invoice Date
            </th>
            <th>
              Source
            </th>
            <th>
              Employee Name
            </th>
            <th>
              Customer Name
            </th>
            <th>
              Pricelist
            </th>
            <th>
              SO Invoice Status
            </th>
            <th>
              Shipped Status
            </th>
            <th>
              Remarks
            </th>
          </tr>
          <tr class="table-info">
            <th>

              <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
            </th>
            <th>

              <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
            </th>
            <th>

              <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
            </th>
            <th>

              <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
            </th>
            <th>

              <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
            </th>
            <th>

              <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
            </th>
            <th>

              <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
            </th>
            <th>

              <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
            </th>
            <th>

              <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
            </th>
            <th>

              <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
            </th>
            <th>

              <input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
            </th>
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

      var table = $('#ReciptTbl').DataTable({
        processing: true,
        serverSide: true,
        order: [[3, 'desc']],
        scrollX: true,
        scrollY: "50vh",
        ajax: "getSalesreplacementinvoiceData",
        columns: [
          {
            data: 'invoice_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';

              buttons += `
                          <button class="btn btn-sm btn-primary replace-btn me-1" data-id="${row.invoice_hdr_id}"
                          data-status="${row.invoice_status}"
                          data-type="${row.invoice_type}"
                          data-bs-toggle="tooltip" 
                          data-bs-placement="top" 
                          title="Replacement">
                         <i class="bi bi-plus"></i>
                          </button>`;

              return buttons;
            }
          },

          { data: 'invoice_number', name: 'invoice_number' },
          { data: 'invoice_type', name: 'invoice_type' },
          { data: 'invoice_date', name: 'invoice_date' },
          { data: 'source', name: 'source' },
          { data: 'first_name', name: 'first_name' },
          { data: 'customer_name', name: 'customer_name' },
          { data: 'pricelist_name', name: 'pricelist_name' },
          { data: 'invoice_status', name: 'invoice_status' },
          { data: 'shiped_status', name: 'shiped_status' },
          { data: 'remarks', name: 'remarks' },

        ]
      });

      // Individual column search
      $('#ReciptTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    $(document).on('click', '.replace-btn', function () {



      var quoteid = $(this).data('id');
      var shiped_status = $(this).data('status');
      var invoicetype = $(this).data('type');

      window.location.replace("{{URL::to('salesinvoicereplacement')}}/" + quoteid + "/" + invoicetype);

    });

    $(".canceled").click(function () {
      var editUrl = "{{URL::to('salesreplacement')}}";
      window.location.replace(editUrl);

    });

  </script>

@endpush