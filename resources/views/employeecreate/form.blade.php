@extends('layouts.header')
@section('content')
<h3 class="text-danger"><?php if($pageMethod=="createemployee") {?>Employee Create <?php } else {?> Update Profile<?php } ?></h3>
@include('layouts.breadcrumb')


<!------------------------- Tab content start ------------------------------->

<div class="container-fluid mt-3">
    <!-- Horizontal Tabs -->
    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
        <li class="nav-item menu firsttab" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#Section1" type="button" role="tab">Official</button>
        </li>
        <li class="nav-item menu" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Section2" type="button" role="tab">Personal</button>
        </li>
        <li class="nav-item menu" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Section3" type="button" role="tab">Contact</button>
        </li>
        <li class="nav-item menu" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Section4" type="button" role="tab">Bank Details</button>
        </li>
        <li class="nav-item menu" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Section5" type="button" role="tab">Education</button>
        </li>
        <li class="nav-item menu" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Section6" type="button" role="tab">Experience</button>
        </li>
        <li class="nav-item menu" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Section7" type="button" role="tab">Skills</button>
        </li>
        <li class="nav-item menu" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Section8" type="button" role="tab">Training & Certification</button>
        </li>
        <li class="nav-item menu" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Section9" type="button" role="tab">Visa & Immigration</button>
        </li>
    </ul>

	
	
    <!-- Tab Content -->
    <div class="tab-content border rounded-bottom p-4 bg-white" id="profileTabContent">
		
        <div class="tab-pane fade show active" id="Section1" role="tabpanel">
           <form id="official_form" data-parsley-validate>
                <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
                <input type="hidden" name="form_name" id="form_name" value="official_details"/>
            <div class="container">
                <div class="row">

                <div class="col-lg-6 col-md-6">
                    <fieldset>
						<h4 class="mb-3 text-primary">Employee Details</h4>
                        <div class="mb-3 row">
                                <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Employee Code:</label>
                                <div class="col-md-6 ">
                                    <input type="text" class="col-md-10 form-control" name="employee_number" id="employee_number" required value="{{$employee_official[0]->employee_number}}">
                                    <span class="btn btn-danger dup_name1" style="display:none; font-size:10px;margin-left: 21px;" ></span>
                                </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Prefix:</label>
                            <div class="col-md-6 ">
                                <select class="select2 form-control" id="prefix" name="prefix" required>
                                    <option value="">-- Please select --</option>
                                    <option value="1" {{$employee_official[0]->prefix == "1" ? 'selected' : ''}}>Mr</option>
                                    <option value="2" {{$employee_official[0]->prefix == "2" ? 'selected' : ''}}>Ms</option>
                                    <option value="3" {{$employee_official[0]->prefix == "3" ? 'selected' : ''}}>Mrs</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>First Name:</label>
                            <div class="col-md-6 ">
                                <input type="text" class="col-lg-10 col-md-10 form-control" id="first_name" name="first_name" value="{{$employee_official[0]->first_name}}" data-parsley-required="true"   >
                            </div>
                        </div>

                        <div class="mb-3 row">
                              <label class="col-lg-5 col-md-5 col-form-label">Last Name:</label>
                              <div class="col-md-6 ">
                                  <input type="text" class="col-lg-10 col-md-10 form-control" id="last_name" name="last_name" value="{{$employee_official[0]->last_name}}">
                              </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Email:</label>
                            <div class="col-md-6 ">
                                <input type="text" class="col-lg-10 col-md-10 form-control email" id="email" name="email" value="{{$employee_official[0]->email}}" required>
                                <span class="btn btn-danger dup_name" style="display:none; font-size:10px;" style="margin-left: -130px;"></span>
                                <span class="btn btn-danger email_vali" id="" style="display:none;"> Email format is example123@gmail.com</span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Company:</label>
                                <div class="col-md-6 ">
                                    <select class="select2 " name="company_id" id="company_id"  required>
                                        {!!$company!!}
                                    </select>
                               </div>

                        </div>
                        <div class="mb-3 row">
                          <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Location Name:</label>
                           <div class="col-md-6 parsleymulti">
                              <select class="select2 form-control placeholder-multiple " name="location_id[]" id="location_id" multiple="true"  required >
                                  {!!$location!!}
                              </select>
                          </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Department:</label>
                                <div class="col-md-6 parsleymulti">
                                    <select class="select2 form-control" name="department[]" id="department" multiple="" required>
                                         {!!$department!!}
                                    </select>
                                </div>
                        </div>

                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req spanhide">*</span>Reporting Manager:</label>
                        <div class="col-md-6">
                            <select class="select2 form-control" name="reporting_manager" id="reporting_manager" required>
                                 {!!$reporting_manager!!}
                            </select>
                        </div>
                    </div>
                      <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label">Reporting Manager1:</label>
                        <div class="col-md-6">
                            <select class="select2 form-control" name="reporting_manager1" id="reporting_manager1" >
                                 {!!$reporting_manager1!!}
                            </select>
                        </div>
                    </div>
                        
                        @php $image = $employee_official[0]->photo == "" ? "profile.png" : $employee_official[0]->photo; @endphp
                   
                    <div class="mb-3 row" >
                        <label class="col-lg-5 col-md-5 col-form-label">Photo:</label> <div class="col-md-7 text-left " style="cursor: pointer;">
                            <img id="myImg"  src="{{asset('images/profile_images/' . $image)}}" alt="your image" height="20px" width="20px" style="width: 100px;height: 100px; border-radius: 15%;">
                            <input type="file" name="photo" class="file_upload" id="photo"></div>
                        
                            
                        
                        <div class="col-md-offset-5 col-md-6" style="margin-top:2.8%;margin-left:42.666667%;">
                        <label for="file-upload" class="custom-file-upload file_choose ">
                                 <i class="fa fa-cloud-upload "></i>&nbsp;File Upload
                        </label>
                        </div>

                    </div>
                         <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Area:</label>
                        <div class="col-md-6 parsleymulti">
                            <select class="select2 form-control" name="area[]" id="area" required multiple>
                               {!! $area !!}
                            </select>
                        </div>
                    </div>
                           <div class="mb-3 row hr_show">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Casual Leave</label>
                        <div class="col-md-6 parsleymulti">
                            <?php $groupname = \Session::get('groupname');   ?>
                            <?php if ($groupname != '16') { ?>
                            <input type="text" name="c_l"value="{{$employee_official[0]->c_l}}" class="form-control" required>
                            <?php } else { ?>
                            <input type="text" name="c_l"value="{{$employee_official[0]->c_l}}" class="form-control">
                            <?php } ?>
                        </div>
                         <div class="col-md-1 showinline">

 </div>
                    </div>
                           <div class="mb-3 row hr_show">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Sick Leave</label>
                        <div class="col-md-6 parsleymulti">
                            
                            <?php if ($groupname != '16') { ?>
                            <input type="text" name="s_l"value="{{$employee_official[0]->s_l}}"  class="form-control" required>
                            <?php } else { ?>
                            <input type="text" name="s_l"value="{{$employee_official[0]->s_l}}"  class="form-control">
                            <?php } ?>
                        </div>
                         <div class="col-md-1 showinline">

 </div>
                    </div>
                    </fieldset>
                </div>
                <div class="col-lg-6 col-md-6">
                    <fieldset>
						<h4 class="mb-3 text-primary">Other Details</h4>
                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Position:</label>
                        <div class="col-md-6 ">
                            <select class="select2 form-control" name="job_title" id="job_title" required>
                             {!! $job_title !!}  
                            </select>
                        </div>
                    </div>
                     <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Grade:</label>
                        <div class="col-md-6 ">
                            <select class="select2 form-control" name="position" id="position" required>
                              {!! $position !!}
                            </select>
                        </div>
                    </div>

                   
                        
                     <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Date Of Joining:</label>
                        <div class="col-md-6 dateparsley">
                            <!-- <div class="input-group col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd"> -->
                                <input  class="form-control date_of_joining" id="date_of_joining" name="date_of_joining" size="16" type="text" value="{{$employee_official[0]->date_of_joining}}" required>
                              
                            <!-- </div> -->
                        </div>
                    </div>
                        
                    <div class="mb-3 row">
                       <label class="col-lg-5 col-md-5 col-form-label">ESI Number:</label>
                       <div class="col-md-6">
                           <input type="text" class="col-lg-10 col-md-10 form-control" value="{{$employee_official[0]->esi_no}}" name="esi_no" id="esi_no">
                       </div>
                    </div>
                         <div class="mb-3 row">
                       <label class="col-lg-5 col-md-5 col-form-label">ESI Dispensary:</label>
                       <div class="col-md-6">
                           <input type="text" class="form-control" value="{{$employee_official[0]->esi_dispensary}}" name="esi_dispensary" id="esi_dispensary">
                       </div>
                    </div>
                           <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label">PF Date:</label>
                        <div class="col-md-6">
                            <!-- <div class="input-group col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd"> -->
                                <?php if ($employee_official[0]->pf_date == "0000-00-00") {
  $pf_date = '';
} else {
  $pf_date = $employee_official[0]->pf_date;
}?>
                                <input  class="form-control form_date from_date date_of_joining" id="pf_date" name="pf_date" size="16" type="text" value="{{$pf_date}}" >
                                
                            <!-- </div> -->
                        </div>
                    </div>
                    <div class="mb-3 row">
                       <label class="col-lg-5 col-md-5 col-form-label">Provident Fund Number:</label>
                       <div class="col-md-6 ">
                           <input type="text" class="form-control" value="{{$employee_official[0]->pf_no}}" name="pf_no" id="pf_no" maxlength="25">
                       </div>
                   </div>
                        
                    <div class="mb-3 row">
                       <label class="col-lg-5 col-md-5 col-form-label">UAN Number:</label>
                       <div class="col-md-6 parsleyuan ">
                           <input type="text" class=" form-control" value="{{$employee_official[0]->uan_no}}" name="uan_no" id="uan_no" minlength="12" maxlength="12">
                       </div>
                    </div>
                    <div class="mb-3 row">
                       <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Mobile Number:</label>
                       <div class="col-md-6 dateparsley">
                           <input type="text" class="form-control work_telephone_number" value="{{$employee_official[0]->work_telephone_number}}" name="work_telephone_number" id="work_telephone_number" required minlength="10" maxlength="12">
                       </div>
                    </div>
                        
                    <div class="mb-3 row">
                       <label class="col-lg-5 col-md-5 col-form-label">Alternative Number:</label>
                       <div class="col-md-6 dateparsley">
                           <input type="text" class="form-control alternative_telephone_number" value="{{$employee_official[0]->alternative_telephone_number}}" name="alternative_telephone_number" id="alternative_telephone_number" minlength="10"  maxlength="12">
                       </div>
                    </div>
                        
                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label">Biometric Emp No:</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="biometric_empno" value="{{$employee_official[0]->biometric_empno}}" id="biometric_empno" >
                        </div>
                    </div>
                        
                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Employee type:</label>
                        <div class="col-md-6">
                            <select class="select2 form-control" name="employee_type" id="employee_type" required>
                               {!! $employee_type !!}
                            </select>
                        </div>
                    </div>
                        
                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Group Type:</label>
                        <div class="col-md-6">
                            <select class="select2 form-control" name="group_type" id="group_type" required>
                               {!! $group_type !!}
                            </select>
                        </div>
                    </div>
                         <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Active:</label>
                        <div class="col-md-6">
                            <select class="select2 form-control" name="active" id="active" required>
                                <option <?php if ($employee_official[0]->active == "Yes")
  echo "selected";  ?> value="Yes">Yes</option>
                                <option <?php if ($employee_official[0]->active == "No")
  echo "selected";  ?> value="No">No</option>
                            </select>
                        </div>
                    </div>



                    
                <div class="mb-3 row">
                <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>OT Formula:</label>  
                <div class="col-md-6">
                <select class="form-control select2 ot_formula" id="ot_formula" name="ot_formula"  type="text"  style="width: 100%;" required>
                <option value="">--Please Select--</option>
                <option  <?php if ($employee_official[0]->ot_formula == "1")
  echo "selected";  ?> value="1" >OT Applicable</option>
                <option  <?php if ($employee_official[0]->ot_formula == "2")
  echo "selected";  ?> value="2" >OT Not Applicable</option>
                </select>
                  
                </div> 
                </div>
   <div class="mb-3 row hr_show">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Earn Leave</label>
                        <div class="col-md-6 parsleymulti">
                            
                            <?php if ($groupname != '16') { ?>
                            <input type="text" name="e_l"value="{{$employee_official[0]->e_l}}"  class="form-control" required>
                            <?php } else { ?>
                            <input type="text" name="e_l"value="{{$employee_official[0]->e_l}}"  class="form-control">
                            <?php } ?>
                        </div>
                         <div class="col-md-1 showinline">

                     </div>
                    </div>
                    
                     <!-- ZONE -->
                     <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label">Zone</label>
                        <div class="col-md-6">
                            <select class="select2 form-control" name="zone_id" id="zone_id">
                                
                              <option value="">-- Please Select --</option>
                              <option <?php if ($employee_official[0]->zone_id == "16")
  echo "selected";  ?> value="16">East</option>
                              <!--<option <?php //if($employee_official[0]->zone_id=="17") echo "selected";  ?> value="17">West</option>-->
                              <option <?php if ($employee_official[0]->zone_id == "18")
  echo "selected";  ?> value="18">North</option>
                              <option <?php if ($employee_official[0]->zone_id == "25")
  echo "selected";  ?> value="25">South 1</option>
                              <option <?php if ($employee_official[0]->zone_id == "24")
  echo "selected";  ?> value="24">South 2</option>
                              <option <?php if ($employee_official[0]->zone_id == "26")
  echo "selected";  ?> value="26">South 3</option>
                              <!--<option <?php //if($employee_official[0]->zone_id=="20") echo "selected";  ?> value="20">Central</option>-->
                              <option <?php if ($employee_official[0]->zone_id == "27")
  echo "selected";  ?> value="27">Central West</option>
                              <option <?php if ($employee_official[0]->zone_id == "19")
  echo "selected";  ?> value="19">Corporate</option>
                            </select>
                        </div>
                    </div>
                    <!-- end -->
                    
                    </fieldset>
                </div>

                  <div class="mb-3 col-md-12 text-center">
                    <button type="button"  class="btn save_off btn-success save_offical_hide mt-3 px-4" data-form="0" id="save_off">Save</button>
                
            </div>

              </div>

            </div>
            
            </form>
        </div>

		    <!-- Personal form content -->
        <div class="tab-pane fade" id="Section2" role="tabpanel">
                    <form  id="personal_form" >
                {{csrf_field()}}
                <input type="hidden" name="form_name" id="form_name" value="personal_details">
                <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                     <fieldset>
                      <h4 class="mb-3 text-primary">Personal Details</h4>

                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Gender :</label>
                        <div class="col-md-6">
                            <select  class="col-md-6 form-control select2" name="gender" id="gender"  required style="width:100%;">
                                <option value="">-- Please Select --</option>
                                <option value="1" {{$employee_personal[0]->gender == "1" ? 'selected' : ''}}>Male</option>
                                <option value="2" {{$employee_personal[0]->gender == "2" ? 'selected' : ''}}>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                      <label class="col-lg-5 col-md-5 col-form-label">Marital Status:</label>
                      <div class="col-md-6">
                          <select class="select2 form-control marital_status" name="marital_status" id="marital_status" style="width:100%;">
                              <option value="">-- Please select --</option>
                              <option value="1" {{ $employee_personal[0]->marital_status == "1" ? 'selected' : ''}}>Single</option>
                              <option value="2" {{ $employee_personal[0]->marital_status == "2" ? 'selected' : ''}}>Married</option>
                          </select>
                      </div>
                    </div>

                    <div class="mb-3 row">
                      <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Nationality:</label>
                      <div class="col-md-6 ">
                          <select type="text" class="col-md-6 form-control select2" id="nationality" name="nationality" required style="width:100%;">
                             {!! $nationality !!}
                          </select>
                      </div>
                    </div>
                        
                       

                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Date of Birth:</label>
                        <div class="col-md-6 dateofbirth">
                           <!--  <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
 -->                                <input class="form-control date_of_birth" id="date_of_birth" name="date_of_birth" size="16" type="text" value="{{$employee_personal[0]->date_of_birth}}" required>
                                
                            
                                      
                            <!-- </div> -->
                              <span class="dob_error_message" style="color: #cc0000"></span>  
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label">Age:</label>
                            <div class="col-md-6 ">
                               <input type="text" class="col-lg-10 col-md-10 form-control" id="age" name="age" value="{{$employee_personal[0]->age}}">
                            </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req"></span>Mother Tongue:</label>
                        <div class="col-md-6 ">
                            <select class="select2 form-control" name="monther_tongue" id="monther_tongue"  style="width:100%;">
                               {!! $mother_tongue !!}
                            </select>
                       </div>
                    </div>

                  <div class="mb-3 row">
                    <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Religion:</label>
                     <div class="col-md-6 ">
                        <select class="select2 form-control" name="religion" id="religion" required style="width:100%;">
                            {!! $religion !!}
                        </select>
                    </div>
                  </div>

                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Language known:</label>
                        <div class="col-md-6 field_wrapper">
                            
                                @if(!empty($employee_personal[0]->language))
                          @php $result1[] = json_decode($employee_personal[0]->language); 


                    @endphp 
                          @foreach($result1 as $key => $value)
                      @foreach($value as $k => $v1)


                    <input type="text" name="language[]"  id="#lang" data-provide="typeahead" autocomplete="off" class="lang lang-0 form-control" value="{{$v1[0]}}" >
                    <div class="mb-3 row">


                    <div class="l-checkbox">
                     <div class="c-checkbox">
                     <a href="javascript:void(0);" class="add_button" title="Add field"><i class="glyphicon glyphicon-plus-sign" style="top:0px;"></i></a>
                     </div>
                    <div class="c-checkbox">
                    <input type="checkbox" name="langr{{$k}}" value="read" class="read-0" {{$v1[1] == 'read' ? "checked" : ""}}>
                    <span class="check_mark"></span>
                    <label for="">Read</label>
                    </div>
                    <div class="c-checkbox">
                     <input type="checkbox" name="langw{{$k}}" value="write" class="write-0" {{$v1[2] == 'write' ? "checked" : ""}}>
                    <span class="check_mark"></span>
                    <label for="">write</label>
                    </div>
                    <div class="c-checkbox">
                     <input type="checkbox" name="langs{{$k}}" value="speak" class="speak-0" {{$v1[3] == 'speak' ? "checked" : ""}}>
                    <span class="check_mark"></span>
                    <label for="">speak</label>
                    </div>
                    </div>


                    </div>
                    <div>
                    </div>

                    @endforeach
                  @endforeach
                @else



            <div class="mb-3 row mt-4">
             <input type="text" name="language[]"  data-provide="typeahead" autocomplete="off" class="lang lang-0 form-control" value="" required>
            <div class="l-checkbox">
              <div class="c-checkbox">
               <a href="javascript:void(0);" class="add_button" title="Add field"><i class="glyphicon glyphicon-plus-sign" style="top:0px;"></i></a>
               </div>
              <div class="c-checkbox">
              <input type="checkbox" name="langr0" value="read" class="read-0" >
              <span class="check_mark"></span>
              <label for="">Read</label>
              </div>

              <div class="c-checkbox">
              <input type="checkbox" name="langw0" value="write" class="write-0" >
              <span class="check_mark"></span>
              <label for="">write</label>
              </div>

              <div class="c-checkbox">
              <input type="checkbox" name="langs0" value="speak" class="speak-0" >
              <span class="check_mark"></span>
              <label for="">Speak</label>
              </div>

            </div>

            </div>

            <div>
            </div>

        @endif
                            
                        </div>
						 </div>
                    </fieldset>

                </div>
                        
                <div class="col-lg-6 col-md-6">
                    <fieldset>
						<h4 class="mb-3 text-primary">Additional Details</h4>
                        <div class="mb-3 row">
                            <label class="col-lg-5 col-md-5 col-form-label"><span class="req"></span>Blood Group:</label>
                            <div class="col-md-6 ">
                                <select class="select2 form-control" name="blood_group" id="blood_group"  style="width:100%;">
                                   {!!  $blood_group !!}
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-lg-5 col-md-5 col-form-label">Personal Mail ID:</label>
                            <div class="col-md-6 " id="parsleymail">
                                <input type="text" name="personal_mail" id="personal_mail" class="form-control personal_mail" value="{{$employee_personal[0]->personal_mail}}"  />
