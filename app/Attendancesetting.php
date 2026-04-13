<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendancesetting extends Model
{
    protected $table='hr_attendance_settings_t';
    protected $primaryKey='attendance_settings_id';
    protected $fillable =['attendance_rules','rules_data','department_name','ot_formula','min_ot','max_ot'];
}
