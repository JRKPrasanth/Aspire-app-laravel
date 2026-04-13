<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Examquestionslines extends Model
{
    protected $table='t_exam_questions_lines_tbl';
    protected $primaryKey='exam_questions_line_id';
    protected $foreignKey='exam_questions_hdr_id';
    protected $fillable=['exam_questions_hdr_id','qtype','qusetion','option1','option2','option3','option4','answer','text_answer','remarks'];


}