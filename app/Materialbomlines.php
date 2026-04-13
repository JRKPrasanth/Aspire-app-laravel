<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materialbomlines extends Model
{
   public $table='m_material_bom_lines_t';
	public $primaryKey='material_bom_line_id';
	public $foreignKey='material_bom_hdr_id';
	public $fillable =['material_bom_hdr_id','component_product_id','component_uom_code_id','component_qty','component_type','comments'];
	public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
