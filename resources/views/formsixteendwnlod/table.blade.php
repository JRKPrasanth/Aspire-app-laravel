@extends('layouts.header')
@section('content')
<h3 class="text-danger"> Employee Form 16 Download</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="formtbl" class="table table-bordered table-striped w-100">
        <thead>
   <tr class="table-warning">
  <th>Employee Number</th>
  <th>Employee Name</th>
  <th>Employee Type</th>
  <th>Financial Year</th>
  <th>Remarks</th>
  <th>Actions</th>
  </tr>
  <tr class="table-info">
  <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee Number</span></th>
  <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee Name</span></th>
  <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee Type</span></th>
  <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Financial Year</span></th>
  <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Remarks</span></th>
    <th></th>
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

$(document).ready(function () {
  var table = $('#formtbl').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('getFormsixteendnlodData') }}",
    columns: [
      { data: "employee_number"},
      { data: "first_name" },
      { data: "emp_type_name" },
     // { data: "position_name" },
     // { data: "job_title_name" },
      { data: "year" },
      { data: "remarks" },

      {
        data: "choosefile",
        name: "choosefile",
        orderable: false,
        searchable: false,
        className: "text-center",
        width: "140px",
        render: function (data, type, row) {
        
    return `<a target="_blank" href="${data}" class="btn btn-sm btn-primary">
              <i class="bi bi-download"></i>
            </a>`;
        }
      }
    ]
  });

  // Individual column search
  $('#formtbl thead').on('keyup change', '.column-search', function () {
    var colIndex = $(this).closest('th').index();
    table.column(colIndex).search(this.value).draw();
  });
});

 
	
</script>

@endpush
