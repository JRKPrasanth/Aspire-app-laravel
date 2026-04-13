<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lookuplines extends Model
{
   public $table='a_lookuplines_t';
	public $primaryKey='lookuplines_id';
	public $foreignKey='lookuphdr_id';
	public $fillable =['lookuplines_id','lookuphdr_id','lookup_code','lookup_meaning','sequence','active','lookup_type'];

}



