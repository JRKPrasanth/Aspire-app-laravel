<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobtitle extends Model
{
    protected $table='m_job_title';
    protected $primaryKey='job_title_id';
    protected $fillable=['department_id','job_title_name','job_description','organization_id','company_id','active','last_updated_by','created_by'];
}
