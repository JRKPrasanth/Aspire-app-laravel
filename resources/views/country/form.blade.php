@extends('layouts.header')
@section('content')
  <h2 class="text-danger">Country</h2>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form method="post" action="" id="countrysave" data-parsley-validate>
        @csrf
        <input type="hidden" name="savestatus" id="savestatus" value="">
        <input type="hidden" name="edit_id" id="edit_id" value="">

        <div class="row mb-3">
          <div class="col-md-8">
            <div class="row mb-3 align-items-center">
              <label class="col-sm-5 col-form-label">
                <span class="text-danger">*</span> Country Name
              </label>
              <div class="col-sm-7">
                <input type="text" id="country_name" name="country_name" tabindex="1" class="form-control country_name"
                  required>
                <span class="btn btn-danger dup_name mt-2 d-none"></span>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="text-center">
            <button type="button" id="save" class="btn btn-success saveform px-4" value="SAVE">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>



  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="CountryTbl" class="table table-bordered table-striped">
        <thead>
          <tr class="table-warning">
            <th>Country</th>
            <th>Actions</th>
          </tr>
          <tr class="table-info">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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
      var table = $('#CountryTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getcountryData') }}",
        columns: [

          { data: 'country_name', name: 'm_countries_t.country_name' },

          {
            data: 'country_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
                    <button type="button" class="btn btn-sm btn-primary edit-btn"
                      data-id="${row.country_id}"
                      data-country="${row.country_name}">  
                      <i class="bi bi-pencil"></i>
                    </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#CountryTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });



    //Edit button
    $(document).on('click', '.edit-btn', function () {
      const btn = $(this);

      $('#edit_id').val(btn.data('id'));
      $('#country_name').val(btn.data('country'));

    });


    // save function
    let dup_chk = true;

    $(document).on('click', '.saveform', function () {

      var form = $("#countrysave");
      form.parsley().validate();

      if (form.parsley().isValid() && dup_chk == true) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: "{{ URL::to('countrysave') }}",
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