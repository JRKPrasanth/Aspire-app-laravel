<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesreturnlines extends Model
{
    protected $table='so_rma_lines_t';
    protected $primaryKey='so_rma_line_id';
	protected $foreignKey='so_rma_hdr_id';
	protected $fillable =['so_rma_line_id','so_rma_hdr_id','line_no','product_id','uom_code_id','invoice_qty','return_qty','returned_qty','reason_code','reason_comments','line_return_status','batch_number','dispatched_qty','issue_qty','returnedqty','returnqty'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
