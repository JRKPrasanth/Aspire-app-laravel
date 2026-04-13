<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Casualleave extends Model
{
    protected $table='hr_casual_leave_t';
    protected $primaryKey='casual_leave_id';
    protected $fillable = ['org','year', 'casual_leave','sick_leave','earn_leave','employee_type'];
}
