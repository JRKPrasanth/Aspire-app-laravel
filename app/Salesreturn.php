<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesreturn extends Model
{
    protected $table='so_rma_hdr_t';
    protected $primaryKey='so_rma_hdr_id';
	protected $fillable =['so_rma_hdr_id','rma_ref_no','return_date','return_status','return_source','customer_id','bill_to_address_id','ship_to_address_id','reference_no','remarks','reference_source_id','organization_id','discount_amount'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
