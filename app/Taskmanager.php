<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taskmanager extends Model
{
    protected $table ='a_taskmanager_t';
	protected $primaryKey ='taskmanager_id';
	protected $fillable=['department','assigned_to','start_date','status','end_date','department_lead','task','admin','description','created_at','created_by','updated_at','last_updated_by','location_id','organization_id','company_id'];
}
