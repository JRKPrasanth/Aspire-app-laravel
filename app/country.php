<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class country extends Model
{ 
   protected $table = 'm_countries_t';
   protected $primaryKey ='country_id';	
   protected $fillable = ['country_name','active','created_by','last_updated_by','updated_at','created_at','location_id','organization_id','company_id'];	
}
