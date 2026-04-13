<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scheduletraininglines extends Model
{
    protected $table='t_schedule_training_lines_tbl';
    protected $primaryKey='schedule_training_line_id';
    protected $foreignKey='schedule_training_hdr_id';
    protected $fillable=['schedule_training_hdr_id','employee_id'];
}
