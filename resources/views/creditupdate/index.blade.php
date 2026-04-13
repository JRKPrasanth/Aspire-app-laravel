@extends('layouts.header')
@section('content')
  <h3 class="text-danger">ITC Credit Update</h3>
  @include('layouts.breadcrumb')
  <button class="btn btn-primary px-4 mt-1 credit_update" id="credit_update"> Credit Update </button>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th><input type="checkbox" id="select-all"></th>
            <th>Status</th>
            <th>Month</th>
            <th>Invoice Date</th>
            <th>Invoice Number</th>
            <th>GST Number</th>
            <th>Type</th>
            <th>Supplier Name</th>
            <th>Taxable Value</th>
            <th>IGST</th>
            <th>CGST</th>
            <th>SGST</th>

          </tr>

          <tr class="table-danger">
            <th></th> <!-- Empty for checkbox -->
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
        <tbody></tbody>
      </table>
    </div>
  </div>





  <!-- Credit Update Modal -->
  <div class="modal fade" id="creditModal" tabindex="-1" aria-labelledby="creditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="creditModalLabel">Credit Update Details</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
          <form id="credit_update_form" data-parsley-validate>
            <input type="hidden" class="invoice_id" value="">

            <div class="mb-3">
              <label class="form-label"><strong>Credit Taken:</strong></label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="credit_taken" id="credit_taken_yes" value="Yes">
                <label class="form-check-label" for="credit_taken_yes">Yes</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="credit_taken" id="credit_taken_no" value="No">
                <label class="form-check-label" for="credit_taken_no">No</label>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="credit_date" class="col-sm-4 col-form-label">Date</label>
              <div class="col-sm-8">
                <input type="text" class="form-control credit_date start_date" id="credit_date" name="credit_date"
                  placeholder="Select date">
              </div>
            </div>

            <!-- Modal Footer -->
            <div class="text-center">
              <button type="button" class="btn btn-success" id="credit_update_val">Update</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>


@endsection
@push('scripts')

  <script>


    $(document).ready(function () {
      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "getcreditData",
        columns: [
          {
            data: 'id',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data) {
              return `<input type="checkbox" class="row-checkbox" value="${data}">`;
            }
          },
          {
            data: 'status',
            className: 'text-center',
            render: function (data) {
              let color = '';
              if (data === 'MATCH') color = 'green';
              else if (data === 'PARTIAL MATCH') color = 'blue';
              else color = 'red';
              return `<span style="color:${color}; font-weight:bold;">${data}</span>`;
            }
          },
          { data: 'monyr', name: 'monyr' },
          { data: 'invoice_date', name: 'invoice_date' },
          { data: 'bill_number', name: 'bill_number' },
          { data: 'gst_number', name: 'gst_number' },
          { data: 'type', name: 'type' },
          { data: 'supplier_name', name: 'supplier_name' },
          { data: 'total_tax_value', name: 'total_tax_value' },
          { data: 'igst', name: 'igst' },
          { data: 'cgst', name: 'cgst' },
          { data: 'sgst', name: 'sgst' }

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


      $('#select-all').on('click', function () {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"].row-checkbox', rows).prop('checked', this.checked);
      });


      $('#AccTbl tbody').on('change', '.row-checkbox', function () {
        if (!this.checked) {
          var el = $('#select-all').get(0);
          if (el && el.checked && ('indeterminate' in el)) {
            el.indeterminate = true;
          }
        }
      });
    });



    $(document).on('click', '#credit_update', function () {
      var table = $('#AccTbl').DataTable();
      var selectedIds = [];
      var allMatch = true;

      // Loop through all checked checkboxes
      $('.row-checkbox:checked').each(function () {
        var rowData = table.row($(this).closest('tr')).data();
        if (rowData) {
          selectedIds.push(rowData.id);

          if (rowData.status !== "MATCH") {
            allMatch = false;
          }
        }
      });

      // 🔹 Validate selections
      if (selectedIds.length === 0) {
        showCustomAlert("Please select at least one row.", "info");
        return;
      }

      if (!allMatch) {
        showCustomAlert("Please select only rows with status 'MATCH'.", "error");
        return;
      }

      // ✅ All valid → open modal and pass IDs
      $('#creditModal').modal('show');
      $('.invoice_id').val(selectedIds.join(",")); // store all IDs in hidden field or input
    });




    $(document).on('click', '#credit_update_val', function () {
      var selectedIds = [];
      var types = [];
      var allValid = true;

      // Loop through all checked checkboxes
      $('.row-checkbox:checked').each(function () {
        var rowData = $('#AccTbl').DataTable().row($(this).closest('tr')).data();
        selectedIds.push(rowData.id);
        types.push(rowData.type);
        if (rowData.status !== "MATCH") {
          allValid = false;
        }
      });

      if (selectedIds.length === 0) {
        showCustomAlert("Please select at least one record.", "info");
        return;
      }

      if (!allValid) {
        showCustomAlert("Only Status MATCH records are allowed for Credit Update.", "error");
        return;
      }

      var uniqueTypes = [...new Set(types)];
      if (uniqueTypes.length > 1) {
        showCustomAlert("Can't Credit Update for Different Types", "info");
        return;
      }

      var creditDate = $("#credit_date").val().trim();
      if (creditDate === "") {
        showCustomAlert("Please enter a valid Credit Date.", "info");
        return;
      }

      $.ajax({
        url: "{{ URL::to('creditupdatesave') }}",
        type: "POST",
        data: {
          id: selectedIds.join(","),
          type: uniqueTypes[0],
          credit_date: creditDate,
          _token: "{{ csrf_token() }}"
        },
        dataType: "json",
        success: function (data) {
          showCustomAlert('Saved successfully!', 'success');
          window.location.href = "{{ URL::to('creditupdate') }}";
        },
        error: function () {
          showCustomAlert("Something went wrong. Please try again.", "error");
        }
      });
    });



  </script>

@endpush