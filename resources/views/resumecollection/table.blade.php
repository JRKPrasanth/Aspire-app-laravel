@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Resume Collection</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <form action="" id="searchresume">
                <div class="row g-3">

                    <input type="hidden" value="{{ $job_desc }}" id="job_desc" />

                    @if($pageMethod == "resumecollection")
                        <div class="col-md-3">
                            <input type="text" name="first_keyword" id="first_keyword" class="form-control first_keyword"
                                placeholder="Search Key Skill or Designation">
                        </div>

                        <div class="col-md-3">
                            <input type="text" name="second_keyword" id="second_keyword" class="form-control second_keyword"
                                placeholder="Location">
                        </div>

                        <div class="col-md-3 me-4">
                            <select name="third_third" id="third_third" class="form-select select2 third_third">
                                <option value="">-- Experience --</option>
                                <option value="Fresher">Fresher</option>
                                <option value="1">1 Year</option>
                                <option value="2">2 Years</option>
                                <option value="3">3 Years</option>
                                <option value="4">4 Years</option>
                                <option value="5">5 Years</option>
                                <option value=">1">> 1 Year</option>
                                <option value=">2">> 2 Years</option>
                                <option value=">3">> 3 Years</option>
                                <option value=">4">> 4 Years</option>
                                <option value=">5">> 5 Years</option>
                            </select>
                        </div>

                        <div class="col-md-2 d-grid">
                            <button type="button" id="search" class="btn btn-primary rsearch">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    @endif
                </div>
            </form>

            @if($pageMethod == "resumecollection")
                <div class="mt-3">
                    <a href="{{ URL::to('addresume') }}" class="btn btn-success">
                        + Create Resume
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if(count($resume_list) > 0)
        @foreach($resume_list as $key => $value)

            <div class="card shadow-lg rounded-4 border-0">
                <div class="card-body">

                    <h4 class="fw-bold text-primary mb-3">{{ $value->name_of_the_candidate }}</h4>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p><strong>Email:</strong> {{ $value->email }}</p>
                            <p><strong>Mobile:</strong> {{ $value->mobile_no }}</p>
                            <p><strong>Skill:</strong> {{ $value->skills }}</p>
                            <p><strong>Qualification:</strong> {{ $value->qualificaition }}</p>
                            <p><strong>Gender:</strong> {{ $value->gender }}</p>
                        </div>

                        <div class="col-md-4">
                            <p><strong>Marital Status:</strong> {{ $value->marital_status }}</p>
                            <p><strong>Experience:</strong>
                                {{ $value->exp_level == 1 ? 'Fresher' : 'Experienced' }}
                            </p>
                            <p><strong>Years:</strong> {{ $value->years_of_experience }}</p>
                            <p><strong>Designation:</strong> {{ $value->current_position }}</p>
                            <p><strong>Previous Company:</strong> {{ $value->current_company }}</p>
                        </div>

                        <div class="col-md-4">
                            <p><strong>Current Salary:</strong> {{ $value->current_salary }}</p>
                            <p><strong>Location:</strong> {{ $value->location }}</p>
                            <p><strong>State:</strong> {{ $value->city }}</p>
                            <p><strong>Address:</strong> {{ $value->address }}</p>
                            <p><strong>Expected Salary:</strong> {{ $value->expected_salary }}</p>
                            <p><strong>Interview Date:</strong> {{ $value->interview_date }}</p>
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">

                        <button type="button" class="btn btn-primary action" data-action="editresume" id="{{ $value->resume_id }}">
                            Edit
                        </button>

                        @php
                            $disabled = $value->resume_upload != "" ? "" : "disabled";
                        @endphp

                        <a download href="{{ 'resumeupload/' . $value->resume_upload }}">
                            <button type="button" class="btn btn-warning" {{$disabled}}>
                                Download Resume
                            </button>
                        </a>

                        @if($value->schedule_status == 0 && $value->select_status == 0)
                            <!-- Scheduled -->
                            <button type="button" data-action="createinterview"
                                class="btn btn-sm btn-success rounded-pill px-3 shadow-sm action createinterview" id="{{$value->resume_id}}">
                                <i class="bi bi-calendar-plus"></i> Scheduled
                            </button>

                        @elseif($value->schedule_status == 1 && $value->select_status == 1)
                            <!-- Employee -->
                            <button type="button" data-action="employee" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm"
                                id="{{$value->resume_id}}">
                                <i class="bi bi-person-check-fill"></i> Employee
                            </button>

                        @elseif($value->schedule_status == 3 && $value->select_status == '')
                            <!-- Rejected -->
                            <button type="button" data-action="rejected_scheduled"
                                class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" id="{{$value->resume_id}}">
                                <i class="bi bi-x-circle"></i> Rejected
                            </button>

                        @elseif($value->schedule_status == 2 && $value->select_status == 1)
                            <!-- Employee -->
                            <button type="button" data-action="" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm"
                                id="{{$value->resume_id}}">
                                <i class="bi bi-check-circle"></i> Employee
                            </button>

                        @elseif($value->schedule_status == 1 && $value->select_status == 2)
                            <!-- Selected -->
                            <button type="button" data-action="cancel_scheduled"
                                class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm" id="{{$value->resume_id}}">
                                <i class="bi bi-star-fill"></i> Selected
                            </button>

                        @elseif($value->schedule_status == 2 && $value->select_status == 2)
                            <!-- Waiting List -->
                            <button type="button" data-action="cancel_scheduled"
                                class="btn btn-sm btn-info text-white rounded-pill px-3 shadow-sm" id="{{$value->resume_id}}">
                                <i class="bi bi-hourglass-split"></i> Waiting List
                            </button>

                        @else
                            <!-- Disabled Schedule -->
                            <button type="button" disabled class="btn btn-sm btn-secondary rounded-pill px-3 shadow-sm me-1"
                                id="{{$value->resume_id}}">
                                <i class="bi bi-calendar"></i> Scheduled
                            </button>

                            <button type="button" data-action="cancel_scheduled" 
                                class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm action" id="{{$value->resume_id}}">
                                <i class="bi bi-x-octagon"></i> Cancel Schedule
                            </button>
                        @endif



                    </div>

                </div>
            </div>

        @endforeach

    @else
        <div class="alert alert-warning text-center">
            No Record Found
        </div>
    @endif





