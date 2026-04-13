<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Missedcallreport extends Model
{
    protected $table ='app_missedcall_rpt_t';
	protected $primaryKey ='missedcall_rpt_id';
	protected $fillable=['employee_id','tp_date','area','actual_doctor','doctor_visited','doctor_missed','created_at','updated_at','created_by','last_updated_by','location_id','organization_id','company_id','parent_id'];
}
