<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employeecontact extends Model
{
    protected $table='hr_emp_contact';
     protected $primaryKey='id';
    protected $fillable=['employee_id','personal_email','permanent_street_address','permanent_country','permanent_state','permanent_city','permanent_postal_code','current_street_address','current_country',
                    'current_state','current_city','current_postal_code','emergency_contacts','same_address',
                    'driving_licence_number','driving_licence_expiry_date','id_name','id_number','id_name1','id_number1','vehicle_type','vehicle_number','father_name','mother_name','spouse_name','spouse_dob','no_of_children','children_name1','children_dob1','children_dob2','aadhar_number','pan_number','permanent_locality','current_locality','current_street','current_flat_no','permanent_street','permanent_flat_no'];
}
