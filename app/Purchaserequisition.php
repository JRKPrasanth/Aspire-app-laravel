<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaserequisition extends Model
{
    protected $table='p_requisition_hdr_t';
    protected $primaryKey='requisition_hdr_id';
	protected $fillable =['requisition_hdr_id','requisition_no','requisition_date','requisition_status','requestor_id','project_id','organization_id','remarks'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
