<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qualityproductspechdr extends Model
{
    protected $table='i_quality_product_specs_hdr_t';
    protected $primaryKey='quality_product_specs_hdr_id';
	protected $fillable =['quality_product_specs_hdr_id','product_id','quality_status'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
