@extends('layouts.header')
@section('content')
<h3 class="text-danger">Employee Details </h3>
@include('layouts.breadcrumb')



<div class="card shadow-lg rounded-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
		<h5 class="mb-0"></h5>
      <a href="{{ url('viewprofile') }}" class="btn btn-sm text-white bg-danger">
        <i class="bi bi-x-lg"></i> Close
      </a>
    </div>
    <div class="card-body bg-white">
        <input type="hidden" value="1" id="employee_id">

        {{-- Official Details --}}
        <div class="card mb-4 border-info">
            <div class="card-header bg-info bg-gradient text-white fw-bold">Official Details</div>
            <div class="card-body">
                <div class="row g-4">
                    {{-- Profile Photo & Status --}}
                    <div class="col-lg-3 text-center">
                        @php
                            $prefix = $user_details[0]->prefix == 1 ? 'Mr.' : ($user_details[0]->prefix == 2 ? 'Ms.' : 'Mrs.');
                            $image = $user_details[0]->photo == "" ? "profile_none.jpg" : $user_details[0]->photo;
                            $color = $user_details[0]->active == 'Yes' ? 'text-success' : 'text-danger';
                        @endphp
                        <h5 class="fw-bold text-primary">{{$prefix . $user_details[0]->first_name}}</h5>
                        <img src="{{asset('images/profile_images/'.$image)}}" class="img-thumbnail rounded-circle" width="150" height="150" alt="User Image">
                        <h6 class="mt-2 fw-bold {{ $color }} ">
                            Status: {{ $user_details[0]->active == 'Yes' ? 'Active' : 'Resigned' }}
                        </h6>
                    </div>

                    {{-- Details --}}
                    <div class="col-lg-9">
                        @php
                            $personal_details[0]->gender = $personal_details[0]->gender == 1 ? "Male" : "Female";
                            $personal_details[0]->marital_status = $personal_details[0]->marital_status == 1 ? "Single" : "Married";
                            $personal_details[0]->zone_id = $personal_details[0]->zone_id ?: "HO/CO";
                            $user_details[0]->date_of_leaving = $user_details[0]->date_of_leaving ?: "NA";

                            $first_array = [
                                'Employee Number' => $user_details[0]->employee_number,
                                'Email' => $user_details[0]->email,
                                'Contact Number' => $user_details[0]->work_telephone_number,
                                'Department' => $user_details[0]->department_name,
                                'Company' => $user_details[0]->company_name,
                                'Biometric Number' => $user_details[0]->biometric_empno,
                                'Date Of Leaving' => $user_details[0]->date_of_leaving
                            ];

                            $second_array = [
                                'Date of Birth' => $user_details[0]->date_of_birth,
                                'Job Title' => $user_details[0]->job_title_name,
                                'Reporting Manager' => $user_details[0]->reporting_manager_name,
                                'Employment Type' => $user_details[0]->employeement_status_name,
                                'Date of Joining' => $user_details[0]->date_of_joining,
                                'Area' => $user_details[0]->area_name,
                                'Zone' => $personal_details[0]->zone_id
                            ];
						                                    $third_array = array('Gender'=>$personal_details[0]->gender,'Marital Status'=>$personal_details[0]->marital_status,'Nationality'=>$personal_details[0]->nation,'Age'=>$personal_details[0]->age,'Mother Tongue'=>$personal_details[0]->mother,'Religion'=>$personal_details[0]->religion_name,'Blood Group'=>$personal_details[0]->blood,    'Aadhar Num'=>$personal_details[0]->aadhar_number,'Pan Num'=>$personal_details[0]->pan_number,'UAN Num'=>$personal_details[0]->uan_no,'ESI Num'=>$personal_details[0]->esi_no,'Father Name'=>$personal_details[0]->father_name);
                                
                                    $fouth_array = array('Permanent Address'=>$contact_details[0]->permanent_street_address,'Current Address'=>$contact_details[0]->current_street_address);
                        @endphp

                        <div class="row">
                            <div class="col-md-6">
                                @foreach($first_array as $key => $value)
                                    <p><strong>{{ strtoupper($key) }}:</strong> {{ $value }}</p>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                @foreach($second_array as $key => $value)
                                    <p><strong>{{ strtoupper($key) }}:</strong> {{ $value }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


		<div class="card shadow mb-4 border-0">
    <div class="card-header bg-success bg-gradient text-white border-bottom">
        <h5 class="mb-0">Personal Details</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-borderless align-middle mb-0">
                    <tbody>
                        @foreach($third_array as $key => $value)
                        <tr>
                            <td class="fw-bold text-uppercase" style="width: 25%;">{{ $key }}</td>
                            <td style="width: 5%;">:</td>
                            <td style="width: 70%;">{{ $value }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

		
		<div class="card shadow mb-4 border-0">
    <div class="card-header bg-secondary bg-gradient text-white">
        <h5 class="mb-0">Contact Details</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-borderless align-middle">
                    <tbody>
                        @foreach($fouth_array as $key => $value)
                        <tr>
                            <td class="fw-bold text-uppercase" style="width: 25%;">{{ $key }}</td>
                            <td style="width: 5%;">:</td>
                            <td style="width: 70%;">{{ $value }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

		
		@if(count($experience_details) > 0)
<div class="card shadow mb-4 border-0">
    <div class="card-header bg-dark bg-gradient text-white">
        <h5 class="mb-0">Experience Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Organization Name</th>
                        <th scope="col">Organization Website</th>
                        <th scope="col">Designation</th>
                        <th scope="col">From</th>
                        <th scope="col">To</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($experience_details as $key => $value)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->organization_name }}</td>
                        <td>{{ $value->organization_website }}</td>
                        <td>{{ $value->designation }}</td>
                        <td>{{ $value->from_date }}</td>
                        <td>{{ $value->to_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

		
		@if(count($skill_details) > 0)
<div class="card shadow mb-4 border-0">
    <div class="card-header bg-primary bg-gradient text-white">
        <h5 class="mb-0">Skills Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Skill</th>
                        <th scope="col">Version</th>
                        <th scope="col">Competency Level</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($skill_details as $key => $value)
                    @php
                        $competency_map = [
                            1 => 'Fundamental Awareness',
                            2 => 'Novice',
                            3 => 'Intermediate',
                            4 => 'Advanced',
                            5 => 'Expert'
                        ];
                        $competency = $competency_map[$value->competency_level] ?? 'N/A';
                    @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->skill }}</td>
                        <td>{{ $value->version }}</td>
                        <td>{{ $competency }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

		
		
	@if(count($education_details) > 0)
<div class="card shadow mb-4 border-0">
    <div class="card-header bg-info bg-gradient text-white">
        <h5 class="mb-0">Education Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Education Level</th>
                        <th scope="col">Institution Name</th>
                        <th scope="col">Course</th>
                        <th scope="col">From</th>
                        <th scope="col">To</th>
                        <th scope="col">Percentage / Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($education_details as $key => $value)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->lookup_code }}</td>
                        <td>{{ $value->institution_name }}</td>
                        <td>{{ $value->course }}</td>
                        <td>{{ $value->from_date }}</td>
                        <td>{{ $value->to_date }}</td>
                        <td>{{ $value->percentage }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

		
	
		@if(count($training_details) > 0)
<div class="card shadow mb-4 border-0">
    <div class="card-header bg-warning bg-gradient text-dark">
        <h5 class="mb-0">Training and Certification Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Course Name</th>
                        <th scope="col">Certificate Name</th>
                        <th scope="col">Certificate Level</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Issued Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($training_details as $key => $value)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->course_name }}</td>
                        <td>{{ $value->certificate_name }}</td>
                        <td>{{ $value->lookup_code }}</td>
                        <td>{{ $value->course_duration }}</td>
                        <td>{{ $value->issue_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

		
		
		
	@if(count($visa_details) > 0)
<div class="card shadow mb-4 border-0">
    <div class="card-header bg-success bg-gradient text-white">
        <h5 class="mb-0">Visa and Immigration Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Passport Number</th>
                        <th scope="col">Passport Issue Date</th>
                        <th scope="col">Passport Expiry Date</th>
                        <th scope="col">Visa Number</th>
                        <th scope="col">Visa Country</th>
                        <th scope="col">Visa Issue Date</th>
                        <th scope="col">Visa Expiry Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visa_details as $key => $value)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->passport_number }}</td>
                        <td>{{ $value->passport_issued_date }}</td>
                        <td>{{ $value->passport_expiry_date }}</td>
                        <td>{{ $value->visa_number }}</td>
                        <td>{{ $value->country_name }}</td>
                        <td>{{ $value->visa_issued_date }}</td>
                        <td>{{ $value->visa_expiry_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

		
		
		
@if(count($bank_details) > 0)
<div class="card shadow mb-4 border-0">
    <div class="card-header bg-primary bg-gradient  text-white">
        <h5 class="mb-0">Bank Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Bank Name</th>
                        <th scope="col">Branch</th>
                        <th scope="col">Account Holder Name</th>
                        <th scope="col">Account Number</th>
                        <th scope="col">IFSC No</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bank_details as $key => $value)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->bank_name }}</td>
                        <td>{{ $value->branch_name }}</td>
                        <td>{{ $value->account_holder_name }}</td>
                        <td>{{ $value->account_number }}</td>
                        <td>{{ $value->ifsc_code }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

		
		
    </div>
</div>


@endsection
