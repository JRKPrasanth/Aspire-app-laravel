<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Misviewers extends Model
{
    	protected $table = 'sd_tracker_t';
		protected $primaryKey = 'id';
    	protected $fillable =['emp_id','date','date_time','url','created_at','organization_id','updated_at'];

}