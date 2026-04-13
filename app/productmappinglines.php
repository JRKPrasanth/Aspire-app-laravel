<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productmappinglines extends Model
{
   protected $table ='productmapping_lines_tbl';
	protected $primaryKey ='productmappinglines_id';
         protected $foreignKey='productmapping_id';
	protected $fillable=['state_id','town_id','product_id','outlet_id','description'];
}
