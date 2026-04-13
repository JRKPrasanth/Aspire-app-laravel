<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employeepersonal extends Model
{
    protected $table='hr_emp_personal';
    protected $primaryKey='id';
    protected $fillable=['employee_id','gender','marital_status','nationality','mother_tongue','religion','language','date_of_birth','age',
                    'blood_group','personal_mail','personal_mobile','passport_number','passport_expiry_date','mother_tongue',
                    'driving_licence_number','driving_licence_expiry_date','id_name','id_number','id_name1','id_number1','vehicle_type','vehicle_number','father_name','mother_name','spouse_name','spouse_dob','no_of_children','children_name1','children_dob1','children_dob2','aadhar_number','pan_number','father_aadhar_number','mother_aadhar_number','spouce_aadhar_number'];
    
  
    
}

