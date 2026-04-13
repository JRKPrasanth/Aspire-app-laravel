@extends('layouts.header')
@section('content')


<style>
    input[type="file"] {
    display: none;
}
.panel-info {
    border-color: #5b75a6;
}

    .select2-container{
      height: auto !important ;
    }

    .select2-selection__rendered
    {

      font-size: 11px !important;
    }

    .select2-container--default .select2-selection--multiple
    {
      border:none !important ;
    }
    .select2-container .select2-selection--multiple 
    {
        box-sizing: border-box !important ;
        cursor: pointer !important ;
        display: block !important ;
        min-height: 27px !important ;
        user-select: none !important;
        -webkit-user-select: none !important ;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple
    {
       border: none !important;

    }
    
.custom-file-upload 
{
    border: 1px solid #000;
    border-radius: 5px;
    display: inline-block;
    padding: 5px 12px;
    cursor: pointer;
}

.btnic
{
    text-align: center;
    background-color: DodgerBlue;
    border: none;
    color: white;
    padding: 12px 30px;
    cursor: pointer;
    font-size: 12px;
    background-color: #33a9ec1c;
}
.ver1
{
  border: 1px solid rgba(55, 48, 73, .3);
}

.ver1 table
{
  width:100%;
}
.column100.column1
{
 width: 265px;
 padding-left: 42px;
}

.row100.head th
{
    padding: 10px;
}

.table100.ver1 td {
 font-size: 12px;
 color: #808080;
 padding: 10px;
 }

.table100.ver1 th {
 font-size: 12px;
 color: #fff;
 text-transform: uppercase;
 padding: 10px;
 background-color:#112f7aad;
}
.actioncolumn
{
display:inherit;    
}
.l-checkbox .glyphicon{
    top:-20px;
}
</style>




<!------------------------- Tab content start ------------------------------->
<div class="tab row" role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs col-lg-2 col-md-2 tabmenu" role="tablist">
        <li role="presentation" data-tab="tab1" class="active menu firsttab"><a href="#Section1" class="1" aria-controls="home" role="tab" data-toggle="tab">Official</a></li>
        <li role="presentation" data-tab="tab2" class="menu"><a href="#Section2" class="2" aria-controls="profile" role="tab" data-toggle="tab">Personal</a></li>
        <li role="presentation" data-tab="tab3" class="menu"><a href="#Section3" class="3" aria-controls="messages" role="tab" data-toggle="tab">Contact</a></li>
        <li role="presentation" data-tab="tab4" class="menu"><a href="#Section4" class="4" aria-controls="messages" role="tab" data-toggle="tab">Bank Details</a></li>
        <li role="presentation" data-tab="tab5" class="menu"><a href="#Section5" class="5" aria-controls="messages" role="tab" data-toggle="tab">Education</a></li>
        <li role="presentation" data-tab="tab6" class="menu"><a href="#Section6" class="6" aria-controls="messages" role="tab" data-toggle="tab">Experience</a></li>
        <li role="presentation" data-tab="tab7" class="menu"><a href="#Section7" class="7" aria-controls="messages" role="tab" data-toggle="tab">Skills</a></li>
        <li role="presentation" data-tab="tab8" class="menu"><a href="#Section8" class="8" aria-controls="messages" role="tab" data-toggle="tab">Training and Certification</a></li>
        <li role="presentation" data-tab="tab9" class="menu"><a href="#Section9" class="9" aria-controls="messages" role="tab" data-toggle="tab">Visa and Immigration</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content col-lg-10 col-md-10">
        <div role="tabpanel" class="tab-pane fade in active" id="Section1">
            <form id="official_form" data-parsley-validate>
                <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
                <input type="hidden" name="form_name" id="form_name" value="official_details"/>
            <div class="container">
                <div class="row">
                <div class="col-lg-6 col-md-6">
                    <fieldset>
                        <legend>Employee Details</legend>
                        <div class="form-group row">
                                <label class="col-lg-5 col-md-5"><span class="req">*</span>Employee Code:</label>
                                <div class="col-md-7 ">
                                    <input type="text" class="col-md-10 form-control" name="employee_number" id="employee_number" required value="{{$employee_official[0]->employee_number}}">
                                    <span class="btn btn-danger dup_name1" style="display:none; font-size:10px;" style="margin-left: -130px;"></span>
                                </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-5 col-md-5"><span class="req">*</span>Prefix:</label>
                            <div class="col-md-7 ">
                                <select class="select2 form-control" id="prefix" name="prefix" required>
                                    <option value="">-- Please select --</option>
                                    <option value="1" {{$employee_official[0]->prefix == "1" ? 'selected' : ''}}>Mr</option>
                                    <option value="2" {{$employee_official[0]->prefix == "2" ? 'selected' : ''}}>Ms</option>
                                    <option value="3" {{$employee_official[0]->prefix == "3" ? 'selected' : ''}}>Mrs</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-lg-5 col-md-5"><span class="req">*</span>First Name:</label>
                            <div class="col-md-7 ">
                                <input type="text" class="col-lg-10 col-md-10 form-control" id="first_name" name="first_name" value="{{$employee_official[0]->first_name}}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                              <label class="col-lg-5 col-md-5"><span class="req">*</span>Last Name:</label>
                              <div class="col-md-7 ">
                                  <input type="text" class="col-lg-10 col-md-10 form-control" id="last_name" name="last_name" value="{{$employee_official[0]->last_name}}"required>
                              </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-5 col-md-5"><span class="req">*</span>Email:</label>
                            <div class="col-md-7 ">
                                <input type="text" class="col-lg-10 col-md-10 form-control email" id="email" name="email" value="{{$employee_official[0]->email}}" required>
                                <span class="btn btn-danger dup_name" style="display:none; font-size:10px;" style="margin-left: -130px;"></span>
                                <span class="btn btn-danger email_vali" id="" style="display:none;"> Email format is example123@gmail.com</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-5 col-md-5"><span class="req">*</span>Company:</label>
                                <div class="col-md-7 ">
                                    <select class="select2 " name="company_id" id="company_id"  required>
                                        {!!$company!!}}
                                    </select>
                               </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-5 col-md-5"><span class="req">*</span>Organization:</label>
                                <div class="col-md-7">
                                   <select class="select2 form-control" name="organisation" id="organisation" required>                                       
                                       {!!$organization!!}}
                                   </select>
                               </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-lg-5 col-md-5"><span class="req">*</span>Location Name:</label>
                           <div class="col-md-7 ">
                              <select class="select2 form-control" name="location_id" id="location_id" required>
                                  {!!$location!!}}
                              </select>
                          </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-5 col-md-5"><span class="req">*</span>Department:</label>
                                <div class="col-md-7 ">
                                    <select class="select2 form-control" name="department[]" id="department" multiple="" required>
                                         {!!$department!!}
                                    </select>
                                </div>
                        </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Reporting Manager:</label>
                        <div class="col-md-7">
                            <select class="select2 form-control" name="reporting_manager" id="reporting_manager" required>
                                 {!!$reporting_manager!!}
                            </select>
                        </div>
                    </div>
                        
                        @php  $image = $employee_official[0]->photo == "" ? "profile_none.jpg" : $employee_official[0]->photo; @endphp
                   
                    <div class="form-group row" >
                        <label class="col-lg-5 col-md-5">Photo:</label> <div class="col-md-7 text-left" style="margin-top:-2%;">
                            <img id="myImg"  src="{{asset('images/profile_images/'.$image)}}" alt="your image" height="20px" width="20px" style="width: 140px;height: 140px;">
                            <input type="file" name="photo" class="file_upload" id="photo"></div>
                        
                            
                        
                        <div class="col-md-offset-5 col-md-6" style="margin-top:2.8%;margin-left:42.666667%;">
                        <label for="file-upload" class="custom-file-upload file_choose ">
                                 <i class="fa fa-cloud-upload "></i>&nbsp;File Upload
                        </label>
                        </div>

                    </div>
                    </fieldset>
                </div>
                <div class="col-lg-6 col-md-6">
                    <fieldset>
                        <legend>Other Details</legend>
                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Job Title:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" name="job_title" id="job_title" required>
                             {!! $job_title !!}  
                            </select>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Position:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" name="position" id="position" required>
                              {!! $position !!}
                            </select>
                        </div>
                    </div>

                     <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Employment Status:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" name="employment_status" id="employment_status" required>
                                <option value="">-- Please select --</option>
                                <option value="1" {{$employee_official[0]->employment_status == "1" ? 'selected' : ''}}>Contract</option>
                                <option value="2" {{$employee_official[0]->employment_status == "2" ? 'selected' : ''}}>Deputation</option>
                                <option value="3" {{$employee_official[0]->employment_status == "3" ? 'selected' : ''}}>Permanent</option>
                                <option value="4" {{$employee_official[0]->employment_status == "4" ? 'selected' : ''}}>Probationary</option>
                            </select>
                        </div>
                    </div>
                        
                     <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Date Of Joining:</label>
                        <div class="col-md-7">
                            <div class="input-group col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control form_date from_date date_of_joining" id="date_of_joining" name="date_of_joining" size="16" type="text" value="{{$employee_official[0]->date_of_joining}}" required>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                        
                    <div class="form-group row">
                       <label class="col-lg-5 col-md-5">ESI Number:</label>
                       <div class="col-md-7 ">
                           <input type="text" class="col-lg-10 col-md-10 form-control" value="{{$employee_official[0]->esi_no}}" name="esi_no" id="esi_no">
                       </div>
                    </div>
                        
                    <div class="form-group row">
                       <label class="col-lg-5 col-md-5">Provident Fund Number:</label>
                       <div class="col-md-7 ">
                           <input type="text" class="col-lg-10 col-md-10 form-control" value="{{$employee_official[0]->pf_no}}" name="pf_no" id="pf_no" maxlength="25">
                       </div>
                   </div>
                        
                    <div class="form-group row">
                       <label class="col-lg-5 col-md-5">UAN Number:</label>
                       <div class="col-md-7 ">
                           <input type="text" class="col-lg-10 col-md-10 form-control" value="{{$employee_official[0]->uan_no}}" name="uan_no" id="uan_no">
                       </div>
                    </div>
                    <div class="form-group row">
                       <label class="col-lg-5 col-md-5"><span class="req">*</span>Mobile Number:</label>
                       <div class="col-md-7">
                           <input type="text" class="col-lg-10 col-md-10 form-control work_telephone_number" value="{{$employee_official[0]->work_telephone_number}}" name="work_telephone_number" id="work_telephone_number" required maxlength="10">
                       </div>
                    </div>
                        
                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5">Biometric Emp No:</label>
                        <div class="col-md-7 ">
                            <input type="text" class="col-lg-10 col-md-10 form-control" name="biometric_empno" value="{{$employee_official[0]->biometric_empno}}" id="biometric_empno" >
                        </div>
                    </div>
                        
                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Employee type:</label>
                        <div class="col-md-7">
                            <select class="select2 form-control" name="employee_type" id="employee_type" required>
                               {!! $employee_type !!}
                            </select>
                        </div>
                    </div>
                        
                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Group Type:</label>
                        <div class="col-md-7">
                            <select class="select2 form-control" name="group_type" id="group_type" required>
                               {!! $group_type !!}
                            </select>
                        </div>
                    </div>
                        
                    </fieldset>
                </div>

                  <div class="form-group col-md-12 text-center">
                    <button type="button"  class="btn save_off save" data-form="0" id="save_off">Submit</button>
                    <button type="button"  class="btn btn-cancel cancel reset" >Cancel</button>
            </div>

              </div>

            </div>
            
            </form>
        </div>

        <div role="tabpane2" class="tab-pane fade" id="Section2">
            <div class="container">
            <form  id="personal_form" >
                {{csrf_field()}}
                <input type="hidden" name="form_name" id="form_name" value="personal_details">
                <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                     <fieldset>
                        <legend>Personal Details</legend>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Gender :</label>
                        <div class="col-md-7">
                            <select  class="col-md-6 form-control select2" name="gender" id="gender"  required>
                                <option value="">-- Please Select --</option>
                                <option value="1" {{$employee_personal[0]->gender == "1" ? 'selected' : ''}}>Male</option>
                                <option value="2" {{$employee_personal[0]->gender == "2" ? 'selected' : ''}}>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-lg-5 col-md-5">Marital Status:</label>
                      <div class="col-md-7 ">
                          <select class="select2 form-control marital_status" name="marital_status" id="marital_status" >
                              <option value="">-- Please select --</option>
                              <option value="1" {{ $employee_personal[0]->marital_status == "1" ? 'selected' : ''}}>Single</option>
                              <option value="2" {{ $employee_personal[0]->marital_status == "2" ? 'selected' : ''}}>Married</option>
                          </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-lg-5 col-md-5"><span class="req">*</span>Nationality:</label>
                      <div class="col-md-7 ">
                          <select type="text" class="col-md-6 form-control select2" id="nationality" name="nationality" required>
                              <option value="">-- Please Select --</option>
                              <option value="1" {{$employee_personal[0]->nationality == "1" ? 'selected' : ''}}>India</option>
                              <option value="2" {{$employee_personal[0]->nationality == "2" ? 'selected' : ''}}>British</option>
                          </select>
                      </div>
                    </div>
                        
                        

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5">Date of Birth:</label>
                        <div class="col-md-7">
                            <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control  from_date date_of_birth" id="date_of_birth" name="date_of_birth" size="16" type="text" value="{{$employee_personal[0]->date_of_birth}}">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Age:</label>
                            <div class="col-md-7 ">
                               <input type="text" class="col-lg-10 col-md-10 form-control" id="age" name="age" value="{{$employee_personal[0]->age}}" required>
                            </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Mother Tongue:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" name="monther_tongue" id="monther_tongue" required>
                                <option value="">-- Please select --</option>
                                <option value="1" {{$employee_personal[0]->mother_tongue == "1" ? 'selected' : ''}}>English</option>
                                <option value="2" {{$employee_personal[0]->mother_tongue == "2" ? 'selected' : ''}}>Tamil</option>
                                <option value="3" {{$employee_personal[0]->mother_tongue == "3" ? 'selected' : ''}}>Telugu</option>
                            </select>
                       </div>
                    </div>

                  <div class="form-group row">
                    <label class="col-lg-5 col-md-5"><span class="req">*</span>Religion:</label>
                     <div class="col-md-7 ">
                        <select class="select2 form-control" name="religion" id="religion" required>
                            <option value="">-- Please select --</option>
                            <option value="1" {{$employee_personal[0]->religion == "1" ? 'selected' : ''}}>Hindu</option>
                            <option value="2" {{$employee_personal[0]->religion == "2" ? 'selected' : ''}}>Muslim</option>
                            <option value="3" {{$employee_personal[0]->religion == "3" ? 'selected' : ''}}>Christain</option>
                            <option value="4" {{$employee_personal[0]->religion == "4" ? 'selected' : ''}}>Others</option>
                        </select>
                    </div>
                  </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Language known:</label>
                        <div class="col-md-7">
                            <div class="field_wrapper">
                                @if(!empty($employee_personal[0]->language))
                                @php   $result1[] = json_decode($employee_personal[0]->language); 
                                
                                
                                @endphp 
                                @foreach($result1 as $key=>$value)
                                  @foreach($value as $k=>$v1)
                                  
                                <div>
                                    <input type="text" name="language[]"  data-provide="typeahead" autocomplete="off" class="lang lang-0 form-control" value="{{$v1[0]}}" required>
                                    <div class="form-group">
              
                                            <div class="l-checkbox">
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

                                        <a href="javascript:void(0);" class="add_button" title="Add field"><i class="glyphicon glyphicon-plus-sign" style="top:-20px;left-18px;"></i></a>
                                    </div>
                                <div>
                                </div>
                                </div>
                                  @endforeach
                                  @endforeach
                                @else
                                <div>
                                    <input type="text" name="language[]"  data-provide="typeahead" autocomplete="off" class="lang lang-0 form-control" value="" required>

                            <div class="form-group">
                                <div class="l-checkbox">
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
                                <a href="javascript:void(0);" class="add_button" title="Add field"><i class="glyphicon glyphicon-plus-sign"></i></a>
                            </div>
                                    
                                <div>
                                </div>
                                </div>
                                @endif
                            </div>
                        </div>
						 </div>
                    </fieldset>

                </div>
                        
                <div class="col-lg-6 col-md-6">
                    <fieldset>
                        <legend>Additional Details</legend>
                        <div class="form-group row">
                            <label class="col-lg-5 col-md-5"><span class="req">*</span>Blood Group:</label>
                            <div class="col-md-7 ">
                                <select class="select2 form-control" name="blood_group" id="blood_group" required>
                                    <option value="">-- Please select --</option>
                                    <option value="1" {{$employee_personal[0]->blood_group == "1" ? 'selected' : ''}}>A+</option>
                                    <option value="2" {{$employee_personal[0]->blood_group == "2" ? 'selected' : ''}}>O+</option>
                                    <option value="3" {{$employee_personal[0]->blood_group == "3" ? 'selected' : ''}}>B+</option>
                                    <option value="4" {{$employee_personal[0]->blood_group == "4" ? 'selected' : ''}}>AB+</option>
                                    <option value="5" {{$employee_personal[0]->blood_group == "5" ? 'selected' : ''}}>A-</option>
                                    <option value="6" {{$employee_personal[0]->blood_group == "6" ? 'selected' : ''}}>O-</option>
                                    <option value="7" {{$employee_personal[0]->blood_group == "7" ? 'selected' : ''}}>B-</option>
                                    <option value="8" {{$employee_personal[0]->blood_group == "8" ? 'selected' : ''}}>AB-</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-5 col-md-5"><span class="req">*</span>Personal Mail ID:</label>
                            <div class="col-md-7 ">
                                <input type="text" name="personal_mail" id="personal_mail" class="form-control personal_mail" value="{{$employee_personal[0]->personal_mail}}" required/>
                                <span class="btn btn-danger email_vali" id="" style="display:none;"> Email format is example123@gmail.com</span>
                            </div>
                        </div>

                        <div class="form-group row">
                           <label class="col-lg-5 col-md-5"><span class="req">*</span>Personal Contact:</label>
                           <div class="col-md-7 ">
                               <input type="text" name="personal_mobile" id="personal_mobile" class="form-control personal_mobile" value="{{$employee_personal[0]->personal_mobile}}" maxlength="10" required />
                           </div>
                        </div>

                        

                        

                        <div class="form-group row">
                           <label class="col-lg-5 col-md-5">PAN Number:</label>
                           <div class="col-md-7 ">
                               <input type="text" class="col-lg-10 col-md-10 form-control" name="pan_number" value="{{$employee_personal[0]->pan_number}}" id="pan_number">
                           </div>
                        </div>

                        <div class="form-group row">
                           <label class="col-lg-5 col-md-5"><span class="req">*</span>AADHAR Number:</label>
                           <div class="col-md-7 ">
                               <input type="text" class="col-lg-10 col-md-10 form-control aadhar_number" name="aadhar_number" value="{{$employee_personal[0]->aadhar_number}}" id="aadhar_number" required>
                           </div>
                        </div>

                        <div class="form-group row">
                           <label class="col-lg-5 col-md-5"><span class="req">*</span>ID Type 1:</label>

                           <div class="col-md-7 ">
                               <select class="select2 form-control" name="id_name" id="id_name" required>
                                   <option value="">-- Please select --</option>
                                   <option value="1" {{$employee_personal[0]->id_name == "1" ? 'selected' : ''}}>VOTER ID</option>
                                   <option value="2" {{$employee_personal[0]->id_name == "2" ? 'selected' : ''}}>DRIVING LICENCE</option>
                                   <option value="3" {{$employee_personal[0]->id_name == "3" ? 'selected' : ''}}>PASSPORT</option>
                                   <option value="4" {{$employee_personal[0]->id_name == "4" ? 'selected' : ''}}>RATION CARD</option>
                               </select>
                           </div>
                         
                       </div>

                        <div class="form-group row">
                           <label class="col-lg-5 col-md-5"><span class="req">*</span>ID Number 1:</label>
                           <div class="col-md-7 ">
                               <input type="text" class="col-lg-10 col-md-10 form-control id_number" name="id_number" id="id_number" value="{{$employee_personal[0]->id_number}}">
                           </div>
                        </div>

                        <div class="form-group row">
                           <label class="col-lg-5 col-md-5"><span class="req">*</span>ID Type 2:</label>
                           <div class="col-md-7">
                            <select class="select2 form-control" name="id_name1" id="id_name1" required>
                                <option value="">-- Please select --</option>
                                <option value="1" {{$employee_personal[0]->id_name1 == "1" ? 'selected' : ''}}>VOTER ID</option>
                                <option value="2" {{$employee_personal[0]->id_name1 == "2" ? 'selected' : ''}}>DRIVING LICENCE</option>
                                <option value="3"{{$employee_personal[0]->id_name1 == "3" ? 'selected' : ''}}>PASSPORT</option>
                                <option value="4" {{$employee_personal[0]->id_name1 == "4" ? 'selected' : ''}}>RATION CARD</option>
                            </select>
                        </div>
                            
                        </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>ID Number 2:</label>
                         <div class="col-md-7 ">
                               <input type="text" class="col-lg-10 col-md-10 form-control id_number1" name="id_number1" id="id_number1" value="{{$employee_personal[0]->id_number1}}" required>
                           </div>
                    </div>
                    </fieldset>
                </div>


                  
                  
                   <div class="col-lg-6 col-md-6">
                     <fieldset>
                        <legend>Family Details</legend>
                      
                
                    
                    <div class="form-group row">
                          <label class="col-lg-5 col-md-5"><span class="req">*</span>Father Name :</label>
                          <div class="col-md-7 ">
                              <input type="text" class="col-md-10 form-control father_name" name="father_name" id="father_name" value="{{$employee_personal[0]->father_name}}" required>
                          </div>
                          
                    </div>
                    <div class="form-group row">
                           <label class="col-lg-5 col-md-5">AADHAR Number:</label>
                           <div class="col-md-7 ">
                               <input type="text" class="col-lg-10 col-md-10 form-control father_aadhar_number" name="father_aadhar_number" value="{{$employee_personal[0]->father_aadhar_number}}" id="father_aadhar_number" >
                            </div>
                    </div>
                       

                    <div class="form-group row">
                          <label class="col-lg-5 col-md-5"><span class="req">*</span>Mother Name :</label>
                          <div class="col-md-7 ">
                              <input type="text" class="col-md-10 form-control mother_name" name="mother_name" id="mother_name" value="{{$employee_personal[0]->mother_name}}" required>
                          </div>
                    </div>
                    <div class="form-group row">
                           <label class="col-lg-5 col-md-5">AADHAR Number:</label>
                           <div class="col-md-7 ">
                               <input type="text" class="col-lg-10 col-md-10 form-control mother_aadhar_number" name="mother_aadhar_number" value="{{$employee_personal[0]->mother_aadhar_number}}" id="mother_aadhar_number" >
                           </div>
                    </div>

                  <div class="form-group row">
                    <label class="col-lg-5 col-md-5">Spouse Name:</label>
                    <div class="col-md-7 ">
                        <input type="text" class="col-lg-10 col-md-10 form-control spouse_name" id="spouse_name" name="spouse_name" value="{{$employee_personal[0]->spouse_name}}">
                    </div>
                  </div>
                  <div class="form-group row">
                           <label class="col-lg-5 col-md-5">AADHAR Number:</label>
                           <div class="col-md-7 ">
                               <input type="text"  class="col-lg-10 col-md-10 form-control spouce_aadhar_number" name="spouce_aadhar_number" value="{{$employee_personal[0]->spouce_aadhar_number}}" id="spouce_aadhar_number" >
                           </div>
                    </div>

                  <div class="form-group row">
                    <label class="col-lg-5 col-md-5">Spouse DOB:</label>
                    <div class="col-md-7 ">
                        <div class="input-group form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input type="text"  class="col-lg-10 col-md-10 form-control from_date spouse_dob" id="spouse_dob" name="spouse_dob" value="{{$employee_personal[0]->spouse_dob}}" >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                  </div>

                
                    
                     
                    
                    
                       

                    

                    

                   


                    

               
            </fieldset>
