<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employeetraining extends Model
{
    protected $table='hr_emp_training_certification';
    protected $primaryKey='id';
    protected $fillable=['course_name','certificate_level','course_offered_by','course_duration',
        'description','certificate_name','issue_date','files'];
}
