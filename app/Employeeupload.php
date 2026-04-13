<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employeeupload extends Model
{
    protected $table='hr_employee_int_t';
    protected $primaryKey='hr_employee_id';
    protected $fillable=['batch_no','emp_number','prefix','first_name','last_name','birth_date','email','company','location','department','reporting_manger'
           ,'job_title','position','group_type','med_rep_area','employee_type','c_l','s_l','e_l','joining_date','esi_number','esi_number','mobile_number','gender','marital_status','nationality','birth_date_other','age','mother_tongue','religion','language1','read1','write1','language2','read2','write2','language3','read3','write3','blood_group','id_type_1','id_number_1','id_type_2','id_number_2','father_name','mother_name','spouse_name','spouse_dob','children_number','child1name','child1dob','child2name','child2dob','permanent_address','perm_country','perm_state','perm_city','perm_pincode','current_address','curr_country','curr_state','curr_city','curr_pincode','emergency_contact','emergency_relationtype',
           'emergency_address','emergency_number','account_type','bank_name','branch_name','ifsc_code','acc_holder_name','accunt_number','education_level1','institutionname1','course_board1','fromduration1','toduration1','percentage1','education_level2','institutionname2','course_board2','fromduration2','to_duration2','percentage2','education_level3','institution3','course_board3','from_duration3','to_duration3','percentage3','organization_name1','website1','designation1',
           'ctc1','expfrom1','expto1','organization2','website2','designation2','ctc2','expfrom2','expto2','organization3','website3','designation3','ctc3','expfrom3','expto3','skills1','version1',
           'competency1','skills2','version2','competency2','skills3','version3','competency3','batch_status','batch_comments','employee_type','pay_frequency','certificate_level','visa_country','personal_mail']; 
}
