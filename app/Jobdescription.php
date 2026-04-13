<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobdescription extends Model
{
    protected $table='hr_job_description';
    protected $primaryKey='description_id';
    protected $fillable =['description_name','department','job_title','reqired_skills','months','year','role','min_salary','max_salary','min_experience','max_experience','interview_process','team_leads_id','approve_status','no_of_persons','last_interview_date','select_status','schedule_status','remarks','address','current_company','current_company_exp','notice_period','others','exp_level','organization_id','company_id','location_id','created_by','last_updated_by','desc_id','active','reporting_id','position_id'];
}
