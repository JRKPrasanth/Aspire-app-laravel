<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materialbom extends Model
{
   public $table='m_material_bom_hdr_t';
	public $primaryKey='material_bom_hdr_id';
	public $foreignKey='';	
	public $fillable =['material_bom_hdr_id','bom_name','assembly_product_id','uom_code_id','company_id','process','start_date','end_date','project_id','remarks','savestatus','approver_id'];
	public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
