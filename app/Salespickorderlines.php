<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salespickorderlines extends Model
{
    protected $table='s_pickrelease_lines_t';
    protected $primaryKey='so_pickrelease_line_id';
	protected $foreignKey='so_pickrelease_hdr_id';
	protected $fillable =['line_no','ar_sales_hdr_id','	ar_sales_line_id','product_id','uom_code_id','so_qty','release_qty','picked_qty','qoh','comments','location_id','company_id','organization_id','created_by','created_at','last_updated_by','updated_at'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
