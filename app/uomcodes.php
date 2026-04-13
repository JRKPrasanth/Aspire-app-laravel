<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class uomcodes extends Model
{ 
   protected $table = 'm_uom_codes_t';
   protected $primaryKey ='uom_code_id';	
   protected $fillable = ['uom_code','code_meaning','active','created_by','last_updated_by','updated_at','created_at','location_id','organization_id','company_id'];	
}
