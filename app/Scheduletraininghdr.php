<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scheduletraininghdr extends Model
{
    protected $table='t_schedule_training_hdr_tbl';
    protected $primaryKey='schedule_training_hdr_id';
    protected $fillable=['schedule_date','schedule_type','schedule_status','remarks','topic_id','start_time','start_time','trainer_type','trainer_name','trainer_mail','need_exam','remarks','exttrainer_name'];
}
