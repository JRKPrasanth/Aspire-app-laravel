@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Training Request Approve</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="TopicTbl" class="table table-striped table-bordered w-100">
          <thead>
            <tr class="table-warning">
              <th>Department</th>
              <th>Topic</th>
              <th>Requested By</th>
              <th>Request Type</th>
              <th>Created By</th>
              <th>Actions</th>
            </tr>

            <tr class="table-success">
              <th><input type="text" placeholder="Search" /></th>
              <th><input type="text" placeholder="Search" /></th>
              <th><input type="text" placeholder="Search" /></th>
              <th><input type="text" placeholder="Search" /></th>
              <th><input type="text" placeholder="Search" /></th>

              <th></th>
            </tr>

          </thead>
        </table>
      </div>
    </div>
  </div>

@endsection
@push('scripts')

  <script>
    // data table funcrion	
    $(document).ready(function () {
      var table = $('#TopicTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('gettrainingrequestapprovegrid') }}",
        columns: [
          { data: 'sub_department_name', name: 'm_department_lines_t.sub_department_name' },
          { data: 'topic_name', name: 'topic_name' },
          { data: 'employee_name', name: 'employee_name' },
          { data: 'request_type', name: 'request_type' },
          { data: 'first_name', name: 'first_name' },
          {
            data: 'training_request_id',
            name: 'actions',
            width: '120px',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              return `
              <button type="button" class="btn btn-sm btn-success approve-btn" data-id="${data}">Approve</button>`;
            }
          }
        ]
      });



      $('#TopicTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

    /*End*/
    /*approve Function*/
    $(document).on('click', '.approve-btn', function () {

      const id = $(this).data('id');
      const url = "{{ url('trainingrequestapprovecreate') }}/" + id;
      window.location.href = url;

    });

  </script>

@endpush