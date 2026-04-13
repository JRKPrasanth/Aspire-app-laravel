<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employeeallowance extends Model
{
   protected $table='m_allowance_tbl';
    protected $primaryKey='allowance_id';
    protected $fillable = ['allowance_id','allowance_name','employee_type','active','position_id','grade_id'];
}
