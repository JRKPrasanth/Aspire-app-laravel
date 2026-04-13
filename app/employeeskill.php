<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employeeskill extends Model
{
    protected $table='hr_emp_skills';
    protected $primaryKey='id';
    protected $fillable=['skill','version','competency_level','skill_last_used_year','files'];
}
