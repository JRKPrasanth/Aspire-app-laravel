<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobcard extends Model
{
  protected $table='w_jobcard_hdr_t';
    protected $primaryKey='w_jobs_hdr_id';
    protected $fillable = [
          'job_no','job_date', 'batch_no','remarks','organization_id','job_completion_date','job_qty','hour','material_issue_req'
      ];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
   }