</div>
                 
                <div class="col-lg-6 col-md-6">
                    <fieldset>
                        <legend>Additional Details</legend>
                        <div class="form-group row">
                            <label class="col-lg-5 col-md-5">Number of Child:</label>
                            <div class="col-md-7 ">
                                <input type="text" class="col-lg-10 col-md-10 form-control no_of_children" id="no_of_children" value="{{$employee_personal[0]->no_of_children}}" name="no_of_children">
                            </div>
                        </div>
                     <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Child 1 Name:</label>

                            <div class="col-md-7 ">
                                <input type="text" class="col-lg-10 col-md-10 form-control children_name1" id="children_name1" value="{{$employee_personal[0]->children_name1}}" name="children_name1" required>
                            </div>

                    </div>
                     <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Child 1 DOB:</label>
                        <div class="col-md-7">
            <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input class="form-control children_dob1 from_date" id="children_dob1" name="children_dob1" size="16" type="text" value="{{$employee_personal[0]->children_dob1}}"  required>
             <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            </div>
                    </div>
                     <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Child 2 Name:</label>

                            <div class="col-md-7 ">
                                <input type="text" class="col-lg-10 col-md-10 form-control children_name2" id="children_name2" value="{{$employee_personal[0]->children_name2}}" name="children_name2" required>
                            </div>

                    </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Child 2 DOB:</label>
                        <div class="col-md-7">
                            <div class="input-group form_date  col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control children_dob2 from_date" id="children_dob2" name="children_dob2" size="16" type="text" value="{{$employee_personal[0]->children_dob2}}"  required>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>

                    </fieldset>
                </div>




              


              <div class="form-group col-md-12 text-center">
                <button type="button"  class="btn save btn_save" data-form="1" id="save">Submit</button>
                <button type="button"  class="btn btn-cancel cancel reset" >Cancel</button>
            </div>

              </div>
            
            </form>
            </div>

        </div>
                
        <div role="tabpanel" class="tab-pane fade" id="Section3">
            <div class="container">
                <form id="contact_form"  >
                     {{csrf_field()}}
                <input type="hidden" name="form_name" id="form_name" value="contact_details"/>
                <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
              <div class="row">
                <div class="col-lg-6 col-md-6">
                     <fieldset>
                        <legend>Permanent Details</legend>

                        <div class="form-group row" style="margin-top:58px;">
                          <label class="col-lg-5 col-md-5"><span class="req">*</span>Address :</label>
                          <div class="col-md-7 ">
                              <textarea class="form-control" id="p_address" name="p_address" required>{{$employee_contact[0]->permanent_street_address}}</textarea>
                          </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5">Country:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" name="p_country" id="p_country" class="p_country">
                                {!! $p_country !!}
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5">State:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" id="p_state" name="p_state" class="p_state">
                                {!! $p_state !!}
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5">City:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" id="p_city" name="p_city" class="p_city">
                               {!! $p_city !!}
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Pincode:</label>
                         <div class="col-md-7 ">
                            <input type="text" class="col-lg-6 col-md-6 form-control" id="p_pincode" name="p_pincode" value="{{$employee_contact[0]->current_postal_code}}" required>
                        </div>
                    </div>
                        
                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5">Locality:</label>
                         <div class="col-md-7 ">
                            <input type="text" class="col-lg-6 col-md-6 form-control" id="p_locality" name="p_locality" value="{{$employee_contact[0]->permanent_locality}}" >
                        </div>
                    </div>
                    </fieldset>

                </div>
                <div class="col-lg-6 col-md-6">
                    <fieldset>
                        <legend>Current Details</legend>
                        
                         <div class="form-group row" id="radio_address">
                              <label for="same_address" class=" control-label col-md-5 "></label>
                              <div class="l-checkbox col-md-7">
                            <div class=" c-checkbox">
                                <input type="checkbox" name="same_address" id="same_address">
                                <span class="check_mark"></span>
                                <label for="">Same as Permanent Address</label>
                            </div>
                            
                        </div>
                          </div>

                        <div class="form-group row">
                          <label class="col-lg-5 col-md-5"><span class="req">*</span>Address :</label>
                          <div class="col-md-7 ">
                              <textarea class="form-control" id="c_address" name="c_address"  required>{{$employee_contact[0]->current_street_address}}</textarea>
                          </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5">Country:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" id="c_country" name="c_country" >
                               {!! $c_country !!}
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5">State:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" id="c_state" name="c_state">
                                {!! $c_state !!}
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5">City:</label>
                        <div class="col-md-7 ">
                            <select class="select2 form-control" id="c_city" name="c_city">
                                {!! $c_city !!}
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5"><span class="req">*</span>Pincode:</label>
                         <div class="col-md-7 ">
                            <input type="text" class="col-lg-6 col-md-6 form-control" id="c_pincode" name="c_pincode" value="{{$employee_contact[0]->current_postal_code}}" required>
                        </div>
                    </div>
                        
                    <div class="form-group row">
                        <label class="col-lg-5 col-md-5">Locality:</label>
                         <div class="col-md-7 ">
                            <input type="text" class="col-lg-6 col-md-6 form-control" id="c_locality" name="c_locality" value="{{$employee_contact[0]->current_locality}}" >
                        </div>
                    </div>

                    </fieldset>
                </div>


                  <div class="row">
                <div class="col-lg-12 col-md-12">
                     <fieldset>
                        <legend>Emergency Details</legend>
                        <table class="table table-striped ">
                                <thead>
                                    <tr>
                                        <th width="30"> Name</th>
                                        <th width="30"> Relation Type</th>
                                        <th width="30"> Address</th>
                                        <th width="30"> Contact Number</th>
                                        <th width="30"> Action</th>
                                    </tr>

                                </thead>
                                <tbody class="response_data">
                                    
                                    @if(!empty($employee_contact[0]->emergency_contacts))
                                    @php   $result[] = json_decode($employee_contact[0]->emergency_contacts); 
                                   
                                    @endphp 
                                    @foreach($result as $key=>$value)
                                       @foreach($value as $k=>$v)
                                     
                                    <tr>
                                        <td><input type="text" name="emer_name[]" class="form-control emer_name name-0" value="{{$v[0]}}"></td>
                                        <td><input type="text" name="relation[]" class="form-control relation-0" value="{{$v[1]}}"></td>
                                        <td><textarea  name="address[]" class="form-control address-0">{{$v[2]}}</textarea></td>
                                        <td><input type="text" name="mobile_no[]"  class="form-control mobile_no number-0" value="{{$v[3]}}" maxlength="10"></td>                              
                                        <td>
                                        <?php if($k == 0) { ?>
                                        <button class="btn-xs btn-primary add_field_button">+</button>
                                        <?php } else { ?>                                       
                                        <button class="btn-xs btn-danger remove_field">-</button>
                                        <?php } ?>
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
                                        <td><input type="text" name="mobile_no[]"  class="form-control mobile_no number-0" value=""></td>
                                        <td><button class="btn-xs btn-primary add_field_button">+</button></td>
                                    </tr>
                                    
                                    @endif
                            </table>


                    </fieldset>

                </div>

              </div>
                   <div class="form-group text-center">
                <button type="button"  class="btn save btn_save" data-form="2" id="save">Submit</button>
                <button type="button"  class="btn btn-cancel cancel reset" >Cancel</button>
            </div>

              </div>
                
                </form>
            </div>

        </div>
                
        <div role="tabpanel" class="tab-pane fade" id="Section4">
            <div class="container">
              <div class="row">

                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-info bank_details_table ">
                        <div class="panel-heading">
                        <div class="panel-title">Bank Details </div>
                          <div style="float:right; font-size: 80%; position: relative; top:-20px"><a class="show_form_btn bank_details" style="cursor: pointer;"><i class="glyphicon glyphicon-plus-sign"></i></a></div>
                        </div>
                        <div class="container" >
                        <div class="container-table100" >
                        <div class="wrap-table100">
                            <div class="table100 ver1 m-b-110">
                                <table data-vertable="ver1">
                                    <thead>
                                        <tr class="row100 head">
                                            <th class="column100 column1" width="10%">No</th>
                                            <th class="column100 column2" width="10%">Acc Type</th>
                                            <th class="column100 column3" width="10%">Bank Name</th>
                                            <th class="column100 column4" width="10%">Branch Name</th>
                                            <th class="column100 column5" width="10%">IFSC Code</th>
                                            <th class="column100 column7" width="10%">Acc H.Name</th>
                                            <th class="column100 column8" width="10%">Acc Number</th>
                                            <th class="column100 column8" width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($employee_salary)>0)
                                        @foreach($employee_salary as $key=>$value)
                                         
                                        <tr class="row100">
                                                <td class="column100 column8" data-column="column8">{{ ($key + 1) }}</td>
                                                <td class="column100 column1" data-column="column1"><?php  
                                                    if($value->account_type == "1")
                                                        echo "Saving";
                                                    if($value->account_type == "2")
                                                       echo "Salary";
                                                    if($value->account_type == "3")
                                                        echo "Current";
                                                        ?></td>
                                                <td class="column100 column2" data-column="column2">{{ $value->bank_name }}</td>
                                                <td class="column100 column3" data-column="column3">{{ $value->branch_name }}</td>
                                                <td class="column100 column4" data-column="column4">{{$value->ifsc_code}}</td>
                                               
                                                <td class="column100 column6" data-column="column6">{{$value->account_holder_name}}</td>
                                                <td class="column100 column7" data-column="column7">{{$value->account_number}}</td>
                                                <td class="column100 column8 actioncolumn" data-column="column8">
                                                      <button class="btn-sm edit_details"  data-form="salary_form" id="{{$value->id}}" type="button"><i class="fa fa-edit"></i></button>
                                                      <button class="btn-sm delete_details" data-form="salary_form" data-formid="3" id="{{$value->id}}" type="button"><i class="fa fa-trash-o"></i></button>
                                                  </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="row100">
                                            <td colspan="9" align="center" class="column100 column8" data-column="column8">No Records Found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                    
                    <div class="panel panel-info bank_details_form" style="margin-top:7px; display:none; ">
                        <div class="panel-heading" style="color:#fff; background-color: #5b75a6;">
                          <div class="panel-title">Bank Details </div>  </div>
                          <!------------------------------------------------------>
                          <form id="salary_form" data-parsley-validate >
                               {{csrf_field()}}
                            <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
                            <input type="hidden" name="row_id" class="salary_row" id="row_id"  value=""/>
                            <input type="hidden" name="form_name" id="form_name" value="salary_details"/>
                           
                          <div class="row" style="padding-top:35px;">
                             
                                
                                    <div class="col-md-6">
