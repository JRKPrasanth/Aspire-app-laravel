<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispatchlines extends Model
{
   protected $table='s_dispatch_lines_t';
	protected $primaryKey='so_dispatch_line_id';
	protected $foreignKey='so_dispatch_hdr_id';
	protected $fillable=['so_dispatch_line_id','so_dispatch_hdr_id','line_no','product_id','uom_code_id','so_qty','dispatch_qty','comments','sototqty','sowise_qty','soorder_id'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
