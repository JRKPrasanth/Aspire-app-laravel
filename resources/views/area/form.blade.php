@extends('layouts.header')
@section('content')
<h2 class="text-danger">Area</h2>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body mt-2">
        <form id="area_form" method="post" action="" data-parsley-validate>
            @csrf
            <input type="hidden" name="savestatus" id="savestatus" value="" />
            <input type="hidden" name="edit_id" id="edit_id" value="" />

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">
                            <span class="text-danger">*</span> Country
                        </label>
                        <div class="col-sm-6">
                            <select name="country_id" class="form-select select2 country_id" required>
                                {!! $country_id !!}
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">
                            <span class="text-danger">*</span> State
                        </label>
                        <div class="col-sm-6">
                            <select name="state_id" class="form-select select2 state_id" required></select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">
                            <span class="text-danger">*</span> City
                        </label>
                        <div class="col-sm-6">
                            <select name="city_id" class="form-select select2 city_id" required></select>
                        </div>
                    </div>

                    <div class="mb-3 row d-none">
                        <label class="col-sm-5 col-form-label">Active</label>
                        <div class="col-sm-7">
                            <select name="active" class="form-select select2 active">
                                <option value="Yes" selected>Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-5 col-form-label">
                            <span class="text-danger">*</span> Area
                        </label>
                        <div class="col-sm-6">
                            <input type="text" name="area_name" id="area_name" class="form-control area_name" required>
                        </div>
                    </div>

                    <div class="mb-3 row d-none">
                        <label class="col-sm-5 col-form-label">Created By</label>
                        <div class="col-sm-6">
                            <select name="created_by" id="created_by" class="form-select select2 created_by">
                                {!! $created_by !!}
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="button" class="btn btn-success saveform px-4">Save</button>
            </div>
        </form>
    </div>
</div>

      
<div class="card shadow-lg rounded-4 border-0">
<div class="container mt-4 table-responsive">
  <table id="AreaTbl" class="table table-bordered table-striped w-100">
    <thead>
      <tr class="table-warning">
		    <th style="display: none;">area ID</th>
        <th>Area</th>
        <th>City</th>
        <th>State</th>
        <th>Country</th>
        <th>Created By</th>
        <th>Actions</th>
      </tr>
      <tr class="table-info">
        <th><input type="text" style="display: none;" class="form-control form-control-sm column-search" placeholder="Search Type" /></th>
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

	  $(document).ready(function () {
    var table = $('#AreaTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('getareagrid') }}",
      columns: [
        { data: 'area_id', name: 'area_id', visible: false },
        { data: 'area_name', name: 'm_area_t.area_name' },
        { data: 'city_name', name: 'm_cities_t.city_name' },
        { data: 'state_name', name: 'm_states_t.state_name' },
        { data: 'country_name', name: 'm_countries_t.country_name' },
        { data: 'first_name', name: 'tb_users.first_name' },
        {
          data: 'area_id',
          name: 'actions',
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                      buttons += `
                  <button type="button" class="btn btn-sm btn-primary edit-btn"
                    data-id="${row.area_id}"
                    data-country="${row.country_id}" 
                    data-city="${row.city_id}"
                    data-state="${row.state_id}"
					 data-name="${row.area_name}"
                    data-country="${row.country_name}">
                
                    <i class="bi bi-pencil"></i>
                  </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                      buttons += `
                      <button type="button" class="btn btn-sm btn-danger delete-btn"
                        data-id="${row.organization_id}">
                        <i class="bi bi-trash"></i>
                      </button>`;
              }
              return buttons;
            }
            
        }
      ]
    });
  
  
    $('#AreaTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });

// on change functions
	
		$(document).on('change', '.country_id', function () {
  let country_id = $(this).val();
  $('.state_id').html('<option value="">-- Loading States --</option>');

  if (country_id) {
    $.ajax({
      url: "{{ url('jcomboformlogin') }}?table=m_states_t:state_id:state_name&parent=country_id=" + country_id + "&order_by=state_name",
      success: function (data) {
        $('.state_id').html('<option value="">-- Select State --</option>');
        $.each(data, function (i, item) {
          let selected = item.val == "{{ $row->state_id ?? '' }}" ? 'selected' : '';
          $('.state_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
        });
      }
    });
  }
});

	$(document).on('change', '.state_id', function () {
  let state_id = $(this).val();
  $('.city_id').html('<option value="">-- Loading Cities --</option>');

  if (state_id) {
    $.ajax({
      url: "{{ url('jcomboformlogin') }}?table=m_cities_t:city_id:city_name&parent=state_id=" + state_id + "&order_by=city_name",
      success: function (data) {
        $('.city_id').html('<option value="">-- Select City --</option>');
        $.each(data, function (i, item) {
          let selected = item.val == "{{ $row->city_id ?? '' }}" ? 'selected' : '';
          $('.city_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
        });
      }
    });
  }
});
	
	
// Edit
		//Edit button
	$(document).on('click', '.edit-btn', function () {
  const btn = $(this);

  $('#edit_id').val(btn.data('id'));
  $('#area_id').val(btn.data('country'));
  $('#location_code').val(btn.data('code'));
  $('#area_name').val(btn.data('name'));
  $('#street_name').val(btn.data('street'));
  $('#pincode').val(btn.data('pincode'));

  // Populate select dropdowns with select2
  $('.country_id').val(btn.data('country')).trigger('change');

  // wait for state to load after country
  setTimeout(() => {
    $('.state_id').val(btn.data('state')).trigger('change');
  }, 300);

  // wait for city to load after state
  setTimeout(() => {
    $('.city_id').val(btn.data('city')).trigger('change');
  }, 600);

});
	
	
	// delete function
	let deleteId = null; 

	$(document).on('click', '.delete-btn', function () {
		deleteId = $(this).data('id'); 
		$('#globalDeleteModal').modal('show'); 
	});

	$('#globalConfirmDeleteBtn').on('click', function () {
		if (deleteId) {
				$.ajax({
					url: "{{ url('areadelete') }}/" + deleteId,
					type: "GET",
					success: function (response) {
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Deleted successfully!', 'success');
						$('#AreaTbl').DataTable().ajax.reload();

					},
					error: function (xhr) {
						 $('#globalDeleteModal').modal('hide');
						const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
						showCustomAlert(errorMsg, 'error');
					}
				});
		}
	});	
	
	
	
	// save function
	let dup_chk = true;
	
	$(document).on('click', '.saveform', function () {
	
    var form = $("#area_form");
    form.parsley().validate();

    if (form.parsley().isValid() && dup_chk == true) {
      var $btn = $(this);            
			$btn.prop('disabled', true);
        $.ajax({
            url: "{{ URL::to('areasave') }}",
            type: "POST",
            data: form.serialize(),
            success: function (data) {
                // Show success message
               showCustomAlert('Saved successfully!', 'success');
                $('.select2').val('').trigger('change');
                // Reload DataTable
                window.location.reload();
            },
            error: function (xhr) {
              showCustomAlert('Save failed. Try again.', 'error');
            }
        });
    }
});	
	
	
	
	
	
      

	</script>
@endpush