<!--                                      <div class="form-group row">
                                          <label class="col-lg-5 col-md-5"><span class="req">*</span>Pay Frequency :</label>
                                          <div class="col-md-7 ">
                                             <select class="select2 form-control pay_frequency" name="pay_frequency" id="pay_frequency" required>
                                                 <option value="">-- Please Select --</option>
                                                 <option value="1">Daily</option>
                                                 <option value="2">Weekly</option>
                                                 <option value="3">Monthly</option>
                                              </select>
                                          </div>
                                      </div>-->

<!--                                      <div class="form-group row">
                                          <label class="col-lg-5 col-md-5"><span class="req">*</span>Salary:</label>
                                          <div class="col-md-7 ">
                                              <input type="text" name="salary" id="salary" value="" class="form-control salary" required/>   
                                          </div>
                                      </div>-->

                                  <div class="form-group row">
                                      <label class="col-lg-5 col-md-5">Bank Name:</label>
                                      <div class="col-md-7 ">
                                           <input type="text" name="bank_name" id="bank_name" value="" class="form-control" />   
                                      </div>
                                  </div>
                                        
                                    <div class="form-group row">
                                      <label class="col-lg-5 col-md-5">Branch Name :</label>
                                      <div class="col-md-7 ">
                                           <input type="text" name="branch_name" id="branch_name" value="" class="form-control" required/>   
                                      </div>
                                    </div>

                                    <div class="form-group row">
                                      <label class="col-lg-5 col-md-5">Account Type:</label>
                                      <div class="col-md-7 ">
                                           <select name="account_type" id="account_type" value="" class="select2 " required>
                                               <option value="">-- Please Select --</option>
                                               <option value="1">Saving</option>
                                               <option value="2">Salary</option>
                                               <option value="3">Current</option>
                                           </select>
                                      </div>
                                    </div>

                                    


                                    </div>
                                    <div class="col-md-6">    
                                    <div class="form-group row">
                                      <label class="col-lg-5 col-md-5">IFSC Code :</label>
                                      <div class="col-md-7 ">
                                           <input type="text" name="ifsc_code" id="ifsc_code" value="" class="form-control" required/>   
                                      </div>
                                    </div>
                                        
                                    <div class="form-group row">
                                      <label class="col-lg-5 col-md-5">Account Holder Name :</label>
                                      <div class="col-md-7 ">
                                           <input type="text" name="account_holder_name" id="account_holder_name" value="" class="form-control" required/>   
                                      </div>
                                    </div>
                                        
                                  <div class="form-group row">
                                      <label class="col-lg-5 col-md-5">Account Numbers:</label>
                                      <div class="col-md-7 ">
                                           <input type="text" name="account_number" id="account_number" value="" class="form-control" required/>   
                                      </div>
                                    </div>
                                     
                                    
                                    </div>
                               

                               <div class="form-group text-center">
                          <button  type="button" class="btn save btn_save" data-form="3" id="save">Submit</button> &nbsp;&nbsp;&nbsp;
                          <button type="button"  class="btn btn-cancel cancel bank_form_show" >Cancel</button>
                      </div>

                           </div>
                          
                          
                    </form>
                    </div>
                </div>
              </div>



            </div>
        </div>

        <div role="tabpane5" class="tab-pane fade" id="Section5">
            <div class="container">
              <div class="row">
                 
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-info education_details_table" style='display:block;'>
                        <div class="panel-heading">
                            <div class="panel-title">Education  Details</div>
                            <div style="float:right; font-size: 80%; position: relative; top:-20px"><a class="show_form_btn " style="cursor: pointer;"><i class="glyphicon glyphicon-plus-sign education_details"></i></a></div>
                        </div>
                        <div class="container" >
                        <div class="container-table100" >
                        <div class="wrap-table100">
                            <div class="table100 ver1 m-b-110">
                                <table data-vertable="ver1">
                                  <thead>
                                    <tr class="row100 head">
                                        <th class="column100 column1" width="10%">No</th>
                                        <th class="column100 column2" width="10%">Education Level</th>
                                        <th class="column100 column3" width="10%">Institution  Name</th>
                                        <th class="column100 column4" width="10%">Board/Course</th>
                                        <th class="column100 column5" width="10%">From</th>
                                        <th class="column100 column6" width="10%">To</th>
                                        <th class="column100 column7" width="10%">Percentage/Grade</th>
                                        <th class="column100 column8" width="15%">Action</th>
                                    </tr>
                                  </thead>
                                    <tbody>
                                        @if(count($employee_education)>0)
                                        @foreach($employee_education as $key=>$value)
                                            <tr class="row100">
                                                  <td class="column100 column1" data-column="column1">{{++$key}}</td>
                                                  <td class="column100 column2" data-column="column2">
                                                  <?php  
                                                    if($value->education_level == "1")
                                                        echo "SSLC";
                                                    if($value->education_level == "2")
                                                       echo "HSC";
                                                    if($value->education_level == "3")
                                                        echo "diplamo";
                                                    if($value->education_level == "4")
                                                        echo "Bachelor's Degree";
                                                    if($value->education_level == "5")
                                                       echo "Master's Degree";
                                                    if($value->education_level == "6")
                                                        echo "Others";
                                                    ?>
                                                  </td>
                                                  <td class="column100 column3" data-column="column3">{{$value->school_name}}</td>
                                                  <td class="column100 column4" data-column="column4">{{$value->school_board}}</td>
                                                  <td class="column100 column5" data-column="column5">{{$value->from_date}}</td>
                                                  <td class="column100 column7" data-column="column7">{{$value->to_date}}</td>
                                                  <td class="column100 column8" data-column="column8">{{ $value->percentage }}</td>
                                                  <td class="column100 column8 actioncolumn" data-column="column8">
                                                      <button class="btn-sm edit_details"  data-form="education_form" id="{{$value->id}}" type="button"><i class="fa fa-edit"></i></button>
                                                      <button class="btn-sm delete_details"  data-form="education_form" data-formid="4" id="{{$value->id}}" type="button"><i class="fa fa-trash-o"></i></button>
                                                  </td>
                                            </tr>
                                        @endforeach
                                        @else
                                        <tr class="row100">
                                                <td align="center" colspan="8" class="column100 column1" data-column="column1">No Records Found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                    
                        <div class="panel panel-info education_details_form" style="margin-top:7px; display:none; ">
                        <div class="panel-heading" style="color:#fff; background-color: #5b75a6;">
                          <div class="panel-title">Education Details </div>  
                        </div>
                          <!------------------------------------------------------>
                          <form id="education_form" data-parsley-validate >
                            <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
                            <input type="hidden" name="row_id" class="education_row" id="row_id"  value=""/>
                            <input type="hidden" name="form_name" id="form_name" value="education_details"/>
                                <div class="row" style="padding-top:35px;">
                                   <div class="col-md-12" >
                                      <div class="row">
                                          <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-lg-5 col-md-5"><span class="req">*</span>Education level :</label>
                                                <div class="col-md-7 educational_leavel1">
                                                   <select class="select2 form-control education_level" name="education_level" id="education_level" required>
                                                       <option value="">-- Please Select --</option>
                                                       <option value="1">SSLC </option>
                                                       <option value="2">HSC </option>
                                                       <option value="3">Diplomo </option>
                                                       <option value="4">Bachelor's Degree</option>
                                                       <option value="5">Master's Degree</option>
                                                       <option value="6">Others</option>
                                                    </select>
                                                </div>
                                            </div>
                                              
                                            <div class="form-group row specify">
                                              <label class="col-lg-5 col-md-5 " ><span class="req">*</span>Please Specify:</label>
                                              <div class="col-md-7">
                                                  <input type="text" name="other" id="other" value="" class="form-control" required />
                                              </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-5 col-md-5 edu_school1" ><span class="req">*</span>Name of the School:</label>
                                                <div class="col-md-7 ">
                                                    <input type="text" name="school_name"  id="school_name" value="" class="form-control edu_school11" required/>   
                                                </div>
                                            </div>

                                        <div class="form-group row education_level1">
                                            <label class="col-lg-5 col-md-5 edu_school2" ><span class="req">*</span>Boards of Education:</label>
                                            <div class="col-md-7 ">
                                                 <input type="text" name="school_board" id="school_board" value="" class="form-control education_level12" required />   
                                            </div>
                                        </div>
                                         
                                         </div>
                                         <div class="col-md-6">     
                                        <div class="form-group row course">
                                          <label class="col-lg-5 col-md-5 " ><span class="req">*</span>Course:</label>
                                              <div class="col-md-7">
                                                  <input type="text" name="course" id="course" value="" class="form-control" required />
                                              </div>
                                        </div>

                                          <div class="form-group row">
                                            <label class="col-lg-5 col-md-5" ><span class="req">*</span>From:</label>
                                            <div class="col-md-7 ">
                                                <div class="input-group form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                 <input type="text" name="from_date" id="from_date" value="" class="form-control datepicker" required/>   
                                                  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                   </div>
                                            </div>
                                          </div>

                                          <div class="form-group row">
                                            <label class="col-lg-5 col-md-5"><span class="req">*</span>To :</label>
                                            <div class="col-md-7 ">
                                                <div class="input-group form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                 <input type="text" name="to_date" id="to_date" value="" class="form-control datepicker" required/>   
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                   </div>
                                            </div>
                                          </div>

                                          <div class="form-group row">
                                            <label class="col-lg-5 col-md-5"><span class="req">*</span>Percentage / Grade  :</label>
                                            <div class="col-md-7 ">
                                                 <input type="text" name="percentage" id="percentage" value="" class="form-control percentage" required/>   
                                            </div>
                                          </div>

                                          </div>
                                      </div>

                                      </div>


                                 </div>
                          
                          <div class="form-group text-center">
                          <button  type="button" class="btn save btn_save" data-form="4" id="save">Submit</button> &nbsp;&nbsp;&nbsp;
                          <button type="button"  class="btn btn-cancel cancel education_form_show" >Cancel</button>
                      </div>
                    </form>
                    </div>
                </div>
                
              </div>
            </div>
        </div>
                
        <div role="tabpanel" class="tab-pane fade" id="Section6">
            <div class="container">
              <div class="row">
                  
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-info experience_details_table">
                        <div class="panel-heading">
                            <div class="panel-title">Experience Details</div>
                            <div style="float:right; font-size: 80%; position: relative; top:-20px"><a class="show_form_btn" style="cursor: pointer;"><i class="glyphicon glyphicon-plus-sign experience_details"></i></a></div>
                        </div>
                        <div class="container" >
                        <div class="container-table100" >
                        <div class="wrap-table100">
                            <div class="table100 ver1 m-b-110">
                                <table data-vertable="ver1">
                                  <thead>
                                    <tr class="row100 head">
                                        <th class="column100 column1" width="10%">No</th>
                                        <th class="column100 column2" width="10%">Organization  Name</th>
                                        <th class="column100 column3" width="10%">Organization Website</th>
                                        <th class="column100 column4" width="10%">Designation</th>
                                        <th class="column100 column5" width="10%">CTC</th>
                                        <th class="column100 column6" width="10%">From</th>
                                        <th class="column100 column7" width="10%">To</th>
                                        <th class="column100 column8" width="15%">Action</th>
                                    </tr>
                                  </thead>
                                    <tbody>
                                        <?php  ?>
                                        @if(count($employee_experience)>0)
                                        @foreach($employee_experience as $key=>$value)
                                        <tr class="row100">
                                            <td class="column100 column7" data-column="column7">{{++$key}}</td>
                                          <td class="column100 column1" data-column="column1">{{ $value->organization_name }}</td>
                                          <td class="column100 column2" data-column="column2">{{$value->organization_website}}</td>
                                          <td class="column100 column3" data-column="column3">{{$value->designation}}</td>
                                          <td class="column100 column4" data-column="column4">{{$value->ctc}}</td>
                                          <td class="column100 column5" data-column="column5">{{$value->from_date}}</td>
                                          <td class="column100 column6" data-column="column6">{{$value->to_date}}</td>
                                          
                                          <td class="column100 column8 actioncolumn" data-column="column8">
                                              <button class="btn-sm edit_details"  data-form="experience_form" id="{{$value->id}}" type="button"><i class="fa fa-edit"></i></button>
                                              <button class="btn-sm delete_details"  data-form="experience_form" data-formid="5" id="{{$value->id}}" type="button"><i class="fa fa-trash-o"></i></button>
                                          </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="row100">
                                              <td align="center" colspan="8" class="column100 column1" data-column="column1">No Records Found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                    
                    <div class="panel panel-info experience_details_form" style="margin-top:7px; display:none; ">
                        <div class="panel-heading" style="color:#fff; background-color: #5b75a6;">
                          <div class="panel-title">Experience Details </div>  
                        </div>
                          <!------------------------------------------------------>
                          <form id="experience_form" data-parsley-validate >
                            <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
                            <input type="hidden" name="row_id" class="experience_row" id="row_id"  value=""/>
                            <input type="hidden" name="form_name" id="form_name" value="experience_details"/>
                                <div class="row" style="padding-top:35px;">
                                   <div class="col-md-12" >
                                      <div class="row">
                                          <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-lg-5 col-md-5"><span class="req">*</span>Organization Name :</label>
                                                <div class="col-md-7">
                                                   <input type="text" class="form-control" name="organization_name" id="organization_name" required>         
                                                </div>
                                            </div>
                                              
                                            <div class="form-group row">
                                              <label class="col-lg-5 col-md-5 " ><span class="req">*</span>Organization Website:</label>
                                              <div class="col-md-7">
                                                  <input type="text" name="organization_website" id="organization_website" value="" class="form-control" required />
                                              </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-5 col-md-5 " ><span class="req">*</span>Designation:</label>
                                                <div class="col-md-7 ">
                                                    <input type="text" name="designation"  id="designation" value="" class="form-control" required/>   
                                                </div>
                                            </div>

                                        <div class="form-group row ">
                                            <label class="col-lg-5 col-md-5" ><span class="req">*</span>CTC:</label>
                                            <div class="col-md-7 ">
                                                 <input type="text" name="ctc" id="ctc" value="" class="form-control ctc" required />   
                                            </div>
                                        </div>
                                        </div>
                                        <div class="col-md-6">      
                                        <div class="form-group row">
                                          <label class="col-lg-5 col-md-5 " ><span class="req">*</span>From:</label>
                                              <div class="col-md-7">
                                                  <div class="input-group form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                  <input type="text" name="from_date" id="ex_from_date" value="" class="form-control ex_from_date from_date" required />
                                                     <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                   </div>
                                              </div>
                                        </div>

                                          <div class="form-group row">
                                            <label class="col-lg-5 col-md-5" ><span class="req">*</span>To:</label>
                                            <div class="col-md-7">
                                                <div class="input-group form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                 <input type="text" name="to_date" id="ex_to_date" value="" class="form-control ex_to_date from_date" required/>   
                                                   <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                   </div>
                                            </div>
                                          </div>

                                          <div class="form-group row">
                                            <label class="col-lg-5 col-md-5"><span class="req">*</span>Reason for Leaving :</label>
                                            <div class="col-md-7 ">
                                                 <input type="text" name="reason_leaving" id="reason_leaving" value="" class="form-control" required/>   
                                            </div>
                                          </div>

                                          </div>
                                      </div>

                                      </div>
                                       <div class="form-group text-center">
                          <button  type="button" class="btn save btn_save" data-form="5" id="save">Submit</button> &nbsp;&nbsp;&nbsp;
                          <button type="button"  class="btn btn-cancel cancel experience_form_show" >Cancel</button>
                      </div>

                                 </div>
                          
                          
                    </form>
                    </div>
                </div>
                 
              </div>
            </div>
        </div>
                
                
                
        <div role="tabpanel" class="tab-pane fade" id="Section7">
            <div class="container">
              <div class="row">
                 
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-info  skill_details_table">
                        <div class="panel-heading">
                            <div class="panel-title">Skills Details</div>
                            <div style="float:right; font-size: 80%; position: relative; top:-20px"><a class="show_form_btn" style="cursor: pointer;"><i class="glyphicon glyphicon-plus-sign skill_details"></i></a></div>
                        </div>
                       
                        <div class="container" >
                        <div class="container-table100" >
                        <div class="wrap-table100">
                            <div class="table100 ver1 m-b-110">
                                <table data-vertable="ver1">
                                  <thead>
                                    <tr class="row100 head">
                                        <th class="column100 column1" width="10%">No</th>
                                        <th class="column100 column2" width="10%">Skills</th>
                                        <th class="column100 column3" width="10%">Version</th>
                                        <th class="column100 column4" width="10%">Competency Level</th>
                                        <th class="column100 column8" width="15%">Action</th>
                                    </tr>
                                  </thead>
                                    <tbody>
                                        @if(count($employee_skill)>0)
                                        @foreach($employee_skill as $key=>$value)
                                        <tr class="row100">
                                              <td class="column100 column1" data-column="column1">{{++$key}}</td>
                                              <td class="column100 column2" data-column="column2">{{$value->skill}}</td>
                                              <td class="column100 column3" data-column="column3">{{$value->version}}</td>
                                              <td class="column100 column4" data-column="column4">
                                                  <?php                                                   
                                                  if($value->competency_level =="1")
                                                      echo "Fundamental Awareness";
                                                  if($value->competency_level =="2")
                                                      echo "Novice";
                                                  if($value->competency_level =="3")
                                                      echo "Intermediate";
                                                  if($value->competency_level =="4")
                                                      echo "Advanced";
                                                  if($value->competency_level =="5")
                                                      echo "Expert";
                                                   ?></td>
                                              <td class="column100 column8 actioncolumn" data-column="column8">
                                                  <button class="btn-sm edit_details"  data-form="skill_form" id="{{$value->id}}"type="button"><i class="fa fa-edit"></i></button>
                                                  <button class="btn-sm delete_details"  data-form="skill_form" data-formid="6" id="{{$value->id}}" type="button"><i class="fa fa-trash-o"></i></button>
                                              </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="row100">
                                              <td align="center" colspan="5" class="column100 column1" data-column="column1">No Records Found</td>
                                        </tr>
                                        @endif
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                    
                    <div class="panel panel-info skill_details_form" style="margin-top:7px; display:none; ">
                        <div class="panel-heading" style="color:#fff; background-color: #5b75a6;">
                          <div class="panel-title">Experience Details </div>  
                        </div>
                          
                          <form id="skill_form" data-parsley-validate >
                            <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
                            <input type="hidden" name="row_id"  class="skill_row" id="row_id"  value=""/>
                            <input type="hidden" name="form_name" id="form_name" value="skill_details"/>
                                <div class="row" style="padding-top:35px;">
                                   <div class="col-md-12" >
                                      <div class="row">
                                          
                                            <div class="form-group col-md-4 ">
                                                <label class="col-lg-4 col-md-4"><span class="req">*</span>Skill :</label>
                                                <div class="col-md-8">
                                                   <input type="text" class="form-control" name="skill" id="skill" required>
                                                </div>
                                            </div>
                                              
                                            <div class="form-group col-md-4 ">
                                              <label class="col-lg-4 col-md-4 " >Version:</label>
                                              <div class="col-md-8">
                                                  <input type="text" name="version" id="version" value="" class="form-control" required />
                                              </div>
                                            </div>

                                            <div class="form-group col-md-4 ">
                                                <label class="col-lg-4 col-md-4 " ><span class="req">*</span>Competency Level:</label>
                                                <div class="col-md-8 ">
                                                <select  name="competency_level"  id="competency_level" class="select2 form-control" required>
                                                    <option value="">--Please Select--</option>
                                                    <option value="1">Fundamental Awareness</option>
                                                    <option value="2">Novice</option>
                                                    <option value="3">Intermediate</option>
                                                    <option value="4">Advanced</option>
                                                    <option value="5">Expert</option>
                                                </select>
                                                </div>
                                            </div>

                                          
                                      </div>



                                      </div>
                                         <div class="form-group text-center">
                          <button  type="button" class="btn save btn_save" data-form="6" id="save">Submit</button> &nbsp;&nbsp;&nbsp;
                          <button type="button"  class="btn btn-cancel cancel skill_form_show" >Cancel</button>
                      </div>

                                 </div>
                          
                          
                    </form>
                    </div>
                </div>
                  </form>
              </div>
            </div>
        </div>


        <div role="tabpanel" class="tab-pane fade" id="Section8">
            <div class="container">
              <div class="row">
                 
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-info training_detail_table">
                        <div class="panel-heading">
                            <div class="panel-title">Training and Certification</div>
                            <div style="float:right; font-size: 80%; position: relative; top:-20px"><a class="show_form_btn" style="cursor: pointer;"><i class="glyphicon glyphicon-plus-sign training_details"></i></a></div>
                        </div>
                        <div class="container" >
                        <div class="container-table100" >
                        <div class="wrap-table100">
                            <div class="table100 ver1 m-b-110">
                                <table data-vertable="ver1">
                                  <thead>
                                   
                                    <tr class="row100 head">
                                        <th class="column100 column1" width="10%">No</th>
                                        <th class="column100 column2" width="10%">Course Name</th>
                                        <th class="column100 column3" width="10%">Certificate Name</th>
                                        <th class="column100 column4" width="10%">Certificate Level</th>
                                        <th class="column100 column4" width="10%">Duration</th>
                                        <th class="column100 column4" width="10%">Issued Date</th>
                                        <th class="column100 column8" width="15%">Action</th>
                                    </tr>
                                  </thead>
                                    <tbody>
                                        @if(count($employee_training)>0)
                                        @foreach($employee_training as $key=>$value)
                                        <tr class="row100">
                                              <td class="column100 column1" data-column="column1">{{++$key}}</td>
                                              <td class="column100 column2" data-column="column2">{{$value->course_name}}</td>
                                              <td class="column100 column3" data-column="column3">{{$value->certificate_name}}</td>
                                              <td class="column100 column4" data-column="column4">
                                                  <?php
                                                  if($value->certificate_level =="1")
                                                      echo "Beginner";
                                                  if($value->certificate_level =="2")
                                                      echo "Intermediate";
                                                  if($value->certificate_level =="3")
                                                      echo "Advanced";
                                                  ?>
                                              </td>
                                              <td class="column100 column4" data-column="column4">{{$value->course_offered_by}}</td>
                                              <td class="column100 column4" data-column="column4">{{$value->course_duration}}</td>
                                              <td class="column100 column8 actioncolumn" data-column="column8">
                                                  <button class="btn-sm edit_details" data-form="training_form" id="{{$value->id}}" type="button"><i class="fa fa-edit"></i></button>
                                                  <button class="btn-sm delete_details" data-form="training_form" data-formid="7" id="{{$value->id}}" type="button"><i class="fa fa-trash-o"></i></button>
                                              </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="row100">
                                              <td align="center" colspan="8" class="column100 column1" data-column="column1">No Records Found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                    
                    <div class="panel panel-info training_detail_form" style="margin-top:7px; display:none; ">
                        <div class="panel-heading" style="color:#fff; background-color: #5b75a6;">
                          <div class="panel-title">Experience Details </div>  
                        </div>
                         
                          <form id="training_form" data-parsley-validate >
                            <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
                            <input type="hidden" name="row_id" class="training_row" id="row_id"  value=""/>
                            <input type="hidden" name="form_name" id="form_name" value="training_details"/>
                                <div class="row" style="padding-top:35px;">
                                   <div class="col-md-12" >
                                      <div class="row">
                                          <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-lg-5 col-md-5"><span class="req">*</span>Course Name :</label>
                                                <div class="col-md-7">
                                                   <input type="text" class="form-control" name="course_name" id="course_name" required>
                                                </div>
                                            </div>
                                              
                                            <div class="form-group row">
                                              <label class="col-lg-5 col-md-5 " ><span class="req">*</span>Certificate Name:</label>
                                              <div class="col-md-7">
                                                  <input type="text" name="certificate_name" id="certificate_name" value="" class="form-control" required />
                                              </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-5 col-md-5 " ><span class="req">*</span>Certificate Level:</label>
                                                <div class="col-md-7 ">
                                                <select  name="certificate_level"  id="certificate_level" class="select2 form-control" required>
                                                    <option value="">--Please Select--</option>
                                                    <option value="1">Beginner</option>
                                                    <option value="2">Intermediate</option>
                                                    <option value="3">Advanced</option>
                                                </select>
                                                </div>
                                            </div>
                                              </div>
                                              <div class="col-md-6">
                                              <div class="form-group row">
                                                <label class="col-lg-5 col-md-5 " ><span class="req">*</span>Course Duration in (months):</label>
                                                <div class="col-md-7 ">
                                                <input  name="course_offered_by"  id="course_offered_by" class="form-control" required/>
                                                    
                                                </div>
                                            </div>
                                              
                                              
                                              <div class="form-group row">
                                                <label class="col-lg-5 col-md-5 " ><span class="req">*</span>Issue Date:</label>
                                                <div class="col-md-7 ">
                                                    <div class="input-group form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                <input type="text"  name="course_duration"  id="course_duration" class="form-control from_date" required/>
                                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                   </div>
                                                   
                                                </div>
                                            </div>

                                          </div>
                                      </div>

                                      </div>
                                      

                                      <div class="form-group text-center">
                          <button  type="button" class="btn save btn_save" data-form="7" id="save">Submit</button> &nbsp;&nbsp;&nbsp;
                          <button type="button"  class="btn btn-cancel cancel training_form_show" >Cancel</button>
                      </div>

                                 </div>
                          
                          
                    </form>
                    </div>
                    
                    
                </div>
                 
              </div>
            </div>
        </div>


         <div role="tabpanel" class="tab-pane fade" id="Section9">
            <div class="container">
              <div class="row">
                  
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-info visa_detail_table">
                        <div class="panel-heading">
                            <div class="panel-title">Visa and Immigration</div>
                            <div style="float:right; font-size: 80%; position: relative; top:-20px"><a class="show_form_btn" style="cursor: pointer;"><i class="glyphicon glyphicon-plus-sign visa_details"></i></a></div>
                        </div>
                        <div class="container" >
                        <div class="container-table100" >
                        <div class="wrap-table100">
                            <div class="table100 ver1 m-b-110">
                                <table data-vertable="ver1">
                                  <thead>
                                    <tr class="row100 head">
                                        <th class="column100 column1" width="10%">No</th>
                                        <th class="column100 column2" width="10%">Passport Number</th>
                                        <th class="column100 column3" width="10%">Passport Issue Date</th>
                                        <th class="column100 column4" width="10%">Passport Expiry Date</th>
                                        <th class="column100 column4" width="10%">Visa Number</th>
                                        <th class="column100 column4" width="10%">Visa Issue Date</th>
                                        <th class="column100 column4" width="10%">Visa Expiry Date</th>
                                        <th class="column100 column8" width="15%">Action</th>
                                    </tr>
                                  </thead>
                                    <tbody> 
                                        @if(count($employee_visa)>0)
                                        @foreach($employee_visa as $key =>$value)
                                        <tr class="row100">
                                              <td class="column100 column1" data-column="column1">{{++$key}}</td>
                                              <td class="column100 column2" data-column="column2">{{$value->passport_number}}</td>
                                              <td class="column100 column3" data-column="column3">{{$value->passport_issued_date}}</td>
                                              <td class="column100 column4" data-column="column4">{{$value->passport_expiry_date}}</td>
                                              <td class="column100 column4" data-column="column4">{{$value->visa_number}}</td>
                                              <td class="column100 column4" data-column="column4">{{$value->visa_issued_date}}</td>
                                              <td class="column100 column4" data-column="column4">{{$value->visa_expiry_date}}</td>
                                              <td class="column100 column8 actioncolumn" data-column="column8">
                                                  <button class="btn-sm edit_details" data-form="visa_form" id="{{$value->id}}" type="button"><i class="fa fa-edit"></i></button>
                                                  <button class="btn-sm delete_details" data-form="visa_form" data-formid="8" id="{{$value->id}}" type="button"><i class="fa fa-trash-o"></i></button>
                                              </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="row100">
                                              <td align="center" colspan="8" class="column100 column1" data-column="column1">No Records Found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                    
                    
                    <div class="panel panel-info visa_detail_form" style="margin-top:7px; display:none; ">
                        <div class="panel-heading" style="color:#fff; background-color: #5b75a6;">
                          <div class="panel-title">Experience Details </div>  
                        </div>
                          <!------------------------------------------------------>
                          <form id="visa_form" data-parsley-validate >
                            <input type="hidden" name="edit_id" id="edit_id"  value="{{$edit_id}}"/>
                            <input type="hidden" name="row_id" class="visa_row" id="row_id"  value=""/>
                            <input type="hidden" name="form_name" id="form_name" value="visa_details"/>
                                <div class="row" style="padding-top:35px;">
                                   <div class="col-md-12" >
                                      <div class="row">
                                          <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-lg-5 col-md-5"><span style="color:red;">*</span>Passport Number :</label>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control" name="passport_number" id="v_passport_number" required="" >
                                                     <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
                                                </div>
                                            </div>
                                              
                                            <div class="form-group row">
                                              <label class="col-lg-5 col-md-5 " >Passport Issued Date:</label>
                                              <div class="col-md-7">
                                                    <div class="input-group form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                        <input type="text" name="passport_issued_date" id="passport_issued_date" value="" class="form-control from_date"  />
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                   </div>
                                              </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-lg-5 col-md-5 " >Passport Expiry Date:</label>
                                                <div class="col-md-7 ">
                                                    <div class="input-group form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                    <input type="text"  name="passport_expiry_date"  id="v_passport_expiry_date" class="form-control from_date v_passport_expiry_date" />
                                                    
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                     </div>
                                                </div>
                                            </div>
                                              
                                              <div class="form-group row">
                                                <label class="col-lg-5 col-md-5 " >Visa Type Code:</label>
                                                <div class="col-md-7 ">
                                                <input  name="visa_type_code"  id="visa_type_code" class="form-control" />
                                                    
                                                </div>
                                            </div>
                                              </div>
                                              <div class="col-md-6">
                                              
                                            <div class="form-group row">
                                                <label class="col-lg-5 col-md-5 " >Visa Number:</label>
                                                <div class="col-md-7 ">
                                                <input type="text"  name="visa_number"  id="visa_number" class="form-control" />
                                                   
                                                </div>
                                            </div>
                                              
                                              
                                              <div class="form-group row">
                                                <label class="col-lg-5 col-md-5 " >Visa Country:</label>
                                                <div class="col-md-7 ">
                                                <select  type="text"  name="visa_country"  id="visa_country" class="select2 form-control" >
                                                    <option value="">-- Please Select --</option>
                                                    <option value="1">India</option>
                                                    <option value="2">Italy</option>
                                                    <option value="3">Japan</option>
                                                    <option value="4">UK</option>
                                                    <option value="5">USA</option>
                                                </select>
                                                   
                                                </div>
                                            </div>
                                              
                                            <div class="form-group row">
                                              <label class="col-lg-5 col-md-5 " >Visa Issued Date:</label>
                                              <div class="col-md-7 ">
                                                  <div class="input-group form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                              <input type="text"  name="visa_issued_date"  id="visa_issued_date" class="form-control from_date" />
                                               <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                   </div>

                                              </div>
                                          </div>
                                              
                                            <div class="form-group row">
                                              <label class="col-lg-5 col-md-5 " >Visa Expiry Date :</label>
                                              <div class="col-md-7 ">
                                                  <div class="input-group form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                              <input type="text"  name="visa_expiry_date"  id="visa_expiry_date" class="form-control from_date" />
                                               <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                   </div>

                                              </div>
                                          </div>

                                          </div>
                                      </div>

                                      </div>

                                    
                                   <div class="form-group text-center">
                          <button  type="button" class="btn save btn_save" data-form="8" id="save">Submit</button> 
                          <button type="button"  class="btn btn-cancel cancel visa_form_show" >Cancel</button>
                      </div>

                                 </div>
                         
                          
                    </form>
                    </div>
                    
                    
                </div>
                    
              </div>
            </div>
        </div>
    </div>