<!--                                <span class="btn btn-danger email_vali" id="" style="display:none;"> Email format is example123@gmail.com</span>-->
                            </div>
                        </div>

                        <div class="mb-3 row">
                           <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Personal Contact:</label>
                           <div class="col-md-6 " id="parsleymobile">
                               <input type="text" name="personal_mobile" id="personal_mobile" class="form-control personal_mobile" value="{{$employee_personal[0]->personal_mobile}}" minlength="10" maxlength="12"  />
                           </div>
                        </div>

                        

                        

                        <div class="mb-3 row">
                           <label class="col-lg-5 col-md-5 col-form-label">PAN Number:</label>
                           <div class="col-md-6 ">
                               <input type="text" class="col-lg-10 col-md-10 form-control pan_number" name="pan_number" value="{{$employee_personal[0]->pan_number}}" id="pan_number" maxlength="10" minlength="10">
                               <span style="font-size: 85%;">(format:(1-5 and 10)-alphabet,(6-9)-numeric)</span>
                         <span class="pan" style="font-size:11px;color:red;">Please Enter Valid Pan Number</span> 
                           </div>
                        </div>

                        <div class="mb-3 row">
                           <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>AADHAR Number:</label>
                           <div class="col-md-6 ">
                               <input type="text" class="col-lg-10 col-md-10 form-control aadhar_number" name="aadhar_number" value="{{$employee_personal[0]->aadhar_number}}" id="aadhar_number" required minlength="12" maxlength="12">
                           </div>
                        </div>

                        <div class="mb-3 row">
                           <label class="col-lg-5 col-md-5 col-form-label">ID Type 1:</label>

                           <div class="col-md-6">
                               <select class="select2 form-control id_name" name="id_name" id="id_name" style="width:100%;">
                                  
                                   {!! $idtype1 !!}
                               </select>
                           </div>
                       </div>

                        <div class="mb-3 row">
                           <label class="col-lg-5 col-md-5 col-form-label">ID Number 1:</label>
                           <div class="col-md-6 ">
                               <input type="text" class="col-lg-10 col-md-10 form-control id_number" name="id_number" id="id_number" value="{{$employee_personal[0]->id_number}}">
                           </div>
                        </div>

                        <div class="mb-3 row">
                           <label class="col-lg-5 col-md-5 col-form-label">ID Type 2:</label>
                           <div class="col-md-6">
                            <select class="select2 form-control id_name1"  name="id_name1" id="id_name1" style="width: 100%;">
                                {!! $idtype2 !!}
                            </select>
                        </div>
                        </div>

                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label">ID Number 2:</label>
                         <div class="col-md-6 ">
                               <input type="text" class="col-lg-10 col-md-10 form-control id_number1" name="id_number1" id="id_number1" value="{{$employee_personal[0]->id_number1}}" >
                           </div>

                    </div>
                    </fieldset>
                </div>


                   <div class="col-lg-6 col-md-6">
                     <fieldset>
                      	<h4 class="mb-3 text-primary">Family Details</h4>

                    <div class="mb-3 row">
                          <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Father Name :</label>
                          <div class="col-md-6 ">
                              <input type="text" class="col-md-10 form-control father_name" name="father_name" id="father_name" value="{{$employee_personal[0]->father_name}}" required>
                          </div>
                          
                    </div>
                    <div class="mb-3 row">
                           <label class="col-lg-5 col-md-5 col-form-label">AADHAR Number:</label>
                           <div class="col-md-6 ">
                               <input type="text" class="col-lg-10 col-md-10 form-control father_aadhar_number" name="father_aadhar_number" value="{{$employee_personal[0]->father_aadhar_number}}" id="father_aadhar_number" minlength="12" maxlength="12" >
                            </div>
                    </div>
                       

                    <div class="mb-3 row">
                          <label class="col-lg-5 col-md-5 col-form-label">Mother Name :</label>
                          <div class="col-md-6 ">
                              <input type="text" class="col-md-10 form-control mother_name" name="mother_name" id="mother_name" value="{{$employee_personal[0]->mother_name}}" >
                          </div>
                    </div>
                    <div class="mb-3 row">
                           <label class="col-lg-5 col-md-5 col-form-label">AADHAR Number:</label>
                           <div class="col-md-6">
                               <input type="text" class="col-lg-10 col-md-10 form-control mother_aadhar_number" name="mother_aadhar_number" value="{{$employee_personal[0]->mother_aadhar_number}}" id="mother_aadhar_number" minlength="12" maxlength="12" >
                           </div>
                    </div>

                  <div class="mb-3 row single_div"style="display:none;">
                    <label for="single" class="col-lg-5 col-md-5 " >Spouse Name:</label>
                    <div class="col-md-6 ">
                        <input type="text" class="col-lg-10 col-md-10 form-control spouse_name" id="spouse_name" name="spouse_name" value="{{$employee_personal[0]->spouse_name}}">
                    </div>
                  </div>
                  <div class="mb-3 row single_div " style="display:none;">
                           <label for="single" class="col-lg-5 col-md-5 ">AADHAR Number:</label>
                           <div class="col-md-6 ">
                               <input type="text"  class="col-lg-10 col-md-10 form-control spouce_aadhar_number" name="spouce_aadhar_number" value="{{$employee_personal[0]->spouce_aadhar_number}}" id="spouce_aadhar_number" minlength="12" maxlength="12" >
                           </div>
                    </div>

                  <div class="mb-3 row single_div" style="display:none;">
                    <label for="single" class="col-lg-5 col-md-5 col-form-label">Spouse DOB:</label>
                    <div class="col-md-6 ">
                       <!--  <div class="input-group form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd"> -->
                            <input type="text"  class="col-lg-10 col-md-10 form-control from_date spouse_dob" id="spouse_dob" name="spouse_dob" value="{{$employee_personal[0]->spouse_dob}}" >
                           
                        <!-- </div> -->
                        <span class="sdob_error_message" style="color: #cc0000"></span>
                    </div>
                  </div>

            </fieldset>
