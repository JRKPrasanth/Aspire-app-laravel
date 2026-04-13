<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employeeexperience extends Model
{
    protected $table='hr_emp_experience';
    protected $primaryKey='id';
    protected $fillable=['organization_name','organization_website','designation','ctc','from_date','to_date','reason_leaving','referer_name','referer_contact',
   'referer_email','files','experience_type'];
}
