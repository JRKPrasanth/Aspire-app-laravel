@extends('layouts.header')
@section('content')
<h2 class="text-danger">Relieve Details</h2>
@include('layouts.breadcrumb')


<form>
    {{ csrf_field() }}

    <div class="card shadow-lg border-0 rounded-4">
        <!-- Card Header -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Relieve Details</h5>
            <a href="{{ url('separation') }}"><button type="button" class="btn btn-sm btn-danger closeurl">
                <i class="bi bi-x-lg"></i>
				</button></a>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="p-4 rounded-3 border bg-light" id="section-to-print">

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <td><strong>Employee Number:</strong> {{$list->employee_number}}</td>
                                        <td><strong>First Name:</strong> {{$list->first_name}}</td>
                                        <td><strong>Last Name:</strong> {{$list->last_name}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date Of Joining:</strong> {{$list->date_of_joining}}</td>
                                        <td><strong>Date Of Leaving:</strong> {{$list->date_of_leaving}}</td>
                                        <td><strong>Notice Period:</strong> {{$list->lookup_code}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</form>

   
<script type="text/javascript">
    /** close buuton url  **/
    $(document).on('click','.closeurl',function()
    {
     var url = "{{URL::to('separation')}}";
      window.location.href=url;
    });
</script>

@endsection