</div>
                 
                <div class="col-lg-6 col-md-6 single_div"style="display:none;">
                    <fieldset>
                        <legend>Additional Details</legend>
                        <div class="mb-3 row ">
                            <label class="col-lg-5 col-md-5 " >Number of Child:</label>
                            <div class="col-md-6 ">
                                <input type="text" class="col-lg-10 col-md-10 form-control no_of_children" maxlength="1" id="no_of_children" value="{{$employee_personal[0]->no_of_children}}" name="no_of_children">
                            </div>
                        </div>
			<div class="child_div">
				@if(!empty($employee_personal[0]->no_of_children)) 
					<?php  
						$childname = json_decode($employee_personal[0]->children_name1);
						$childdob = json_decode($employee_personal[0]->children_dob1);

						if (is_array($childname)) {
							foreach ($childname as $k => $val) { 
					?>
						<div class="mb-3 row">
							<label class="col-lg-5 col-md-5 col-form-label">Child Name {{$k + 1}} </label>
							<div class="col-md-6">
								<input type="text" class="col-lg-10 col-md-10 form-control children_name children_name{{$k}}" name="children_name[]" value="{{$val}}">
							</div>
						</div>

						<div class="mb-3 row">
							<label class="col-lg-5 col-md-5 col-form-label">Child DOB {{$k + 1}}:</label>
							<div class="col-md-6">
								<div class="input-group m-b">
									<input type="text"  
										class="col-lg-10 col-md-10 form-control children_dob children_dob{{$k}} from_date"  
										name="children_dob[]"  
										value="{{ isset($childdob[$k]) ? $childdob[$k] : '' }}">
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
							</div>
						</div>
					<?php 
							} 
						} 
					?>
				@endif
			</div>

           </fieldset>
                </div>

              <div class="mb-3 col-md-12 text-center">
                <button type="button"  class="btn btn-success btn_save mt-4 px-4" data-form="1" id="save">Save</button>

            </div>

              </div>
            
            </form>
        </div>

		
		   <!-- Contact form content -->
		
        <div class="tab-pane fade" id="Section3" role="tabpanel">
              <form id="contact_form"  >
                     {{csrf_field()}}
                <input type="hidden" name="form_name" id="form_name" value="contact_details"/>
                <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
              <div class="row">
                <div class="col-lg-6 col-md-6">
                     <fieldset>
                           <h4 class="mb-3 text-primary">Permanent Details</h4>
                       
                        <div class="mb-3 row" style="margin-top:58px;">
                            <label class="col-lg-5 col-md-5 col-form-label">Street:</label>
                            <div class="col-md-7 ">
                                <input type="text" class=" form-control" name="p_street"  id="p_street" class="p_street" value="{{$employee_contact[0]->permanent_street}}" >
                            </div>
                        </div>
                        
                        <div class="mb-3 row" >
                          <label class="col-lg-5 col-md-5 col-form-label">Address :</label>
                          <div class="col-md-7 ">
                              <textarea class="form-control" id="p_address" name="p_address" >{{$employee_contact[0]->permanent_street_address}}</textarea>
                          </div>
                        </div>
                        
                        
                        
                        <div class="mb-3 row">
                            <label class="col-lg-5 col-md-5 col-form-label">Flat No:</label>
                            <div class="col-md-7 ">
                                <input type="text" class=" form-control" name="p_flat_no" id="p_flat_no" class="p_flat_no" value="{{$employee_contact[0]->permanent_flat_no}}" >
                            </div>
                        </div>

                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Country:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" name="p_country" id="p_country" class="p_country" required style="width: 100%;">
                                {!! $p_country !!}
                            </select>
                        </div>

                    </div>

                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>State:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" id="p_state" name="p_state" class="p_state" required style="width: 100%;">
                                {!! $p_state !!}
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>City:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" id="p_city" name="p_city" class="p_city" required style="width: 100%;">
                               {!! $p_city !!}
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Pincode:</label>
                         <div class="col-md-7 pincodeparsley">
                            <input type="text" class="col-lg-6 col-md-6 form-control" id="p_pincode" name="p_pincode" value="{{$employee_contact[0]->permanent_postal_code}}"  required  maxlength="6">
                        </div>
                    </div>
                        
                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label">Locality:</label>
                         <div class="col-md-7 ">
                            <input type="text" class="col-lg-6 col-md-6 form-control" id="p_locality" name="p_locality" value="{{$employee_contact[0]->permanent_locality}}" >
                        </div>
                    </div>
                        
                        
                     
                    </fieldset>

                </div>
                <div class="col-lg-6 col-md-6">
                    <fieldset>
						    <h4 class="mb-3 text-primary">Current Details</h4>
                        
                         <div class="mb-3 row" id="radio_address">
                              <label for="same_address" class=" control-label col-md-5 "></label>
                              <div class="l-checkbox col-md-7">
                            <div class=" c-checkbox">
                                <input type="checkbox" name="same_address" id="same_address">
                                <span class="check_mark"></span>
                                <label for="">Same as Permanent Address</label>
                            </div>
                            
                        </div>
                          </div>

                        
                         <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label">Street:</label>
                        <div class="col-md-7 ">
                            <input type="text" class=" form-control" name="c_street" id="c_street" class="c_street" value="{{$employee_contact[0]->current_street}}" >
                                
                        </div>
                         </div>
                        
                        
                        <div class="mb-3 row">
                          <label class="col-lg-5 col-md-5 col-form-label">Address :</label>
                          <div class="col-md-7 ">
                              <textarea class="form-control" id="c_address" name="c_address"  >{{$employee_contact[0]->current_street_address}}</textarea>
                          </div>
                    </div>
    
                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label">Flat No:</label>
                        <div class="col-md-7 ">
                            <input type="text" class=" form-control" name="c_flat_no" id="c_flat_no" value="{{$employee_contact[0]->current_flat_no}}" class="c_flat_no" >
                                
                        </div>
                    </div>
                        
                    <div class="mb-3 row country_contact">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Country:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" id="c_country" name="c_country" required style="width: 100%;">
                               {!! $c_country !!}
                            </select>
                        </div>
                    </div>
                        
                    

                    <div class="mb-3 row country_contact">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>State:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" id="c_state" name="c_state" required style="width: 100%;">
                                {!! $c_state !!}
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row country_contact">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>City:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" id="c_city" name="c_city" required="" style="width: 100%;">
                                {!! $c_city !!}
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Pincode:</label>
                         <div class="col-md-7 pincodeparsley">
                            <input type="text" class="col-lg-6 col-md-6 form-control" id="c_pincode" name="c_pincode" value="{{$employee_contact[0]->current_postal_code}}" required  maxlength="6">
                        </div>
                    </div>
                        
                    <div class="mb-3 row">
                        <label class="col-lg-5 col-md-5 col-form-label">Locality:</label>
                         <div class="col-md-7 ">
                            <input type="text" class="col-lg-6 col-md-6 form-control" id="c_locality" name="c_locality" value="{{$employee_contact[0]->current_locality}}" >
                        </div>
                    </div>

                    </fieldset>
                </div>


                  <div class="row">
                <div class="col-lg-12 col-md-12">
                     <fieldset>
					<h4 class="mb-3 text-primary">Emergency Details</h4>
                        <table class="table table-striped table-bordered " >
                                <thead class="table-warning">
                                    <tr>
                                        <th> Name</th>
                                        <th> Relation Type</th>
                                        <th> Address</th>
                                        <th> Contact Number</th>
                                        <th> Action</th>
                                    </tr>

                                </thead>
                                <tbody class="response_data">
                                    
                                    @if(!empty($employee_contact[0]->emergency_contacts))
                            @php $result[] = json_decode($employee_contact[0]->emergency_contacts); 

                    @endphp 
                            @foreach($result as $key => $value)
                         @foreach($value as $k => $v)

                      <tr>
                      <td><input type="text" name="emer_name[]" class="form-control emer_name name-0" value="{{$v[0]}}"></td>
                      <td><input type="text" name="relation[]" class="form-control relation-0" value="{{$v[1]}}"></td>
                      <td><textarea  name="address[]" class="form-control address-0">{{$v[2]}}</textarea></td>
                      <td><input type="text" name="mobile_no[]" id="mobile_no[]"  class="form-control mobile_no number-0" value="{{$v[3]}}" maxlength="12"></td>                              
                      <td>
                      <?php      if ($k == 0) { ?>

						  
						      <button type="button" class="btn btn-success btn-sm add_field_button">
   								   <i class="fas fa-plus-circle"></i>
								</button>
						  
                      <?php      } else { ?>                                       
						  
						          <button type="button" class="btn btn-sm btn-danger remove_field">
								  <i class="fas fa-minus-circle"></i>
									</button>
						  
                      <?php  } ?>
                      </td>
                      </tr>
                      @endforeach
                    @endforeach
                    @else
          <tr>
            <tr>
            <td><input type="text" name="emer_name[]" class="form-control emer_name name-0" value=""></td>
            <td><input type="text" name="relation[]" class="form-control relation-0" value=""></td>
            <td><textarea  name="address[]" class="form-control address-0"></textarea></td>
            <td><input type="text" name="mobile_no[]" id="mobile_no[]" class="form-control mobile_no number-0" value="" maxlength="10"></td>
            <td>						      <button type="button" class="btn btn-success btn-sm add_field_button">
   								   <i class="fas fa-plus-circle"></i>
								</button></td>
          </tr>

          @endif
                            </table>

                    </fieldset>

                </div>

              </div>
                   <div class="mb-3 text-center">
                <button type="button"  class="btn btn-success btn_save mt-4 px-4" data-form="2" id="save">Save</button>
             
            </div>

              </div>
                </form>
         
        </div>

		
        <div class="tab-pane fade" id="Section4" role="tabpanel">
            <h4 class="mb-3 text-primary">Bank Details</h4>
			<a class="show_form_btn bank_details">  <button class="btn btn-primary" style="float:right; font-size: 80%; position: relative; top:-65px">+</button></a>
                              <div class="panel panel-info bank_details_table ">
                        <div class="container" >
                        <div class="container-table100" >
                        <div class="wrap-table100">
                            <div class="table100 ver1 m-b-110">
<div class="table-responsive">
  <table data-vertable="ver1" class="table table-bordered table-striped table-hover" style="table-layout: fixed; width:100%;">
    <thead class="table-dark">
      <tr class="row100 head text-center align-middle">
        <th class="column100 column1" style="width:40px;">No</th>
        <th class="column100 column2">Acc Type</th>
        <th class="column100 column3">Bank Name</th>
        <th class="column100 column4">Branch Name</th>
        <th class="column100 column5">IFSC Code</th>
        <th class="column100 column7">Acc H. Name</th>
        <th class="column100 column8">Acc Number</th>
        <th class="column100 column8">Documents</th>
        <th class="column100 column8">Action</th>
      </tr>
    </thead>
    <tbody>
      @if(count($employee_salary) > 0)
        @foreach($employee_salary as $key => $value)
        <tr class="row100 text-center align-middle">
        <td class="column100 column8" data-column="column8">{{ $key + 1 }}</td>
        <td class="column100 column1" data-column="column1">{{ $value->lookup_code }}</td>
        <td class="column100 column2" data-column="column2">{{ $value->bank_name }}</td>
        <td class="column100 column3" data-column="column3">{{ $value->branch_name }}</td>
        <td class="column100 column4" data-column="column4">{{ $value->ifsc_code }}</td>
        <td class="column100 column6" data-column="column6">{{ $value->account_holder_name }}</td>
        <td class="column100 column7" data-column="column7">{{ $value->account_number }}</td>

        @php $bk_file = $value->files ? url('/uploads/file_uploads/' . $value->files) : ''; @endphp
        <td class="column100 column7" data-column="column7">
        @if($bk_file != '')
        <a download href="{{ $bk_file }}">
        <img height="20px" width="20px" src="{{ url('/images/download.png') }}" alt="Download">
        </a>
        @endif
        </td>

        <td class="column100 column8 actioncolumn" data-column="column8">
        <button class="btn btn-sm btn-primary edit_details" data-form="salary_form" id="{{ $value->id }}" type="button">
        <i class="bi bi-pencil"></i>
        </button>
        <button class="btn btn-sm btn-danger delete_details" data-form="salary_form" data-formid="3" id="{{ $value->id }}" type="button">
        <i class="bi bi-trash"></i>
        </button>
        </td>
        </tr>
      @endforeach
  @else
    <tr class="row100 text-center">
      <td colspan="9" class="column100 column8" data-column="column8">No Records Found</td>
    </tr>
  @endif
    </tbody>
  </table>
</div>

                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
			
			                    <div class="panel panel-info bank_details_form" style="margin-top:7px; display:none; ">

                          <!------------------------------------------------------>
                          <form id="salary_form" data-parsley-validate >
                               {{csrf_field()}}
                            <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
                            <input type="hidden" name="row_id" class="salary_row" id="row_id"  value=""/>
                            <input type="hidden" name="form_name" id="form_name" value="salary_details"/>
                           
                          <div class="row" style="padding-top:35px;">
                             
                                
                                    <div class="col-md-6">


                                  <div class="mb-3 row">
                                      <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Bank Name:</label>
                                      <div class="col-md-6 ">
                                           <input type="text" name="bank_name" id="bank_name" value="" class="form-control" required/>   
                                      </div>
                                  </div>
                                        
                                    <div class="mb-3 row">
                                      <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Branch Name :</label>
                                      <div class="col-md-6 ">
                                           <input type="text" name="branch_name" id="branch_name" value="" class="form-control" required/>   
                                      </div>
                                    </div>

                                    <div class="mb-3 row accountparsley" id="accountparsley">
                                      <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Account Type:</label>
                                      <div class="col-md-6 ">
                                           <select name="account_type" id="account_type" value="" class="select2 " required>
                                        {!! $account_type !!}
                                           </select>
                                      </div>
                                    </div>

                                    <div class="mb-3 row" >
                                        <label class="col-lg-5 col-md-5 col-form-label">File:</label> 
                                        <div class="col-md-6" style="margin-top:-6%;margin-left:42.666667%;">
                                            <input type="file" name="bank_file" class="bank_file_upload" id="file"><span class="b_name"></span>
                                            <label for="file-upload" class="custom-file-upload  bank_choose_file  ">
                                                     <i class="fa fa-cloud-upload "></i>&nbsp;File Upload
                                            </label>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">    
                                    <div class="mb-3 row parsleyifsc">
                                      <label class="col-lg-5 col-md-5 "><span class="req">*</span>IFSC Code :</label>
                                      <div class="col-md-6">
                                           <input type="text" name="ifsc_code" id="ifsc_code" value="" class="form-control ifsc_code" maxlength="11" required>   
                                            <span style="font-size: 85%;">(format:(1-4)-alphabet,(5-11)-numeric)</span>
                         <span class="ifscpan" style="font-size:11px;color:red;" >Please Enter Valid IFSC Code</span> 
                                      </div>
                                    </div>
                                        
                                    <div class="mb-3 row">
                                      <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Account Holder Name :</label>
                                      <div class="col-md-6">
                                           <input type="text" name="account_holder_name" id="account_holder_name" value="" class="form-control" required/>   
                                      </div>
                                    </div>
                                        
                                  <div class="mb-3 row">
                                      <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Account Number:</label>
                                      <div class="col-md-6 ">
                                           <input type="text" name="account_number" id="account_number" value="" class="form-control" required/>   
                                      </div>
                                    </div>
                                    </div>
                               
                              <div class="col-md-12 text-center">
                               <div class="mb-3 text-center">
                          <button  type="button" class="btn btn-success btn_save px-4" data-form="3" id="save">Save</button> &nbsp;&nbsp;&nbsp;
                          <button type="button"  class="btn btn-secondary bank_form_show px-4" >Cancel</button>
                      </div>

                           </div>
                          </div>
                    </form>
                    </div>
			
        </div>

        <!-- education -->
		        <div class="tab-pane fade" id="Section5" role="tabpanel">
         <h4 class="mb-3 text-primary"> Education Details </h4>
			<a class="show_form_btn education_details">  <button class="btn btn-primary" style="float:right; font-size: 80%; position: relative; top:-65px">+</button></a>
