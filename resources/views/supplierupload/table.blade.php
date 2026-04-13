@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Supplier Upload</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold"></div>
    <div class="card-body container">
      <form method="POST" action="" id="supplieruploads" enctype="multipart/form-data">
        {{ csrf_field() }}

        <!-- Upload Section -->
        <div class="row g-3 align-items-center mb-3">
          <input name="batch_name" type="hidden" class="batch_name" />

          <div class="col-md-3">
            <a href="{{ url('uploads/SUPPLIER TEMPLATE.csv') }}" class="btn btn-outline-primary w-100" download>
              <i class="fa fa-download me-1"></i> Template
            </a>
          </div>

          <div class="col-md-3">
            <input id="choosefiles" name="choosefile" type="file" class="form-control choosefile" required />
          </div>

          <div class="col-md-2">
            <button type="button" id="upload" class="btn btn-success w-100 uploaded">
              <i class="fa fa-upload me-1"></i> Upload
            </button>
          </div>

          <div class="col-md-4">
            <select name="batchnumber" id="batchnumber" class="form-select select2 batchnumber"></select>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
          <div class="col-md-12 d-flex justify-content-between align-items-center flex-wrap">
            <div class="mb-2">
              <a class="btn btn-secondary searchfile_cls me-2"><i class="bi bi-search"></i> Search</a>
              <a class="btn btn-success verifyed validated me-2"><i class="bi bi-check2-circle"></i> Validate</a>
              <a class="btn btn-primary loaded"><i class="bi bi-file-arrow-up"></i> Load</a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>


  <!--upload  Modal -->

  <!-- Modal -->
  <div class="modal fade" id="myModal1" tabindex="-1" aria-labelledby="batchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h5 class="modal-title" id="batchModalLabel">Batch Name</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
          <form id="batchForm">
            <input type="hidden" name="hidden_field" />

            <div class="mb-3 row">
              <div class="col-5">
                <input type="text" name="batch_name1" id="batch_name1" class="form-control" readonly
                  value="BATCH-<?php echo date('Y-m-d'); ?>">
              </div>
              <div class="col-1 text-center">-</div>
              <div class="col-6">
                <input type="text" name="batch_name2" id="batch_name2" class="form-control">
              </div>
            </div>

            <div class="text-center">
              <button type="button" class="btn btn-success index" data-val="modal">Go</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
  <!--end-->



  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="supplierUpTbl" class="table table-bordered table-striped">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th>Batch Name</th>
              <th>Batch Date</th>
              <th>Supplier Number</th>
              <th>Supplier Type</th>
              <th>Batch Status</th>
              <th>Pan Number</th>
              <th>Active</th>

              <!--   
        <th>Supplier Name</th>
        <th>Default Payment Method</th>
        <th>Batch Comments</th>
        <th>Default Payment Terms</th>
        <th>Default Pricelist</th>
        <th>Default bank</th>
        <th>Convert To Supplier</th>
        <th>Customer</th>
        <th>Account Structure</th>
        <th>Insurance Term</th>
        <th>Delivery Term</th>
        <th>Freight Term</th>
        <th>Freight Carrier</th> -->



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
              <!-- 
   <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th><th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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
      <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th> -->

            </tr>
          </thead>

          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>