</div>




</div>
</body>

    <script>

        var edit_id = $("#edit_id").val();
        if(edit_id != '')
            $('#employee_number').attr('readonly',true);
        
    $(document).ready(function()
        {
		var test=0;
		$(document).on('change', '.email', function ()
     {
    var currentElement = $(this).val();
		  var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
 
    if (expr.test(currentElement)) {
      
    }
    else 
    {
        $('.email').val('');
        notyMsg("info","Enter valid e-mail address");
		
    }

	});
        var tabid = "{{  $tab_id }}";
        $('.'+tabid).trigger('click');
            var edit_id = $("#edit_id").val();
            if(edit_id == "")
            {
                $('li.menu').not('li.firsttab').click(function() {
                notyMsgs('info','please Save Official Details First!!! ');
                return false;
                });
                $('li.menu active').click(function() {
                return true;
                });
            }
            
            $(document).on('click','.cancel',function(){
              
                var url = "{{URL::to('viewprofile')}}";
                
                window.location.href=url;
            });
           
            function view_details(form_name,data)
            {
               
                var form_show = [];
                var table_show = [];
                console.log(data);
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
                    console.log(data);
                    $('#education_level').select2('val',[data['education_level']]);
                    $('#school_name').val(data['school_name']);
                    $('#school_board').val(data['school_board']);
                    
                    
                    
                    var dt1 = data['from_date'].split("-");
                    var y1 = dt1[0];
                    var m1 = dt1[1];
                    var d1 = dt1[2];
                    //var fr_date = m1+"/"+d1+"/"+y1;
                    var fr_date = d1+"-"+m1+"-"+y1;
                    
                    var dt2 = data['to_date'].split("-");
                    var y2 = dt2[0];
                    var m2 = dt2[1];
                    var d2 = dt2[2];
                    //var fr_date = m1+"/"+d1+"/"+y1;
                    var to_date = d2+"-"+m2+"-"+y2
                    
                    $('#from_date').val(fr_date);
                    
                    
                    
                    $('#to_date').val(to_date);
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
                    
                    var dt1 = data['from_date'].split("-");
                    var y1 = dt1[0];
                    var m1 = dt1[1];
                    var d1 = dt1[2];
                    //var fr_date = m1+"/"+d1+"/"+y1;
                    var fr_date = d1+"-"+m1+"-"+y1;
                    
                    var dt2 = data['to_date'].split("-");
                    var y2 = dt2[0];
                    var m2 = dt2[1];
                    var d2 = dt2[2];
                    //var fr_date = m1+"/"+d1+"/"+y1;
                    var to_date = d2+"-"+m2+"-"+y2
                    
                    $('#ex_from_date').val(fr_date);
                    $('#ex_to_date').val(to_date);
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
                    var dt2 = data['course_duration'].split("-");
                    var y2 = dt2[0];
                    var m2 = dt2[1];
                    var d2 = dt2[2];
                    var to_date = d2+"-"+m2+"-"+y2;
                    $('#course_duration').val(to_date);
                    $('.training_row').val(data['id']);
                    
                }
                else if(form_name == 'visa_form')
                {
                   
                    $('#v_passport_number').val(data['passport_number']);
                    
                    var dt1 = data['passport_issued_date'].split("-");
                    var y1 = dt1[0];
                    var m1 = dt1[1];
                    var d1 = dt1[2];
                    var passport_issued_date = d1+"-"+m1+"-"+y1;
                    
                    var dt2 = data['passport_expiry_date'].split("-");
                    var y2 = dt2[0];
                    var m2 = dt2[1];
                    var d2 = dt2[2];
                    var passport_expiry_date = d2+"-"+m2+"-"+y2;
                    
                    var dt3 = data['visa_issued_date'].split("-");
                    var y3 = dt3[0];
                    var m3 = dt3[1];
                    var d3 = dt3[2];
                    var visa_issued_date = d3+"-"+m3+"-"+y3;
                    
                    var dt4 = data['visa_expiry_date'].split("-");
                    var y4 = dt4[0];
                    var m4 = dt4[1];
                    var d4 = dt4[2];
                    var visa_expiry_date = d4+"-"+m4+"-"+y4;
                    
                    $('#passport_issued_date').val(passport_issued_date);
                    $('#certificate_level').select2('val',[data['certificate_level']]);
                    $('#v_passport_expiry_date').val(passport_expiry_date);
                    $('#visa_type_code').val(data['visa_type_code']);
                    $('#visa_number').val(data['visa_number']);
                    $('#visa_country').val(data['visa_country']).change();
                    $('#visa_issued_date').val(visa_issued_date);
                    $('#visa_expiry_date').val(visa_expiry_date);
                    $('.visa_row').val(data['id']);
                    
                }
                    
                $('.'+form_show[0][form_name]).show();
                $('.'+table_show[0][form_show[0][form_name]]).hide();
                
            }
            /****** edit function  ***/
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
            /********/
            
            /****** delete function  ***/
            $(document).on('click','.delete_details',function()
            {
                var edit_id = $(this).attr('id');
                var form_name = $(this).attr('data-form');
                var form_id = $(this).attr('data-formid');
                var tab_id = Number(form_id) + Number(1);  
                var editid = $("#edit_id").val();
                var dirurl = "{{ URL::to('editprofile') }}/"+editid+'/'+tab_id;
                
                //window.location.replace(url);
                var url="{{ URL::to('delete_information') }}/"+form_name+"/"+edit_id;
                $.get(url, function(data,status)
                {
                    if(data[0] != "")
                    {
                      window.location.replace(dirurl);  
                    }
                    else if(data == "")
                    {
                        
                    }
                });
               
            });
            /********/
            
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

           


               /**************** email validation start ***********/
     function ValidateEmail(email) {
            var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            return expr.test(email);
        };
        $(document).on('click','#save_off',function()
        {
            if (!ValidateEmail($("#email").val())) {
                $('.email_vali').attr('id',1);
            }
            else {
                $('.email_vali').attr('id',0);
            }
        });

        $(document).on('click','#save',function()
        {
            if (!ValidateEmail($("#personal_mail").val())) {
                $('.email_vali').attr('id',1);
            }
            else {
                $('.email_vali').attr('id',0);
            }
        });
    /**************** email validation end ***********/
    
                

            var dup_chk = true;
            function duplicate_validate()
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
                        console.log(response);
                        if(response == 1)
                        {
                            $('.dup_name').html('Mail-Id:'+mail_id+' Already Exists In the Table');
                            $('.dup_name').show();
                            //$("#mail_id").val('');
                            dup_chk = false;

                        }
                        else if(response == 0)
                        {
                            var html ="";
                                $('.dup_name').hide();
                            dup_chk = true;

                        }

                    },
                    error: function(xhr, resp, text)
                    {
                        console.log(xhr, resp, text);
                    }
                });
                }
                
                
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
                            $('.dup_name1').html('Employee Id:'+employee_id+' Already Exists In the Table');
                            $('.dup_name1').show();
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



            /**************** moblie number validation start ***********/
        $(document).on('keypress', '.work_telephone_number,#esi_no,#uan_no', function(ev){
            var regex = new RegExp("^[0-9.]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) {
                        return true;
                    }
                    ev.preventDefault();
                    return false;
        });

   



        $(document).on('keypress', '.personal_mobile', function(ev)
        {
           var regex = new RegExp("^[0-9.]+$");
                   var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                   if (regex.test(str)) {
                       return true;
                   }
                   ev.preventDefault();
                   return false;
       });

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
    
    $(".file_upload").change(function(){
        readURL(this);
    });


     $(document).on('keypress', '.mobile_no', function(ev){
        var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
    });

   
    /**************** moblie number validation end ***********/
    
        function change_date1()
        {
            $(".from_date").each(function() {
                 var dt2 = $(this).val().split("-");
                
                var d2 = dt2[0];
                var m2 = dt2[1];
                var y2 = dt2[2];
                var to_date = y2+"-"+m2+"-"+d2;
                $(this).val(to_date);
                
            });
            
        }
        
        function change_dates()
        {
            
            $(".from_date").each(function() 
            {
                var dt2 = $(this).val().split("-");
                if(dt2 != ''){
                var y2 = dt2[0];
                var m2 = dt2[1];
                var d2 = dt2[2];
                var to_date = d2+"-"+m2+"-"+y2;
                $(this).val(to_date);
                }
            });
            
        }
        var edit_id = $('.edit_id').val() ;
        if(edit_id != '')
        {
            change_dates();
        }