<div class="container-table100 mb-4">
    <div class="wrap-table100">
        <div class="table100 ver1 border rounded shadow-sm p-2">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0" data-vertable="ver1">
                    <thead class="table-primary text-center">
                        <tr class="row100 head">
                            <th class="column100 column1" width="5%">No</th>
                            <th class="column100 column2" width="10%">Education Level</th>
                            <th class="column100 column3" width="15%">Institution Name</th>
                            <th class="column100 column4" width="15%">Board/Course</th>
                            <th class="column100 column5" width="10%">From</th>
                            <th class="column100 column6" width="10%">To</th>
                            <th class="column100 column7" width="10%">Percentage/Grade</th>
                            <th class="column100 column7" width="10%">Documents</th>
                            <th class="column100 column8" width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($employee_education) > 0)
                      @foreach($employee_education as $key => $value)
                    @php $files = json_decode($value->files); @endphp
                    <tr class="row100 text-center">
                    <td class="column100 column1">{{ ++$key }}</td>
                    <td class="column100 column2">{{ $value->lookup_code }}</td>
                    <td class="column100 column3">{{ $value->school_name }}</td>
                    <td class="column100 column4">{{ $value->school_board }}</td>
                    <td class="column100 column5">{{ $value->from_date }}</td>
                    <td class="column100 column6">{{ $value->to_date }}</td>
                    <td class="column100 column7">{{ $value->percentage }}</td>
                    <td class="column100 column7">
                    @foreach($files ?? [] as $v)
                  @php $bk_file = url('/uploads/file_uploads/' . $v); @endphp
                  @if(!empty($v))
                  <a download href="{{ $bk_file }}">
                  <img src="{{ url('/images/download.png') }}" width="20" height="20" alt="Download">
                  </a>
                  @endif
                  @endforeach
                    </td>
                    <td class="column100 column8">
                    <button class="btn btn-sm btn-primary edit_details" data-form="education_form" id="{{ $value->id }}">
                    <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete_details" data-form="education_form" data-formid="4" id="{{ $value->id }}">
                    <i class="bi bi-trash"></i>
                    </button>
                    </td>
                    </tr>
                @endforeach
            @else
          <tr class="row100 text-center">
          <td colspan="9" class="column100 column1">No Records Found</td>
          </tr>
      @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

                        <div class="panel panel-info education_details_form" style="margin-top:7px; display:none; ">
						<div class="card shadow-lg rounded-4 border-0 container">
                          <!------------------------------------------------------>
                          <form id="education_form" data-parsley-validate >
                            <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
                            <input type="hidden" name="row_id" class="education_row" id="row_id"  value=""/>
                            <input type="hidden" name="form_name" id="form_name" value="education_details"/>
                                <div class="row" style="padding-top:35px;">
                                   <div class="col-md-12" >
                                      <div class="row">
                                          <div class="col-md-6">
                                            <div class="mb-3 row">
                                                <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Education level :</label>
                                                <div class="col-md-6 educational_leavel1">
                                                   <select class="select2 form-control education_level" name="education_level" id="education_level" required>
                                                      {!! $education_level  !!}
                                                    </select>
                                                </div>
                                            </div>
                                              
                                            <div class="mb-3 row specify">
                                              <label class="col-lg-5 col-md-5 " ><span class="req">*</span>Please Specify:</label>
                                              <div class="col-md-6">
                                                  <input type="text" name="other" id="other" value="" class="form-control" required />
                                              </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label class="col-lg-5 col-md-5 edu_school1" ><span class="req">*</span>Name of the Institution:</label>
                                                <div class="col-md-6">
                                                    <input type="text" name="school_name"  id="school_name" value="" class="form-control edu_school11" required/>   
                                                </div>
                                            </div>

                                        <div class="mb-3 row education_level1">
                                            <label class="col-lg-5 col-md-5 edu_school2" ><span class="req">*</span>Boards of Education/University:</label>
                                            <div class="col-md-6 ">
                                                 <input type="text" name="school_board" id="school_board" value="" class="form-control education_level12" required />   
                                            </div>
                                        </div>
                                              
                                        <div class="mb-3 row" >
                                                <label class="col-lg-5 col-md-5 col-form-label">File:</label> 
                                                <div class=" col-md-6" style="margin-top:-6%;margin-left:42.666667%;">
                                                    <input type="file" name="education_file[]" class="education_file_upload" id="file" multiple><span class="e_name"></span>
                                                    <label for="file-upload" class="custom-file-upload  education_choose_file">
                                                             <i class="fa fa-cloud-upload "></i>&nbsp;File Upload
                                                    </label>
                                                </div>
                                        </div>
                                         
                                         </div>
                                         <div class="col-md-6">     
                                        <div class="mb-3 row course">
                                          <label class="col-lg-5 col-md-5 " ><span class="req">*</span>Course:</label>
                                              <div class="col-md-6">
                                                  <input type="text" name="course" id="course" value="" class="form-control" required />
                                              </div>
                                        </div>

                                          <div class="mb-3 row">
                                            <label class="col-lg-5 col-md-5 col-form-label" ><span class="req">*</span>From:</label>
                                            <div class="col-md-6 ">

                                                 <input type="text" name="from_date" id="ed_from_date" value="" class="form-control ed_from_date" required/>   

                                            </div>
                                          </div>

                                          <div class="mb-3 row">
                                            <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>To :</label>
                                            <div class="col-md-6">
                                                 <input type="text" name="to_date" id="ed_to_date" value="" class="form-control ed_to_date" required/>   
                                            </div>
                                          </div>

                                          <div class="mb-3 row">
                                            <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Percentage / Grade  :</label>
                                            <div class="col-md-6 ">
                                                 <input type="text" name="percentage" id="percentage" value="" class="form-control percentage" required maxlength="2">   
                                            </div>
                                          </div> 
                                          </div>
                                      </div>
                                      </div>
                                 </div>
                          
                          <div class="mb-3 text-center">
                          <button  type="button" class="btn btn-success btn_save px-4" data-form="4" id="save">Save</button> &nbsp;&nbsp;&nbsp;
                          <button type="button"  class="btn btn-secondary  education_form_show px-4" >Cancel</button>
                      </div>
                    </form>
                    </div>
                  </div>
        </div>
		
       <!-- EXPRIENCE -->

			        <div class="tab-pane fade" id="Section6" role="tabpanel">
						<h4 class="text-primary mb-3">Experience Details</h4>
									<a class="show_form_btn experience_details">  <button class="btn btn-primary" style="float:right; font-size: 80%; position: relative; top:-65px">+</button></a>
						
						 <div class="card shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 40px;">No</th>
                        <th scope="col">Organization Name</th>
                        <th scope="col">Organization Website</th>
                        <th scope="col">Designation</th>
                        <th scope="col">CTC</th>
                        <th scope="col">From</th>
                        <th scope="col">To</th>
                        <th scope="col">Documents</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($employee_experience) > 0)
                  @foreach($employee_experience as $key => $value)
                <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $value->organization_name }}</td>
                <td>{{ $value->organization_website }}</td>
                <td>{{ $value->designation }}</td>
                <td>{{ $value->ctc }}</td>
                <td>{{ $value->from_date }}</td>
                <td>{{ $value->to_date }}</td>
                <td class="text-center">
                @if($value->files != '')
              @php $bk_file = url('/uploads/file_uploads/' . $value->files) @endphp
              <a download href="{{ $bk_file }}">
              <img height="20" width="20" src="{{ url('/images/download.png') }}" alt="Download">
              </a>
              @endif
                </td>
                <td>
                <button class="btn btn-sm btn-primary edit_details" data-form="experience_form" id="{{ $value->id }}">
                <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger delete_details" data-form="experience_form" data-formid="5" id="{{ $value->id }}">
                <i class="bi bi-trash"></i>
                </button>
                </td>
                </tr>
            @endforeach
            @else
      <tr>
        <td colspan="9" class="text-center">No Records Found</td>
      </tr>
      @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

                    <div class="panel panel-info experience_details_form" style="margin-top:7px; display:none; ">
                          <!------------------------------------------------------>
                          <form id="experience_form" data-parsley-validate >
                            <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
                            <input type="hidden" name="row_id" class="experience_row" id="row_id"  value=""/>
                            <input type="hidden" name="form_name" id="form_name" value="experience_details"/>
                                <div class="row" style="padding-top:35px;">
                                   <div class="col-md-12" >
                                      <div class="row">
                                          <div class="col-md-6">
                                            <div class="mb-3 row">
                                                <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Organization Name :</label>
                                                <div class="col-md-7">
                                                   <input type="text" class="form-control" name="organization_name" id="organization_name" required>         
                                                </div>
                                            </div>
                                              
                                            <div class="mb-3 row">
                                              <label class="col-lg-5 col-md-5 " ><span class="req">*</span>Organization Website:</label>
                                              <div class="col-md-7">
                                                  <input type="text" name="organization_website" id="organization_website" value="" class="form-control" required />
                                              </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label class="col-lg-5 col-md-5 " ><span class="req">*</span>Designation:</label>
                                                <div class="col-md-7 ">
                                                    <input type="text" name="designation"  id="designation" value="" class="form-control" required/>   
                                                </div>
                                            </div>

                                        <div class="mb-3 row ">
                                            <label class="col-lg-5 col-md-5 col-form-label" ><span class="req">*</span>CTC:</label>
                                            <div class="col-md-7 ">
                                                 <input type="text" name="ctc" id="ctc" value="" class="form-control ctc" required />   
                                            </div>
                                        </div>
                                              
                                        <div class="mb-3 row" >
                                            <label class="col-lg-5 col-md-5 col-form-label">File:</label> 
                                            <div class=" col-md-7" style="margin-top:-6%;margin-left:42.666667%;">
                                                <input type="file" name="exp_file" class="exp_file_upload" id="file"><span class="ex_name"></span>
                                                <label for="file-upload" class="custom-file-upload exp_choose_file  ">
                                                         <i class="fa fa-cloud-upload "></i>&nbsp;File Upload
                                                </label>
                                            </div>
                                        </div>
                                              
                                              
                                              
                                        </div>
                                        <div class="col-md-6">      
                                        <div class="mb-3 row">
                                          <label class="col-lg-5 col-md-5 " ><span class="req">*</span>From:</label>
                                              <div class="col-md-7">
                                                  <input type="text" name="from_date" id="ex_from_date" value="" class="form-control ex_from_date " required />
                                       </div>
                                        </div>

                                          <div class="mb-3 row">
                                            <label class="col-lg-5 col-md-5 col-form-label" ><span class="req">*</span>To:</label>
                                            <div class="col-md-7">
                                                 <input type="text" name="to_date" id="ex_to_date" value="" class="form-control ex_to_date " required/>   

                                            </div>
                                          </div>
                                <div class="mb-3 row">
                                            <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Experience years :</label>
                                            <div class="col-md-7 ">
                                                 <input type="text" name="experience_type" id="experience_type" value="" class="form-control" required readonly/>   
                                            </div>
                                          </div>
                                          <div class="mb-3 row">
                                            <label class="col-lg-5 col-md-5 col-form-label"><span class="req">*</span>Reason for Leaving :</label>
                                            <div class="col-md-7 ">
                                                 <input type="text" name="reason_leaving" id="reason_leaving" value="" class="form-control" required/>   
                                            </div>
                                          </div>
                                          </div>
                                      </div>

                                      </div>
                                       <div class="mb-3 text-center">
                          <button  type="button" class="btn btn-success btn_save px-4" data-form="5" id="save">Save</button> &nbsp;&nbsp;&nbsp;
                          <button type="button"  class="btn btn-secondary experience_form_show px-4" >Cancel</button>
                      </div>
                        </div>
                    </form>
                    </div>
                </div>

	      <!-- SKILLS-->
	
	
		        <div class="tab-pane fade" id="Section7" role="tabpanel">
				<h4 class="text-primary mb-3"> Skills Details </h4>
					<a class="show_form_btn skill_details">  <button class="btn btn-primary" style="float:right; font-size: 80%; position: relative; top:-65px">+</button></a>

	<div class="card shadow-sm skill_details_table mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-success">
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Skill</th>
                        <th>Version</th>
                        <th>Competency Level</th>
                        <th>Documents</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($employee_skill) > 0)
                  @foreach($employee_skill as $key => $value)
                <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $value->skill }}</td>
                <td>{{ $value->version }}</td>
                <td>{{ $value->lookup_code }}</td>
                <td class="text-center">
                @if(!empty($value->files))
              @php $bk_file = url('/uploads/file_uploads/' . $value->files); @endphp
              <a download href="{{ $bk_file }}">
              <img height="20" width="20" src="{{ url('/images/download.png') }}" alt="Download">
              </a>
              @endif
                </td>
                <td>
                <button class="btn btn-sm btn-info edit_details" data-form="skill_form" id="{{ $value->id }}">
                <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger delete_details" data-form="skill_form" data-formid="6" id="{{ $value->id }}">
                <i class="bi bi-trash"></i>
                </button>
                </td>
                </tr>
            @endforeach
            @else
      <tr>
        <td colspan="6" class="text-center">No Records Found</td>
      </tr>
      @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
		<div class="card shadow-sm skill_details_form" style="margin-top:7px; display:none;">
    <div class="card-header text-white bg-primary">
        <h6 class="mb-0">Add Skill Details</h6>
    </div>
    <div class="card-body">
        <form id="skill_form" data-parsley-validate>
            <input type="hidden" name="edit_id" id="edit_id" value="{{ $edit_id }}"/>
            <input type="hidden" name="row_id" class="skill_row" id="row_id" value=""/>
            <input type="hidden" name="form_name" id="form_name" value="skill_details"/>

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="skill" class="form-label"><span class="req">*</span> Skill:</label>
                    <input type="text" class="form-control" name="skill" id="skill" required>
                </div>

                <div class="col-md-4">
                    <label for="version" class="form-label"><span class="req">*</span> Version:</label>
                    <input type="text" class="form-control" name="version" id="version" required>
                </div>

                <div class="col-md-4">
                    <label for="competency_level" class="form-label"><span class="req">*</span> Competency Level:</label>
                    <div class="d-flex align-items-center">
                        <select name="competency_level" id="competency_level" class="form-select select2 me-2" required>
                            {!! $competency_level !!}
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">File:</label>
                    <div class="d-flex align-items-center">
                        <input type="file" name="skl_file" class="form-control skill_file_upload me-2" id="file">
                        <label for="file-upload" class="custom-file-upload skill_choose_file">
                            <i class="fa fa-cloud-upload"></i>&nbsp;Upload
                        </label>
                        <span class="s_name ms-2"></span>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <button type="button" class="btn btn-success btn_save px-4" data-form="6" id="save">Save</button>
                <button type="button" class="btn btn-secondary skill_form_show px-4">Cancel</button>
            </div>
        </form>
		
		
    </div>
</div>
        </div>

	<!-- TRAINING and certificate -->

			<div class="tab-pane fade" id="Section8" role="tabpanel">
          <div class="panel panel-info training_detail_table">

