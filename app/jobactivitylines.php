<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobactivitylines extends Model
{
   protected $table ='w_jobactivity_lines_t';
	protected $primaryKey ='job_activity_line_id';
         protected $foreignKey='job_activity_hdr_id';
	protected $fillable=['activity_name','product','machine','batch','qty','start_datetime','end_datetime','duration','created_at','created_by','updated_at','updated_by','company_id','location_id'];
}




