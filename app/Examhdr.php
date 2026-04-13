<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Examhdr extends Model
{
    protected $table='t_exam_hdr_tbl';
    protected $primaryKey='exam_hdr_id';
    protected $fillable=['schedule_exam_line_id','exam_date','employee_id','remarks','topic_id','start_time','end_time','exam_start_time','exam_end_time','total_questions',
    'total_answered_questions','total_correct_answers','percentage','result','reference_id','source','location_id','company_id','created_by'];
}