<div class="card shadow mb-4 training_detail_table">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Training and Certification</h5>
        <a class="btn btn-sm btn-light show_form_btn training_details" style="cursor: pointer;">
            <i class="bi bi-plus-circle"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Course Name</th>
                        <th>Certificate Name</th>
                        <th>Certificate Level</th>
                        <th>Duration</th>
                        <th>Issued Date</th>
                        <th>Documents</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($employee_training) > 0)
                  @foreach($employee_training as $key => $value)
                <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $value->course_name }}</td>
                <td>{{ $value->certificate_name }}</td>
                <td>{{ $value->lookup_code }}</td>
                <td>{{ $value->course_offered_by }}</td>
                <td>{{ $value->course_duration }}</td>
                <td class="text-center">
                @if(!empty($value->files))
              <a download href="{{ url('/uploads/file_uploads/' . $value->files) }}">
              <img height="20" width="20" src="{{ url('/images/download.png') }}">
              </a>
              @endif
                </td>
                <td>
                <button class="btn btn-sm btn-primary edit_details" data-form="training_form" id="{{ $value->id }}"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-danger delete_details" data-form="training_form" data-formid="7" id="{{ $value->id }}"><i class="bi bi-trash"></i></button>
                </td>
                </tr>
            @endforeach
            @else
      <tr>
        <td colspan="8" class="text-center">No Records Found</td>
      </tr>
      @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
 </div>
                    
              <div class="panel panel-info training_detail_form" style="margin-top:7px; display:none; ">
						<h4 class="mb-4 text-primary">Training and Certification Details</h4>
        <form id="training_form" data-parsley-validate>
            <input type="hidden" name="edit_id" id="edit_id" value="{{ $edit_id }}">
            <input type="hidden" name="row_id" class="training_row" id="row_id" value="">
            <input type="hidden" name="form_name" id="form_name" value="training_details">

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="course_name" class="form-label">* Course Name</label>
                        <input type="text" class="form-control" id="course_name" name="course_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="certificate_name" class="form-label">* Certificate Name</label>
                        <input type="text" class="form-control" id="certificate_name" name="certificate_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="certificate_level" class="form-label">* Certificate Level</label>
                        <div class="d-flex align-items-center">
                            <select name="certificate_level" id="certificate_level" class="form-select me-2 select2" required>
                                {!! $certificate_level !!}
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload File</label>
                        <input class="form-control" type="file" name="train_file" id="file">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="course_offered_by" class="form-label">* Course Duration (months)</label>
                        <input type="text" class="form-control" id="course_offered_by" name="course_offered_by" required>
                    </div>
                    <div class="mb-3">
                        <label for="course_duration" class="form-label">* Issued Date</label>
                        <input type="text" class="form-control from_date" id="course_duration" name="course_duration" required readonly>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="button" class="btn btn-success btn_save px-4" data-form="7" id="save">Save</button>
                <button type="button" class="btn btn-secondary training_form_show px-4">Cancel</button>
            </div>
        </form>
                    </div>

                </div>

	  <!-- VISA and migration -->
	
	
				        <div class="tab-pane fade" id="Section9" role="tabpanel">
         
							<h4 class="text-primary mb-3">Visa and Immigration</h4>
			
											<a class="show_form_btn visa_details">  <button class="btn btn-primary" style="float:right; font-size: 80%; position: relative; top:-65px">+</button></a>	
							
							
                    <div class="panel panel-info visa_detail_table">

      <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm mb-0">
          <thead class="table-success">
            <tr>
              <th style="width: 50px;">No</th>
              <th>Passport Number</th>
              <th>Passport Issue Date</th>
              <th>Passport Expiry Date</th>
              <th>Visa Number</th>
              <th>Visa Country</th>
              <th>Visa Issue Date</th>
              <th>Visa Expiry Date</th>
              <th>Documents</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @if(count($employee_visa) > 0)
            @foreach($employee_visa as $key => $value)
            <tr>
            <td>{{++$key}}</td>
            <td>{{$value->passport_number}}</td>
            <td>{{$value->passport_issued_date}}</td>
            <td>{{$value->passport_expiry_date}}</td>
            <td>{{$value->visa_number}}</td>
            <td>{{$value->country_name}}</td>
            <td>{{$value->visa_issued_date}}</td>
            <td>{{$value->visa_expiry_date}}</td>
            <td>
            @php $bk_file = $value->files ? url('/uploads/file_uploads/' . $value->files) : ''; @endphp
            @if($bk_file)
            <a download href="{{$bk_file}}">
            <img src="{{url('/images/download.png')}}" alt="Download" width="20" height="20">
            </a>
          @endif
            </td>
            <td>
            <button class="btn btn-sm btn-primary edit_details" data-form="visa_form" id="{{$value->id}}">
            <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-danger delete_details" data-form="visa_form" data-formid="8" id="{{$value->id}}">
            <i class="bi bi-trash"></i>
            </button>
            </td>
            </tr>
          @endforeach
        @else
      <tr>
      <td colspan="10" class="text-center">No Records Found</td>
      </tr>
    @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

                    <div class="panel panel-info visa_detail_form" style="margin-top:7px; display:none; ">
                          <form id="visa_form" data-parsley-validate >
                            <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
                            <input type="hidden" name="row_id" class="visa_row" id="row_id"  value=""/>
                            <input type="hidden" name="form_name" id="form_name" value="visa_details"/>
                                <div class="row" style="padding-top:35px;">
                                   <div class="col-md-12" >
                                      <div class="row">
                                          
                                        <div class="col-md-6">
                                            <div class="mb-3 row">
                                                <label class="col-lg-5 col-md-5 col-form-label"><span style="color:red;">*</span>Passport Number :</label>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control" name="passport_number" id="v_passport_number" required="" >
                                                </div>
                                            </div>
                                              
                                            <div class="mb-3 row">
                                              <label class="col-lg-5 col-md-5 " >Passport Issued Date:</label>
                                              <div class="col-md-7">
                                                        <input type="text" name="passport_issued_date" id="passport_issued_date" value="" class="form-control from_date"  />

                                              </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label class="col-lg-5 col-md-5 " >Passport Expiry Date:</label>
                                                <div class="col-md-7 ">
                                                    <input type="text"  name="passport_expiry_date"  id="v_passport_expiry_date" class="form-control from_date v_passport_expiry_date" readonly="" />

                                                </div>
                                            </div>
                                              
                                            <div class="mb-3 row">
                                                <label class="col-lg-5 col-md-5 " >Visa Type Code:</label>
                                                <div class="col-md-7 ">
                                                <input  name="visa_type_code"  id="visa_type_code" class="form-control" />
                                                    
                                                </div>
                                            </div>
                                              
                                            <div class="mb-3 row" >
                                                <label class="col-lg-5 col-md-5 col-form-label">File:</label> 
                                                <div class=" col-md-7" style="margin-top:-8%;margin-left:41.666667%;">
                                                    <input type="file" name="visa_file" class="visa_file_upload" id="file"><span class="v_name"></span>
                                                    <label for="file-upload" class="custom-file-upload visa_choose_file  ">
                                                             <i class="fa fa-cloud-upload "></i>&nbsp;File Upload
                                                    </label>
                                                </div>
                                            </div>
                                              
                                              
                                        </div>
                                          
                                        <div class="col-md-6">
                                              
                                            <div class="mb-3 row">
                                                <label class="col-lg-5 col-md-5 " >Visa Number:</label>
                                                <div class="col-md-7 ">
                                                <input type="text"  name="visa_number"  id="visa_number" class="form-control" />
                                                   
                                                </div>
                                            </div>
                                              
                                              
                                            <div class="mb-3 row">
                                                <label class="col-lg-5 col-md-5 " >Visa Country:</label>
                                                <div class="col-md-6 ">
                                                <select  type="text"  name="visa_country"  id="visa_country" class="select2 form-control" >
                                                   {!! $visa_country !!}
                                                </select>
                                                   
                                                </div>
                                            </div>
                                              
                                            <div class="mb-3 row">
                                              <label class="col-lg-5 col-md-5 " >Visa Issued Date:</label>
                                              <div class="col-md-7 ">
                                              <input type="text"  name="visa_issued_date"  id="visa_issued_date" class="form-control from_date" />

                                              </div>
                                          </div>
                                              
                                            <div class="mb-3 row">
                                              <label class="col-lg-5 col-md-5 " >Visa Expiry Date :</label>
                                              <div class="col-md-7 ">
                                        
                                              <input type="text"  name="visa_expiry_date"  id="visa_expiry_date" class="form-control from_date" />

                                              </div>
                                          </div>
                                          </div>
                                      </div>
                                      </div>
                                    
                                   <div class="mb-3 text-center">
                          <button  type="button" class="btn btn-success btn_save px-4 me-2" data-form="8" id="save">Save</button> 
                          <button type="button"  class="btn btn-secondary visa_form_show px-4" >Cancel</button>
                      </div>

                                 </div>
                    </form>
                    </div>

        </div>
    </div>

	
<div id="myModalImage" class="imageModal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>
	
 
@endsection
@push('scripts')

<script>

 /**************** after save official form only remaining all tabs enabel start ***********/
	
