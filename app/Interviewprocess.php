<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interviewprocess extends Model
{
    protected $table='m_interview_steps';
    protected $primaryKey='interview_steps_id';
    protected $fillable =['interview_process','remarks','interview_date','date','last_interview_date','remarks','schedule_status','interview_process','date_of_joining','salary_annum','offer_status','acceptance_status','location_id','employee_id','select_candidate','employee_status','emp_condition','approve_status','upload_download','active','company_id','organization_id','created_at','created_by','updated_at','last_updated_by'];
}
