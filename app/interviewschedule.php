<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class interviewschedule extends Model
{
    protected $table='hr_schedule_interview';
    protected $primaryKey='interview_id';
    protected $fillable =['name_of_the_candidate','job_description_name','interview_date','date','last_interview_date','remarks','schedule_status','interview_process','date_of_joining','salary_annum','offer_status','acceptance_status','location_id','employee_id','select_candidate','employee_status','emp_condition','approve_status','upload_download'];
}
    