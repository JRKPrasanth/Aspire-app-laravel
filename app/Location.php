<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
   
     protected $table='m_location_t';
    protected $primaryKey='location_id';
	protected $fillable =['location_id','location_name','location_code','location_type','country_id','state_id','city_id','active','company_id'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
