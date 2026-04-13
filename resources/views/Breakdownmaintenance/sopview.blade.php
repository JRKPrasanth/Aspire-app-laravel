@extends('layouts.header')
@section('content')

<div class="card shadow-lg rounded-4 border-0">

    <div class="card-header d-flex justify-content-between align-items-center text-danger">
      <h5 class="mb-0">SOP Details</h5>
      <a href="{{ URL::to($pageMethod) }}" class="btn btn-sm btn-danger">CLOSE</a>
    </div>
	
	
	
  <div class="card-body">
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <table class="table table-borderless mb-0">
            <tbody>
              <tr>
                <td>
                  <p><strong>Ticket Number:</strong> {{ $ticket_number }}</p>
                  <p><strong>Department:</strong> {{ $department_name }}</p>
                  <p><strong>Machine:</strong> {{ $machine_name }}</p>
                  <p><strong>Breakdown Type:</strong> {{ $breakdown_name }}</p>
                </td>

                <td>
                  <p><strong>Issue Date:</strong> {{ $issue_date }}</p>
                  <p><strong>Causes of Breakdown:</strong> {{ $causes }}</p>
                  <p><strong>Breakdown Severity:</strong> {{ $severity_name }}</p>
                  <p><strong>Preventive Action:</strong> {{ $preventive_action }}</p>
                </td>

                <td>
                  <p><strong>Maintenance Type:</strong> {{ $maintenance_type }}</p>
                  <p><strong>Is Breakdown?</strong> {{ $is_breakdown }}</p>
                  <p><strong>Repair Start Date:</strong> {{ $start_date }}</p>
                  <p><strong>Repair End Date:</strong> {{ $end_date }}</p>
                </td>

                <td>
                  <p><strong>Allocate Engineer:</strong> {{ $e_name }}</p>
                  <p><strong>Allocate Technician:</strong> {{ $t_name }}</p>
                  <p><strong>Error Code:</strong> {{ $error_code }}</p>
                  <p><strong>Shift:</strong> {{ $shift }}</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection


