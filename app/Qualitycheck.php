<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qualitycheck extends Model
{
    protected $table='i_quality_spec_trx_hdr_t';
    protected $primaryKey='quality_spec_trx_hdr_id';
    protected $fillable=['product_id','job_hdr_id','trx_date','trx_status'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
	
}
