@extends('layouts.header')
@section('content')
  <?php include('tools_menu.php');?>

  <?php if ($pageMethod == "myprofile") {?>
  <h3 class="mb-4 text-danger">My Profile </h3>
  @include('layouts.breadcrumb')
  <?php } else {?>
  <h3 class="mb-4 text-danger">Create User </h3>
  @include('layouts.breadcrumb')
  <?php } ?>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form autocomplete="off" id="user_form" data-parsley-validate enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="savestatus" id="savestatus" />

        <div class="container mt-4">
          <div class="row mb-4 pagemethod">
            <div class="col-md-4">
              <label class="form-label">* User Name</label>
              <input type="text" id="username" name="username" class="form-control" value="{{ $row->username }}" required>
              <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $row->employee_id; ?>" />
              <input class="form-control user_id" id="user_id" name="user_id" size="16" type="hidden"
                value="{{$row->user_id}}">
            </div>
            <div class="col-md-4">
              <label class="form-label">* First Name</label>
              <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $row->first_name }}"
                required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Last Name</label>
              <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $row->last_name }}">
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-md-4 pagemethod">
              <label class="form-label">* Email</label>
              <input type="email" id="email" name="email" class="form-control" value="{{ $row->email }}" required>
              <div class="invalid-feedback d-none email_vali">Invalid email format</div>
            </div>
            <div class="col-md-4">
              <label class="form-label">* Mobile Number</label>
              <input type="text" id="mobile_no" name="mobile_no" maxlength="12" class="form-control"
                value="{{ $row->mobile_no }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Avatar</label><br>
              @php
                $image = $row->avatar == '' ? 'profile_none.jpg' : $row->avatar;
              @endphp
              <img src="{{ asset('images/profile_images/' . $image) }}" alt="Avatar" class="img-thumbnail mb-2" width="40">
              <input type="file" name="avatar" class="form-control">
            </div>
          </div>

          <div class="row mb-4 pagemethod">
            <div class="col-md-4">
              <label class="form-label">* Group</label>
              <select name="group_id" class="form-select select2" required>{!! $row->group_id !!}</select>
            </div>
            <div class="col-md-4">
              <label class="form-label">* Company</label>
              <select name="company_id" class="form-select select2" required>{!! $row->comp_id !!}</select>
            </div>
            <div class="col-md-4">
              <label class="form-label">* Department</label>
              <select name="admindept_id[]" multiple class="form-select select2"
                required>{!! $row->admindept_id !!}</select>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-md-4 pagemethod">
              <label class="form-label">* Location</label>
              <select name="loc_id[]" multiple class="form-select select2" required>{!! $row->loc_id !!}</select>
            </div>
            <div class="col-md-4 pagemethod">
              <label class="form-label">* Active</label>
              <select name="active" class="form-selec select2" required>
                <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">{{ $row->user_id ? 'Change Password' : 'Password' }}</label>
              <input type="password" name="password" id="password" class="form-control">
              <small id="result" class="form-text text-muted">Password must contain capital & special characters</small>
            </div>
          </div>

          @if($pageMethod == "myprofile")
            <div class="row mb-4">
              <div class="col-md-6">
                <label class="form-label">User Mail</label>
                <input type="email" id="user_mail" name="user_mail" class="form-control" value="{{ $row->user_mail }}">
                <div class="invalid-feedback d-none email_user_mail">Invalid email format</div>
              </div>
              <div class="col-md-6">
                <label class="form-label">User Password</label>
                <input type="password" name="user_password" class="form-control">
              </div>
            </div>
          @endif

          <div class="text-center mt-4">
            <button type="button" class="btn btn-success me-2 saveform px-4" value="SAVE">Save</button>
            <a href="{{ $pageMethod == 'myprofile' ? url('home') : url('user') }}"
              class="btn btn-secondary px-4">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>

@endsection
@push('scripts')

  <script>

    $(document).ready(function () {
      var profile = '<?php echo $pageMethod; ?>';
      if (profile == "myprofile") {
        $('.pagemethod').attr('readonly', true);
        $('.pagemethod').css('pointer-events', 'none');
        $('.activecol').hide();
      } else {
        $('.pagemethod').attr('readonly', false);
        $('.pagemethod').css('pointer-events', 'auto');
        $('.activecol').show();

      }

      var id = "{{$row->user_id}}";
      if (!id) {
        $('.username').val(" ");
        $('.password').val("");
      }
      $('.read').css('pointer-events', 'none');
      $('.company').css('pointer-events', 'none');

      /**************** email validation end ***********/

      $(function () {

        $('.password').keyup(function () {
          var pass_check = checkStrength($('.password').val());
          if (pass_check == "Too short" || pass_check == "Weak")
            $('#result').css("color", "red");
          else
            $('#result').css("color", "green");

          $('#result').html(pass_check);

        })

        function checkStrength(password) {
          /*initial strength*/
          var strength = 0

          /*if the password length is less than 6, return message.*/
          if (password.length < 5) {
            $('#result').removeClass()
            $('#result').addClass('short')
            return 'Too short'
          }

          /*length is ok, lets continue.*/

          /*if length is 8 characters or more, increase strength value*/
          if (password.length > 5) strength += 1

          /*if password contains both lower and uppercase characters, increase strength value*/
          if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1

          /*if it has numbers and characters, increase strength value*/
          if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1

          /*if it has one special character, increase strength value*/
          if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1

          /*if it has two special characters, increase strength value*/
          if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1

          /*now we have calculated strength value, we can return messages*/

          /*if value is less than 2*/
          if (strength < 2) {
            $('#result').removeClass()
            $('#result').addClass('weak')
            return 'Weak'
          }
          else if (strength == 2) {
            $('#result').removeClass()
            $('#result').addClass('good')
            return 'Good'
          }
          else {
            $('#result').removeClass()
            $('#result').addClass('strong')
            return 'Strong'
          }
        }
      });

      jQuery('.mobile_no').keyup(function () {
        this.value = this.value.replace(/[^0-9\.]/g, '');
      });
      $('.first_name,.last_name').keyup(function () {
        $(this).val($(this).val().toUpperCase());
      });

      $('#savestatus').val('');


      function ValidateEmail(email) {

        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
      };

      // save function
      $(document).on('click', '.saveform', function () {
        // email check (unchanged) ...
        const form = $("#user_form");
        let dup_chk = true;
        form.parsley().validate();
        if (form.parsley().isValid() && dup_chk === true) {
          var $btn = $(this);
          $btn.prop('disabled', true);
          const formData = new FormData(form[0]); // <-- includes files

          $.ajax({
            url: "{{ url('usersave') }}",
            type: "POST",
            data: formData,
            processData: false,         
            contentType: false,          
            // optional but nice if your app expects header token:
            headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
            success: function (response) {
              if (response.status === "success") {
                showCustomAlert(response.message || 'Saved Successfully!','success');
                setTimeout(() => { window.location.href = "{{ url('user') }}"; }, 1500);
              } else {
                showCustomAlert(response.message || 'Save failed. Please check your input.','error');
              }
            },
            error: function (xhr) {
              let errorMsg = 'Unexpected error occurred.';
              if (xhr.responseJSON && xhr.responseJSON.message) { errorMsg = xhr.responseJSON.message; }
              showCustomAlert(errorMsg, 'error');
            }
          });
        } else {
          showCustomAlert("Please fill out all required fields correctly.",'warning');
        }
      });



    });

  </script>
@endpush