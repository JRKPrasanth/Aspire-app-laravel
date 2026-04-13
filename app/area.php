<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class area extends Model
{
    protected $table='m_area_t';
	protected $primaryKey='area_id';
	protected $fillable =['city_id','state_id','country_id','area_name','teritory_type','created_by','updated_by','created_at','updated_at','organization_id','location_id','company_id'];
}
