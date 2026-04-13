<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productmapping extends Model
{
   protected $table='productmapping_hdr_tbl';
    protected $primaryKey='productmapping_id';
    protected $fillable = [
          'description','employee_id','beatmappinglines_id'
          ];
           public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
