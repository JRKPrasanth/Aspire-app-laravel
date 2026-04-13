@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Material Return Details</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="ReturnTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Job No</th>
            <th>Job Date</th>
            <th>Product Name</th>
            <th>Batch No</th>
            <th>Job Status</th>
            <th>Reference No</th>
            <th>Job Qty</th>
            <th>Actions</th>
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

    var pageurl = '<?php echo $pageMethod; ?>';

    $(document).ready(function () {
      var table = $('#ReturnTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "getmaterialreturnData",
        columns: [

          { data: 'job_no', name: 'job_no' },
          { data: 'job_date', name: 'job_date' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'batch_no', name: 'batch_no' },
          { data: 'job_status', name: 'job_status' },
          { data: 'reference_no', name: 'reference_no' },
          { data: 'job_adjusted_qty', name: 'job_adjusted_qty' },

          {
            data: 'qa_submitstage_trx_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
                buttons += `
          <button class="btn btn-sm btn-danger view-btn" data-id="${row.qa_submitstage_trx_hdr_id}" data-status="${row.job_status}">
            Return
          </button>`;
              }

              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#ReturnTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });

    // return

    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      var url = "{{ URL::to('materialreqcreate') }}";
      var editUrl = url + '/' + id + '?src=JOB';
      window.location.replace(editUrl);
    });





  </script>

@endpush