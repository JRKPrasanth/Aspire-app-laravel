<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class state extends Model
{ 
   protected $table = 'm_states_t';
   protected $primaryKey ='state_id';	
   protected $fillable = ['state_name','state_code','state_code_no','country_id','active','created_by','last_updated_by','updated_at','created_at','location_id','organization_id','company_id'];	
}
