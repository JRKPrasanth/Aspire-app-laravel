@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> Quality Check</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3"></div>


  <?php if ($pageMethod == 'qualitymr' || $pageMethod == 'mrapproval') { ?>
  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="QcTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Action</th>
              <th>QC Number</th>
              <th>QC Date</th>
              <th>Status</th>
              <th>Batch No</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Employee</th>

            </tr>

            <tr class="table-info">
               <th></th>
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
  </div>
  <?php } else { ?>
  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="QcTbl1" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Action</th>
              <th>QC Number</th>
              <th>QC Date</th>
              <th>QC Status</th>
              <th>GRN Number</th>
              <th>PO Number</th>
              <th>Product Code</th>
              <th>Product Name</th>
              <th>Supplier Name</th>
              <th>SubContract Name</th>
              <th>DC Number</th>

            </tr>
            <tr class="table-info">
              <th></th>
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
  </div>
  <?php } ?>

  <!-- popup -->
  <form method="post" action="" id="indentreducesaves" data-parsley-validate>
    <!-- Modal -->
    <div class="modal fade" id="myModal2" tabindex="-1" aria-labelledby="qualityIndentModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title text-danger" id="qualityIndentModalLabel">Quality Indent</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-bordered align-middle mb-0">
                <thead class="table-primary">
                  <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">UOM Code</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Used Qty</th>
                  </tr>
                </thead>
                <tbody class="indentdata">
                  <!-- Dynamic rows go here -->
                </tbody>
              </table>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-success indentreducesave" data-bs-dismiss="modal">Save</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>

        </div>
      </div>
    </div>
  </form>



@endsection
@push('scripts')


  <script>

    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
                  <button class="btn btn-primary create me-2">Create
                    <i class="bi bi-plus-circle"></i> 
                  </button>
                `);
      }
    });


    $(document).ready(function () {

      var batch_no = {!! json_encode($batch_no) !!};
      var employee_id = {!! json_encode($employee_id) !!};


      var table = $('#QcTbl').DataTable({
        processing: true,
        serverSide: true,
        order: [[1, 'desc']],
        ajax: "{{ url('MRQCData') }}?status=" + status,
        columns: [
          {
            data: 'qc_header_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '100px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
                <button  class="btn btn-sm btn-info me-1 edit-btn" data-id="${data}"><i class="bi bi-pencil"></i></button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `<button class="btn btn-sm btn-warning me-1 view-btn" data-id="${data}"><i class="bi bi-eye"></i></button>`;
              }

              return buttons;
            }

          },
          { data: 'qc_number', name: 'qc_number' },
          { data: 'qc_date', name: 'qc_date' },
          { data: 'qc_status', name: 'qc_status' },
          { data: 'batch_no', name: 'batch_no' },
          { data: 'start_date', name: 'start_date' },
          { data: 'end_date', name: 'end_date' },
          { data: 'first_name', name: 'first_name' }

        ]
      });

      // Individual column search
      $('#QcTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });

    $(document).ready(function () {
      var status = '<?php echo $status; ?>';
      var table = $('#QcTbl1').DataTable({
        processing: true,
        serverSide: false,
        order: [[1, 'desc']],
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "{{ url('getQCData') }}?status=" + status,
        columns: [
          {
            data: 'qc_header_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btnn')) {
                buttons += `
                <button  class="btn btn-sm btn-info me-1 edit-btn" data-id="${data}" data-status="${row.qc_status}"><i class="bi bi-pencil"></i></button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `<button class="btn btn-sm btn-warning me-1 view-btn" data-id="${data}"><i class="bi bi-eye"></i></button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'qualityindent')) {
                buttons += `<button class="btn btn-sm btn-primary me-1 quality" data-id="${data}"
                    data-bs-toggle="tooltip" 
          data-bs-placement="top" 
          title="Quality Indent"><i class="bi bi-check2-circle"></i></button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                buttons += `<button class="btn btn-sm btn-success me-1 approval" data-id="${data}"
                      data-bs-toggle="tooltip" 
          data-bs-placement="top" 
          title="Approval"><i class="bi bi-check2-circle"></i></button>`;
              }
              return buttons;
            }

          },
          { data: 'qc_number', name: 'qc_number' },
          {
            data: 'qc_date',
            name: 'qc_date',
            className: 'text-center',
            render: function (data, type, row) {
              if (!data) return '';
              const date = new Date(data);
              const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
              return date.toLocaleDateString('en-GB', options);
            }
          },
          { data: 'qc_status', name: 'qc_status' },
          { data: 'grn_number', name: 'grn_number' },
          { data: 'po_number', name: 'po_number' },
          { data: 'product_code', name: 'product_code' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'supplier_name', name: 'supplier_name' },
          { data: 'subcontract_name', name: 'subcontract_name' },
          { data: 'dc_number', name: 'dc_number' },

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

    $(document).on('change', '.used_qty', function () {
      var index = $(this).data('val');
      var used_qty = parseFloat($(this).val());
      var remaining_qty = parseFloat($('.remaining_qty' + index).val());
      if (used_qty > remaining_qty) {

        $('.used_qty' + index).val('');
        showCustomAlert("Not greater than Remaining Quantity", 'warning');

      }
    });

    //create function

    $(".create").click(function () {

      var pageMethod = "{{ $pageMethod }}";
      if (pageMethod === 'qualitymr') {
        var url = "{{ url('mrqctable') }}";
        window.location.replace(url);
      } else {
        var url = "{{ url('qcgrntable') }}";
        window.location.replace(url);
      }
    });



    // view function

    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('purchaseqcview') }}/" + id + '/23';
      window.location.href = url;
    });

    // qc indent check function	

    $(document).on('click', '.quality', function () {
      const id = $(this).data('id');
      $('#myModal2').modal('show');
      const url = "{{ url('getindentquantity') }}/" + id + '?status=purchaseqc';
      $.get(url, function (data) {
        $('.indentdata').html(data);
        $('.readonlydiv').css("pointer-events", "none");
      });
    });

    $(document).on('change', '.used_qty', function () {
      var index = $(this).data('val');
      var used_qty = parseFloat($(this).val());
      var remaining_qty = parseFloat($('.remaining_qty' + index).val());
      if (used_qty > remaining_qty) {

        $('.used_qty' + index).val('');
        showCustomAlert("Not greater than Remaining Quantity", 'warning');

      }
    });

    // save function

    $(document).on('click', '.indentreducesave', function () {
      var save = $(this).val();
      var url = "{{ url('indentreducesave') }}";
      var red_url = "{{ url('purchaseqc') }}";

      var formdata = $('#indentreducesaves').serialize();
      var form = $('#indentreducesaves');
      $.post(url, formdata, function (data) {
        var status = data.status;
        var msg = data.message;
        var id = data.id;
        if (save = 'SAVE') {

          showCustomAlert(msg, status);
          setTimeout(function () {
            window.location.href = red_url;
          }, 1500);
        }
        else {
          showCustomAlert(msg, status);
          setTimeout(function () {
            window.location.href = create_url;
          }, 1500);
        }
      });
    });



    // edit button
    $(document).on('click', '.edit-btn', function () {

      const id = $(this).data('id');
      const status = $(this).data('status');

      if (status != "APPROVED") {
        window.location.replace('purchaseqcedit/' + id);
      } else {
        showCustomAlert('Approved QC Cannot Be Edit!!', 'warning');
      }

    });


    // Approve funcion	
    $(document).on('click', '.approval', function () {
      const id = $(this).data('id');
      const url = "{{ url('qcapprovalcreate') }}/" + id;
      window.location.href = url;
    });

  </script>

@endpush