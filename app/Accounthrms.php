<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accounthrms extends Model
{
   protected $table='f_hr_account_allowance_setting_t';
    protected $primaryKey='account_allowance_setting_id';
    protected $fillable = ['department_id'];
}
