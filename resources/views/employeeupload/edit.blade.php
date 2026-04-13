@extends('layouts.header')
@section('content')
<h3 class="text-danger">
	<?php if($source=="create") { ?>
	Upload Employee Update
<?php } else { ?>
	Upload Employee Update
	<?php } ?>
</h3>
@include('layouts.breadcrumb')




	
	<!------------------------- breadcrumbs start here --------------------------->

	<!---------------------------------------------------------------------------->
	
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">

            @if($source == "create")
            <form method="post" action="{{ url('employeeuploadupdate') }}" data-parsley-validate>
            @else
            <form method="post" action="{{ url('employeeupdateuploadupdate') }}" data-parsley-validate>
            @endif

            {{ csrf_field() }}
            <input type="hidden" id="edit_id" name="edit_id" value="{{ $edit_id }}" />

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Employee Information</h5>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <?php

                                    $label = ['Batch No','Emp Number','Prefix','First Name','Last Name','Birth Date','Email','Company','Location','Department','Reporting Manger','Job Title','Position','Joining Date','Esi Number','Esi Dispensary','Pf Number','PF Date','UAN Number','Mobile Number','Alternatice Mobile','Employee Type','Biometric Number','Group Type','Area','Casual Leave','Sick Leave','Earn Leave','Gender','Marital Status','Nationality','Birth Date Other','Age','Mother Tongue','Religion','Language1'
           ,'Read1','Write1','Language2','Read2','Write2','Language3','Read3','Write3','Blood Group','Id Type 1','Id Number 1','Id Type 2','Id Number 2','Father Name'
           ,'Mother Name','Spouse Name','Spouse Dob','Children Number','Child1name','Child1dob','Child2name','Child2dob','Permanent Address',
           'Perm Country','Perm State','Perm City','Perm Pincode','Current Address','Curr Country','Curr State','Curr City','Curr Pincode','Emergency Contact','Emergency Relation Type','Emergency Address','Emergency Number',
           'Account Type','Bank Name','Branch Name','Ifsc Code','Acc Holder Name','Accunt Number','Education Level1','Institutionname1','Course Board1','Fromduration1','Toduration1','Percentage1','Education Level2','Institutionname2','Course Board2','Fromduration2','To Duration2','Percentage2','Education Level3','Institution3','Course Board3','From Duration3','To Duration3','Percentage3','Organization Name1','Website1',
           'Designation1','Ctc1','Expfrom1','Expto1','Organization2','Website2','Designation2','Ctc2',
           'Expfrom2','Expto2','Organization3','Website3','Designation3','Ctc3','Expfrom3','Expto3','Skills1','Version1','Competency1','Skills2','Version2','Competency2','Skills3','Version3','Competency3']; 
        
           $field_name = ['batch_no','emp_number','prefix','first_name','last_name','birth_date','email','company','location','department','reporting_manger'
           ,'job_title','position','joining_date','esi_number','esi_dispensary','pf_number','pf_date','uan_no','mobile_number','alternative_telephone_number','employee_type','biometric_empno','group_type','med_rep_area','c_l','s_l','e_l','gender','marital_status','nationality','birth_date_other','age','mother_tongue','religion','language1','read1','write1','language2','read2','write2','language3','read3','write3','blood_group','id_type_1','id_number_1','id_type_2','id_number_2','father_name','mother_name','spouse_name','spouse_dob','children_number','child1name','child1dob','child2name','child2dob','permanent_address','perm_country','perm_state','perm_city','perm_pincode','current_address','curr_country','curr_state','curr_city','curr_pincode','emergency_contact','emergency_relationtype',
           'emergency_address','emergency_number','account_type','bank_name','branch_name','ifsc_code','acc_holder_name','accunt_number','education_level1','institutionname1','course_board1','fromduration1','toduration1','percentage1','education_level2','institutionname2','course_board2','fromduration2','to_duration2','percentage2','education_level3','institution3','course_board3','from_duration3','to_duration3','percentage3','organization_name1','website1','designation1',
           'ctc1','expfrom1','expto1','organization2','website2','designation2','ctc2','expfrom2','expto2','organization3',
           'website3','designation3','ctc3','expfrom3','expto3','skills1','version1',
           'competency1','skills2','version2','competency2','skills3','version3','competency3'];
           
            $titile_info = ["Office Details", "Personal And Contact Details", "Salary Bank And Education Details", "Experience And Skills Details"];
            
                        for ($i = 0; $i < 4; $i++) {
                            if ($i == 0) { $k = 0; $l = 30; }
                            else if ($i == 1) { $k = 31; $l = 61; }
                            else if ($i == 2) { $k = 62; $l = 90; }
                            else if ($i == 3) { $k = 91; $l = 122; }

                            for ($j = $k; $j <= $l; $j++) {
                        ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="{{ $field_name[$j] }}" class="form-label">{{ $label[$j] }}:</label>
                                @if($j != 58 && $j != 63 && $j != 70)
                                <input type="text"
                                       @if($j == 0) readonly @endif
                                       id="{{ $field_name[$j] }}"
                                       name="{{ $field_name[$j] }}"
                                       value="{{ $details[$field_name[$j]] }}"
                                       class="form-control" />
                                @else
                                <textarea id="{{ $field_name[$j] }}"
                                          name="{{ $field_name[$j] }}"
                                          class="form-control">{{ $details[$field_name[$j]] }}</textarea>
                                @endif
                            </div>
                        </div>
                        <?php } ?>

                        @if($l == 122)
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="batch_comments" class="form-label">Batch Comments:</label>
                                <textarea readonly name="batch_comments" class="form-control" rows="2">{{ $details['batch_comments'] }}</textarea>
                            </div>
                        </div>
                        @endif

                        <?php } ?>
                    </div>

                    <input type="hidden" name="batch_status" class="batch_status" value="UPLOADED" />
                </div>

                <div class="card-footer bg-light text-center">
                    <button type="submit" class="btn btn-success px-4 me-2">Save</button>
                    @if($source == "create")
                    <a href="{{ url('employeeupload') }}" class="btn btn-secondary px-4">Cancel</a>
                    @else
                    <a href="{{ url('employeeupdateupload') }}" class="btn btn-secondary px-4">Cancel</a>
                    @endif
                </div>
            </div>
        </form>

        </div>
    </div>
</div>
	
@endsection
@push('scripts')

	<script>
		
$(document).ready(function(){
$('.save').click(function(){
     notyMsgs('success','Employee Details Updated Successfully');
});

	});
		
	</script>

@endpush
