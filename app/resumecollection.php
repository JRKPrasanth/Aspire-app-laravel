<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class resumecollection extends Model
{
    protected $table='add_resume';
    protected $primaryKey='resume_id';
    protected $fillable =['name_of_the_candidate','email','mobile_no','gender','qualificaition','skills','previous_company','location','marital_status','current_salary','expected_salary','current_position','years_of_experience','resume_upload','interview_date','last_interview_date','select_status','schedule_status','remarks','address','current_company',
        'current_company_exp','notice_period','others','exp_level','city'];
    
    public function getTableColumns() 
    {
       return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
