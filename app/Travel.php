<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    protected $table='hr_travel_amount_tbl';
      protected $primaryKey='travel_id';
      protected $fillable =['employee_id','group_id','travel_date','amount','active','description'];
}
