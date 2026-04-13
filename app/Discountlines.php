<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discountlines extends Model
{
    protected $table='m_discounts_lines_t';
    protected $primaryKey='ar_discount_line_id';
	protected $foreignKey='ar_discount_hdr_id';
    protected $fillable=['ar_discount_line_id','ar_discount_hdr_id','discount_percentage','discount_days','comments'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
