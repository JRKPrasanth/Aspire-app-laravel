<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobactivity extends Model
{
    protected $table='w_jobactivity_hdr_t';
    protected $primaryKey='job_activity_id';
    protected $fillable=['emp_id','type','remarks','created_at','created_by'
						,'updated_at','last_updated_by','company_id','location_id','organization_id'];
						public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}

