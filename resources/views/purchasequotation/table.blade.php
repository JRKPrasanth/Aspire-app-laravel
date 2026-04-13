@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Purchase Quotation</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="mb-3 mt-2"></div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <div class="table-responsive">
        <table id="PurchaseTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th>Quotation Number</th>
              <th>Quotation Date</th>
              <th>Supplier Name</th>
              <th>Quotation Type</th>
              <th>Quotation Status</th>
              <th>Supplier Ref No</th>
              <th>Remarks</th>
              <th>Reference Number</th>
              <th>Created By</th>
              <th>Approved By</th>
              <th>Source</th>
              <th>Grand Total</th>
              <th>Tax Total</th>


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
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            </tr>
          </thead>
          <tbody>
            {{-- DataTable will populate via AJAX --}}
          </tbody>
        </table>
      </div>
    </div>
  </div>



@endsection
@push('scripts')


  <script>

    // button purpose
    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
              <button class="btn btn-primary create_standard me-2">Create Standard
                 <i class="bi bi-plus-circle"></i> 
              </button>
            `);
      }

      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create1')) {
        $('#toolbar-container').append(`
              <button class="btn btn-success create_labour me-2">Create Labour
                 <i class="bi bi-plus-circle"></i> 
              </button>
            `);
      }

    });

    // table data
    $(document).ready(function () {

      var status = "{{$status}}";
      var pageMethod = "{{$pageMethod}}";



      var table = $('#PurchaseTbl').DataTable({
        processing: true,
        serverSide: false,
        orderCellsTop: true,
        order: [[2, 'desc']],
        scrollX: true,
        scrollY: "50vh",
        ajax: "getPurchasequotationData?status=" + status + "&pagemethod=" + pageMethod,
        columns: [

          {

            data: 'quotation_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
          <button type="button" class="btn btn-sm btn-primary edit-btn"
            data-id="${row.quotation_hdr_id}"
            data-type="${row.quotation_type}"
            data-status="${row.quote_status}">
            <i class="bi bi-pencil"></i>
          </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-danger delete-btn"
              data-id="${row.quotation_hdr_id}">
              <i class="bi bi-trash"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-warning view-btn"
              data-id="${row.quotation_hdr_id}">
              <i class="bi bi-eye"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'copy')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-primary copy-btn"
              data-id="${row.quotation_hdr_id}">
              <i class="bi bi-copy"></i> Copy
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-success app-btn"
              data-id="${row.quotation_hdr_id}"
              		data-bs-toggle="tooltip" 
                  data-bs-placement="top" 
                  title="Approve">
              <i class="bi bi-check2-circle"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'convert')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-primary convert-btn"
              data-id="${row.quotation_hdr_id}">
             Convert PO
            </button>`;
              }

              return buttons;
            }

          },

          { data: 'quotation_no', name: 'quotation_no' },
          { data: 'quotation_date', name: 'quotation_date' },
          { data: 'supplier_name', name: 'supplier_name' },
          { data: 'quotation_type', name: 'quotation_type' },
          { data: 'quote_status', name: 'quote_status' },
          { data: 'supplier_ref_no', name: 'supplier_ref_no' },
          { data: 'remarks', name: 'remarks' },
          { data: 'reference_number', name: 'reference_number' },
          { data: 'created_by', name: 'created_by' },
          { data: 'approved_by', name: 'approved_by' },
          { data: 'source', name: 'source', visible: false },
          { data: 'grand_total', name: 'grand_total', visible: false },
          { data: 'tax_total', name: 'tax_total', visible: false },


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


    //
    $(document).on('click', '.create_standard', function () {
      var enqtype = "STANDARD";
      var url = "{{ url('purchasequotationcreate') }}/0/" + enqtype;
      var red_url = "{{ url('purchasequotation') }}";
      window.location.replace(url);
    });

    $(document).on('click', '.create_labour', function () {
      var enqtype = "LABOUR";
      var url = "{{ url('purchaselabourquotationcreate') }}/0/" + enqtype;
      var red_url = "{{ url('purchasequotation') }}";
      window.location.replace(url);
    });

    //edit

    $(document).on('click', '.edit-btn', function () {


      const id = $(this).data('id');
      const type = $(this).data('type');
      const status = $(this).data('status');


      if (status != "APPROVED" && status != "INITIATED" && status != "CANCELLED") {
        window.location.replace('purchasequotationcreate/' + id + '/' + type);
      }
      else {
        showCustomAlert('Approved or Submitted Quotation Cannot Be Edit', 'error');
      }
    });


    // delete	
    // delete function
    let deleteId = null;

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('purchasequotationdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {
            if (data == '0') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Deleted Successfully', 'success');
              $('#PurchaseTbl').DataTable().ajax.reload();
            }
            if (data == '1') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert("You Cant't delete , Purchase Enquiry Used in SomeWhere", 'info');
              $('#PurchaseTbl').DataTable().ajax.reload();
            }

          },
          error: function (xhr) {
            $('#globalDeleteModal').modal('hide');
            const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
            showCustomAlert(errorMsg, 'error');
          }
        });
      }
    });

    //view
    $(document).on('click', '.view-btn', function () {

      const id = $(this).data('id');
      var url = "{{$pageMethod}}";

      window.location.replace('purchasequotationview/' + id + '/?return=' + url);

    });




    // Purpose For Copy Quote
    $(document).on('click', '.copy-btn', function () {

      const id = $(this).data('id');

      window.location.replace('purchasecopyquotecreate/' + id + '/0?status=COPYQUOTE');


    });

    // Approval
    $(document).on('click', '.app-btn', function () {

      const id = $(this).data('id');

      window.location.replace('purchasequotationcreate/' + id + '?approve_status=approved');

    });

    // convert po

    $(document).on('click', '.convert-btn', function () {

      const id = $(this).data('id');

      window.location.replace('purchasequtoetopocreate/' + id + '/0?status=QUOTATION');

    });


  </script>

@endpush