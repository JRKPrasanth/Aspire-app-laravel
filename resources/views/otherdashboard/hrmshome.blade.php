@extends('layouts.header')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />

<style>
  .fc-daygrid-event {
    display: inline-block !important;
    width: 30px;
    height: 30px;
    border-radius: 29%;
    text-align: center;
    font-size: 14px;
    padding: 0 !important;
    color: #fff !important;
    font-weight: 600;
    line-height: 23px;
  }

  .fc-daygrid-event-harness {
    text-align: center;
  }

  /* Adjust today highlight */
  .fc-day-today {
    background-color: #c6cfd8 !important;
    border: 1px solid #ddd !important;
  }

  .fc-media-screen {
    height: 450px !important;
  }

  .upcoming-holiday {
    background-color: #cfe4c2 !important;
    color: #198754 !important;
    font-weight: 700;
  }
</style>

  @section('content')
  <h3 class="text-danger">Employee Dashboard</h3>
  @include('layouts.breadcrumb')


  <!-- for HR see all employee -->

  <?php if ($group == '2' || $group == '4' || $group == '1') { ?>
  <div class="col-lg-12 mb-3">
    <div class="card shadow-sm rounded-4 border-0 p-4">
      <form action="{{ url('hrmshome') }}" method="get" id="searchForm">

        <div class="row g-3 align-items-end">
          <!-- Employee Search Dropdown -->
          <div class="col-lg-4 col-md-6 me-4">
            <label for="emp_search" class="form-label fw-semibold">Select Employee</label>
            <select name="emp_search" id="emp_search" class="form-select emp_search select2">
              {!! $emp_search !!}
            </select>
          </div>

          <!-- Search Button -->
          <div class="col-lg-2 col-md-4 me-4">
            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-search"></i> Search
            </button>
          </div>

        </div>

      </form>
    </div>
  </div>
  <?php } ?>


  <?php if ($group == '16' || $group == '1') { ?>
  <div class="col-lg-12 mb-3">
    <div class="card shadow-sm rounded-4 border-0 p-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
            <a href="{{ url('companyorganogram') }}"><button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-people"></i> Employee Organogram
            </button></a>
          </div>
        </div>
    </div>
  </div>
  <?php } ?>


  <!-- Leave Details -->
  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow-lg rounded-4 border-0">
        <div id="leaveBal"></div>
        <div class="col-md-12 text-center">
          <a href="leave"><button class="btn btn-primary leave" id="leave">Apply Leave</button></a>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card shadow-lg rounded-4 border-0">
        <div class="card shadow-sm rounded-3 border-0">
          <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">Holidays</h5>
          </div>
          <div class="card-body p-2" style="max-height: 370px; overflow-y: auto;">
            <ul class="list-group list-group-flush">
              @foreach ($holiday_detail as $holiday)
                @php
                  $holidayDate = \Carbon\Carbon::parse($holiday->date);
                  $isUpcoming = $holidayDate->isFuture(); 
                @endphp
                <li
                  class="list-group-item d-flex justify-content-between align-items-center {{ $isUpcoming ? 'upcoming-holiday' : '' }}">
                  {{ $holiday->holiday_name }}
                  <span class="badge bg-primary square-pill">
                    {{ $holidayDate->format('d M') }}
                  </span>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Attendance Details -->
  <div class="row mt-4">
    <div class="col-md-8 card shadow-lg rounded-4 border-0">

      <div class="d-flex flex-wrap gap-2 mt-4">
        <span class="badge" style="background:#198754">P / P(H) / P(OD)</span>
        <span class="badge" style="background:#dc3545">Absent</span>
        <span class="badge" style="background:#0d6efd">Week Off</span>
        <span class="badge" style="background:#6c757d">Holiday</span>
        <span class="badge" style="background:#ffc107">CL / SL / EL</span>
        <span class="badge" style="background:#0dcaf0">C-OFF</span>
      </div>

      <div class="mt-4" id="attendanceCalendar"></div>


    </div>

    <div class="col-md-4">
      <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
          <!-- Profile Image & Name -->
          <div class="text-center mb-3">
            @php $image = $user_details[0]->photo == "" ? "profile_none.jpg" : $user_details[0]->photo; @endphp
            <img src="{{asset('images/profile_images/' . $image)}}" class="rounded-circle mb-2" alt="profile"
              style="width: 160px;height: 160px;">
            <h5 class="fw-bold mb-0">{{ $user_details[0]->first_name }}</h5>
            <small class="text-muted">{{ $user_details[0]->email }}</small>
          </div>

          <!-- Tabs Navigation -->
          <ul class="nav nav-tabs justify-content-center mb-3" id="profileTabs" role="tablist">
            <li class="nav-item">
              <button class="nav-link active" id="official-tab" data-bs-toggle="tab" data-bs-target="#official"
                type="button">Official Info</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal"
                type="button">Personal Info</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                type="button">Contact</button>
            </li>
          </ul>

          <!-- Tabs Content -->
          <div class="tab-content" id="profileTabsContent">
            <!-- Official Info -->
            <div class="tab-pane fade show active" id="official">
              <dl class="row">
                <dt class="col-6">Employee Number:</dt>
                <dd class="col-6">{{ $user_details[0]->employee_number }}</dd>
                <dt class="col-6">Date of Join:</dt>
                <dd class="col-6">{{ $user_details[0]->date_of_joining }}</dd>
                <dt class="col-6">Branch:</dt>
                <dd class="col-6">{{ $user_details[0]->area_name }}</dd>
                <dt class="col-6">Department:</dt>
                <dd class="col-6">{{ $user_details[0]->department_name }}</dd>
                <dt class="col-6">Designation:</dt>
                <dd class="col-6">{{ $user_details[0]->job_title_name }}</dd>
                <dt class="col-6">Employment Type:</dt>
                <dd class="col-6">{{ $user_details[0]->employeement_status_name }}</dd>
                <dt class="col-6">Manager:</dt>
                <dd class="col-6">{{ $user_details[0]->reporting_manager_name }}</dd>
              </dl>
            </div>

            <!-- Personal Info -->
            <div class="tab-pane fade" id="personal">
              <dl class="row">
                <dt class="col-6">DOB:</dt>
                <dd class="col-6">{{ $user_details[0]->date_of_birth }}</dd>
                <dt class="col-6">Gender:</dt>
                <?php if ($user_details[0]->prefix == 1) {
    $user_details[0]->prefix = "Male";
  } else {
    $user_details[0]->prefix = "Female";
  } ?>
                <dd class="col-6">{{ $user_details[0]->prefix }}</dd>
                <dt class="col-6">Marital Status:</dt>
                <?php if ($user_details[0]->marital_status == 1) {
    $user_details[0]->marital_status = "Single";
  } else {
    $user_details[0]->marital_status = "Married";
  } ?>
                <dd class="col-6">{{ $user_details[0]->marital_status }}</dd>

                <dt class="col-6">Age:</dt>
                <dd class="col-6">{{ $user_details[0]->age }}</dd>
                <dt class="col-6">Email:</dt>
                <dd class="col-6">{{ $user_details[0]->email }}</dd>
              </dl>
            </div>

            <!-- Contact Info -->
            <div class="tab-pane fade" id="contact">
              <dl class="row">
                <dt class="col-6">Personal Mail:</dt>
                <dd class="col-6">--</dd>
                <dt class="col-6">Phone:</dt>
                <dd class="col-6">{{ $user_details[0]->work_telephone_number }}</dd>
                <dt class="col-6">Address:</dt>
                <dd class="col-6">{{ $contact_details[0]->permanent_street_address }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

<?php if ($group == '1') { ?>
  <div class="row" style="margin-top: 40px;">
  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <label class="fw-semibold">From</label>
        <input type="text" name="report_from" 
               value="{{ $report_from }}" class="form-control start_date">
    </div>

    <div class="col-md-3">
        <label class="fw-semibold">To</label>
        <input type="text" name="report_to" 
               value="{{ $report_to }}" class="form-control end_date">
    </div>

    <div class="col-md-2 align-self-end">
        <button class="btn btn-primary">Filter</button>
    </div>
  </form>
</div>
<div class="row mb-4">

    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <h6>Total Gross Salary</h6>
            <h4 class="text-primary">
                {{ number_format($kpi->total_gross ?? 0,2) }}
            </h4>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <h6>Total Achieved</h6>
            <h4 class="text-success">
                {{ number_format($kpi->total_achieved ?? 0,2) }}
            </h4>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <h6>Overall Contribution %</h6>
            <h4 class="text-warning">
                {{ $overall_percent }} %
            </h4>
        </div>
    </div>

</div>


<div id="deptPerformanceChart" style="height:400px;"></div>
<div id="salaryAchChart" style="height:400px;"></div>
<div id="deptRankingChart" style="height:400px;"></div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Rank</th>
            <th>Employee</th>
            <th class="text-end">Achieved</th>
        </tr>
    </thead>
    <tbody>
        @foreach($top_performers as $index => $row)
        <tr>
            <td>#{{ $index+1 }}</td>
            <td>{{ $row->employee }}</td>
            <td class="text-end">{{ number_format($row->achieved,2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

  <h4 class="mt-4">Employee Monthly wise Performance</h4>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
              <th>Department</th>
              <th>Employee</th>
              <th>Month</th>
              <th class="text-end">Gross</th>
              <th class="text-end">Net</th>
              <th class="text-end">Achieved</th>
              <th class="text-end">%</th>
            </tr>
        </thead>


        <tbody>
            @php 
            $currentDept = '';
            $currentEmp = '';
            @endphp

            @foreach($gr_ach_report as $row)
              {{-- Department Header --}}
              @if($currentDept != $row->department)
            <tr class="table-secondary">
                <td colspan="7"><strong>{{ $row->department }}</strong></td>
            </tr>
              @php $currentDept = $row->department; @endphp
            @endif

             {{-- Employee Header --}}
             @if($currentEmp != $row->employee_id)
            <tr class="table-light">
                <td></td>
                <td colspan="6"><strong>{{ $row->employee_name }}</strong></td>
            </tr>
            @php $currentEmp = $row->employee_id; @endphp
            @endif

          {{-- Monthly Row --}}
            <tr>
                <td></td>
                <td></td>
                <td>{{ $row->month_year }}</td>
                <td class="text-end">{{ number_format($row->gross_salary,2) }}</td>
                <td class="text-end">{{ number_format($row->net_pay,2) }}</td>
                <td class="text-end">{{ number_format($row->achieved,2) }}</td>
                <td class="text-end">
                    <span class="badge bg-{{ $row->contribution_percent >= 100 ? 'success' : ($row->contribution_percent >= 70 ? 'warning' : 'danger') }}">
                        {{ $row->contribution_percent }} %
                    </span>
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>

<?php } ?>



  <!-- END -->

@endsection
@push('scripts')

<script src="https://code.highcharts.com/highcharts.js"></script>
<script>

    const leaveData = <?php echo $leave_details; ?>;

    Highcharts.chart('leaveBal', {
      chart: {
        type: 'pie',
        custom: {},
        events: {
          render() {
            const chart = this,
              series = chart.series[0];
            let customLabel = chart.options.chart.custom.label;

            if (!customLabel) {
              customLabel = chart.options.chart.custom.label =
                chart.renderer.label(
                  'Total<br/>' +
                  '<strong>' + series.data.reduce((a, b) => a + b.y, 0) + '</strong>'
                )
                  .css({
                    color: '#000',
                    textAnchor: 'middle'
                  })
                  .add();
            }

            const x = series.center[0] + chart.plotLeft,
              y = series.center[1] + chart.plotTop -
                (customLabel.attr('height') / 2);

            customLabel.attr({ x, y });

            customLabel.css({
              fontSize: `${series.center[2] / 12}px`
            });
          }
        }
      },
      title: {
        text: 'Employee Leave Detail'
      },
      tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b>'
      },
      legend: {
        enabled: false
      },
      plotOptions: {
        series: {
          allowPointSelect: true,
          cursor: 'pointer',
          borderRadius: 8,
          dataLabels: [{
            enabled: true,
            format: '{point.name}: {point.y}',
            style: {
              fontSize: '1em'
            }
          }],
          showInLegend: true
        }
      },
      series: [{
        name: 'Leave Balance',
        colorByPoint: true,
        innerSize: '75%',
        data: leaveData
      }]
    });

    // calender
    document.addEventListener('DOMContentLoaded', function () {
      var calendarEl = document.getElementById('attendanceCalendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: @json($events),
        eventDidMount: function (info) {
          const data = info.event.extendedProps;
          const d = info.event.start;
          const tooltipContent = `
          <div>

            <strong>Status:</strong> ${info.event.title || 'N/A'}<br>
            <strong>Leave Type:</strong> ${data.leave_type || 'N/A'}<br>
            <strong>IN:</strong> ${data.in_time || 'N/A'}<br>
            <strong>OUT:</strong> ${data.out_time || 'N/A'}<br>
          </div>
        `;
          new bootstrap.Tooltip(info.el, {
            title: tooltipContent,
            html: true,
            placement: 'top',
            trigger: 'hover'
          });
        },
        eventColor: '#6c757d',
        eventDisplay: 'block'
      });

      calendar.render();
    });

        $(document).ready(function () {

        var employee = "{{ request('emp_search') }}";

        $('.emp_search').select2();
        $('#emp_search').val(employee).trigger('change');


    });


Highcharts.chart('deptPerformanceChart', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Department Performance ({{ $report_from }} to {{ $report_to }})'
    },
    xAxis: {
        categories: @json($chart_months)
    },
    yAxis: {
        title: {
            text: 'Achieved Amount'
        }
    },
    tooltip: {
        shared: true,
        valueDecimals: 2
    },
    plotOptions: {
        line: {
            marker: {
                enabled: true
            }
        }
    },
    series: {!! $chart_series !!}
});


Highcharts.chart('salaryAchChart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Monthly Achieved Revenue'
    },
    xAxis: {
        categories: @json($salaryMonths)
    },
    yAxis: {
        title: {
            text: 'Achieved Amount'
        }
    },
    tooltip: {
        valueDecimals: 2
    },
    series: [{
        name: 'Achieved',
        data: @json($achievedData)
    }]
});


Highcharts.chart('deptRankingChart', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Department Contribution Ranking'
    },
    xAxis: {
        categories: @json($deptCategories)
    },
    yAxis: {
        title: {
            text: 'Achieved Amount'
        }
    },
    tooltip: {
        valueDecimals: 2
    },
    series: [{
        name: 'Achieved',
        data: @json($deptAchieved)
    }]
});

  </script>
@endpush