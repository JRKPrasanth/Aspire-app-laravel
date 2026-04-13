<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class doctoraddresslines extends Model
{
    protected $table='app_doctors_adr_t';
	protected $primaryKey='doctors_adr_id';
	protected $foreignKey='doctor_id';
	protected $fillable=['doctor_address','concat_address','pin_code','country_id','state_id','city_id','area','created_at','created_by','updated_at','last_updated_by','location_id','organization_id','company_id'];
	public function getTableColumns() 
	{
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
