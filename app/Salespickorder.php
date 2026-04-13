<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salespickorder extends Model
{
    protected $table='s_pickrelease_hdr_t';
    protected $primaryKey='so_pickrelease_hdr_id';
	protected $fillable =['release_source','release_date','release_status','pricelist_id','freight_carrier_id','location_id','organization_id','deliver_to_location','bill_to_customerid','ship_to_customerid','preparer_id','prepare_date','bill_to_address_id','ship_to_address_id','remarks','created_by','created_at','last_updated_by','updated_at','company_id'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