$(document).ready(function () {
    var tabid = "{{ $tab_id }}";

    // Activate the tab if provided
    if (tabid !== "") {
        $('.' + tabid).click();
    }

    var edit_id = $("#edit_id").val();

    if (edit_id === "") {
        // Intercept Bootstrap tab change
        const tabTriggerEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabTriggerEls.forEach(function (tab) {
            if (!tab.classList.contains('active')) {
                tab.addEventListener('show.bs.tab', function (e) {
                    // Prevent tab switch
                    e.preventDefault();
                    showCustomAlert('Please Save Official Details First', 'warning');
                });
            }
        });
    }
});


        var edit_id = $("#edit_id").val();
        if(edit_id != ''){
            $('#employee_number').attr('readonly',true);
             <?php if($save_button==0) { ?>
            $('.save_offical_hide').hide();
            <?php }else{ ?>
                 $('.save_offical_hide').show();
            <?php } ?>
		}
        
    // zone field mantory based on marketing group type
       $(document).ready(function () {
    
            $("#group_type").change(function () {
                var selectedGroupType = $(this).val();
                if (selectedGroupType === "14") {
                    // Make the Zone field mandatory
                    $("#zone_id").prop("required", true);
                } else {
                    $("#zone_id").prop("required", false);
                }
            });
    
            $("#group_type").trigger("change");
        });
    
    // end

	
    $(document).ready(function()
        {
			 var employee="{{\Session::get('id')}}";
            var depart="{{\Session::get('department')}}";
           var dept_id="{{\Session::get('dept_id')}}";
		  var dept_id=JSON.parse(dept_id.replace(/&quot;/g,'"'));
        if(employee=="1" || dept_id=="HR" ||dept_id=="Human Resources" || dept_id=="15" || dept_id=="26" || dept_id=="27" || dept_id=="28" || dept_id=="33" || dept_id=="29" || dept_id=="30" || dept_id=="31" || dept_id=="32" || dept_id=="96"){
            $('.hr_show').show();
        }else{

        }
            $('.pan').hide();
            $('.ifscpan').hide();
            $('select').on('select2:select', function(evt) {
           $('#competency_level').parsley().validate();
                  
                  });

         $(document).on('change','#marital_status',function(){
          var marital_status=$(this).select2('val');
          if(marital_status=="2"){
            $('.single_div').css("display","block");
            $('.single').attr("required","true");
           
          }
          else{
            $('.single_div').css("display","none");
            $('.single').removeAttr("required","false");
          
            
          }
        });

	<?php if($employee_personal[0]->marital_status!=0) { ?>
	  $('#marital_status').trigger('change');
	  <?php } ?>


            /**************** file name display in form start ***********/
    
            $(document).on('change','.bank_file_upload',function(){
              
                var file = $(this).val();
                var file_name = $('.bank_file_upload')[0].files[0].name;
                $('.b_name').html(file_name);
            
            });
           
                /**************** file name display in form end ***********/
                   /**************** file name display in form start ***********/
    
            $(document).on('change','.education_file_upload',function(){
              
              
                var file = $(this).val();
                var file_name = $('.education_file_upload')[0].files[0].name;
                $('.e_name').html(file_name);
            
            });
           
                /**************** file name display in form end ***********/
                   /**************** file name display in form start ***********/
    
            $(document).on('change','.exp_file_upload',function(){
              
              
                var file = $(this).val();
                var file_name = $('.exp_file_upload')[0].files[0].name;
                $('.ex_name').html(file_name);
     
            });
           
                /**************** file name display in form end ***********/
                   /**************** file name display in form start ***********/
    
            $(document).on('change','.skill_file_upload',function(){
              
              
                var file = $(this).val();
                var file_name = $('.skill_file_upload')[0].files[0].name;
                $('.s_name').html(file_name);
   
            });
           
                /**************** file name display in form end ***********/
                   /**************** file name display in form start ***********/
    
            $(document).on('change','.train_file_upload',function(){
              
              
                var file = $(this).val();
                var file_name = $('.train_file_upload')[0].files[0].name;
                $('.t_name').html(file_name);
       
            });
           
                /**************** file name display in form end ***********/
                   /**************** file name display in form start ***********/
    
            $(document).on('change','.visa_file_upload',function(){
              
              
                var file = $(this).val();
                var file_name = $('.visa_file_upload')[0].files[0].name;
                $('.v_name').html(file_name);
     
            });
           
                /**************** file name display in form end ***********/
				 /**************** email validation start ***********/
 
		var test=0;
		$(document).on('change', '.email,.personal_mail', function ()
     {
                  var currentElement = $(this).val();
		  var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
 
    if (expr.test(currentElement)) {
      
    }
    else 
    {
        $(this).val('');
        showCustomAlert("Enter valid e-mail address","warning");
		
    }

	});

/** based on exerience from and to date calculate nof days start **/
  $(document).on('change','#ex_from_date,#ex_to_date',function()
        {
          var dateFrom =$('#ex_from_date').val();
          var dateTo = $('#ex_to_date').val();
        
            if(dateFrom!='' && dateTo!=''){
              $("#experience_type").parsley().destroy(); 
            var url="{{ URL::to('getexperience') }}?from_date="+dateFrom+"&to_date="+dateTo;
               
               $.get(url, function(data)
                {
                    $('#experience_type').val(data);
            });
        }
    });
/** based on exerience from and to date calculate nof days end **/
/** based on passport from date calculate to date start **/
  $(document).on('change','#passport_issued_date',function()
        {
              var dateFrom =$('#passport_issued_date').val();
         
        
            if(dateFrom!=''){
             
            var url="{{ URL::to('passport') }}?from_date="+dateFrom;
               
               $.get(url, function(data)
                {
                    $('#v_passport_expiry_date').val(data);
            });
        }
    });
/** based on passport from date calculate to date end **/
 /**************** view function for all tab form start ***********/
           
            function view_details(form_name,data)
            {
               
                var form_show = [];
                var table_show = [];
                
                form_show.push({salary_form:"bank_details_form",education_form:"education_details_form",experience_form:"experience_details_form",skill_form:"skill_details_form",training_form:"training_detail_form",visa_form:"visa_detail_form"});
                table_show.push({bank_details_form:"bank_details_table",education_details_form:"education_details_table",experience_details_form:"experience_details_table",skill_details_form:"skill_details_table",training_detail_form:"training_detail_table",visa_detail_form:"visa_detail_table"});
                if(form_name == 'salary_form')
                {                   
                    $('#pay_frequency').select2('val',[data['pay_frequency']]);
                    $('#salary').val(data['salary']);
                    $('#bank_name').val(data['bank_name']);
                    $('#branch_name').val(data['branch_name']);
                    $('#ifsc_code').val(data['ifsc_code']);
                    $('#account_holder_name').val(data['account_holder_name']);
                    $('#account_type').select2('val',[data['account_type']]);
                    $('#account_number').val(data['account_number']);
                    $('.salary_row').val(data['id']);
                    
                }
                else if(form_name == 'education_form')
                {
                    
                    $('#education_level').select2('val',[data['education_level']]);
                    $('#school_name').val(data['school_name']);
                    $('#school_board').val(data['school_board']);
                     var myDate = data['from_date'];
                         if(myDate!='0000-00-00')
                         {
                        var parsedDate = $.datepicker.parseDate("yy-mm-dd", myDate);
                        $('#ed_from_date').val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));
                         }
                         else {
                           $('#ed_from_date').val('');
                         }
                 var myDate1 = data['to_date'];
                         if(myDate1!='0000-00-00')
                         {
                        var parsedDate1 = $.datepicker.parseDate("yy-mm-dd", myDate1);
                        $('#ed_to_date').val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate1));
                         }
                         else {
                           $('#ed_to_date').val('');
                         }

                    $('#percentage').val(data['percentage']);
                    $('#institution_name').select2('val',[data['institution_name']]);
                    $('#university_name').val(data['university_name']);
                    $('#course').val(data['course']);
                    $('#other').val(data['other']);
                    $('.education_row').val(data['id']);
                }
                else if(form_name == 'experience_form')
                {                   
                    $('#organization_name').val(data['organization_name']);
                    $('#organization_website').val(data['organization_website']);
                    $('#designation').val(data['designation']);
                    $('#ctc').val(data['ctc']);
                    
                       var myDate = data['from_date'];
                         if(myDate!='0000-00-00')
                         {
                        var parsedDate = $.datepicker.parseDate("yy-mm-dd", myDate);
                        $('#ex_from_date').val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));
                         }
                         else {
                           $('#ex_from_date').val('');
                         }
                 var myDate1 = data['to_date'];
                         if(myDate1!='0000-00-00')
                         {
                        var parsedDate1 = $.datepicker.parseDate("yy-mm-dd", myDate1);
                        $('#ex_to_date').val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate1));
                         }
                         else {
                           $('#ex_to_date').val('');
                         }
               
                    $('#experience_type').val(data['experience_type']);
                    $('#reason_leaving').val(data['reason_leaving']);
                    $('.experience_row').val(data['id']);
                }
                else if(form_name == 'skill_form')
                {
                   

                    $('#skill').val(data['skill']);
                    $('#version').val(data['version']);
                    $('#competency_level').select2('val',[data['competency_level']]);
                                        $('.skill_row').val(data['id']);
                    
                }
                else if(form_name == 'training_form')
                {
                   
                    $('#course_name').val(data['course_name']);
                    $('#certificate_name').val(data['certificate_name']);
                    $('#certificate_level').select2('val',[data['certificate_level']]);
                    $('#course_offered_by').val(data['course_offered_by']);

                       var myDate = data['course_duration'];
                         if(myDate!='0000-00-00')
                         {
                        var parsedDate = $.datepicker.parseDate("yy-mm-dd", myDate);
                        $('#course_duration').val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));
                         }
                         else {
                           $('#course_duration').val('');
                         }
                    $('.training_row').val(data['id']);
                    
                }
                else if(form_name == 'visa_form')
                {
                
                    $('#v_passport_number').val(data['passport_number']);
                   
                      var myDate = data['passport_issued_date'];
                    
                         if(myDate!='0000-00-00' && myDate!=null)
                         {
                             
                        var parsedDate = $.datepicker.parseDate("yy-mm-dd", myDate);
                        $('#passport_issued_date').val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));
                         }
                         else {
                           $('#passport_issued_date').val('');
                         }
                          
                 var myDate1 = data['passport_expiry_date'];
                
                         if(myDate1!='0000-00-00' && myDate1!=null)
                         {
                      
                        var parsedDate1 = $.datepicker.parseDate("yy-mm-dd", myDate1);
                        $('#v_passport_expiry_date').val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate1));
                         }
                         else {
                           $('#v_passport_expiry_date').val('');
                         }
                          var myDate2 = data['visa_issued_date'];
                         if(myDate2!='0000-00-00' && myDate2!=null)
                         {
                        var parsedDate2 = $.datepicker.parseDate("yy-mm-dd", myDate2);
                        $('#visa_issued_date').val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate2));
                         }
                         else {
                           $('#visa_issued_date').val('');
                         }
                            var myDate3 = data['visa_expiry_date'];
                         if(myDate3!='0000-00-00' && myDate3!=null)
                         {
                        var parsedDate3 = $.datepicker.parseDate("yy-mm-dd", myDate3);
                        $('#visa_expiry_date').val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate3));
                         }
                         else {
                           $('#visa_expiry_date').val('');
                         }
                         
                    $('#visa_type_code').val(data['visa_type_code']);
                    $('#visa_number').val(data['visa_number']);
                    $('#visa_country').val(data['visa_country']).change();
                    $('.visa_row').val(data['id']);
                    
                }
                    
                $('.'+form_show[0][form_name]).show();
                $('.'+table_show[0][form_show[0][form_name]]).hide();
                
            }
			 /**************** view function for all tab form end ***********/
            /****** edit function start ***/
            $(document).on('click','.edit_details',function(){
               var edit_id = $(this).attr('id');
               var form_name = $(this).attr('data-form');
               var url="{{ URL::to('fetch_information') }}/"+form_name+"/"+edit_id;
               
               $.get(url, function(data,status)
                {
                    
                    if(data[0] != "")
                    {
                      
                        view_details(form_name,data[0]);
                    }
                    else if(data == "")
                    {
                        
                    }
                });
            });
                 /****** edit function end ***/
            
            /****** delete function start  ***/
            $(document).on('click','.delete_details',function()
            {
                var edit_id = $(this).attr('id');
                var form_name = $(this).attr('data-form');
                var form_id = $(this).attr('data-formid');
                var tab_id = Number(form_id) + Number(1);  
                var editid = $("#edit_id").val();
                var dirurl = "{{ URL::to('editprofile') }}/"+editid+'/'+tab_id;
                
                
                var url="{{ URL::to('delete_information') }}/"+form_name+"/"+edit_id;
                $.get(url, function(data,status)
                {
                    if(data[0] != "")
                    {
                    showCustomAlert('Deleted Successfully','SUCCESS',);
                      window.location.replace(dirurl);  
                    }
                    else if(data == "")
                    {
                        
                    }
                });
               
            });
                  /****** delete function start  ***/
                   /****** image display function start  ***/
            function readURL(input) {

            if (input.files && input.files[0]) {
              var reader = new FileReader();
                $('#myImg').attr('src','');
              reader.onload = function(e) 
            {
                $('#myImg').attr('src', e.target.result);
              }

              reader.readAsDataURL(input.files[0]);
            }
          }
 /****** image display function end  ***/
           


               /**************** email validation start ***********/
     function ValidateEmail(email) {
            var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            return expr.test(email);
        };
        $(document).on('click','#save_off',function()
        {

        });

        $(document).on('click','#save',function()
        {

        });
    /**************** email validation end ***********/
    
                
 /**************** email duplicate check start***********/
    
            var dup_chk = true;
           $(document).on('change','#email',function()
        {
                var mail_id = $("#email").val();
                var edit_id = $("#edit_id").val();

                $.ajax({
                    cache: false,
                    url: "{{ URL::to('employee/checkusermail') }}", //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async : false,
                    data: {mail_id : mail_id,edit_id : edit_id},
                    success: function(response)
                    {
                        if(response == 1)
                        {
                          showCustomAlert('E-mail already exists','warning');

                            dup_chk = true;

                        }
                        else if(response == 0)
                        {
                            var html ="";

                            dup_chk = true;

                        }

                    },
                    error: function(xhr, resp, text)
                    {
                        console.log(xhr, resp, text);
                    }
                });
                });
                /**************** email duplicate check end***********/ 
                 /**************** employee name duplicate check start***********/
            var dup_chk1 = true;
            function duplicate_validate1()
            {
                var employee_id = $("#employee_number").val();
                var edit_id = $("#edit_id").val();

                $.ajax({
                    cache: false,
                    url: "{{ URL::to('employee/checkemployee')}}", //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async : false,
                    data: {employee_id : employee_id,edit_id : edit_id},
                    success: function(response)
                    {
                        if(response == 1)
                        {
                            $('.dup_name1').html('Employee Code:'+employee_id+' Already Exists In the Table');
                            $('.dup_name1').show();
                            $('#employee_number').val('');
                            dup_chk1 = false;
                        }
                        else if(response == 0)
                        {
                            var html ="";
                                $('.dup_name1').hide();
                            dup_chk1 = true;

                        }
                    },
                    error: function(xhr, resp, text)
                    {
                        console.log(xhr, resp, text);
                    }
                });
                }

  /**************** employee name duplicate check end***********/
  /** employe keyup hide dup div start **/
  $(document).on('keypress', '#employee_number', function(ev){
        $('.dup_name1').hide();
  });
  
           
  /** employe keyup hide dup div end **/
              /**************** moblie number validation start ***********/
        $(document).on('keypress', '.percentage,.work_telephone_number,#no_of_children,#esi_no,#uan_no,#c_pincode,#p_pincode,#alternative_telephone_number,#account_number,#aadhar_number,#mobile_no,#father_aadhar_number,#mother_aadhar_number,#spouce_aadhar_number', function(ev){
            var regex = new RegExp("^[0-9.]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) {
                        return true;
                    }
                    ev.preventDefault();
                    return false;
        });
            /**************** moblie number validation start ***********/
        $(document).on('keypress', '.work_telephone_number,#no_of_children,#esi_no,#uan_no,#c_pincode,#p_pincode,#alternative_telephone_number,#account_number,#aadhar_number,#mobile_no,#father_aadhar_number,#mother_aadhar_number,#spouce_aadhar_number', function(ev){
            var regex = new RegExp("^[0-9.]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) {
                        return true;
                    }
                    ev.preventDefault();
                    return false;
        });
       

     $(document).on('keypress', '.mobile_no,#c_pincode,#version,#course_offered_by', function(ev){
        var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
    });

   
    /**************** moblie number validation end ***********/
      /**************** image display function in form start ***********/
        function readURL(input) 
        {
            if (input.files && input.files[0]) 
            {
                var reader = new FileReader();
                reader.onload = function (e) 
                {
                    $('#myImg').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
		  /**************** image display function in form end ***********/
		    /**************** image display in form start ***********/

    $(".file_upload").change(function(){
        readURL(this);
    });
  /**************** image display in form  end***********/
  /**************** change date format to save in php start***********/
        function change_date1()
        {
            $(".from_date").each(function() {
                var myDate = $(this).val();
            
var parsedDate = $.datepicker.parseDate("{{\Session::get('j_date_format')}}", myDate);
$(this).val($.datepicker.formatDate("yy-mm-dd", parsedDate));
                
            });
               $(".ex_to_date").each(function() {
               var myDate = $(this).val();
var parsedDate = $.datepicker.parseDate("{{\Session::get('j_date_format')}}", myDate);
$(this).val($.datepicker.formatDate("yy-mm-dd", parsedDate));
                
            });
               $(".ex_from_date").each(function() {
              var myDate = $(this).val();
var parsedDate = $.datepicker.parseDate("{{\Session::get('j_date_format')}}", myDate);
$(this).val($.datepicker.formatDate("yy-mm-dd", parsedDate));
                
            });
                 $(".ed_to_date").each(function() {
               var myDate = $(this).val();
var parsedDate = $.datepicker.parseDate("{{\Session::get('j_date_format')}}", myDate);
$(this).val($.datepicker.formatDate("yy-mm-dd", parsedDate));
                
            });
               $(".ed_from_date").each(function() {
              var myDate = $(this).val();
var parsedDate = $.datepicker.parseDate("{{\Session::get('j_date_format')}}", myDate);
$(this).val($.datepicker.formatDate("yy-mm-dd", parsedDate));
                
            });
        }
        function change_dates()
        {
            
            $(".from_date").each(function() 
            {

                   var myDate = $.trim($(this).val());
               
 if(myDate!='0000-00-00' && myDate!='')
 {
	  
var parsedDate = $.datepicker.parseDate("yy-mm-dd", myDate);
$(this).val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));
 }
 else {
   $(this).val('');
 }
            });
               $(".ex_to_date").each(function() 
            {
                  var myDate = $.trim($(this).val());
 if(myDate!='0000-00-00')
 {
var parsedDate = $.datepicker.parseDate("yy-mm-dd", myDate);
$(this).val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));
 }
 else {
   $(this).val('');
 }
            });
               $(".ex_from_date").each(function() 
            {
                   var myDate = $.trim($(this).val());
 if(myDate!='0000-00-00')
 {
var parsedDate = $.datepicker.parseDate("yy-mm-dd", myDate);
$(this).val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));
 }
 else {
   $(this).val('');
 }
            });
              $(".ed_to_date").each(function() 
            {
                  var myDate = $.trim($(this).val());
 if(myDate!='0000-00-00')
 {
var parsedDate = $.datepicker.parseDate("yy-mm-dd", myDate);
$(this).val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));
 }
 else {
   $(this).val('');
 }
            });
               $(".ed_from_date").each(function() 
            {
                   var myDate = $.trim($(this).val());
 if(myDate!='0000-00-00')
 {
var parsedDate = $.datepicker.parseDate("yy-mm-dd", myDate);
$(this).val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));
 }
 else {
   $(this).val('');
 }
            });
        }
		  /**************** change date format to save in php end***********/
        var edit_id = $('.edit_id').val() ;
        if(edit_id != '')
        {
            change_dates();
        }
          /**************** save function for all form in one function  start***********/
         $(document).on('click','#save',function()
        {
            
            var from_array = ["official_form","personal_form","contact_form","salary_form","education_form","experience_form","skill_form","training_form","visa_form"];
            var tab_array = ["1","2","3","4","5","6","7","8","9"];
            var form_id = $(this).attr('data-form');
            var edit_id = $("#edit_id").val();
            
            validationrule(from_array[form_id]);
            var form = $('#'+from_array[form_id]);
            var tab_id = Number(form_id) + Number(1);  
            form.parsley().validate();
            
            if (form.parsley().isValid())
            {
 
                
                var url                     =   "{{url('employee/save')}}";
                var red_url                 =   "{{url('createemployee')}}";
               
                
                duplicate_validate1();
                 /**************** email validation start ***********/
                  var mail='';
                 if(form_id==1){
                    mail = $('.email_vali').attr('id');
                    if(mail == 1){
                    $('.email_vali').show();
                    }else{
                        $('.email_vali').hide();
                    }
                }
                 /**************** email validation end ***********/
               
                if(mail != 1 && dup_chk1 && check_ifsc) 
                {
                    change_date1();
                     var btnval                  =   $(this).val();
                var formdata                =   $('#'+from_array[form_id]).serialize();
                      $('.ajaxLoading').show().delay(2000);
                var form_data = new FormData(document.getElementById(from_array[form_id]));
          
                $.ajax({
                  url: "{{ url('employee/save')}}",
                  type: "POST",
                  data: form_data,
                  enctype: 'multipart/form-data',
                  processData: false,  // tell jQuery not to process the data
                  contentType: false,   // tell jQuery not to set contentType
                  async:true,
                  xhr: function(){
                      var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                        percent = Math.ceil(position / total * 100);
                                }
                                        //update progressbar

                                }, true);
                        }
                        return xhr;

                }
                }).done(function(data,status)
                {
                    
                    if(data['status'] == 1)
                    {
                        $('.ajaxLoading').show(0).delay(500).hide(0);
                        showCustomAlert('Employee Details Saved Successfully','success');
                        var url = "{{ URL::to('editprofile') }}/"+edit_id+'/'+tab_id;
                        setTimeout(function()
                        {
                            window.location.replace(url);
                        }, 1000);
                    }
                    else
                    {
                        $('.ajaxLoading').show(0).delay(500).hide(0);
                        $(".alert-success").hide();
                        $(".alert-danger").fadeIn(800);

                    }
                }).fail(function(data,status)
                {
                    $('.ajaxLoading').show(0).delay(500).hide(0);


                });
            }
    }
        });


/**************** state load based on country start***********/
$(document).on('change', '#p_country', function () {

    var country_id = $('#p_country').val();

    if (country_id !== '') {

        $.ajax({
            url: "{{ URL::to('jcomboformlogin') }}",
            type: "GET",
            dataType: "json",   // IMPORTANT
            data: {
                table: "m_states_t:state_id:state_name",
                parent: "country_id=" + country_id,
                order_by: "state_name"
            },
            success: function (data) {
                // data is array of {val: ..., option_name: ...}
                var $state = $("#p_state");
                $state.empty().append('<option value="">-- Select State --</option>');

                $.each(data, function (i, row) {
                    $state.append(
                        '<option value="' + row.val + '">' + row.option_name + '</option>'
                    );
                });

                // clear city dropdown
                $("#p_city").empty()
                    .append('<option value="">-- Select City --</option>');

                // if using select2
                $state.select2();
                $("#p_city").select2();

                // if same address is checked, mirror country
                if ($("#same_address").is(':checked')) {
                    $('#c_country').val(country_id).trigger('change');
                }
            },
            error: function (xhr, status, error) {
                console.log("Error loading states:", status, error);
                console.log("Server response:", xhr.responseText);
            }
        });
    }
});


$(document).on('change', '#p_state', function () {
    var state_id = $('#p_state').val();

    if (state_id !== '') {
        $.ajax({
            url: "{{ URL::to('jcomboformlogin') }}",
            type: "GET",
            dataType: "json",
            data: {
                table: "m_cities_t:city_id:city_name",
                parent: "state_id=" + state_id,
                order_by: "city_name"
            },
            success: function (data) {
                var $city = $("#p_city");
                $city.empty().append('<option value="">-- Select City --</option>');

                $.each(data, function (i, row) {
                    $city.append(
                        '<option value="' + row.val + '">' + row.option_name + '</option>'
                    );
                });

                $city.select2();

                if ($("#same_address").is(':checked')) {
                    $('#c_state').val(state_id).trigger('change');
                }
            }
        });
    }
});


