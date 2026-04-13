<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class topic extends Model
{
    protected $table='t_topic_tbl';
	protected $primaryKey='topic_id';
	protected $fillable =['topic_name','remarks','active','created_by','last_updated_by','created_at','updated_at','organization_id','location_id','company_id'];
}