@endsection
@push('scripts')

    <script>

        $(document).ready(function () {
            /**************** Resume Collection View Start ***********/
            $(document).on('click', '.view', function () {
                var view_id = $(this).attr('id');
                var url = "{{url('profileview')}}/" + view_id;
                window.location.href = url;

            });
            /**************** Resume Collection View End ***********/

            /**************** Resume Collection Search box Start ***********/
            $(document).on('click', '.rsearch', function () {
                var firstkeyword = $('#first_keyword').val();
                var secondkeyword = $('#second_keyword').val();
                var thirdthird = $('#third_third').select2('val');

                if (firstkeyword == '')
                    firstkeyword = 0;
                else
                    firstkeyword = firstkeyword;

                if (secondkeyword == '')
                    secondkeyword = 0;
                else
                    secondkeyword = secondkeyword;

                if (thirdthird == '')
                    thirdthird = 0;
                else
                    thirdthird = thirdthird;

                var url = "{{url('resumesearch')}}/" + firstkeyword + "/" + secondkeyword + "/" + thirdthird;

                window.location.href = url;

            });
            /**************** Resume Collection Search box End ***********/

            /**************** Resume Collection Interview Scheduled Cancelled Start ***********/
            $(document).on('click', '.action', function () {

                var button = $(this).attr('data-action');
                var id = $(this).attr('id');
                var job_desc = $('#job_desc').val();

                if (button != 'cancel_scheduled' && button != 'createinterview') {
                    var $button = button;
                    var base_url = {!! json_encode(url('/')) !!};
                    window.location.href = base_url + "/" + button + "/" + id;
                }
                else if (button == 'cancel_scheduled') {
                    var url = "{{ URL::to('schedule_cancel') }}/" + id;
                    $.get(url, function (data) {
                        if (data == 1) {
                            showCustomAlert('Interview Scheduled Cancelled Successfully', 'success');
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        }
                    });

                }
                else {
                    var $button = button;
                    var base_url = {!! json_encode(url('/')) !!};
                    window.location.href = base_url + "/" + button + "/" + id + "?job_desc=" + job_desc;
                }
            });


        });

    </script>

@endpush