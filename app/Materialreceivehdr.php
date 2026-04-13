<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materialreceivehdr extends Model
{
    protected $table='w_materialreceive_hdr_t';
    protected $primaryKey='w_materialreceive_hdr_id';
      protected $fillable=['w_materialreceive_hdr_id','w_jobs_hdr_id','product_id','uom_code_id','mtl_issue_date','organization_id','job_qty','source'];
    public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
