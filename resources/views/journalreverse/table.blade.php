@extends('layouts.header')
@section('content')
<h3 class="text-danger">Journal Reverse</h3>
@include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
          <th>Journal Name</th>
          <th>Journal Date</th>
          <th>Journal Type</th>
          <th>Journal Status</th>
          <th>Actions</th>
          </tr>
          <tr class="table-danger">
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
    $(document).ready(function () {


      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "getJournalreverseData",
        columns: [


      { data: 'journal_name', name: 'journal_name' },
      { data: 'journal_date', name: 'journal_date' },
      { data: 'journal_type', name: 'journal_type' },
      { data: 'journal_status', name: 'journal_status' },

    {
            data: 'journal_entry_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'reverse')) {
                buttons += `
        <button class="btn btn-sm btn-primary rev-btn" data-id="${row.journal_entry_id}">
         Reverse
        </button>`;
              }


              return buttons;
            }
          }

        ]
      });

      // Individual column search
      $('#AccTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });
	

$(document).on('click', '.rev-btn', function () {
	
      const id = $(this).data('id');
      const url = "{{ url('journalreverseview') }}/" + id;
      window.location.href = url;
});	
	
	
</script>

@endpush
