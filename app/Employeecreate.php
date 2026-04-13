<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employeecreate extends Model
{

    protected $table='hr_employee_t';
    protected $primaryKey='employee_id';
    protected $fillable=['zone_id','s_l','e_l','c_l','ot_formula','reporting_manager1','prefix','employee_number','first_name','last_name','email','company_id','organization','location_id','department','reporting_manager','med_rep_area',
                    'job_title','position','employment_status','date_of_joining','esi_no','pf_no','uan_no','active','med_rep_area','work_telephone_number','alternative_telephone_number','biometric_empno','employee_type','photo','group_type','esi_dispensary','pf_date'];
    
    
    public function getTableColumns() 
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
     
    public function personal()
    {
        return $this->hasOne('App\Employeedepartment','department_id');
    }
     

}
