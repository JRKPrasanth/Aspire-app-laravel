@extends('layouts.header')
@section('content')
<h3 class="text-danger"> Journal Entry</h3>
@include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3 mt-1"></div>


<div class="card shadow-lg rounded-4 border-0">
	    <div class="card-header bg-primary text-white fw-semibold">
        <i class="bi bi-funnel me-2"></i>Filter Options
    </div>
    <div class="card-body p-4">
        <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate="" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="_token" value="LqlLcWQshCMsfvBs48tEi6tsnSJ5tEmlTlirqgeh">
            <!-- First Row: Date Inputs -->
            <div class="row g-4 mb-3">
				<div class="col-md-2"></div>
                <div class="col-md-4">
                    <label for="start_date" class="form-label fw-semibold">From Date</label>
                    <input type="text" class="form-control start_date" id="start_date" name="start_date" required="" autocomplete="off">
                    <div class="invalid-feedback">Please select a start date.</div>
                </div>

                <div class="col-md-4">
                    <label for="end_date" class="form-label fw-semibold">To Date</label>
                    <input type="text" class="form-control end_date" id="end_date" name="end_date" required="" autocomplete="off" disabled="">
                    <div class="invalid-feedback">Please select an end date.</div>
                </div>
            </div>

            <!-- Second Row: Centered Search Button -->
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-primary px-4 report_search" id="report_search">
                        <i class="bi bi-search-heart me-1"></i> Search
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
          <th>Journal Name</th>
          <th>Journal Date</th>
          <th>Journal Type</th>
          <th>Journal Status</th>
          <th>Ref source</th>
          <th>Ref Name</th>
          <th>Actions</th>
          </tr>
          <tr class="table-danger">
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
	
  const userGroup = "{{ Session::get('groupname') }}";
  console.log (userGroup);

    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
              <button class="btn btn-success text-white px-4 create me-2">Create
                <i class="bi bi-plus-circle"></i> 
              </button>
            `);
      }
    });	
	
	
	
    $(document).ready(function () {

       var status ="{{$status}}";
		console.log(status);
      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: true,
		ajax: {
        url: "getJournalentryData?status="+status,
        type: "GET",
        data: function(d) {
          d.start_date = $('#start_date').val();
		  d.end_date = $('#end_date').val();
        }
      },  

      columns: [

      { data: 'journal_name', name: 'journal_name' },
      { data: 'journal_date', name: 'journal_date' },
      { data: 'journal_type', name: 'journal_type' },
      { data: 'journal_status', name: 'journal_status' },
      { data: 'reference_source', name: 'reference_source' },
      { data: 'namee', name: 'namee' },

       {
            data: 'journal_entry_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
        <button class="btn btn-sm btn-warning view-btn me-1" data-id="${row.journal_entry_id}">
          <i class="bi bi-eye"></i>
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.journal_entry_id}" data-status="${row.journal_status}">
          <i class="bi bi-pencil"></i>
        </button>`;
              }
              
				              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                buttons += `
        <button class="btn btn-sm btn-success approve-btn" data-id="${row.journal_entry_id}" data-status="${row.journal_status}">
         Approve
        </button>`;
              }
				
              return buttons;
            }
          }

        ]
      });

		
	    // Trigger search
    $('.report_search').on('click', function () {
      $('#AccTbl').DataTable().ajax.reload();
    });
		
      // Individual column search
      $('#AccTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });
	
	
// create 
	
      $(".create").click(function(){
	var url="{{  URL::to('journalentrycreate')}}";
	window.location.replace(url);

	});
	
    //edit function
    $(document).on('click', '.edit-btn', function () {
		
        const id = $(this).data('id');
        const status = $(this).data('status');

              if(userGroup == "1"){
              //if( status != "POSTED" && status != "APPROVED" && status != "SUBMITTED"){
	      	var url = "{{ url('journalentrycreate') }}";
                var editUrl = url + '/' + id;
		window.location.replace(editUrl);
                }
            else{
               showCustomAlert('Approved,Submitted,Posted JOURNALS Cannot Be Edit!!!','error');
            }
    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('journalentryview') }}/" + id;
      window.open(url, '_blank');
    });
	

// approve
	
             $(document).on('click', '.approve-btn', function () {
                

                        const id = $(this).data('id');
                        const status = $(this).data('status');


                    window.location.replace('journalapproval/' +id+'/'+status);


            });
	
	
</script>

@endpush
