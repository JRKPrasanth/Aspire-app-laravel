{{-- resources/views/rd/index.blade.php --}}
@extends('layouts.header')
@section('content')
  <div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0 text-danger">R & D Entries</h3>
      <a href="{{ route('create') }}" class="btn btn-success product_group">
        + New R&D Entry
      </a>
    </div>

    {{-- DATATABLE --}}
    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-body">
        <table id="rdTable" class="table table-bordered table-striped table-hover w-120">
          <thead class="table-warning">
            <tr>
              <th>Project</th>
              <th>Batch</th>
              <th>Product</th>
              <th>Stage</th>
              <th>Test Result</th>
              <th>Test Observation</th>
              <th>Output Status</th>
              <th>Review Comments</th>
              <th>Status</th>
              <th>Created</th>
              <th>Updated</th>
              <th width="80">Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>

  </div>
@endsection

@push('scripts')

  <script>



    $(document).ready(function () {

      var table = $('#rdTable').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        order: [[1, 'desc']],
        ajax: "{{ url('data') }}",

        columns: [
          { data: 'project_name', name: 'project_name' },
          { data: 'batch_no', name: 'batch_no' },
          { data: 'product_name', name: 'product_name' },
          { data: 'current_stage', name: 'current_stage' },
          { data: 'test_results', name: 'test_results' },
          { data: 'test_observations', name: 'test_observations' },
          { data: 'output_status', name: 'output_status' },
          { data: 'review_comments', name: 'review_comments' },
          {
            data: 'approval_status',
            name: 'approval_status',
            render: function (data, type, row) {
              let badge = 'danger';

              if (data === 'Approved') {
                badge = 'success';
              } else if (data === 'Pending') {
                badge = 'warning';
              }

              return `<span class="badge bg-${badge} text-capitalize">
                                ${data ?? 'rejected'}
                            </span>`;
            }
          },

            { data: 'created_at', name: 'created_at' },
          { data: 'updated_at', name: 'updated_at' },
          {
            data: 'id',
            orderable: false,
            searchable: false,
            render: function (id) {
              return `
                          <a href="edit/${id}" <button type="button" class="btn btn-sm btn-primary edit-btn"><i class="bi bi-pencil"></i></button></a>
                          <a href="pdf/${id}" <button type="button" class="btn btn-sm btn-info pdf-btn"><i class="bi bi-file-pdf-fill"></i></button></a>

                          <button type="button" class="btn btn-sm btn-success approve-btn" data-id="${id}"><i class="bi bi-check2-circle"></i></button>
                      `;
            }
          }
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

      // Trigger search
      $('.report_search').on('click', function () {
        $('#rdTable').DataTable().ajax.reload();
      });

    });


    $(document).on('click', '.approve-btn', function (e) {
      e.preventDefault();
      let id = $(this).data('id');
      let review_comments = prompt('Approval remarks');

      if (!review_comments) return;

      $.ajax({
        url: 'approve/' + id,
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          review_comments: review_comments
        },
        success: function () {
          $('#rdTable').DataTable().ajax.reload(null, false);
        },
        error: function (xhr) {
          alert(xhr.responseText);
        }
      });
    });


  </script>

@endpush