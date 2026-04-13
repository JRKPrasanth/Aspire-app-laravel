<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
     protected $table='i_consumable_hdr_t';
    protected $primaryKey='consumable_hdr_id';
	protected $fillable =['consumable_date','consumable_date','status','created_by','created_at','last_updated_by','updated_at','organization_id','company_id','location_id'];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
