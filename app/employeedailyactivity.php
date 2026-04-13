<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employeedailyactivity extends Model
{
  protected $table = 'hr_emp_daily_activity_t';
	protected $primaryKey = 'emp_daily_activity_id';
protected $fillable=['employee_id','description','activity_date','hour','remarks'];
}
