@extends('layouts.header')
@section('content')
<h3 class="text-danger">Goods Inward Note</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3 mt-1"></div>

<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="ginTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

            <th>Actions</th>
            <th>GIN Number</th>
            <th>Supplier Type</th>
            <th>Supplier Name</th>
            <th>DC Number</th>
            <th>DC Date</th>
            <th>Total Packs</th>
            <th>GIN Status</th>

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
          {{-- DataTable will populate via AJAX --}}
        </tbody>
      </table>
    </div>
  </div>
</div>


@endsection
@push('scripts')


<script>

    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'gnerategin')) {
        $('#toolbar-container').append(`
                  <button class="btn btn-success text-white px-4 generate-btn create me-2">Generate GIN
                  </button>
                `);
      }
    });
	
  // table data

  $(document).ready(function () {

    var table = $('#ginTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "getgoodsinwardData",

      columns: [

        {

          data: 'p_gin_hdr_id',
          name: 'actions',
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
            let buttons = '';
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
              buttons += `
				<button type="button" class="btn btn-sm btn-primary edit-btn"
				  data-id="${row.p_gin_hdr_id}"
				  data-status="${row.gin_status}">
				  <i class="bi bi-pencil"></i>
				</button>`;
            }

            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
              buttons += `
					<button type="button" class="btn btn-sm btn-warning view-btn"
					  data-id="${row.p_gin_hdr_id}">
					  <i class="bi bi-eye"></i>
					</button>`;
            }


            return buttons;
          }

        },

        { data: 'gin_number', name: 'gin_number' },
        { data: 'supplier_type', name: 'supplier_type' },
        { data: 'supplier_name', name: 'supplier_name' },
        { data: 'dc_number', name: 'dc_number' },
        { data: 'dc_date', name: 'dc_date' },
        { data: 'total_packs', name: 'total_packs' },
        { data: 'gin_status', name: 'gin_status' },
      ]
    });


    $('#ginTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });

  // edit	

  $(document).on('click', '.edit-btn', function () {

    const id = $(this).data('id');
    const status = $(this).data('status');

    if (status != "INITIATED") {
      window.location.replace('goodsinwardnoteedit/' + id);
		
    } else {
		
      showCustomAlert("GIN is sumbitted can't be Edited", 'info');
    }

  });

  // generate

  $(document).on('click', '.generate-btn', function () {

    window.location.replace('goodsinwardnotecreate');

  });

  // view

  $(document).on('click', '.view-btn', function () {

    const id = $(this).data('id');

    window.location.replace('goodsinwnoteviewtbl/' + id);

  });


</script>


@endpush