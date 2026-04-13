@extends('layouts.header')
@section('content')
<h3 class="text-danger">WebOps Track - Dashboard</h3>
@include('layouts.breadcrumb')


<div class="section dashboard">
  <div class="row g-3">
    <?php error_reporting(0); ?>

    @php
      $cards = [
        ['title' => 'Total Tasks | Overall', 'count' => $totalTask_count[0]->count, 'icon' => 'bi-list-columns-reverse'],
        ['title' => 'Total Tasks | Initiated', 'count' => $actTask_count[0]->count, 'icon' => 'bi-list-task'],
        ['title' => 'Total Tasks | Allocated', 'count' => $inactTask_count[0]->count, 'icon' => 'bi-list-stars'],
        ['title' => 'Total Tasks | Pending', 'count' => $maleTask_count[0]->count, 'icon' => 'bi-list-ol'],
        ['title' => 'Total Tasks | Completed', 'count' => $femaleTask_count[0]->count, 'icon' => 'bi-check2-square'],
        ['title' => 'Total Tasks | Closed', 'count' => $officeTask_count[0]->count, 'icon' => 'bi-list-check'],
        ['title' => 'Total Tasks | Rejected', 'count' => $factoryTask_count[0]->count, 'icon' => 'bi-x-square'],
        ['title' => 'High Priority | Initiated', 'count' => $marketingTask_count[0]->count, 'icon' => 'bi-alarm'],
      ];
    @endphp

    @foreach ($cards as $card)
    <div class="col-xxl-3 col-md-6">
      <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body d-flex align-items-center">
          <div class="me-3">
            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
              <i class="bi {{ $card['icon'] }}"></i>
            </div>
          </div>
          <div>
            <h6 class="card-title mb-1">{{ $card['title'] }}</h6>
            <h5 class="mb-0 fw-bold">{{ $card['count'] }}</h5>
            <small class="text-muted">From <span class="text-success fw-bold">{{ $totalTask_count[0]->count }}</span> Tasks</small>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  <div class="row mt-4">
    <div class="col-lg-12">
      <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
          <div class="table-responsive">
            <table id="Table1" class="table table-bordered table-striped w-100">
              <thead class="table-light">
                <tr class="table-warning">
                  <th>Ticket Number</th>
                  <th>Category</th>
                  <th>Sub-Category</th>
                  <th>Priority</th>
                  <th>Task</th>
                  <th>Description</th>
                  <th>Assigned Date</th>
                  <th>Expected Date</th>
                  <th>Department</th>
                  <th>Team Type</th>
                  <th>Assigned Type</th>
                  <th>Assigned To</th>
                  <th>Dept. Lead</th>
                  <th>Created By</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($task_detail as $value)
                  @php
                    $priorityBadge = match($value->priority) {
                      'High' => 'bg-success',
                      'Medium' => 'bg-warning text-dark',
                      'Low' => 'bg-danger',
                      default => 'bg-secondary',
                    };

                    $statusClasses = [
                      'INITIATED' => 'bg-primary text-white',
                      'ALLOCATED' => 'bg-info text-dark',
                      'Pending' => 'bg-warning text-dark',
                      'Completed' => 'bg-success',
                      'REJECTED' => 'bg-danger',
                      'CLOSED' => 'bg-success',
                    ];
                    $statusBadge = $statusClasses[$value->status] ?? 'bg-secondary';
                  @endphp
                  <tr>
                    <td>{{ $value->ticket_number }}</td>
                    <td>{{ $value->category_name }}</td>
                    <td>{{ $value->subcategory_name }}</td>
                    <td><span class="badge {{ $priorityBadge }}">{{ $value->priority }}</span></td>
                    <td>{{ $value->task }}</td>
                    <td>{{ $value->description }}</td>
                    <td>{{ $value->start_date }}</td>
                    <td>{{ $value->end_date }}</td>
                    <td>{{ $value->sub_department_name }}</td>
                    <td>{{ $value->team_type }}</td>
                    <td>{{ $value->assigned_type }}</td>
                    <td>{{ $value->assigned_to }}</td>
                    <td>{{ $value->dept_lead }}</td>
                    <td>{{ $value->created_by }}</td>
                    <td><span class="badge rounded-pill {{ $statusBadge }}">{{ $value->status }}</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div> <!-- table-responsive -->
        </div> <!-- card-body -->
      </div> <!-- card -->
    </div>
  </div>
</div>


@endsection
@push('scripts')
	
<script>
	
                          $(document).ready(function() {
                            $('#Table1').DataTable({
                            });
                        });

</script>


@endpush