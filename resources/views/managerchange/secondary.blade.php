@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Secondary Manager Change </h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold">
    </div>
    <div class="card-body p-4">
      <form method="post" action="" id="manager_form" class="needs-validation" novalidate=""
        enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="_token" value="tfdiEpoBfETz785FMRCa8vcN5U2fNFqxcS5aXZRo">
        <!-- First Row: Date Inputs -->
        <div class="row g-4 mb-3">

          <div class="col-md-4">
            <label for="from_manager" class="form-label fw-semibold">Old Manager</label>
            <select type="text" class="form-control from_manager select2" id="from_manager" name="from_manager" required>
                        <option value="">-- please select --</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->name }}">{{ $manager->name }}</option>
                        @endforeach
           </select>
          </div>

          <div class="col-md-4">
            <label for="new_manager" class="form-label fw-semibold">New Manager</label>
            <input type="text" class="form-control new_manager" id="new_manager" name="new_manager" required>
          </div>

         <div class="col-md-4">
            <label for="hq" class="form-label fw-semibold">Head Quarters</label>
            <select type="text" class="form-control select2" id="hq" name="hq[]" required multiple>
                        <option value="">-- please select --</option>
                        @foreach($hqs as $hq)
                            <option value="{{ $hq->hq_name }}">{{ $hq->hq_name }}</option>
                        @endforeach
            </select>
          </div>


        </div>

        <!-- Second Row: Centered Search Button -->
        <div class="row mt-4">
          <div class="col-md-12 text-center">
            <button type="button" class="btn btn-success px-4 saveform" value="SAVE">
             Update
            </button>
          </div>
        </div>

      </form>
    </div>
  </div>




@endsection
@push('scripts')

  <script>

// save function
	let dup_chk = true;
	
	$(document).on('click', '.saveform', function () {
	
    var form = $("#manager_form");
    form.parsley().validate();

    if (form.parsley().isValid() && dup_chk == true) {
      
            var $btn = $(this);            
			$btn.prop('disabled', true);

        $.ajax({
            url: "{{ URL::to('secmanagersave') }}",
            type: "POST",
            data: form.serialize(),
            success: function (data) {
                // Show success message
               showCustomAlert('Updated successfully!','success');
                window.location.reload();
            },
            error: function (xhr) {
              showCustomAlert('Update failed. Try again.', 'error');
            }
        });
    }
});


  </script>

@endpush