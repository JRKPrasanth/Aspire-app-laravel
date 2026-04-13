@extends('layouts.header')
@section('content')


  <style>
    .select2-container--open {
      z-index: 200000 !important;
    }
  </style>

  <h3 class="text-danger">Schedule Training</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3"></div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="TopicTbl" class="table table-striped table-bordered w-100">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th>Topic</th>
              <th>Meeting Start Time</th>
              <th>Meeting End DateTime</th>
              <th>Trainer Name</th>
              <th>Schedule Type</th>
            </tr>

            <tr class="table-danger">
              <th></th>
              <th><input type="text" placeholder="Search" /></th>
              <th><input type="text" placeholder="Search" /></th>
              <th><input type="text" placeholder="Search" /></th>
              <th><input type="text" placeholder="Search" /></th>
              <th><input type="text" placeholder="Search" /></th>
            </tr>

          </thead>
        </table>
      </div>
    </div>
  </div>


  <div class="modal fade" id="trainingModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content rounded-4 shadow-lg">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">
            <i class="bi bi-people-fill me-2"></i> Training Attendees Status
          </h5>
          <button type="button" id="closeButton" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">

          <form method="post" id="scheduletrainingstatus" class="scheduletrainingstatus" enctype="multipart/form-data">

            <div class="row g-4">

              <!-- Topic -->
              <div class="col-md-4">
                <label class="form-label fw-semibold">Topic</label>
                <input type="text" name="topic" class="form-control topic" readonly>
                <input type="hidden" name="hdrid" class="hdrid">
              </div>

              <!-- Schedule Type -->
              <div class="col-md-4">
                <label class="form-label fw-semibold">Schedule Type</label>
                <input type="text" name="scheduleType" class="form-control scheduleType" readonly>
              </div>

              <!-- Schedule Status -->
              <div class="col-md-4">
                <label class="form-label fw-semibold">Schedule Status</label>
                <select name="schedule_status" id="schedule_status" class="form-select select2 schedule_status" required>
                  <option value="OPEN">OPEN</option>
                  <option value="COMPLETED">COMPLETED</option>
                  <option value="CANCELED">CANCELED</option>
                </select>
              </div>

            </div>

            <!-- Dynamic Status Content -->
            <div class="row mt-4">
              <div class="col-12 statuspopup" style="max-height: 400px;overflow-y: auto;"></div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
              <button type="button" class="btn btn-success px-4 py-2 statusdata">
                <i class="bi bi-check-circle me-1"></i> Update Attendees Status
              </button>
            </div>

          </form>

        </div>

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
        serverSide: false,
        orderCellsTop: true,
        ajax: "{{ route('scheduletraininggriddata') }}",
        columns: [
          {
            data: 'schedule_training_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              return `
                    <button type="button" class="btn btn-sm btn-warning view-btn" data-id="${data}">
                      <i class="bi bi-eye"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-success approve-btn" data-id="${data}"
                            data-type="${row.schedule_type}" 
                            data-name="${row.topic_name}" 
                            data-status="${row.schedule_status}" 
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Update Attendties Status">
                      <i class="bi bi-check-circle"></i>
                    </button>
                  `;
            }
          },

          { data: 'topic_name', name: 'topic_name' },
          { data: 'start_time', name: 'start_time' },
          { data: 'end_time', name: 'end_time' },
          { data: 'trainer_name', name: 'trainer_name' },
          { data: 'schedule_type', name: 'schedule_type' },

        ],

        initComplete: function () {
          var api = this.api();

          // get the real visible header inside the scroll container
          var $scrollHead = $(api.table().container())
            .find('.dataTables_scrollHead thead');

          // second header row (index 1) has the inputs
          $scrollHead.find('tr:eq(1) th').each(function (colIndex) {
            var th = this;
            $('input.column-search', th).on('keyup change', function () {
              if (api.column(colIndex).search() !== this.value) {
                api.column(colIndex).search(this.value).draw();
              }
            });
          });
        }
      });

      // Optional: Attach handlers for the buttons
      $(document).on('click', '.view-btn', function () {
        const id = $(this).data('id');
        const url = "{{ url('scheduletrainingview') }}/" + id;
        window.location.href = url;
      });

      $(document).on('click', '.approve-btn', function () {

        var hdrid = $(this).data('id');


        $.get("{{URL::to('scheduledetails')}}/" + hdrid, function (data) {
          $('.statuspopup').html(data);
        });

        var cellValue = $(this).data('status');
        var topic_name = $(this).data('name');
        var schedule_type = $(this).data('type');

        if (cellValue === 'OPEN') {
          $('#trainingModal').modal('show');
          $('.hdrid').val(hdrid);
          $('.topic').val(topic_name);
          $('.scheduleType').val(schedule_type);
          $('.schedule_status').val(cellValue);
        } else {
          showCustomAlert("Please Select Schedule Status Open", "info");
        }

      });

    });

    $(".create").click(function () {

      var url = "{{ URL::to('scheduletrainingcreate/0')}}";
      window.location.replace(url);

    });

    // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
                    <button class="btn btn-primary create me-2">Create
                      <i class="bi bi-plus-circle"></i> 
                    </button>
                  `);
      }
    });

    $('.statusdata').click(function () {
      var hdrid = $('.hdrid').val();
      var lineid = $('.schedule_training_line_id').val();

      var url = "{{ URL::to('updatestatus') }}/" + hdrid + "/" + lineid;


      var form = $('#scheduletrainingstatus');
      form.parsley().validate();

      if (form.parsley().isValid()) {

        var formdata = form.serialize();
        formdata += '&schedule_status=' + encodeURIComponent($('#schedule_status').val());
        formdata += '&attend_status=' + encodeURIComponent($('#attend_status').val());
        $.post(url, formdata, function (data) {
          if (data.message === 'Status updated successfully') {
            showCustomAlert('Status Updated Successfully', 'success');
            $('#grid1').trigger("reloadGrid");
            $('#trainingModal').modal('hide');
          } else {
            showCustomAlert('Update Failed', 'error');
          }
        });
      }
    });

  </script>
@endpush