// CURRENT COUNTRY → CURRENT STATE
$(document).on('change', '#c_country', function () {

    var country_id = $('#c_country').val();

    if (country_id !== '') {

        var selected_state = $("#same_address").is(':checked') ? $('#p_state').val() : '';

        $.ajax({
            url: "{{ URL::to('jcomboformlogin') }}",
            type: "GET",
            dataType: "json",  // <<< JSON
            data: {
                table: "m_states_t:state_id:state_name",
                parent: "country_id=" + country_id,
                order_by: "state_name"
            },
            success: function (data) {

                var $cState = $("#c_state");
                $cState.empty().append('<option value="">-- Select State --</option>');

                $.each(data, function (i, row) {
                    // val / option_name from your Preview
                    $cState.append(
                        '<option value="' + row.val + '">' + row.option_name + '</option>'
                    );
                });

                // reset city
                var $cCity = $("#c_city");
                $cCity.empty().append('<option value="">-- Select City --</option>');

                if (selected_state) {
                    $cState.val(selected_state).trigger('change');
                }

                $cState.select2();
                $cCity.select2();
            },
            error: function (xhr, status, error) {
                console.log("Error loading states:", status, error);
                console.log("Response:", xhr.responseText);
            }
        });
    }
});


// CURRENT STATE → CURRENT CITY
$(document).on('change', '#c_state', function () {

    var state_id = $('#c_state').val();

    if (state_id !== '') {

        var selected_city = $("#same_address").is(':checked') ? $('#p_city').val() : '';

        $.ajax({
            url: "{{ URL::to('jcomboformlogin') }}",
            type: "GET",
            dataType: "json",  // <<< JSON
            data: {
                table: "m_cities_t:city_id:city_name",
                parent: "state_id=" + state_id,
                order_by: "city_name"
            },
            success: function (data) {

                var $cCity = $("#c_city");
                $cCity.empty().append('<option value="">-- Select City --</option>');

                $.each(data, function (i, row) {
                    $cCity.append(
                        '<option value="' + row.val + '">' + row.option_name + '</option>'
                    );
                });

                if (selected_city) {
                    $cCity.val(selected_city).trigger('change');
                }

                $cCity.select2();
            },
            error: function (xhr, status, error) {
                console.log("Error loading cities:", status, error);
                console.log("Response:", xhr.responseText);
            }
        });
    }
});




$(document).on('change', '#same_address', function () {
    if ($(this).is(':checked')) {

        // copy text fields
        $('#c_street').val($('#p_street').val());
        $('#c_address').val($('#p_address').val());
        $('#c_flat_no').val($('#p_flat_no').val());
        $('#c_locality').val($('#p_locality').val());
        $('#c_pincode').val($('#p_pincode').val());

        // copy dropdowns (country triggers full chain)
        $('#c_country').val($('#p_country').val()).trigger('change');
        // state and city are handled in ajax success using selected_state/selected_city
    }
});


    /**************** datepicker set start***********/
    var data = "{{\Session::get('j_date_format')}}";
    $('.from_date').datepicker({
        changeMonth: true,
        dateFormat: data,
        changeYear: true,
        yearRange: "1947:+2100"
    });
    $('.ex_from_date').datepicker({
        changeMonth: true,
        changeYear: true,

        dateFormat: data,
        minDate: null,
        maxDate: null,
        onSelect: function (selected) {

            $('.ex_to_date').datepicker("option", "minDate", $(".ex_from_date").datepicker('getDate'))
        }, onClose: function () {
            $(this).parsley().destroy();
        }
    }).attr("readonly", "readonly");

    $('.ex_to_date').datepicker({
        dateFormat: data,
        changeMonth: true,
        changeYear: true,
        minDate: null,
        maxDate: null,
    }).attr("readonly", "readonly");
    $('.ed_from_date').datepicker({
        changeMonth: true,
        changeYear: true,

        dateFormat: data,
        minDate: null,
        maxDate: null,
        onSelect: function (selected) {

            $('.ed_to_date').datepicker("option", "minDate", $(".ed_from_date").datepicker('getDate'))
        }, onClose: function () {
            $(this).parsley().destroy();
        }
    }).attr("readonly", "readonly");

    $('.ed_to_date').datepicker({
        dateFormat: data,
        changeMonth: true,
        changeYear: true,
        minDate: null,
        maxDate: null,
    }).attr("readonly", "readonly"); 
       
		    /**************** save function for all form in one function  start***********/
       //$('.ajaxLoading').show().delay(2000);
        $(document).on('click','#save_off',function()
        {
            
            var from_array = ["official_form","personal_form","contact_form","salary_form","education_form","experience_form","skill_form","training_form","visa_form"];
            var form_id = $(this).attr('data-form');   
            var form = $('#'+from_array[form_id]);
            var formid = $('.'+from_array[form_id]);
            form.parsley().validate();
            if (form.parsley().isValid())
            {
              
                
                var url                     =   "{{ url('employee/save') }}";
                var red_url                 =   "{{ url('createemployee') }}";
               
               
                duplicate_validate1();
                    /**************** email validation start ***********/
                    var mail = "";
                    if(form_id==1){
                        mail = $('.email_vali').attr('id');
                    if(mail == 1){
                    $('.email_vali').show();
                    }else{
                        $('.email_vali').hide();
                    }
                }
                 /**************** email validation end ***********/
               
                if( mail != 1 && dup_chk1)
                {
                    change_date1();
                     var btnval                  =   $(this).val();
                var formdata                =   $('#'+from_array[form_id]).serialize();
                    $('.ajaxLoading').show().delay(2000);  
                var form_data = new FormData(document.getElementById(from_array[form_id]));
               
                $.ajax({
                  url: "{{ url('employee/save') }}",
                  type: "POST",
                  data: form_data,
                  enctype: 'multipart/form-data',
                  processData: false,  // tell jQuery not to process the data
                  contentType: false,   // tell jQuery not to set contentType
                  async:true,
                  xhr: function(){
                      var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                        percent = Math.ceil(position / total * 100);
                                }
                                        //update progressbar

                                }, true);
                        }
                    return xhr;

                }
                }).done(function(data,status)
                {
                    
                    if(data['status'] == 1)
                    {
                         $('.ajaxLoading').show(0).delay(500).hide(0);
                        showCustomAlert('Employee Details Saved Successfully','success');
                        var urls = "{{ URL::to('editprofile') }}/"+data['id']; 
                        setTimeout(function(){
                        window.location.replace(urls);
                                               }, 1500);
                        $('.'+from_array[form_id]).trigger('click');
                        
                       
                    }
                    else
                    {
                         $('.ajaxLoading').show(0).delay(500).hide(0);
                        $(".alert-success").hide();
                        $(".alert-danger").fadeIn(800);

                    }
                }).fail(function(data,status)
                {
                       
                        $(".alert-success").hide();
                        $(".alert-danger").fadeIn(800);

                });
            }
    }
        });
$('select').on('select2:select', function(evt) {
        $(this).parsley().validate();
    });

         /**************** save function for all form in one function  end***********/
        $(".select2").select2();
        $(".select2").css('width','100%');
        
       /**************** Bank Details form show function start***********/

        $(document).on('click','.bank_details',function()
        {
            $('.bank_details_table').hide();
            $('.bank_details_form').show();
            $('.bank_details_form').find('input:text').val(''); 
            $('.salary_row').val(''); 
            $('#pay_frequency,#account_type').select2('val',['']);
            $("#salary_form").parsley().destroy(); 
            $('.b_name').html('');
            
        });

         /**************** Bank Details form show function end ***********/

        /**************** Bank Details form hide function  start***********/

    
        $(document).on('click','.bank_form_show',function()
        {
            $('.bank_details_form').hide();
            $('.bank_details_table').show();
        });
        /**************** Bank Details form hide function  end***********/



                   /**************** Numbers only function  start***********/

    $(document).on('keypress','.ctc,.salary,.no_of_children', function(ev){

            var regex = new RegExp("^[0-9.]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) {
                        return true;
                    }
                    ev.preventDefault();
                    return false;
        });

                             /**************** Numbers only function  end***********/
        
                   /**************** Education Details Form show function  start***********/
        $(document).on('click','.education_details',function()
        {
            $('.education_details_table').hide();
            $('.education_details_form').show();
            $('.education_details_form').find('input:text').val(''); 
            $('.education_row').val(''); 
            $('#education_level').select2('val',['']);
             $("#education_form").parsley().destroy(); 
             $('.e_name').html('');
        });
 /**************** Education Details Form show function  end***********/
 /**************** Education Details Form hide function  start***********/
        $(document).on('click','.education_form_show',function()
        {

            $('.education_details_form').hide();
            $('.education_details_table').show();
        });
       /**************** Education Details Form hide function  end***********/

        
             /**************** Education level change below feilds show function start***********/
        $('.specify').hide();
        $('.course').hide();
        $(document).on('change','.education_level',function()
        {
            var edu_leave = $('#education_level').select2('val');
            $("#education_level").parsley().validate();
            if(edu_leave == 3 || edu_leave == 4 || edu_leave == 5)
            {
               $('.education_level1').after('');
                $('.edu_school1').html('Institution Name');
                $('.edu_school11').attr('name','institution_name').attr('id','institution_name');
                
                $('.edu_school2').html('University Name');
                $('.edu_school12').attr('name','university_name').attr('id','university_name');
                
                $('.course').show();
                $('.specify').hide();
                $('.course').attr('required');
            }
            else if(edu_leave == 6)
            {
               $('.specify').show();
               $('.other').attr('required');
            }
            else 
            {
                $('.course').hide();
                $('.edu_school1').html('<span class="req">*</span>Name of the Institution:');
                $('.edu_school11').attr('name', 'school_name').attr('id','school_name');
                
                $('.edu_school2').html('<span class="req">*</span>Boards of Education/University :');
                $('.edu_school12').attr('name', 'school_name').attr('id','school_name');
                $('.specify').hide();
               
                $('#course').removeAttr('required');
                $('#other').removeAttr('required');
            }
        });
  /**************** Education level change below feilds show function end***********/
   
        
        /****** Experience Details show form function start ****/
        $(document).on('click','.experience_details',function()
        {
            $('.experience_details_table').hide();
            $('.experience_details_form').show();
            $('.experience_details_form').find('input:text').val('');   
            $('.experience_row').val('');  
              $("#experience_form").parsley().destroy();
              $('.ex_name').html('');
        });
                    /****** Experience Details show form function end ****/
                      /****** Experience Details hide form function start ****/
        $(document).on('click','.experience_form_show',function()
        {

            $('.experience_details_form').hide();
            $('.experience_details_table').show();
        });
  /****** Experience Details hide form function end ****/
        
          /*** Skill Details show form function start ***/
        $(document).on('click','.skill_details',function()
        {
            $('.skill_details_table').hide();
            $('.skill_details_form').show();
            $('.skill_details_form').find('input:text').val('');
            $('.competency_level').select2('val',['']);
            $('.skill_row').val('');
                    $("#skill_form").parsley().destroy(); 
                    $('.s_name').html('');
        });
                      /*** Skill Details show form function end ***/
                      /*** Skill Details hide form function start ***/
        $(document).on('click','.skill_form_show',function()
        {

            $('.skill_details_form').hide();
            $('.skill_details_table').show();
        });
                              /*** Skill Details hide form function end ***/
        
           /*** Training Details show form function start ***/
         $(document).on('click','.training_details',function()
        {
            $('.training_detail_table').hide();
            $('.training_detail_form').show();
            $('.training_detail_form').find('input:text').val('');
            $('#certificate_level').select2('val',['']);
            $('.training_row').val('');
                    $("#training_form").parsley().destroy(); 
                    $('.t_name').html('');

        });
           /*** Training Details show form function end ***/
           /*** Training Details hide form function start ***/ 
        $(document).on('click','.training_form_show',function()
        {

            $('.training_detail_form').hide();
            $('.training_detail_table').show();
        });
        /*** Training Details hide form function end ***/        
        
         /*** Training Details show form function start ***/
         $(document).on('click','.visa_details',function()
        {
            $('.visa_detail_table').hide();
            $('.visa_detail_form').show();
            $('.visa_detail_form').find('input:text').val('');
            $('#visa_country').select2('val',['']);
            $('.visa_row').val('');
             $("#visa_form").parsley().destroy(); 
             $('.v_name').html('');
        });
        /*** Training Details hide form function start ***/    
        $(document).on('click','.visa_form_show',function()
        {

            $('.visa_detail_form').hide();
            $('.visa_detail_table').show();
        });
        /*** Training Details hide form function end ***/ 
        
            /**** edit_bank_details fucntion start***/
                $(document).on('.edit_bank_details','click',function(){
                   var edit_id = $('.edit_bank_details').attr('id');           
                });
/**** edit_bank_details function end***/


            $(document).on('click','.file_choose',function(e)
            {
                $(".file_upload").trigger( "click" );
               
            });
            
            $(document).on('click','.bank_choose_file',function(e)
            {
                $(".bank_file_upload").trigger( "click" );
               
            });
             
            $(document).on('click','.education_choose_file',function(e)
            {
          
                $(".education_file_upload").trigger( "click" );
            });
             $(document).on('click','.exp_choose_file',function(e)
            {
                $(".exp_file_upload").trigger( "click" );
            });
             $(document).on('click','.skill_choose_file',function(e)
            {
                $(".skill_file_upload").trigger( "click" );
            });
             $(document).on('click','.train_choose_file',function(e)
            {
                $(".train_file_upload").trigger( "click" );
            });
             $(document).on('click','.visa_choose_file',function(e)
            {
                $(".visa_file_upload").trigger( "click" );
            });




            <?php if($edit_id == "") { ?>
           
   /* $(document).ready(function () {

    $.ajax({
        url: "{{ URL::to('jcomboformrerule') }}",
        type: "GET",
        data: {
            table: "m_job_title:job_title_id:job_title_name",
            parent: "1=1",
            order_by: "job_title_name asc"
        },
        success: function (data) {
            $("#job_title").empty().append('<option value="">-- Select Job Title --</option>');
            $.each(data, function (i, row) {
                $("#job_title").append('<option value="' + row.value + '">' + row.text + '</option>');
            });
            $("#job_title").trigger("change");
        }
    });


    $.ajax({
        url: "{{ URL::to('jcomboform') }}",
        type: "GET",
        data: {
            table: "m_position:position_id:position",
            parent: "1=1",
            order_by: "position asc"
        },
        success: function (data) {
            $("#position").empty().append('<option value="">-- Select Position --</option>');
            $.each(data, function (i, row) {
                $("#position").append('<option value="' + row.value + '">' + row.text + '</option>');
            });
            $("#position").trigger("change");
        }
    });

}); */

<?php } else { ?>

$(document).ready(function () {
    setTimeout(function () {
        $('#position').trigger('change');
    }, 200);
});

<?php } ?>

         $(document).on('change','#position',function()
            {
            
               var position= $("#position option:selected").text();
           if(position=="director"){
               $('#reporting_manager').removeAttr('required','false');
               $('.spanhide').css('display','none');
           }
           else{
                $('#reporting_manager').attr('required','true');
               $('.spanhide').css('display','');
           }
            });
         /*** for director  position reporting manager required remove end ***/


            $(document).on('click','#same_address',function()
            {
                if($(this).is(':checked'))
                {
                   $("#contact_form").parsley().destroy();
                    $('#c_address').val($('#p_address').val());
                    $('#c_street').val($('#p_street').val());
                    $('#c_flat_no').val($('#p_flat_no').val());
                    $('#c_locality').val($('#p_locality').val());
                     if($('#p_country').select2('val') !=''){
                    $('#c_country').select2('val',[$('#p_country').select2('val')]);
                   
                  }
                    $('#c_pincode').val($('#p_pincode').val());
                    $('#c_address,#c_pincode,#c_locality,#c_flat_no,#c_street').attr('readonly',true);
                    $('.country_contact').css('pointer-events','none');
                }
                else
                {
                    $('#c_country,#c_state,#c_city').select2('val',['']);
                    $('#c_address,#c_pincode,#c_locality,#c_flat_no,#c_street').val('');
                       $('#c_address,#c_pincode,#c_locality,#c_flat_no,#c_street').attr('readonly',false);
                        $('.country_contact').css('pointer-events','auto');
                }


            });
        
            /*** contact permanent address same as current details end***/
