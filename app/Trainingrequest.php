<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trainingrequest extends Model
{
    protected $table='t_training_request_tbl';
	protected $primaryKey='training_request_id';
	protected $fillable =['request_type','department_topic_id','department_id','topic_id','employee_id','remarks','active','reject_reason','approve_status','created_by','last_updated_by','created_at','updated_at','organization_id','location_id','company_id'];
}
