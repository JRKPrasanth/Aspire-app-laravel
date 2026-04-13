<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobreportsettings extends Model
{
    protected $table='jobreport_settings_t';
    protected $primaryKey='jobreport_settings_id';
    protected $fillable = [
          'pricelist_id','organization_id','company_id','location_id','created_by','created_at','last_updated_by','updated_at'
      ];
	 public function getTableColumns() 
	 {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
     }
}
