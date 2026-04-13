@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Initiate PM</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="pmTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th>PM No</th>
              <th>Machine Name</th>
              <th>Department Name</th>
              <th>Frequency Name</th>
              <th>Actual PM Date </th>
              <th>Postpone PM Date</th>

            </tr>
            <tr class="table-info">
              <th></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>


            </tr>
          </thead>

          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="postponeModal" tabindex="-1" aria-labelledby="postponeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 shadow">

        <div class="modal-header  bg-info text-white">
          <h5 class="modal-title" id="postponeModalLabel">PM NOT DONE</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="d-flex justify-content-between mb-3">
            <div><strong>PM Number:</strong> <span class="intpm_no"></span></div>
            <div><strong>Machine Name:</strong> <span class="mach_name"></span></div>
          </div>

          <div class="mb-3">
            <label for="actl_pm_date" class="form-label">Actual PM Date</label>
            <input type="text" class="form-control datepicker actl_pm_date" id="actl_pm_date" autocomplete="off">
            <input type="hidden" class="form-control act_pm_date">
          </div>

          <div class="mb-3">
            <label for="reason" class="form-label">Reason for Postpone</label>
            <textarea class="form-control reason" id="reason" rows="3" required></textarea>
            <input type="hidden" class="form-control ini_pm_id">
          </div>
        </div>

        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-success postpone_save" id="updateClose">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>


@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      var table = $('#pmTbl').DataTable({
        processing: true,
        serverSide: true,
        order: [[4, 'desc']],
        ajax: "{{ route('initiatepmData') }}",
        columns: [
          {
            data: 'initiate_pm_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '200px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'initiate')) {
                buttons += `
              <button  class="btn btn-sm btn-primary me-1 edit-btn" data-id="${data}"
                  data-bs-toggle="tooltip" 
        data-bs-placement="top" 
        title="PM Initiate"><i class="bi bi-clipboard2-plus-fill"></i></button>`;
              }

              buttons += `<button class="btn btn-sm btn-danger checkButton" data-id="${data}"
                data-bs-toggle="tooltip" 
        data-bs-placement="top" 
        title="PM Not Done"><i class="bi-backspace-reverse-fill"></i></button>`;


              return buttons;
            }

          },
          { data: 'pm_no', name: 'pm_no' },
          { data: 'machine_name', name: 'machine_name' },
          { data: 'department_name', name: 'department_name' },
          { data: 'frequency_id', name: 'frequency_id' },
          { data: 'actual_pm_date', name: 'actual_pm_date' },
          { data: 'postponed_date', name: 'postponed_date' },
          //   { data: 'move_stat', name: 'move_stat' },
          //  { data: 'move_reason', name: 'move_reason' },

        ]
      });

      // Individual column search
      $('#pmTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('initiatepmcreate') }}/" + id;
      window.location.href = url;
    });


    //popup function

    $(document).on('click', '.checkButton', function () {
      // Get DataTable row data using the clicked button's closest row
      var table = $('#pmTbl').DataTable();
      var tr = $(this).closest('tr');
      var rowData = table.row(tr).data();

      var initiate_pm_id = rowData.initiate_pm_id;
      var move_status = rowData.move_stat;
      var actual_pm_date = rowData.actual_pm_date;
      var machine_name = rowData.machine_name;
      var pm_no = rowData.pm_no;

      if (move_status !== 'PM NOT DONE') {
        $(".intpm_no").html(pm_no);
        $(".act_pm_date").html(actual_pm_date);
        $(".ini_pm_id").val(initiate_pm_id);
        $(".mach_name").html(machine_name);

        $("#postponeModal").modal('show');

        var url = "{{ URL::to('pmedit') }}?id=" + initiate_pm_id;
        $.get(url, function (data) {
          let formattedDate = data.initiate_date === '0000-00-00'
            ? formatToYMD(new Date())
            : data.initiate_date;

          $('.init_date').val(formattedDate);
          $('.actl_pm_date').val(data.actual_pm_date);
          $('.reason').val(data.move_reason);
          $('.pstpne_to').val(data.actual_pm_date);

          if (data.update !== "create") {
            $('.actl_pm_date').css("pointer-events", "none");
            $('.reason').attr("required", true);
          } else {
            $('.actl_pm_date').css("pointer-events", "auto");
            $('.reason').attr("required", true);
          }
        });
      } else {
        showCustomAlert("Selected PM already Postponed!", "error",);
      }
    });




    $(document).on('click', '.postpone_save', function () {

      var id = $(".ini_pm_id").val();
      var pstpne_to = $(".pstpne_to").val();
      var reason = $(".reason").val();
      if (!reason) {
        showCustomAlert('Reason for PM Postpone is mandatory', 'warning');
        return;
      } else {
        $.get("pmupdate?id=" + id + "&pstpne_to=" + pstpne_to + "&reason=" + reason, function (data) {

          if ($.trim(data) == '1') {
            showCustomAlert('PM Postponed Successfully!', 'success');
            $("#initiatepmgrid")[0].triggerToolbar();
            $('#pmTbl').DataTable().ajax.reload();
          } else {
            showCustomAlert('Please Try Again', 'error');
            $("#initiatepmgrid")[0].triggerToolbar();
          }
        });
        $("#postponeModal").modal('hide');
      }
    });

    function formatToYMD(dateInput) {
      // Create a Date object from the input date string
      const date = new Date(dateInput);

      // Extract the year, month, and day
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
      const day = String(date.getDate()).padStart(2, '0');

      // Format the date as yyyy-mm-dd
      return `${year}-${month}-${day}`;
    }


  </script>

@endpush