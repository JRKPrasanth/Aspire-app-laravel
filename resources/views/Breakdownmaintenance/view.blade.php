@extends('layouts.header')
@section('content')

<form>
<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center text-danger">
      <h5 class="mb-0">
        @switch($pageMethod)
          @case('createissue') Issue Details @break
          @case('allocateengineer') Allocate Engineer @break
          @case('allocatetechnician') Allocate Technician @break
          @case('requestraise') Raise Request @break
          @default Approve Request
        @endswitch
      </h5>
      <a href="{{ URL::to($pageMethod) }}" class="btn btn-sm btn-danger">CLOSE</a>
    </div>

    <div class="card-body">
      <div class="row g-4">
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-borderless mb-0">
              <tbody>
                <tr>
                  <td>
                    <strong>Department:</strong><br>
                    {{ $department_name }}<br><br>
                    <strong>Machine:</strong><br>
                    {{ $machine_name }}<br><br>
                    <strong>Breakdown Type:</strong><br>
                    {{ $breakdown_name }}
                  </td>
                  <td>
                    <strong>Issue Date:</strong><br>
                    {{ $issue_date }}<br><br>
                    <strong>Causes of Breakdown:</strong><br>
                    {{ $causes }}<br><br>
                    <strong>Breakdown Severity:</strong><br>
                    {{ $severity_name }}
                  </td>
                  <td>
                    <strong>Maintenance Type:</strong><br>
                    {{ $maintenance_type }}<br><br>
                    <strong>Active:</strong><br>
                    {{ $active }}
                  </td>

                  @if(in_array($pageMethod, ['allocateengineer', 'allocatetechnician', 'requestraise', 'approverequest']))
                    <td>
                      <strong>Allocate Engineer:</strong><br>
                      {{ $first_name }}<br><br>
                      <strong>Allocate Technician:</strong><br>
                      {{ $first_name }}
                    </td>
                  @endif

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection
