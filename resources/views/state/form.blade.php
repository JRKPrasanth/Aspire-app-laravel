@extends('layouts.header')
@section('content')
  <h2 class="text-danger">State</h2>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form id="statesave" method="post" action="" data-parsley-validate>
        @csrf
        <input type="hidden" name="savestatus" id="savestatus" value="">
        <input type="hidden" name="edit_id" id="edit_id" value="{{ $row->state_id }}">

        <div class="row mb-4">
          <div class="col-md-6">
            <div class="row mb-3 align-items-center">
              <label class="col-sm-5 col-form-label">
                <span class="text-danger">*</span> Country Name
              </label>
              <div class="col-sm-6">
                <select name="country_id" id="country_id" class="form-select select2 country_id" required tabindex="1">
                  {!! $country_id !!}
                </select>
              </div>
            </div>

            <div class="row mb-3 align-items-center">
              <label class="col-sm-5 col-form-label">
                <span class="text-danger">*</span> State Name
              </label>
              <div class="col-sm-6">
                <input type="text" name="state_name" id="state_name" class="form-control state_name" tabindex="3"
                  required>
                <span class="btn btn-danger dup_name mt-2 d-none"></span>
              </div>
              <div class="col-sm-1"></div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="row mb-3 align-items-center">
              <label class="col-sm-5 col-form-label">State Code</label>
              <div class="col-sm-7">
                <input type="text" id="state_code" name="state_code" class="form-control state_code" tabindex="2">
              </div>
            </div>
          </div>
        </div>

        <div class="row text-center">
          <div class="col-md-12">
            <button type="button" id="save" class="btn btn-success saveform px-4" value="SAVE">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="StateTbl" class="table table-bordered table-striped">
        <thead>
          <tr class="table-warning">
            <th>State Name</th>
            <th>Country Name</th>
            <th>State Code</th>
            <th style="display: none;">Country ID</th>
            <th>Actions</th>
          </tr>
          <tr class="table-info">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" style="display: none;" class="form-control form-control-sm column-search"
                placeholder="Search Type" /></th>
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

    $(document).ready(function () {
      var table = $('#StateTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getstatedata') }}",
        columns: [

          { data: 'state_name', name: 'm_states_t.state_name' },
          { data: 'country_name', name: 'm_countries_t.country_name' },
          { data: 'state_code', name: 'm_states_t.state_code' },
          { data: 'country_id', name: 'm_countries_t.country_id', visible: false },

          {
            data: 'state_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
                    <button type="button" class="btn btn-sm btn-primary edit-btn"
                      data-id="${row.state_id}"
                      data-cid="${row.country_id}"
                      data-state="${row.state_name}"
                      data-code="${row.state_code}">  

                      <i class="bi bi-pencil"></i>
                    </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#StateTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });


    //Edit button
    $(document).on('click', '.edit-btn', function () {
      const btn = $(this);
      $('#edit_id').val(btn.data('id'));
      $('#country_id').val(btn.data('cid')).trigger('change');;
      $('#state_name').val(btn.data('state'));
      $('#state_code').val(btn.data('code'));

    });


    // save function
    let dup_chk = true;

    $(document).on('click', '.saveform', function () {

      var form = $("#statesave");
      form.parsley().validate();

      if (form.parsley().isValid() && dup_chk == true) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: "{{ URL::to('statedatasave') }}",
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