<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class distributormappinglines extends Model
{
   protected $table ='distributormapping_lines_tbl';
	protected $primaryKey ='distributormappinglines_id';
         protected $foreignKey='distributormapping_id';
	protected $fillable=['state_id','town_id','product_id','outlet_id','description'];
}
