<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scheduleexamhdr extends Model
{
    protected $table='t_schedule_exam_hdr_tbl';
    protected $primaryKey='schedule_exam_hdr_id';
    protected $fillable=['schedule_date','schedule_type','remarks','topic_id','start_time','end_time','remarks'];
}
