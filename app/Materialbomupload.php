<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materialbomupload extends Model
{
	public $table='w_bomupload_t';
	public $primaryKey='bom_upload_id';
	public $foreignKey='';	
	public $fillable =['bom_product','bom_uom_code','ratio','bom_remarks','component_product','component_uom_code','component_qty','process','process_level','process_name','machine','comments','batch_status','batch_name','batch_date','batch_comments','company_id','organization_id','created_by','created_at','last_updated_by','updated_at','location_id'];
	public function getTableColumns() 
	{
	    return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
	}
}
