<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qualityindent extends Model
{
    protected $table='w_quality_indent_hdr_t';
    protected $primaryKey='quality_indent_hdr_id';
    protected $fillable=['indent_name','indent_date','remarks'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
