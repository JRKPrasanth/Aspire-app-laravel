<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class departmentTopic extends Model
{
    protected $table='t_department_topic_tbl';
	protected $primaryKey='department_topic_id';
	protected $fillable =['topic_id','department_id','remarks','active','created_by','last_updated_by','created_at','updated_at','organization_id','location_id','company_id'];
}