@endsection
@push('scripts')

  <script>

    // batch number
    var url = "{{URL::to('jcomboformlogin?table=p_supplier_upload_t:batch_name:batch_name') }}&group_by=batch_name";

    $.ajax({
      url: url,
      type: 'GET',
      success: function (data) {
        // Parse JSON string if needed
        if (typeof data === "string") {
          try {
            data = JSON.parse(data);
          } catch (e) {
            console.error("Invalid JSON response:", data);
            return;
          }
        }

        $('.batchnumber').html('<option value="">-- Select Batch --</option>');

        $.each(data, function (i, item) {
          let selected = item.val == "{{ $row->batchnumber ?? '' }}" ? 'selected' : '';
          $('.batchnumber').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
        });

        $('.batchnumber').trigger('change.select2');
      }

    });


    // table data	

    $(document).ready(function () {

      var table = $('#supplierUpTbl').DataTable({
        processing: true,
        serverSide: true,

        ajax: {
          url: "getSupplieruploaddata",
          data: function (d) {
            d.batchname = $(".batchnumber").val(); // send batchname filter
          }
        },
        columns: [

          {
            data: 'supplier_upload_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              return `
                    <button class="btn btn-sm btn-primary me-1 edit-btn" data-id="${data}"
             data-status="${row.batch_status}">
                        <i class="bi bi-pencil"></i>
                    </button>`;
            }
          },
          { data: 'batch_name', name: 'batch_name' },
          { data: 'batch_date', name: 'batch_date' },

          { data: 'supplier_name', name: 'supplier_name' },
          { data: 'supplier_type', name: 'supplier_type' },
          { data: 'batch_status', name: 'batch_status' },
          { data: 'pan_number', name: 'pan_number' },
          { data: 'active', name: 'active' },

          /* 
          { data: 'supplier_number', name: 'supplier_number' },
          { data: 'default_payment_method', name: 'default_payment_method' },
           { data: 'batch_comments', name: 'batch_comments' },
           { data: 'default_payment_terms', name: 'default_payment_terms' },
           { data: 'default_pricelist', name: 'default_pricelist' },
           { data: 'default_bank', name: 'default_bank' },
           { data: 'convertcustosup', name: 'convertcustosup' },
           { data: 'customer', name: 'customer' },
           { data: 'account_structure', name: 'account_structure' },
           { data: 'insurance_term', name: 'insurance_term' },
           { data: 'delivery_term', name: 'delivery_term' },
           { data: 'freight_term', name: 'freight_term' },
           { data: 'freight_carrier', name: 'freight_carrier' }, */



        ]
      });

      // Individual column search
      $('#supplierUpTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });


      $('.searchfile_cls').click(function () {

        table.ajax.reload(); // reload table with selected batchname
      });

    });

    // upload	
    $('.uploaded').click(function () {
      var flname = $("#file").val();
      if (flname != "") {
        $('#myModal1').modal('show');
        $('.modal-dialog').width('40%');
        $("#myModal1").modal({ backdrop: "static" });
      }
      else {
        showCustomAlert("Please choose a file", "error");
      }
    });

    // pop up

    $('#myModal1').on('shown.bs.modal', function () {
      $('.index').click(function () {
        var tmp1 = $('#batch_name1').val();
        var tmp2 = $('#batch_name2').val();
        var temp = tmp1 + tmp2;
        $('.batch_name').val(temp);
        var form_data = new FormData(document.getElementById('supplieruploads'));
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: "{{url('supplierdataupload')}}",
          data: form_data,
          type: 'POST',
          enctype: 'multipart/form-data',
          contentType: false,
          processData: false,
          success: function (data) {
            showCustomAlert(data['message'], 'success');
            $('.close').trigger('click');
            setTimeout(function () {
              location.reload();
            }, 2000);

          },
          error: function (xhr, status, error) {
          }
        });


      });
    });


    // 	edit

    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const status = $(this).data('status');


      if (status == "ERROR") {
        const url = "{{ url('supplieruploadedit') }}/" + id;
        window.location.href = url;
      }
      else {
        showCustomAlert("Batch not allow to edit", 'error');
      }

    });



    // verfy
    $('.verifyed').click(function () {
      var batchname = $('#batchnumber option:selected').val();

      if (!batchname) {
        showCustomAlert("Please select a batchnumber", 'info');
        return;
      }

      var verify = 'verify';
      var url = "{{ URL::to('getSuppliervalidate') }}";
      var newUrl = refineUrl(); // custom function to generate the redirect URL

      $.get(url, {
        batchname: encodeURIComponent(batchname),
        type: verify
      }, function (response) {
        var data = response.status;
        var message = response.message;

        if (data === 'success' || data === 'info') {
          showCustomAlert(message, data);
        } else if (data === 'error') {
          showCustomAlert(message, 'error');
        }

        setTimeout(function () {
          window.location.replace(newUrl);
        }, 2000);
      });
    });


    // loded

    $('.loaded').click(function () {
      var batchname = $('#batchnumber option:selected').val();

      if (!batchname) {
        showCustomAlert("Please select a batchnumber", 'info');
        return;
      }

      var load = 'load';
      var url = "{{ URL::to('getSuppliervalidate') }}";
      var newUrl = refineUrl(); // function to get new URL

      $.get(url, {
        batchname: encodeURIComponent(batchname),
        type: load
      }, function (response) {
        var data = response.status;
        var message = response.message;

        if (data === 'success' || data === 'info') {
          showCustomAlert(message, data);
        } else if (data === 'error') {
          showCustomAlert(message, 'error');
        }

        setTimeout(function () {
          window.location.replace(newUrl);
        }, 2000);
      });
    });






    /*refine url with  params */
    function refineUrl() {
      //get full url
      var url = window.location.href;

      var value = url.split("?")[0];
      // alert(value);
      return value;
    }

    function showResponse(data, message) {
      if (data == 'success') {
        showCustomAlert(message, "success");
        var url = "{{ URL::to('supplierupload') }}";

      }

      if (data == 'info') {
        showCustomAlert(message, "info");
        var url = "{{ URL::to('supplierupload') }}";

      }


      if (data == 'error') {
        showCustomAlert(message, "error");
        var url = "{{ URL::to('supplierupload') }}";

      }


    }


  </script>

@endpush