<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employeeadvance extends Model
{
    protected $table='hr_employee_advance_t';
    protected $primaryKey='advance_id';
    protected $fillable = ['employee_id','advance_date','effective_date','mode','amount','emi','advance_reason','forwarded_id','loan_document','advance_from','emi_amount'];
}
