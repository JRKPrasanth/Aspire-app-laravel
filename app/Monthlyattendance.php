<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Monthlyattendance extends Model
{
    protected $table='hr_monthly_attendance';
    protected $primaryKey='monthly_atten_id';
    protected $fillable =['employee_id','month','no_of_days','no_of_present_days','ot_hours','loan_deduction','status','start_date','end_date','year','c_l','s_l','e_l'];
}
