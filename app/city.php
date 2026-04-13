<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class city extends Model
{ 
   protected $table = 'm_cities_t';
   protected $primaryKey ='city_id';	
   protected $fillable = ['city_name','country_id','state_id','created_by','last_updated_by','updated_at','created_at','location_id','organization_id','company_id'];	
}