//        $(document).on('click','#save',function()
//        {
//            var from_array = ["official_form","personal_form","contact_form","salary_form","education_form","experience_form","skill_form","training_form","visa_form"];
//            var tab_array = ["1","2","3","4","5","6","7","8","9"];
//            var form_id = $(this).attr('data-form');
//            var edit_id = $("#edit_id").val();
//            
//            validationrule(from_array[form_id]);
//            var form = $('#'+from_array[form_id]);
//            var tab_id = Number(form_id) + Number(1);  
//            form.parsley().validate();
//            
//            if (form.parsley().isValid())
//            {
//                change_date1();
//                var url                     =   "{{url('employee/save')}}";
//                var red_url                 =   "{{url('createemployee')}}";
//                var btnval                  =   $(this).val();
//                var formdata                =   $('#'+from_array[form_id]).serialize();
//                duplicate_validate();
//                duplicate_validate1();
//                 /**************** email validation start ***********/
//                    var mail = $('.email_vali').attr('id');
//                    if(mail == 1){
//                    $('.email_vali').show();
//                    }else{
//                        $('.email_vali').hide();
//                    }
//                 /**************** email validation end ***********/
//               
//                if(dup_chk == true && mail != 1 && dup_chk1) 
//                {
//                var form_data = new FormData(document.getElementById(from_array[form_id]));
//               
//                $.ajax({
//                  url: "{{ url('employee/save')}}",
//                  type: "POST",
//                  data: form_data,
//                  enctype: 'multipart/form-data',
//                  processData: false,  // tell jQuery not to process the data
//                  contentType: false,   // tell jQuery not to set contentType
//                  async:true,
//                  xhr: function(){
//                      var xhr = $.ajaxSettings.xhr();
//                    if (xhr.upload) {
//                        xhr.upload.addEventListener('progress', function(event) {
//                                var percent = 0;
//                                var position = event.loaded || event.position;
//                                var total = event.total;
//                                if (event.lengthComputable) {
//                                        percent = Math.ceil(position / total * 100);
//                                }
//                                        //update progressbar
//
//                                }, true);
//                        }
//                        return xhr;
//
//                }
//                }).done(function(data,status)
//                {
//                    if(data['status'] == 1)
//                    {
//                        notyMsgs('info','Employee Details Saved Successfully');
//                        var url = "{{ URL::to('editprofile') }}/"+edit_id+'/'+tab_id;
//                        setTimeout(function()
//                        {
//                            window.location.replace(url);
//                        }, 1000);
//                    }
//                    else
//                    {
//                        $(".alert-success").hide();
//                        $(".alert-danger").fadeIn(800);
//
//                    }
//                }).fail(function(data,status)
//                {
//                       
//                        $(".alert-success").hide();
//                        $(".alert-danger").fadeIn(800);
//
//                });
//            }
//    }
//        });
        
    $(document).on('change', '#p_country', function () 
    {
        var country_id = $('#p_country').select2('val');
        // alert(country_id);
        $("#p_state").jCombo("{{ URL::to('jcomboformlogin?table=m_states_t:state_id:state_name') }}&parent=country_id="+country_id+ '&order_by=state_name asc',
        {selected_value:""});
        $("#p_city").find('option').not(':first').remove();
    });
        
    $(document).on('change', '#p_state', function () {

        var state_id = $('#p_state').select2('val');
        $("#p_city").jCombo("{{ URL::to('jcomboformlogin?table=m_cities_t:city_id:city_name') }}&parent=state_id="+state_id+ '&order_by=city_name asc',
        {selected_value:""});
    });
        
        
    $(document).on('change', '#c_country', function () 
    {
        var country_id = $('#c_country').select2('val');
        // alert(country_id);
        $("#c_state").jCombo("{{ URL::to('jcomboform?table=m_states_t:state_id:state_name') }}&parent=country_id="+country_id+ '&order_by=state_name asc',
        {selected_value:""});
        $("#c_city").find('option').not(':first').remove();
    });
        
        $(document).on('change', '#c_state', function () {

            var state_id = $('#c_state').select2('val');
            $("#c_city").jCombo("{{ URL::to('jcomboform?table=m_cities_t:city_id:city_name') }}&parent=state_id="+state_id+ '&order_by=city_name asc',
            {selected_value:""});
        }); 
        
        $('.from_date').datepicker({
            changeMonth: true,
            dateFormat: 'dd-mm-yy',
            changeYear: true, 
            yearRange: "1947:+2100"          
        });
       
        $(document).on('click','#save_off',function()
        {
           
            var from_array = ["official_form","personal_form","contact_form","salary_form","education_form","experience_form","skill_form","training_form","visa_form"];
            var form_id = $(this).attr('data-form');   
            validationrule(from_array[form_id]);
            var form = $('#'+from_array[form_id]);
            var formid = $('.'+from_array[form_id]);
            form.parsley().validate();
            
            if (form.parsley().isValid())
            {
                change_date1();
                var url                     =   "{{ url('employee/save') }}";
                var red_url                 =   "{{ url('createemployee') }}";
                var btnval                  =   $(this).val();
                var formdata                =   $('#'+from_array[form_id]).serialize();
                duplicate_validate();
                duplicate_validate1();
                    /**************** email validation start ***********/
                    var mail = $('.email_vali').attr('id');
                    if(mail == 1){
                    $('.email_vali').show();
                    }else{
                        $('.email_vali').hide();
                    }
                 /**************** email validation end ***********/
                 
                if(dup_chk == true && mail != 1 && dup_chk1)
                {
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
                        notyMsgs('info','Employee Details Saved Successfully');
                        var urls = "{{ URL::to('editprofile') }}/"+data['id']; 
                        setTimeout(function(){
                        window.location.replace(urls);
                                               }, 1500);
                        $('.'+from_array[form_id]).trigger('click');
                        
                       
                    }
                    else
                    {
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
       
        $(".select2").select2();
        $(".select2").css('width','100%');
        
        /*** Bank details  **/
        $(document).on('click','.bank_details',function()
        {
            $('.bank_details_table').hide();
            $('.bank_details_form').show();
            $('.bank_details_form').find('input:text').val(''); 
            $('.salary_row').val(''); 
            $('#pay_frequency,#account_type').select2('val',['']);
            
        });
            
        $(document).on('click','.bank_form_show',function()
        {
            $('.bank_details_form').hide();
            $('.bank_details_table').show();
        });
        /*** ***/


           /*Validation*/
    $(document).on('keypress','.percentage,.ctc,.salary', function(ev){
            var regex = new RegExp("^[0-9.]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) {
                        return true;
                    }
                    ev.preventDefault();
                    return false;
        });
        
        /***** Educational details ***/
        $(document).on('click','.education_details',function()
        {
            $('.education_details_table').hide();
            $('.education_details_form').show();
            $('.education_details_form').find('input:text').val(''); 
            $('.education_row').val(''); 
            $('#education_level').select2('val',['']);
        });
            
        $(document).on('click','.education_form_show',function()
        {

            $('.education_details_form').hide();
            $('.education_details_table').show();
        });
        /*** ***/
        
      
        $('.specify').hide();
        $('.course').hide();
        $(document).on('change','.education_level',function()
        {
            var edu_leave = $('#education_level').select2('val');
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
                $('.edu_school1').html('<span class="req">*</span>Name of the School :');
                $('.edu_school11').attr('name', 'school_name').attr('id','school_name');
                
                $('.edu_school2').html('<span class="req">*</span>Boards of Education :');
                $('.edu_school12').attr('name', 'school_name').attr('id','school_name');
                $('.specify').hide();
               
                $('#course').removeAttr('required');
                $('#other').removeAttr('required');
            }
        });
        /****      ******/
        
        
        /****** Experience Details ****/
        $(document).on('click','.experience_details',function()
        {
            $('.experience_details_table').hide();
            $('.experience_details_form').show();
            $('.experience_details_form').find('input:text').val('');   
            $('.experience_row').val('');   
        });
            
        $(document).on('click','.experience_form_show',function()
        {

            $('.experience_details_form').hide();
            $('.experience_details_table').show();
        });
        /***** *****/
        
          /*** Skill Details ***/
        $(document).on('click','.skill_details',function()
        {
            $('.skill_details_table').hide();
            $('.skill_details_form').show();
            $('.skill_details_form').find('input:text').val('');
            $('.competency_level').select2('val',['']);
            $('.skill_row').val('');
        });
            
        $(document).on('click','.skill_form_show',function()
        {

            $('.skill_details_form').hide();
            $('.skill_details_table').show();
        });
        /*** ***/
        
           /*** Skill Details ***/
         $(document).on('click','.training_details',function()
        {
            $('.training_detail_table').hide();
            $('.training_detail_form').show();
            $('.training_detail_form').find('input:text').val('');
            $('#certificate_level').select2('val',['']);
            $('.training_row').val('');
        });
            
        $(document).on('click','.training_form_show',function()
        {

            $('.training_detail_form').hide();
            $('.training_detail_table').show();
        });
        /*** ***/
        
        
         /*** Skill Details ***/
         $(document).on('click','.visa_details',function()
        {
            $('.visa_detail_table').hide();
            $('.visa_detail_form').show();
            $('.visa_detail_form').find('input:text').val('');
            $('#visa_country').select2('val',['']);
            $('.visa_row').val('');
        });
            
        $(document).on('click','.visa_form_show',function()
        {

            $('.visa_detail_form').hide();
            $('.visa_detail_table').show();
        });
        /*** ***/
        
        /**** edit_bank_details ***/
        $(document).on('.edit_bank_details','click',function(){
           var edit_id = $('.edit_bank_details').attr('id');
           
        });
        /****/

        //$('.date_of_joining,.date_of_birth,.children_dob1,.children_dob2').datepicker({format: 'yyyy-mm-dd', minDate:0, autoClose: true});

           $(document).on('click','.file_choose',function(e)
            {
                $(".file_upload").trigger( "click" );
               
            });

            $(document).on('change','#department',function()
            {
                var department_id=$(this).val();
                var condition = ' department_id IN('+department_id+')';
                
                $("#job_title").jCombo("{{ URL::to('jcomboformrerule?table=m_job_title:job_title_id:job_title_name') }}&order_by=job_title_name asc"+'&parent='+condition,
                    {selected_value:""});
            });

            $(document).on('change','#job_title ',function()
            {
                var job_title_id=$(this).val();
                if(job_title_id != ''){
                    $("#position").jCombo("{{ URL::to('jcomboform?table=m_position:position_id:position') }}&parent=job_title_id="+job_title_id+"&order_by=position asc",
                    {parent: '#job_title_id',selected_value:''});
                }
            });

            /*** Contact Tab ***/
            $(document).on('click','#same_address',function()
            {
                if($(this).is(':checked')){
                    $('#c_address').val($('#p_address').val());
                    $('#c_country').val($('#p_country').val()).change();
                    setTimeout(function(){ 
                    $('#c_state').val($('#p_state').val()).change();
                    }, 500);
                    setTimeout(function(){ 
                    $('#c_city').val($('#p_city').val()).change();
                        }, 800);
                    $('#c_pincode').val($('#p_pincode').val());
                }
                else
                {
                    $('#c_address,#c_country,#c_state,#c_city,#c_pincode').val('').change();
                }


            });
        


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
                                    <td><button class="btn-xs btn-danger remove_field">-</button>\n\
                    </td></tr>');
                    x++; //text box increment
                }
                if(x == 10)
                {
                    alert("Only 10 Can Add");
                }


            });

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
                                <input type="text" name="language[]" style="width:225px;" data-provide="typeahead" autocomplete="off"  class="lang lang-' + i + ' form-control" >\n\
                                <div class="form-group"><div class="l-checkbox"><div class="c-checkbox">\n\
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


            $(document).on("click", ".remove_field", function (e) { //user click on remove text
                e.preventDefault();
                $(this).closest('tr').remove();
                x--;
            });

            $(wrapper).on('click', '.remove_button', function (e) { //Once remove button is clicked
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x--; //Decrement field counter
            });


    });

//$(function()
//{
  
  
 // $('.date_of_birth').datepicker({
  //  changeMonth: true,
  //    dateFormat: data,
 //     changeYear: true,   
  //    
 // });
  //$('.date_of_joining').datepicker({
  //  changeMonth: true,
 //     dateFormat: data,
 //     changeYear: true,   
      
 // });
//});
    </script>
@include('layouts.php_js_validation')
@endsection
