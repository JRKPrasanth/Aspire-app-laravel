<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leavebalance extends Model
{
      protected $table='Leave_balance_tbl';
      protected $primaryKey='leave_balance_id';
      protected $fillable =['employee_id','causal_leave','earn_leave','sick_leave','comp_off_leave','remarks','ocl','oel'];
}
