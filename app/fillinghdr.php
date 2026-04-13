<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class fillinghdr extends Model
{
  protected $table='w_jobcard_hdr_t';
  	protected $primaryKey='fillings_lines_id';
  	public $foreignKey='w_jobs_hdr_id';
	protected $fillable =['job_no','job_date', 'batch_no','remarks','organization_id','job_completion_date','job_process','machine_capacity','machine_hdr_id','job_assigned_to'];
}
