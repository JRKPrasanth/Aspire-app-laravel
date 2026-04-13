@extends('layouts.header')
@section('content')
<h3 class="text-danger">Monthly Shift Details</h3>
@include('layouts.breadcrumb')


<form>
    <div class="container-fluid">
        <div class="card">

            <!-- Card Header -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Monthly Shift Details</h5>
                <a href="../shiftupload" class="btn btn-sm btn-danger">
                    <i class="fa fa-times"></i> Close
                </a>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <div id="section-to-print">
                    <div class="mb-3">
                        <p><strong>Employee Name:</strong> {{ $name }}</p>
                    </div>

                    <!-- Shift Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead class="table-primary">
                                <tr>
                                    @for ($i = 1; $i <= 31; $i++)
                                        <th>{{ $i }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @for ($i = 1; $i <= 31; $i++)
                                        <td>{{ $shift_details[$i-1][0] }}</td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end card-body -->

        </div> <!-- end card -->
    </div> <!-- end container-fluid -->
</form>



@endsection