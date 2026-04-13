@extends('layouts.header')
@section('content')
<h3 class="text-danger">Location</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
<div class="card-body card-block">
   <div class="col-md-12">
<form method="post" action="" id="location_form" class="needs-validation" data-parsley-validate enctype="multipart/form-data" novalidate>
  @csrf

  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label"><span class="text-danger">*</span> Location Name</label>
      <input type="hidden" class="form-control" id="location_id" name="location_id" value="{{$row->location_id}}">
      <input type="text" id="location_name" name="location_name" class="form-control" value="{{$row->location_name}}" required>
      <div class="invalid-feedback dup_name d-none"></div>
    </div>

    <div class="col-md-4">
      <label class="form-label"><span class="text-danger">*</span> Location Code</label>
      <input type="text" id="location_code" name="location_code" class="form-control" value="{{$row->location_code}}" required>
    </div>

    <div class="col-md-4">
      <label class="form-label"><span class="text-danger">*</span> Location Type</label>
      
        <select name="location_type" class="form-select select2 location_type" required></select>
	<!--	<div class="input-group">
        <span class="input-group-text"><i class="fa fa-refresh jcr_product_group_id"></i></span>
      </div> -->
    </div>

    <div class="col-md-4">
      <label class="form-label">Address</label>
      <input type="text" id="address" name="address" class="form-control" value="{{$row->address}}">
    </div>

    <div class="col-md-4">
      <label class="form-label">Street Name</label>
      <input type="text" id="street_name" name="street_name" class="form-control" value="{{$row->street_name}}">
    </div>

    <div class="col-md-4">
      <label class="form-label"><span class="text-danger">*</span> Country</label>
      <select class="form-control select2 country_id" name="country_id"></select>
    </div>

    <div class="col-md-4">
      <label class="form-label"><span class="text-danger">*</span> State</label>
      <select name="state_id" class="form-select state_id select2" required></select>
    </div>

    <div class="col-md-4">
      <label class="form-label"><span class="text-danger">*</span> City</label>
      <select name="city_id" class="form-select city_id select2" required></select>
    </div>

    <div class="col-md-4">
      <label class="form-label"><span class="text-danger">*</span> Area</label>
		<select name="area" class="form-select area select2" required></select>
	  </div>
   <!--   <div class="col-md-1">
        <span class="input-group-text"><i class="fa fa-refresh jcr_product_group_id"></i></span>
      </div> -->


    <div class="col-md-4">
      <label class="form-label">Pincode</label>
      <input type="text" id="pincode" name="pincode" class="form-control" value="{{$row->pincode}}" maxlength="6">
      <div class="form-text text-danger">Please enter only numbers</div>
    </div>

    <div class="col-md-4">
      <label class="form-label">Active</label>
      <select name="active" class="form-select select2">
        <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
        <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
      </select>
    </div>
  </div>

  <div class="text-center mt-4">
    <button type="button" class="btn btn-success saveform px-4" value="SAVE">Save</button>
  </div>
</form>
 </div>
</div>
</div>
<div class="card shadow-lg rounded-4 border-0">
<div class="container mt-4">
  <table id="loctionTable" class="table table-bordered table-striped w-100">
    <thead>
      <tr class="table-warning">
        <th>Location Code</th>
        <th>Location Name</th>
        <th>Location Type</th>
		<th style="display: none;">Address</th>
		<th>Country</th>
	    <th style="display: none;">Country ID</th>
        <th>State</th>
        <th>City</th>
        <th>Active</th>
        <th>Actions</th>
      </tr>
      <tr class="table-info">
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Code" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Name" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Type" /></th>
		<th><input type="text" style="display: none;" class="form-control form-control-sm column-search" placeholder="Search Type" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Country" /></th>
		  <th><input type="text" style="display: none;" class="form-control form-control-sm column-search" placeholder="Search Type" /></th>
		<th><input type="text" class="form-control form-control-sm column-search" placeholder="Search State" /></th>
		<th><input type="text" class="form-control form-control-sm column-search" placeholder="Search City" /></th>
		 <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Status" /></th>
        <th></th>
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
$(document).ready(function() {
  var table = $('#loctionTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('getlocationData') }}",
    columns: [
	{ data: 'location_code', name: 'm_location_t.location_code' },
	{ data: 'location_name', name: 'm_location_t.location_name' },
	{ data: 'lookup_code', name: 'a_lookuplines_t.lookup_code' },
	{ data: 'address', name: 'address', visible: false },
	{ data: 'country_name', name: 'm_countries_t.country_name' },
	{ data: 'country_id', name: 'country_id', visible: false },
	{ data: 'state_name', name: 'm_states_t.state_name' },
	{ data: 'city_name', name: 'm_cities_t.city_name' },
	{ data: 'active', name: 'm_location_t.active' },
      {
        data: 'location_id',
        name: 'actions',
        orderable: false,
        searchable: false,
		className: 'text-center',
        width: '140px', 
		render: function (data, type, row) {
		let buttons = '';
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
			buttons += `<button class="btn btn-sm btn-primary me-1 edit-btn" 
			  data-id="${row.location_id}" 
			  data-name="${row.location_name}" 
			  data-code="${row.location_code}" 
			  data-type="${row.location_type}" 
			  data-address="${row.address}" 
			  data-street="${row.street_name}" 
			  data-country="${row.country_id}" 
			  data-state="${row.state_id}" 
			  data-city="${row.city_id}" 
			  data-area="${row.area_id}" 
			  data-pincode="${row.pincode}" 
			  data-active="${row.active}">
			  <i class="bi bi-pencil"></i>
			</button>`;
			}
			if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
            buttons += `
			<button class="btn btn-sm btn-danger delete-btn" data-id="${row.location_id}">
			  <i class="bi bi-trash"></i>
			</button>`;
			}
			return buttons;
		}
      }
    ]
  });

  // Individual column search
  $('#loctionTable thead').on('keyup change', ".column-search", function() {
    var colIndex = $(this).parent().index();
    table.column(colIndex).search(this.value).draw();
  });
});
// dropdown data purpose
$.ajax({
  url: "{{ url('jcomboformlogin') }}?table=a_lookuplines_t:lookuplines_id:lookup_code&parent=lookup_type='LOCATION_TYPES'&order_by=lookup_code",
  success: function (data) {
    $('.location_type').html('<option value="">-- Please Select --</option>');
    $.each(data, function (i, item) {
      $('.location_type').append(`<option value="${item.val}">${item.option_name}</option>`);
    });
  }
});
// on change purpose
	$.ajax({
  url: "{{ url('jcomboformlogin') }}?table=m_countries_t:country_id:country_name&order_by=country_name",
  success: function (data) {
    $('.country_id').html('<option value="">-- Select Country --</option>');
    $.each(data, function (i, item) {
      let selected = item.val == "{{ $row->country_id ?? '' }}" ? 'selected' : '';
      $('.country_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
    });
  }
});

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

	$(document).on('change', '.city_id', function () {
  let city_id = $(this).val();
  $('.area').html('<option value="">-- Loading Areas --</option>');

  if (city_id) {
    $.ajax({
      url: "{{ url('jcomboformlogin') }}?table=m_area_t:area_id:area_name&parent=city_id=" + city_id + "&order_by=area_name",
      success: function (data) {
        $('.area').html('<option value="">-- Select Area --</option>');
        $.each(data, function (i, item) {
          let selected = item.val == "{{ $row->area ?? '' }}" ? 'selected' : '';
          $('.area').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
        });
      }
    });
  }
});
// save function
let dup_chk = true;

$(document).on('click', '.saveform', function () {

var form = $("#location_form");
form.parsley().validate();

if (form.parsley().isValid() && dup_chk == true) {
  			var $btn = $(this);            
			$btn.prop('disabled', true);
    $.ajax({
        url: "{{ URL::to('locationsave') }}",
        type: "POST",
        data: form.serialize(),
        success: function (data) {
            // Show success message
           showCustomAlert('Saved successfully!', 'success');
            // Clear the form (optional)
            form[0].reset();
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
	//Edit button
	$(document).on('click', '.edit-btn', function () {
  const btn = $(this);

  $('#location_id').val(btn.data('id'));
  $('#location_name').val(btn.data('name'));
  $('#location_code').val(btn.data('code'));
  $('#address').val(btn.data('address'));
  $('#street_name').val(btn.data('street'));
  $('#pincode').val(btn.data('pincode'));

  // Populate select dropdowns with select2
  $('.location_type').val(btn.data('type')).trigger('change');
  $('.country_id').val(btn.data('country')).trigger('change');

  // wait for state to load after country
  setTimeout(() => {
    $('.state_id').val(btn.data('state')).trigger('change');
  }, 300);

  // wait for city to load after state
  setTimeout(() => {
    $('.city_id').val(btn.data('city')).trigger('change');
  }, 600);

  // wait for area to load after city
  setTimeout(() => {
    $('.area').val(btn.data('area')).trigger('change');
  }, 900);

  // Active
  $('select[name="active"]').val(btn.data('active')).trigger('change');
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
					url: "{{ url('locationdelete') }}/" + deleteId,
					type: "GET",
					success: function (response) {
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Deleted successfully!', 'success');
						$('#loctionTable').DataTable().ajax.reload();

					},
					error: function (xhr) {
						 $('#globalDeleteModal').modal('hide');
						const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
						showCustomAlert(errorMsg, 'error');
					}
				});
		}
	});
</script>
@endpush