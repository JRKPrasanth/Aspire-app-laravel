@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Production Plan</h3>
  @include('layouts.breadcrumb')
  <?php if($pageurl=='planapproval') { ?>
    <a> <button id="mailSend" type="button" class="btn btn-success me-2 status"><i class="bi bi-envelope"></i> Plan Scheduler</button></a>
  <?php } ?>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <div class="table-responsive">
        <table id="ProplanTbl" class="table table-bordered table-striped w-100" style="width:150% !important;">
          <thead>
            <tr class="table-warning">
              <th style="width:40px"><input type="checkbox" id="selectAll"></th>
              <th>Actions</th>
              <th>Plan no</th>
              <th>Product Code</th>
              <th>Product</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Plan Qty</th>
              <th>Actual Qty</th>
              <th>Pending Qty</th>
              <th>Remarks</th>
              <th>Status</th>


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

    // table data

    $(document).ready(function () {

      var status = '<?php echo $status;?>';
      var type = '<?php echo $type; ?>';
      var pageurl = '<?php echo $pageurl; ?>';

      var table = $('#ProplanTbl').DataTable({
        processing: true,
        serverSide: false,
        order: [[4, 'desc']],
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "getproductionplanData?status=" + status + "&type=" + type,

        columns: [

          {
              data: null,
              orderable: false,
              searchable: false,
              className: 'text-center',
              render: function (data, type, row) {
                  return `<input type="checkbox" class="row-select"
                          value="${row.productionplan_hdr_id}"
                          data-status="${row.email_status}">`;
              }
          },

          {

            data: 'productionplan_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-warning view-btn me-2"
                                    data-id="${row.productionplan_hdr_id}">
                                    <i class="bi bi-eye"></i>
                                </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-primary edit-btn me-1"
                                    data-id="${row.productionplan_hdr_id}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Create Jobcard">
                                 <i class="bi bi-plus"></i>
                                </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'closeplan')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-danger close-btn"
                                    data-id="${row.productionplan_hdr_id}"
                    data-number="${row.plan_no}"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Close Plan">
                                    <i class="bi bi-x"></i>
                                </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-success approve-btn"
                                    data-id="${row.productionplan_hdr_id}">
                                    Approve
                                </button>`;
              }




              return buttons;
            },

          },

          { data: 'plan_no', name: 'plan_no' },
          { data: 'product_code', name: 'product_code' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'start_date', name: 'start_date' },
          { data: 'end_date', name: 'end_date' },
          { data: 'production_qty', name: 'production_qty' },
          { data: 'plan_qty', name: 'plan_qty' },
          { data: 'pending_qty', name: 'pending_qty' },
          { data: 'remarks', name: 'remarks' },
          { data: 'plan_status', name: 'plan_status' },

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


    $(document).on('click', '#selectAll', function () {
      $('.row-select').prop('checked', this.checked);
    });

    //approve function
    $(document).on('click', '.approve-btn', function () {

      const id = $(this).data('id');
      var pageurl = "<?php echo $pageurl; ?>";

      var url = "{{ URL::to('planapprovalcreate') }}";
      var editUrl = url + '/' + id + "?pageurl=" + pageurl;
      window.location.replace(editUrl);

    });


    //view function
    $(document).on('click', '.view-btn', function () {

      var pageurl = "<?php echo $pageurl; ?>";

      const id = $(this).data('id');
      var url = "{{URL::to('planview')}}/" + id + "?pageurl=" + pageurl;

      window.location.href = url;

    });

  $('#mailSend').on('click', function () {

    var mail1 = "aspire@jrkresearch.com";
    var cellvalues = [];

    $('.row-select:checked').each(function () {
        var id = $(this).val();
        if (id) cellvalues.push(id);
    });

    if (cellvalues.length === 0) {
        showCustomAlert("Please Select a Row", "info");
        return;
    }

    var url = "{{ URL::to('planschedulemail') }}/" + cellvalues.join(',');

    $.get(url, function (response) {

        if (response.status) {
            showCustomAlert(response.message, 'success');
            $('#ProplanTbl').DataTable().ajax.reload(null, false);
        } else {
            showCustomAlert(response.message, 'info');
        }

    }).fail(function () {
        showCustomAlert("Mail sending failed", "error");
    });

});



    // close 

  $(document).on('click', '.close-btn', function () {

      const id = $(this).data('id');
      const number = $(this).data('number');


      var clsurl = "{{ URL::to('productionplan') }}";
      var url = "{{ URL::to('updateplanstatus') }}/" + id + "?planno=" + number;

      $.get(url, function (data) {
        if (data == 1) {
          showCustomAlert('Plan Closed Successfully', 'success');
          setTimeout(function () {
            window.location.href = clsurl;
          }, 1000);
        } else if (data == 2) {
          showCustomAlert('Job already created for this plan','info');
        } else {
          showCustomAlert('Plan Not Closed', 'error');
        }

      });
  });


    // create jobcard


  $(document).on('click', '.edit-btn', function () {


      const id = $(this).data('id');

      var pageurl = "<?php echo $pageMethod; ?>";

      var jobtype = "";
      if (pageurl == "packingjobcard") {
        jobtype = "packing";
      } else {
        jobtype = "production";
      }

      var url = "{{ URL::to('productionplanedit') }}";
      var editUrl = url + '/' + id + "?jobtype=" + jobtype;
      window.location.replace(editUrl);

  });


    // extra	
  $(document).on('click', '.create', function () {
      var url = "{{ URL::to('productionplancreate') }}/0";
      var red_url = "{{ URL::to('productionplan') }}";
      window.location.replace(url);
  });


  </script>

@endpush