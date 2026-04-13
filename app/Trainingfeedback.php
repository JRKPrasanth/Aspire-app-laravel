<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trainingfeedback extends Model
{
    protected $table='t_training_feedback';
    protected $primaryKey='feedback_id';
    protected $fillable=['employee_id','topic_id','topic_name','feedback_title','feedback','comment','feedback_date','created_at','created_by','location_id','organization_id','company_id','last_updated_by','updated_at','status'];
}
