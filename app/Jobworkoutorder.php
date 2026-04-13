<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobworkoutorder extends Model
{
    protected $table='w_jobworkoutorder_hdr_t';
    protected $primaryKey='jobworkoutorder_hdr_id';
	protected $fillable =['joboutorder_no','subcontract_supplier_id','return_date','joboutorder_status','remarks'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
