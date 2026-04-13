@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Generate Bonus</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">

    <div class="card-header bg-primary text-white fw-semibold"></div>
    <div class="card-body card-block">
      <div class="row mb-4 mt-2">
        {{-- Group Selection --}}
        <div class="col-md-3">
          <label>Group:</label>
          <select id="group-select" class="form-control select2">
            <option value="">-- please select --</option>
            @if($groupname == "16")
              <option value="Marketing">Marketing</option>
            @else
              <option value="HO/CO">HO/CO</option>
              <option value="Factory">Factory</option>
              <option value="Marketing">Marketing</option>
            @endif
          </select>
        </div>

        {{-- Zone Dropdown --}}
        <div class="col-md-3" id="zone-field" style="display: none;">
          <label>Zone:</label>
          <select name="zone" id="zone" class="form-control select2">{!! $zone !!}</select>
        </div>

        {{-- Start Date --}}
        <div class="col-md-3">
          <label>Start Date:</label>
          <div class="input-group form_date" data-date="" data-link-format="yyyy-mm-dd">
            <input class="form-control start_date" id="start_date" name="start_date" type="text"
              style="border-radius: 5px;" autocomplete="off">
          </div>
        </div>

        {{-- End Date --}}
        <div class="col-md-3">
          <label>End Date:</label>
          <div class="input-group form_date" data-date="" data-link-format="yyyy-mm-dd">
            <input class="form-control end_date" id="end_date" name="end_date" type="text" style="border-radius: 5px;"
              autocomplete="off">
          </div>
        </div>

        {{-- Generate Button --}}
        <div class="col-md-12 text-center mt-4">
          <button type="button" class="btn btn-primary generated"><i class="bi bi-search"></i> Generate</button>
        </div>
      </div>
    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">

              <th>Employee Number</th>
              <th>Employee Name</th>
              <th>Department</th>
              <th>Status</th>
              <th>Zone</th>
              <th>Date of joining</th>
              <th>Last Salary Revision</th>
              <th>Effective Date</th>
              <th>Bonus 1</th>
              <th>Bonus 2</th>
              <th>Bonus 3</th>
              <th>Bonus 4</th>
              <th>From Bonus 1</th>
              <th>To Bonus 1</th>
              <th>Payable 1</th>
              <th>From Bonus 2</th>
              <th>To Bonus 2</th>
              <th>Payable 2</th>
              <th>From Bonus 3</th>
              <th>To Bonus 3</th>
              <th>Payable 3</th>
              <th>From Bonus 4</th>
              <th>To Bonus 4</th>
              <th>Payable 4</th>
              <th>From Last Period</th>
              <th>To Last Period</th>
              <th>Last Period Payable</th>
              <th>Arrear Bonus</th>
              <th>Total Bonus Payable</th>
            </tr>
            <tr class="table-danger">

              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;"></span>
              </th>
            </tr>
          </thead>
          <tbody>
            <!-- Your dynamic row data goes here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
          url: "{{ url('employeebonusgrid1') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.account_code_line = $('#account_line_id').val();
          }
        },
        columns: [

          { data: 'employee_number', name: 'employee_number' },
          { data: 'employee_name', name: 'employee_name' },
          { data: 'department', name: 'department' },
          { data: 'status', name: 'status' },
          { data: 'zone', name: 'zone' },
          { data: 'doj', name: 'doj' },
          { data: 'last_revision_date', name: 'last_revision_date' },
          { data: 'effective_date', name: 'effective_date' },
          { data: 'bonus1', name: 'bonus1' },
          { data: 'bonus2', name: 'bonus2' },
          { data: 'bonus3', name: 'bonus3' },
          { data: 'bonus4', name: 'bonus4' },
          { data: 'from1', name: 'from1' },
          { data: 'to1', name: 'to1' },
          { data: 'payable1', name: 'payable1' },
          { data: 'from2', name: 'from2' },
          { data: 'to2', name: 'to2' },
          { data: 'payable2', name: 'payable2' },
          { data: 'from3', name: 'from3' },
          { data: 'to3', name: 'to3' },
          { data: 'payable3', name: 'payable3' },
          { data: 'from4', name: 'from4' },
          { data: 'to4', name: 'to4' },
          { data: 'payable4', name: 'payable4' },
          { data: 'last_period', name: 'last_period' },
          { data: 'end_date', name: 'end_date' },
          { data: 'final_payable', name: 'final_payable' },
          { data: 'arrear_bonus', name: 'arrear_bonus' },
          { data: 'total_bonus', name: 'total_bonus' }

        ]

      });

      // Trigger search
      $('.report_search').on('click', function () {
        $('#ReportTbl').DataTable().ajax.reload();
      });


      // Column search
      $('#ReportTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });



    $(document).ready(function () {
      $('#group-select').change(function () {
        var selectedGroup = $(this).val();
        if (selectedGroup === 'Marketing') {
          $('#zone-field').show();
        } else {
          $('#zone-field').hide();
        }
      });
    });



    // Generate click function start
    $(document).on('click', '.generated', function () {
      var group = $('#group-select').select2('val');
      var zone = $('#zone').select2('val');
      var start_date = $('#start_date').val();
      var end_date = $('#end_date').val();

      if (group && start_date && end_date) {
        var url = "{{ URL::to('generatebonus') }}";

        $.ajax({
          url: url,
          type: 'GET',
          data: {
            group: group,
            zone: zone || '',
            start_date: start_date,
            end_date: end_date
          },
          success: function (response) {
            if (response.success) {
              showCustomAlert('Bonus generated successfully.', 'success');

              // Reload the jqGrid after successful generation
              $("#bonusgrid").trigger("reloadGrid");
            } else {
              showCustomAlert('Failed to generate bonus. Please try again.', 'error');
            }

            // Reload the page after 1 second
            setTimeout(function () {
              location.reload();
            }, 1000);
          },
          error: function () {
            showCustomAlert('An error occurred while generating the bonus.', 'error');
          }
        });
      } else {
        showCustomAlert('Please Select Group', 'info');
      }
    });


  </script>

@endpush