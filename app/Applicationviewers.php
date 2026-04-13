<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicationviewers extends Model
{
    	protected $table = 'hr_tracker_t';
		protected $primaryKey = 'id';
    	protected $fillable =['emp_id','date','date_time','type','url','created_at','organization_id','updated_at'];

}