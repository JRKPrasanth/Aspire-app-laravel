<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employeeeducation extends Model
{
    protected $table='hr_emp_education';
    protected $primaryKey='id';
    protected $fillable=['education_level','other','institution_name','school_name','school_board','university_name','course','from_date','to_date',
                    'percentage','files'];
}
