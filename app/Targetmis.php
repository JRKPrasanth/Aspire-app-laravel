<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Targetmis extends Model
{
   protected $table='sd_targetname_t';
   protected $primaryKey='id';
   protected $fillable =['f_year','target_name','active','created_by','created_at','location_id'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}