/** after clcik same as permamnent after keyup in permmanent details is enter into current details start **/
 $(document).on('change','#p_address,#p_street,#p_flat_no,#p_locality,#p_country,#p_state,#p_city,#p_pincode',function()
            {
                if($('#same_address').is(':checked'))
                {
                   
                    $('#c_address').val($('#p_address').val());
                    $('#c_street').val($('#p_street').val());
                    $('#c_flat_no').val($('#p_flat_no').val());
                    $('#c_locality').val($('#p_locality').val());
                    if($('#p_country').select2('val') !=''){
                    $('#c_country').select2('val',[$('#p_country').select2('val')]);
                    var country_id =$('#p_country').select2('val');
                    $("#c_state").jCombo("{{ URL::to('jcomboform?table=m_states_t:state_id:state_name') }}&parent=country_id="+country_id+ '&order_by=state_name asc',
                    {selected_value:""});
                    setTimeout(function()
                    { 
                        $('#c_state').select2('val',[$('#p_state').select2('val')]);
                    }, 500);
                    setTimeout(function(){ 
                    $('#c_city').select2('val',[$('#p_city').select2('val')]);
                        }, 800);
                  }
                    $('#c_pincode').val($('#p_pincode').val());
                    $('#c_address,#c_pincode,#c_locality,#c_flat_no,#c_street').attr('readonly',true);
                    $('.country_contact').css('pointer-events','none');
                }
                else
                {
					  if(edit_id == ''){
                    $('#c_country,#c_state,#c_city').select2('val',['']);
                    $('#c_address,#c_pincode,#c_locality,#c_flat_no,#c_street').val('');
                     $('#c_address,#c_pincode,#c_locality,#c_flat_no,#c_street').attr('readonly',false);
                    $('.country_contact').css('pointer-events','block');
					  }
                }


            });
/** after clcik same as permamnent after keyup in permmanent details is enter into current details end **/
            /*** Emergency Details 10 details only to add start***/
            var max_fields = 10; //maximum input boxes allowed
            var wrapper = $(".input_fields_wrap"); //Fields wrapper
            var add_button = $(".add_field_button"); //Add button ID
            var x = 1; //initlal text box count
            $(add_button).click(function (e) { //on add input button click
                var i = 0;
                $('.emer_name').each(function (index) {
                    i++;
                });
                e.preventDefault();

                if (x < max_fields) { //max input box allowed
                    $('.response_data').append('<tr>\n\
                                    <td><input type="text" name="emer_name[]" class="form-control emer_name name-' + i + '"></td>\n\
                                    <td><input type="text" name="relation[]" class="form-control relation-' + i + '"></td>\n\
                                    <td><textarea name="address[]" class="form-control address-' + i + '"></textarea></td>\n\
                                    <td><input name="mobile_no[]" type="text"  class="form-control mobile_no number-' + i + '"></td>\n\
                                    <td> <span class=" showspan"><i class="fa fa-minus remove_field"></i></span>\n\
                    </td></tr>');
                    x++; //text box increment
                }
                if(x == 10)
                {
                    showCustomAlert("Only 10 Can Add",'error');
                }


            });
            /*** Emergency Details 10 details only to add end***/
            /*** Emergency Details  add start***/
             var maxField = 5; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var fieldHTML = ''; //New input field html
        var x = 1; //Initial field counter is 1
        $(addButton).click(function () { //Once add button is clicked
            var i = 0;
            $('.lang').each(function (index) {
                i++;
            });

            if (x < maxField) { //Check maximum number of input fields

                fieldHTML = '<div class="lang_div"> \n\
                                <input type="text" name="language[]" style="width:225px;" data-provide="typeahead" autocomplete="off"  class="lang lang-' + i + ' form-control mb-3" >\n\
                                <div class="form-group row"><div class="l-checkbox"><div class="c-checkbox">\n\
                                 <input type="checkbox" value="read" name="langr'+ i +'" class="read-' + i + '" >  <span class="check_mark"></span><label for="">Read</label> </div> <div class="c-checkbox">\n\
                                <input type="checkbox" value="write" name="langw'+ i +'" class="write-' + i + '"> <span class="check_mark"></span><label for="">write</label></div><div class="c-checkbox">\n\
                                <input type="checkbox" value="speak" name="langs'+ i +'" class="speak-' + i + '"> <span class="check_mark"></span><label for="">speak</label></div></div>\n\
                                <a href="javascript:void(0);" class="remove_button" title="Remove field"><i class="glyphicon glyphicon-minus-sign"></i></div>'; //New input field html
                x++; //Increment field counter
                $(wrapper).append(fieldHTML); // Add field html
                //auto-search language function called
               // $('.lang-' + i).typeahead({source: auto_search});
            }

        });
                    /*** Emergency Details  add end***/
                      /*** calculate age of given date of birth and not below 18 start***/
        $('#age').attr('readonly',true);
    $( "#date_of_birth").change(function () { 

            var date_of_birth=$("#date_of_birth").val();

   var url="{{ URL::to('getage') }}?date="+date_of_birth;
               
               $.get(url, function(data)
                {
                  if($.trim(data[0]) < 18 ){
               $(".dob_error_message").html("<p>Age should be greater than 18.</p>"); 
               $("#date_of_birth").val('');
               $("#age").val('');
                 $("#age").parsley().validate();
            }
            else {
                   $("#age").val($.trim(data[0]));
                $(".dob_error_message").html("<p></p>");
                   
                   $("#age").parsley().destroy();
            }  
            if($.trim(data[1])=="1"){
                  showCustomAlert("Wish U happy Birthday",'success');
                }
                });
         
        });



    $( "#spouse_dob").change(function () { 

            var date_of_birth=$("#spouse_dob").val();

   var url="{{ URL::to('getage') }}?date="+date_of_birth;
               
               $.get(url, function(data)
                {
                  if($.trim(data[0]) < 18 ){
               $(".sdob_error_message").html("<p>Age should be greater than 18.</p>"); 
               $("#spouse_dob").val('');
             
            }else{

             $(".sdob_error_message").html(""); 
}
                });
         
        });

                          /*** calculate age of given date of birth and not below 18 end***/
/**** Emergency Details remove function start *****/
            $(document).on("click", ".remove_field", function (e) { 
                e.preventDefault();
                $(this).closest('tr').remove();
                x--;
            });

            $(wrapper).on('click', '.remove_button', function (e) { //Once remove button is clicked
                e.preventDefault();
                $(this).parent('div').parent().remove(); //Remove field html
                x--; //Decrement field counter
            });
/**** Emergency Details remove function start *****/

    });

    $(document).on("focus", ".date_of_joining", function () {

        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            minDate: "2024-04-01", 
            showAnim: "slideDown",
            yearRange: "-25:+0",

        });
    });

// validate alphabets only 
  $(document).on('keypress', '.relation-0,.emer_name,.lang,#first_name,#spouse_name,#last_name,.father_name,.mother_name,#skill,#children_name', function(ev){
        var regex = new RegExp("^[a-z,A-Z.,' ']+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
    });

//ajima
  $(document).on('keypress', '#branch_name,#bank_name,#school_name,#school_board,#account_holder_name,#organization_name,#certificate_name,#course_name', function(ev){
        var regex = new RegExp("^[a-z,A-Z.,' ']+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
    });




//uppercase validation
$("#id_number1,#id_number,#visa_number").bind('keyup', function (e) {
    if (e.which >= 97 && e.which <= 122) {
        var newKey = e.which - 32;
        // I have tried setting those
        e.keyCode = newKey;
        e.charCode = newKey;
    }


    $("#id_number1").val(($("#id_number1").val()).toUpperCase());
    $("#id_number").val(($("#id_number").val()).toUpperCase());
    $("#visa_number").val(($("#visa_number").val()).toUpperCase());
 
});


        /*Ajima purpose for PAn no Validation*/
$('.pan_number').change(function(event){

 var regExp = /[a-zA-z]{5}\d{4}[a-zA-Z]{1}/; 
 var txtpan = $(this).val(); 
 if (txtpan.length == 10 ) { 
  if( txtpan.match(regExp) ){
   $('.pan').hide();
  }
  else {
  $(".pan_number").val('');
    $('.pan').show();
   event.preventDefault(); 
  } 
 } 
 else { 
     $(".pan_number").val('');
      $('.pan').show();
       event.preventDefault(); 
 } 

});

$('.pan_number').on('keyup',function(){
  this.value= this.value.toUpperCase();
  });   
        /*End*/

var check_ifsc=true;
 /*Ajima purpose for ifsc no Validation*/
$('.ifsc_code').change(function(event){

 var regExp = /[a-zA-z]{4}\d{7}/; 
 var txtpan = $(this).val(); 
 if (txtpan.length == 11 ) { 

   $('.ifscpan').hide();
   check_ifsc=true;
  
  
 } 
 else { 
     $(".ifsc_code").val('');
      showCustomAlert("Please Enter Valid IFSC Code",'error');
       event.preventDefault(); 
 } 

});

$('.ifsc_code').on('keyup',function(){
  this.value= this.value.toUpperCase();
  });   
        /*End*/



//based on reporting second one to hide
  $(document).on('change', '#reporting_manager', function(ev){
        
         var nam=  $("#reporting_manager option:selected").val();   
       
  if(nam!=''){
$("#reporting_manager1 option").removeProp("disabled");

$("#reporting_manager1 option[value='"+nam+"']").prop("disabled","disabled");
$("#reporting_manager1").select2('destroy');
$("#reporting_manager1").select2();

}
    });
  //based onreporting1 second one to hide
  $(document).on('change', '#reporting_manager1', function(ev){          
         var nam=  $("#reporting_manager1 option:selected").val();
       
if(nam!=''){
  $("#reporting_manager option").removeProp("disabled");
 
 $("#reporting_manager option[value='"+nam+"']").prop("disabled","disabled");
$("#reporting_manager").select2('destroy');
$("#reporting_manager").select2();
}
    });


//based on id type1 second one to hide
  $(document).on('change', '.id_name', function(ev){
        
         var nam=  $(".id_name option:selected").val();   
       
  if(nam!=''){
$(".id_name1 option").removeProp("disabled");

$(".id_name1 option[value='"+nam+"']").prop("disabled","disabled");
$(".id_name1").select2('destroy');
$(".id_name1").select2();

}
    });
  //based on id type second one to hide
  $(document).on('change', '.id_name1', function(ev){          
         var nam=  $("#id_name1 option:selected").val();
       
if(nam!=''){
  $(".id_name option").removeProp("disabled");
 
 $(".id_name option[value='"+nam+"']").prop("disabled","disabled");
$(".id_name").select2('destroy');
$(".id_name").select2();
}
    });
    $(document).on('change', '.id_name', function(ev){
         var nam=  $("#id_name option:selected").text();
         if(nam=="VOTER ID"){
          $('#id_number').attr('minlength','10');
          $('#id_number').attr('maxlength','10');
         }
           else if(nam=="DRIVING LICENCE"){
          $('#id_number').attr('minlength','15');
          $('#id_number').attr('maxlength','15');
         } else if(nam=="PASSPORT"){
          $('#id_number').attr('minlength','8');
          $('#id_number').attr('maxlength','9');
         }else{
            $('#id_number').removeAttr('minlength');
          $('#id_number').removeAttr('maxlength');
         }
});
  $(document).on('change', '.id_name1', function(ev){
         var nam=  $("#id_name1 option:selected").text();
         if(nam=="VOTER ID"){
          $('#id_number1').attr('minlength','10');
          $('#id_number1').attr('maxlength','10');
         }
        else if(nam=="DRIVING LICENCE"){
          $('#id_number1').attr('minlength','15');
          $('#id_number1').attr('maxlength','15');
         }else if(nam=="PASSPORT"){
          $('#id_number1').attr('minlength','8');
          $('#id_number1').attr('maxlength','9');
         }else{
            $('#id_number1').removeAttr('minlength');
          $('#id_number1').removeAttr('maxlength');
         }


});
  $(document).on('keyup', '.no_of_children', function(ev){
    var child=$('.no_of_children').val();
   
    var html='';
    for(i=1; i<=child;i++){

    html+='<div class="form-group row"><label class="col-lg-5 col-md-5">Child '+i+' Name:</label><div class="col-md-7 "><input type="text" class="col-lg-10 col-md-10 form-control children_name" id="children_name"  name="children_name[]" ></div></div><div class="form-group row"> <label class="col-lg-5 col-md-5">Child '+i+' DOB:</label><div class="col-md-7"><div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd"><input class="form-control children_dob  from_date "  name="children_dob[]" size="16" type="text"  > <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div> ';

                  }
                  $('.child_div').html(html);

  $('.children_dob').on('mousemove',function () {

//    $('.datepicker').datepicker("destroy");
    var i = 0;
  var data ="{{\Session::get('j_date_format')}}";
   var dateToday = new Date();
      $('.children_dob').each(function () {
       $(this).attr("id",'dtpicker'+ i).datepicker({
               dateFormat:  data,
              changeYear: true ,
              changeMonth:true,
              //minDate:dateToday,
              minDate: null,
              todayHighlight: true,      

            });

    i++;
});
   });
        });
           /** contact same as permanent check box check **/
var check_check="{{$employee_contact[0]->same_address}}";

if(check_check=="on"){
                    $('#same_address').prop('checked',true);
                    $('#c_address,#c_pincode,#c_locality,#c_flat_no,#c_street').attr('readonly',true);
                    $('.country_contact').css('pointer-events','none');
 
}


  jQuery("#department option:contains('-- Please Select --')").remove();
  jQuery("#location_id option:contains('-- Please Select --')").remove();
  jQuery("#area option:contains('-- Please Select --')").remove();


/*** end **/

// Get the modal
var modal = document.getElementById('myModalImage');

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById('myImg');
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
img.onclick = function(){
  modal.style.display = "block";
  modalImg.src = this.src;
  captionText.innerHTML = this.alt;
}

// Get the <span> element that closes the modal
var closeButton = document.querySelector(".close");

// Function to close the modal when the close button is clicked
closeButton.onclick = function() {
    modal.style.display = "none";
}

// Close the modal if the user clicks anywhere outside the modal content
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

    $(document).on("focus", ".date_of_birth", function () {

        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            showAnim: "slideDown",
            yearRange: "-60:+0",

        });
    });
</script>

@endpush
