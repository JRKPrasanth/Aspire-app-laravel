@extends('layouts.header')
@section('content')
  <h3 class="text-danger">MIS viewers</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-success bg-gradient text-white fw-semibold"></div>
    <div class="card-body">
      <div class="col-lg-12">
        <form action="{{ url('salesmisviewers') }}" method="get" id="searchForm">
          <div class="row g-3">

            <!-- Employee Name -->
            <div class="col-md-4">
              <label for="emp_id" class="form-label">Employee Name</label>
              <select name="emp_id" id="emp_id" class="form-select select2">
                {!! $employee !!}
              </select>
            </div>

            <!-- Start Date -->
            <div class="col-md-4">
              <label for="start_date1" class="form-label">Start Date</label>
              <div class="input-group">
                <input type="text" class="form-control start_date1" id="start_date" name="start_date">
              </div>
            </div>

            <!-- End Date -->
            <div class="col-md-4">
              <label for="end_date1" class="form-label">End Date</label>
              <div class="input-group">
                <input type="text" class="form-control end_date1" id="end_date" name="end_date">
              </div>
            </div>

            <!-- Search Button -->

            <div class="col-md-12 mt-2" style="text-align:center;">
              <button type="submit" class="btn btn-primary" id="searchButton"><i class="bi bi-search me-1"></i> Search
              </button>
            </div>


          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <div class="table-responsive" style="overflow-x: auto;">
        <table id="Table1" class="table table-bordered table-striped table-hover w-100">
          <thead>
            <tr>
              <th class="text-center bg-danger text-white">Employee Name</th>
              <th class="text-center bg-danger text-white">Menu Name</th>
              <th class="text-center bg-danger text-white">Month</th>
              <th class="text-center bg-danger text-white">Date & Time</th>
            </tr>
          </thead>
          <tbody>

            <tr>
              <?php foreach ($mis_view as $value) { ?>
              <td class="sticky-col">{{ $value->first_name }}</td>
              <td class="sticky-col">{{ $value->menus_name }}</td>
              <td class="sticky-col">{{ $value->month }}</td>
              <td class="text-primary fw-bold">{{ $value->date_time }}</td>


            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>


@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      var Employee = "{{ request('emp_id') }}"
      $('.emp_id').select2();
      $('#emp_id').val(Employee).trigger('change');

      var startDate = "{{ request('start_date') }}";
      var endDate = "{{ request('end_date') }}";

      $('#start_date').val(startDate);
      $('#end_date').val(endDate);

    });


    $(document).ready(function () {
      $('#Table1').DataTable({

      });
    });

  </script>

@endpush