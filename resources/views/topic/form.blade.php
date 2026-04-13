@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Topic</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body card-block">
      <form id="prdsubcat" method="post" action="" data-parsley-validate>
        <input type="hidden" value="" name="savestatus" id="savestatus" />
        <input type="hidden" name="edit_id" value="" id="edit_id" />{{ csrf_field()}}
        <div class="row">

          <div class="col-md-4">

            <div class="form-group row">
              <label for="inputIsValid" class="form-control-label col-md-3"><span style="color:red;">*</span>Topic</label>
              <div class="col-md-8 sel2">
                <input type="text" class="form-control topic_name" name="topic_name" id="topic_name" required="">
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group row">
              <label for="inputIsValid" class="form-control-label col-md-3">Remarks</label>
              <div class="col-md-8 sel2">

                <input type="text" class="form-control remarks" name="remarks" id="remarks">
              </div>
            </div>
          </div>
          <div class="col-md-4">

            <div class="form-group row">
              <label for="inputIsValid" class="form-control-label  col-md-3">Active</label>
              <div class="col-md-6">
                <select name="active" tabindex="5" class="form-control select2 active" id="active">
                  <option value="Yes" selected>Yes</option>
                  <option value="No">No</option>
                </select>
              </div>
            </div>

            <div class="form-group row" style="display: none;">
              <label for="active" class="form-control-label col-md-5">Created By</label>
              <div class="col-md-6" style="pointer-events:none;">
                <select name='created_by' rows='5' tabindex="6" class='select2 created_by' id="created_by">
                  {!! $created_by !!}
                </select>
              </div>
            </div>

          </div>
        </div>

        <div class="col-12 text-center mt-4">
          <button type="button" class="btn btn-success saveform px-4" value="SAVE">Save</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="TopicTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>Topic</th>
              <th>Remarks</th>
              <th>Status</th>
              <th>Created By</th>
              <th>Actions</th>
            </tr>

            <tr class="table-success">
              <th><input type="text" placeholder="Search" /><span style="display: none;">Topic</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Remarks</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Status</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Created By</span></th>
              <th></th>
            </tr>

          </thead>
        </table>
      </div>
    </div>
  </div>

@endsection
@push('scripts')

  <script>
    // data table funcrion	
    $(document).ready(function () {
      var table = $('#TopicTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('gettopicgrid') }}",
        columns: [
          { data: 'topic_name', name: 'topic_name' },
          { data: 'remarks', name: 'remarks' },
          { data: 'active', name: 'active' },
          { data: 'username', name: 'username' },

          {
            data: 'topic_id',
            name: 'actions',
            width: '150px',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-info edit-btn"
              data-id="${row.topic_id}"
              data-name="${row.topic_name}"
              data-remark="${row.remarks}"
              data-active="${row.active}">
              <i class="bi bi-pencil"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
              <button type="button" class="btn btn-sm btn-danger delete-btn"
                data-id="${row.topic_id}">
                <i class="bi bi-trash"></i>
              </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#TopicTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

    // save function


    $(document).on('click', '.saveform', function () {
      let dup_chk = true;
      var form = $("#prdsubcat");
      form.parsley().validate();

      if (form.parsley().isValid() && dup_chk == true) {

        var $btn = $(this);
        $btn.prop('disabled', true);

        $.ajax({
          url: "{{ URL::to('topicsave') }}",
          type: "POST",
          data: form.serialize(),
          success: function (data) {
            // Show success message
            showCustomAlert('Saved successfully!', 'success');
            // Clear the form (optional)
            form[0].reset();
            $('.select2').val('').trigger('change');
            // Reload DataTable
            $('#TopicTbl').DataTable().ajax.reload();
          },
          error: function (xhr) {
            showCustomAlert('Save failed. Try again.', 'error');
          }
        });
        window.location.reload();
      }
    });
    // edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const remark = $(this).data('remark');
      const name = $(this).data('name');
      const active = $(this).data('active');

      // Fill form fields
      $('input[name="topic_id"]').val(id);
      $('input[name="topic_name"]').val(name);
      $('input[name="remarks"]').val(remark);

      // For select2 fields, use .val().trigger('change')

      $('select[name="active"]').val(active).trigger('change');
    });


    // delete function


    $(document).on('click', '.delete-btn', function () {
      let deleteId = null;
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('topicdelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert(response.message, 'success');
            $('#TopicTbl').DataTable().ajax.reload();

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