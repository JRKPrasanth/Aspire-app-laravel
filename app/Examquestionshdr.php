<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Examquestionshdr extends Model
{
    protected $table='t_exam_questions_hdr_tbl';
    protected $primaryKey='exam_questions_hdr_id';
    protected $fillable=['topic_id','department_topic_id','department_id'];
}
