<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qualityindentlines extends Model
{
   protected $table='w_quality_indent_lines_t';
    protected $primaryKey='quality_indent_lines_id';
    protected $foreignKey='quality_indent_hdr_id';
    protected $fillable=['line_no','product_id','uom_code_id','qty','comments'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
