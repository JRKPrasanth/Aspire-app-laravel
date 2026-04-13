<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qualityproductspeclines extends Model
{
     protected $table='i_quality_product_specs_lines_t';
     protected $primaryKey='quality_product_specs_line_id';
     protected $foreignKey='quality_product_specs_hdr_id';
	 protected $fillable =['quality_product_specs_hdr_id','parameter','standard','comments'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
