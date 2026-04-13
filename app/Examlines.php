<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Examlines extends Model
{
    protected $table='t_exam_lines_tbl';
    protected $primaryKey='exam_line_id';
    protected $foreignKey='exam_hdr_id';
    protected $fillable=['exam_hdr_id','exam_question_type','exam_questions_line_id','answer','status','location_id','company_id','created_by'];
}
