@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Sales Replacement</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3 mt-2 me-2"><button
      class="btn btn-primary text-white px-4 directreplace">Direct Replacement</button></div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="ReplaceTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Replacement Number</th>
            <th>Invoice Type</th>
            <th>Invoice Date</th>
            <th>Customer Name</th>
            <th>Shipped Status</th>
            <th>Actions</th>
          </tr>
          <tr class="table-info">
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

    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
                <button class="btn btn-success text-white px-4 create me-2">Create
                  <i class="bi bi-plus-circle"></i> 
                </button>
              `);
      }
    });


    // data table funcrion	
    $(document).ready(function () {

      var status = "{{$status}}";

      var table = $('#ReplaceTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{URL::to('getSalesreplacementData')}}/?status=" + status,
        columns: [
          { data: 'replacement_number', name: 'replacement_number' },
          { data: 'invoice_type', name: 'invoice_type' },
          { data: 'invoice_date', name: 'invoice_date' },
          { data: 'customer_name', name: 'customer_name' },
          { data: 'shiped_status', name: 'shiped_status' },
          {
            data: 'replacement_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'convert')) {
                buttons += `
          <button class="btn btn-sm btn-success convert-btn" data-id="${row.replacement_hdr_id}">
            Dispatch
          </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
          <button class="btn btn-sm btn-primary edit-btn" data-id="${row.replacement_hdr_id}">
            <i class="bi bi-pencil"></i>
          </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
          <button class="btn btn-sm btn-warning view-btn" data-id="${row.replacement_hdr_id}">
            <i class="bi bi-eye"></i>
          </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approval')) {
                buttons += `
          <button class="btn btn-sm btn-success approve-btn" data-id="${row.replacement_hdr_id}">
            Approve
          </button>`;
              }

              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#ReplaceTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });

    // convert

    $(document).on('click', '.convert-btn', function () {

      const id = $(this).data('id');


      window.location.replace('dispatchcreate/' + id + '?status=REPLACEMENT');

    });


    // Create main action
    $(document).on('click', '.create', function (e) {
      // Ignore clicks if they came from the directreplace button
      if ($(e.target).closest('.directreplace').length) return;

      var url = "{{ url('salesreplacementcreate') }}";
      window.location.replace(url);
    });

    // Direct Replacement button
    $(document).on('click', '.directreplace', function (e) {
      e.stopPropagation(); // Prevent parent .create click
      window.location.replace("{{ url('createreplacement') }}/");
    });






    $("#replacement").click(function () {
      var index = jQuery("#salesinvoicegrid").jqGrid('getGridParam', 'selrow');
      var quoteid = $("#salesinvoicegrid").jqGrid('getCell', index, 'invoice_hdr_id');
      var shiped_status = $("#salesinvoicegrid").jqGrid('getCell', index, 'shiped_status');
      var invoicetype = $("#salesinvoicegrid").jqGrid('getCell', index, 'invoice_type');

      if (quoteid) {
        if (shiped_status == "SHIPPED") {
          window.location.replace("{{URL::to('salesinvoicereplacement')}}/" + quoteid + "/" + invoicetype);
        } else {
          notyMsg('info', 'Only shipped data can be replacement');
        }
      } else {
        notyMsg('info', 'Please select row');
      }
    });


    $("#approval").click(function () {
      var index = jQuery("#salesinvoicegrid").jqGrid('getGridParam', 'selrow');
      var quoteid = $("#salesinvoicegrid").jqGrid('getCell', index, 'replacement_hdr_id');
      if (quoteid) {
        window.location.replace("{{URL::to('salesinvoicereplacementapprve')}}/" + quoteid);
      } else {
        notyMsg('info', 'Please select row');
      }
    });


    $("#editdata").click(function () {
      var gr = jQuery("#salesinvoicegrid").jqGrid('getGridParam', 'selrow');
      var cellValue = jQuery("#salesinvoicegrid").jqGrid('getCell', gr, 'replacement_hdr_id');
      var status = jQuery("#salesinvoicegrid").jqGrid('getCell', gr, 'invoice_status');
      if (gr) {
        if (status == "DRAFT") {
          var editUrl = '{{URL::to("salesreplacecreate")}}/' + cellValue;
          window.location.replace(editUrl);
        } else {
          notyMsg("info", "Only draft data can edit");
        }
      }
      else {
        notyMsg("info", "Please Select Row");
      }
    });




    /*Karthigaa Purpose For Delete Function*/
    $("#delete").click(function () {
      var gr = $("#salesinvoicegrid").jqGrid('getGridParam', 'selrow');
      var invoiceid = $("#salesinvoicegrid").jqGrid('getCell', gr, 'invoice_hdr_id');
      if (gr) {

        swal({
          title: "Are you sure?",
          text: "You want to delete!",
          type: "warning",
          showCancelButton: !0,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes",
          cancelButtonText: "No",
          closeOnConfirm: !1,
          closeOnCancel: !1
        }, function (e) {
          if (e == true) {
            var url = "{{ url('salesinvoicedelete')}}/" + invoiceid;
            var red_url = "{{ url('salesinvoice') }}";
            $.get(url, function (data) {
              var data = $.trim(data);
              //console.log(data);
              if (data == "1") {
                notyMsg('error', "You Can't delete , Sales In-voice Used in SomeWhere!!!", red_url);
                $('.cancel').trigger('click');

              }
              else {
                notyMsg('success', 'Deleted Successfully!!!', red_url);
                setTimeout(function () {
                  window.location.href = red_url;
                }, 1500);
              }
            });
          }
          else {
            $('.apply').css('display', 'none');
            swal("Cancelled");
          }

        })
        $('.apply').css('display', 'none');
        //window.location.replace('salesinvoicedelete/' +invoiceid);
      }
      else {
        notyMsg("info", "Please Select Row");
      }
    });


  </script>

@endpush