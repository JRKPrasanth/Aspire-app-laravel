<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productionindentlines extends Model
{
 protected $table='w_requisition_indent_lines_t';
    protected $primaryKey='w_requisition_indent_line_id';
    protected $foreignKey='w_requisition_indent_hdr_id';
	protected $fillable =['w_requisition_indent_line_id','product_id','product_description','uom_code_id','qty','need_by_date','organization_id'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
