<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scheduleexamlines extends Model
{
    protected $table='t_schedule_exam_lines_tbl';
    protected $primaryKey='schedule_exam_line_id';
    protected $foreignKey='schedule_exam_hdr_id';
    protected $fillable=['schedule_exam_hdr_id','employee_id','results'];
}
