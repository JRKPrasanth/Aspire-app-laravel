<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class distributormapping extends Model
{
   protected $table='distributormapping_hdr_tbl';
    protected $primaryKey='distributormapping_id';
    protected $fillable = [
          'description','employee_id','beatmappinglines_id'
          ];
           public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
