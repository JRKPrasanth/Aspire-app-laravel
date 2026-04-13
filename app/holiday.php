<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class holiday extends Model
{
       protected $table='hr_holiday_t';
      protected $primaryKey='holiday_id';
      protected $fillable =['holiday_name','date','company','location','active'];
}
